<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management - School SIS</title>
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
                    <h2><i class="fas fa-book me-2"></i>Library Management</h2>
                    <div>
                        <a href="{{ route('library.reports') }}" class="btn btn-outline-info me-2">
                            <i class="fas fa-chart-bar me-2"></i>Reports
                        </a>
                        <a href="{{ route('library.borrow') }}" class="btn btn-success me-2">
                            <i class="fas fa-hand-holding me-2"></i>Borrow Book
                        </a>
                        <a href="{{ route('library.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Add Book
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

                <!-- Library Statistics -->
                <div class="row mb-4">
                    <div class="col-md-2">
                        <div class="card text-center">
                            <div class="card-body">
                                <i class="fas fa-books fa-2x text-primary mb-2"></i>
                                <h4 class="card-title">{{ $stats['total_books'] }}</h4>
                                <p class="card-text text-muted">Total Books</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card text-center">
                            <div class="card-body">
                                <i class="fas fa-copy fa-2x text-success mb-2"></i>
                                <h4 class="card-title">{{ $stats['total_copies'] }}</h4>
                                <p class="card-text text-muted">Total Copies</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card text-center">
                            <div class="card-body">
                                <i class="fas fa-check-circle fa-2x text-info mb-2"></i>
                                <h4 class="card-title">{{ $stats['available_copies'] }}</h4>
                                <p class="card-text text-muted">Available</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card text-center">
                            <div class="card-body">
                                <i class="fas fa-hand-holding fa-2x text-warning mb-2"></i>
                                <h4 class="card-title">{{ $stats['borrowed_copies'] }}</h4>
                                <p class="card-text text-muted">Borrowed</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card text-center">
                            <div class="card-body">
                                <i class="fas fa-exclamation-triangle fa-2x text-danger mb-2"></i>
                                <h4 class="card-title">{{ $stats['overdue_borrowings'] }}</h4>
                                <p class="card-text text-muted">Overdue</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card text-center">
                            <div class="card-body">
                                <i class="fas fa-tags fa-2x text-dark mb-2"></i>
                                <h4 class="card-title">{{ $stats['total_categories'] }}</h4>
                                <p class="card-text text-muted">Categories</p>
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
                                <h5 class="card-title">Add Book</h5>
                                <p class="card-text">Add new books to the library collection</p>
                                <a href="{{ route('library.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>Add Book
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body text-center">
                                <i class="fas fa-hand-holding fa-3x text-success mb-3"></i>
                                <h5 class="card-title">Borrow Book</h5>
                                <p class="card-text">Process book borrowing for students and teachers</p>
                                <a href="{{ route('library.borrow') }}" class="btn btn-success">
                                    <i class="fas fa-hand-holding me-2"></i>Borrow
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body text-center">
                                <i class="fas fa-undo fa-3x text-warning mb-3"></i>
                                <h5 class="card-title">Return Book</h5>
                                <p class="card-text">Process book returns and update inventory</p>
                                <a href="{{ route('library.borrowings') }}" class="btn btn-warning">
                                    <i class="fas fa-undo me-2"></i>View Borrowings
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body text-center">
                                <i class="fas fa-tags fa-3x text-info mb-3"></i>
                                <h5 class="card-title">Categories</h5>
                                <p class="card-text">Manage book categories and classifications</p>
                                <a href="{{ route('library.categories') }}" class="btn btn-info">
                                    <i class="fas fa-tags me-2"></i>Manage
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Borrowings -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-history me-2"></i>Recent Borrowings
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($recentBorrowings->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Book</th>
                                            <th>Borrower</th>
                                            <th>Borrow Date</th>
                                            <th>Due Date</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentBorrowings as $borrowing)
                                            <tr>
                                                <td>
                                                    <div><strong>{{ $borrowing->book->title }}</strong></div>
                                                    <small class="text-muted">{{ $borrowing->book->author }}</small>
                                                </td>
                                                <td>
                                                    <div>{{ $borrowing->borrower->first_name }} {{ $borrowing->borrower->last_name }}</div>
                                                    <small class="text-muted">{{ ucfirst($borrowing->borrower_type) }}</small>
                                                </td>
                                                <td>
                                                    <small>{{ \Carbon\Carbon::parse($borrowing->borrow_date)->format('M d, Y') }}</small>
                                                </td>
                                                <td>
                                                    <small>{{ \Carbon\Carbon::parse($borrowing->due_date)->format('M d, Y') }}</small>
                                                </td>
                                                <td>
                                                    @if($borrowing->return_date)
                                                        <span class="badge bg-success">Returned</span>
                                                    @elseif($borrowing->due_date < Carbon\Carbon::today())
                                                        <span class="badge bg-danger">Overdue</span>
                                                    @else
                                                        <span class="badge bg-warning">Active</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if(!$borrowing->return_date)
                                                        <a href="{{ route('library.return', $borrowing->id) }}" 
                                                           class="btn btn-sm btn-outline-success" title="Return Book">
                                                            <i class="fas fa-undo"></i>
                                                        </a>
                                                    @else
                                                        <span class="text-muted">Returned</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-book fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No recent borrowings</h5>
                                <p class="text-muted">Start by borrowing books to students and teachers.</p>
                                <a href="{{ route('library.borrow') }}" class="btn btn-success">
                                    <i class="fas fa-hand-holding me-2"></i>Borrow Book
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-info-circle me-2"></i>Library Overview
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Active Borrowings:</span>
                                            <span class="badge bg-warning">{{ $stats['active_borrowings'] }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Overdue Books:</span>
                                            <span class="badge bg-danger">{{ $stats['overdue_borrowings'] }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Recent Additions:</span>
                                            <span class="badge bg-info">{{ $stats['recent_books'] }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Utilization Rate:</span>
                                            <span class="badge bg-primary">
                                                {{ $stats['total_copies'] > 0 ? round(($stats['borrowed_copies'] / $stats['total_copies']) * 100, 1) : 0 }}%
                                            </span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Availability Rate:</span>
                                            <span class="badge bg-success">
                                                {{ $stats['total_copies'] > 0 ? round(($stats['available_copies'] / $stats['total_copies']) * 100, 1) : 0 }}%
                                            </span>
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
                                        <a href="{{ route('library.books') }}" class="btn btn-outline-primary btn-sm w-100 mb-2">
                                            <i class="fas fa-books me-1"></i>View All Books
                                        </a>
                                        <a href="{{ route('library.borrowings') }}" class="btn btn-outline-warning btn-sm w-100 mb-2">
                                            <i class="fas fa-list me-1"></i>View Borrowings
                                        </a>
                                    </div>
                                    <div class="col-md-6">
                                        <a href="{{ route('library.export') }}?type=books&format=csv" class="btn btn-outline-success btn-sm w-100 mb-2">
                                            <i class="fas fa-download me-1"></i>Export Books
                                        </a>
                                        <a href="{{ route('library.export') }}?type=borrowings&format=csv" class="btn btn-outline-info btn-sm w-100 mb-2">
                                            <i class="fas fa-download me-1"></i>Export Borrowings
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
