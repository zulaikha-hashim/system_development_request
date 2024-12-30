<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\IntecSdrDeveloper;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;
use App\Models\User;  // Add the User model

class DeveloperController extends Controller
{

    public function index()
    {
    
        
        if (request()->ajax()) {
            $intec_sdr_developers = IntecSdrDeveloper::all();
    
            return DataTables::of($intec_sdr_developers)
                ->addIndexColumn()
                ->addColumn('dev_id', function ($row) {
                    return $row->dev_id;
                })
                ->addColumn('dev_ic', function ($row) {
                    return $row->dev_ic;
                })
                ->addColumn('dev_name', function ($row) {
                    return $row->dev_name;
                })
                ->addColumn('dev_email', function ($row) {
                    return $row->dev_email;
                })
                ->addColumn('action', function ($row) {
                    return '
                        <a href="#" data-bs-target="#staticBackdrop" data-bs-toggle="modal" data-dev-id="' . $row->dev_id . '"
                            data-ic="' . $row->dev_ic . '"
                            data-name="' . $row->dev_name . '" 
                            data-email="' . $row->dev_email . '"
                            class="btn btn-warning btn-sm me-2 edit-btn">
                            <i class="fas fa-edit"></i></a>
                        
                        <button type="button" class="btn btn-danger btn-sm delete-btn" data-dev-id="' . $row->dev_id . '">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    
        return view('admin.developer');
    }
    
    public function developerView()
    {
        $intec_sdr_application = IntecSdrDeveloper::all();
        return view('developerView', compact('intec_sdr_application'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'dev_ic' => 'required|string|max:12|unique:intec_sdr_developers,dev_ic',
            'dev_name' => 'required|string|max:255',
            'dev_email' => 'required|email|max:50|unique:intec_sdr_developers,dev_email',
        ], [
            'dev_ic.required' => 'The IC field is required.',
            'dev_ic.unique' => 'The IC is already taken.',
            'dev_name.required' => 'The name field is required.',
            'dev_email.required' => 'The email field is required.',
            'dev_email.unique' => 'This email is already taken.',
        ]);
    
        // Create the developer record
        $developer = IntecSdrDeveloper::create($validatedData);
    
        // Create the corresponding user record
        \App\Models\User::create([
            'name' => $developer->dev_name,
            'email' => $developer->dev_email,
            'password' => bcrypt($developer->dev_ic),
            'role' => 'developer',
            'user_id' => $developer->dev_id, // Ensure this column exists in the `users` table
        ]);
    
        return redirect()->back()->with('success', 'Developer registered and user created successfully!');
    }

    public function update(Request $request, string $dev_id)
    {
        $developer = IntecSdrdeveloper::find($dev_id);

        if (!$developer) {
            return redirect()->back()->with('error', 'developer not found!');
        }

        $validatedData = $request->validate([
            'ic' => 'required|string|max:12|unique:intec_sdr_developers,dev_ic,' . $dev_id . ',dev_id',
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:50|unique:intec_sdr_developers,dev_email,' . $dev_id . ',dev_id',
        ], [
            'ic.required' => 'The IC field is required.',
            'ic.unique' => 'The IC is already taken.',
            'name.required' => 'The name field is required.',
            'email.required' => 'The email field is required.',
            'email.unique' => 'This email is already taken.',
        ]);


       
        $developer->dev_ic = $validatedData['ic'];
        $developer->dev_name = $validatedData['name'];
        $developer->dev_email = $validatedData['email'];
        $developer->save();

        $user = User::where('user_id', $developer->dev_id)->first();
        if ($user) {
            $user->password = bcrypt($validatedData['ic']);
            $user->name =  $developer->dev_name;
            $user->email =  $developer->dev_email;
            $user->save();
        }

        return redirect()->route('developer.index')->with('success', 'Admin updated successfully, and password updated to the new NRIC.');
        
    }

    public function destroy($dev_id)
    {
        $developer = IntecSdrDeveloper::find($dev_id);
        $user = User::where('user_id', $developer->dev_id)->first();
        if ($user) {
            $user->delete();
        }
    
        $developer->delete();
    
        return response()->json(['success' => 'Developer and associated login data deleted successfully.']);
    }

}
