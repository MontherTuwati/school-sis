<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Examination Management - School SIS</title>
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
                    <h2><i class="fas fa-file-alt me-2"></i>Examination Management</h2>
                    <div>
                        <a href="{{ route('examination.reports') }}" class="btn btn-outline-info me-2">
                            <i class="fas fa-chart-bar me-2"></i>Reports
                        </a>
                        <a href="{{ route('examination.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Create Exam
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

                <!-- Examination Statistics -->
                <div class="row mb-4">
                    <div class="col-md-2">
                        <div class="card text-center">
                            <div class="card-body">
                                <i class="fas fa-file-alt fa-2x text-primary mb-2"></i>
                                <h4 class="card-title">{{ $stats['total_examinations'] }}</h4>
                                <p class="card-text text-muted">Total Exams</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card text-center">
                            <div class="card-body">
                                <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                                <h4 class="card-title">{{ $stats['active_examinations'] }}</h4>
                                <p class="card-text text-muted">Active</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card text-center">
                            <div class="card-body">
                                <i class="fas fa-clipboard-check fa-2x text-info mb-2"></i>
                                <h4 class="card-title">{{ $stats['total_results'] }}</h4>
                                <p class="card-text text-muted">Total Results</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card text-center">
                            <div class="card-body">
                                <i class="fas fa-thumbs-up fa-2x text-success mb-2"></i>
                                <h4 class="card-title">{{ $stats['passed_results'] }}</h4>
                                <p class="card-text text-muted">Passed</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card text-center">
                            <div class="card-body">
                                <i class="fas fa-calendar fa-2x text-warning mb-2"></i>
                                <h4 class="card-title">{{ $stats['upcoming_exams'] }}</h4>
                                <p class="card-text text-muted">Upcoming</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card text-center">
                            <div class="card-body">
                                <i class="fas fa-clock fa-2x text-dark mb-2"></i>
                                <h4 class="card-title">{{ $stats['recent_examinations'] }}</h4>
                                <p class="card-text text-muted">Recent (30d)</p>
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
                                <h5 class="card-title">Create Exam</h5>
                                <p class="card-text">Create new examination</p>
                                <a href="{{ route('examination.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>Create
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body text-center">
                                <i class="fas fa-calendar-alt fa-3x text-success mb-3"></i>
                                <h5 class="card-title">Exam Schedules</h5>
                                <p class="card-text">Manage exam schedules</p>
                                <a href="{{ route('examination.schedules') }}" class="btn btn-success">
                                    <i class="fas fa-calendar-alt me-2"></i>Manage
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body text-center">
                                <i class="fas fa-chart-line fa-3x text-info mb-3"></i>
                                <h5 class="card-title">Exam Results</h5>
                                <p class="card-text">Manage exam results</p>
                                <a href="{{ route('examination.results') }}" class="btn btn-info">
                                    <i class="fas fa-chart-line me-2"></i>Manage
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body text-center">
                                <i class="fas fa-file-alt fa-3x text-warning mb-3"></i>
                                <h5 class="card-title">All Examinations</h5>
                                <p class="card-text">View all examinations</p>
                                <a href="{{ route('examination.examinations') }}" class="btn btn-warning">
                                    <i class="fas fa-file-alt me-2"></i>View
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Upcoming Exams -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-calendar me-2"></i>Upcoming Examinations
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($upcomingExams->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Title</th>
                                            <th>Course</th>
                                            <th>Subject</th>
                                            <th>Type</th>
                                            <th>Date</th>
                                            <th>Time</th>
                                            <th>Duration</th>
                                            <th>Total Marks</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($upcomingExams as $exam)
                                            <tr>
                                                <td>
                                                    <div><strong>{{ $exam->title }}</strong></div>
                                                    <small class="text-muted">{{ $exam->semester }}</small>
                                                </td>
                                                <td>
                                                    <div>{{ $exam->course->name ?? 'N/A' }}</div>
                                                </td>
                                                <td>
                                                    <div>{{ $exam->subject->name ?? 'N/A' }}</div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-{{ $exam->exam_type == 'final' ? 'danger' : ($exam->exam_type == 'midterm' ? 'warning' : 'info') }}">
                                                        {{ ucfirst($exam->exam_type) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <small>{{ \Carbon\Carbon::parse($exam->exam_date)->format('M d, Y') }}</small>
                                                </td>
                                                <td>
                                                    <small>{{ $exam->start_time }} - {{ $exam->end_time }}</small>
                                                </td>
                                                <td>
                                                    <small>{{ $exam->duration }} min</small>
                                                </td>
                                                <td>
                                                    <span class="badge bg-primary">{{ $exam->total_marks }}</span>
                                                </td>
                                                <td>
                                                    @if($exam->is_active)
                                                        <span class="badge bg-success">Active</span>
                                                    @else
                                                        <span class="badge bg-secondary">Inactive</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('examination.view', $exam->id) }}" 
                                                       class="btn btn-sm btn-outline-primary" title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('examination.edit', $exam->id) }}" 
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
                                <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No upcoming examinations</h5>
                                <p class="text-muted">Start by creating new examinations.</p>
                                <a href="{{ route('examination.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>Create Exam
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Examination Overview -->
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-info-circle me-2"></i>Examination Overview
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Total Results:</span>
                                            <span class="badge bg-info">{{ $stats['total_results'] }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Passed Results:</span>
                                            <span class="badge bg-success">{{ $stats['passed_results'] }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Failed Results:</span>
                                            <span class="badge bg-danger">{{ $stats['failed_results'] }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Total Schedules:</span>
                                            <span class="badge bg-primary">{{ $stats['total_schedules'] }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Upcoming Exams:</span>
                                            <span class="badge bg-warning">{{ $stats['upcoming_exams'] }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Recent Results (30d):</span>
                                            <span class="badge bg-secondary">{{ $stats['recent_results'] }}</span>
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
                                        <a href="{{ route('examination.examinations') }}" class="btn btn-outline-primary btn-sm w-100 mb-2">
                                            <i class="fas fa-file-alt me-1"></i>View All Exams
                                        </a>
                                        <a href="{{ route('examination.schedules') }}" class="btn btn-outline-success btn-sm w-100 mb-2">
                                            <i class="fas fa-calendar-alt me-1"></i>View Schedules
                                        </a>
                                    </div>
                                    <div class="col-md-6">
                                        <a href="{{ route('examination.export') }}?type=examinations&format=csv" class="btn btn-outline-info btn-sm w-100 mb-2">
                                            <i class="fas fa-download me-1"></i>Export Exams
                                        </a>
                                        <a href="{{ route('examination.export') }}?type=results&format=csv" class="btn btn-outline-warning btn-sm w-100 mb-2">
                                            <i class="fas fa-download me-1"></i>Export Results
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
