@extends('layouts.client')
@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4" style="margin-top: 20px;">
        <div>
            <h1 class="h3 mb-0 text-gray-800 d-inline">Applicant</h1>
            <span class="h6 mb-0 text-gray-500 ml-2">Form</span>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="background-color: transparent; border: none;">
                <li class="breadcrumb-item"><a href="/applicant/dashboard">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Form</li>
            </ol>
        </nav>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-clipboard-list"></i> System Development Request
                    </h6>
                </div>
                <div class="card-body">
                    <div class="container mt-6">
                        <div class="row">
                            <div class="col-md-5">
                                <div id="form-step-1" class="form-step active">
                                    <div class="card shadow-sm">
                                        <div class="card-body" style="padding-bottom: 96px;">
                                            <h1 class="text-center mb-4 text-dark">Personal Details</h1>
                                            <form action="{{ route('applicantStore') }}" method="POST"
                                                enctype="multipart/form-data" id="applicantForm">
                                                @csrf

                                                <div class="mb-3">
                                                    <label for="applicant_name" class="form-label">Applicant ID<span
                                                            style="color: red;"> *</span></label>
                                                    <input type="text" name="applicant_name" class="form-control"
                                                        id="applicant_name" value="{{ auth()->user()->user_id }}" readonly
                                                        required>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="applicant_name" class="form-label">Full Name<span
                                                            style="color: red;"> *</span></label>
                                                    <input type="text" name="applicant_name" class="form-control"
                                                        id="applicant_name" value="{{ auth()->user()->name }}" readonly
                                                        required>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="applicant_email" class="form-label">Email Address<span
                                                            style="color: red;"> *</span></label>
                                                    <input type="text" name="applicant_email" class="form-control"
                                                        id="applicant_email" value="{{ auth()->user()->email }}" readonly
                                                        required>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="applicant_ic" class="form-label">NRIC<span
                                                            style="color: red;"> *</span></label>
                                                    <input type="text" name="applicant_ic" class="form-control"
                                                        id="applicant_ic" placeholder="Enter your NRIC" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="applicant_phone" class="form-label">Contact Number<span
                                                            style="color: red;"> *</span></label>
                                                    <input type="text" name="applicant_phone" class="form-control"
                                                        id="applicant_phone" placeholder="Enter your contact number"
                                                        required>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="applicant_depart" class="form-label">Department <span
                                                            style="color: red;"> *</span></label>
                                                    <select id="applicant_depart" name="applicant_depart"
                                                        class="form-select custom-select-width" required>
                                                        <option selected disabled>Select Department</option>
                                                        <option>Information Technology</option>
                                                        <option>Strategy & Business Development</option>
                                                        <option>Head of Programme & Coordinator</option>
                                                        <option>Finance</option>
                                                        <option>Marketing & Students Recruitment</option>
                                                        <option>Academic Management</option>
                                                        <option>Student Affairs</option>
                                                        <option>Internal Audit & Integrity</option>
                                                        <option>Quality, Accreditation & Governance (QAG)</option>
                                                        <option>Administration (HR), Operation & Facility Management
                                                        </option>
                                                        <option>Counselor</option>
                                                        <option>Library</option>
                                                        <option>Security</option>
                                                        <option>Transport</option>
                                                    </select>
                                                </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-7">
                                <div id="form-step-2" class="form-step">
                                    <div class="card shadow-sm">
                                        <div class="card-body" style="padding-bottom: 25px;">
                                            <h1 class="text-center mb-4 text-dark">Application Details</h1>
                                            @csrf
                                            <div class="mb-3">
                                                <label for="applications_system_name" class="form-label">System Development
                                                    Name<span style="color: red;"> *</span></label>
                                                <input type="text" name="applications_system_name" class="form-control"
                                                    id="applications_system_name" placeholder="Enter the system name"
                                                    required>
                                            </div>

                                            <div class="mb-3">
                                                <label for="applications_system_desc" class="form-label">System
                                                    Description<span style="color: red;"> *</span></label>
                                                <textarea name="applications_system_desc" class="form-control" id="applications_system_desc"
                                                    placeholder="Enter system's description" rows="4" required></textarea>
                                            </div>

                                            <div class="mb-3">
                                                <label for="applications_urgency" class="form-label">Urgency<span
                                                        style="color: red;"> *</span></label>
                                                <select id="applications_urgency" name="applications_urgency"
                                                    class="form-select custom-select-width" required>
                                                    <option selected disabled>Select level of urgency</option>
                                                    <option>Low</option>
                                                    <option>Medium</option>
                                                    <option>High</option>
                                                </select>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-4 mb-3">
                                                    <label for="applications_date1" class="form-label">Date 1:<span
                                                            style="color: red;"> *</span></label>
                                                    <div class="input-group">
                                                        <input type="text" name="applications_date1"
                                                            class="form-control flatpickr" id="applications_date1"
                                                            placeholder="Select a date" required>
                                                        <span class="input-group-text" id="calendar1"
                                                            style="cursor: pointer;">
                                                            <i class="fas fa-calendar-alt"></i>
                                                        </span>
                                                    </div>
                                                </div>

                                                <div class="col-md-4 mb-3">
                                                    <label for="applications_date2" class="form-label">Date 2:<span
                                                            style="color: red;"> *</span></label>
                                                    <div class="input-group">
                                                        <input type="text" name="applications_date2"
                                                            class="form-control flatpickr" id="applications_date2"
                                                            placeholder="Select a date" required>
                                                        <span class="input-group-text" id="calendar2"
                                                            style="cursor: pointer;">
                                                            <i class="fas fa-calendar-alt"></i>
                                                        </span>
                                                    </div>
                                                </div>

                                                <div class="col-md-4 mb-3">
                                                    <label for="applications_date3" class="form-label">Date 3:<span
                                                            style="color: red;"> *</span></label>
                                                    <div class="input-group">
                                                        <input type="text" name="applications_date3"
                                                            class="form-control flatpickr" id="applications_date3"
                                                            placeholder="Select a date" required>
                                                        <span class="input-group-text" id="calendar3"
                                                            style="cursor: pointer;">
                                                            <i class="fas fa-calendar-alt"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label for="applications_time" class="form-label">Select Time<span
                                                        style="color: red;"> *</span></label>
                                                <div class="input-group">
                                                    <select name="applications_time" class="form-select"
                                                        id="applications_time" required>
                                                        <option selected disabled>Select a time</option>
                                                        <option value="08:00">8:00 AM</option>
                                                        <option value="09:00">9:00 AM</option>
                                                        <option value="10:00">10:00 AM</option>
                                                        <option value="11:00">11:00 AM</option>
                                                        <option value="12:00">12:00 PM</option>
                                                        <option value="14:00">2:00 PM</option>
                                                        <option value="15:00">3:00 PM</option>
                                                        <option value="16:00">4:00 PM</option>
                                                    </select>
                                                    <span class="input-group-text" id="timeIcon"
                                                        style="cursor: pointer;">
                                                        <i class="fas fa-clock"></i>
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label for="applications_document" class="form-label">Upload Document
                                                    (PDF)<span style="color: red;"> *</span></label>
                                                <input type="file" name="applications_document" class="form-control"
                                                    id="applications_document" accept=".pdf" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-end mt-4 gap-2">
                                        <div class="w-35">
                                            <button type="reset" class="btn btn-secondary w-100">
                                                <i class="fas fa-eraser me-2"></i>Clear
                                            </button>
                                        </div>
                                        <div class="w-30">
                                            <button type="submit" class="btn btn-primary w-100" id="submitBtn">
                                                <i class="fas fa-paper-plane me-2"></i>Submit
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            flatpickr(".flatpickr", {
                dateFormat: "d/m/Y",
                minDate: "today" 
            });

            const form = document.getElementById('applicantForm');
            const submitBtn = document.getElementById('submitBtn');
            const clearBtn = document.querySelector('button[type="reset"]');

            submitBtn.addEventListener('click', function(event) {
                event.preventDefault();

                // Validate fields
                const requiredFields = document.querySelectorAll("#applicantForm [required]");
                let allFieldsFilled = true;

                requiredFields.forEach((field) => {
                    if (!field.value.trim()) {
                        allFieldsFilled = false;
                    }
                });

                if (!allFieldsFilled) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Missing Fields',
                        text: 'Please fill in all required fields before submitting.',
                        confirmButtonText: 'OK'
                    });
                } else {
                    Swal.fire({
                        title: 'Form confirmation',
                        text: "Make sure all your details are correct.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, submit it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                }
            });

            clearBtn.addEventListener('click', function(event) {
                event.preventDefault();

                Swal.fire({
                    title: 'Are you sure?',
                    text: "This will clear all the form data.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, clear it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.reset();
                    }
                });
            });

            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: '{{ session('success') }}',
                    confirmButtonText: 'OK'
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: '{{ session('error') }}',
                    confirmButtonText: 'OK'
                });
            @endif
        });
    </script>
@endsection
