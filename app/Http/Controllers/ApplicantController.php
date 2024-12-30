<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\IntecSdrApplicant;
use App\Models\IntecSdrApplication;
use App\Models\IntecSdrAdmin;
use App\Models\IntecSdrDocument;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Yajra\DataTables\DataTables;
use App\Models\User;

class ApplicantController extends Controller
{
    use SoftDeletes;

    public function index()
    {
        $user = Auth::user();

        // if (Auth::user()->role !== 'Staff') {
        //     return redirect()->route('login')->withErrors('Access Denied');
        // }

        $applicant = IntecSdrApplicant::where('applicant_id', $user->user_id)->first();

        return view('staff.applicant', compact('applicant'));
    }

    public function applicantStore(Request $request)
    {
        $user = Auth::user();
        $applicant = IntecSdrApplicant::where('applicant_id', $user->user_id)->first();

        $applicant->update([
            'applicant_ic' => $request->applicant_ic,
            'applicant_phone' => $request->applicant_phone,
            'applicant_depart' => $request->applicant_depart,
        ]);

        $applications_date1 = Carbon::createFromFormat('d/m/Y', $request->applications_date1)->format('Y-m-d');
        $applications_date2 = Carbon::createFromFormat('d/m/Y', $request->applications_date2)->format('Y-m-d');
        $applications_date3 = Carbon::createFromFormat('d/m/Y', $request->applications_date3)->format('Y-m-d');

        $application = IntecSdrApplication::create([
            'applicant_id' => $applicant->applicant_id,
            'status_id' => '8',
            'admin_id' => 'A0001',
            'applications_system_name' => $request->applications_system_name,
            'applications_system_desc' => $request->applications_system_desc,
            'applications_urgency' => $request->applications_urgency,
            'applications_date1' => $applications_date1,
            'applications_date2' => $applications_date2,
            'applications_date3' => $applications_date3,
            'applications_time' => $request->applications_time,
        ]);

        // Handle document upload
        if ($request->hasFile('applications_document')) {
            $file = $request->file('applications_document');
            $originalName = time() . $file->getClientOriginalName();
            $file->storeAs('/document', $originalName);

            IntecSdrDocument::create([
                'applications_id' => $application->applications_id,
                'doc_type_id' => '92',
                'doc_name' => $originalName,
                'doc_web_path' => "document/" . $originalName,
                'doc_size' => $file->getSize()
            ]);
        }

        $admin = IntecSdrAdmin::first();

        $data = [

            'receiver' => 'Dear ' . $admin->admin_name,
            'email' => 'You have received a new application ID ' . $application->applications_id . ' on ' . date(' j F Y ', strtotime(Carbon::now()->toDateTimeString())) . 'by ' . $applicant->applicant_id . ' ' . $applicant->applicant_name,
            'footer' => 'Please log in to the system to view the full application details.',
        ];

        Mail::send('emails.email', $data, function ($message) use ($admin, $applicant) {

            $message->subject('NEW APPLICATION');
            $message->from('noreply@intec.com');
            $message->to($admin->admin_email);

        });

        return redirect()->back()->with('success', 'Form submitted successfully!');
    }

    public function staff(Request $request)
    {
        $user = Auth::user();
        
        // if (Auth::user()->role !== 'admin') {
        //     Auth::logout(); 
        //     return redirect()->route('login')->withErrors('Access Denied');
        // }
        
        if ($request->ajax()) {
            $staff = IntecSdrApplicant::all();

            return DataTables::of($staff)
                ->addIndexColumn()
                ->addColumn('applicant_id', function ($row) {
                    return $row->applicant_id;
                })
                ->addColumn('applicant_ic', function ($row) {
                    return $row->applicant_ic;
                })
                ->addColumn('applicant_name', function ($row) {
                    return $row->applicant_name;
                })
                ->addColumn('applicant_email', function ($row) {
                    return $row->applicant_email;
                })
                ->addColumn('applicant_de[art', function($row){
                    return $row->applicant_depart;
                })
                ->addColumn('action', function ($row) {
                    return '
                            
                            <button type="button" class="btn btn-danger btn-sm delete-btn" 
                                data-applicant-id="' . $row->applicant_id . '">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.staff', compact('user'));
    }

    public function destroy($applicant_id)
    {
        $staff = IntecSdrApplicant::find($applicant_id);

        $user = User::where('user_id', $staff->applicant_id)->first();
           
        if ($user) {
                $user->delete();
            }

            $staff->delete();

            return response()->json(['success' => 'Staff data deleted successfully.']);
    }
}
