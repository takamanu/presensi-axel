@extends('layouts.app')

@section('content')
    <div class="container">

        <body class="d-flex flex-column">
            <script src="./dist/js/demo-theme.min.js?1692870487"></script>
            <div class="page page-center">
                <div class="container-normal container py-4">
                    <div class="row align-items-center g-4">
                        <div class="col-lg">
                            <div class="container-tight">
                                <div class="mb-4 text-center">
                                    <a href="." class="navbar-brand navbar-brand-autodark"><img
                                            src="assets/img/logo-small.svg" height="36" alt=""></a>
                                </div>

                                <div class="card card-md">
                                    <div class="card-body">
                                        <form method="POST" action="{{ route('verifyLogin') }}">
                                            @csrf
                                            <h2 class="h2 mb-4 text-center">Login to your account</h2>
                                            <div class="mb-3">
                                                <label class="form-label">Username</label>
                                                <input id="username" type="text" class="form-control" autofocus
                                                    name="username" placeholder="Username" autocomplete="off"
                                                    value="{{ old('username') }}"
                                                    class="form-control @error('username') is-invalid @enderror" required>

                                                @error('username')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="mb-2">
                                                <label class="form-label">
                                                    Password
                                                </label>
                                                <div class="input-group input-group-flat">
                                                    <input type="password" class="form-control" name="password"
                                                        placeholder="Password" autocomplete="off"
                                                        class="form-control @error('password') is-invalid @enderror"
                                                        required>

                                                    @error('password')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror

                                                    <span class="input-group-text">
                                                        <a href="#" class="link-secondary" title="Show password"
                                                            data-bs-toggle="tooltip"><!-- Download SVG icon from http://tabler-icons.io/i/eye -->
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon"
                                                                width="24" height="24" viewBox="0 0 24 24"
                                                                stroke-width="2" stroke="currentColor" fill="none"
                                                                stroke-linecap="round" stroke-linejoin="round">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                                                <path
                                                                    d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                                                            </svg>
                                                        </a>
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="form-footer">
                                                <button type="submit" name="login"
                                                    class="btn btn-primary w-100">{{ __('Login') }}</button>
                                            </div>
                                        </form>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="col-lg d-none d-lg-block">
                        <img src="assets/img/undraw_secure_login_pdn4.svg" height="300" class="d-block mx-auto"
                            alt="">
                    </div>
                </div>
            </div>
    </div>
@endsection
