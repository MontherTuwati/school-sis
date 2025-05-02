<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Graduates - School SIS</title>
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
                    <h2><i class="fas fa-user-graduate me-2"></i>Graduates</h2>
                    <div>
                        <a href="{{ route('graduates.statistics') }}" class="btn btn-outline-dark me-2">
                            <i class="fas fa-chart-bar me-2"></i>Statistics
                        </a>
                        <a href="{{ route('graduates.alumni') }}" class="btn btn-outline-dark me-2">
                            <i class="fas fa-users me-2"></i>Alumni Directory
                        </a>
                        <a href="{{ route('graduates.create') }}" class="btn btn-dark">
                            <i class="fas fa-plus me-2"></i>Add New Graduate
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

                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h5 class="mb-0">Graduate List</h5>
                            </div>
                            <div class="col-auto">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('graduates.index') }}" class="btn btn-outline-dark btn-sm">
                                        <i class="fas fa-list me-1"></i>List
                                    </a>
                                    <a href="{{ route('graduates.grid') }}" class="btn btn-outline-dark btn-sm">
                                        <i class="fas fa-th me-1"></i>Grid
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($graduates->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Graduate</th>
                                            <th>Degree</th>
                                            <th>Graduation Date</th>
                                            <th>GPA</th>
                                            <th>Employment Status</th>
                                            <th>Current Position</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($graduates as $graduate)
                                            <tr>
                                                <td>
                                                    <div><strong>{{ $graduate->student->first_name }} {{ $graduate->student->last_name }}</strong></div>
                                                    <small class="text-muted">{{ $graduate->student->email }}</small>
                                                    @if($graduate->student->department)
                                                        <br><small class="text-muted">{{ $graduate->student->department->name }}</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div>{{ $graduate->degree_type }}</div>
                                                    <small class="text-muted">{{ $graduate->major }}</small>
                                                    @if($graduate->honors)
                                                        <br><span class="badge bg-warning">{{ $graduate->honors }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <small>{{ \Carbon\Carbon::parse($graduate->graduation_date)->format('M d, Y') }}</small>
                                                </td>
                                                <td>
                                                    <span class="badge bg-{{ $graduate->gpa >= 3.5 ? 'success' : ($graduate->gpa >= 3.0 ? 'info' : ($graduate->gpa >= 2.5 ? 'warning' : 'danger')) }}">
                                                        {{ $graduate->gpa }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @switch($graduate->employment_status)
                                                        @case('employed')
                                                            <span class="badge bg-success">Employed</span>
                                                            @break
                                                        @case('unemployed')
                                                            <span class="badge bg-danger">Unemployed</span>
                                                            @break
                                                        @case('self-employed')
                                                            <span class="badge bg-info">Self-Employed</span>
                                                            @break
                                                        @case('continuing_education')
                                                            <span class="badge bg-warning">Continuing Education</span>
                                                            @break
                                                        @default
                                                            <span class="badge bg-secondary">{{ ucfirst($graduate->employment_status) }}</span>
                                                    @endswitch
                                                </td>
                                                <td>
                                                    @if($graduate->employer && $graduate->job_title)
                                                        <div><strong>{{ $graduate->job_title }}</strong></div>
                                                        <small class="text-muted">{{ $graduate->employer }}</small>
                                                        @if($graduate->salary_range)
                                                            <br><small class="text-muted">{{ $graduate->salary_range }}</small>
                                                        @endif
                                                    @else
                                                        <span class="text-muted">N/A</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('graduates.show', $graduate->id) }}" 
                                                           class="btn btn-sm btn-outline-info" title="View">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('graduates.edit', $graduate->id) }}" 
                                                           class="btn btn-sm btn-outline-warning" title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <form action="{{ route('graduates.destroy', $graduate->id) }}" 
                                                              method="POST" class="d-inline" 
                                                              onsubmit="return confirm('Are you sure you want to delete this graduate record?')">
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
                                <i class="fas fa-user-graduate fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No graduates found</h5>
                                <p class="text-muted">Get started by adding your first graduate record.</p>
                                <a href="{{ route('graduates.create') }}" class="btn btn-dark">
                                    <i class="fas fa-plus me-2"></i>Add Graduate
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
