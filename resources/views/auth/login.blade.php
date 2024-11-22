
@extends('layouts.app')
@section('content')
{{-- message --}}
{!! Toastr::message() !!}
<div class="login-right">
    <div class="login-right-wrap">
        <h1>Welcome to Dashbord</h1>
        <p class="account-subtitle">Need an account? <a href="{{ route('register') }}">Sign Up</a></p>
        <h2>Log in</h2>
        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Username<span class="login-danger">*</span></label>
                <input type="username" class="form-control @error('username') is-invalid @enderror" name="username">
                <span class="profile-views"><i class="fas fa-user"></i></span>
            </div>
            <div class="form-group">
                <label>Password <span class="login-danger">*</span></label>
                <input type="password" class="form-control pass-input @error('password') is-invalid @enderror" name="password">
                <span class="profile-views feather-eye toggle-password"></span>
            </div>
            <div class="forgotpass">
                <div class="remember-me">
                    <label class="custom_check mr-2 mb-0 d-inline-flex remember-me"> Remember me
                        <input type="checkbox" name="radio">
                        <span class="checkmark"></span>
                    </label>
                </div>
                <a href="forgot-password.html">Forgot Password?</a>
            </div>
            <div class="form-group">
                <button class="btn btn-primary btn-block" type="submit">Login</button>
            </div>
        </form>
    </div>
</div>

@endsection
