<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Communication Management - School SIS</title>
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
                    <h2><i class="fas fa-comments me-2"></i>Communication Management</h2>
                    <div>
                        <a href="{{ route('communication.reports') }}" class="btn btn-outline-info me-2">
                            <i class="fas fa-chart-bar me-2"></i>Reports
                        </a>
                        <a href="{{ route('communication.create-announcement') }}" class="btn btn-warning me-2">
                            <i class="fas fa-bullhorn me-2"></i>Create Announcement
                        </a>
                        <a href="{{ route('communication.compose') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Compose Message
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

                <!-- Communication Statistics -->
                <div class="row mb-4">
                    <div class="col-md-2">
                        <div class="card text-center">
                            <div class="card-body">
                                <i class="fas fa-envelope fa-2x text-primary mb-2"></i>
                                <h4 class="card-title">{{ $stats['total_messages'] }}</h4>
                                <p class="card-text text-muted">Total Messages</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card text-center">
                            <div class="card-body">
                                <i class="fas fa-envelope-open fa-2x text-warning mb-2"></i>
                                <h4 class="card-title">{{ $stats['unread_messages'] }}</h4>
                                <p class="card-text text-muted">Unread Messages</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card text-center">
                            <div class="card-body">
                                <i class="fas fa-bullhorn fa-2x text-success mb-2"></i>
                                <h4 class="card-title">{{ $stats['total_announcements'] }}</h4>
                                <p class="card-text text-muted">Total Announcements</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card text-center">
                            <div class="card-body">
                                <i class="fas fa-check-circle fa-2x text-info mb-2"></i>
                                <h4 class="card-title">{{ $stats['published_announcements'] }}</h4>
                                <p class="card-text text-muted">Published</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card text-center">
                            <div class="card-body">
                                <i class="fas fa-bell fa-2x text-danger mb-2"></i>
                                <h4 class="card-title">{{ $stats['unread_notifications'] }}</h4>
                                <p class="card-text text-muted">Unread Notifications</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card text-center">
                            <div class="card-body">
                                <i class="fas fa-clock fa-2x text-dark mb-2"></i>
                                <h4 class="card-title">{{ $stats['recent_messages'] }}</h4>
                                <p class="card-text text-muted">Recent (7 days)</p>
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
                                <h5 class="card-title">Compose Message</h5>
                                <p class="card-text">Send messages to students, teachers, or users</p>
                                <a href="{{ route('communication.compose') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>Compose
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body text-center">
                                <i class="fas fa-bullhorn fa-3x text-warning mb-3"></i>
                                <h5 class="card-title">Create Announcement</h5>
                                <p class="card-text">Make announcements to the school community</p>
                                <a href="{{ route('communication.create-announcement') }}" class="btn btn-warning">
                                    <i class="fas fa-bullhorn me-2"></i>Create
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body text-center">
                                <i class="fas fa-bell fa-3x text-info mb-3"></i>
                                <h5 class="card-title">Notifications</h5>
                                <p class="card-text">View and manage your notifications</p>
                                <a href="{{ route('communication.notifications') }}" class="btn btn-info">
                                    <i class="fas fa-bell me-2"></i>View
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body text-center">
                                <i class="fas fa-envelope fa-3x text-success mb-3"></i>
                                <h5 class="card-title">Messages</h5>
                                <p class="card-text">View all your messages and conversations</p>
                                <a href="{{ route('communication.messages') }}" class="btn btn-success">
                                    <i class="fas fa-envelope me-2"></i>View
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Messages and Announcements -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-envelope me-2"></i>Recent Messages
                                </h5>
                            </div>
                            <div class="card-body">
                                @if($recentMessages->count() > 0)
                                    <div class="list-group list-group-flush">
                                        @foreach($recentMessages as $message)
                                            <div class="list-group-item">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-1">{{ $message->subject }}</h6>
                                                        <p class="mb-1 text-muted">
                                                            <small>
                                                                @if($message->sender_id == auth()->id())
                                                                    To: {{ $message->recipient ? $message->recipient->name : 'N/A' }}
                                                                @else
                                                                    From: {{ $message->sender ? $message->sender->name : 'N/A' }}
                                                                @endif
                                                            </small>
                                                        </p>
                                                        <small class="text-muted">
                                                            {{ \Carbon\Carbon::parse($message->created_at)->format('M d, Y H:i') }}
                                                            @if($message->priority != 'normal')
                                                                <span class="badge bg-{{ $message->priority == 'urgent' ? 'danger' : ($message->priority == 'high' ? 'warning' : 'info') }} ms-2">
                                                                    {{ ucfirst($message->priority) }}
                                                                </span>
                                                            @endif
                                                        </small>
                                                    </div>
                                                    <div>
                                                        <a href="{{ route('communication.view-message', $message->id) }}" 
                                                           class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-4">
                                        <i class="fas fa-envelope fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">No recent messages</h5>
                                        <p class="text-muted">Start by composing a message.</p>
                                        <a href="{{ route('communication.compose') }}" class="btn btn-primary">
                                            <i class="fas fa-plus me-2"></i>Compose Message
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-bullhorn me-2"></i>Recent Announcements
                                </h5>
                            </div>
                            <div class="card-body">
                                @if($recentAnnouncements->count() > 0)
                                    <div class="list-group list-group-flush">
                                        @foreach($recentAnnouncements as $announcement)
                                            <div class="list-group-item">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-1">{{ $announcement->title }}</h6>
                                                        <p class="mb-1 text-muted">
                                                            <small>{{ Str::limit($announcement->content, 100) }}</small>
                                                        </p>
                                                        <small class="text-muted">
                                                            {{ \Carbon\Carbon::parse($announcement->created_at)->format('M d, Y') }}
                                                            <span class="badge bg-{{ $announcement->type == 'emergency' ? 'danger' : ($announcement->type == 'event' ? 'success' : 'info') }} ms-2">
                                                                {{ ucfirst($announcement->type) }}
                                                            </span>
                                                            @if($announcement->is_published)
                                                                <span class="badge bg-success ms-1">Published</span>
                                                            @else
                                                                <span class="badge bg-warning ms-1">Draft</span>
                                                            @endif
                                                        </small>
                                                    </div>
                                                    <div>
                                                        <a href="{{ route('communication.edit-announcement', $announcement->id) }}" 
                                                           class="btn btn-sm btn-outline-warning">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-4">
                                        <i class="fas fa-bullhorn fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">No recent announcements</h5>
                                        <p class="text-muted">Start by creating an announcement.</p>
                                        <a href="{{ route('communication.create-announcement') }}" class="btn btn-warning">
                                            <i class="fas fa-bullhorn me-2"></i>Create Announcement
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Communication Overview -->
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-info-circle me-2"></i>Communication Overview
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Unread Messages:</span>
                                            <span class="badge bg-warning">{{ $stats['unread_messages'] }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Unread Notifications:</span>
                                            <span class="badge bg-danger">{{ $stats['unread_notifications'] }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Recent Messages (7d):</span>
                                            <span class="badge bg-primary">{{ $stats['recent_messages'] }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Recent Announcements (7d):</span>
                                            <span class="badge bg-info">{{ $stats['recent_announcements'] }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Published Announcements:</span>
                                            <span class="badge bg-success">{{ $stats['published_announcements'] }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Total Notifications:</span>
                                            <span class="badge bg-secondary">{{ $stats['total_notifications'] }}</span>
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
                                        <a href="{{ route('communication.messages') }}" class="btn btn-outline-primary btn-sm w-100 mb-2">
                                            <i class="fas fa-envelope me-1"></i>View All Messages
                                        </a>
                                        <a href="{{ route('communication.announcements') }}" class="btn btn-outline-warning btn-sm w-100 mb-2">
                                            <i class="fas fa-bullhorn me-1"></i>View Announcements
                                        </a>
                                    </div>
                                    <div class="col-md-6">
                                        <a href="{{ route('communication.export') }}?type=messages&format=csv" class="btn btn-outline-success btn-sm w-100 mb-2">
                                            <i class="fas fa-download me-1"></i>Export Messages
                                        </a>
                                        <a href="{{ route('communication.export') }}?type=announcements&format=csv" class="btn btn-outline-info btn-sm w-100 mb-2">
                                            <i class="fas fa-download me-1"></i>Export Announcements
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
