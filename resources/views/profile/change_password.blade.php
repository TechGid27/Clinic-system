@extends('admin.layout.app')
@section('title', 'Change Password')

@section('content')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-secondary me-3">
        <i class="bi bi-arrow-left"></i>
    </a>
    <div>
        <h5 class="fw-bold mb-0">Change Password</h5>
        <small class="text-muted">Update your account password</small>
    </div>
</div>

<div class="card" style="max-width:480px;">
    <div class="card-body p-4">
        @if($errors->any())
            <div class="alert alert-danger d-flex gap-2 align-items-start mb-3" style="font-size:.82rem;">
                <i class="bi bi-exclamation-triangle-fill mt-1"></i>
                <ul class="mb-0 ps-2">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif

        <form method="POST" action="{{ route('profile.change-password.update') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Current Password <span class="text-danger">*</span></label>
                <input type="password" name="current_password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">New Password <span class="text-danger">*</span></label>
                <input type="password" name="password" class="form-control" required minlength="8" placeholder="Min. 8 characters">
            </div>
            <div class="mb-4">
                <label class="form-label">Confirm New Password <span class="text-danger">*</span></label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-shield-lock me-1"></i> Update Password
            </button>
        </form>
    </div>
</div>
@endsection
