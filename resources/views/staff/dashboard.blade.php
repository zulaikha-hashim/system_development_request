@extends('layouts.client')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4" style="margin-top: 20px;">
        <div>
            <h1 class="h3 mb-0 text-gray-800 d-inline">Applicant</h1>
            <span class="h6 mb-0 text-gray-500 ml-2">Dashboard</span>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="background-color: transparent; border: none;">
                <li class="breadcrumb-item"><a href="/staff/dashboard">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Applicant</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-xl-5 col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-user"></i> Personal Details
                    </h6>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th scope="row" style="width: 150px;">Applicant ID</th>
                                <td>{{ Auth::user()->user_id }}</td>
                            </tr>
                            <tr>
                                <th scope="row" style="width: 150px;">Full Name</th>
                                <td>{{ Auth::user()->name }}</td>
                            </tr>
                            <tr>
                                <th scope="row" style="width: 150px;">Email</th>
                                <td>{{ Auth::user()->email }}</td>
                            </tr>

                        </tbody>
                    </table>
                </div>


            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-bell"></i> Applications
                    </h6>
                </div>
                <div class="card-body">
                    @if ($progressPercentage > 0 || $statusName === 'New Application')
                        <h4 class="small font-weight-bold">Current Application Progress
                            <span class="float-right">{{ $progressPercentage }}%</span>
                        </h4>
                        <div class="progress mb-4">
                            <div class="progress-bar bg-{{ $progressClass }}" role="progressbar"
                                style="width: {{ $progressPercentage }}%" aria-valuenow="{{ $progressPercentage }}"
                                aria-valuemin="0" aria-valuemax="100">
                            </div>
                        </div>
                    @else
                        <p>No active request progresses found.</p>
                    @endif

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <p class="mb-0"><strong>Total :</strong> {{ $applications->count() ?? '0' }}</p>
                        <p class="mb-0"><strong>In Progress :</strong> {{ $inProgressCount ?? '0' }}</p>
                        <p class="mb-0"><strong>Completed :</strong> {{ $completedCount ?? '0' }}</p>
                    </div>
                    
                    <div class="d-flex gap-2 mt-3 justify-content-end">
                        <a href="{{ route('applicant.index') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-clipboard-list"></i> Form
                        </a>
                        <a href="{{ route('application.applicationClientView') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-eye"></i> Application
                        </a>
                    </div>                    
                </div>
            </div>
        </div>
        <div class="col-xl-7 col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-calendar-alt"></i> Meeting Schedule
                    </h6>
                </div>
                <div class="card-body">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var applicationMeeting = @json($applicationMeeting);

        function formatDate(dateStr) {
            let date = new Date(dateStr);
            let day = String(date.getDate()).padStart(2, '0');
            let month = String(date.getMonth() + 1).padStart(2, '0'); // Months are zero-indexed
            let year = date.getFullYear();
            return `${day}/${month}/${year}`;
        }

        var events = applicationMeeting.map(function(application) {
            return {
                title: application.applications_system_name,
                start: application.date_confirm,
                applicationId: application.applications_id,
                applications_system_name: application.applications_system_name,
                date_confirm: formatDate(application.date_confirm), 
                application_time: application.applications_time, 
                admin_id: application.admin_id
            };
        });

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            events: events, 
            eventClick: function(info) {
                var application = info.event.extendedProps;
                var applicationDetails = `
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr>
                            <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Field</th>
                            <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Details</th>
                        </tr>
                        <tr>
                            <td style="border: 1px solid #ddd; padding: 8px;">Application ID</td>
                            <td style="border: 1px solid #ddd; padding: 8px;">${application.applicationId}</td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid #ddd; padding: 8px;">Admin ID</td>
                            <td style="border: 1px solid #ddd; padding: 8px;">${application.admin_id}</td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid #ddd; padding: 8px;">Project Name</td>
                            <td style="border: 1px solid #ddd; padding: 8px;">${application.applications_system_name}</td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid #ddd; padding: 8px;">Meeting Date</td>
                            <td style="border: 1px solid #ddd; padding: 8px;">${application.date_confirm}</td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid #ddd; padding: 8px;">Meeting Time</td>
                            <td style="border: 1px solid #ddd; padding: 8px;">${application.application_time}</td>
                        </tr>
                    </table>
                `;

                Swal.fire({
                    title: 'Application Details',
                    html: applicationDetails,
                    icon: 'info',
                    confirmButtonText: 'Close'
                });
            }
        });
        calendar.render();
    });
</script>

@endsection
