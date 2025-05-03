<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - School SIS</title>
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
                    <h2><i class="fas fa-cog me-2"></i>System Settings</h2>
                    <div>
                        <a href="{{ route('settings.backup') }}" class="btn btn-outline-success me-2">
                            <i class="fas fa-download me-2"></i>Backup Settings
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

                <!-- Settings Categories -->
                <div class="row">
                    <div class="col-md-3">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-school fa-3x text-primary mb-3"></i>
                                <h5 class="card-title">General Settings</h5>
                                <p class="card-text">School information, contact details, and basic configuration</p>
                                <a href="{{ route('settings.general') }}" class="btn btn-primary">
                                    <i class="fas fa-edit me-2"></i>Configure
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-graduation-cap fa-3x text-success mb-3"></i>
                                <h5 class="card-title">Academic Settings</h5>
                                <p class="card-text">Academic year, semesters, grading system, and attendance policies</p>
                                <a href="{{ route('settings.academic') }}" class="btn btn-success">
                                    <i class="fas fa-edit me-2"></i>Configure
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-envelope fa-3x text-warning mb-3"></i>
                                <h5 class="card-title">Email Settings</h5>
                                <p class="card-text">Email configuration, notifications, and communication settings</p>
                                <a href="{{ route('settings.email') }}" class="btn btn-warning">
                                    <i class="fas fa-edit me-2"></i>Configure
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-server fa-3x text-danger mb-3"></i>
                                <h5 class="card-title">System Settings</h5>
                                <p class="card-text">Security, maintenance, backup, and system configuration</p>
                                <a href="{{ route('settings.system') }}" class="btn btn-danger">
                                    <i class="fas fa-edit me-2"></i>Configure
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Settings Backup/Restore -->
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-download me-2"></i>Backup Settings
                                </h5>
                            </div>
                            <div class="card-body">
                                <p class="card-text">Create a backup of all current settings for safekeeping or migration.</p>
                                <a href="{{ route('settings.backup') }}" class="btn btn-outline-success">
                                    <i class="fas fa-download me-2"></i>Download Backup
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-upload me-2"></i>Restore Settings
                                </h5>
                            </div>
                            <div class="card-body">
                                <p class="card-text">Restore settings from a previously created backup file.</p>
                                <form action="{{ route('settings.restore') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="input-group">
                                        <input type="file" class="form-control" name="backup_file" accept=".json" required>
                                        <button type="submit" class="btn btn-outline-warning">
                                            <i class="fas fa-upload me-2"></i>Restore
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Current Settings Summary -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-info-circle me-2"></i>Current Settings Summary
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="text-center">
                                            <h6 class="text-muted">School Name</h6>
                                            <p class="fw-bold">{{ $settings['general']['school_name'] ?? 'Not Set' }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="text-center">
                                            <h6 class="text-muted">Academic Year</h6>
                                            <p class="fw-bold">{{ $settings['academic']['academic_year'] ?? 'Not Set' }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="text-center">
                                            <h6 class="text-muted">Grade Scale</h6>
                                            <p class="fw-bold">{{ $settings['academic']['grade_scale'] ?? 'Not Set' }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="text-center">
                                            <h6 class="text-muted">Email Notifications</h6>
                                            <p class="fw-bold">
                                                @if($settings['email']['enable_email_notifications'] ?? false)
                                                    <span class="badge bg-success">Enabled</span>
                                                @else
                                                    <span class="badge bg-danger">Disabled</span>
                                                @endif
                                            </p>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
