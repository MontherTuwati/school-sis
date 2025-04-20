<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teachers - School SIS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-success">
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
                    <h2><i class="fas fa-chalkboard-teacher me-2"></i>Teachers</h2>
                    <a href="{{ route('teachers.create') }}" class="btn btn-success">
                        <i class="fas fa-plus me-2"></i>Add New Teacher
                    </a>
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
                                <h5 class="mb-0">Teacher List</h5>
                            </div>
                            <div class="col-auto">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('teachers.index') }}" class="btn btn-outline-success btn-sm">
                                        <i class="fas fa-list me-1"></i>List
                                    </a>
                                    <a href="{{ route('teachers.grid') }}" class="btn btn-outline-success btn-sm">
                                        <i class="fas fa-th me-1"></i>Grid
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($teachers->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Gender</th>
                                            <th>Department</th>
                                            <th>Qualification</th>
                                            <th>Experience</th>
                                            <th>Location</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($teachers as $teacher)
                                            <tr>
                                                <td>{{ $teacher->id }}</td>
                                                <td>
                                                    <strong>{{ $teacher->full_name }}</strong>
                                                    @if($teacher->user)
                                                        <br><small class="text-muted">{{ $teacher->user->email }}</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge bg-{{ $teacher->gender == 'Male' ? 'primary' : 'pink' }}">
                                                        {{ $teacher->gender }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if($teacher->department)
                                                        <span class="badge bg-info">{{ $teacher->department->name }}</span>
                                                    @else
                                                        <span class="text-muted">N/A</span>
                                                    @endif
                                                </td>
                                                <td>{{ $teacher->qualification }}</td>
                                                <td>{{ $teacher->experience }} years</td>
                                                <td>
                                                    <small>{{ $teacher->city }}, {{ $teacher->state }}</small>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('teachers.show', $teacher->id) }}" 
                                                           class="btn btn-sm btn-outline-info" title="View">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('teachers.edit', $teacher->id) }}" 
                                                           class="btn btn-sm btn-outline-warning" title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <form action="{{ route('teachers.destroy', $teacher->id) }}" 
                                                              method="POST" class="d-inline" 
                                                              onsubmit="return confirm('Are you sure you want to delete this teacher?')">
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
                                <i class="fas fa-chalkboard-teacher fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No teachers found</h5>
                                <p class="text-muted">Get started by adding your first teacher.</p>
                                <a href="{{ route('teachers.create') }}" class="btn btn-success">
                                    <i class="fas fa-plus me-2"></i>Add Teacher
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
