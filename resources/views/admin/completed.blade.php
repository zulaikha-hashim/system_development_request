@extends('layouts.admin')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4" style="margin-top: 20px;">
        <div>
            <a href="{{ route('application.applicationAdminView') }}" class="text-dark" style="text-decoration: none;">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h1 class="h3 mb-0 text-gray-800 d-inline">Applicant</h1>
            <span class="h6 mb-0 text-gray-500 ml-2">Completed</span>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="background-color: transparent; border: none;">
                <li class="breadcrumb-item"><a href="/admin/dashboard/">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="/applications/">Application</a></li>
                <li class="breadcrumb-item active" aria-current="page">Completed</li>
            </ol>
        </nav>
    </div>
    
    <div class="row d-flex"> 
        <div class="col-xl-5 col-lg-12 d-flex"> 
            <div class="card shadow mb-4 w-100" style="position: sticky; top: 80px;"> 
                <div class="card-header py-4 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-user"></i> Personal Details</h6>
                </div>
                <div class="card-body d-flex"> 
                    <div class="container mt-4 w-100"> 
                        <div class="card shadow-sm w-100"> 
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-4">
                                        <span class="font-weight-bold">Applicant ID:</span>
                                    </div>
                                    <div class="col-7">
                                        {{ $intec_sdr_application->applicant_id?? 'N/A' }}
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-4">
                                        <span class="font-weight-bold">NRIC:</span>
                                    </div>
                                    <div class="col-8">
                                        {{ $intec_sdr_application->applicant->applicant_ic?? 'N/A' }}
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-4">
                                        <span class="font-weight-bold">Full Name:</span>
                                    </div>
                                    <div class="col-8">
                                        {{ $intec_sdr_application->applicant->applicant_name?? 'N/A' }}
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-4">
                                        <span class="font-weight-bold">Email:</span>
                                    </div>
                                    <div class="col-8">
                                        {{ $intec_sdr_application->applicant->applicant_email?? 'N/A' }}
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-4">
                                        <span class="font-weight-bold">Phone:</span>
                                    </div>
                                    <div class="col-8">
                                        {{ $intec_sdr_application->applicant->applicant_phone?? 'N/A' }}
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-4">
                                        <span class="font-weight-bold">Department:</span>
                                    </div>
                                    <div class="col-8">
                                        {{ $intec_sdr_application->applicant->applicant_depart?? 'N/A' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    
        <div class="col-xl-7 col-lg-12 d-flex"> 
            <div class="card shadow mb-4 w-100"> 
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-bell"></i> System Development Request Details</h6>
                    <div class="col text-right">
                        <a href="{{route('generate.report', ['applications_id' => $applications_id]) }}" target="_blank" class="btn btn-primary" style="padding: 6.4px 10px; font-size: 0.875rem;">
                            <i class="fas fa-file-alt"></i> Generate Report
                        </a>
                    </div>                    
                </div>
                <div class="card-body d-flex"> 
                    <div class="container mt-4 w-100">
                        <div class="card shadow-sm w-100"> 
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-4">
                                        <span class="font-weight-bold">Application ID:</span>
                                    </div>
                                    <div class="col-8">
                                        {{ $intec_sdr_application->applications_id?? 'N/A' }}
                                    </div>
                                </div>
    
                                <div class="row mb-3">
                                    <div class="col-4">
                                        <span class="font-weight-bold">Developer ID:</span>
                                    </div>
                                    <div class="col-8">
                                        {{ $intec_sdr_application->dev_id?? 'N/A' }}
                                    </div>
                                </div>
    
                                <div class="row mb-3">
                                    <div class="col-4">
                                        <span class="font-weight-bold">Developer:</span>
                                    </div>
                                    <div class="col-8">
                                        {{ $intec_sdr_application->developer->dev_id?? 'N/A' }}
                                    </div>
                                </div>
    
                                <div class="row mb-3">
                                    <div class="col-4">
                                        <span class="font-weight-bold">Status:</span>
                                    </div>
                                    <div class="col-8">
                                        {{ $intec_sdr_application->status->status_name?? 'N/A' }}
                                    </div>
                                </div>
    
                                <div class="row mb-3">
                                    <div class="col-4">
                                        <span class="font-weight-bold">System Name:</span>
                                    </div>
                                    <div class="col-8">
                                        {{ $intec_sdr_application->applications_system_name ?? 'N/A' }}
                                    </div>
                                </div>
    
                                <div class="row mb-3">
                                    <div class="col-4">
                                        <span class="font-weight-bold">System Description:</span>
                                    </div>
                                    <div class="col-8">
                                        {{ $intec_sdr_application->applications_system_desc?? 'N/A' }}
                                    </div>
                                </div>
    
                                <div class="row mb-3">
                                    <div class="col-4">
                                        <span class="font-weight-bold">Uploaded Document:</span>
                                    </div>
                                    <div class="col-6">
                                        @if(isset($file))
                                                    <a target="_blank" href="/get-file/{{$file->applications_id}}" class="btn btn-secondary btn-sm"><i class="bi bi-file-earmark-text"></i> View Document</a>
                                                @else
                                                    <button class="btn btn-danger btn-sm" disabled><i class="bi bi-x-circle"></i> Not Available</button>
                                                @endif
                                    </div>
                                </div>
    
                                <div class="row mb-3">
                                    <div class="col-4">
                                        <span class="font-weight-bold">Urgency:</span>
                                    </div>
                                    <div class="col-6">
                                        {{ $intec_sdr_application->applications_urgency?? 'N/A' }}
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-4">
                                        <span class="font-weight-bold">Request date & time:</span>
                                    </div>
                                    <div class="col-6">
                                        {{ $intec_sdr_application->created_at ? \Carbon\Carbon::parse($intec_sdr_application->created_at)->format('d-m-Y h:i A') : 'N/A' }}
                                    </div>
                                </div>                                
                                
                                <div class="row mb-3">
                                    <div class="col-4">
                                        <span class="font-weight-bold">Meeting Confirmation:</span>
                                    </div>
                                    <div class="col-6">
                                        {{ $intec_sdr_application->date_confirm ? \Carbon\Carbon::parse($intec_sdr_application->date_confirm)->format('d-m-Y') : 'N/A' }}
                                        {{ $intec_sdr_application->applications_time ? \Carbon\Carbon::parse($intec_sdr_application->applications_time)->format('h:i A') : 'N/A' }}
                                    </div>
                                </div>
    
                                <div class="row mb-3">
                                    <div class="col-4">
                                        <span class="font-weight-bold">System Deadline:</span>
                                    </div>
                                    <div class="col-6">
                                        {{ $intec_sdr_application->applications_deadline ? \Carbon\Carbon::parse($intec_sdr_application->applications_deadline)->format('d-m-Y') : 'N/A' }}
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
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary"><i class="bi bi-file-earmark-pdf-fill"></i>System Documentation</h6>
        </div>
        <div class="card-body">
            <div class="container mt-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="container">
                            <div class="row mb-3">
                                <div class="col-9">
                                    <label for="formFile1" class="form-label">i. System Requirement</label>
                                </div>
                                <div class="col-3 text-end">
                                    @if (isset($fileSR))
                                        <a target="_blank" href="/get-file/system-requirement/{{ $fileSR->doc_id }}"
                                            class="btn btn-primary btn-sm"><i class="bi bi-file-earmark-text"></i> View
                                            Document</a>
                                    @else
                                        <button class="btn btn-danger btn-sm" disabled><i class="bi bi-x-circle"></i> Not
                                            Available</button>
                                    @endif
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-9">
                                    <label for="formFile4" class="form-label">ii. System Flowchart</label>
                                </div>
                                <div class="col-3 text-end">
                                    @if (isset($fileSF))
                                        <a target="_blank" href="/get-file/system-flowchart/{{ $fileSF->doc_id }}"
                                            class="btn btn-primary btn-sm"><i class="bi bi-file-earmark-text"></i> View
                                            Document</a>
                                    @else
                                        <button class="btn btn-danger btn-sm" disabled><i class="bi bi-x-circle"></i> Not
                                            Available</button>
                                    @endif
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-9">
                                    <label for="formFile2" class="form-label">iii. ERD (Entity Relationship
                                        Diagram)</label>
                                </div>
                                <div class="col-3 text-end">
                                    @if (isset($fileERD))
                                        <a target="_blank" href="/get-file/entity-relationship-model/{{ $fileERD->doc_id }}"
                                            class="btn btn-primary btn-sm"><i class="bi bi-file-earmark-text"></i> View
                                            Document</a>
                                    @else
                                        <button class="btn btn-danger btn-sm" disabled><i class="bi bi-x-circle"></i> Not
                                            Available</button>
                                    @endif
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-9">
                                    <label for="formFile5" class="form-label">iv. SIT (System Integration Testing)</label>
                                </div>
                                <div class="col-3 text-end">
                                    @if (isset($fileSIT))
                                        <a target="_blank" href="/get-file/system-integration-testing/{{ $fileSIT->doc_id }}"
                                            class="btn btn-primary btn-sm"><i class="bi bi-file-earmark-text"></i> View
                                            Document</a>
                                    @else
                                        <button class="btn btn-danger btn-sm" disabled><i class="bi bi-x-circle"></i> Not
                                            Available</button>
                                    @endif
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-9">
                                    <label for="formFile3" class="form-label">v. UAT (User Acceptance Testing)</label>
                                </div>
                                <div class="col-3 text-end">
                                    @if (isset($fileUAT))
                                        <a target="_blank" href="/get-file/user-accecptance-testing/{{ $fileUAT->doc_id }}"
                                            class="btn btn-primary btn-sm"><i class="bi bi-file-earmark-text"></i> View
                                            Document</a>
                                    @else
                                        <button class="btn btn-danger btn-sm" disabled><i class="bi bi-x-circle"></i> Not
                                            Available</button>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-9">
                                    <label for="formFile6" class="form-label">vi. User Manual</label>
                                </div>
                                <div class="col-3 text-end">
                                    @if (isset($fileUM))
                                        <a target="_blank" href="/get-file/user-manual/{{ $fileUM->doc_id }}"
                                            class="btn btn-primary btn-sm"><i class="bi bi-file-earmark-text"></i> View
                                            Document</a>
                                    @else
                                        <button class="btn btn-danger btn-sm" disabled><i class="bi bi-x-circle"></i> Not
                                            Available</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
@endsection
