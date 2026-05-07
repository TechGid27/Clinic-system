@extends('auth.layout.app')
@section('title', 'Login')

@section('content')
<h1 class="auth-title">Welcome back</h1>
<p class="auth-sub">Sign in to your account to continue.</p>

@if(session('success'))
    <div class="alert alert-success d-flex align-items-center gap-2 mb-3" style="font-size:.85rem;border-radius:8px;">
        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
    </div>
@endif

<form method="POST" action="{{ route('login') }}">
    @csrf
    <div class="mb-3">
        <label for="email" class="form-label">Email address</label>
        <input type="email" name="email" id="email" value="{{ old('email') }}"
            class="form-control @error('email') is-invalid @enderror"
            placeholder="you@aclc.edu.ph" autofocus required>
        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="mb-4">
        <label for="password" class="form-label">Password</label>
        <input type="password" name="password" id="password"
            class="form-control @error('password') is-invalid @enderror"
            placeholder="••••••••" required>
        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <button type="submit" class="btn-auth">Sign in</button>
</form>
@endsection
