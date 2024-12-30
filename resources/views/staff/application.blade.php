@extends('layouts.client')
@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4" style="margin-top: 20px;">
        <div>
            <h1 class="h3 mb-0 text-gray-800 d-inline">Applicant</h1>
            <span class="h6 mb-0 text-gray-500 ml-2">Application</span>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="background-color: transparent; border: none;">
                <li class="breadcrumb-item"><a href="/applicant/dashboard">Dashboard</a></li>
                <li class="breadcrumb-item aria-current="page""><a>Application</a></li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-lg-12 mb-10">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-database"></i> Application Management</h6>
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
                                    <th>Request Date</th>
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
            if (!$.fn.DataTable.isDataTable('#dataTable')) {
                $('#dataTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{{ route('application.applicationClientView') }}',
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'applicant_name',
                            name: 'applicant.applicant_name'
                        },
                        {
                            data: 'applications_system_name',
                            name: 'applications_system_name'
                        },
                        {
                            data: 'status',
                            name: 'status'
                        },
                        {
                            data: 'created_at',
                            name: 'created_at'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        }
                    ]
                });
            }
        });

        function showStatusMessage(message, applicationsId) {
            Swal.fire({
                title: 'Application Status',
                text: message,
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'View Full Details',
                cancelButtonText: 'Close'
            }).then((result) => {
                if (result.isConfirmed) {
                    if (applicationsId) {
                        window.location.href = `/applicant/applications/${applicationsId}`;
                    } else {
                        console.error("applicationsId is missing.");
                    }
                }
            });
        }

        $(document).on('click', '.delete-btn', function() {
            var applicationId = $(this).data('application-id');

            Swal.fire({
                title: 'Withdraw application?',
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonColor: '#d33'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/applications/' + applicationId + '/delete',
                        type: 'POST',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr(
                                'content'),
                            _method: 'DELETE'
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire(
                                    'Deleted!',
                                    'The application has been deleted successfully.',
                                    'success'
                                ).then(() => {
                                    $('#dataTable').DataTable().ajax
                                        .reload();
                                });
                            } else {
                                Swal.fire(
                                    'Error!',
                                    response.message ||
                                    'An error occurred while deleting the application.',
                                    'error'
                                );
                            }
                        },
                        error: function(xhr) {
                            Swal.fire(
                                'Error!',
                                'An error occurred while deleting the application.',
                                'error'
                            );
                        }
                    });
                }
            });
        });
    </script>
@endsection
