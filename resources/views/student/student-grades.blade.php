
@extends('layouts.master')
@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="page-sub-header">
                            <h3 class="page-title">Student Details</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('student/add/page') }}">Student</a></li>
                                <li class="breadcrumb-item active">Student Details</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="about-info">
                                <h4>Profile <span><a href="javascript:;"><i class="fa fa-address-book"></i></a></span></h4>
                            </div>
                            <div class="student-profile-head">
                                <div class="profile-bg-img">
                                    <img src="{{ URL::to('assets/img/profile-bg.jpg') }}" alt="Profile">
                                </div>
                                <div class="row">
                                    <div class="col-lg-4 col-md-4">
                                        <div class="profile-user-box">
                                            <div class="profile-user-img">
                                                @if(Storage::disk('public')->exists('student-photos/'.$studentProfile->upload))
                                                    <img src="{{ Storage::disk('public')->url('student-photos/'.$studentProfile->upload) }}" alt="Profile">
                                                @else
                                                    <p>Image not found</p>
                                                @endif
                                                <div class="form-group students-up-files profile-edit-icon mb-0">
                                                    <div class="upload d-flex">
                                                        <label class="file-upload profile-upbtn mb-0">
                                                            <i class="feather-edit-3"></i><input type="file">
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="names-profiles">
                                                <h4>{{ $studentProfile->first_name }} {{ $studentProfile->last_name }}</h4>
                                                <h5>subject</h5>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-auto text-end float-end ms-auto download-grp">
                                        <a href="{{ route('student/list') }}" class="btn btn-outline-gray me-2 active">
                                            <i class="fa fa-address-card" aria-hidden="true"></i>
                                        </a>
                                        <a href="{{ route('student/grid') }}" class="btn btn-outline-gray me-2">
                                            <i class="fa fa-clipboard-list" aria-hidden="true"></i>
                                        </a>
                                        <a href="#" class="btn btn-outline-primary me-2"><i class="fas fa-download"></i> Download</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="student-personals-grp">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <div class="heading-detail">
                                                    <h4>Student Details :</h4>
                                                </div>
                                                <div class="personal-activity">
                                                    <div class="personal-icons">
                                                        <i class="feather-user"></i>
                                                    </div>
                                                    <div class="views-personal">
                                                        <h4>Name</h4>
                                                        <h5>{{ $studentProfile->first_name }} {{ $studentProfile->last_name }}</h5>
                                                    </div>
                                                </div>
                                                <div class="personal-activity">
                                                    <div class="personal-icons">
                                                        <img src="{{ URL::to('assets/img/icons/buliding-icon.svg') }}" alt="">
                                                    </div>
                                                    <div class="views-personal">
                                                        <h4>Department </h4>
                                                        <h5>{{ $studentProfile->department_name }}</h5>
                                                    </div>
                                                </div>
                                                <div class="personal-activity">
                                                    <div class="personal-icons">
                                                        <i class="feather-phone-call"></i>
                                                    </div>
                                                    <div class="views-personal">
                                                        <h4>Mobile</h4>
                                                            <h5>{{ $studentProfile->phone_number }}</h5>
                                                    </div>
                                                </div>
                                                <div class="personal-activity">
                                                    <div class="personal-icons">
                                                        <i class="feather-mail"></i>
                                                    </div>
                                                    <div class="views-personal">
                                                        <h4>Email</h4>
                                                        <h5><a href="/cdn-cgi/l/email-protection" class="__cf_email__" data-cfemail="81e5e0e8f2f8c1e6ece0e8edafe2eeec">{{ $studentProfile->email }}</a></h5>
                                                    </div>
                                                </div>
                                                <div class="personal-activity">
                                                    <div class="personal-icons">
                                                        <i class="feather-user"></i>
                                                    </div>
                                                    <div class="views-personal">
                                                        <h4>Gender</h4>
                                                        <h5>{{ $studentProfile->gender }}</h5>
                                                    </div>
                                                </div>
                                                <div class="personal-activity">
                                                    <div class="personal-icons">
                                                        <i class="feather-calendar"></i>
                                                    </div>
                                                    <div class="views-personal">
                                                        <h4>Date of Birth</h4>
                                                        <h5>{{ $studentProfile->date_of_birth }}</h5>
                                                    </div>
                                                </div>
                                                <div class="personal-activity">
                                                    <div class="personal-icons">
                                                        <i class="feather-italic"></i>
                                                    </div>
                                                    <div class="views-personal">
                                                        <h4>Language</h4>
                                                        <h5>English, French, Bangla</h5>
                                                    </div>
                                                </div>
                                                <div class="personal-activity mb-0">
                                                    <div class="personal-icons">
                                                        <i class="feather-map-pin"></i>
                                                    </div>
                                                    <div class="views-personal">
                                                        <h4>Address</h4>
                                                        <h5>480, Estern Avenue, New York</h5>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-1 divider">
                                                <!-- Vertical Divider -->
                                                <div class="vertical-divider"></div>
                                            </div>
                                            <div class="col-lg-7">
                                                <div class="heading-detail">
                                                    <h4>About</h4>
                                                </div>
                                                
                                                <!-- Include student education details here -->
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
