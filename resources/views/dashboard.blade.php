<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School SIS - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-graduation-cap me-2"></i>School SIS
            </a>
            <div class="navbar-nav ms-auto">
                <span class="navbar-text me-3">
                    Welcome, {{ auth()->user()->name }}
                </span>
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-light">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <h1>School Management Dashboard</h1>
                <p class="lead">Welcome to your School Student Information System</p>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-users text-primary me-2"></i>Students
                        </h5>
                        <p class="card-text">Manage student records and information</p>
                        <a href="{{ route('students.index') }}" class="btn btn-primary">View Students</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-chalkboard-teacher text-success me-2"></i>Teachers
                        </h5>
                        <p class="card-text">Manage teacher records and assignments</p>
                        <a href="{{ route('teachers.index') }}" class="btn btn-success">View Teachers</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-book text-warning me-2"></i>Courses
                        </h5>
                        <p class="card-text">Manage course offerings and schedules</p>
                        <a href="{{ route('courses.index') }}" class="btn btn-warning">View Courses</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-chart-line text-info me-2"></i>Grades
                        </h5>
                        <p class="card-text">Manage student grades and transcripts</p>
                        <a href="{{ route('grades.index') }}" class="btn btn-info">View Grades</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-building text-secondary me-2"></i>Departments
                        </h5>
                        <p class="card-text">Manage academic departments</p>
                        <a href="{{ route('departments.index') }}" class="btn btn-secondary">View Departments</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-calendar-alt text-danger me-2"></i>Events
                        </h5>
                        <p class="card-text">Manage school events and activities</p>
                        <a href="{{ route('events.index') }}" class="btn btn-danger">View Events</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-user-graduate text-dark me-2"></i>Graduates
                        </h5>
                        <p class="card-text">Manage graduated student records</p>
                        <a href="{{ route('graduates.index') }}" class="btn btn-dark">View Graduates</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-clipboard-check text-success me-2"></i>Attendance
                        </h5>
                        <p class="card-text">Track student attendance</p>
                        <a href="{{ route('attendance.index') }}" class="btn btn-success">View Attendance</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-books text-primary me-2"></i>Library
                        </h5>
                        <p class="card-text">Manage books and borrowing</p>
                        <a href="{{ route('library.index') }}" class="btn btn-primary">View Library</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-dollar-sign text-success me-2"></i>Financial
                        </h5>
                        <p class="card-text">Manage fees and payments</p>
                        <a href="{{ route('financial.index') }}" class="btn btn-success">View Financial</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-comments text-info me-2"></i>Communication
                        </h5>
                        <p class="card-text">Manage messages and announcements</p>
                        <a href="{{ route('communication.index') }}" class="btn btn-info">View Communication</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-calendar-alt text-warning me-2"></i>Timetable
                        </h5>
                        <p class="card-text">Manage class schedules and timetables</p>
                        <a href="{{ route('timetable.index') }}" class="btn btn-warning">View Timetable</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-file-alt text-danger me-2"></i>Examinations
                        </h5>
                        <p class="card-text">Manage exams and results</p>
                        <a href="{{ route('examination.index') }}" class="btn btn-danger">View Examinations</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-chart-bar text-warning me-2"></i>Reports
                        </h5>
                        <p class="card-text">Analytics and reporting dashboard</p>
                        <a href="{{ route('reports.index') }}" class="btn btn-warning">View Reports</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-cog text-muted me-2"></i>Settings
                        </h5>
                        <p class="card-text">System configuration and settings</p>
                        <a href="{{ route('settings.index') }}" class="btn btn-outline-secondary">Settings</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
