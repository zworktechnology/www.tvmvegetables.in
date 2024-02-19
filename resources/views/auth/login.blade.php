@extends('layout.backend.guest')

@section('content')
    <div class="account-content">
        <div class="login-wrapper" style="background-image: url({{ asset('assets/backend/img/login.jpg') }}); background-repeat: no-repeat; background-size: cover;">
            <div class="login-content" style="background-color: white;opacity: 93%;">
                <div class="login-userset" style="width: 60% !important">
                    <div class="login-logo logo-normal">
                        <img src="{{ asset('assets/backend/img/logo.png') }}" alt="img">
                    </div>
                    <a href="javascript::void(o);" class="login-logo logo-white">
                        <img src="{{ asset('assets/backend/img/logo-white.png') }}" alt>
                    </a>
                    <div class="login-userheading">
                        <p style="margin-bottom: 0px;">👋 Welcome Back</p>
                        <h3>Please login with your POS account</h3>
                    </div>
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                    <div class="form-login">
                        <label>Email</label>
                        <div class="form-addons">
                            <input type="email" id="email"
                            class="floating form-control @error('email') is-invalid @enderror"
                            name="email" value="{{ old('email') }}" required autocomplete="email"
                            autofocus placeholder="Enter your email address">
                            <img src="{{ asset('assets/backend/img/icons/mail.svg') }}" alt="img">
                        </div>

                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    </div>
                    <div class="form-login">
                        <label>Password</label>
                        <div class="pass-group">
                            <input type="password" id="password"
                            class="pass-input floating form-control @error('password') is-invalid @enderror"
                            name="password" required autocomplete="current-password" placeholder="Enter your password">
                            <span class="fas toggle-password fa-eye-slash"></span>
                        </div>

                        @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    </div>
                    <div class="form-login">
                        <button class="btn btn-login"
                                        type="submit">Log In</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
