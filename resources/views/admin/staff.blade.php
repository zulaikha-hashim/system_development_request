@extends('layouts.admin')
@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4" style="margin-top: 20px;">
        <div>
            <h1 class="h3 mb-0 text-gray-800 d-inline">Admin</h1>
            <span class="h6 mb-0 text-gray-500 ml-2">Staff</span>

        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="background-color: transparent; border: none;">
                <li class="breadcrumb-item"><a href="/admin/dashboard/">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Staff</li>
            </ol>
        </nav>
    </div>

   <div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-database"></i> Staff Management</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered text-dark" id="staff-table" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Admin ID</th>
                        <th>NRIC</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Department</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
@section('script')
    <script>
        $(document).ready(function() {
            $('#staff-table').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: '{{ route('staff') }}',
        error: function(xhr, error, thrown) {
            console.error('Error in DataTables:', xhr.responseText);
        }
    },
    columns: [
        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
        { data: 'applicant_id', name: 'applicant_id' },
        { data: 'applicant_ic', name: 'applicant_ic' },
        { data: 'applicant_name', name: 'applicant_name' },
        { data: 'applicant_email', name: 'applicant_email' },
        { data: 'applicant_depart', name: 'applicant_depart' },
        { data: 'action', name: 'action', orderable: false, searchable: false }
    ]
});
$(document).on('click', '.delete-btn', function() {
    let applicantId = $(this).data('applicant-id');

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
                url: `/admin/staff/delete/${applicantId}`, // Ensure this matches your backend route
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // CSRF token
                },
                success: function(response) {
                    Swal.fire(
                        'Deleted!',
                        response.success || 'The staff has been deleted.',
                        'success'
                    );
                    $('#staff-table').DataTable().ajax.reload(); // Reload the DataTable
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    Swal.fire(
                        'Error!',
                        xhr.responseJSON?.error || 'Failed to delete the staff. Please try again.',
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
