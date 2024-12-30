<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\IntecSdrApplicant;
use App\Models\IntecSdrApplication;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class dashboardController extends Controller
{

    public function dashboardA()
    {
        if (Auth::user()->role !== 'admin') {
            Auth::logout(); 
            return redirect()->route('login')->withErrors('Access Denied');
        }

        // Fetch application counts
        $newCount = IntecSdrApplication::whereHas('status', function ($query) {
            $query->where('status_name', 'New Application');
        })->count();

        $pendingCount = IntecSdrApplication::whereHas('status', function ($query) {
            $query->where('status_name', 'Pending');
        })->count();

        $approvedCount = IntecSdrApplication::whereHas('status', function ($query) {
            $query->where('status_name', 'Approved');
        })->count();

        $rejectedCount = IntecSdrApplication::whereHas('status', function ($query) {
            $query->where('status_name', 'Rejected');
        })->count();

        $inprogressCount = IntecSdrApplication::whereHas('status', function ($query) {
            $query->where('status_name', 'In Progress');
        })->count();

        // Add completedCount
        $completedCount = IntecSdrApplication::whereHas('status', function ($query) {
            $query->where('status_name', 'Completed');
        })->count();

        // Prevent caching the dashboard page after logout
        return response()
            ->view('admin.dashboard', compact('newCount', 'pendingCount', 'approvedCount', 'rejectedCount', 'inprogressCount', 'completedCount'))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, proxy-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }


    public function dashboardD()
    {
        if (Auth::user()->role !== 'developer') {
            Auth::logout(); 
            return redirect()->route('login')->withErrors('Access Denied');
        }

        $userDevId = auth()->user()->user_id;

        $approvedCount = IntecSdrApplication::where('dev_id', $userDevId)
            ->whereHas('status', function ($query) {
                $query->where('status_name', 'Approved');
            })->count();

        $inprogressCount = IntecSdrApplication::where('dev_id', $userDevId)
            ->whereHas('status', function ($query) {
                $query->where('status_name', 'In Progress');
            })->count();

        $completedCount = IntecSdrApplication::where('dev_id', $userDevId)
            ->whereHas('status', function ($query) {
                $query->where('status_name', 'Completed');
            })->count();

        // Fetch application deadlines
        $applicationDeadlines = IntecSdrApplication::where('dev_id', $userDevId)
            ->select('applications_system_name', 'applications_deadline' , 'applicant_id' , 'applications_id')
            ->get();

        // Pass application deadlines to the view
        return response()
            ->view('developer.dashboard', compact('approvedCount', 'inprogressCount', 'completedCount', 'applicationDeadlines'))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, proxy-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }


    public function dashboardC()
    {
        if (Auth::user()->role !== 'staff') {
            Auth::logout(); 
            return redirect()->route('login')->withErrors('Access Denied');
        }
        
        $user = auth()->user();
        $userApplicantId = $user->user_id; 
    
        // Get application meetings as before
        $applicationMeeting = IntecSdrApplication::where('applicant_id', $userApplicantId)
            ->select('applications_id', 'applications_system_name', 'date_confirm', 'admin_id', 'applications_time')
            ->get();
    
        // Load related data
        $user->load(['applicant.applications.status']);
    
        // Get all applications or an empty collection if no applicant exists
        $applications = $user->applicant ? $user->applicant->applications : collect();
    
        // Filter out applications with 'Rejected' status
        $validApplications = $applications->filter(function ($application) {
            return $application->status && $application->status->status_name !== 'Completed' && $application->status->status_name !== 'Rejected';
        });
    
        // Sort by created_at to get the oldest application
        $oldestApplication = $validApplications->sortBy('created_at')->first();
    
        // Fallback to the first application if no valid (non-rejected, non-completed) applications exist
        if (!$oldestApplication && $applications->isNotEmpty()) {
            $oldestApplication = $applications->sortBy('created_at')->first();
        }
    
        // Define a mapping of statuses to progress percentages
        $statusProgressMap = [
            'New Application' => 0,
            'Pending' => 25,
            'Approved' => 50,
            'In Progress' => 75,
            'Completed' => 100,
        ];
    
        // Get the status of the oldest application
        $statusName = $oldestApplication && $oldestApplication->status
            ? $oldestApplication->status->status_name
            : 'New Application'; // Default to 'New Application' if no valid application found
    
        // Determine the progress percentage based on the status
        $progressPercentage = $statusProgressMap[$statusName] ?? 0; // Default to 0 if status is unknown
    
        // Define the progress bar color class based on the progress
        $progressClass = 'info'; // Default color
        if ($progressPercentage >= 75) {
            $progressClass = 'success';
        } elseif ($progressPercentage >= 50) {
            $progressClass = 'warning';
        } elseif ($progressPercentage > 0) {
            $progressClass = 'danger';
        }
    
        $userDevId = auth()->user()->user_id;
    
        // Count In Progress and Completed applications, ignoring 'Rejected' applications
        $inProgressCount = IntecSdrApplication::where('applicant_id', $userApplicantId)
            ->whereHas('status', function ($query) {
                $query->where('status_name', 'In Progress');
            })->count();
    
        $completedCount = IntecSdrApplication::where('applicant_id', $userApplicantId)
            ->whereHas('status', function ($query) {
                $query->where('status_name', 'Completed');
            })->count();
    
        return view('staff.dashboard', compact('applicationMeeting'), [
            'user' => $user,
            'applicant' => $user->applicant,
            'applications' => $applications,
            'progressPercentage' => $progressPercentage,
            'progressClass' => $progressClass,
            'statusName' => $statusName,
            'inProgressCount' => $inProgressCount,
            'completedCount' => $completedCount,
        ]);
    }

    public function updateApplicantDetails(Request $request)
    {
        $request->validate([
            'applicant_ic' => 'required|string|max:20',
            'applicant_phone' => 'required|string|max:15',
            'applicant_depart' => 'required|string|max:100',
        ]);

        $user = Auth::user();
        $applicant = $user->applicant;

        if ($applicant) {
            $applicant->update([
                'applicant_ic' => $request->applicant_ic,
                'applicant_phone' => $request->applicant_phone,
                'applicant_depart' => $request->applicant_depart,
            ]);

            return redirect()->back()->with('success', 'Personal details updated successfully!');
        } else {
            return redirect()->back()->withErrors('Unable to update details. Please try again later.');
        }
    }
}
