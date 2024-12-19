@extends('layouts.app')

@section('content')
    <div class="container">
        <script src="./dist/js/demo-theme.min.js?1692870487"></script>
        <div class="vh-100 d-flex flex-column justify-content-center align-items-center container">
            <div class="mb-4 text-center">
                <a href="." class="navbar-brand navbar-brand-autodark"><img src="assets/img/logo-small.svg" height="36"
                        alt=""></a>
            </div>
            <div class="row w-100 rounded bg-white p-4 shadow-lg" style="max-width: 500px;">
                <div class="col-lg">
                    <div class="container-tight">
                        @if (session('error'))
                            <script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: '{{ session('error') }}',
                                });
                            </script>
                        @endif
                        <div class="card card-md" style="border: none">
                            <div class="card-body">
                                <form method="POST" action="{{ route('verifyLogin') }}">
                                    @csrf
                                    <h2 class="h2 mb-4 text-center">Login to your account</h2>
                                    <div class="mb-3">
                                        <label class="form-label">Username</label>
                                        <input id="username" type="text" class="form-control" autofocus name="username"
                                            placeholder="Username" autocomplete="off" value="{{ old('username') }}"
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
                                                class="form-control @error('password') is-invalid @enderror" required>

                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror

                                            <span class="input-group-text">
                                                <i class="fas fa-eye" id="togglePassword"></i>
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
                <img src="assets/img/undraw_secure_login_pdn4.svg" height="300" class="d-block mx-auto" alt="">
            </div>
        </div>
    </div>
    </div>
    <script>
        const togglePassword = document.querySelector("#togglePassword");
        const passwordInput = document.querySelector("#password");

        togglePassword.addEventListener("click", function() {
            const type = passwordInput.getAttribute("type") === "password" ? "text" : "password";
            passwordInput.setAttribute("type", type);

            this.classList.toggle("fa-eye");
            this.classList.toggle("fa-eye-slash");
        });
    </script>
@endsection
