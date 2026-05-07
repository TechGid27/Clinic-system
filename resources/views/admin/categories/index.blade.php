@extends('admin.layout.app')
@section('title', 'Categories')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <p class="text-muted mb-0" style="font-size:.85rem;">Manage medication categories</p>
    </div>
    <a href="{{ route('categories.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i> Add Category
    </a>
</div>

<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <span><i class="bi bi-tag me-2 text-primary"></i>All Categories</span>
        <span class="badge bg-primary bg-opacity-10 text-primary">{{ $categories->total() }} total</span>
    </div>
    <div class="card-body p-0">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Medications</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $cat)
                <tr>
                    <td class="fw-500">{{ $cat->name }}</td>
                    <td style="color:#64748b;">{{ Str::limit($cat->description ?? '—', 60) }}</td>
                    <td>
                        <span class="badge bg-primary bg-opacity-10 text-primary">{{ $cat->medications_count }}</span>
                    </td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('categories.edit', $cat) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="{{ route('categories.destroy', $cat) }}">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                    onclick="return confirm('Delete this category? Medications must be removed first.')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-5 text-muted">
                        <i class="bi bi-tag d-block fs-2 mb-2 opacity-25"></i>
                        No categories yet. <a href="{{ route('categories.create') }}">Add one</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white border-top" style="border-radius:0 0 12px 12px;">
        {{ $categories->links() }}
    </div>
</div>
@endsection
