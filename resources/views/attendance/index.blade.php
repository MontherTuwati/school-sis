<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Management - School SIS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('dashboard') }}">
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
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-clipboard-check me-2"></i>Attendance Management</h2>
                    <div>
                        <a href="{{ route('attendance.report') }}" class="btn btn-outline-info me-2">
                            <i class="fas fa-chart-bar me-2"></i>Reports
                        </a>
                        <a href="{{ route('attendance.take') }}" class="btn btn-success">
                            <i class="fas fa-plus me-2"></i>Take Attendance
                        </a>
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Today's Date -->
                <div class="alert alert-info">
                    <i class="fas fa-calendar-day me-2"></i>
                    <strong>Today's Date:</strong> {{ $today->format('l, F d, Y') }}
                </div>

                <!-- Attendance Statistics -->
                <div class="row mb-4">
                    <div class="col-md-2">
                        <div class="card text-center">
                            <div class="card-body">
                                <i class="fas fa-clipboard-list fa-2x text-primary mb-2"></i>
                                <h4 class="card-title">{{ $stats['total_records'] }}</h4>
                                <p class="card-text text-muted">Total Records</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card text-center">
                            <div class="card-body">
                                <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                                <h4 class="card-title">{{ $stats['present'] }}</h4>
                                <p class="card-text text-muted">Present</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card text-center">
                            <div class="card-body">
                                <i class="fas fa-times-circle fa-2x text-danger mb-2"></i>
                                <h4 class="card-title">{{ $stats['absent'] }}</h4>
                                <p class="card-text text-muted">Absent</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card text-center">
                            <div class="card-body">
                                <i class="fas fa-clock fa-2x text-warning mb-2"></i>
                                <h4 class="card-title">{{ $stats['late'] }}</h4>
                                <p class="card-text text-muted">Late</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card text-center">
                            <div class="card-body">
                                <i class="fas fa-exclamation-triangle fa-2x text-info mb-2"></i>
                                <h4 class="card-title">{{ $stats['excused'] }}</h4>
                                <p class="card-text text-muted">Excused</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card text-center">
                            <div class="card-body">
                                <i class="fas fa-percentage fa-2x text-dark mb-2"></i>
                                <h4 class="card-title">{{ $stats['attendance_rate'] }}%</h4>
                                <p class="card-text text-muted">Attendance Rate</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body text-center">
                                <i class="fas fa-clipboard-check fa-3x text-success mb-3"></i>
                                <h5 class="card-title">Take Attendance</h5>
                                <p class="card-text">Record attendance for today's classes</p>
                                <a href="{{ route('attendance.take') }}" class="btn btn-success">
                                    <i class="fas fa-plus me-2"></i>Start Recording
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body text-center">
                                <i class="fas fa-search fa-3x text-primary mb-3"></i>
                                <h5 class="card-title">View Attendance</h5>
                                <p class="card-text">Browse and search attendance records</p>
                                <a href="{{ route('attendance.view') }}" class="btn btn-primary">
                                    <i class="fas fa-eye me-2"></i>View Records
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body text-center">
                                <i class="fas fa-chart-bar fa-3x text-info mb-3"></i>
                                <h5 class="card-title">Attendance Reports</h5>
                                <p class="card-text">Generate reports and analytics</p>
                                <a href="{{ route('attendance.report') }}" class="btn btn-info">
                                    <i class="fas fa-chart-line me-2"></i>View Reports
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Attendance Records -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-history me-2"></i>Recent Attendance Records (Today)
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($recentAttendance->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Student</th>
                                            <th>Course</th>
                                            <th>Status</th>
                                            <th>Time</th>
                                            <th>Remarks</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentAttendance as $record)
                                            <tr>
                                                <td>
                                                    <div><strong>{{ $record->student->first_name }} {{ $record->student->last_name }}</strong></div>
                                                    <small class="text-muted">{{ $record->student->email }}</small>
                                                </td>
                                                <td>
                                                    <div>{{ $record->course->name }}</div>
                                                    <small class="text-muted">{{ $record->course->teacher->first_name ?? 'N/A' }}</small>
                                                </td>
                                                <td>
                                                    @switch($record->status)
                                                        @case('present')
                                                            <span class="badge bg-success">Present</span>
                                                            @break
                                                        @case('absent')
                                                            <span class="badge bg-danger">Absent</span>
                                                            @break
                                                        @case('late')
                                                            <span class="badge bg-warning">Late</span>
                                                            @break
                                                        @case('excused')
                                                            <span class="badge bg-info">Excused</span>
                                                            @break
                                                        @default
                                                            <span class="badge bg-secondary">{{ ucfirst($record->status) }}</span>
                                                    @endswitch
                                                </td>
                                                <td>
                                                    <small>{{ $record->created_at->format('H:i') }}</small>
                                                </td>
                                                <td>
                                                    <small>{{ $record->remarks ?? 'N/A' }}</small>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('attendance.edit', $record->id) }}" 
                                                           class="btn btn-sm btn-outline-warning" title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <form action="{{ route('attendance.destroy', $record->id) }}" 
                                                              method="POST" class="d-inline" 
                                                              onsubmit="return confirm('Are you sure you want to delete this attendance record?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No attendance records for today</h5>
                                <p class="text-muted">Start by taking attendance for your classes.</p>
                                <a href="{{ route('attendance.take') }}" class="btn btn-success">
                                    <i class="fas fa-plus me-2"></i>Take Attendance
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Course-wise Attendance Summary -->
                @if($stats['course_attendance']->count() > 0)
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-book me-2"></i>Course-wise Attendance Summary (Today)
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach($stats['course_attendance'] as $course)
                                    <div class="col-md-3 mb-3">
                                        <div class="card border-0 bg-light">
                                            <div class="card-body text-center">
                                                <h6 class="card-title">{{ $course->name }}</h6>
                                                <p class="card-text">
                                                    <span class="badge bg-primary">{{ $course->attendance_count }} records</span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
