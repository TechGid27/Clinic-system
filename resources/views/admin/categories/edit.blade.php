@extends('admin.layout.app')
@section('title', 'Edit Category')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <p class="text-muted mb-0" style="font-size:.85rem;">Update category details</p>
    <a href="{{ route('categories.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Back
    </a>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><i class="bi bi-pencil me-2 text-primary"></i>Edit Category</div>
            <div class="card-body">
                <form method="POST" action="{{ route('categories.update', $category) }}">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Name *</label>
                        <input type="text" name="name" value="{{ old('name', $category->name) }}"
                            class="form-control @error('name') is-invalid @enderror" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description', $category->description) }}</textarea>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-sm px-4">
                            <i class="bi bi-check-lg me-1"></i> Update Category
                        </button>
                        <a href="{{ route('categories.index') }}" class="btn btn-sm btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
