<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — PhysioCare</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Inter', sans-serif; background: #f1f5f9; color: #1e293b; }

    /* ── SIDEBAR ── */
    .adm-sidebar {
        position: fixed; top: 0; left: 0; bottom: 0;
        width: 255px; background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%);
        display: flex; flex-direction: column; z-index: 200; overflow-y: auto;
    }
    .sb-brand {
        padding: 1.4rem 1.3rem 1rem;
        border-bottom: 1px solid rgba(255,255,255,.07);
        display: flex; align-items: center; gap: 10px; text-decoration: none;
    }
    .sb-brand-icon {
        width: 36px; height: 36px; background: linear-gradient(135deg,#3b82f6,#06b6d4);
        border-radius: 9px; display: flex; align-items: center; justify-content: center;
        font-size: 17px; flex-shrink: 0;
    }
    .sb-brand-text { font-size: 14px; font-weight: 700; color: #fff; line-height: 1.2; }
    .sb-brand-sub  { font-size: 10px; color: #64748b; }

    .sb-section { font-size: 10px; font-weight: 700; color: #475569;
        text-transform: uppercase; letter-spacing: .08em;
        padding: .5rem .9rem; margin-top: .75rem; }

    .sb-nav { padding: .5rem .6rem; flex: 1; }
    .sb-link {
        display: flex; align-items: center; gap: 9px; padding: .6rem .8rem;
        border-radius: 8px; text-decoration: none; color: #94a3b8;
        font-size: 13px; font-weight: 500; transition: all .2s; margin-bottom: 2px;
    }
    .sb-link:hover, .sb-link.active {
        background: rgba(59,130,246,.18); color: #93c5fd;
    }
    .sb-link.active { color: #60a5fa; font-weight: 600; }
    .sb-icon { font-size: 15px; width: 20px; text-align: center; flex-shrink: 0; }

    .sb-footer {
        padding: .9rem; border-top: 1px solid rgba(255,255,255,.06);
    }
    .sb-logout {
        width: 100%; background: rgba(239,68,68,.1); color: #f87171;
        border: none; border-radius: 8px; padding: .6rem 1rem;
        font-size: 13px; font-weight: 600; cursor: pointer;
        display: flex; align-items: center; gap: 8px; transition: all .2s;
    }
    .sb-logout:hover { background: rgba(239,68,68,.2); }

    /* ── MAIN ── */
    .adm-main { margin-left: 255px; min-height: 100vh; display: flex; flex-direction: column; }

    /* ── TOPBAR ── */
    .adm-topbar {
        background: #fff; border-bottom: 1px solid #e2e8f0;
        padding: .9rem 1.75rem; display: flex; align-items: center;
        justify-content: space-between; position: sticky; top: 0; z-index: 100;
    }
    .topbar-left h2 { font-size: 16px; font-weight: 700; color: #0f172a; }
    .topbar-left .breadcrumb { font-size: 12px; color: #64748b; margin-top: 2px; }
    .topbar-right { display: flex; align-items: center; gap: .75rem; }
    .topbar-avatar {
        width: 34px; height: 34px; background: linear-gradient(135deg,#3b82f6,#06b6d4);
        border-radius: 50%; display: flex; align-items: center; justify-content: center;
        color: #fff; font-size: 13px; font-weight: 700;
    }
    .topbar-name { font-size: 13px; font-weight: 600; color: #374151; }

    /* ── CONTENT ── */
    .adm-content { padding: 1.75rem; flex: 1; }

    /* ── ALERTS ── */
    .alert { padding: .85rem 1.1rem; border-radius: 10px; margin-bottom: 1.25rem; font-size: 13.5px; font-weight: 500; display: flex; align-items: center; gap: 8px; }
    .alert-success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; }
    .alert-error   { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; }
    .alert-info    { background: #eff6ff; border: 1px solid #bfdbfe; color: #1e40af; }

    /* ── CARD ── */
    .card { background: #fff; border-radius: 14px; border: 1px solid #e2e8f0; overflow: hidden; }
    .card-header { padding: 1.1rem 1.4rem; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between; }
    .card-title  { font-size: 14.5px; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 7px; }
    .card-body   { padding: 1.25rem 1.4rem; }

    /* ── BUTTONS ── */
    .btn { display: inline-flex; align-items: center; gap: 6px; padding: .55rem 1.1rem; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; text-decoration: none; border: none; transition: all .2s; }
    .btn-primary { background: #3b82f6; color: #fff; }
    .btn-primary:hover { background: #2563eb; }
    .btn-success { background: #10b981; color: #fff; }
    .btn-success:hover { background: #059669; }
    .btn-danger  { background: #ef4444; color: #fff; }
    .btn-danger:hover  { background: #dc2626; }
    .btn-warning { background: #f59e0b; color: #fff; }
    .btn-warning:hover { background: #d97706; }
    .btn-ghost   { background: #f1f5f9; color: #374151; border: 1px solid #e2e8f0; }
    .btn-ghost:hover   { background: #e2e8f0; }
    .btn-sm { padding: .35rem .75rem; font-size: 12px; }

    /* ── TABLE ── */
    .tbl { width: 100%; border-collapse: collapse; }
    .tbl th { background: #f8fafc; padding: .75rem 1rem; font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: .05em; text-align: left; border-bottom: 1px solid #e2e8f0; }
    .tbl td { padding: .85rem 1rem; font-size: 13.5px; border-bottom: 1px solid #f1f5f9; color: #374151; vertical-align: middle; }
    .tbl tr:hover td { background: #f8fafc; }
    .tbl tr:last-child td { border-bottom: none; }

    /* ── BADGES ── */
    .badge { display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; text-transform: uppercase; }
    .badge-pending   { background: #fef9c3; color: #92400e; }
    .badge-confirmed { background: #dcfce7; color: #166534; }
    .badge-completed { background: #dbeafe; color: #1e40af; }
    .badge-cancelled { background: #fee2e2; color: #991b1b; }
    .badge-paid      { background: #d1fae5; color: #065f46; }
    .badge-unpaid    { background: #fee2e2; color: #991b1b; }
    .badge-partial   { background: #fef3c7; color: #92400e; }

    /* ── FORM ── */
    .form-group { margin-bottom: 1.1rem; }
    .form-label { display: block; font-size: 12.5px; font-weight: 600; color: #374151; margin-bottom: .4rem; }
    .form-control {
        width: 100%; padding: .6rem .85rem; border: 1.5px solid #e2e8f0; border-radius: 8px;
        font-size: 13.5px; font-family: 'Inter', sans-serif; transition: all .2s;
        background: #fff;
    }
    .form-control:focus { outline: none; border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,.12); }
    .form-text  { font-size: 11.5px; color: #94a3b8; margin-top: 3px; }
    .form-error { font-size: 12px; color: #ef4444; margin-top: 3px; }

    /* ── GRID ── */
    .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1.1rem; }
    .grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.1rem; }
    .grid-4 { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.1rem; }

    /* ── MODAL ── */
    .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.5); z-index: 500; align-items: center; justify-content: center; }
    .modal-overlay.open { display: flex; }
    .modal-box { background: #fff; border-radius: 14px; padding: 1.75rem; max-width: 520px; width: 95%; max-height: 90vh; overflow-y: auto; }
    .modal-title { font-size: 16px; font-weight: 700; margin-bottom: 1.2rem; color: #0f172a; }

    /* ── TABS ── */
    .tabs { display: flex; gap: 2px; border-bottom: 2px solid #e2e8f0; margin-bottom: 1.5rem; }
    .tab-btn { padding: .6rem 1.2rem; border: none; background: none; font-size: 13px; font-weight: 500; color: #64748b; cursor: pointer; border-bottom: 2px solid transparent; margin-bottom: -2px; transition: all .2s; }
    .tab-btn.active { color: #3b82f6; border-bottom-color: #3b82f6; font-weight: 700; }
    .tab-panel { display: none; }
    .tab-panel.active { display: block; }

    /* ── STAT CARD ── */
    .stat-card { background: #fff; border-radius: 14px; border: 1px solid #e2e8f0; padding: 1.3rem; position: relative; overflow: hidden; }
    .stat-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; }
    .stat-card.blue::before   { background: linear-gradient(90deg,#3b82f6,#06b6d4); }
    .stat-card.green::before  { background: linear-gradient(90deg,#10b981,#34d399); }
    .stat-card.amber::before  { background: linear-gradient(90deg,#f59e0b,#fbbf24); }
    .stat-card.purple::before { background: linear-gradient(90deg,#8b5cf6,#a78bfa); }
    .stat-card.rose::before   { background: linear-gradient(90deg,#f43f5e,#fb7185); }
    .stat-icon { width: 42px; height: 42px; border-radius: 11px; display: flex; align-items: center; justify-content: center; font-size: 19px; margin-bottom: .9rem; }
    .stat-icon.blue   { background: #eff6ff; }
    .stat-icon.green  { background: #f0fdf4; }
    .stat-icon.amber  { background: #fffbeb; }
    .stat-icon.purple { background: #f5f3ff; }
    .stat-icon.rose   { background: #fff1f2; }
    .stat-value { font-size: 28px; font-weight: 800; color: #0f172a; line-height: 1; }
    .stat-label { font-size: 12.5px; color: #64748b; margin-top: 3px; }

    /* ── EMPTY ── */
    .empty-state { text-align: center; padding: 3.5rem 1rem; }
    .empty-icon  { font-size: 48px; margin-bottom: .9rem; }
    .empty-title { font-size: 15px; font-weight: 700; color: #374151; }
    .empty-sub   { font-size: 13px; color: #9ca3af; margin-top: .4rem; }

    @media (max-width: 900px) {
        .adm-sidebar { transform: translateX(-100%); }
        .adm-main { margin-left: 0; }
        .grid-4, .grid-3 { grid-template-columns: 1fr 1fr; }
    }
    @media (max-width: 600px) {
        .grid-2, .grid-4, .grid-3 { grid-template-columns: 1fr; }
    }
    </style>
    @stack('styles')
</head>
<body>

{{-- SIDEBAR --}}
<aside class="adm-sidebar">
    @php
        $rol = strtolower(Auth::user()->role->nombre_rol ?? 'patient');
        $isAdmin = ($rol === 'admin');
        $isDoctor = in_array($rol, ['doctor', 'physiotherapist']);
    @endphp

    <a href="{{ route('dashboard') }}" class="sb-brand">
        <img src="{{ asset('images/physiocare_logo_premium.png') }}" alt="Logo" style="width: 36px; height: 36px; border-radius: 9px; object-fit: cover;">
        <div>
            <div class="sb-brand-text">PhysioCare</div>
            <div class="sb-brand-sub">{{ $isAdmin ? 'Admin Panel' : 'Doctor Portal' }}</div>
        </div>
    </a>

    <nav class="sb-nav">

        <div class="sb-section">Overview</div>
        <a href="{{ route('dashboard') }}" class="sb-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <span class="sb-icon">🏠</span> Dashboard
        </a>

        @if($isAdmin || $isDoctor)
            <div class="sb-section">Management</div>
            <a href="{{ route('admin.patients.index') }}" class="sb-link {{ request()->routeIs('admin.patients.*') ? 'active' : '' }}">
                <span class="sb-icon">👥</span> Patients
            </a>
            <a href="{{ route('admin.treatment-calendar') }}" class="sb-link {{ request()->routeIs('admin.treatment-calendar') ? 'active' : '' }}">
                <span class="sb-icon">📅</span> Treatment Calendar
            </a>
        @endif

        @if($isAdmin)
            <a href="{{ route('admin.appointments.index') }}" class="sb-link {{ request()->routeIs('admin.appointments.*') ? 'active' : '' }}">
                <span class="sb-icon">📅</span> Appointments
            </a>
            <a href="{{ route('admin.doctors.index') }}" class="sb-link {{ request()->routeIs('admin.doctors.*') ? 'active' : '' }}">
                <span class="sb-icon">👨‍⚕️</span> Physiotherapists
            </a>

            <div class="sb-section">Billing & Reports</div>
            <a href="{{ route('admin.invoices.index') }}" class="sb-link {{ request()->routeIs('admin.invoices.*') ? 'active' : '' }}">
                <span class="sb-icon">💰</span> Invoices
            </a>

            <div class="sb-section">System</div>
            <a href="{{ route('admin.users.index') }}" class="sb-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <span class="sb-icon">🔐</span> Users
            </a>
        @endif

        @if($isDoctor)
            <div class="sb-section">My Clinic</div>
            <a href="{{ route('doctor.mi-schedule') }}" class="sb-link {{ request()->routeIs('doctor.mi-schedule') ? 'active' : '' }}">
                <span class="sb-icon">⏰</span> Availability
            </a>
        @endif

        <div class="sb-section">Personal</div>
        <a href="{{ route('profile.edit') }}" class="sb-link">
            <span class="sb-icon">👤</span> My Profile
        </a>
    </nav>

    <div class="sb-footer">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="sb-logout">🚪 Log Out</button>
        </form>
    </div>
</aside>

{{-- MAIN --}}
<main class="adm-main">
    {{-- TOPBAR --}}
    <div class="adm-topbar">
        <div class="topbar-left">
            <h2>@yield('page-title', 'Dashboard')</h2>
            <div class="breadcrumb">@yield('breadcrumb', 'Admin / Dashboard')</div>
        </div>
        <div class="topbar-right">
            <div class="topbar-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
            <span class="topbar-name">{{ Auth::user()->name }}</span>
        </div>
    </div>

    <div class="adm-content">
        @if(session('success'))
            <div class="alert alert-success">✅ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">❌ {{ session('error') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-error">
                ❌ {{ $errors->first() }}
            </div>
        @endif

        @yield('content')
    </div>
</main>

<script>
// Simple tab system
document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        const group = btn.closest('[data-tabs]') || btn.closest('.card');
        const target = btn.dataset.tab;
        group.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        group.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
        btn.classList.add('active');
        const panel = group.querySelector('#tab-' + target);
        if (panel) panel.classList.add('active');
    });
});
</script>
@stack('scripts')
</body>
</html>
