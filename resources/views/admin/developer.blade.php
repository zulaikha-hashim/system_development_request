@extends('layouts.admin')
<!-- Begin Page Content -->
@section('content')

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4" style="margin-top: 20px;">
        <div>
            <h1 class="h3 mb-0 text-gray-800 d-inline">Admin</h1>
            <span class="h6 mb-0 text-gray-500 ml-2">Developer</span>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="background-color: transparent; border: none;">
                <li class="breadcrumb-item"><a href="/admin/dashboard/">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Developer</li>
            </ol>
        </nav>
    </div>

    <!-- Content Row -->
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary"> <i class="fas fa-user-plus"></i> Developer Registration</h6>
                    <div class="dropdown no-arrow"></div>
                </div>

                <!-- Card Body -->
                <div class="card-body">

                    <!-- Form -->
                    <div class="card card-body">
                        <h1 class="text-center mb-4 text-dark">Developer Registration</h1>
                        <form  id="developerForm"  action="{{ route('developer.submitForm') }}" method="POST">
                            @csrf
                            <div class="row">
                                <!-- NRIC Field -->
                                <div class="col-md-6 mb-3">
                                    <label for="dev_ic" class="form-label">NRIC</label><span class="text-danger">*</span>
                                    <input type="text" name="dev_ic" class="form-control" id="dev_ic" required
                                        placeholder="IC">
                                </div>

                                <!-- Full Name Field -->
                                <div class="col-md-6 mb-3">
                                    <label for="dev_name" class="form-label">Full Name</label><span
                                        class="text-danger">*</span>
                                    <input type="text" name="dev_name" class="form-control" id="dev_name" required
                                        placeholder="John Doe Bin Doe" oninput="this.value = this.value.toUpperCase()">
                                </div>

                                <!-- Email Field -->
                                <div class="col-md-6 mb-3">
                                    <label for="dev_email" class="form-label">Email</label><span
                                        class="text-danger">*</span>
                                    <input type="email" name="dev_email" class="form-control" id="dev_email" required
                                        placeholder="johndoe@gmail.com">
                                </div>
 
                                <!-- Submit Button -->
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

        <!-- Content Column -->
        <div class="col-lg-12 mb-10">
            <!-- Project Card Example -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"> <i class="fas fa-database"></i> Developer Management</h6>
                </div>
                <div class="card-body">
                    <!-- DataTables Example -->
                    <div class="table-responsive">
                        <table class="table table-bordered text-dark" id="data-developer" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Developer ID</th>
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
                        <input type="hidden" id="modal_dev_id">

                        <!-- NRIC Field -->
                        <div class="row justify-content-center mb-3">
                            <div class="col-md-12">
                                <label for="ic" class="form-label">NRIC</label><span class="text-danger">*</span>
                                <input type="text" name="ic" class="form-control" id="update_dev_ic" required>
                            </div>
                        </div>

                        <!-- Admin Name Field -->
                        <div class="row justify-content-center mb-3">
                            <div class="col-md-12">
                                <label for="name" class="form-label">Developer Name</label><span
                                    class="text-danger">*</span>
                                <input type="text" name="name" class="form-control" id="update_name" required>
                            </div>
                        </div>

                        <!-- Admin Email Field -->
                        <div class="row justify-content-center mb-3">
                            <div class="col-md-12">
                                <label for="email" class="form-label">Developer Email</label><span
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
    // Initialize DataTable
    $('#data-developer').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('developer.index') }}',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'dev_id', name: 'dev_id' },
            { data: 'dev_ic', name: 'dev_ic' },
            { data: 'dev_name', name: 'dev_name' },
            { data: 'dev_email', name: 'dev_email' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });

    // Show modal with pre-filled data
    $('#staticBackdrop').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var devId = button.data('dev-id');
        var devIc = button.data('ic');
        var devName = button.data('name');
        var devEmail = button.data('email');

        $('#modal_dev_id').val(devId);
        $('#update_dev_ic').val(devIc);
        $('#update_name').val(devName);
        $('#update_email').val(devEmail);

        var updateForm = $('#updateForm');
        updateForm.attr('action', '{{ route('developer.update', ':dev_id') }}'.replace(':dev_id', devId));
    });

    // Handle form submission for creating a developer
    $('#developerForm').on('submit', function(e) {
        e.preventDefault(); 

        var formData = $(this).serialize(); 

        $.ajax({
            url: '{{ route('developer.submitForm') }}',
            type: 'POST',
            data: formData,
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Developer registered successfully.'
                });

                $('#developerForm')[0].reset(); 
                $('#data-developer').DataTable().ajax.reload(); 
            },
            error: function(xhr) {
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
        });
    });

    // Handle delete button click
    $(document).on('click', '.delete-btn', function() {
    var devId = $(this).data('dev-id');

    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '{{ route('developer.destroy', ':id') }}'.replace(':id', devId),  // Ensure the correct route
                type: 'DELETE',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    Swal.fire('Deleted!', response.success, 'success');
                    $('#data-developer').DataTable().ajax.reload(); // Reload the DataTable after deletion
                },
                error: function(xhr) {
                    Swal.fire('Error!', xhr.responseJSON.error || 'There was an issue deleting the developer.', 'error');
                }
            });
        }
    });
});

$('#updateForm').on('submit', function(e) {
    e.preventDefault(); // Prevent default form submission

    var devId = $('#modal_dev_id').val(); // Get the developer ID from the hidden input

    var formData = {
        ic: $('#update_dev_ic').val(),
        name: $('#update_name').val(),
        email: $('#update_email').val(),
        _method: 'PUT', 
        _token: $('meta[name="csrf-token"]').attr('content') // Use the correct CSRF token
    };

    $.ajax({
        url: '{{ route('developer.update', ':id') }}'.replace(':id', devId), // Construct the URL
        type: 'POST',
        data: formData,
        success: function(response) {
            Swal.fire('Updated!', 'Developer updated successfully.', 'success');
            $('#staticBackdrop').modal('hide'); // Hide the modal after success
            $('#data-developer').DataTable().ajax.reload(); // Reload the DataTable
        },
        error: function(response) {
            Swal.fire('Error!', 'There was an issue updating the developer.', 'error');
        }
    });
});


});
    </script>
@endsection
