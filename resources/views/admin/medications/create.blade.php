@extends('admin.layout.app')
@section('title', 'Add Medication')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <p class="text-muted mb-0" style="font-size:.85rem;">Add one or multiple medications at once</p>
    <a href="{{ route('medications.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Back
    </a>
</div>

<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <span><i class="bi bi-capsule me-2 text-primary"></i>Add Medication / Supply</span>
        <button type="button" class="btn btn-sm btn-outline-primary" onclick="addMedication()">
            <i class="bi bi-plus-lg me-1"></i> Add Another
        </button>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('medications.store') }}" id="medForm">
            @csrf

            @if($errors->any())
            <div class="alert alert-danger d-flex gap-2 align-items-start mb-3" style="font-size:.82rem;">
                <i class="bi bi-exclamation-triangle-fill mt-1"></i>
                <ul class="mb-0 ps-2">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <div id="medications-container">
                <div class="medication-item border rounded-3 p-3 mb-3" style="background:#fafafa;">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="fw-600" style="font-size:.82rem;color:#64748b;">MEDICATION #1</span>
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeMedication(this)">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Category <span class="text-danger">*</span></label>
                            <select name="medications[0][category_id]" class="form-select" required>
                                @foreach($categories as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" name="medications[0][name]" class="form-control" placeholder="e.g. Paracetamol 500mg" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea name="medications[0][description]" class="form-control" rows="2" placeholder="Optional..."></textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Quantity <span class="text-danger">*</span></label>
                            <input type="number" name="medications[0][quantity]" class="form-control" min="0" placeholder="0" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Low Stock Threshold <span class="text-danger">*</span></label>
                            <input type="number" name="medications[0][low_stock_threshold]" class="form-control" min="0" placeholder="10" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Unit</label>
                            <input type="text" name="medications[0][unit]" class="form-control" value="pcs" placeholder="pcs / tablets / ml">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Production Date (Optional)</label>
                            <input type="date" name="medications[0][production_date]" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Expiry Date <span class="text-danger">*</span></label>
                            <input type="date" name="medications[0][expiry_date]" class="form-control" required>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2 mt-2">
                <button type="submit" class="btn btn-primary btn-sm px-4">
                    <i class="bi bi-check-lg me-1"></i> Save All
                </button>
                <a href="{{ route('medications.index') }}" class="btn btn-sm btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
let idx = 1;
const categoriesHtml = `@foreach($categories as $c)<option value="{{ $c->id }}">{{ $c->name }}</option>@endforeach`;

function addMedication() {
    const container = document.getElementById('medications-container');
    const num = container.querySelectorAll('.medication-item').length + 1;
    const html = `
        <div class="medication-item border rounded-3 p-3 mb-3" style="background:#fafafa;">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="fw-600" style="font-size:.82rem;color:#64748b;">MEDICATION #${num}</span>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeMedication(this)">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Category <span class="text-danger">*</span></label>
                    <select name="medications[${idx}][category_id]" class="form-select" required>
                        ${categoriesHtml}
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Name <span class="text-danger">*</span></label>
                    <input type="text" name="medications[${idx}][name]" class="form-control" placeholder="e.g. Paracetamol 500mg" required>
                </div>
                <div class="col-12">
                    <label class="form-label">Description</label>
                    <textarea name="medications[${idx}][description]" class="form-control" rows="2" placeholder="Optional..."></textarea>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Quantity <span class="text-danger">*</span></label>
                    <input type="number" name="medications[${idx}][quantity]" class="form-control" min="0" placeholder="0" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Low Stock Threshold <span class="text-danger">*</span></label>
                    <input type="number" name="medications[${idx}][low_stock_threshold]" class="form-control" min="0" placeholder="10" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Unit</label>
                    <input type="text" name="medications[${idx}][unit]" class="form-control" value="pcs" placeholder="pcs / tablets / ml">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Production Date</label>
                    <input type="date" name="medications[${idx}][production_date]" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Expiry Date <span class="text-danger">*</span></label>
                    <input type="date" name="medications[${idx}][expiry_date]" class="form-control" required>
                </div>
            </div>
        </div>`;
    container.insertAdjacentHTML('beforeend', html);
    idx++;
}

function removeMedication(btn) {
    const items = document.querySelectorAll('.medication-item');
    if (items.length === 1) return;
    btn.closest('.medication-item').remove();
}
</script>
@endpush
@endsection
