<?php

namespace App\Http\Controllers;

use App\Models\IntecSdrApplication;
use PDF;

class ReportController extends Controller
{
    public function generateReport($applications_id)
    {
        $application = IntecSdrApplication::with('applicant','status', 'developer')->find($applications_id);
    
        $data = [
            'title' => 'Application Report',
            'application' => $application
        ];
    
        // Generate PDF from the view
        $pdf = PDF::loadView('report.applications-report', $data);
    
        // Return the generated PDF to the browser for download
        return $pdf->stream('Application-Report.pdf');
    }
    
    
}
