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
                                    <h1 class="h4 text-gray-900 mb-4">Welcome to System Development Request!</h1>
                                </div>
                                <form method="POST" action="{{ route('login') }}" class="user">
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

                                    <div class="form-group mb-3">
                                        <input type="password" id="password" name="password"
                                            class="form-control form-control-user" placeholder="Password" required>
                                        @error('password')
                                            <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <hr>
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary btn-user btn-block">
                                            Log in
                                        </button>
                                    </div>
                                </form>

                                <hr>

                                <div class="text-center">
                                    <a class="small" href="{{ route('register') }}">Register an Account!</a>
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
                const loginForm = document.querySelector('form');

                if (loginForm) {
                    loginForm.addEventListener('submit', function() {
                        document.getElementById('loading-screen').style.display = 'flex';
                    });
                }
            });
        </script>

        @if (session('success'))
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: '{{ session('success') }}',
                });
            </script>
        @endif

    @endsection
