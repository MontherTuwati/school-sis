@extends('layouts.master')
@section('content')
{{-- message --}}
{!! Toastr::message() !!}

<style>
    /* Exam List Styling */
    .page-wrapper {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: 100vh;
    }
    
    .content.container-fluid {
        padding: 2rem;
    }
    
    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem;
        border-radius: 15px;
        margin-bottom: 2rem;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
    
    .page-title {
        font-size: 2rem;
        font-weight: bold;
        margin-bottom: 0.5rem;
    }
    
    .breadcrumb {
        background: transparent;
        padding: 0;
        margin: 0;
    }
    
    .breadcrumb-item a {
        color: rgba(255, 255, 255, 0.8);
        text-decoration: none;
    }
    
    .breadcrumb-item.active {
        color: white;
    }
    
    /* Stats Cards */
    .stats-card {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        border: none;
        margin-bottom: 1.5rem;
    }
    
    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    }
    
    .stats-icon {
        width: 60px;
        height: 60px;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
    }
    
    .stats-info h3 {
        font-size: 2rem;
        font-weight: bold;
        margin: 0;
        color: #343a40;
    }
    
    .stats-info p {
        color: #6c757d;
        margin: 0;
        font-weight: 500;
    }
    
    /* Exam Table */
    .card {
        background: white;
        border: none;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }
    
    .card-header {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-bottom: 1px solid #dee2e6;
        padding: 1.5rem;
    }
    
    .card-title {
        font-size: 1.25rem;
        font-weight: bold;
        color: #343a40;
        margin: 0;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 10px;
        padding: 0.5rem 1rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }
    
    .btn-outline-primary {
        border: 2px solid #667eea;
        color: #667eea;
        border-radius: 10px;
        padding: 0.5rem 1rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .btn-outline-primary:hover {
        background: #667eea;
        color: white;
        transform: translateY(-2px);
    }
    
    /* Table Styling */
    .table {
        margin-bottom: 0;
    }
    
    .table th {
        background: #f8f9fa;
        border: none;
        font-weight: 600;
        color: #495057;
        padding: 1rem;
    }
    
    .table td {
        border: none;
        padding: 1rem;
        vertical-align: middle;
    }
    
    .table tbody tr {
        transition: all 0.3s ease;
    }
    
    .table tbody tr:hover {
        background: #f8f9fa;
        transform: scale(1.01);
    }
    
    /* Status Badges */
    .badge {
        padding: 0.5rem 0.75rem;
        border-radius: 20px;
        font-weight: 500;
    }
    
    .badge-upcoming {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
    }
    
    .badge-today {
        background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
        color: white;
    }
    
    .badge-completed {
        background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
        color: white;
    }
    
    .badge-inactive {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        color: white;
    }
    
    /* Action Buttons */
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
        border-radius: 8px;
        margin: 0 0.25rem;
    }
    
    .btn-view {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        border: none;
    }
    
    .btn-edit {
        background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
        color: white;
        border: none;
    }
    
    .btn-delete {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        color: white;
        border: none;
    }
    
    .btn-toggle {
        background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        color: white;
        border: none;
    }
    
    /* Exam Type Badges */
    .exam-type {
        padding: 0.25rem 0.5rem;
        border-radius: 15px;
        font-size: 0.75rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .exam-type-midterm {
        background: #e3f2fd;
        color: #1976d2;
    }
    
    .exam-type-final {
        background: #fce4ec;
        color: #c2185b;
    }
    
    .exam-type-quiz {
        background: #f3e5f5;
        color: #7b1fa2;
    }
    
    .exam-type-assignment {
        background: #e8f5e8;
        color: #388e3c;
    }
    
    .exam-type-practical {
        background: #fff3e0;
        color: #f57c00;
    }
</style>

<div class="page-wrapper">
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">Exam Management</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Exams</li>
                    </ul>
                </div>
                <div class="col-auto">
                    <a href="{{ route('exam.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Add New Exam
                    </a>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="stats-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="stats-info">
                            <h3>{{ $stats['total'] }}</h3>
                            <p>Total Exams</p>
                        </div>
                        <div class="stats-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <i class="fas fa-clipboard-list"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="stats-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="stats-info">
                            <h3>{{ $stats['upcoming'] }}</h3>
                            <p>Upcoming</p>
                        </div>
                        <div class="stats-icon" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
                            <i class="fas fa-calendar-plus"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="stats-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="stats-info">
                            <h3>{{ $stats['completed'] }}</h3>
                            <p>Completed</p>
                        </div>
                        <div class="stats-icon" style="background: linear-gradient(135deg, #6c757d 0%, #495057 100%);">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="stats-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="stats-info">
                            <h3>{{ $stats['today'] }}</h3>
                            <p>Today</p>
                        </div>
                        <div class="stats-icon" style="background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);">
                            <i class="fas fa-calendar-day"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Exam List -->
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h5 class="card-title">Exam List</h5>
                            </div>
                            <div class="col-auto">
                                <a href="{{ route('exam.export') }}" class="btn btn-outline-primary me-2">
                                    <i class="fas fa-download me-2"></i>Export
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Exam Title</th>
                                        <th>Course</th>
                                        <th>Subject</th>
                                        <th>Type</th>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Duration</th>
                                        <th>Status</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($exams as $exam)
                                    <tr>
                                        <td>
                                            <h6 class="mb-0">{{ $exam->title }}</h6>
                                            <small class="text-muted">Created by {{ $exam->creator->name ?? 'N/A' }}</small>
                                        </td>
                                        <td>{{ $exam->course->name ?? 'N/A' }}</td>
                                        <td>{{ $exam->subject->name ?? 'N/A' }}</td>
                                        <td>
                                            <span class="exam-type exam-type-{{ $exam->exam_type }}">
                                                {{ $exam->getExamTypeLabel() }}
                                            </span>
                                        </td>
                                        <td>{{ $exam->formatted_date }}</td>
                                        <td>
                                            <div>{{ $exam->formatted_start_time }}</div>
                                            <small class="text-muted">to {{ $exam->formatted_end_time }}</small>
                                        </td>
                                        <td>{{ $exam->duration_formatted }}</td>
                                        <td>
                                            @if($exam->is_active)
                                                @if($exam->isUpcoming())
                                                    <span class="badge badge-upcoming">Upcoming</span>
                                                @elseif($exam->isToday())
                                                    <span class="badge badge-today">Today</span>
                                                @else
                                                    <span class="badge badge-completed">Completed</span>
                                                @endif
                                            @else
                                                <span class="badge badge-inactive">Inactive</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('exam.show', $exam->id) }}" class="btn btn-sm btn-view" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('exam.edit', $exam->id) }}" class="btn btn-sm btn-edit" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('exam.toggle-status', $exam->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-toggle" title="{{ $exam->is_active ? 'Deactivate' : 'Activate' }}">
                                                        <i class="fas fa-{{ $exam->is_active ? 'pause' : 'play' }}"></i>
                                                    </button>
                                                </form>
                                                <form action="{{ route('exam.destroy', $exam->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this exam?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-delete" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-clipboard-list fa-3x mb-3"></i>
                                                <h5>No exams found</h5>
                                                <p>Start by creating your first exam.</p>
                                                <a href="{{ route('exam.create') }}" class="btn btn-primary">
                                                    <i class="fas fa-plus me-2"></i>Create Exam
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        @if($exams->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $exams->links() }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
