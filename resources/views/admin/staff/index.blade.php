@extends('admin.layout.app')
@section('title', 'Staff Management')

@section('content')

<div class="row g-3">

    {{-- LEFT: Staff List --}}
    <div class="col-lg-7">

        {{-- Add Staff --}}
        <div class="card mb-3">
            <div class="card-header">
                <i class="bi bi-person-plus me-2 text-primary"></i>Add New Staff
            </div>
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger d-flex gap-2 align-items-start" style="font-size:.82rem;">
                        <i class="bi bi-exclamation-triangle-fill mt-1"></i>
                        <ul class="mb-0 ps-2">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                    </div>
                @endif
                <form method="POST" action="{{ route('staff.store') }}">
                    @csrf
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="form-label">Full Name *</label>
                            <input type="text" name="name" value="{{ old('name') }}" class="form-control" placeholder="e.g. Juan Dela Cruz" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email *</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="staff@aclc.edu.ph" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Password *</label>
                            <input type="password" name="password" class="form-control" placeholder="Min. 8 characters" required minlength="8">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Confirm Password *</label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Repeat password" required>
                        </div>
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary btn-sm px-4">
                            <i class="bi bi-plus-lg me-1"></i> Create Account
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Staff Table --}}
        <div class="card">
            <div class="card-header">
                <i class="bi bi-people me-2 text-primary"></i>Staff Accounts
                <span class="badge bg-primary bg-opacity-10 text-primary ms-2">{{ $staffList->count() }}</span>
            </div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($staffList as $staff)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div style="width:30px;height:30px;border-radius:50%;background:#eff6ff;color:#1d4ed8;display:flex;align-items:center;justify-content:center;font-size:.7rem;font-weight:700;flex-shrink:0;">
                                        {{ strtoupper(substr($staff->name, 0, 2)) }}
                                    </div>
                                    <span>{{ $staff->name }}</span>
                                </div>
                            </td>
                            <td style="color:#64748b;">{{ $staff->email }}</td>
                            <td>
                                @if($staff->is_active)
                                    <span class="badge" style="background:#f0fdf4;color:#16a34a;">Active</span>
                                @else
                                    <span class="badge" style="background:#f8fafc;color:#94a3b8;">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    {{-- Toggle active switch --}}
                                    <form method="POST" action="{{ route('staff.toggle-active', $staff) }}" class="d-flex align-items-center">
                                        @csrf @method('PATCH')
                                        <div class="form-check form-switch mb-0" title="{{ $staff->is_active ? 'Deactivate' : 'Activate' }}">
                                            <input class="form-check-input" type="checkbox" role="switch"
                                                {{ $staff->is_active ? 'checked' : '' }}
                                                onchange="if(confirm('{{ $staff->is_active ? 'Deactivate' : 'Activate' }} {{ addslashes($staff->name) }}?')) this.form.submit(); else this.checked = {{ $staff->is_active ? 'true' : 'false' }};">
                                        </div>
                                    </form>
                                    {{-- Delete --}}
                                    <form method="POST" action="{{ route('staff.destroy', $staff) }}">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('Delete {{ addslashes($staff->name) }}? This cannot be undone.')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <i class="bi bi-people d-block fs-2 mb-2 opacity-25"></i>
                                No staff accounts yet.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- RIGHT: Module Toggles --}}
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-toggles me-2 text-primary"></i>Module Access
            </div>
            <div class="card-body">
                <p class="text-muted mb-3" style="font-size:.8rem;">
                    Toggle modules on or off for staff. Admins always have full access regardless of these settings.
                </p>

                @php
                    $moduleIcons = [
                        'categories' => ['icon' => 'bi-tag',              'color' => '#7c3aed', 'bg' => '#faf5ff'],
                        'medications' => ['icon' => 'bi-capsule',          'color' => '#1d4ed8', 'bg' => '#eff6ff'],
                        'requests'   => ['icon' => 'bi-clipboard2-pulse',  'color' => '#16a34a', 'bg' => '#f0fdf4'],
                        'reports'    => ['icon' => 'bi-bar-chart-line',    'color' => '#d97706', 'bg' => '#fffbeb'],
                    ];
                @endphp

                <div class="d-flex flex-column gap-2">
                    @foreach($modules as $mod)
                    @php $meta = $moduleIcons[$mod->module] ?? ['icon' => 'bi-grid', 'color' => '#64748b', 'bg' => '#f8fafc']; @endphp
                    <div class="d-flex align-items-center justify-content-between p-3 rounded-3"
                         style="background:{{ $mod->is_active ? $meta['bg'] : '#f8fafc' }};border:1px solid {{ $mod->is_active ? 'rgba(0,0,0,.06)' : '#e2e8f0' }};">
                        <div class="d-flex align-items-center gap-3">
                            <div style="width:36px;height:36px;border-radius:8px;background:{{ $mod->is_active ? $meta['color'] : '#cbd5e1' }};display:flex;align-items:center;justify-content:center;color:#fff;font-size:.95rem;">
                                <i class="bi {{ $meta['icon'] }}"></i>
                            </div>
                            <div>
                                <div style="font-size:.85rem;font-weight:600;color:{{ $mod->is_active ? '#1e293b' : '#94a3b8' }};">
                                    {{ ucfirst($mod->module) }}
                                </div>
                                <div style="font-size:.72rem;color:#94a3b8;">
                                    {{ $mod->is_active ? 'Accessible to staff' : 'Hidden from staff' }}
                                </div>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('staff.modules.toggle', $mod->module) }}">
                            @csrf @method('PATCH')
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" role="switch"
                                    style="width:2.4em;height:1.3em;"
                                    {{ $mod->is_active ? 'checked' : '' }}
                                    onchange="this.form.submit()">
                            </div>
                        </form>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
