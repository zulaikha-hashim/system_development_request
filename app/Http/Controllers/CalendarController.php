<?php

namespace App\Http\Controllers;

use App\Models\IntecSdrApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CalendarController extends Controller
{
    // Existing method
    public function getCalendarData()
    {
        $events = IntecSdrApplication::whereNotNull('date_confirm')
            ->get(['date_confirm', 'applications_system_name', 'applicant_id', 'applications_id', 'applications_time']);
        return response()->json($events);
    }

    public function getCalendarDataDev()
    {
        $events = IntecSdrApplication::whereNotNull('applications_deadline')
            ->get(['applications_deadline', 'applications_system_name', 'applicant_id', 'applications_id'])
            ->map(function ($event) {
                return [
                    'applications_deadline' => \Carbon\Carbon::parse($event->applications_deadline)->format('d-m-Y'),
                    'applications_system_name' => $event->applications_system_name,
                    'applicant_id' => $event->applicant_id,
                    'applications_id' => $event->applications_id, // Ensure this is included
                ];
            });

        return response()->json($events);
    }

    public function getCalendarDataUser()
    {
        // Fetch only events where the applicant_id matches the logged-in user's ID
        $events = IntecSdrApplication::whereNotNull('date_confirm')
            ->where('applicant_id', auth()->id()) // Filter by logged-in user's ID
            ->get(['date_confirm', 'applications_system_name', 'applicant_id', 'applications_id']);

        return response()->json($events);
    }

}


