<?php

namespace App\Http\Controllers;

use App\Models\IntecSdrStatusApplication;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;

class StatusController extends Controller
{
    public function index()
    {

        if (Auth::user()->role !== 'admin') {
            Auth::logout(); 
            return redirect()->route('login')->withErrors('Access Denied');
        }

        if (request()->ajax()) {
            $intec_sdr_status_applications = IntecSdrStatusApplication::all();

            return DataTables::of($intec_sdr_status_applications)
                ->addIndexColumn()
                ->addColumn('status_name', function ($row) {
                    return $row->status_name;
                })
                ->addColumn('action', function ($row) {
                    return '
                    <a href="#" data-bs-target="#staticBackdrop" data-bs-toggle="modal" data-status-id="' . $row->status_id . '"
                        data-name="' . $row->status_name . '" class="btn btn-warning btn-sm me-2 edit-btn"><i class="fas fa-edit"></i></a>
                    <button type="button" class="btn btn-danger btn-sm delete-btn" data-status-id="' . $row->status_id . '">
                    <i class="fas fa-trash-alt"></i>
                </button>
            ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.status');
    }

    public function store(Request $request)
    {
        // Validate request
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
    
        // Create a new status entry
        IntecSdrStatusApplication::create([
            'status_name' => $request->name,
        ]);
    
        return response()->json(['success' => 'Form submitted successfully!']);
    }
    

    public function update(Request $request, $id)
    {
        $status = IntecSdrStatusApplication::findOrFail($id);
    
        $status->update(['status_name' => $request->name]);
    
        return response()->json(['success' => 'Status updated successfully.']);
    }
    
    public function destroy($status_id)
    {
        $status = IntecSdrStatusApplication::find($status_id);
        $status->delete();
    
        return response()->json(['success' => 'Status deleted successfully.']);
    }
    
}
