@extends('layouts.master')

@section('content')
<div class="page-wrapper">
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">Event Management</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Events</li>
                    </ul>
                </div>
                <div class="col-auto text-end float-end ms-auto">
                    <a href="{{ route('events.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Add New Event
                    </a>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row">
            <div class="col-xl-3 col-sm-6 col-12">
                <div class="card stats-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="db-icon">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <div class="db-info">
                                <h6>{{ $stats['total'] }}</h6>
                                <p>Total Events</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12">
                <div class="card stats-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="db-icon">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <div class="db-info">
                                <h6>{{ $stats['upcoming'] }}</h6>
                                <p>Upcoming Events</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12">
                <div class="card stats-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="db-icon">
                                <i class="fas fa-calendar-day"></i>
                            </div>
                            <div class="db-info">
                                <h6>{{ $stats['today'] }}</h6>
                                <p>Today's Events</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12">
                <div class="card stats-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="db-icon">
                                <i class="fas fa-calendar-times"></i>
                            </div>
                            <div class="db-info">
                                <h6>{{ $stats['past'] }}</h6>
                                <p>Past Events</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Event List -->
        <div class="row">
            <div class="col-sm-12">
                <div class="card comman-shadow">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title">Event List</h5>
                            <div>
                                <a href="{{ route('events.export') }}" class="btn btn-outline-primary me-2">
                                    <i class="fas fa-download me-1"></i>Export
                                </a>
                            </div>
                        </div>

                        <!-- Search and Filters -->
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <input type="text" id="search" class="form-control" placeholder="Search events...">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <select id="type-filter" class="form-control">
                                    <option value="">All Types</option>
                                    <option value="academic">Academic</option>
                                    <option value="social">Social</option>
                                    <option value="sports">Sports</option>
                                    <option value="cultural">Cultural</option>
                                    <option value="meeting">Meeting</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select id="priority-filter" class="form-control">
                                    <option value="">All Priorities</option>
                                    <option value="high">High</option>
                                    <option value="medium">Medium</option>
                                    <option value="low">Low</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select id="status-filter" class="form-control">
                                    <option value="">All Status</option>
                                    <option value="upcoming">Upcoming</option>
                                    <option value="today">Today</option>
                                    <option value="past">Past</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <div class="d-flex gap-2">
                                    <button id="clear-filters" class="btn btn-outline-secondary btn-sm">
                                        <i class="fas fa-times me-1"></i>Clear
                                    </button>
                                    <button id="toggle-view" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-th-large me-1"></i>Grid View
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Table View -->
                        <div id="table-view" class="table-responsive">
                            <div class="table-container" style="max-height: 500px; overflow-y: auto;">
                                <table class="table table-hover">
                                    <thead class="table-light sticky-top" style="background: white; z-index: 10;">
                                        <tr>
                                            <th>Event Title</th>
                                            <th>Date & Time</th>
                                            <th>Location</th>
                                            <th>Type</th>
                                            <th>Priority</th>
                                            <th>Status</th>
                                            <th>Created By</th>
                                            <th class="text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($events as $event)
                                        <tr class="event-row" 
                                            data-title="{{ strtolower($event->title) }}"
                                            data-type="{{ $event->event_type }}"
                                            data-priority="{{ $event->priority }}"
                                            data-status="{{ $event->status }}">
                                            <td>
                                                <div>
                                                    <h6 class="mb-0">{{ $event->title }}</h6>
                                                    <small class="text-muted">{{ Str::limit($event->description, 50) }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <div>{{ $event->formatted_date }}</div>
                                                <small class="text-muted">
                                                    {{ $event->formatted_start_time }}
                                                    @if(!$event->is_all_day)
                                                        - {{ $event->formatted_end_time }}
                                                    @endif
                                                </small>
                                            </td>
                                            <td>{{ $event->location ?? 'N/A' }}</td>
                                            <td>
                                                <span class="badge bg-{{ $event->event_type_color }}">
                                                    {{ $event->event_type_label }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $event->priority_color }}">
                                                    {{ $event->priority_label }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($event->is_active)
                                                    @if($event->isUpcoming())
                                                        <span class="badge bg-success">Upcoming</span>
                                                    @elseif($event->isToday())
                                                        <span class="badge bg-warning">Today</span>
                                                    @else
                                                        <span class="badge bg-secondary">Past</span>
                                                    @endif
                                                @else
                                                    <span class="badge bg-danger">Inactive</span>
                                                @endif
                                            </td>
                                            <td>{{ $event->creator->name ?? 'N/A' }}</td>
                                            <td class="text-end">
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('events.show', $event->id) }}" class="btn btn-sm btn-view" title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('events.edit', $event->id) }}" class="btn btn-sm btn-edit" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('events.toggle-status', $event->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-toggle" title="{{ $event->is_active ? 'Deactivate' : 'Activate' }}">
                                                            <i class="fas fa-{{ $event->is_active ? 'pause' : 'play' }}"></i>
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('events.destroy', $event->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this event?')">
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
                                            <td colspan="8" class="text-center py-4">
                                                <div class="empty-state">
                                                    <i class="fas fa-calendar-alt fa-3x text-muted mb-3"></i>
                                                    <h5>No events found</h5>
                                                    <p>Start by creating your first event.</p>
                                                    <a href="{{ route('events.create') }}" class="btn btn-primary">
                                                        <i class="fas fa-plus me-2"></i>Create Event
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Grid View -->
                        <div id="grid-view" class="row" style="display: none;">
                            <div class="grid-container" style="max-height: 500px; overflow-y: auto;">
                                @forelse($events as $event)
                                <div class="col-md-6 col-lg-4 mb-3 event-card" 
                                     data-title="{{ strtolower($event->title) }}"
                                     data-type="{{ $event->event_type }}"
                                     data-priority="{{ $event->priority }}"
                                     data-status="{{ $event->status }}">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <h6 class="card-title mb-0">{{ $event->title }}</h6>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li><a class="dropdown-item" href="{{ route('events.show', $event->id) }}"><i class="fas fa-eye me-2"></i>View</a></li>
                                                        <li><a class="dropdown-item" href="{{ route('events.edit', $event->id) }}"><i class="fas fa-edit me-2"></i>Edit</a></li>
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li><a class="dropdown-item text-danger" href="#" onclick="deleteEvent({{ $event->id }})"><i class="fas fa-trash me-2"></i>Delete</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <p class="card-text small text-muted">{{ Str::limit($event->description, 80) }}</p>
                                            <div class="mb-2">
                                                <small class="text-muted">
                                                    <i class="fas fa-calendar me-1"></i>{{ $event->formatted_date }}
                                                </small>
                                            </div>
                                            <div class="mb-2">
                                                <small class="text-muted">
                                                    <i class="fas fa-clock me-1"></i>{{ $event->formatted_start_time }}
                                                    @if(!$event->is_all_day) - {{ $event->formatted_end_time }}@endif
                                                </small>
                                            </div>
                                            @if($event->location)
                                            <div class="mb-2">
                                                <small class="text-muted">
                                                    <i class="fas fa-map-marker-alt me-1"></i>{{ $event->location }}
                                                </small>
                                            </div>
                                            @endif
                                            <div class="d-flex gap-1 mb-2">
                                                <span class="badge bg-{{ $event->event_type_color }}">{{ $event->event_type_label }}</span>
                                                <span class="badge bg-{{ $event->priority_color }}">{{ $event->priority_label }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <small class="text-muted">By {{ $event->creator->name ?? 'N/A' }}</small>
                                                @if($event->is_active)
                                                    @if($event->isUpcoming())
                                                        <span class="badge bg-success">Upcoming</span>
                                                    @elseif($event->isToday())
                                                        <span class="badge bg-warning">Today</span>
                                                    @else
                                                        <span class="badge bg-secondary">Past</span>
                                                    @endif
                                                @else
                                                    <span class="badge bg-danger">Inactive</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <div class="col-12">
                                    <div class="empty-state text-center py-4">
                                        <i class="fas fa-calendar-alt fa-3x text-muted mb-3"></i>
                                        <h5>No events found</h5>
                                        <p>Start by creating your first event.</p>
                                        <a href="{{ route('events.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus me-2"></i>Create Event
                                        </a>
                                    </div>
                                </div>
                                @endforelse
                            </div>
                        </div>

                        <!-- Pagination -->
                        @if($events->hasPages())
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="text-muted">
                                Showing {{ $events->firstItem() ?? 0 }} to {{ $events->lastItem() ?? 0 }} of {{ $events->total() }} events
                            </div>
                            <div>
                                {{ $events->links() }}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Event List Styling */
.page-wrapper {
    background-color: #f8f9fa;
    min-height: 100vh;
    padding: 20px 0;
}

.page-header {
    background: white;
    padding: 20px;
    border-radius: 10px;
    margin-bottom: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.stats-card {
    border: none;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    transition: transform 0.2s;
}

.stats-card:hover {
    transform: translateY(-2px);
}

.db-icon {
    width: 50px;
    height: 50px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
    background: #667eea;
    color: white;
}

.db-icon i {
    font-size: 20px;
}

.db-info h6 {
    font-size: 24px;
    font-weight: 600;
    margin: 0;
    color: #333;
}

.db-info p {
    margin: 0;
    color: #666;
    font-size: 14px;
}

.card {
    border: none;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.card-title {
    font-weight: 600;
    color: #333;
}

.table {
    margin-bottom: 0;
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #555;
    background-color: #f8f9fa;
}

.table td {
    vertical-align: middle;
    border-top: 1px solid #f1f1f1;
}

.btn-group .btn {
    margin: 0 2px;
}

.btn-view {
    background-color: #17a2b8;
    color: white;
    border: none;
}

.btn-edit {
    background-color: #ffc107;
    color: white;
    border: none;
}

.btn-toggle {
    background-color: #6c757d;
    color: white;
    border: none;
}

.btn-delete {
    background-color: #dc3545;
    color: white;
    border: none;
}

.btn-view:hover, .btn-edit:hover, .btn-toggle:hover, .btn-delete:hover {
    opacity: 0.8;
    color: white;
}

.empty-state {
    text-align: center;
    padding: 40px 20px;
}

.empty-state i {
    color: #ccc;
}

.empty-state h5 {
    color: #666;
    margin: 15px 0 10px;
}

.empty-state p {
    color: #999;
    margin-bottom: 20px;
}

.badge {
    font-size: 11px;
    padding: 5px 8px;
}

/* Priority Colors */
.bg-danger { background-color: #dc3545 !important; }
.bg-warning { background-color: #ffc107 !important; color: #000 !important; }
.bg-success { background-color: #28a745 !important; }
.bg-secondary { background-color: #6c757d !important; }

/* Event Type Colors */
.bg-primary { background-color: #007bff !important; }
.bg-info { background-color: #17a2b8 !important; }
.bg-dark { background-color: #343a40 !important; }

/* Filter Section */
.form-group label {
    font-weight: 500;
    color: #555;
    margin-bottom: 5px;
}

.gap-2 {
    gap: 0.5rem;
}

/* Grid View Cards */
.event-card .card {
    transition: transform 0.2s, box-shadow 0.2s;
}

.event-card .card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.15);
}

.event-card .card-title {
    font-size: 14px;
    line-height: 1.3;
}

.event-card .card-text {
    font-size: 12px;
    line-height: 1.4;
}

/* Hidden rows/cards */
.hidden {
    display: none !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search');
    const typeFilter = document.getElementById('type-filter');
    const priorityFilter = document.getElementById('priority-filter');
    const statusFilter = document.getElementById('status-filter');
    const clearFiltersBtn = document.getElementById('clear-filters');
    const toggleViewBtn = document.getElementById('toggle-view');
    const tableView = document.getElementById('table-view');
    const gridView = document.getElementById('grid-view');
    
    let isGridView = false;

    // Toggle view function
    toggleViewBtn.addEventListener('click', function() {
        isGridView = !isGridView;
        if (isGridView) {
            tableView.style.display = 'none';
            gridView.style.display = 'block';
            toggleViewBtn.innerHTML = '<i class="fas fa-list me-1"></i>List View';
        } else {
            tableView.style.display = 'block';
            gridView.style.display = 'none';
            toggleViewBtn.innerHTML = '<i class="fas fa-th-large me-1"></i>Grid View';
        }
    });

    // Filter function
    function filterEvents() {
        const searchTerm = searchInput.value.toLowerCase();
        const typeValue = typeFilter.value;
        const priorityValue = priorityFilter.value;
        const statusValue = statusFilter.value;

        const eventRows = document.querySelectorAll('.event-row');
        const eventCards = document.querySelectorAll('.event-card');

        eventRows.forEach(row => {
            const title = row.dataset.title;
            const type = row.dataset.type;
            const priority = row.dataset.priority;
            const status = row.dataset.status;

            const matchesSearch = title.includes(searchTerm);
            const matchesType = !typeValue || type === typeValue;
            const matchesPriority = !priorityValue || priority === priorityValue;
            const matchesStatus = !statusValue || status === statusValue;

            if (matchesSearch && matchesType && matchesPriority && matchesStatus) {
                row.classList.remove('hidden');
            } else {
                row.classList.add('hidden');
            }
        });

        eventCards.forEach(card => {
            const title = card.dataset.title;
            const type = card.dataset.type;
            const priority = card.dataset.priority;
            const status = card.dataset.status;

            const matchesSearch = title.includes(searchTerm);
            const matchesType = !typeValue || type === typeValue;
            const matchesPriority = !priorityValue || priority === priorityValue;
            const matchesStatus = !statusValue || status === statusValue;

            if (matchesSearch && matchesType && matchesPriority && matchesStatus) {
                card.classList.remove('hidden');
            } else {
                card.classList.add('hidden');
            }
        });
    }

    // Event listeners
    searchInput.addEventListener('input', filterEvents);
    typeFilter.addEventListener('change', filterEvents);
    priorityFilter.addEventListener('change', filterEvents);
    statusFilter.addEventListener('change', filterEvents);

    // Clear filters
    clearFiltersBtn.addEventListener('click', function() {
        searchInput.value = '';
        typeFilter.value = '';
        priorityFilter.value = '';
        statusFilter.value = '';
        filterEvents();
    });
});

// Delete event function
function deleteEvent(eventId) {
    if (confirm('Are you sure you want to delete this event?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/event/delete/${eventId}`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        
        form.appendChild(csrfToken);
        form.appendChild(methodField);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection
