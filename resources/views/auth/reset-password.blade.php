@extends('layouts.auth')

@section('content')
    <div id="loading-screen" style="display: none;">
        <div class="spinner"></div>
    </div>

    <body class="bg-gradient-danger">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="card shadow-lg my-5">
                        <div class="card-body">
                            <div class="p-3">
                                <div class="text-center">
                                    <img src="{{ asset('import/assets/img/intec-logo.png') }}" alt="Logo"
                                        style="width: 220px; height: auto;">
                                    <h1 class="h4 text-gray-900 mb-4">Reset Your Password</h1>
                                    <p class="mb-4 text-sm text-gray-600 dark:text-gray-400">
                                        {{ __('Enter your new password below to reset it.') }}
                                    </p>
                                </div>

                                <!-- Password Reset Form -->
                                <form method="POST" action="{{ route('password.update') }}">
                                    @csrf
                                    <input type="hidden" name="_method" value="PUT">

                                    <!-- Password Reset Token -->
                                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                                    <!-- Email Address -->
                                    <div class="form-group mb-3">
                                        <input type="email" id="email" name="email"
                                            class="form-control form-control-user" placeholder="Email Address"
                                            value="{{ old('email', $request->email) }}" required autofocus
                                            autocomplete="username">
                                        @error('email')
                                            <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Password -->
                                    <div class="form-group mb-3">
                                        <input type="password" id="password" name="password"
                                            class="form-control form-control-user" placeholder="New Password" required
                                            autocomplete="new-password">
                                        @error('password')
                                            <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Confirm Password -->
                                    <div class="form-group mb-3">
                                        <input type="password" id="password_confirmation" name="password_confirmation"
                                            class="form-control form-control-user" placeholder="Confirm New Password"
                                            required autocomplete="new-password">
                                        @error('password_confirmation')
                                            <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <hr>

                                    <!-- Submit Button -->
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary btn-user btn-block">
                                            Reset Password
                                        </button>
                                    </div>
                                </form>

                                <hr>

                                <div class="text-center">
                                    <a class="small" href="{{ route('login') }}">Back to Login</a>
                                </div>

                                <div class="text-center">
                                    <a class="small" href="{{ route('register') }}">Register an Account!</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
@endsection

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const resetForm = document.querySelector('form');

            if (resetForm) {
                resetForm.addEventListener('submit', function() {
                    document.getElementById('loading-screen').style.display = 'flex';
                });
            }
        });
    </script>
@endsection
