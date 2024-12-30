@extends('layouts.admin')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4" style="margin-top: 20px;">
        <div>
            <h1 class="h3 mb-0 text-gray-800 d-inline">Admin</h1>
            <span class="h6 mb-0 text-gray-500 ml-2">Dashboard</span>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="background-color: transparent; border: none;">
                <li class="breadcrumb-item active" aria-current="page"><a>Dashboard</a></li>
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

            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card-counter new">
                        <i class="bi bi-exclamation-circle-fill"></i>
                        <span class="count-numbers">{{ $newCount }}</span>
                        <span class="count-name">New Application</span>
                    </div>
                </div>

                <!-- Approved Counter -->
                <div class="col-md-6">
                    <div class="card-counter pending">
                        <i class="bi bi-clock-fill"></i>
                        <span class="count-numbers">{{ $pendingCount }}</span>
                        <span class="count-name">Pending</span>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card-counter approved">
                        <i class="bi bi-check-circle-fill"></i>
                        <span class="count-numbers">{{ $approvedCount }}</span>
                        <span class="count-name">Approved</span>
                    </div>
                </div>

                <!-- Approved Counter -->
                <div class="col-md-6">
                    <div class="card-counter rejected">
                        <i class="bi bi-x-circle-fill"></i>
                        <span class="count-numbers">{{ $rejectedCount }}</span>
                        <span class="count-name">Rejected</span>
                    </div>
                </div>

                <div class="col-md-6"> <!-- Column for In Progress -->
                    <div class="card-counter inprogress">
                        <i class="bi bi-file-earmark-code-fill"></i>
                        <span class="count-numbers">{{ $inprogressCount }}</span>
                        <span class="count-name">In Progress</span>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card-counter completed">
                        <i class="bi bi-check-all"></i>
                        <span class="count-numbers">{{ $completedCount }}</span>
                        <span class="count-name">Completed</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Meeting Schedule (Calendar) -->
        <div class="col-xl-7 col-lg-12">
            <div class="card shadow mb-4 w-100">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-calendar-alt"></i> Meeting Schedule</h6>
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

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        height: 'auto',
        events: function(fetchInfo, successCallback, failureCallback) {
            fetch('/calendar-data')
                .then(response => response.json())
                .then(data => {
                    let events = data.map(event => {
                        return {
                            title: event.applications_system_name,
                            start: event.date_confirm,
                            name: event.applicant_id,
                            applicationsId: event.applications_id,
                            time: event.applications_time 
                        };
                    });
                    successCallback(events);
                })
                .catch(error => {
                    failureCallback(error);
                });
        },
        eventClick: function(info) {
            // Format the date to dd-mm-yy
            var startDate = new Date(info.event.start).toLocaleDateString('en-GB', {
                day: '2-digit',
                month: '2-digit',
                year: '2-digit'
            });
            var time = info.event.extendedProps.time;

            var applicationDetails = `
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Field</th>
                        <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Details</th>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #ddd; padding: 8px;">Application ID</td>
                        <td style="border: 1px solid #ddd; padding: 8px;">${info.event.extendedProps.applicationsId}</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #ddd; padding: 8px;">Applicant ID</td>
                        <td style="border: 1px solid #ddd; padding: 8px;">${info.event.extendedProps.name}</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #ddd; padding: 8px;">Project Name</td>
                        <td style="border: 1px solid #ddd; padding: 8px;">${info.event.title}</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #ddd; padding: 8px;">Meeting Date</td>
                        <td style="border: 1px solid #ddd; padding: 8px;">${startDate}</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #ddd; padding: 8px;">Meeting Time</td>
                        <td style="border: 1px solid #ddd; padding: 8px;">${time}</td>
                    </tr>
                </table>
            `;

            Swal.fire({
                title: 'Application Details',
                html: applicationDetails,
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'View Full Details',
                cancelButtonText: 'Close'
            }).then((result) => {
                if (result.isConfirmed) {
                    if (info.event.extendedProps.applicationsId) {
                        window.location.href =
                            `/applications/${info.event.extendedProps.applicationsId}/pending`;
                    } else {
                        console.error('Application ID is undefined');
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Application ID is missing.',
                        });
                    }
                }
            });
        }
    });
    calendar.render();
});

</script>
@endsection
