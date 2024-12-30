@extends('layouts.admin')
@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4" style="margin-top: 20px;">
        <div>
            <h1 class="h3 mb-0 text-gray-800 d-inline">Admin</h1>
            <span class="h6 mb-0 text-gray-500 ml-2">Document</span>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="background-color: transparent; border: none;">
                <li class="breadcrumb-item"><a href="/admin/dashboard/">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Document</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary "><i class="fas fa-clipboard-list"></i> Document Form</h6>
                    <div class="dropdown no-arrow">
                    </div>
                </div>
                <div class="card-body">
                    <div class="card card-body mb-4">
                        <h1 class="text-center mb-4 text-dark">Document Form</h1>
                        <form id="documentForm" method="POST">
                            @csrf
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="type" class="form-label">Document Type</label>
                                    <input type="text" name="type" class="form-control" required
                                        placeholder="Document Type" id="type">
                                </div>
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Document Name</label>
                                    <input type="text" name="name" class="form-control" required
                                        placeholder="Document Name" id="name">
                                </div>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary w-85" id="submitBtn">
                                    <i class="fas fa-paper-plane me-3"></i> Submit
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-12 mb-10">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-database"></i> Document Management</h6>
                </div>
                <div class="card-body">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered text-dark" id="doc-table" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Type</th>
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
                    <!-- Modal Form -->
                    <form id="updateForm" method="POST" action="{{ route('document.update', ':id') }}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="type_id" id="type_id">

                        <!-- Form Fields -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="type" class="form-label">Document Type</label>
                                <input type="text" name="type" class="form-control" id="update_type" required>
                            </div>
                            <div class="col-md-6">
                                <label for="name" class="form-label">Document Name</label>
                                <input type="text" name="name" class="form-control" id="update_name" required>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary" id="updateSubmitBtn">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- end of Modal -->
@endsection
@section('script')
    <script>
        // data table
        $(document).ready(function() {
            $('#doc-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('docType.index') }}',
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'doc_type',
                        name: 'doc_type'
                    },
                    {
                        data: 'doc_name',
                        name: 'doc_name'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });
        });

        $(document).on('click', '.edit-btn', function() {
            var type_id = $(this).data('type-id');
            var type = $(this).data('type');
            var name = $(this).data('name');

            $('#type_id').val(type_id);
            $('#update_type').val(type);
            $('#update_name').val(name);

            // Update the form action
            var formAction = "{{ route('document.update', ':id') }}".replace(':id', type_id);
            $('#updateForm').attr('action', formAction);

            $('#staticBackdrop').modal('show');
        });

        $('#updateForm').on('submit', function(event) {
            event.preventDefault();
            var formAction = $(this).attr('action');
            var formData = $(this).serialize();

            $.ajax({
                url: formAction,
                type: 'POST',
                data: formData,
                success: function(response) {
                    $('#staticBackdrop').modal('hide');
                    Swal.fire({
                        title: 'Success!',
                        text: response.success,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    });

                    $('#doc-table').DataTable().ajax.reload();
                },
                error: function(xhr) {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Something went wrong. Please try again.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        });

        $(document).on('click', '.delete-btn', function() {
            var type_id = $(this).data('type_id'); 

            Swal.fire({
                title: 'Are you sure?',
                text: 'This action cannot be undone!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('document.destroy', ':id') }}".replace(':id', type_id),
                        type: 'DELETE',
                        data: {
                            _token: "{{ csrf_token() }}" // Include CSRF token
                        },
                        success: function(response) {
                            // Show success notification
                            Swal.fire({
                                title: 'Deleted!',
                                text: response.success,
                                icon: 'success',
                                confirmButtonText: 'OK'
                            });

                        $('#doc-table').DataTable().ajax.reload();
                        },
                        error: function(xhr) {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Failed to delete the document. Please try again.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                }
            });
        });

        // form submit 
        $(document).ready(function() {
            $('#documentForm').on('submit', function(e) {
                e.preventDefault();
                var formData = {
                    type: $('#type').val(),
                    name: $('#name').val(),
                    _token: $('input[name="_token"]').val()
                };

                $.ajax({
                    url: '{{ route('docType.submitForm') }}',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        Swal.fire('Success!', 'Document submitted successfully.', 'success');

                        $('#documentForm')[0].reset();
                        $('#doc-table').DataTable().ajax.reload();
                    },
                    error: function(response) {
                        Swal.fire('Error!', 'There was an issue submitting the form.', 'error');
                    }
                });
            });
        });
    </script>
@endsection
