@extends('layouts.admin')
<!-- Begin Page Content -->
@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4" style="margin-top: 20px;">
        <div>
            <h1 class="h3 mb-0 text-gray-800 d-inline">Admin</h1>
            <span class="h6 mb-0 text-gray-500 ml-2">Application</span>
            <!-- Smaller text beside the heading -->
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="background-color: transparent; border: none;">
                <li class="breadcrumb-item"><a href="/admin/dashboard/">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Application</li>
            </ol>
        </nav>
    </div>

    <div class="row mb-4">
        <!-- First Row -->
        <div class="col-md-4"> <!-- Column for New Application -->
            <div class="card-counter new">
                <i class="bi bi-exclamation-circle-fill"></i>
                <span class="count-numbers">{{ $newCount }}</span>
                <span class="count-name">New Application</span>
            </div>
        </div>

        <div class="col-md-4"> <!-- Column for Approved -->
            <div class="card-counter approved">
                <i class="bi bi-check-circle-fill"></i>
                <span class="count-numbers">{{ $approvedCount }}</span>
                <span class="count-name">Approved</span>
            </div>
        </div>

        <div class="col-md-4"> <!-- Column for In Progress -->
            <div class="card-counter inprogress">
                <i class="bi bi-file-earmark-code-fill"></i>
                <span class="count-numbers">{{ $inprogressCount }}</span>
                <span class="count-name">In Progress</span>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <!-- Second Row -->
        <div class="col-md-4"> <!-- Column for Pending -->
            <div class="card-counter pending">
                <i class="bi bi-clock-fill"></i>
                <span class="count-numbers">{{ $pendingCount }}</span>
                <span class="count-name">Pending</span>
            </div>
        </div>

        <div class="col-md-4"> <!-- Column for Rejected -->
            <div class="card-counter rejected">
                <i class="bi bi-x-circle-fill"></i>
                <span class="count-numbers">{{ $rejectedCount }}</span>
                <span class="count-name">Rejected</span>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card-counter completed">
                <i class="bi bi-check-all"></i>
                <span class="count-numbers"> {{ $completedCount }}</span>
                <span class="count-name">Completed</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 mb-10">
            <!-- Project Card Example -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-database"></i> Application Management</h6>
                </div>
                <div class="card-body">
                    <!-- DataTables Example -->
                    <div class="table-responsive">
                        <table class="table table-bordered text-dark" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Applicant Name</th>
                                    <th>System Name</th>
                                    <th>Status</th>
                                    <th>Urgency</th> <!-- Urgency Column -->
                                    <th>Request Date & Time</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')

 <script type="text/javascript">
     $(document).ready(function() {
         $('#dataTable').DataTable({
             processing: true,
             serverSide: true,
             ajax: '{{ route('application.applicationAdminView') }}',
             columns: [
                 { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                 { data: 'applicant_name', name: 'applicant_name' },
                 { data: 'applications_system_name', name: 'applications_system_name' },
                 { data: 'status', name: 'status' },
                 { data: 'urgency', name: 'urgency' }, // Urgency Column
                 { data: 'created_at', name: 'created_at' },
                 { data: 'action', name: 'action', orderable: false, searchable: false }
             ],
             columnDefs: [
                 { targets: 2, render: $.fn.dataTable.render.text() }
             ],
             order: [[2, 'asc']] // Order by Request Date (created_at)
         });
     });
 </script>
@endsection
