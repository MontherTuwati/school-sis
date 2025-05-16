<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Timetable Management - School SIS</title>
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
                    <h2><i class="fas fa-calendar-alt me-2"></i>Timetable Management</h2>
                    <div>
                        <a href="{{ route('timetable.reports') }}" class="btn btn-outline-info me-2">
                            <i class="fas fa-chart-bar me-2"></i>Reports
                        </a>
                        <a href="{{ route('timetable.weekly') }}" class="btn btn-success me-2">
                            <i class="fas fa-calendar-week me-2"></i>Weekly View
                        </a>
                        <a href="{{ route('timetable.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Add Timetable
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

                <!-- Timetable Statistics -->
                <div class="row mb-4">
                    <div class="col-md-2">
                        <div class="card text-center">
                            <div class="card-body">
                                <i class="fas fa-calendar fa-2x text-primary mb-2"></i>
                                <h4 class="card-title">{{ $stats['total_timetables'] }}</h4>
                                <p class="card-text text-muted">Total Timetables</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card text-center">
                            <div class="card-body">
                                <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                                <h4 class="card-title">{{ $stats['active_timetables'] }}</h4>
                                <p class="card-text text-muted">Active</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card text-center">
                            <div class="card-body">
                                <i class="fas fa-chalkboard-teacher fa-2x text-info mb-2"></i>
                                <h4 class="card-title">{{ $stats['teachers_with_timetables'] }}</h4>
                                <p class="card-text text-muted">Teachers Scheduled</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card text-center">
                            <div class="card-body">
                                <i class="fas fa-building fa-2x text-warning mb-2"></i>
                                <h4 class="card-title">{{ $stats['active_classrooms'] }}</h4>
                                <p class="card-text text-muted">Active Classrooms</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card text-center">
                            <div class="card-body">
                                <i class="fas fa-book fa-2x text-danger mb-2"></i>
                                <h4 class="card-title">{{ $stats['courses_with_timetables'] }}</h4>
                                <p class="card-text text-muted">Courses Scheduled</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card text-center">
                            <div class="card-body">
                                <i class="fas fa-clock fa-2x text-dark mb-2"></i>
                                <h4 class="card-title">{{ $stats['recent_timetables'] }}</h4>
                                <p class="card-text text-muted">Recent (7 days)</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body text-center">
                                <i class="fas fa-plus fa-3x text-primary mb-3"></i>
                                <h5 class="card-title">Add Timetable</h5>
                                <p class="card-text">Create new timetable entries</p>
                                <a href="{{ route('timetable.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>Add
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body text-center">
                                <i class="fas fa-calendar-week fa-3x text-success mb-3"></i>
                                <h5 class="card-title">Weekly View</h5>
                                <p class="card-text">View complete weekly timetable</p>
                                <a href="{{ route('timetable.weekly') }}" class="btn btn-success">
                                    <i class="fas fa-calendar-week me-2"></i>View
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body text-center">
                                <i class="fas fa-chalkboard-teacher fa-3x text-info mb-3"></i>
                                <h5 class="card-title">Teacher Timetable</h5>
                                <p class="card-text">View teacher schedules</p>
                                <a href="{{ route('timetable.teacher') }}" class="btn btn-info">
                                    <i class="fas fa-chalkboard-teacher me-2"></i>View
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body text-center">
                                <i class="fas fa-building fa-3x text-warning mb-3"></i>
                                <h5 class="card-title">Classrooms</h5>
                                <p class="card-text">Manage classroom assignments</p>
                                <a href="{{ route('timetable.classrooms') }}" class="btn btn-warning">
                                    <i class="fas fa-building me-2"></i>Manage
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Timetables -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-history me-2"></i>Recent Timetables
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($recentTimetables->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Course</th>
                                            <th>Subject</th>
                                            <th>Teacher</th>
                                            <th>Day</th>
                                            <th>Time</th>
                                            <th>Classroom</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentTimetables as $timetable)
                                            <tr>
                                                <td>
                                                    <div><strong>{{ $timetable->course->name ?? 'N/A' }}</strong></div>
                                                    <small class="text-muted">{{ $timetable->semester }}</small>
                                                </td>
                                                <td>
                                                    <div>{{ $timetable->subject->name ?? 'N/A' }}</div>
                                                </td>
                                                <td>
                                                    <div>{{ $timetable->teacher->first_name ?? 'N/A' }} {{ $timetable->teacher->last_name ?? '' }}</div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-primary">{{ ucfirst($timetable->day_of_week) }}</span>
                                                </td>
                                                <td>
                                                    <small>{{ $timetable->start_time }} - {{ $timetable->end_time }}</small>
                                                </td>
                                                <td>
                                                    <small>{{ $timetable->classroom->name ?? 'N/A' }}</small>
                                                </td>
                                                <td>
                                                    @if($timetable->is_active)
                                                        <span class="badge bg-success">Active</span>
                                                    @else
                                                        <span class="badge bg-secondary">Inactive</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('timetable.view', $timetable->id) }}" 
                                                       class="btn btn-sm btn-outline-primary" title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('timetable.edit', $timetable->id) }}" 
                                                       class="btn btn-sm btn-outline-warning" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-calendar fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No recent timetables</h5>
                                <p class="text-muted">Start by creating timetable entries.</p>
                                <a href="{{ route('timetable.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>Add Timetable
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Timetable Overview -->
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-info-circle me-2"></i>Timetable Overview
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Total Teachers:</span>
                                            <span class="badge bg-info">{{ $stats['total_teachers'] }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Teachers Scheduled:</span>
                                            <span class="badge bg-success">{{ $stats['teachers_with_timetables'] }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Total Courses:</span>
                                            <span class="badge bg-warning">{{ $stats['total_courses'] }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Courses Scheduled:</span>
                                            <span class="badge bg-primary">{{ $stats['courses_with_timetables'] }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Total Classrooms:</span>
                                            <span class="badge bg-secondary">{{ $stats['total_classrooms'] }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Active Classrooms:</span>
                                            <span class="badge bg-success">{{ $stats['active_classrooms'] }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-list me-2"></i>Quick Actions
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <a href="{{ route('timetable.timetables') }}" class="btn btn-outline-primary btn-sm w-100 mb-2">
                                            <i class="fas fa-calendar me-1"></i>View All Timetables
                                        </a>
                                        <a href="{{ route('timetable.teacher') }}" class="btn btn-outline-info btn-sm w-100 mb-2">
                                            <i class="fas fa-chalkboard-teacher me-1"></i>Teacher Schedules
                                        </a>
                                    </div>
                                    <div class="col-md-6">
                                        <a href="{{ route('timetable.export') }}?type=timetables&format=csv" class="btn btn-outline-success btn-sm w-100 mb-2">
                                            <i class="fas fa-download me-1"></i>Export Timetables
                                        </a>
                                        <a href="{{ route('timetable.export') }}?type=classrooms&format=csv" class="btn btn-outline-warning btn-sm w-100 mb-2">
                                            <i class="fas fa-download me-1"></i>Export Classrooms
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
