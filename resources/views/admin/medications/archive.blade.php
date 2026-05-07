@extends('admin.layout.app')
@section('title', 'Medication Archive')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <p class="text-muted mb-0" style="font-size:.85rem;">
            Archived medications are removed from active inventory but kept for records.
        </p>
    </div>
    <a href="{{ route('medications.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Back to Inventory
    </a>
</div>

{{-- SEARCH --}}
<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" class="d-flex gap-2 align-items-center">
            <input type="text" name="search" value="{{ request('search') }}"
                   class="form-control form-control-sm" placeholder="Search by name…" style="max-width:240px;">
            <button type="submit" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-search me-1"></i> Search
            </button>
            @if(request('search'))
            <a href="{{ route('medications.archive') }}" class="btn btn-sm btn-outline-secondary">Clear</a>
            @endif
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <span><i class="bi bi-archive me-2 text-secondary"></i>Archived Medications</span>
        <span class="badge bg-secondary bg-opacity-10 text-secondary">{{ $medications->total() }} total</span>
    </div>
    <div class="card-body p-0">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Qty</th>
                    <th>Unit</th>
                    <th>Expiry Date</th>
                    <th>Archived On</th>
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
                        @if($med->expiry_date)
                            <span style="font-size:.82rem;color:#64748b;">{{ $med->expiry_date->format('M d, Y') }}</span>
                        @else
                            <span style="color:#cbd5e1;">—</span>
                        @endif
                    </td>
                    <td style="font-size:.82rem;color:#64748b;">
                        {{ $med->deleted_at->format('M d, Y') }}
                        <div style="font-size:.75rem;color:#94a3b8;">{{ $med->deleted_at->diffForHumans() }}</div>
                    </td>
                    <td>
                        <div class="d-flex gap-2">
                            {{-- Restore --}}
                            <form method="POST" action="{{ route('medications.restore', $med->id) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-outline-success"
                                    title="Restore to inventory"
                                    onclick="return confirm('Restore \'{{ addslashes($med->name) }}\' back to active inventory?')">
                                    <i class="bi bi-arrow-counterclockwise me-1"></i>Restore
                                </button>
                            </form>

                            {{-- Permanent delete --}}
                            <form method="POST" action="{{ route('medications.force-delete', $med->id) }}">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                    title="Permanently delete"
                                    onclick="return confirm('Permanently delete \'{{ addslashes($med->name) }}\'? This cannot be undone.')">
                                    <i class="bi bi-trash3 me-1"></i>Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5 text-muted">
                        <i class="bi bi-archive d-block fs-2 mb-2 opacity-25"></i>
                        No archived medications.
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
