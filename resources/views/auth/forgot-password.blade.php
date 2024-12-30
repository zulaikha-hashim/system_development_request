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
                                <img src="{{ asset('import/assets/img/intec-logo.png') }}" alt="Logo" style="width: 220px; height: auto;">
                                <h1 class="h4 text-gray-900 mb-4">Forgot Your Password?</h1>
                                <p class="mb-4 text-sm text-gray-600 dark:text-gray-400">
                                    {{ __('No problem. Just let us know your email address and we will send you a password reset link to choose a new one.') }}
                                </p>
                            </div>

                            <!-- Session Status -->
                            <x-auth-session-status class="mb-4" :status="session('status')" />

                            <form method="POST" action="{{ route('password.email') }}">
                                @csrf

                                <!-- Email Address -->
                                <div class="form-group mb-3">
                                    <input type="email" id="email" name="email"
                                        class="form-control form-control-user" placeholder="Email Address"
                                        value="{{ old('email') }}" required autofocus>
                                    @error('email')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>

                                <hr>


                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary btn-user btn-block">
                                        Email Password Reset Link
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
    document.addEventListener('DOMContentLoaded', function () {
        const loginForm = document.querySelector('form');

        if (loginForm) {
            loginForm.addEventListener('submit', function () {
                document.getElementById('loading-screen').style.display = 'flex';
            });
        }
    });
</script>
@endsection
