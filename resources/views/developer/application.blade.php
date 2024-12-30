@extends('layouts.dev')
@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4" style="margin-top: 20px;">
        <div>
            <h1 class="h3 mb-0 text-gray-800 d-inline">Developer</h1>
            <span class="h6 mb-0 text-gray-500 ml-2">Application</span>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="background-color: transparent; border: none;">
                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page""><a>Application</a></li>
            </ol>
        </nav>
    </div>

    <div class="row mb-2">
        <div class="col-md-4">
            <div class="card-counter approved">
                <i class="bi bi-check-circle-fill"></i>
                <span class="count-numbers">{{ $approvedCount }}</span>
                <span class="count-name">Approved</span>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card-counter inprogress">
                <i class="bi bi-file-earmark-code-fill"></i>
                <span class="count-numbers">{{ $inprogressCount }}</span>
                <span class="count-name">In Progress</span>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card-counter completed">
                <i class="bi bi-check-all"></i>
                <span class="count-numbers">{{$completedCount}}</span>
                <span class="count-name">Completed</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 mb-10">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Application</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered text-dark" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Applicant Name</th>
                                    <th>System Name</th>
                                    <th>Status</th>
                                    <th>Urgency</th>
                                    <th>Deadline</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>

                        <!-- jQuery and DataTables JS -->
                        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                        <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

                        <script type="text/javascript">
                            $('#dataTable').DataTable({
                                processing: true,
                                serverSide: true,
                                ajax: '{{ route('application.applicationDevView') }}',
                                columns: [
                                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                                    { data: 'applicant_name', name: 'applicant.applicant_name' },
                                    { data: 'applications_system_name', name: 'applications_system_name' },
                                    { data: 'status', name: 'status' },
                                    { data: 'urgency', name: 'urgency' },
                                    { data: 'applications_deadline', name: 'applications_deadline' }, // New column for deadline and urgency
                                    { data: 'action', name: 'action', orderable: false, searchable: false }
                                ]
                            });
                        </script>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
