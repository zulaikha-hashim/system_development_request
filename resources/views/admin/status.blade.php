@extends('layouts.admin')
@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4" style="margin-top: 20px;">
        <div>
            <h1 class="h3 mb-0 text-gray-800 d-inline">Admin</h1>
            <span class="h6 mb-0 text-gray-500 ml-2">Status</span>

        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="background-color: transparent; border: none;">
                <li class="breadcrumb-item"><a href="/admin/dashboard/">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Status</li>
            </ol>
        </nav>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-clipboard-list"></i> Status Form</h6>
            <div class="dropdown no-arrow"></div>
        </div>
        <div class="card-body">
            <div class="card card-body">
                <h1 class="text-center mb-4 text-dark">Status Form</h1>
                <form id="statusForm" method="POST" action="{{ route('status.submitForm') }}">
                    @csrf
                    <div class="row justify-content-center mb-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Status Name</label>
                            <input type="text" name="name" class="form-control" id="name" placeholder="status"
                                required>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-85">
                                <i class="fas fa-paper-plane"></i> Submit
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-database"></i> Status Management</h6>
        </div>
        <div class="card-body">
            <!-- DataTables Example -->
            <div class="table-responsive">
                <table class="table table-bordered text-dark" id="status-table" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title fs-5" id="staticBackdropLabel">Update Form</h4>
                </div>
                <div class="modal-body">
                    <form id="updateForm" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status_id" id="modal_status_id">
                        <div class="row justify-content-center mb-3">
                            <div class="col-md-8">
                                <label for="name-update" class="form-label">Status Name</label>
                                <input type="text" name="name" class="form-control" id="name-update">

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            $('#status-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('status.index') }}',
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'status_name',
                        name: 'status_name'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            // Click event for the edit button
            $(document).on('click', '.edit-btn', function() {
                var statusId = $(this).data('status-id');
                var statusName = $(this).data('name');
                $('#modal_status_id').val(statusId); // Set the hidden input value
                $('#name-update').val(statusName); // Set the name in the input field
            });

            // Form submit for creating a status
            $('#statusForm').on('submit', function(e) {
                e.preventDefault(); // Prevent the form from submitting normally

                var formData = {
                    name: $('#name').val(),
                    _token: $('input[name="_token"]').val() // CSRF token
                };

                $.ajax({
                    url: '{{ route('status.submitForm') }}',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        Swal.fire('Success!', 'Status submitted successfully.', 'success');
                        $('#statusForm')[0].reset();
                        $('#status-table').DataTable().ajax.reload();
                    },
                    error: function(response) {
                        Swal.fire('Error!', 'There was an issue submitting the form.', 'error');
                    }
                });
            });

            // Intercept the update form submission
            $('#updateForm').on('submit', function(e) {
                e.preventDefault(); // Prevent default form submission
                var statusId = $('#modal_status_id').val(); // Get the status ID from the hidden input
                var formData = {
                    name: $('#name-update').val(),
                    _method: 'PUT', // Indicate the method is PUT
                    _token: $('input[name="_token"]').val() // CSRF token
                };

                $.ajax({
                    url: '{{ route('status.update', '') }}' + '/' +
                    statusId, // Correctly build the URL
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        Swal.fire('Updated!', 'Status updated successfully.', 'success');
                        $('#staticBackdrop').modal('hide'); // Hide the modal after success
                        $('#status-table').DataTable().ajax.reload(); // Reload the data table
                    },
                    error: function(response) {
                        Swal.fire('Error!', 'There was an issue updating the status.', 'error');
                    }
                });
            });

            $(document).on('click', '.delete-btn', function() {
                let statusId = $(this).data('status-id');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "This action cannot be undone!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/admin/status/delete/${statusId}`,
                            type: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                    'content') // Include CSRF token
                            },
                            success: function(response) {
                                Swal.fire(
                                    'Deleted!',
                                    response.success,
                                    'success'
                                );
                                $('#status-table').DataTable().ajax
                            .reload(); // Reload DataTable
                            },
                            error: function(xhr) {
                                console.error(xhr.responseText);
                                Swal.fire(
                                    'Error!',
                                    'Failed to delete the status. Please try again.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
