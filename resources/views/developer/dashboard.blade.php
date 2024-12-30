@extends('layouts.dev')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <!-- Heading and Breadcrumb -->
        <div>
            <h1 class="h3 mb-0 text-gray-800 d-inline">Developer</h1>
            <span class="h6 mb-0 text-gray-500 ml-2">Dashboard</span>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="background-color: transparent; border: none;">
                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                <li class="breadcrumb-item active"><a>Developer</a></li>
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
                    <div class="card-counter approved">
                        <i class="bi bi-check-circle-fill"></i>
                        <span class="count-numbers">{{ $approvedCount }}</span>
                        <span class="count-name">Approved</span>
                    </div>
                </div>
                <div class="col-md-6">
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

        <!-- Right Column: Calendar -->
        <div class="col-xl-7 col-lg-12">
            <!-- Calendar Card -->
            <div class="card shadow mb-4 w-100">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-calendar-alt"></i> Project Deadline</h6>
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

        // Fetch application deadlines from the controller
        var applicationDeadlines = @json($applicationDeadlines);

        // Log to check the data
        console.log(applicationDeadlines);

        // Helper function to format the date
        function formatDate(dateStr) {
            let date = new Date(dateStr);
            let day = String(date.getDate()).padStart(2, '0');
            let month = String(date.getMonth() + 1).padStart(2, '0'); // Months are zero-indexed
            let year = date.getFullYear();
            return `${day}-${month}-${year}`;
        }

        // Helper function to format the time
        function formatTime(timeStr) {
            let date = new Date(`1970-01-01T${timeStr}Z`); // Create a date object for time
            let hours = date.getHours();
            let minutes = String(date.getMinutes()).padStart(2, '0');
            let amPm = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12 || 12; // Convert to 12-hour format
            return `${hours}:${minutes} ${amPm}`;
        }

        // Map deadlines to FullCalendar events
        var events = applicationDeadlines.map(function(application) {
            return {
                title: application.applications_system_name,
                start: application.applications_deadline,
                extendedProps: {
                    applicationId: application.applications_id,
                    applicantId: application.applicant_id,
                    applications_system_name: application.applications_system_name,
                    applications_deadline: formatDate(application.applications_deadline),
                    applications_time: formatTime(application.applications_time),
                    admin_id: application.admin_id
                }
            };
        });

        // Initialize the calendar
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            events: events, // Pass the events to the calendar
            eventClick: function(info) {
                // When an event is clicked, show more information
                var application = info.event.extendedProps;

                // Create the table structure to display application details
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
                            <td style="border: 1px solid #ddd; padding: 8px;">Applicant ID</td>
                            <td style="border: 1px solid #ddd; padding: 8px;">${application.applicantId}</td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid #ddd; padding: 8px;">Project Name</td>
                            <td style="border: 1px solid #ddd; padding: 8px;">${application.applications_system_name}</td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid #ddd; padding: 8px;">Project Deadline</td>
                            <td style="border: 1px solid #ddd; padding: 8px;">${application.applications_deadline}</td>
                        </tr>
                    </table>
                `;

                // Display the table in the SweetAlert modal
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
