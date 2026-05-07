@extends('admin.layout.app')
@section('title', 'Dashboard')

@section('content')

@php
    $totalMeds       = \App\Models\Medication::count();
    $totalRequests   = \App\Models\MedicationRequest::count();
    $lowStockCount   = $lowStockMedications->count();
    $totalCategories = $categories->count();
@endphp

{{-- STAT CARDS --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#eff6ff;color:#1d4ed8;"><i class="bi bi-capsule"></i></div>
            <div>
                <div class="stat-value">{{ $totalMeds }}</div>
                <div class="stat-label">Total Medications</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fef2f2;color:#dc2626;"><i class="bi bi-exclamation-triangle"></i></div>
            <div>
                <div class="stat-value">{{ $lowStockCount }}</div>
                <div class="stat-label">Low Stock Items</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fffbeb;color:#d97706;"><i class="bi bi-hourglass-split"></i></div>
            <div>
                <div class="stat-value">{{ $pendingRequests }}</div>
                <div class="stat-label">Pending Requests</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#faf5ff;color:#7c3aed;"><i class="bi bi-tag"></i></div>
            <div>
                <div class="stat-value">{{ $totalCategories }}</div>
                <div class="stat-label">Categories</div>
            </div>
        </div>
    </div>
</div>

{{-- ALERT BANNERS --}}
@if($expiredMedications->isNotEmpty())
<div class="alert d-flex align-items-center gap-2 mb-3" style="background:#fef2f2;color:#991b1b;border-left:4px solid #dc2626;">
    <i class="bi bi-x-octagon-fill fs-5"></i>
    <div><strong>Expired:</strong> {{ $expiredMedications->count() }} medication(s) have expired and should be removed.</div>
    @if($modules['medications'] ?? true)
    <a href="{{ route('medications.index', ['expired' => 1]) }}" class="btn btn-sm ms-auto" style="background:#dc2626;color:#fff;border:none;">View</a>
    @endif
</div>
@endif

@if($expiringSoon->isNotEmpty())
<div class="alert d-flex align-items-center gap-2 mb-3" style="background:#fffbeb;color:#92400e;border-left:4px solid #f59e0b;">
    <i class="bi bi-clock-history fs-5"></i>
    <div><strong>Expiring Soon:</strong> {{ $expiringSoon->count() }} medication(s) expire within 30 days.</div>
    @if($modules['medications'] ?? true)
    <a href="{{ route('medications.index', ['expiring' => 1]) }}" class="btn btn-sm ms-auto" style="background:#f59e0b;color:#fff;border:none;">View</a>
    @endif
</div>
@endif

@if($lowStockMedications->isNotEmpty())
<div class="alert d-flex align-items-center gap-2 mb-4" style="background:#eff6ff;color:#1e40af;border-left:4px solid #3b82f6;">
    <i class="bi bi-exclamation-triangle-fill fs-5"></i>
    <div><strong>Low Stock:</strong> {{ $lowStockCount }} medication(s) need restocking.</div>
    @if($modules['reports'] ?? true)
    <a href="{{ route('reports.restock') }}" class="btn btn-sm ms-auto" style="background:#1d4ed8;color:#fff;border:none;">View Report</a>
    @endif
</div>
@endif

@if($pendingRequests > 0)
<div class="alert d-flex align-items-center gap-2 mb-4" style="background:#fffbeb;color:#92400e;border-left:4px solid #f59e0b;">
    <i class="bi bi-hourglass-split fs-5"></i>
    <div><strong>Pending Approval:</strong> {{ $pendingRequests }} request(s) waiting for your action.</div>
    @if($modules['requests'] ?? true)
    <a href="{{ route('requests.index', ['status' => 'pending']) }}" class="btn btn-sm ms-auto" style="background:#f59e0b;color:#fff;border:none;">Review</a>
    @endif
</div>
@endif

<div class="row g-3">
    {{-- LOW STOCK TABLE --}}
    @if($modules['medications'] ?? true)
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span><i class="bi bi-exclamation-circle text-warning me-2"></i>Low Stock Medications</span>
                <a href="{{ route('medications.index', ['low_stock' => 1]) }}" class="btn btn-sm btn-outline-primary" style="font-size:.75rem;">View All</a>
            </div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead><tr><th>Medication</th><th>Category</th><th>Qty Left</th></tr></thead>
                    <tbody>
                        @forelse($lowStockMedications->take(6) as $med)
                        <tr>
                            <td>{{ $med->name }}</td>
                            <td>{{ $med->category->name ?? '—' }}</td>
                            <td><span class="badge" style="background:#fef2f2;color:#dc2626;">{{ $med->quantity }} {{ $med->unit }}</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center text-muted py-4">All medications are well stocked.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    {{-- RECENT REQUESTS --}}
    @if($modules['requests'] ?? true)
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span><i class="bi bi-clipboard2-pulse text-primary me-2"></i>Recent Requests</span>
                <div class="d-flex gap-2">
                    <a href="{{ route('requests.create') }}" class="btn btn-sm btn-primary" style="font-size:.75rem;">+ New</a>
                    <a href="{{ route('requests.index') }}" class="btn btn-sm btn-outline-secondary" style="font-size:.75rem;">View All</a>
                </div>
            </div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead><tr><th>Student</th><th>Status</th><th>Date</th></tr></thead>
                    <tbody>
                        @forelse($recentRequests as $req)
                        <tr>
                            <td>{{ $req->student_name ?? '—' }}</td>
                            <td>
                                @php
                                    $sc = match($req->status) {
                                        'pending'   => ['background:#fffbeb;color:#d97706', 'hourglass-split'],
                                        'approved'  => ['background:#eff6ff;color:#1d4ed8', 'check-circle'],
                                        'disbursed' => ['background:#f0fdf4;color:#16a34a', 'check-circle-fill'],
                                        'rejected'  => ['background:#fef2f2;color:#dc2626', 'x-circle'],
                                        default     => ['background:#f8fafc;color:#64748b', 'circle'],
                                    };
                                @endphp
                                <span class="badge" style="{{ $sc[0] }}">
                                    <i class="bi bi-{{ $sc[1] }} me-1"></i>{{ ucfirst($req->status) }}
                                </span>
                            </td>
                            <td style="color:#94a3b8;font-size:.78rem;">{{ $req->created_at->format('M d, Y') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center text-muted py-4">No requests yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    {{-- EXPIRING SOON --}}
    @if($expiringSoon->isNotEmpty() && ($modules['medications'] ?? true))
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span><i class="bi bi-clock-history text-warning me-2"></i>Expiring Within 30 Days</span>
                <a href="{{ route('medications.index', ['expiring' => 1]) }}" class="btn btn-sm btn-outline-warning" style="font-size:.75rem;">View All</a>
            </div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead><tr><th>Medication</th><th>Qty</th><th>Expires</th></tr></thead>
                    <tbody>
                        @foreach($expiringSoon->take(5) as $med)
                        <tr>
                            <td>{{ $med->name }}</td>
                            <td>{{ $med->quantity }} {{ $med->unit }}</td>
                            <td>
                                <span class="badge" style="background:#fffbeb;color:#d97706;">
                                    {{ $med->expiry_date->format('M d, Y') }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    {{-- CATEGORIES SUMMARY --}}
    @if($modules['categories'] ?? true)
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span><i class="bi bi-tag me-2" style="color:#7c3aed;"></i>Stock by Category</span>
                <a href="{{ route('categories.index') }}" class="btn btn-sm btn-outline-secondary" style="font-size:.75rem;">View All</a>
            </div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead><tr><th>Category</th><th>Medications</th></tr></thead>
                    <tbody>
                        @forelse($categories as $cat)
                        <tr>
                            <td>{{ $cat->name }}</td>
                            <td><span class="badge bg-primary bg-opacity-10 text-primary">{{ $cat->medications->count() }} items</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="2" class="text-center text-muted py-4">No categories yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
