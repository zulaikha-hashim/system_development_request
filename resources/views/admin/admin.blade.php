@extends('layouts.admin')
@section('content')

    <div class="d-sm-flex align-items-center justify-content-between mb-4" style="margin-top: 20px;">
        <div>
            <h1 class="h3 mb-0 text-gray-800 d-inline">Admin</h1>
            <span class="h6 mb-0 text-gray-500 ml-2">Admin</span>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="background-color: transparent; border: none;">
                <li class="breadcrumb-item"><a href="/admin/dashboard/">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Admin</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary"> <i class="fas fa-user-plus"></i> Admin Registration</h6>
                    <div class="dropdown no-arrow"></div>
                </div>
                <div class="card-body">
                    <div class="card card-body">
                        <h1 class="text-center mb-4 text-dark">Admin Registration</h1>
                        <form  id="adminForm" action="{{ route('admin.submitForm') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="admin_ic" class="form-label">NRIC</label><span class="text-danger">*</span>
                                    <input type="text" name="admin_ic" class="form-control" id="admin_ic" required
                                        placeholder="IC">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="admin_name" class="form-label">Full Name</label><span
                                        class="text-danger">*</span>
                                    <input type="text" name="admin_name" class="form-control" id="admin_name" required
                                        placeholder="John Doe Bin Doe">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="admin_email" class="form-label">Email</label><span
                                        class="text-danger">*</span>
                                    <input type="email" name="admin_email" class="form-control" id="admin_email" required
                                        placeholder="johndoe@gmail.com">
                                </div>

                                <div class="col-md-6 mb-3 d-flex justify-content-end align-items-center">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-paper-plane"></i> Submit
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-12 mb-10">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-database"></i> Admin Management</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered text-dark" id="data-admin" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Admin ID</th>
                                    <th>NRIC</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                        </table>
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
                    <form id="updateForm" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status_id" id="modal_admin_id">
                        <div class="row justify-content-center mb-3">
                            <div class="col-md-12">
                                <label for="ic" class="form-label">NRIC</label><span class="text-danger">*</span>
                                <input type="text" name="ic" class="form-control" id="update_admin_ic" required>
                            </div>
                        </div>

                        <div class="row justify-content-center mb-3">
                            <div class="col-md-12">
                                <label for="name" class="form-label">Admin Name</label><span
                                    class="text-danger">*</span>
                                <input type="text" name="name" class="form-control" id="update_name" required>
                            </div>
                        </div>

                        <div class="row justify-content-center mb-3">
                            <div class="col-md-12">
                                <label for="email" class="form-label">Admin Email</label><span
                                    class="text-danger">*</span>
                                <input type="text" name="email" class="form-control" id="update_email" required>
                            </div>
                        </div>

                        <div class="modal-footer d-flex justify-content-end">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Update</button>
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
$(document).ready(function() {
    $('#data-admin').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('admin.index') }}',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'admin_id', name: 'admin_id' },
            { data: 'admin_ic', name: 'admin_ic' },
            { data: 'admin_name', name: 'admin_name' },
            { data: 'admin_email', name: 'admin_email' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });

    $('#staticBackdrop').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var adminId = button.data('admin-id');
        var adminIc = button.data('ic');
        var adminName = button.data('name');
        var adminEmail = button.data('email');

        $('#modal_admin_id').val(adminId);
        $('#update_admin_ic').val(adminIc);
        $('#update_name').val(adminName);
        $('#update_email').val(adminEmail);

        var updateForm = $('#updateForm');
        updateForm.attr('action', '{{ route('admin.update', ':admin_id') }}'.replace(':admin_id', adminId));
    });

    $('#adminForm').on('submit', function(e) {
    e.preventDefault(); 

    var formData = $(this).serialize(); 

    $.ajax({
        url: '{{ route('admin.submitForm') }}',
        type: 'POST',
        data: formData,
        success: function(response) {
            if (response.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: response.message
                });

                $('#adminForm')[0].reset(); 
                $('#data-admin').DataTable().ajax.reload(); 
            }
        },
        error: function(xhr) {
            if (xhr.status === 400) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: xhr.responseJSON.message
                });
            } else {
                var errors = xhr.responseJSON.errors;
                var errorMessage = 'There was an issue submitting the form.';
                if (errors) {
                    errorMessage = Object.values(errors).join('<br>');
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    html: errorMessage
                });
            }
        }
    });
});



    $('#updateForm').on('submit', function(e) {
        e.preventDefault(); 

        var adminId = $('#modal_admin_id').val(); 

        var formData = {
            ic: $('#update_admin_ic').val(), 
            name: $('#update_name').val(),
            email: $('#update_email').val(),
            _method: 'PUT', 
            _token: $('input[name="_token"]').val() 
        };

        $.ajax({
            url: '{{ route('admin.update', '') }}' + '/' + adminId, 
            type: 'POST',
            data: formData,
            success: function(response) {
                Swal.fire('Updated!', 'Admin updated successfully.', 'success');
                $('#staticBackdrop').modal('hide'); 
                $('#data-admin').DataTable().ajax.reload(); 
            },
            error: function(response) {
                Swal.fire('Error!', 'There was an issue updating the admin.', 'error');
            }
        });
    });
});



</script>
@endsection
