<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\IntecSdrAdmin;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;
use App\Models\User;  // Add the User model

class AdminController extends Controller
{

    public function index()
    {

        if (Auth::user()->role !== 'admin') {
            Auth::logout(); 
            return redirect()->route('login')->withErrors('Access Denied');
        }

        $user = Auth::user();
        
        if (request()->ajax()) {
            $intec_sdr_admins = IntecSdrAdmin::all();

            return DataTables::of($intec_sdr_admins)
                ->addIndexColumn()
                ->addColumn('admin_id', function ($row) {
                    return $row->admin_id;
                })
                ->addColumn('admin_ic', function ($row) {
                    return $row->admin_ic;
                })
                ->addColumn('admin_name', function ($row) {
                    return $row->admin_name;
                })
                ->addColumn('admin_email', function ($row) {
                    return $row->admin_email;
                })
                ->addColumn('action', function ($row) {
                    return '
                        <a href="#" data-bs-target="#staticBackdrop" data-bs-toggle="modal" data-admin-id="' . $row->admin_id . '"
                           data-ic="' . $row->admin_ic . '" data-name="' . $row->admin_name . '" data-email="' . $row->admin_email . '"
                           class="btn btn-warning btn-sm me-2 edit-btn"><i class="fas fa-edit"></i></a>
                    ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        
        return view('admin.admin', compact('user'));
    }

    public function store(Request $request)
    {
        if (IntecSdrAdmin::count() > 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'An admin already exists in the system.'
            ]);
        }
    
        $validatedData = $request->validate([
            'admin_ic' => 'required|string|max:12|unique:intec_sdr_admins,admin_ic',
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|string|max:50|unique:intec_sdr_admins,admin_email',
        ], [
            'admin_ic.required' => 'The IC field is required.',
            'admin_ic.unique' => 'The IC is already taken.',
            'admin_name.required' => 'The name field is required.',
            'admin_email.required' => 'The email field is required.',
            'admin_email.unique' => 'This email is already taken.',
        ]);
    
        $admin = IntecSdrAdmin::create($validatedData);
    
        User::create([
            'id' => $admin->admin_id,
            'name' => $admin->admin_name,
            'email' => $admin->admin_email,
            'password' => bcrypt($admin->admin_ic),
            'role' => 'admin',
            'user_id' => $admin->admin_id,
        ]);
    
        return response()->json([
            'status' => 'success',
            'message' => 'Admin created and registered successfully!'
        ]);
    }

    public function update(Request $request, $admin_id)
    {
        $admin = IntecSdrAdmin::find($admin_id);

        if (!$admin) {
            return redirect()->back()->with('error', 'Admin not found!');
        }

        $validatedData = $request->validate([
            'ic' => 'required|string|max:12|unique:intec_sdr_admins,admin_ic,' . $admin_id . ',admin_id',
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:50|unique:intec_sdr_admins,admin_email,' . $admin_id . ',admin_id',
        ], [
            'ic.required' => 'The IC field is required.',
            'ic.unique' => 'The IC is already taken.',
            'name.required' => 'The name field is required.',
            'email.required' => 'The email field is required.',
            'email.unique' => 'This email is already taken.',
        ]);
        
        $admin->admin_ic = $validatedData['ic'];
        $admin->admin_name = $validatedData['name'];
        $admin->admin_email = $validatedData['email'];
        $admin->save();

        $user = User::where('user_id', $admin->admin_id)->first();
        if ($user) {
            $user->password = bcrypt($validatedData['ic']);
            $user->name = $admin->admin_name;
            $user->email =  $admin->admin_email;
            $user->save();
        }

        return redirect()->route('admin.index')->with('success', 'Admin updated successfully, and password updated to the new NRIC.');
    }

}
