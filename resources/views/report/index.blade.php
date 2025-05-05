<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports & Analytics - School SIS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                    <h2><i class="fas fa-chart-bar me-2"></i>Reports & Analytics</h2>
                    <div>
                        <a href="{{ route('reports.export') }}?type=students&format=csv" class="btn btn-outline-success me-2">
                            <i class="fas fa-download me-2"></i>Export Data
                        </a>
                    </div>
                </div>

                <!-- Key Statistics Cards -->
                <div class="row mb-4">
                    <div class="col-md-2">
                        <div class="card text-center">
                            <div class="card-body">
                                <i class="fas fa-users fa-2x text-primary mb-2"></i>
                                <h4 class="card-title">{{ $stats['total_students'] }}</h4>
                                <p class="card-text text-muted">Total Students</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card text-center">
                            <div class="card-body">
                                <i class="fas fa-chalkboard-teacher fa-2x text-success mb-2"></i>
                                <h4 class="card-title">{{ $stats['total_teachers'] }}</h4>
                                <p class="card-text text-muted">Total Teachers</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card text-center">
                            <div class="card-body">
                                <i class="fas fa-building fa-2x text-warning mb-2"></i>
                                <h4 class="card-title">{{ $stats['total_departments'] }}</h4>
                                <p class="card-text text-muted">Departments</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card text-center">
                            <div class="card-body">
                                <i class="fas fa-book fa-2x text-info mb-2"></i>
                                <h4 class="card-title">{{ $stats['total_courses'] }}</h4>
                                <p class="card-text text-muted">Courses</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card text-center">
                            <div class="card-body">
                                <i class="fas fa-user-graduate fa-2x text-dark mb-2"></i>
                                <h4 class="card-title">{{ $stats['total_graduates'] }}</h4>
                                <p class="card-text text-muted">Graduates</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card text-center">
                            <div class="card-body">
                                <i class="fas fa-calendar-alt fa-2x text-danger mb-2"></i>
                                <h4 class="card-title">{{ $stats['total_events'] }}</h4>
                                <p class="card-text text-muted">Events</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Report Categories -->
                <div class="row">
                    <div class="col-md-3">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-users fa-3x text-primary mb-3"></i>
                                <h5 class="card-title">Student Reports</h5>
                                <p class="card-text">Comprehensive student analytics, demographics, and performance metrics</p>
                                <a href="{{ route('reports.students') }}" class="btn btn-primary">
                                    <i class="fas fa-chart-line me-2"></i>View Reports
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-chalkboard-teacher fa-3x text-success mb-3"></i>
                                <h5 class="card-title">Teacher Reports</h5>
                                <p class="card-text">Faculty performance, course load analysis, and department statistics</p>
                                <a href="{{ route('reports.teachers') }}" class="btn btn-success">
                                    <i class="fas fa-chart-line me-2"></i>View Reports
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-graduation-cap fa-3x text-warning mb-3"></i>
                                <h5 class="card-title">Academic Reports</h5>
                                <p class="card-text">Grade distributions, course performance, and academic analytics</p>
                                <a href="{{ route('reports.academic') }}" class="btn btn-warning">
                                    <i class="fas fa-chart-line me-2"></i>View Reports
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-user-graduate fa-3x text-dark mb-3"></i>
                                <h5 class="card-title">Graduate Reports</h5>
                                <p class="card-text">Alumni statistics, employment rates, and career outcomes</p>
                                <a href="{{ route('reports.graduates') }}" class="btn btn-dark">
                                    <i class="fas fa-chart-line me-2"></i>View Reports
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Section -->
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-chart-pie me-2"></i>Student Gender Distribution
                                </h5>
                            </div>
                            <div class="card-body">
                                <canvas id="genderChart" width="400" height="200"></canvas>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-chart-bar me-2"></i>Department Distribution
                                </h5>
                            </div>
                            <div class="card-body">
                                <canvas id="departmentChart" width="400" height="200"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="row mt-4">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-clock me-2"></i>Recent Activity
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>New Students (30 days)</span>
                                    <span class="badge bg-primary">{{ $stats['recent_students'] }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>New Grades (30 days)</span>
                                    <span class="badge bg-success">{{ $stats['recent_grades'] }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Upcoming Events</span>
                                    <span class="badge bg-warning">{{ $stats['upcoming_events'] }}</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>Graduate Employment Rate</span>
                                    <span class="badge bg-info">{{ $stats['employment_rate'] }}%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-list me-2"></i>Quick Actions
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <a href="{{ route('reports.export') }}?type=students&format=csv" class="btn btn-outline-primary btn-sm w-100 mb-2">
                                            <i class="fas fa-download me-1"></i>Export Students
                                        </a>
                                    </div>
                                    <div class="col-md-3">
                                        <a href="{{ route('reports.export') }}?type=teachers&format=csv" class="btn btn-outline-success btn-sm w-100 mb-2">
                                            <i class="fas fa-download me-1"></i>Export Teachers
                                        </a>
                                    </div>
                                    <div class="col-md-3">
                                        <a href="{{ route('reports.export') }}?type=grades&format=csv" class="btn btn-outline-warning btn-sm w-100 mb-2">
                                            <i class="fas fa-download me-1"></i>Export Grades
                                        </a>
                                    </div>
                                    <div class="col-md-3">
                                        <a href="{{ route('reports.export') }}?type=graduates&format=csv" class="btn btn-outline-dark btn-sm w-100 mb-2">
                                            <i class="fas fa-download me-1"></i>Export Graduates
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
    <script>
        // Gender Distribution Chart
        const genderCtx = document.getElementById('genderChart').getContext('2d');
        new Chart(genderCtx, {
            type: 'doughnut',
            data: {
                labels: ['Male', 'Female'],
                datasets: [{
                    data: [{{ $stats['male_students'] }}, {{ $stats['female_students'] }}],
                    backgroundColor: ['#007bff', '#e83e8c'],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Department Distribution Chart
        const departmentCtx = document.getElementById('departmentChart').getContext('2d');
        const departmentData = @json($stats['department_stats']);
        const labels = departmentData.map(dept => dept.name);
        const studentCounts = departmentData.map(dept => dept.students_count);
        
        new Chart(departmentCtx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Students per Department',
                    data: studentCounts,
                    backgroundColor: 'rgba(54, 162, 235, 0.8)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    </script>
</body>
</html>
