@extends('admin.layout.app')
@section('title', 'Add Category')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <p class="text-muted mb-0" style="font-size:.85rem;">Create a new medication category</p>
    <a href="{{ route('categories.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Back
    </a>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><i class="bi bi-tag me-2 text-primary"></i>Category Details</div>
            <div class="card-body">
                <form method="POST" action="{{ route('categories.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Name *</label>
                        <input type="text" name="name" value="{{ old('name') }}"
                            class="form-control @error('name') is-invalid @enderror"
                            placeholder="e.g. Over-the-counter" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3"
                            placeholder="Optional description...">{{ old('description') }}</textarea>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-sm px-4">
                            <i class="bi bi-check-lg me-1"></i> Save Category
                        </button>
                        <a href="{{ route('categories.index') }}" class="btn btn-sm btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
