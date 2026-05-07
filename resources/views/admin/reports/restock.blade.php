@extends('admin.layout.app')
@section('title', 'Restock Report')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <p class="text-muted mb-0" style="font-size:.85rem;">{{ now()->format('F j, Y') }} — ACLC College of Mandaue</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('reports.restock.pdf') }}" target="_blank" class="btn btn-primary btn-sm">
            <i class="bi bi-file-earmark-pdf me-1"></i> Download PDF
        </a>
    </div>
</div>

{{-- SUMMARY STAT --}}
@php $totalLow = $lowStock->count(); $totalUsed = $mostUsed->sum('total_used'); @endphp
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fef2f2;color:#dc2626;"><i class="bi bi-exclamation-triangle"></i></div>
            <div>
                <div class="stat-value">{{ $totalLow }}</div>
                <div class="stat-label">Items Need Restock</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fef2f2;color:#dc2626;"><i class="bi bi-x-octagon"></i></div>
            <div>
                <div class="stat-value">{{ $expired->count() }}</div>
                <div class="stat-label">Expired Items</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fffbeb;color:#d97706;"><i class="bi bi-clock-history"></i></div>
            <div>
                <div class="stat-value">{{ $expiringSoon->count() }}</div>
                <div class="stat-label">Expiring in 30 Days</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background:#1d4ed8;border-color:#1d4ed8;">
            <div class="stat-icon" style="background:rgba(255,255,255,.15);color:#fff;"><i class="bi bi-graph-up"></i></div>
            <div>
                <div class="stat-value" style="color:#fff;">{{ number_format($totalUsed) }}</div>
                <div class="stat-label" style="color:rgba(255,255,255,.7);">Total Units Dispensed</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    {{-- LOW STOCK --}}
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center gap-2">
                <i class="bi bi-exclamation-circle text-danger"></i>
                Items Needing Restock
            </div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Medication</th>
                            <th>Category</th>
                            <th>Production Date</th>
                            <th>Qty</th>
                            <th>Threshold</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($lowStock as $med)
                        <tr>
                            <td>{{ $med->name }}</td>
                            <td>{{ $med->category->name ?? '—' }}</td>
                            <td style="color:#64748b;font-size:.82rem;">{{ $med->production_date?->format('M d, Y') ?? '—' }}</td>
                            <td><span class="badge" style="background:#fef2f2;color:#dc2626;">{{ $med->quantity }} {{ $med->unit }}</span></td>
                            <td style="color:#94a3b8;">{{ $med->low_stock_threshold }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center text-muted py-4">No low stock items.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- MOST USED --}}
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center gap-2">
                <i class="bi bi-bar-chart-line text-primary"></i>
                Most Frequently Used
            </div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Medication</th>
                            <th>Category</th>
                            <th>Total Used</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($mostUsed as $row)
                        @if($row->medication)
                        <tr>
                            <td>{{ $row->medication->name }}</td>
                            <td>{{ $row->medication->category->name ?? '—' }}</td>
                            <td>
                                <span class="badge bg-primary bg-opacity-10 text-primary">
                                    {{ number_format($row->total_used) }} {{ $row->medication->unit }}
                                </span>
                            </td>
                        </tr>
                        @endif
                        @empty
                        <tr><td colspan="3" class="text-center text-muted py-4">No usage data yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<p class="text-muted mt-4" style="font-size:.75rem;">
    Generated by ACLC Clinic Information & Inventory System · {{ now()->format('F j, Y \a\t g:i A') }}
</p>

{{-- EXPIRY SECTIONS --}}
@if($expired->isNotEmpty())
<div class="row g-3 mt-1">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center gap-2">
                <i class="bi bi-x-octagon text-danger"></i> Expired Medications
            </div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead>
                        <tr><th>Medication</th><th>Category</th><th>Qty</th><th>Production Date</th><th>Expired On</th></tr>
                    </thead>
                    <tbody>
                        @foreach($expired as $med)
                        <tr>
                            <td>{{ $med->name }}</td>
                            <td>{{ $med->category->name ?? '—' }}</td>
                            <td>{{ $med->quantity }} {{ $med->unit }}</td>
                            <td style="color:#64748b;font-size:.82rem;">{{ $med->production_date?->format('M d, Y') ?? '—' }}</td>
                            <td><span class="badge" style="background:#fef2f2;color:#dc2626;">{{ $med->expiry_date->format('M d, Y') }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endif

@if($expiringSoon->isNotEmpty())
<div class="row g-3 mt-1">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center gap-2">
                <i class="bi bi-clock-history text-warning"></i> Expiring Within 30 Days
            </div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead>
                        <tr><th>Medication</th><th>Category</th><th>Qty</th><th>Production Date</th><th>Expiry Date</th><th>Days Left</th></tr>
                    </thead>
                    <tbody>
                        @foreach($expiringSoon as $med)
                        <tr>
                            <td>{{ $med->name }}</td>
                            <td>{{ $med->category->name ?? '—' }}</td>
                            <td>{{ $med->quantity }} {{ $med->unit }}</td>
                            <td style="color:#64748b;font-size:.82rem;">{{ $med->production_date?->format('M d, Y') ?? '—' }}</td>
                            <td><span class="badge" style="background:#fffbeb;color:#d97706;">{{ $med->expiry_date->format('M d, Y') }}</span></td>
                            <td style="color:#d97706;font-weight:600;">{{ now()->diffInDays($med->expiry_date) }} days</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endif

@endsection
