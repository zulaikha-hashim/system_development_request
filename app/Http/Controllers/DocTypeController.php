<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IntecSdrDocumentType;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;
class DocTypeController extends Controller
{
    public function index()
    {
        if (Auth::user()->role !== 'admin') {
            Auth::logout(); 
            return redirect()->route('login')->withErrors('Access Denied');
        }
           
        $user = Auth::user();

        if (request()->ajax()) {
            $intec_sdr_document_types = IntecSdrDocumentType::all();

            return DataTables::of($intec_sdr_document_types)
                ->addIndexColumn()
                ->addColumn('doc_type', function ($row) {
                    return $row->doc_type;
                })
                ->addColumn('doc_name', function ($row) {
                    return $row->doc_name;
                })
                ->addColumn('action', function ($row) {
                    return '
                        <a href="#" data-bs-target="#staticBackdrop" data-bs-toggle="modal" data-type-id="' . $row->type_id . '"
                            data-type="' . $row->doc_type . '"
                            data-name="' . $row->doc_name . '" class="btn btn-warning btn-sm me-2 edit-btn"><i class="fas fa-edit"></i></a>
                        <button type="button" class="btn btn-danger btn-sm delete-btn" data-type_id="' . $row->type_id . '">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    ';
                })
                ->rawColumns(['action']) 
                ->make(true);
        }
        return view('admin.document', compact('user'));
    }

    public function store(Request $request)
    {
        IntecSdrDocumentType::create([
            'doc_type' => $request->type,
            'doc_name' => $request->name
        ]);
    
        return response()->json(['success' => 'Form submitted successfully!']);
    }
    
 
    public function update(Request $request, $type_id)
    {
        // Find the document record by ID
        $document = IntecSdrDocumentType::findOrFail($type_id);
    
        // Update the document with the new data
        $document->update([
            'doc_type' => $request->type,
            'doc_name' => $request->name,
        ]);
    
        // Return a JSON response with the success message
        return response()->json(['success' => 'Document type has been updated.']);
    }

    public function destroy($type_id)
    {
        // Find the document by ID and delete it
        $document = IntecSdrDocumentType::findOrFail($type_id);
        $document->delete();
    
        // Return a JSON response
        return response()->json(['success' => 'Document has been deleted.']);
    }
    
}
