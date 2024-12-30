@extends('layouts.auth')

@section('content')

<body class="bg-gradient-danger">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card shadow-lg my-5">
                    <div class="card-body">
                        <div class="p-3">
                            
                            <div class="text-center">
                                <img src="{{ asset('import/assets/img/intec-logo.png') }}" alt="Logo" style="width: 220px; height: auto;">
                                <h1 class="h4 text-gray-900 mb-4">Register your account</h1>
                            </div>
                            <form method="POST" action="{{ route('register') }}" class="user">
                                @csrf

                                <div class="form-group mb-3">
                                    <input type="text" name="name" class="form-control form-control-user" id="name" placeholder="Name" value="{{ old('name') }}" required autofocus>
                                    @error('name')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <input type="email" name="email" class="form-control form-control-user" id="email" placeholder="Email Address" value="{{ old('email') }}" required>
                                    @error('email')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group row mb-3">
                                    <div class="col-sm-6">
                                        <input type="password" name="password" class="form-control form-control-user" id="password" placeholder="Password" required>
                                        <small class="text-muted">Please use your NRIC as password</small>
                                        @error('password')
                                            <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="password" name="password_confirmation" class="form-control form-control-user" id="password_confirmation" placeholder="Repeat Password" required>
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <div class="d-flex justify-content-center">
                                        <div class="form-check me-4">
                                            <input class="form-check-input" type="radio" name="role" id="roleAdmin" value="admin" required>
                                            <label class="form-check-label" for="roleAdmin">
                                                Admin
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="role" id="roleStaff" value="staff" required>
                                            <label class="form-check-label" for="roleStaff">
                                                Staff
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                
                                <button type="submit" class="btn btn-primary btn-user btn-block">
                                    Register Account
                                </button>

                            </form>
                            <hr>
                            <div class="text-center">
                                <a class="small" href="{{ route('login') }}">Already have an account? Login!</a>
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

