@extends('admin.layout.app')
@section('title', 'Medication Requests')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <p class="text-muted mb-0" style="font-size:.85rem;">Manage and process medication requests</p>
    <a href="{{ route('requests.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i> New Request
    </a>
</div>

{{-- STATUS FILTER TABS --}}
<div class="d-flex gap-2 mb-3 flex-wrap">
    @php
        $statuses = [
            ''          => ['label' => 'All',       'color' => 'secondary'],
            'pending'   => ['label' => 'Pending',   'color' => 'warning'],
            'approved'  => ['label' => 'Approved',  'color' => 'primary'],
            'disbursed' => ['label' => 'Disbursed', 'color' => 'success'],
            'rejected'  => ['label' => 'Rejected',  'color' => 'danger'],
        ];
        $currentStatus = request('status', '');
    @endphp
    @foreach($statuses as $val => $meta)
    <a href="{{ route('requests.index', $val ? ['status' => $val] : []) }}"
       class="btn btn-sm {{ $currentStatus === $val ? 'btn-'.$meta['color'] : 'btn-outline-'.$meta['color'] }}">
        {{ $meta['label'] }}
        @if(isset($counts[$val]) && $counts[$val] > 0)
            <span class="badge bg-white text-{{ $meta['color'] }} ms-1">{{ $counts[$val] }}</span>
        @endif
    </a>
    @endforeach
</div>

<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <span><i class="bi bi-clipboard2-pulse me-2 text-primary"></i>
            {{ $currentStatus ? ucfirst($currentStatus).' Requests' : 'All Requests' }}
        </span>
        <span class="badge bg-primary bg-opacity-10 text-primary">{{ $requests->total() }} total</span>
    </div>
    <div class="card-body p-0">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Student</th>
                    <th>Course / Year</th>
                    <th>Reason</th>
                    <th>Items</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($requests as $req)
                <tr>
                    <td style="color:#94a3b8;font-size:.78rem;white-space:nowrap;">
                        {{ $req->created_at->format('M d, Y') }}<br>
                        <span style="font-size:.72rem;">{{ $req->created_at->format('h:i A') }}</span>
                    </td>
                    <td class="fw-500">{{ $req->student_name ?? '—' }}</td>
                    <td style="color:#64748b;">
                        {{ $req->course ?? '—' }}
                        @if($req->year_level)
                            <br><small class="text-muted">{{ $req->year_level }}</small>
                        @endif
                    </td>
                    <td style="color:#64748b;">{{ Str::limit($req->reason ?? '—', 35) }}</td>
                    <td>
                        <div class="d-flex flex-wrap gap-1">
                            @foreach($req->items as $i)
                                <span class="badge" style="background:#eff6ff;color:#1d4ed8;font-weight:500;">
                                    {{ $i->medication->name ?? '?' }} ({{ $i->quantity }})
                                </span>
                            @endforeach
                        </div>
                    </td>
                    <td>
                        @php
                            $badge = match($req->status) {
                                'pending'   => 'background:#fffbeb;color:#d97706',
                                'approved'  => 'background:#eff6ff;color:#1d4ed8',
                                'disbursed' => 'background:#f0fdf4;color:#16a34a',
                                'rejected'  => 'background:#fef2f2;color:#dc2626',
                                default     => 'background:#f8fafc;color:#64748b',
                            };
                            $icon = match($req->status) {
                                'pending'   => 'hourglass-split',
                                'approved'  => 'check-circle',
                                'disbursed' => 'check-circle-fill',
                                'rejected'  => 'x-circle',
                                default     => 'circle',
                            };
                        @endphp
                        <span class="badge" style="{{ $badge }}">
                            <i class="bi bi-{{ $icon }} me-1"></i>{{ ucfirst($req->status) }}
                        </span>
                        @if($req->approver && $req->status !== 'pending')
                        <div style="font-size:.7rem;color:#94a3b8;margin-top:.2rem;">
                            by {{ $req->approver->name }}
                        </div>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-1 flex-wrap">
                            @if($req->status === 'pending')
                                <form method="POST" action="{{ route('requests.approve', $req) }}">
                                    @csrf @method('PATCH')
                                    <button class="btn btn-sm btn-outline-primary" title="Approve">
                                        <i class="bi bi-check-lg"></i>
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('requests.reject', $req) }}">
                                    @csrf @method('PATCH')
                                    <button class="btn btn-sm btn-outline-danger" title="Reject"
                                        onclick="return confirm('Reject this request?')">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                </form>
                            @elseif($req->status === 'approved')
                                <form method="POST" action="{{ route('requests.disburse', $req) }}">
                                    @csrf @method('PATCH')
                                    <button class="btn btn-sm btn-success" title="Disburse medications"
                                        onclick="return confirm('Disburse medications for this request? Stock will be deducted.')">
                                        <i class="bi bi-box-arrow-up me-1"></i> Disburse
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5 text-muted">
                        <i class="bi bi-clipboard2 d-block fs-2 mb-2 opacity-25"></i>
                        No requests found. <a href="{{ route('requests.create') }}">Create one</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white border-top" style="border-radius:0 0 12px 12px;">
        {{ $requests->appends(request()->query())->links() }}
    </div>
</div>
@endsection
