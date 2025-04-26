<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events - School SIS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-danger">
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
                    <h2><i class="fas fa-calendar-alt me-2"></i>Events</h2>
                    <div>
                        <a href="{{ route('events.calendar') }}" class="btn btn-outline-danger me-2">
                            <i class="fas fa-calendar me-2"></i>Calendar View
                        </a>
                        <a href="{{ route('events.create') }}" class="btn btn-danger">
                            <i class="fas fa-plus me-2"></i>Add New Event
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
                        <h5 class="mb-0">Event List</h5>
                    </div>
                    <div class="card-body">
                        @if($events->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Title</th>
                                            <th>Type</th>
                                            <th>Date & Time</th>
                                            <th>Location</th>
                                            <th>Organizer</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($events as $event)
                                            <tr>
                                                <td>
                                                    <div><strong>{{ $event->title }}</strong></div>
                                                    <small class="text-muted">{{ Str::limit($event->description, 50) }}</small>
                                                </td>
                                                <td>
                                                    <span class="badge bg-info">{{ $event->event_type }}</span>
                                                </td>
                                                <td>
                                                    <div><i class="fas fa-calendar me-1"></i>{{ \Carbon\Carbon::parse($event->start_date)->format('M d, Y') }}</div>
                                                    <small class="text-muted">
                                                        <i class="fas fa-clock me-1"></i>{{ $event->start_time }} - {{ $event->end_time }}
                                                    </small>
                                                </td>
                                                <td>
                                                    <i class="fas fa-map-marker-alt me-1"></i>{{ $event->location }}
                                                </td>
                                                <td>
                                                    <span class="badge bg-secondary">{{ $event->organizer }}</span>
                                                </td>
                                                <td>
                                                    @if($event->is_public)
                                                        <span class="badge bg-success">Public</span>
                                                    @else
                                                        <span class="badge bg-warning">Private</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('events.show', $event->id) }}" 
                                                           class="btn btn-sm btn-outline-info" title="View">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('events.edit', $event->id) }}" 
                                                           class="btn btn-sm btn-outline-warning" title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <form action="{{ route('events.destroy', $event->id) }}" 
                                                              method="POST" class="d-inline" 
                                                              onsubmit="return confirm('Are you sure you want to delete this event?')">
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
                                <i class="fas fa-calendar-alt fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No events found</h5>
                                <p class="text-muted">Get started by adding your first event.</p>
                                <a href="{{ route('events.create') }}" class="btn btn-danger">
                                    <i class="fas fa-plus me-2"></i>Add Event
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
