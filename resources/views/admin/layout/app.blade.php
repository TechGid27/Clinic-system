<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <title>@yield('title') — ACLC Clinic</title>
    <style>
        :root {
            --sidebar-bg: #0f1b2d;
            --sidebar-hover: #1a2e4a;
            --sidebar-active: #1d4ed8;
            --sidebar-width: 240px;
            --topbar-height: 60px;
        }
        * { box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f1f5f9; margin: 0; }

        /* SIDEBAR */
        .sidebar {
            position: fixed; top: 0; left: 0; bottom: 0;
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            display: flex; flex-direction: column;
            z-index: 1000;
            transition: transform .25s ease;
        }
        .sidebar-logo {
            padding: 1.25rem 1.25rem 1rem;
            border-bottom: 1px solid rgba(255,255,255,.08);
        }
        .sidebar-logo .logo-icon {
            width: 36px; height: 36px;
            border-radius: 8px; display: inline-flex;
            align-items: center; justify-content: center;
            color: #fff; font-size: 1rem; margin-right: .6rem;
        }
        .sidebar-logo .logo-text { color: #fff; font-weight: 700; font-size: .85rem; line-height: 1.2; }
        .sidebar-logo .logo-sub  { color: rgba(255,255,255,.45); font-size: .7rem; }

        .sidebar-nav { flex: 1; padding: .75rem 0; overflow-y: auto; }
        .sidebar-nav .nav-section {
            padding: .5rem 1.25rem .25rem;
            font-size: .65rem; font-weight: 600;
            color: rgba(255,255,255,.3); text-transform: uppercase; letter-spacing: .08em;
        }
        .sidebar-nav .nav-link {
            display: flex; align-items: center; gap: .65rem;
            padding: .6rem 1.25rem;
            color: rgba(255,255,255,.65);
            font-size: .82rem; font-weight: 500;
            border-radius: 0; text-decoration: none;
            transition: background .15s, color .15s;
            position: relative;
        }
        .sidebar-nav .nav-link i { font-size: 1rem; width: 18px; text-align: center; }
        .sidebar-nav .nav-link:hover { background: var(--sidebar-hover); color: #fff; }
        .sidebar-nav .nav-link.active {
            background: var(--sidebar-active);
            color: #fff;
        }
        .sidebar-nav .nav-link.active::before {
            content: ''; position: absolute; left: 0; top: 0; bottom: 0;
            width: 3px; background: #60a5fa; border-radius: 0 2px 2px 0;
        }
        .sidebar-nav .nav-link .badge-off {
            margin-left: auto; font-size: .6rem;
            background: rgba(255,255,255,.15); color: rgba(255,255,255,.6);
            padding: .15rem .4rem; border-radius: 4px;
        }
        .sidebar-divider { border-top: 1px solid rgba(255,255,255,.08); margin: .5rem 0; }

        .sidebar-footer {
            padding: 1rem 1.25rem;
            border-top: 1px solid rgba(255,255,255,.08);
        }
        .sidebar-footer .user-info { display: flex; align-items: center; gap: .6rem; margin-bottom: .75rem; }
        .sidebar-footer .user-avatar {
            width: 32px; height: 32px; border-radius: 50%;
            background: #1d4ed8; color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-size: .75rem; font-weight: 700; flex-shrink: 0;
        }
        .sidebar-footer .user-name  { color: #fff; font-size: .8rem; font-weight: 600; line-height: 1.2; }
        .sidebar-footer .user-role  { color: rgba(255,255,255,.4); font-size: .7rem; }

        /* MAIN */
        .main-wrapper { margin-left: var(--sidebar-width); min-height: 100vh; display: flex; flex-direction: column; }
        .topbar {
            height: var(--topbar-height);
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            display: flex; align-items: center;
            padding: 0 1.5rem;
            position: sticky; top: 0; z-index: 100;
        }
        .topbar .page-title { font-size: 1rem; font-weight: 600; color: #1e293b; }
        .topbar .topbar-right { margin-left: auto; display: flex; align-items: center; gap: .75rem; }
        .main-content { padding: 1.5rem; flex: 1; }

        /* CARDS */
        .stat-card {
            background: #fff; border-radius: 12px;
            padding: 1.25rem 1.5rem;
            border: 1px solid #e2e8f0;
            display: flex; align-items: center; gap: 1rem;
        }
        .stat-card .stat-icon {
            width: 48px; height: 48px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.3rem; flex-shrink: 0;
        }
        .stat-card .stat-value { font-size: 1.6rem; font-weight: 700; color: #1e293b; line-height: 1; }
        .stat-card .stat-label { font-size: .75rem; color: #64748b; margin-top: .2rem; }

        .card { border: 1px solid #e2e8f0; border-radius: 12px; box-shadow: none; }
        .card-header { background: #fff; border-bottom: 1px solid #e2e8f0; padding: .9rem 1.25rem; font-weight: 600; font-size: .9rem; color: #1e293b; border-radius: 12px 12px 0 0 !important; }
        .table thead th { background: #f8fafc; font-size: .75rem; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: .04em; border-bottom: 1px solid #e2e8f0; padding: .75rem 1rem; }
        .table tbody td { padding: .75rem 1rem; font-size: .85rem; color: #334155; vertical-align: middle; border-bottom: 1px solid #f1f5f9; }
        .table tbody tr:last-child td { border-bottom: none; }
        .table tbody tr:hover td { background: #f8fafc; }

        /* FORM */
        .form-label { font-size: .8rem; font-weight: 600; color: #374151; margin-bottom: .35rem; }
        .form-control, .form-select {
            font-size: .85rem; border-color: #d1d5db;
            border-radius: 8px; padding: .5rem .75rem;
        }
        .form-control:focus, .form-select:focus { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,.1); }

        /* BUTTONS */
        .btn { border-radius: 8px; font-size: .82rem; font-weight: 500; }
        .btn-primary { background: #1d4ed8; border-color: #1d4ed8; }
        .btn-primary:hover { background: #1e40af; border-color: #1e40af; }

        /* BADGES */
        .badge { font-size: .7rem; font-weight: 600; padding: .3em .6em; border-radius: 6px; }

        /* TOGGLE SWITCH */
        .form-switch .form-check-input { width: 2.2em; height: 1.2em; cursor: pointer; }
        .form-switch .form-check-input:checked { background-color: #1d4ed8; border-color: #1d4ed8; }

        /* ALERTS */
        .alert { border-radius: 10px; font-size: .85rem; border: none; }

        /* MODALS */
        .modal-content { background: #fff !important; }
        .modal-content .form-control,
        .modal-content .form-select {
            background: #fff !important;
            color: #1e293b !important;
            border-color: #d1d5db !important;
        }
        .modal-content .form-label { color: #374151 !important; }
        .modal-content .modal-title { color: #1e293b !important; }

        /* MOBILE */
        .sidebar-toggle { display: none; }
        @media(max-width: 991px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .main-wrapper { margin-left: 0; }
            .sidebar-toggle { display: inline-flex; }
        }

        /* =====================
           ANIMATIONS
        ===================== */

        /* Page fade-in */
        @keyframes pageFadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .main-content { animation: pageFadeIn .35s ease both; }

        /* Stat cards stagger in */
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(18px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .stat-card {
            animation: slideUp .4s ease both;
            transition: transform .2s ease, box-shadow .2s ease;
        }
        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(0,0,0,.08) !important;
        }
        /* stagger each stat card */
        .row > *:nth-child(1) .stat-card { animation-delay: .05s; }
        .row > *:nth-child(2) .stat-card { animation-delay: .10s; }
        .row > *:nth-child(3) .stat-card { animation-delay: .15s; }
        .row > *:nth-child(4) .stat-card { animation-delay: .20s; }

        /* Cards fade in */
        @keyframes cardIn {
            from { opacity: 0; transform: translateY(12px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .card {
            animation: cardIn .4s ease both;
            transition: box-shadow .2s ease;
        }
        .card:hover { box-shadow: 0 4px 20px rgba(0,0,0,.07) !important; }

        /* Table rows slide in */
        @keyframes rowIn {
            from { opacity: 0; transform: translateX(-6px); }
            to   { opacity: 1; transform: translateX(0); }
        }
        .table tbody tr {
            animation: rowIn .3s ease both;
            transition: background .15s ease;
        }
        .table tbody tr:nth-child(1)  { animation-delay: .05s; }
        .table tbody tr:nth-child(2)  { animation-delay: .08s; }
        .table tbody tr:nth-child(3)  { animation-delay: .11s; }
        .table tbody tr:nth-child(4)  { animation-delay: .14s; }
        .table tbody tr:nth-child(5)  { animation-delay: .17s; }
        .table tbody tr:nth-child(6)  { animation-delay: .20s; }
        .table tbody tr:nth-child(7)  { animation-delay: .23s; }
        .table tbody tr:nth-child(8)  { animation-delay: .26s; }
        .table tbody tr:nth-child(9)  { animation-delay: .29s; }
        .table tbody tr:nth-child(10) { animation-delay: .32s; }

        /* Sidebar nav links */
        .sidebar-nav .nav-link {
            transition: background .18s ease, color .18s ease, padding-left .18s ease;
        }
        .sidebar-nav .nav-link:hover { padding-left: 1.5rem; }
        .sidebar-nav .nav-link.active { padding-left: 1.5rem; }

        /* Sidebar logo pulse */
        .logo-icon { transition: transform .2s ease; }
        .sidebar-logo:hover .logo-icon { transform: scale(1.1) rotate(-5deg); }

        /* Buttons */
        .btn {
            transition: transform .15s ease, box-shadow .15s ease, background .15s ease, border-color .15s ease;
            position: relative; overflow: hidden;
        }
        .btn:hover:not(:disabled) {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0,0,0,.12);
        }
        .btn:active:not(:disabled) { transform: translateY(0); box-shadow: none; }

        /* Ripple effect on buttons */
        .btn .ripple {
            position: absolute; border-radius: 50%;
            background: rgba(255,255,255,.35);
            transform: scale(0);
            animation: ripple .5s linear;
            pointer-events: none;
        }
        @keyframes ripple {
            to { transform: scale(4); opacity: 0; }
        }

        /* Form inputs */
        .form-control, .form-select {
            transition: border-color .2s ease, box-shadow .2s ease, transform .15s ease;
        }
        .form-control:focus, .form-select:focus { transform: translateY(-1px); }

        /* Alerts slide down */
        @keyframes alertIn {
            from { opacity: 0; transform: translateY(-10px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .alert { animation: alertIn .3s ease both; }

        /* Badges pop */
        .badge {
            transition: transform .15s ease;
        }
        .badge:hover { transform: scale(1.08); }

        /* Toggle switch */
        .form-check-input[type=checkbox] {
            transition: background-color .25s ease, border-color .25s ease;
            cursor: pointer;
        }

        /* Topbar */
        .topbar { transition: box-shadow .2s ease; }
        .topbar:hover { box-shadow: 0 2px 12px rgba(0,0,0,.06); }

        /* Page transition out (on link click) */
        body.page-leaving .main-content {
            animation: pageFadeOut .2s ease forwards;
        }
        @keyframes pageFadeOut {
            to { opacity: 0; transform: translateY(-6px); }
        }

        /* Skeleton shimmer for loading states */
        @keyframes shimmer {
            0%   { background-position: -400px 0; }
            100% { background-position: 400px 0; }
        }
        .skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 400px 100%;
            animation: shimmer 1.4s infinite;
            border-radius: 6px;
        }
    </style>
</head>
<body>

@php
    use App\Models\ModuleSetting;
    $isAdmin = auth()->user()?->isAdmin();
    $mods = ModuleSetting::allModules();
    $authUser = auth()->user();
    $initials = collect(explode(' ', $authUser->name))->map(fn($w) => strtoupper($w[0]))->take(2)->implode('');
@endphp

<aside class="sidebar" id="sidebar">
    <div class="sidebar-logo">
        <div class="d-flex align-items-center">
            <div class="logo-icon"><img src="/newAclcLogo-BQdiVkLw-removebg-preview.png" alt="logo" class="img-fluid"></div>
            <div>
                <div class="logo-text">ACLC Clinic</div>
                <div class="logo-sub">Information & Inventory</div>
            </div>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section">Main</div>

        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
            <i class="bi bi-grid-1x2"></i> Dashboard
        </a>

        @if($isAdmin || ($mods['categories'] ?? true))
        <a class="nav-link {{ request()->routeIs('categories*') ? 'active' : '' }}" href="{{ route('categories.index') }}">
            <i class="bi bi-tag"></i> Categories
            @if(!($mods['categories'] ?? true) && $isAdmin)<span class="badge-off">Off</span>@endif
        </a>
        @endif

        @if($isAdmin || ($mods['medications'] ?? true))
        <a class="nav-link {{ request()->routeIs('medications.index') || request()->routeIs('medications.create') || request()->routeIs('medications.edit') ? 'active' : '' }}" href="{{ route('medications.index') }}">
            <i class="bi bi-capsule"></i> Medications
            @if(!($mods['medications'] ?? true) && $isAdmin)<span class="badge-off">Off</span>@endif
        </a>
        <a class="nav-link {{ request()->routeIs('medications.archive') ? 'active' : '' }}" href="{{ route('medications.archive') }}"
           style="padding-left:2.2rem;font-size:.78rem;color:rgba(255,255,255,.45);">
            <i class="bi bi-archive" style="font-size:.85rem;"></i> Archive
        </a>
        @endif

        @if($isAdmin || ($mods['requests'] ?? true))
        <a class="nav-link {{ request()->routeIs('requests*') ? 'active' : '' }}" href="{{ route('requests.index') }}">
            <i class="bi bi-clipboard2-pulse"></i> Requests
            @if(!($mods['requests'] ?? true) && $isAdmin)<span class="badge-off">Off</span>@endif
        </a>
        @endif

        @if($isAdmin || ($mods['reports'] ?? true))
        <a class="nav-link {{ request()->routeIs('reports.restock') ? 'active' : '' }}" href="{{ route('reports.restock') }}">
            <i class="bi bi-bar-chart-line"></i> Restock Report
            @if(!($mods['reports'] ?? true) && $isAdmin)<span class="badge-off">Off</span>@endif
        </a>
        <a class="nav-link {{ request()->routeIs('reports.visits') ? 'active' : '' }}" href="{{ route('reports.visits') }}">
            <i class="bi bi-clipboard2-data"></i> Visit Report
            @if(!($mods['reports'] ?? true) && $isAdmin)<span class="badge-off">Off</span>@endif
        </a>
        @endif

        @if($isAdmin)
        <div class="sidebar-divider"></div>
        <div class="nav-section">Admin</div>
        <a class="nav-link {{ request()->routeIs('staff*') ? 'active' : '' }}" href="{{ route('staff.index') }}">
            <i class="bi bi-people"></i> Staff Management
        </a>
        @endif

        <div class="sidebar-divider"></div>
        <a class="nav-link {{ request()->routeIs('questionnaire*') ? 'active' : '' }}" href="{{ route('questionnaire') }}" target="_blank">
            <i class="bi bi-file-earmark-pdf"></i> Questionnaire
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="user-info">
            <div class="user-avatar">{{ $initials }}</div>
            <div>
                <div class="user-name">{{ $authUser->name }}</div>
                <div class="user-role">{{ ucfirst($authUser->role) }}</div>
            </div>
        </div>
        <a href="{{ route('profile.change-password') }}" class="btn btn-sm w-100 mb-2" style="background:rgba(255,255,255,.06);color:rgba(255,255,255,.6);border:1px solid rgba(255,255,255,.1);">
            <i class="bi bi-shield-lock me-1"></i> Change Password
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-sm w-100" style="background:rgba(255,255,255,.08);color:rgba(255,255,255,.7);border:1px solid rgba(255,255,255,.12);">
                <i class="bi bi-box-arrow-left me-1"></i> Logout
            </button>
        </form>
    </div>
</aside>

<div class="main-wrapper">
    <div class="topbar">
        <button class="btn btn-sm sidebar-toggle me-3" id="sidebarToggle" style="background:none;border:none;color:#64748b;font-size:1.2rem;">
            <i class="bi bi-list"></i>
        </button>
        <span class="page-title">@yield('title')</span>
        <div class="topbar-right">
            <span class="text-muted" style="font-size:.8rem;">{{ now()->format('M d, Y') }}</span>
        </div>
    </div>

    <div class="main-content">
        <div class="sidebar-overlay position-fixed top-0 start-0 w-100 h-100 bg-dark bg-opacity-50" id="sidebarOverlay" style="display:none;z-index:999"></div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-3">
                <i class="bi bi-check-circle-fill"></i>
                {{ session('success') }}
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 mb-3">
                <i class="bi bi-exclamation-triangle-fill"></i>
                {{ session('error') }}
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script>
(function(){
    var s=document.getElementById('sidebar'),
        t=document.getElementById('sidebarToggle'),
        o=document.getElementById('sidebarOverlay');
    if(t) t.addEventListener('click',function(){ s.classList.toggle('show'); o.style.display=s.classList.contains('show')?'block':'none'; });
    if(o) o.addEventListener('click',function(){ s.classList.remove('show'); o.style.display='none'; });
})();

// Ripple effect on all buttons
document.addEventListener('click', function(e) {
    const btn = e.target.closest('.btn');
    if (!btn) return;
    const r = document.createElement('span');
    r.className = 'ripple';
    const size = Math.max(btn.offsetWidth, btn.offsetHeight);
    const rect = btn.getBoundingClientRect();
    r.style.cssText = `width:${size}px;height:${size}px;left:${e.clientX-rect.left-size/2}px;top:${e.clientY-rect.top-size/2}px`;
    btn.appendChild(r);
    r.addEventListener('animationend', () => r.remove());
});

// Page-leave fade on internal nav links
document.querySelectorAll('a[href]').forEach(function(a) {
    const href = a.getAttribute('href');
    if (!href || href.startsWith('#') || href.startsWith('javascript') || a.target === '_blank') return;
    a.addEventListener('click', function(e) {
        // skip if modifier key held
        if (e.ctrlKey || e.metaKey || e.shiftKey) return;
        e.preventDefault();
        document.body.classList.add('page-leaving');
        const dest = this.href;
        setTimeout(function(){ window.location.href = dest; }, 180);
    });
});
</script>
@stack('scripts')
</body>
</html>
