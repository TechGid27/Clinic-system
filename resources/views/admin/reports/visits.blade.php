@extends('admin.layout.app')
@section('title', 'Visit Logs Report')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h5 class="fw-bold mb-0">Visit Logs Report</h5>
        <small class="text-muted">Student clinic visits based on medication requests</small>
    </div>
    <a href="{{ route('reports.visits.pdf', request()->query()) }}" target="_blank" class="btn btn-primary btn-sm">
        <i class="bi bi-file-earmark-pdf me-1"></i> Download PDF
    </a>
</div>

{{-- Summary Stats --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon bg-primary bg-opacity-10 text-primary"><i class="bi bi-clipboard2-pulse"></i></div>
            <div>
                <div class="stat-value">{{ $totalVisits }}</div>
                <div class="stat-label">Total Visits</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon bg-success bg-opacity-10 text-success"><i class="bi bi-calendar-check"></i></div>
            <div>
                <div class="stat-value">{{ $todayVisits }}</div>
                <div class="stat-label">Today's Visits</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon bg-info bg-opacity-10 text-info"><i class="bi bi-calendar-month"></i></div>
            <div>
                <div class="stat-value">{{ $thisMonth }}</div>
                <div class="stat-label">This Month</div>
            </div>
        </div>
    </div>
</div>

{{-- Filter --}}
<div class="card mb-4">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Search</label>
                <input type="text" name="search" class="form-control" placeholder="Name, course, reason…" value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">Date From</label>
                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">Date To</label>
                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">All</option>
                    @foreach(['pending','approved','disbursed','rejected'] as $s)
                        <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-1">
                <button class="btn btn-primary w-100">Filter</button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('reports.visits') }}" class="btn btn-outline-secondary w-100">Clear</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header"><i class="bi bi-table me-2"></i>Visit Records</div>
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Student</th>
                    <th>Course / Year</th>
                    <th>Reason</th>
                    <th>Medications Given</th>
                    <th>Status</th>
                    <th>Processed By</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($visits as $visit)
                <tr>
                    <td class="text-muted" style="font-size:.75rem;">{{ $visit->id }}</td>
                    <td class="fw-semibold">{{ $visit->student_name ?? '—' }}</td>
                    <td>
                        {{ $visit->course ?? '—' }}
                        @if($visit->year_level)
                            <br><small class="text-muted">{{ $visit->year_level }}</small>
                        @endif
                    </td>
                    <td style="max-width:160px;">{{ Str::limit($visit->reason ?? '—', 50) }}</td>
                    <td style="max-width:200px;">
                        <div class="d-flex flex-wrap gap-1">
                            @foreach($visit->items as $item)
                                <span class="badge" style="background:#eff6ff;color:#1d4ed8;font-size:.7rem;">
                                    {{ $item->medication->name ?? '?' }} ({{ $item->quantity }})
                                </span>
                            @endforeach
                        </div>
                    </td>
                    <td>
                        @php
                            $badge = match($visit->status) {
                                'pending'   => 'background:#fffbeb;color:#d97706',
                                'approved'  => 'background:#eff6ff;color:#1d4ed8',
                                'disbursed' => 'background:#f0fdf4;color:#16a34a',
                                'rejected'  => 'background:#fef2f2;color:#dc2626',
                                default     => 'background:#f8fafc;color:#64748b',
                            };
                        @endphp
                        <span class="badge" style="{{ $badge }}">{{ ucfirst($visit->status) }}</span>
                    </td>
                    <td>{{ $visit->approver?->name ?? '—' }}</td>
                    <td style="white-space:nowrap;">{{ $visit->created_at->format('M d, Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">No visit records found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if(method_exists($visits, 'hasPages') && $visits->hasPages())
    <div class="card-footer">{{ $visits->links() }}</div>
    @endif
</div>

@endsection
