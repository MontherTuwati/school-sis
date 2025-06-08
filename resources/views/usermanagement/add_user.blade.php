@extends('layouts.master')
@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Register User</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="time-table.html">Users</a></li>
                            <li class="breadcrumb-item active">Register User</li>
                        </ul>
                    </div>
                </div>
            </div>
            {{-- message --}}
            {!! Toastr::message() !!}
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <p class="account-subtitle">Enter details to create user account</p>
                            <form action="{{ route('register') }}" method="POST">
                                @csrf
                                <diV class="row">
                                    <div class="col-12 col-sm-6">
                                        <div class="form-group local-forms">
                                            <label>Full Name <span class="login-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name">
                                                <div class="input-group-append">
                                                    <span class="profile-views"><i class="fas fa-user-circle"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <div class="form-group local-forms">
                                            <label>Email <span class="login-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="email" class="form-control @error('email') is-invalid @enderror" name="email">
                                                <div class="input-group-append">
                                                    <span class="profile-views"><i class="fas fa-envelope"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <div class="form-group local-forms">
                                            <label>Username <span class="login-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="username" class="form-control @error('username') is-invalid @enderror" name="username">
                                                <div class="input-group-append">
                                                    <span class="profile-views"><i class="fas fa-user"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <div class="form-group local-forms">
                                            <label>Role Name <span class="login-danger">*</span></label>
                                            <select class="form-control select @error('role') is-invalid @enderror" name="role" id="role">
                                                <option selected disabled>Role Type</option>
                                                @foreach ($role as $name)
                                                    <option value="{{ $name->role_type }}">{{ $name->role_type }}</option>
                                                @endforeach
                                            </select>
                                            @error('role')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                {{-- insert defaults --}}
                                <input type="hidden" class="image" name="image" value="photo_defaults.jpg">
                                <div class="row">
                                    <div class="col-12 col-sm-6">
                                        <div class="form-group local-forms">
                                            <label>Password <span class="login-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="password" class="form-control pass-input @error('password') is-invalid @enderror" name="password">
                                                <div class="input-group-append">
                                                    <span class="profile-views feather-eye toggle-password"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <div class="form-group local-forms">
                                            <label>Department <span class="login-danger">*</span></label>
                                            <select class="form-control select @error('department_id') is-invalid @enderror" name="department_id">
                                                <option selected disabled>Please Select Department</option>
                                                @foreach($departments as $department)
                                                    <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? "selected" : ""}}>
                                                        {{ $department->department_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('department_id')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <diV class="row">
                                    <div class="col-12 col-sm-6">
                                        <div class="form-group local-forms">
                                            <label>Confirm password <span class="login-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="password" class="form-control pass-confirm @error('password_confirmation') is-invalid @enderror" name="password_confirmation">
                                                <div class="input-group-append">
                                                    <span class="profile-views feather-eye reg-toggle-password"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-0">
                                    <button class="btn btn-primary btn-block" type="submit">Register</button>
                                </div>
                            </form>
                            <div class="login-or">
                                <span class="or-line"></span>
                                <span class="span-or"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
