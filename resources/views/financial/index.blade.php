<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Financial Management - School SIS</title>
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
                    <h2><i class="fas fa-dollar-sign me-2"></i>Financial Management</h2>
                    <div>
                        <a href="{{ route('financial.reports') }}" class="btn btn-outline-info me-2">
                            <i class="fas fa-chart-bar me-2"></i>Reports
                        </a>
                        <a href="{{ route('financial.record-payment') }}" class="btn btn-success me-2">
                            <i class="fas fa-credit-card me-2"></i>Record Payment
                        </a>
                        <a href="{{ route('financial.create-fee') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Add Fee
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

                <!-- Financial Statistics -->
                <div class="row mb-4">
                    <div class="col-md-2">
                        <div class="card text-center">
                            <div class="card-body">
                                <i class="fas fa-file-invoice-dollar fa-2x text-primary mb-2"></i>
                                <h4 class="card-title">${{ number_format($stats['total_fees'], 2) }}</h4>
                                <p class="card-text text-muted">Total Fees</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card text-center">
                            <div class="card-body">
                                <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                                <h4 class="card-title">${{ number_format($stats['total_paid'], 2) }}</h4>
                                <p class="card-text text-muted">Total Paid</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card text-center">
                            <div class="card-body">
                                <i class="fas fa-clock fa-2x text-warning mb-2"></i>
                                <h4 class="card-title">${{ number_format($stats['total_pending'], 2) }}</h4>
                                <p class="card-text text-muted">Pending</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card text-center">
                            <div class="card-body">
                                <i class="fas fa-exclamation-triangle fa-2x text-danger mb-2"></i>
                                <h4 class="card-title">{{ $stats['overdue_fees'] }}</h4>
                                <p class="card-text text-muted">Overdue</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card text-center">
                            <div class="card-body">
                                <i class="fas fa-graduation-cap fa-2x text-info mb-2"></i>
                                <h4 class="card-title">${{ number_format($stats['total_scholarships'], 2) }}</h4>
                                <p class="card-text text-muted">Scholarships</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card text-center">
                            <div class="card-body">
                                <i class="fas fa-percentage fa-2x text-dark mb-2"></i>
                                <h4 class="card-title">
                                    {{ $stats['total_fees'] > 0 ? round(($stats['total_paid'] / $stats['total_fees']) * 100, 1) : 0 }}%
                                </h4>
                                <p class="card-text text-muted">Collection Rate</p>
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
                                <h5 class="card-title">Add Fee</h5>
                                <p class="card-text">Create new fee records for students</p>
                                <a href="{{ route('financial.create-fee') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>Add Fee
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body text-center">
                                <i class="fas fa-credit-card fa-3x text-success mb-3"></i>
                                <h5 class="card-title">Record Payment</h5>
                                <p class="card-text">Process student fee payments</p>
                                <a href="{{ route('financial.record-payment') }}" class="btn btn-success">
                                    <i class="fas fa-credit-card me-2"></i>Record Payment
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body text-center">
                                <i class="fas fa-graduation-cap fa-3x text-info mb-3"></i>
                                <h5 class="card-title">Scholarships</h5>
                                <p class="card-text">Manage student scholarships</p>
                                <a href="{{ route('financial.scholarships') }}" class="btn btn-info">
                                    <i class="fas fa-graduation-cap me-2"></i>Manage
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body text-center">
                                <i class="fas fa-tags fa-3x text-warning mb-3"></i>
                                <h5 class="card-title">Categories</h5>
                                <p class="card-text">Manage fee categories</p>
                                <a href="{{ route('financial.categories') }}" class="btn btn-warning">
                                    <i class="fas fa-tags me-2"></i>Manage
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Payments -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-history me-2"></i>Recent Payments
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($recentPayments->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Student</th>
                                            <th>Fee</th>
                                            <th>Amount</th>
                                            <th>Payment Date</th>
                                            <th>Method</th>
                                            <th>Reference</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentPayments as $payment)
                                            <tr>
                                                <td>
                                                    <div><strong>{{ $payment->student->first_name }} {{ $payment->student->last_name }}</strong></div>
                                                    <small class="text-muted">{{ $payment->student->email }}</small>
                                                </td>
                                                <td>
                                                    <div>{{ $payment->fee->description ?? 'N/A' }}</div>
                                                    <small class="text-muted">{{ $payment->fee->category->name ?? 'N/A' }}</small>
                                                </td>
                                                <td>
                                                    <span class="badge bg-success">${{ number_format($payment->amount, 2) }}</span>
                                                </td>
                                                <td>
                                                    <small>{{ \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y') }}</small>
                                                </td>
                                                <td>
                                                    @switch($payment->payment_method)
                                                        @case('cash')
                                                            <span class="badge bg-primary">Cash</span>
                                                            @break
                                                        @case('check')
                                                            <span class="badge bg-info">Check</span>
                                                            @break
                                                        @case('bank_transfer')
                                                            <span class="badge bg-success">Bank Transfer</span>
                                                            @break
                                                        @case('credit_card')
                                                            <span class="badge bg-warning">Credit Card</span>
                                                            @break
                                                        @case('online')
                                                            <span class="badge bg-dark">Online</span>
                                                            @break
                                                        @default
                                                            <span class="badge bg-secondary">{{ ucfirst($payment->payment_method) }}</span>
                                                    @endswitch
                                                </td>
                                                <td>
                                                    <small>{{ $payment->reference_number ?? 'N/A' }}</small>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-credit-card fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No recent payments</h5>
                                <p class="text-muted">Start by recording payments from students.</p>
                                <a href="{{ route('financial.record-payment') }}" class="btn btn-success">
                                    <i class="fas fa-credit-card me-2"></i>Record Payment
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Financial Overview -->
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-info-circle me-2"></i>Financial Overview
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Pending Fees:</span>
                                            <span class="badge bg-warning">{{ $stats['pending_fees'] }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Overdue Fees:</span>
                                            <span class="badge bg-danger">{{ $stats['overdue_fees'] }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Recent Payments (30d):</span>
                                            <span class="badge bg-success">${{ number_format($stats['recent_payments'], 2) }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Recent Fees (30d):</span>
                                            <span class="badge bg-info">${{ number_format($stats['recent_fees'], 2) }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Collection Rate:</span>
                                            <span class="badge bg-primary">
                                                {{ $stats['total_fees'] > 0 ? round(($stats['total_paid'] / $stats['total_fees']) * 100, 1) : 0 }}%
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
                                        <a href="{{ route('financial.fees') }}" class="btn btn-outline-primary btn-sm w-100 mb-2">
                                            <i class="fas fa-file-invoice-dollar me-1"></i>View All Fees
                                        </a>
                                        <a href="{{ route('financial.payments') }}" class="btn btn-outline-success btn-sm w-100 mb-2">
                                            <i class="fas fa-credit-card me-1"></i>View Payments
                                        </a>
                                    </div>
                                    <div class="col-md-6">
                                        <a href="{{ route('financial.export') }}?type=fees&format=csv" class="btn btn-outline-info btn-sm w-100 mb-2">
                                            <i class="fas fa-download me-1"></i>Export Fees
                                        </a>
                                        <a href="{{ route('financial.export') }}?type=payments&format=csv" class="btn btn-outline-warning btn-sm w-100 mb-2">
                                            <i class="fas fa-download me-1"></i>Export Payments
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
