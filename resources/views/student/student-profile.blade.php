
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
                                                <img src="{{ Storage::disk('public')->url('app/public/student-images/'.$studentProfile->upload) }}" alt="Profile">
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
                                                @if ($subjects)
                                                    <h5>{{ $subjects->subject_name }}</h5>
                                                @else
                                                    <h5>No Subject assigned</h5>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <!-- Tabs -->
                            <ul class="nav nav-tabs">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#details">Student Details</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#enrollments">Enrollment</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#grades">Grades</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#transcript">Transcript</a>
                                </li>
                                <!-- Add more tabs as needed -->
                            </ul>
                            <div class="col-auto text-end float-end ms-auto download-grp">
                                <a href="{{ url('student/edit/'.$studentProfile) }}" class="btn btn-outline-gray me-2">
                                    <i class="fe fe-edit" aria-hidden="true"></i>
                                </a>
                            </div>
                            <!-- Tab panes -->
                            <div class="tab-content">
                                <!-- Student Details Tab -->
                                <div id="details" class="tab-pane active">
                                    <div class="row">

                                        <div class="col-lg-12">
                                            <div class="heading-detail">
                                                <h4>Student Details :</h4>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
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
                                                            <i class="feather-phone-call"></i>
                                                        </div>
                                                        <div class="views-personal">
                                                            <h4>Guardian Name</h4>
                                                            <h5>{{ $studentProfile->guardian_name }}</h5>
                                                        </div>
                                                    </div>
                                                    <div class="personal-activity">
                                                        <div class="personal-icons">
                                                            <i class="feather-phone-call"></i>
                                                        </div>
                                                        <div class="views-personal">
                                                            <h4>Mobile</h4>
                                                            <h5>{{ $studentProfile->guardian_number }}</h5>
                                                        </div>
                                                    </div>
                                                    <!-- Add more student details here -->
                                                </div>
                                                <div class="col-md-4">
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
                                                            <h5><a href="mailto:{{ $studentProfile->email }}" class="__cf_email__" data-cfemail="81e5e0e8f2f8c1e6ece0e8edafe2eeec">{{ $studentProfile->email }}</a></h5>
                                                        </div>
                                                    </div>
                                                    <div class="personal-activity mb-0">
                                                        <div class="personal-icons">
                                                            <i class="feather-home"></i>
                                                        </div>
                                                        <div class="views-personal">
                                                            <h4>Address</h4>
                                                            <h5>{{ $studentProfile->address }}</h5>
                                                        </div>
                                                    </div>
                                                    <!-- Add more student details here -->
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="personal-activity">
                                                        <div class="personal-icons">
                                                            <img src="{{ URL::to('assets/img/icons/buliding-icon.svg') }}" alt="">
                                                        </div>
                                                        <div class="views-personal">
                                                            <h4>Department</h4>
                                                            @if ($departments)
                                                                <h5>{{ $departments->department_name }}</h5>
                                                            @else
                                                                <h5>No department assigned</h5>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="personal-activity">
                                                        <div class="personal-icons">
                                                            <i class="fe fe-book"></i>
                                                        </div>
                                                        <div class="views-personal">
                                                            <h4>Subject</h4>
                                                            @if ($subjects)
                                                                <h5>{{ $subjects->subject_name }}</h5>
                                                            @else
                                                                <h5>No Subject assigned</h5>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="personal-activity mb-0">
                                                        <div class="personal-icons">
                                                            <i class="fe fe-credit-card"></i>
                                                        </div>
                                                        <div class="views-personal">
                                                            <h4>National ID</h4>
                                                            <h5>{{ $studentProfile->nation_id }}</h5>
                                                        </div>
                                                    </div>
                                                    <!-- Add more student details here -->
                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                </div>
                                <!-- Enrollments Tabs -->
                                <div id="enrollments" class="tab-pane fade">
                                    <div class="heading-detail">
                                        <h4>Student Enrollments:</h4>
                                    </div>
                                    <!-- Your existing student details HTML code goes here -->
                                    <div class="card-body">
                                        <h5>{{ $studentProfile->first_name }} {{ $studentProfile->last_name }}</h5>
                                        <h6>ID: {{ $studentProfile->student_id }}</h6>
                                            <div class="table-responsive">
                                                <table
                                                    class="table star-student table-hover table-center table-borderless table-striped">
                                                    <thead class="thead-light">
                                                        <tr>
                                                            <th>Course Code</th>
                                                            <th>Course</th>
                                                            <th class="text-center">Marks</th>
                                                            <th class="text-center">Percentage</th>
                                                            <th class="text-end">Semester</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($grades as $grade)
                                                            <tr>
                                                                <td>{{ $grade->course_code }}</td>
                                                                <td>{{ $grade->course }}</td>
                                                                <td>{{ $grade->grade }}</td>
                                                                <td>{{ $grade->semester }}</td>
                                                                <!-- Add more grade details here if needed -->
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    <!-- End of existing student details HTML code -->
                                </div>

                                <!-- Grades Tab -->
                                <div id="grades" class="tab-pane fade">
                                    <!-- Your grades content goes here -->
                                    <!-- Example content -->
                                    <h4>Student Grades:</h4>
                                        <div class="card-body">
                                        <h5>{{ $studentProfile->first_name }} {{ $studentProfile->last_name }}</h5>
                                        <h6>ID: {{ $studentProfile->student_id }}</h6>
                                            <div class="table-responsive">
                                                <table
                                                    class="table star-student table-hover table-center table-borderless table-striped">
                                                    <thead class="thead-light">
                                                        <tr>
                                                            <th>Course Code</th>
                                                            <th>Course</th>
                                                            <th class="text-center">Marks</th>
                                                            <th class="text-center">Percentage</th>
                                                            <th class="text-end">Semester</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($grades as $grade)
                                                            <tr>
                                                                <td>{{ $grade->course_code }}</td>
                                                                <td>{{ $grade->course }}</td>
                                                                <td>{{ $grade->grade }}</td>
                                                                <td>{{ $grade->semester }}</td>
                                                                <!-- Add more grade details here if needed -->
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    <!-- Assuming $grades is available -->
                                </div>

                                <!-- Enrollments Tabs -->
                                <div id="transcript" class="tab-pane fade">
                                    <div class="heading-detail">
                                        <h4>Student Transcript:</h4>
                                    </div>
                                    <!-- Your existing student details HTML code goes here -->
                                    <h3>Transcript</h3>
                                    <!-- End of existing student details HTML code -->
                                </div>

                                <!-- Add more tab panes as needed -->
                            </div>
                            <div class="col-auto text-end float-end ms-auto download-grp">
                                <a href="#" class="btn btn-outline-primary me-2"><i class="fas fa-download"></i> Download</a>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
            
        </div>
    </div>


@endsection
