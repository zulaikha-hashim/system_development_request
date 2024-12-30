@extends('layouts.dev')
@section('content')

    <div class="d-sm-flex align-items-center justify-content-between mb-4" style="margin-top: 20px;">
        <div>
            <a href="{{ route('application.applicationDevView') }}" class="text-dark" style="text-decoration: none;">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h1 class="h3 mb-0 text-gray-800 d-inline">Application</h1>
            <span class="h6 mb-0 text-gray-500 ml-2">In Progress</span>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="background-color: transparent; border: none;">
                <li class="breadcrumb-item"><a href="/developer/dashboard/">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="/developer/applications/">Application</a></li>
                <li class="breadcrumb-item active" aria-current="page">In Progress</li>
            </ol>
        </nav>
    </div>

    <!-- Content Row -->
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-bell"></i> System Development Request</h6>
                </div>
                <div class="card-body">
                    <div class="container mt-3">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card shadow-sm">
                                    <div class="card-body">
                                        <h1 class="text-center mb-4 text-dark">Application</h1>
                                        <div class="row mb-3">
                                            <div class="col-xl-4 col-md-8 mb-4">
                                                <div class="card border-left-primary shadow h-100 py-2">
                                                    <div class="card-body">
                                                        <div class="row no-gutters align-items-center">
                                                            <div class="col mr-2">
                                                                <div
                                                                    class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                                    Application ID</div>
                                                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                                    {{ $intec_sdr_application->applications_id }}
                                                                </div>
                                                            </div>
                                                            <div class="col-auto">
                                                                <i class="fas fa-file-alt fa-2x text-gray-300"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xl-4 col-md-8 mb-4">
                                                <div class="card border-left-success shadow h-100 py-2">
                                                    <div class="card-body">
                                                        <div class="row no-gutters align-items-center">
                                                            <div class="col mr-2">
                                                                <div
                                                                    class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                                    Application Created </div>
                                                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                                    {{ $intec_sdr_application->created_at->format('F j, Y, g:i a') }}
                                                                </div>
                                                            </div>
                                                            <div class="col-auto">
                                                                <i class="fas fa-clock fa-2x text-gray-300"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xl-4 col-md-8 mb-4">
                                                <div class="card border-left-warning shadow h-100 py-2">
                                                    <div class="card-body">
                                                        <div class="row no-gutters align-items-center">
                                                            <div class="col mr-2">
                                                                <div
                                                                    class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                                    Current Status</div>
                                                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                                    {{ $intec_sdr_application->status->status_name }}
                                                                </div>
                                                            </div>
                                                            <div class="col-auto">
                                                                <i class="fas fa-hourglass-half fa-2x text-gray-300"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label for="name" class="form-label">Applicant ID</label>
                                                <input type="text" name="name" class="form-control"
                                                    value="{{ $intec_sdr_application->applicant_id }}" id="name"
                                                    disabled>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="applicantIc" class="form-label">NRIC</label>
                                                <input type="text" name="applicantIc" class="form-control"
                                                    id="applicantIc"
                                                    value="{{ $intec_sdr_application->applicant->applicant_ic }}" disabled>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label for="applicantName" class="form-label">Full Name</label>
                                                <input type="text" name="applicantName" class="form-control"
                                                    id="applicantName"
                                                    value="{{ $intec_sdr_application->applicant->applicant_name }}"
                                                    disabled>
                                            </div>

                                            <div class="col-md-6">
                                                <label for="applicantPhone" class="form-label">Contact Number</label>
                                                <input type="text" name="applicantPhone" class="form-control"
                                                    id="applicantPhone"
                                                    value="{{ $intec_sdr_application->applicant->applicant_phone }}"
                                                    disabled>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label for="applicantEmail" class="form-label">Email Address</label>
                                                <input type="text" name="applicantEmail" class="form-control"
                                                    id="applicantEmail"
                                                    value="{{ $intec_sdr_application->applicant->applicant_email }}"
                                                    disabled>
                                            </div>

                                            <div class="col-md-6">
                                                <label for="depart" class="form-label">Department</label>
                                                <input type="text" name="depart" class="form-control" id="depart"
                                                    value="{{ $intec_sdr_application->applicant->applicant_depart }}"
                                                    disabled>
                                            </div>
                                        </div>

                                        <hr style="border: none; height: 1px; background-color: #ddd; margin: 25px 0;">

                                        <div class="mb-3">
                                            <label for="applicationName" class="form-label">System Development
                                                Name</label>
                                            <input type="text" name="applicationName" class="form-control"
                                                id="applicationName"
                                                value="{{ $intec_sdr_application->applications_system_name }}" disabled>
                                        </div>

                                        <div class="mb-3">
                                            <label for="applicationDesc" class="form-label">System Description</label>
                                            <textarea name="applicationDesc" class="form-control" id="applicationDesc" rows="4" disabled>{{ $intec_sdr_application->applications_system_desc }}</textarea>
                                        </div>

                                        <div class="mb-3">
                                            <label for="applicationUrgency" class="form-label">Urgency</label>
                                            <input type="text" name="applicationUrgency" class="form-control"
                                                id="applicationUrgency"
                                                value="{{ $intec_sdr_application->applications_urgency }}" disabled>
                                        </div>

                                        <div class="mb-3">
                                            <label for="file" class="form-label">Attached Document</label>
                                            <div class="mt-2">
                                                @if(isset($file))
                                                    <a target="_blank" href="/get-file/{{$file->applications_id}}" class="btn btn-secondary btn-sm"><i class="bi bi-file-earmark-text"></i> View Document</a>
                                                @else
                                                    <button class="btn btn-danger btn-sm" disabled><i class="bi bi-x-circle"></i> Not Available</button>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="applicationRemark" class="form-label">Rejection Remark</label>
                                            <textarea name="applicationRemark" class="form-control" id="applicationRemark" rows="4" disabled>{{ $intec_sdr_application->applications_remark ?? 'No rejection remarks.' }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-upload"></i> Upload Document</h6>
        </div>
        <div class="card-body">
            <form  id="fileForm"  action="{{ route('applications.completed', $intec_sdr_application->applications_id) }}" method="POST" enctype="multipart/form-data">
                @csrf <!-- Include CSRF token for security -->
    
                <div class="col-md-12 mt-1">
                        <div class="card-body">
                            <div class="container">
                                <div class="card border-left-danger shadow h-100 py-2 mb-4 small-card">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                                    Important Notice
                                                </div>
                                                <div class="h6 mb-0 font-weight-bold text-gray-800">
                                                    1. Please upload files in PDF format only.<br>
                                                    2. Ensure that all required documents are uploaded before final submission.<br>
                                                    3. Once all documents are submitted, the application status will automatically change to Completed.
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-info-circle fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="sr" class="form-label">User @ System Requirement</label><span style="color: red;"> *</span>
                                            <input  type="file" name="sr" class="form-control"  id="sr" accept=".pdf,.doc,.docx,.png,.jpg,.jpeg" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="sf" class="form-label">System Flowchart</label><span style="color: red;"> *</span>
                                            <input  type="file" name="sf" class="form-control"  id="sf" accept=".pdf,.doc,.docx,.png,.jpg,.jpeg" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="erd" class="form-label">System ERD (Entity Relationship Diagram)</label><span style="color: red;"> *</span>
                                            <input  type="file" name="erd" class="form-control"  id="erd" accept=".pdf,.doc,.docx,.png,.jpg,.jpeg" required>
                                        </div>
                                    </div>
     
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="sit" class="form-label">SIT (System Integration Testing)</label><span style="color: red;"> *</span>
                                            <input  type="file" name="sit" class="form-control"  id="sit" accept=".pdf,.doc,.docx,.png,.jpg,.jpeg" required>
                                      </div>
                                        <div class="mb-3">
                                            <label for="uat" class="form-label">UAT (User Acceptance Testing)</label><span style="color: red;"> *</span>
                                            <input  type="file" name="uat" class="form-control"  id="uat" accept=".pdf,.doc,.docx,.png,.jpg,.jpeg" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="um" class="form-label">User Manual</label><span style="color: red;"> *</span>
                                            <input  type="file" name="um" class="form-control"  id="um" accept=".pdf,.doc,.docx,.png,.jpg,.jpeg" required>
                                       </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
    
                    <div class="row mt-3">
                        <div class="col text-right"> 
                            <button type="submit" class="btn btn-primary mr-2" id="saveButton">
                           <i class="fas fa-paper-plane me-2"></i>Submit
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
     
@endsection
@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- Make sure this is included -->

<script>
    document.getElementById('fileForm').addEventListener('submit', function(e) {
        e.preventDefault(); // Prevent the default form submission

        // Show the SweetAlert2 confirmation dialog
        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to submit the document?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, submit it!'
        }).then((result) => {
            if (result.isConfirmed) {
                this.submit(); // If confirmed, submit the form
            }
        });
    });
</script>
@endsection

