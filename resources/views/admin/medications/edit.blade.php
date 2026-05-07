@extends('admin.layout.app')
@section('title', 'Edit Medication')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <p class="text-muted mb-0" style="font-size:.85rem;">Update medication details</p>
    <a href="{{ route('medications.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Back
    </a>
</div>

<div class="row">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header"><i class="bi bi-pencil me-2 text-primary"></i>Edit Medication</div>
            <div class="card-body">
                @if($errors->any())
                <div class="alert alert-danger d-flex gap-2 align-items-start mb-3" style="font-size:.82rem;">
                    <i class="bi bi-exclamation-triangle-fill mt-1"></i>
                    <ul class="mb-0 ps-2">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
                @endif

                <form method="POST" action="{{ route('medications.update', $medication) }}">
                    @csrf @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Category *</label>
                            <select name="category_id" class="form-select" required>
                                @foreach($categories as $c)
                                    <option value="{{ $c->id }}" {{ old('category_id', $medication->category_id) == $c->id ? 'selected' : '' }}>
                                        {{ $c->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Name *</label>
                            <input type="text" name="name" value="{{ old('name', $medication->name) }}"
                                class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="2">{{ old('description', $medication->description) }}</textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Quantity *</label>
                            <input type="number" name="quantity" value="{{ old('quantity', $medication->quantity) }}"
                                class="form-control" min="0" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Low Stock Threshold *</label>
                            <input type="number" name="low_stock_threshold" value="{{ old('low_stock_threshold', $medication->low_stock_threshold) }}"
                                class="form-control" min="0" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Unit</label>
                            <input type="text" name="unit" value="{{ old('unit', $medication->unit) }}"
                                class="form-control" placeholder="pcs / tablets / ml">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Production Date</label>
                            <input type="date" name="production_date"
                                value="{{ old('production_date', $medication->production_date?->format('Y-m-d')) }}"
                                class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Expiry Date *</label>
                            <input type="date" name="expiry_date"
                                value="{{ old('expiry_date', $medication->expiry_date?->format('Y-m-d')) }}"
                                class="form-control" required>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary btn-sm px-4">
                            <i class="bi bi-check-lg me-1"></i> Update Medication
                        </button>
                        <a href="{{ route('medications.index') }}" class="btn btn-sm btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Info sidebar --}}
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header"><i class="bi bi-info-circle me-2 text-primary"></i>Current Info</div>
            <div class="card-body">
                <div class="d-flex flex-column gap-3">
                    <div>
                        <div style="font-size:.72rem;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;font-weight:600;">Current Stock</div>
                        <div style="font-size:1.8rem;font-weight:700;color:{{ $medication->isLowStock() ? '#dc2626' : '#16a34a' }};">
                            {{ $medication->quantity }} <span style="font-size:.9rem;font-weight:400;">{{ $medication->unit }}</span>
                        </div>
                    </div>
                    <div>
                        <div style="font-size:.72rem;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;font-weight:600;">Status</div>
                        @if($medication->isLowStock())
                            <span class="badge" style="background:#fef2f2;color:#dc2626;font-size:.8rem;">
                                <i class="bi bi-exclamation-triangle-fill me-1"></i>Low Stock
                            </span>
                        @else
                            <span class="badge" style="background:#f0fdf4;color:#16a34a;font-size:.8rem;">
                                <i class="bi bi-check-circle-fill me-1"></i>Well Stocked
                            </span>
                        @endif
                    </div>
                    <div>
                        <div style="font-size:.72rem;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;font-weight:600;">Category</div>
                        <div style="font-size:.85rem;color:#334155;">{{ $medication->category->name ?? '—' }}</div>
                    </div>
                    <div>
                        <div style="font-size:.72rem;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;font-weight:600;">Last Updated</div>
                        <div style="font-size:.85rem;color:#334155;">{{ $medication->updated_at->format('M d, Y') }}</div>
                    </div>
                    @if($medication->production_date)
                    <div>
                        <div style="font-size:.72rem;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;font-weight:600;">Production Date</div>
                        <div style="font-size:.85rem;color:#334155;">{{ $medication->production_date->format('M d, Y') }}</div>
                    </div>
                    @endif
                    @if($medication->expiry_date)
                    <div>
                        <div style="font-size:.72rem;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;font-weight:600;">Expiry Date</div>
                        @if($medication->isExpired())
                            <span class="badge" style="background:#fef2f2;color:#dc2626;font-size:.8rem;">
                                <i class="bi bi-x-octagon-fill me-1"></i>Expired {{ $medication->expiry_date->format('M d, Y') }}
                            </span>
                        @elseif($medication->isExpiringSoon())
                            <span class="badge" style="background:#fffbeb;color:#d97706;font-size:.8rem;">
                                <i class="bi bi-clock-history me-1"></i>{{ $medication->expiry_date->format('M d, Y') }}
                                ({{ $medication->expiry_date->diffForHumans() }})
                            </span>
                        @else
                            <div style="font-size:.85rem;color:#334155;">{{ $medication->expiry_date->format('M d, Y') }}</div>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
