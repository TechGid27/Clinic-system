@extends('admin.layout.app')
@section('title', 'Create Request')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <p class="text-muted mb-0" style="font-size:.85rem;">Record a new medication disbursement</p>
    <a href="{{ route('requests.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Back
    </a>
</div>

@if($errors->any())
<div class="alert alert-danger d-flex gap-2 align-items-start mb-3" style="font-size:.82rem;">
    <i class="bi bi-exclamation-triangle-fill mt-1"></i>
    <ul class="mb-0 ps-2">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
</div>
@endif

@if($medications->isEmpty())
<div class="alert d-flex align-items-center gap-2" style="background:#fffbeb;color:#92400e;border-left:4px solid #f59e0b;">
    <i class="bi bi-exclamation-triangle-fill"></i>
    No medications with available stock. <a href="{{ route('medications.create') }}" class="ms-1">Add medications first.</a>
</div>
@else

<form method="POST" action="{{ route('requests.store') }}" id="requestForm">
    @csrf
    <div class="row g-3">

        {{-- STUDENT INFO --}}
        <div class="col-12">
            <div class="card">
                <div class="card-header"><i class="bi bi-person me-2 text-primary"></i>Student Information</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Student Name *</label>
                            <input type="text" name="student_name" value="{{ old('student_name') }}"
                                class="form-control" placeholder="Full name" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Course *</label>
                            <input type="text" name="course" value="{{ old('course') }}"
                                class="form-control" placeholder="e.g. BSIT" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Year Level *</label>
                            <select name="year_level" class="form-select" required>
                                <option value="">— Select —</option>
                                @foreach(['1st Year','2nd Year','3rd Year','4th Year'] as $yr)
                                    <option value="{{ $yr }}" {{ old('year_level') == $yr ? 'selected' : '' }}>{{ $yr }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Reason *</label>
                            <input type="text" name="reason" value="{{ old('reason') }}"
                                class="form-control" placeholder="e.g. Headache, Fever" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- MEDICATIONS --}}
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <span><i class="bi bi-capsule me-2 text-primary"></i>Select Medications</span>
                    <input type="text" id="medSearch" class="form-control form-control-sm"
                        placeholder="Search medication or category..." style="max-width:240px;">
                </div>
                <div class="card-body p-0">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th style="width:48px;"></th>
                                <th>Medication</th>
                                <th>Category</th>
                                <th>Available</th>
                                <th style="width:110px;">Qty</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $idx = 0; @endphp
                            @foreach($medications as $catName => $items)
                                @foreach($items as $med)
                                <tr class="med-row">
                                    <td>
                                        <input type="checkbox"
                                            name="items[{{ $idx }}][medication_id]"
                                            value="{{ $med->id }}"
                                            class="form-check-input item-check" style="width:1.1em;height:1.1em;">
                                    </td>
                                    <td class="med-name fw-500">{{ $med->name }}</td>
                                    <td class="med-category" style="color:#64748b;">{{ $catName }}</td>
                                    <td>
                                        <span class="badge" style="background:#f0fdf4;color:#16a34a;">
                                            {{ $med->quantity }} {{ $med->unit }}
                                        </span>
                                    </td>
                                    <td>
                                        <input type="number"
                                            name="items[{{ $idx }}][quantity]"
                                            min="1" max="{{ $med->quantity }}"
                                            class="form-control form-control-sm item-qty"
                                            placeholder="Qty"
                                            style="display:none;">
                                    </td>
                                </tr>
                                @php $idx++; @endphp
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer bg-white d-flex gap-2" style="border-radius:0 0 12px 12px;">
                    <button type="submit" class="btn btn-primary btn-sm px-4">
                        <i class="bi bi-check-lg me-1"></i> Record & Disburse
                    </button>
                    <a href="{{ route('requests.index') }}" class="btn btn-sm btn-outline-secondary">Cancel</a>
                </div>
            </div>
        </div>

    </div>
</form>

@push('scripts')
<script>
document.querySelectorAll('.item-check').forEach(function(cb) {
    cb.addEventListener('change', function() {
        const qty = this.closest('tr').querySelector('.item-qty');
        qty.style.display = this.checked ? 'block' : 'none';
        qty.required = this.checked;
        if (!this.checked) qty.value = '';
    });
});

document.getElementById('requestForm').addEventListener('submit', function() {
    document.querySelectorAll('.item-check:not(:checked)').forEach(function(cb) {
        cb.disabled = true;
        const qty = cb.closest('tr').querySelector('.item-qty');
        if (qty) qty.removeAttribute('name');
    });
});

document.getElementById('medSearch').addEventListener('keyup', function() {
    const s = this.value.toLowerCase();
    document.querySelectorAll('.med-row').forEach(function(row) {
        const name = row.querySelector('.med-name').textContent.toLowerCase();
        const cat  = row.querySelector('.med-category').textContent.toLowerCase();
        row.style.display = (name.includes(s) || cat.includes(s)) ? '' : 'none';
    });
});
</script>
@endpush

@endif
@endsection
