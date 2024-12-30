<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\IntecSdrDocument;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\IntecSdrApplication;
use App\Models\IntecSdrAdmin;
use App\Models\IntecSdrDeveloper;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;

class ApplicationController extends Controller
{

    public function applicationDevView()
    {
        
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

        if (request()->ajax()) {
            $applications = IntecSdrApplication::with('applicant', 'status')
                ->where('dev_id', $userDevId)
                ->whereHas('status', function ($query) {
                    $query->whereIn('status_name', ['Approved', 'In Progress', 'Completed']);
                })
                ->get();

            return DataTables::of($applications)
                ->addIndexColumn()
                ->addColumn('applicant_name', function ($row) {
                    return $row->applicant->applicant_name ?? 'N/A';
                })
                ->addColumn('status', function ($row) {
                    $statusClass = match ($row->status->status_id ?? '') {
                        8 => 'status-new',
                        7 => 'status-pending',
                        9 => 'status-approved',
                        11 => 'status-rejected',
                        10 => 'status-inprogress',
                        12 => 'status-completed',
                        default => '',
                    };
                    return "<span class='$statusClass'>{$row->status->status_name}</span>";
                })
                ->addColumn('applications_system_name', function ($row) {
                    return $row->applications_system_name ?? 'N/A';
                })
                ->addColumn('urgency', function ($row) {
                    return $row->applications_urgency ?? 'N/A';
                })
                ->addColumn('applications_deadline', function ($row) {
                    return $row->applications_deadline
                        ? \Carbon\Carbon::parse($row->applications_deadline)->format('d-m-Y')
                        : 'N/A';
                })

                ->addColumn('action', function ($row) {
                    if ($row->status->status_id == 12) {
                        return '<form action="' . route('completedDisplayD', $row->applications_id) . '" method="GET">
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-eye"></i></button>
                                </form>';
                    }
                    $url = route('inprogress', ['applications_id' => $row->applications_id]);
                    return '<a href="' . $url . '" class="btn btn-primary"><i class="fas fa-eye"></i></a>';
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }

        return view('developer.application', compact('approvedCount', 'inprogressCount', 'completedCount'));
    }

    public function applicationClientView()
    {

        if (request()->ajax()) {
            $user = Auth::user();

            // Retrieve applications for the logged-in user
            $intec_sdr_application = IntecSdrApplication::with('applicant', 'status')
                ->where('applicant_id', $user->user_id)  // Filter applications based on the logged-in user's applicant_id
                ->get();

            return DataTables::of($intec_sdr_application)
                ->addIndexColumn()
                ->addColumn('applicant_name', function ($row) {
                    return $row->applicant ? $row->applicant->applicant_name : 'N/A';
                })
                ->addColumn('applications_system_name', function ($row) {
                    return $row->applications_system_name;
                })
                ->addColumn('applications_system_desc', function ($row) {
                    return $row->applications_system_desc;
                })
                ->addColumn('status', function ($row) {
                    $statusClass = '';
                    $statusName = isset($row->status->status_name) ? $row->status->status_name : 'N/A';

                    switch ($row->status->status_id) {
                        case 8:
                            $statusClass = 'status-new';
                            break;
                        case 7:
                            $statusClass = 'status-pending';
                            break;
                        case 9:
                            $statusClass = 'status-approved';
                            break;
                        case 11:
                            $statusClass = 'status-rejected';
                            break;
                        case 10:
                            $statusClass = 'status-inprogress';
                            break;
                        case 12:
                            $statusClass = 'status-completed';
                            break;
                    }

                    return "<span class='$statusClass'>$statusName</span>";
                })
                ->addColumn('created_at', function ($row) {
                    return $row->created_at ? \Carbon\Carbon::parse($row->created_at)->format('d-m-Y') : 'N/A';
                })
                
                ->addColumn('action', function ($row) {
                    $statusMessage = '';
                    $actionUrl = '';
                    $deleteButton = '';

                    switch ($row->status->status_id) {
                        case 8: // New Application status
                            $actionUrl = route('inprogressC', $row->applications_id);
                            $deleteButton = '<button class="btn btn-danger delete-btn" data-application-id="' . $row->applications_id . '" onclick="deleteApplication(' . $row->applications_id . ')"> <i class="fas fa-trash-alt"></i></button>';
                            break;

                        case 7: // Pending status
                            $actionUrl = route('pendingC', $row->applications_id);
                            $deleteButton = '<button class="btn btn-danger delete-btn disabled" disabled> <i class="fas fa-trash-alt"></i></button>';
                            break;

                        case 9: // Approved status
                            $statusMessage = 'Your application was approved by the Admin.';
                            $deleteButton = '<button class="btn btn-danger delete-btn disabled" disabled> <i class="fas fa-trash-alt"></i></button>';
                            break;

                        case 11: // Rejected status
                            $statusMessage = 'Your application was rejected by the Admin.';
                            $deleteButton = '<button class="btn btn-danger delete-btn disabled" disabled> <i class="fas fa-trash-alt"></i></button>';
                            break;

                        case 10: // Under development status
                            $statusMessage = 'Your application is under development.';
                            $deleteButton = '<button class="btn btn-danger delete-btn disabled" disabled> <i class="fas fa-trash-alt"></i></button>';
                            break;

                        case 12: // Completed status
                            $actionUrl = route('completedC', $row->applications_id);
                            $deleteButton = '<button class="btn btn-danger delete-btn disabled" disabled> <i class="fas fa-trash-alt"></i></button>';
                            break;
                    }

                    $viewButton = '<a href="' . $actionUrl . '" class="btn btn-primary"> <i class="fas fa-eye"></i></a>';

                    // If there's a status message, add a form to show the status with SweetAlert
                    if ($statusMessage) {
                        return '<div class="d-inline-flex gap-2">
                                    <form action="javascript:void(0);" onclick="showStatusMessage(\'' . addslashes($statusMessage) . '\', ' . $row->applications_id . ')">
                                        <button type="button" class="btn btn-primary"> <i class="fas fa-eye"></i></button>
                                    </form>' . $deleteButton . '
                                </div>';
                    }

                    // Return action buttons for all other statuses
                    return '<div class="d-inline-flex gap-2">' . $viewButton . $deleteButton . '</div>';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('staff.application');
    }

    // ApplicationAdmin (blade)
    public function applicationAdminView()
    {
       

        $user = Auth::user();

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

        if (request()->ajax()) {
            $applications = IntecSdrApplication::with('applicant', 'status')->get();

            return DataTables::of($applications)
                ->addIndexColumn()
                ->addColumn('applicant_name', function ($row) {
                    return isset($row->applicant->applicant_name) ? $row->applicant->applicant_name : 'N/A';
                })
                ->addColumn('status', function ($row) {
                    $statusClass = '';
                    $statusName = isset($row->status->status_name) ? $row->status->status_name : 'N/A';

                    if ($row->status->status_id == 8) {
                        $statusClass = 'status-new';
                    } elseif ($row->status->status_id == 7) {
                        $statusClass = 'status-pending';
                    } elseif ($row->status->status_id == 9) {
                        $statusClass = 'status-approved';
                    } elseif ($row->status->status_id == 11) {
                        $statusClass = 'status-rejected';
                    } elseif ($row->status->status_id == 10) {
                        $statusClass = 'status-inprogress';
                    } elseif ($row->status->status_id == 12) {
                        $statusClass = 'status-completed';
                    }

                    return "<span class='$statusClass'>$statusName</span>";
                })
                ->addColumn('urgency', function ($row) {
                    return isset($row->applications_urgency) ? $row->applications_urgency : 'N/A';
                })
                ->addColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->setTimezone('Asia/Kuala_Lumpur')->format('d-m-Y h:i A');
                })
                ->addColumn('action', function ($row) {
                    $form = '<form action="';

                    if ($row->status->status_id == 7) {
                        $form .= route('pending', $row->applications_id);
                    } elseif ($row->status->status_id == 8) {
                        $form .= route('newapplication', $row->applications_id);
                    } elseif ($row->status->status_id == 9) {
                        $form .= route('approveDisplay', $row->applications_id);
                    } else if ($row->status->status_id == 11) {
                        $form .= route('rejectDisplay', $row->applications_id);
                    } else if ($row->status->status_id == 12) {
                        $form .= route('displayCompletedA', $row->applications_id);
                    } else if ($row->status_id == 10) {
                        $form .= route('inprogressA', $row->applications_id);
                    }

                    $form .= '" method="GET">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-eye"></i></button>
                              </form>';
                    return $form;
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }
        return view('admin.application', compact('newCount', 'pendingCount', 'approvedCount', 'rejectedCount', 'inprogressCount', 'completedCount'));
    }

    public function displayCompletedD(Request $request, $applications_id)
    {
        $intec_sdr_application = IntecSdrApplication::with('developer', 'status', 'applicant')->find($applications_id);

        if ($intec_sdr_application->status_id == 12) {
            $file = IntecSdrDocument::where('applications_id', $applications_id)->where('doc_type_id', '92')->first();
            $fileSR = IntecSdrDocument::where('applications_id', $applications_id)->where('doc_type_id', '52')->first();
            $fileSF = IntecSdrDocument::where('applications_id', $applications_id)->where('doc_type_id', '93')->first();
            $fileERD = IntecSdrDocument::where('applications_id', $applications_id)->where('doc_type_id', '50')->first();
            $fileSIT = IntecSdrDocument::where('applications_id', $applications_id)->where('doc_type_id', '94')->first();
            $fileUAT = IntecSdrDocument::where('applications_id', $applications_id)->where('doc_type_id', '53')->first();
            $fileUM = IntecSdrDocument::where('applications_id', $applications_id)->where('doc_type_id', '51')->first();

            return view('developer.completed', compact('intec_sdr_application', 'file', 'fileSR', 'fileSF', 'fileERD', 'fileSIT', 'fileUAT', 'fileUM', 'applications_id'))
                ->with('success', 'Application is completed. You can view the uploaded documents.');
        }

        $this->handleFileUploads($request, $applications_id);
        $this->updateApplicationStatusAndSendEmails($applications_id);

        $file = IntecSdrDocument::where('applications_id', $applications_id)->where('doc_type_id', '92')->first();
        $fileSR = IntecSdrDocument::where('applications_id', $applications_id)->where('doc_type_id', '52')->first();
        $fileSF = IntecSdrDocument::where('applications_id', $applications_id)->where('doc_type_id', '93')->first();
        $fileERD = IntecSdrDocument::where('applications_id', $applications_id)->where('doc_type_id', '50')->first();
        $fileSIT = IntecSdrDocument::where('applications_id', $applications_id)->where('doc_type_id', '94')->first();
        $fileUAT = IntecSdrDocument::where('applications_id', $applications_id)->where('doc_type_id', '53')->first();
        $fileUM = IntecSdrDocument::where('applications_id', $applications_id)->where('doc_type_id', '51')->first();

        return view('developer.completed', compact('intec_sdr_application', 'file', 'fileSR', 'fileSF', 'fileERD', 'fileSIT', 'fileUAT', 'fileUM', 'applications_id'))
            ->with('success', 'Files uploaded successfully!');
    }

    public function handleFileUploads(Request $request, $applications_id)
    {
        $documents = [
            'sr' => ['doc_type_id' => '52', 'path' => 'system-requirement'],
            'sf' => ['doc_type_id' => '93', 'path' => 'system-flowchart'],
            'erd' => ['doc_type_id' => '50', 'path' => 'entity-relationship-diagram'],
            'sit' => ['doc_type_id' => '94', 'path' => 'system-integration-testing'],
            'uat' => ['doc_type_id' => '53', 'path' => 'user-accecptance-testing'],
            'um' => ['doc_type_id' => '51', 'path' => 'user-manual']
        ];

        foreach ($documents as $fileKey => $docDetails) {
            $file = $request->file($fileKey);
            if (isset($file)) {
                $originalName = time() . $file->getClientOriginalName();
                $file->storeAs('/' . $docDetails['path'], $originalName);
                IntecSdrDocument::create([
                    'applications_id' => $applications_id,
                    'doc_type_id' => $docDetails['doc_type_id'],
                    'doc_name' => $originalName,
                    'doc_web_path' => $docDetails['path'] . "/" . $originalName,
                    'doc_size' => $file->getSize()
                ]);
            }
        }
    }

    public function updateApplicationStatusAndSendEmails($applications_id)
    {
        $intec_sdr_application = IntecSdrApplication::with('developer', 'status', 'applicant')->find($applications_id);
        $intec_sdr_application->status_id = 12;
        $intec_sdr_application->save();

        $applicant = [
            'receiver' => 'Dear ' . $intec_sdr_application->applicant->applicant_name,
            'email' => 'We are pleased to inform you that your application regarding requesting a ' .
                $intec_sdr_application->applications_system_name . ' system on ' .
                $intec_sdr_application->created_at->format('d-m-y') .
                ' has been successfully completed. Additionally, all the required documents have been uploaded and are available in the system.',
            'footer' => 'Please log in to the system to view the attached document. Should you have any questions or need assistance, feel free to reach out to us.',
        ];

        Mail::send('emails.email', $applicant, function ($message) use ($intec_sdr_application) {
            $message->subject('SYSTEM DEVELOPMENT COMPLETED');
            $message->from($intec_sdr_application->developer->dev_email);
            $message->to($intec_sdr_application->applicant->applicant_email);
        });

        $admin = IntecSdrAdmin::first();
        $adminEmail = [
            'receiver' => 'Dear ' . $admin->admin_name,
            'email' => 'Please be informed that the ' . $intec_sdr_application->applications_system_name . ' system application for ' .
                $intec_sdr_application->applicant_id . ' ' . $intec_sdr_application->applicant->applicant_name . ' has been completed, and all the required documents have been attached to the system.',
            'footer' => 'Please log in to the system to view the attached document.',
        ];

        Mail::send('emails.email', $adminEmail, function ($message) use ($intec_sdr_application) {
            $message->subject('SYSTEM DEVELOPMENT COMPLETED');
            $message->from($intec_sdr_application->developer->dev_email);
            $message->to('admin@intec.com');
        });
    }

    public function comletedDfinal(Request $request, $applications_id)
    {
        $application = IntecSdrApplication::find($applications_id);

        $fileSR = IntecSdrDocument::where('applications_id', $applications_id)->where('doc_type_id', '52')->first();
        $fileSF = IntecSdrDocument::where('applications_id', $applications_id)->where('doc_type_id', '93')->first();
        $fileERD = IntecSdrDocument::where('applications_id', $applications_id)->where('doc_type_id', '50')->first();
        $fileSIT = IntecSdrDocument::where('applications_id', $applications_id)->where('doc_type_id', '94')->first();
        $fileUAT = IntecSdrDocument::where('applications_id', $applications_id)->where('doc_type_id', '94')->first();
        $fileUM = IntecSdrDocument::where('applications_id', $applications_id)->where('doc_type_id', '51')->first();

        return view('developer.completed', compact('application', 'fileSR', 'fileSF', 'fileERD', 'fileSIT', 'fileUAT', 'fileUM'));
    }

    public function fileFinal($type, $id)
    {
        $file = IntecSdrDocument::find($id);

        if (!$file) {
            \Log::error("File with ID {$id} not found.");
            abort(404); // Return a 404 response
        }

        $filePath = $file->doc_web_path;
        if (!Storage::exists($filePath)) {
            \Log::error("File not found in storage at path: {$filePath}");
            abort(404); // Return a 404 response
        }

        return Storage::response($filePath);
    }

    public function displayCompletedA(Request $request, $applications_id)
    {
        $intec_sdr_application = IntecSdrApplication::find($applications_id);

        $file = IntecSdrDocument::where('applications_id', $applications_id)->where('doc_type_id', '92')->first();
        $fileSR = IntecSdrDocument::where('applications_id', $applications_id)->where('doc_type_id', '52')->first();
        $fileSF = IntecSdrDocument::where('applications_id', $applications_id)->where('doc_type_id', '93')->first();
        $fileERD = IntecSdrDocument::where('applications_id', $applications_id)->where('doc_type_id', '50')->first();
        $fileSIT = IntecSdrDocument::where('applications_id', $applications_id)->where('doc_type_id', '94')->first();
        $fileUAT = IntecSdrDocument::where('applications_id', $applications_id)->where('doc_type_id', '94')->first();
        $fileUM = IntecSdrDocument::where('applications_id', $applications_id)->where('doc_type_id', '51')->first();

        // Corrected compact usage
        return view('admin.completed', compact('intec_sdr_application', 'applications_id', 'file', 'fileSR', 'fileSF', 'fileERD', 'fileSIT', 'fileUAT', 'fileUM'));
    }

    // view button (from applicationAdmin) > new application page
    public function new($applications_id)
    {
        $intec_sdr_application = IntecSdrApplication::where('applications_id', $applications_id)->firstOrFail();
        $file = IntecSdrDocument::where('applications_id', $applications_id)->where('doc_type_id', '92')->first();

        return view('admin.newapplication', compact('intec_sdr_application', 'file'));
    }

    public function file($applications_id)
    {
        $file = IntecSdrDocument::where('applications_id', $applications_id)->where('doc_type_id', '92')->first();
        return Storage::response($file->doc_web_path);
    }

    public function approveDisplay(Request $request, $applications_id)
    {

        $intec_sdr_application = IntecSdrApplication::where('applications_id', $applications_id)->first();
        $file = IntecSdrDocument::where('applications_id', $applications_id)->where('doc_type_id', '92')->first();
        return view('admin.approve', compact('intec_sdr_application', 'file'));
    }

    public function rejectDisplay(Request $request, $applications_id)
    {
        $intec_sdr_application = IntecSdrApplication::where('applications_id', $applications_id)->first();
        $file = IntecSdrDocument::where('applications_id', $applications_id)->where('doc_type_id', '92')->first();

        return view('admin.rejected', compact('intec_sdr_application', 'file'));
    }

    // new application page > confirm meeting (radio button) > pending page
    public function confirmMeeting(Request $request, $applications_id)
    {
        $intec_sdr_application = IntecSdrApplication::findOrFail($applications_id);
        $request->validate(['meetingDate' => 'required',]);
        $intec_sdr_application->date_confirm = $request->input('meetingDate');
        $intec_sdr_application->status_id = 7;
        $intec_sdr_application->save();

        $data = [

            'receiver' => 'Dear ' . $intec_sdr_application->applicant->applicant_name,
            'email' => 'We are pleased to inform you that your application has been successfully received and processed. We would like to confirm that a meeting has been scheduled. The meeting will be conducted as per the following details: ',
            'venue' => 'Meeting Room, Information Technology Office',
            'date' => date('d-m-y', strtotime($intec_sdr_application->date_confirm)),
            'time' => $intec_sdr_application->applications_time,
            'footer' => 'Please feel free to reach out if you have any questions or need further clarification. We look forward to your participation and to a productive discussion.',
        ];

        Mail::send('emails.meeting', $data, function ($message) use ($intec_sdr_application) {

            $message->subject('MEETING SCHEDULED');
            $message->from('admin@intec.com');
            $message->to($intec_sdr_application->applicant->applicant_email);

        });

        return redirect()->route('pending', $applications_id)->with('success', 'Meeting date confirmed successfully.');
    }

    // pending page > button approve & reject
    public function pending($applications_id)
    {

        $intec_sdr_application = IntecSdrApplication::where('applications_id', $applications_id)->first();
        $file = IntecSdrDocument::where('applications_id', $applications_id)->where('doc_type_id', '92')->first();
        $developers = IntecSdrDeveloper::all();

        return view('admin.pending', compact('intec_sdr_application', 'developers', 'file'));
    }

    // approve > approve page
    public function approve(Request $request, $applications_id)
    {
        $deadline = \Carbon\Carbon::createFromFormat('d-m-Y', $request->input('deadline'))->format('Y-m-d');

        // Find and update the application
        $intec_sdr_application = IntecSdrApplication::findOrFail($applications_id);
        $intec_sdr_application->status_id = 9;  // Assuming 9 corresponds to 'Approved'
        $intec_sdr_application->dev_id = $request->input('assignDev');  // Save the developer ID
        $intec_sdr_application->applications_deadline = $deadline;  // Save the converted date
        $intec_sdr_application->save();
        $file = IntecSdrDocument::where('applications_id', $applications_id)->where('doc_type_id', '92')->first();

        $applicant = [

            'receiver' => 'Dear ' . $intec_sdr_application->applicant->applicant_name,
            'email' => 'We are pleased to inform you that your application has been approved. A developer  ' . $intec_sdr_application->developer->dev_name . ' has been assigned as the person in charge (PIC) of your system development request to ensure a smooth process.' .
                'Your application will now proceed to the next phase of development. Once the system is completed, we will notify you to initiate the testing phase.',
            'footer' => 'If you have any questions or require further assistance during this process, please do not hesitate to contact us. We appreciate your trust in our team and look forward to delivering a successful outcome.',
        ];

        Mail::send('emails.email', $applicant, function ($message) use ($intec_sdr_application) {

            $message->subject('APPLICATION APPROVED');
            $message->from('admin@intec.com');
            $message->to($intec_sdr_application->applicant->applicant_email);

        });

        $developer = [

            'receiver' => 'Dear ' . $intec_sdr_application->developer->dev_name,
            'email' => 'We are pleased to inform you that you have been assigned as the person in charge of developing a system for ' . $intec_sdr_application->applicant->applicant_name . '. Your expertise and dedication will play a key role in ensuring the successful completion of this project.' .
                'The deadline for the project has been set for ' . $intec_sdr_application->applications_deadline . '. Please ensure that all deliverables are completed within the specified timeline.',
            'footer' => 'Please log in to the system for more details. Should you require any further details or support, feel free to reach out to us. We are confident in your ability to handle this project with professionalism and excellence.',
        ];

        Mail::send('emails.email', $developer, function ($message) use ($intec_sdr_application) {

            $message->subject('SYSTEM DEVELOPMENT ASSIGNED');
            $message->from('admin@intec.com');
            $message->to($intec_sdr_application->developer->dev_email);

        });

        // Return to the view with the updated application
        return view('admin.approve', compact('intec_sdr_application', 'file'));
    }

    public function inprogress($applications_id)
    {
        $intec_sdr_application = IntecSdrApplication::where('applications_id', $applications_id)->first();

        $file = IntecSdrDocument::where('applications_id', $applications_id)->where('doc_type_id', '92')->first();

        $intec_sdr_application->status_id = 10;
        $intec_sdr_application->updated_at = now();
        $intec_sdr_application->save();

        return view('developer.inprogress', compact('intec_sdr_application', 'applications_id', 'file'));
    }
    
    // reject > rejected page
    public function reject(Request $request, $applications_id)
    {
        // Find the application by ID
        $intec_sdr_application = IntecSdrApplication::findOrFail($applications_id);
        $file = IntecSdrDocument::where('applications_id', $applications_id)->where('doc_type_id', '92')->first();

        // Validate the 'remark' field
        $request->validate(['remark' => 'required']);

        // Save the rejection remark
        $intec_sdr_application->applications_remark = $request->input('remark');

        // Update the status to 'rejected'
        $intec_sdr_application->status_id = 11;
        $intec_sdr_application->save();

        $data = [

            'receiver' => 'Dear ' . $intec_sdr_application->applicant->applicant_name,
            'email' => 'We regret to inform you that your application has been rejected due to specific reasons. We sincerely apologize for any inconvenience this may have caused.',
            'footer' => 'To view the remarks regarding the rejection, please log in to the system for further details.',
        ];

        Mail::send('emails.email', $data, function ($message) use ($intec_sdr_application) {

            $message->subject('APPLICATION REJECTED');
            $message->from('admin@intec.com');
            $message->to($intec_sdr_application->applicant->applicant_email);

        });

        return view('admin.rejected', compact('intec_sdr_application', 'file'));
    }

    public function completedC($applications_id)
    {
        $intec_sdr_application = IntecSdrApplication::where('applications_id', $applications_id)->first();

        $file = IntecSdrDocument::where('applications_id', $applications_id)->where('doc_type_id', '92')->first();
        $fileSR = IntecSdrDocument::where('applications_id', $applications_id)->where('doc_type_id', '52')->first();
        $fileSF = IntecSdrDocument::where('applications_id', $applications_id)->where('doc_type_id', '93')->first();
        $fileERD = IntecSdrDocument::where('applications_id', $applications_id)->where('doc_type_id', '50')->first();
        $fileSIT = IntecSdrDocument::where('applications_id', $applications_id)->where('doc_type_id', '94')->first();
        $fileUAT = IntecSdrDocument::where('applications_id', $applications_id)->where('doc_type_id', '94')->first();
        $fileUM = IntecSdrDocument::where('applications_id', $applications_id)->where('doc_type_id', '51')->first();

        return view('staff.completed', compact('intec_sdr_application', 'applications_id', 'file', 'fileSR', 'fileSF', 'fileERD', 'fileUAT', 'fileSIT', 'fileUM'));
    }

    // public function newapplicationC(Request $request, $applications_id)
    // {
    //     // Modify column name based on actual primary key column in database
    //     $intec_sdr_application = IntecSdrApplication::where('applications_id', $applications_id)->first();
    //     $file = IntecSdrDocument::where('applications_id', $applications_id)->where('doc_type_id', '92')->first();
    //     return view('newapplicationC', compact('intec_sdr_application', 'file'));
    // }

    public function pendingC($applications_id)
    {
        // Fetch the record using the correct primary key column name
        $intec_sdr_application = IntecSdrApplication::where('applications_id', $applications_id)->first();

        $file = IntecSdrDocument::where('applications_id', $applications_id)->where('doc_type_id', '92')->first();
        return view('staff.pending', compact('intec_sdr_application', 'file'));
    }

    public function rejectedC($applications_id)
    {
        $intec_sdr_application = IntecSdrApplication::find($applications_id);

        $file = IntecSdrDocument::where('applications_id', $applications_id)->where('doc_type_id', '92')->first();
        return view('staff.rejected', compact('intec_sdr_application', 'file'));
    }

    public function delete($applications_id)
    {

        $application = IntecSdrApplication::where('applications_id', $applications_id)->first();

        if (!$application) {
            return response()->json(['success' => false, 'message' => 'Application not found.'], 404);
        }

        $application->delete();

        return response()->json(['success' => true, 'message' => 'Application deleted successfully.']);
    }

    public function inprogressA($applications_id)
    {

        $intec_sdr_application = IntecSdrApplication::where('applications_id', $applications_id)->first();
        $file = IntecSdrDocument::where('applications_id', $applications_id)->where('doc_type_id', '92')->first();

        return view('inprogressA', compact('intec_sdr_application', 'applications_id', 'file'));
    }

    public function inprogressC($applications_id)
    {

        $intec_sdr_application = IntecSdrApplication::where('applications_id', $applications_id)->first();
        $file = IntecSdrDocument::where('applications_id', $applications_id)->where('doc_type_id', '92')->first();

        return view('inprogressC', compact('intec_sdr_application', 'applications_id', 'file'));
    }
}
