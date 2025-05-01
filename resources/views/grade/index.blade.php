<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grades - School SIS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-info">
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
                    <h2><i class="fas fa-chart-line me-2"></i>Grades</h2>
                    <a href="{{ route('grades.create') }}" class="btn btn-info">
                        <i class="fas fa-plus me-2"></i>Add New Grade
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
                        <h5 class="mb-0">Grade List</h5>
                    </div>
                    <div class="card-body">
                        @if($grades->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Student</th>
                                            <th>Subject</th>
                                            <th>Course</th>
                                            <th>Semester</th>
                                            <th>Total Score</th>
                                            <th>Letter Grade</th>
                                            <th>GPA</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($grades as $grade)
                                            <tr>
                                                <td>
                                                    <div><strong>{{ $grade->student->first_name }} {{ $grade->student->last_name }}</strong></div>
                                                    <small class="text-muted">{{ $grade->student->email }}</small>
                                                </td>
                                                <td>
                                                    @if($grade->subject)
                                                        <span class="badge bg-primary">{{ $grade->subject->name }}</span>
                                                    @else
                                                        <span class="text-muted">N/A</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($grade->course)
                                                        <span class="badge bg-secondary">{{ $grade->course->title }}</span>
                                                    @else
                                                        <span class="text-muted">N/A</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <small>{{ $grade->semester }} - {{ $grade->academic_year }}</small>
                                                </td>
                                                <td>
                                                    <span class="badge bg-{{ $grade->total_score >= 90 ? 'success' : ($grade->total_score >= 80 ? 'info' : ($grade->total_score >= 70 ? 'warning' : 'danger')) }}">
                                                        {{ $grade->total_score }}%
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-{{ $grade->letter_grade == 'A' || $grade->letter_grade == 'A+' || $grade->letter_grade == 'A-' ? 'success' : ($grade->letter_grade == 'B' || $grade->letter_grade == 'B+' || $grade->letter_grade == 'B-' ? 'info' : ($grade->letter_grade == 'C' || $grade->letter_grade == 'C+' || $grade->letter_grade == 'C-' ? 'warning' : 'danger')) }}">
                                                        {{ $grade->letter_grade }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-dark">{{ $grade->gpa }}</span>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('grades.show', $grade->id) }}" 
                                                           class="btn btn-sm btn-outline-info" title="View">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('grades.edit', $grade->id) }}" 
                                                           class="btn btn-sm btn-outline-warning" title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <a href="{{ route('grades.transcript', $grade->student_id) }}" 
                                                           class="btn btn-sm btn-outline-success" title="View Transcript">
                                                            <i class="fas fa-file-alt"></i>
                                                        </a>
                                                        <form action="{{ route('grades.destroy', $grade->id) }}" 
                                                              method="POST" class="d-inline" 
                                                              onsubmit="return confirm('Are you sure you want to delete this grade?')">
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
                                <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No grades found</h5>
                                <p class="text-muted">Get started by adding your first grade record.</p>
                                <a href="{{ route('grades.create') }}" class="btn btn-info">
                                    <i class="fas fa-plus me-2"></i>Add Grade
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
