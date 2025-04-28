<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management - School SIS</title>
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
                    <h2><i class="fas fa-users-cog me-2"></i>User Management</h2>
                    <a href="{{ route('usermanagement.create') }}" class="btn btn-dark">
                        <i class="fas fa-plus me-2"></i>Add New User
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
                        <h5 class="mb-0">User List</h5>
                    </div>
                    <div class="card-body">
                        @if($users->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>ID</th>
                                            <th>User</th>
                                            <th>Role</th>
                                            <th>Department</th>
                                            <th>Contact</th>
                                            <th>Join Date</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($users as $user)
                                            <tr>
                                                <td>{{ $user->id }}</td>
                                                <td>
                                                    <div><strong>{{ $user->name }}</strong></div>
                                                    <small class="text-muted">{{ $user->email }}</small>
                                                    @if($user->username)
                                                        <br><small class="text-muted">@{{ $user->username }}</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    @switch($user->role)
                                                        @case('admin')
                                                            <span class="badge bg-danger">Admin</span>
                                                            @break
                                                        @case('teacher')
                                                            <span class="badge bg-success">Teacher</span>
                                                            @break
                                                        @case('student')
                                                            <span class="badge bg-primary">Student</span>
                                                            @break
                                                        @case('staff')
                                                            <span class="badge bg-warning">Staff</span>
                                                            @break
                                                        @default
                                                            <span class="badge bg-secondary">{{ ucfirst($user->role) }}</span>
                                                    @endswitch
                                                </td>
                                                <td>
                                                    @if($user->department)
                                                        <span class="badge bg-info">{{ $user->department->name }}</span>
                                                    @else
                                                        <span class="text-muted">N/A</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($user->phone)
                                                        <div><i class="fas fa-phone me-1"></i>{{ $user->phone }}</div>
                                                    @endif
                                                    @if($user->address)
                                                        <small class="text-muted">{{ Str::limit($user->address, 30) }}</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($user->join_date)
                                                        <small>{{ \Carbon\Carbon::parse($user->join_date)->format('M d, Y') }}</small>
                                                    @else
                                                        <span class="text-muted">N/A</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($user->is_active)
                                                        <span class="badge bg-success">Active</span>
                                                    @else
                                                        <span class="badge bg-secondary">Inactive</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('usermanagement.show', $user->id) }}" 
                                                           class="btn btn-sm btn-outline-info" title="View">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('usermanagement.edit', $user->id) }}" 
                                                           class="btn btn-sm btn-outline-warning" title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        @if($user->id !== auth()->id())
                                                            <form action="{{ route('usermanagement.toggle-status', $user->id) }}" 
                                                                  method="POST" class="d-inline">
                                                                @csrf
                                                                <button type="submit" class="btn btn-sm btn-outline-{{ $user->is_active ? 'warning' : 'success' }}" 
                                                                        title="{{ $user->is_active ? 'Deactivate' : 'Activate' }}">
                                                                    <i class="fas fa-{{ $user->is_active ? 'pause' : 'play' }}"></i>
                                                                </button>
                                                            </form>
                                                            <form action="{{ route('usermanagement.destroy', $user->id) }}" 
                                                                  method="POST" class="d-inline" 
                                                                  onsubmit="return confirm('Are you sure you want to delete this user?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-users-cog fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No users found</h5>
                                <p class="text-muted">Get started by adding your first user.</p>
                                <a href="{{ route('usermanagement.create') }}" class="btn btn-dark">
                                    <i class="fas fa-plus me-2"></i>Add User
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
