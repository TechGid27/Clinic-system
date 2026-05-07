@extends('admin.layout.app')
@section('title', 'Medications')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <p class="text-muted mb-0" style="font-size:.85rem;">Manage medication inventory</p>
    <div class="d-flex gap-2">
        @if($archivedCount > 0)
        <a href="{{ route('medications.archive') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-archive me-1"></i> Archive
            <span class="badge bg-secondary ms-1">{{ $archivedCount }}</span>
        </a>
        @endif
        <a href="{{ route('medications.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i> Add Medication
        </a>
    </div>
</div>

{{-- EXPIRY ALERT BADGES --}}
@if($expiredCount > 0 || $expiringCount > 0)
<div class="d-flex gap-2 mb-3 flex-wrap">
    @if($expiredCount > 0)
    <a href="{{ route('medications.index', ['expired' => 1]) }}"
       class="btn btn-sm {{ request('expired') ? 'btn-danger' : 'btn-outline-danger' }}">
        <i class="bi bi-x-octagon me-1"></i> {{ $expiredCount }} Expired
    </a>
    @endif
    @if($expiringCount > 0)
    <a href="{{ route('medications.index', ['expiring' => 1]) }}"
       class="btn btn-sm {{ request('expiring') ? 'btn-warning' : 'btn-outline-warning' }}">
        <i class="bi bi-clock-history me-1"></i> {{ $expiringCount }} Expiring Soon
    </a>
    @endif
</div>
@endif

{{-- FILTERS --}}
<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" class="d-flex flex-wrap gap-2 align-items-center">
            <input type="text" name="search" value="{{ request('search') }}"
                class="form-control form-control-sm" style="max-width:200px;" placeholder="Search medication...">
            <select name="category" class="form-select form-select-sm" style="max-width:180px;">
                <option value="">All Categories</option>
                @foreach($categories as $c)
                <option value="{{ $c->id }}" {{ request('category') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                @endforeach
            </select>
            <div class="form-check mb-0 ms-1">
                <input type="checkbox" name="low_stock" value="1" class="form-check-input"
                    {{ request('low_stock') ? 'checked' : '' }} id="lowStock">
                <label class="form-check-label" for="lowStock" style="font-size:.82rem;">Low stock only</label>
            </div>
            <button type="submit" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-funnel me-1"></i> Filter
            </button>
            @if(request()->hasAny(['category','low_stock','expiring','expired','search']))
            <a href="{{ route('medications.index') }}" class="btn btn-sm btn-outline-secondary">Clear</a>
            @endif
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <span><i class="bi bi-capsule me-2 text-primary"></i>Medications / Supplies</span>
        <span class="badge bg-primary bg-opacity-10 text-primary">{{ $medications->total() }} total</span>
    </div>
    <div class="card-body p-0">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Quantity</th>
                    <th>Unit</th>
                    <th>Production Date</th>
                    <th>Expiry Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($medications as $med)
                <tr>
                    <td class="fw-500">{{ $med->name }}</td>
                    <td style="color:#64748b;">{{ $med->category->name ?? '—' }}</td>
                    <td>{{ $med->quantity }}</td>
                    <td style="color:#94a3b8;">{{ $med->unit }}</td>
                    <td>
                        @if($med->production_date)
                            <span style="font-size:.82rem;color:#64748b;">{{ $med->production_date->format('M d, Y') }}</span>
                        @else
                            <span style="color:#cbd5e1;">—</span>
                        @endif
                    </td>
                    <td>
                        @if($med->expiry_date)
                            @if($med->isExpired())
                                <span class="badge" style="background:#fef2f2;color:#dc2626;">
                                    <i class="bi bi-x-octagon me-1"></i>{{ $med->expiry_date->format('M d, Y') }}
                                </span>
                            @elseif($med->isExpiringSoon())
                                <span class="badge" style="background:#fffbeb;color:#d97706;">
                                    <i class="bi bi-clock-history me-1"></i>{{ $med->expiry_date->format('M d, Y') }}
                                </span>
                            @else
                                <span style="font-size:.82rem;color:#64748b;">{{ $med->expiry_date->format('M d, Y') }}</span>
                            @endif
                        @else
                            <span style="color:#cbd5e1;">—</span>
                        @endif
                    </td>
                    <td>
                        @if($med->isExpired())
                            <span class="badge" style="background:#fef2f2;color:#dc2626;">
                                <i class="bi bi-x-octagon-fill me-1"></i>Expired
                            </span>
                        @elseif($med->isLowStock())
                            <span class="badge" style="background:#fef2f2;color:#dc2626;">
                                <i class="bi bi-exclamation-triangle-fill me-1"></i>Low
                            </span>
                        @else
                            <span class="badge" style="background:#f0fdf4;color:#16a34a;">
                                <i class="bi bi-check-circle-fill me-1"></i>OK
                            </span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('medications.edit', $med) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="{{ route('medications.destroy', $med) }}">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                    onclick="return confirm('Archive \'{{ addslashes($med->name) }}\'? It will be moved to the archive and can be restored later.')">
                                    <i class="bi bi-archive me-1"></i>Archive
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-5 text-muted">
                        <i class="bi bi-capsule d-block fs-2 mb-2 opacity-25"></i>
                        No medications found. <a href="{{ route('medications.create') }}">Add one</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white border-top" style="border-radius:0 0 12px 12px;">
        {{ $medications->withQueryString()->links() }}
    </div>
</div>
@endsection
