@extends('layouts.app', ['showNavbar' => false])
@section('content')

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

* { box-sizing: border-box; }

body {
    font-family: 'Inter', sans-serif;
    background: #f0f4f8;
    color: #1a202c;
}

.doc-layout {
    display: flex;
    min-height: 100vh;
}

/* ── SIDEBAR ── */
.doc-sidebar {
    width: 260px;
    flex-shrink: 0;
    background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%);
    display: flex;
    flex-direction: column;
    padding: 0;
    position: fixed;
    top: 0; left: 0; bottom: 0;
    z-index: 100;
    overflow-y: auto;
}

.sidebar-brand {
    padding: 1.75rem 1.5rem 1.25rem;
    border-bottom: 1px solid rgba(255,255,255,0.08);
}

.sidebar-brand-logo {
    display: flex;
    align-items: center;
    gap: 10px;
    text-decoration: none;
}

.sidebar-brand-icon {
    width: 38px; height: 38px;
    background: linear-gradient(135deg, #3b82f6, #06b6d4);
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 18px;
    flex-shrink: 0;
}

.sidebar-brand-name {
    font-size: 15px;
    font-weight: 700;
    color: #fff;
    line-height: 1.2;
}

.sidebar-brand-sub {
    font-size: 11px;
    color: #94a3b8;
    font-weight: 400;
}

.sidebar-doctor-card {
    margin: 1rem 1rem 0.5rem;
    background: rgba(255,255,255,0.06);
    border-radius: 12px;
    padding: 1rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.doctor-avatar {
    width: 44px; height: 44px;
    background: linear-gradient(135deg, #3b82f6, #06b6d4);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 18px;
    font-weight: 700;
    color: #fff;
    flex-shrink: 0;
}

.doctor-card-info .doctor-name {
    font-size: 13px;
    font-weight: 600;
    color: #f1f5f9;
}

.doctor-card-info .doctor-role {
    font-size: 11px;
    color: #64748b;
    margin-top: 2px;
}

.sidebar-nav {
    padding: 0.75rem 0.75rem;
    flex: 1;
}

.nav-section-label {
    font-size: 10px;
    font-weight: 700;
    color: #475569;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    padding: 0.5rem 0.75rem;
    margin-top: 0.75rem;
}

.nav-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 0.65rem 0.9rem;
    border-radius: 9px;
    text-decoration: none;
    color: #94a3b8;
    font-size: 13.5px;
    font-weight: 500;
    transition: all 0.2s;
    margin-bottom: 2px;
}

.nav-item:hover, .nav-item.active {
    background: rgba(59,130,246,0.15);
    color: #93c5fd;
}

.nav-item.active {
    background: rgba(59,130,246,0.2);
    color: #60a5fa;
    font-weight: 600;
}

.nav-item-icon {
    font-size: 16px;
    width: 22px;
    text-align: center;
    flex-shrink: 0;
}

.sidebar-footer {
    padding: 1rem;
    border-top: 1px solid rgba(255,255,255,0.06);
    margin-top: auto;
}

/* ── MAIN ── */
.doc-main {
    margin-left: 260px;
    flex: 1;
    display: flex;
    flex-direction: column;
    min-height: 100vh;
}

.doc-topbar {
    background: #fff;
    border-bottom: 1px solid #e2e8f0;
    padding: 1rem 2rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: sticky;
    top: 0;
    z-index: 50;
}

.topbar-title {
    font-size: 20px;
    font-weight: 700;
    color: #0f172a;
}

.topbar-date {
    font-size: 13px;
    color: #64748b;
    margin-top: 2px;
}

.topbar-actions {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.topbar-badge {
    background: #eff6ff;
    color: #3b82f6;
    border: 1px solid #bfdbfe;
    border-radius: 20px;
    padding: 4px 12px;
    font-size: 12px;
    font-weight: 600;
}

.doc-content {
    padding: 2rem;
    flex: 1;
}

/* ── STATS ROW ── */
.stats-row {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1.25rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: #fff;
    border-radius: 16px;
    padding: 1.5rem;
    border: 1px solid #e2e8f0;
    position: relative;
    overflow: hidden;
    transition: all 0.25s;
}

.stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 30px rgba(0,0,0,0.08);
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
}

.stat-card.blue::before  { background: linear-gradient(90deg, #3b82f6, #06b6d4); }
.stat-card.green::before { background: linear-gradient(90deg, #10b981, #34d399); }
.stat-card.amber::before { background: linear-gradient(90deg, #f59e0b, #fbbf24); }
.stat-card.purple::before{ background: linear-gradient(90deg, #8b5cf6, #a78bfa); }

.stat-icon {
    width: 44px; height: 44px;
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 20px;
    margin-bottom: 1rem;
}

.stat-icon.blue   { background: #eff6ff; }
.stat-icon.green  { background: #f0fdf4; }
.stat-icon.amber  { background: #fffbeb; }
.stat-icon.purple { background: #f5f3ff; }

.stat-value {
    font-size: 32px;
    font-weight: 800;
    color: #0f172a;
    line-height: 1;
    margin-bottom: 0.25rem;
}

.stat-label {
    font-size: 13px;
    color: #64748b;
    font-weight: 500;
}

/* ── CONTENT GRID ── */
.content-grid {
    display: grid;
    grid-template-columns: 1fr 340px;
    gap: 1.5rem;
}

.panel {
    background: #fff;
    border-radius: 16px;
    border: 1px solid #e2e8f0;
    overflow: hidden;
}

.panel-header {
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid #f1f5f9;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.panel-title {
    font-size: 15px;
    font-weight: 700;
    color: #0f172a;
    display: flex;
    align-items: center;
    gap: 8px;
}

.panel-badge {
    background: #eff6ff;
    color: #3b82f6;
    font-size: 11px;
    font-weight: 700;
    border-radius: 20px;
    padding: 2px 10px;
}

.panel-body { padding: 1.25rem 1.5rem; }

/* ── APPOINTMENT CARD ── */
.appt-item {
    display: flex;
    gap: 1rem;
    padding: 1rem 0;
    border-bottom: 1px solid #f1f5f9;
    align-items: flex-start;
    transition: all 0.2s;
}

.appt-item:last-child { border-bottom: none; }

.appt-time-col {
    text-align: center;
    min-width: 58px;
    background: #f8fafc;
    border-radius: 10px;
    padding: 0.6rem 0.5rem;
    border: 1px solid #e2e8f0;
}

.appt-time-val {
    font-size: 14px;
    font-weight: 800;
    color: #3b82f6;
    line-height: 1.1;
}

.appt-time-label {
    font-size: 9px;
    color: #94a3b8;
    text-transform: uppercase;
    font-weight: 600;
    letter-spacing: 0.04em;
}

.appt-body { flex: 1; }

.appt-name {
    font-size: 14px;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 3px;
}

.appt-meta {
    font-size: 12px;
    color: #64748b;
    margin-bottom: 6px;
}

.appt-reason {
    font-size: 12px;
    color: #475569;
    background: #f8fafc;
    border-radius: 7px;
    padding: 5px 9px;
    border-left: 3px solid #3b82f6;
    margin-bottom: 8px;
    line-height: 1.5;
}

.appt-actions { display: flex; gap: 6px; flex-wrap: wrap; }

.badge {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
}

.badge-pending   { background: #fef9c3; color: #a16207; }
.badge-confirmed { background: #dcfce7; color: #15803d; }
.badge-completed { background: #eff6ff; color: #1d4ed8; }
.badge-cancelled { background: #fee2e2; color: #b91c1c; }

.btn-sm {
    padding: 5px 12px;
    border-radius: 7px;
    font-size: 11px;
    font-weight: 700;
    cursor: pointer;
    border: none;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    transition: all 0.2s;
}

.btn-confirm  { background: #10b981; color: #fff; }
.btn-confirm:hover { background: #059669; }
.btn-note     { background: #f59e0b; color: #fff; }
.btn-note:hover { background: #d97706; }
.btn-blue     { background: #3b82f6; color: #fff; }
.btn-blue:hover { background: #2563eb; }

.empty-state {
    text-align: center;
    padding: 3rem 1.5rem;
}
.empty-icon { font-size: 52px; margin-bottom: 1rem; }
.empty-title { font-size: 16px; font-weight: 700; color: #374151; margin-bottom: 6px; }
.empty-sub   { font-size: 13px; color: #9ca3af; }

/* Quick links */
.quick-link {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 0.9rem 1rem;
    border-radius: 12px;
    text-decoration: none;
    font-size: 13.5px;
    font-weight: 600;
    color: #374151;
    border: 1.5px solid #e2e8f0;
    background: #f8fafc;
    transition: all 0.2s;
    margin-bottom: 0.6rem;
}

.quick-link:hover {
    border-color: #3b82f6;
    background: #eff6ff;
    color: #2563eb;
    transform: translateX(3px);
}

.quick-link-icon {
    width: 34px; height: 34px;
    border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
    font-size: 16px;
    flex-shrink: 0;
}
</style>

<div class="doc-layout">

    {{-- ── SIDEBAR ── --}}
    <aside class="doc-sidebar">
        <div class="sidebar-brand">
            <a href="{{ route('dashboard') }}" class="sidebar-brand-logo">
                <img src="{{ asset('images/physiocare_logo_premium.png') }}" alt="Logo" style="width: 38px; height: 38px; border-radius: 10px; object-fit: cover;">
                <div>
                    <div class="sidebar-brand-name">PhysioCare</div>
                    <div class="sidebar-brand-sub">Doctor Portal</div>
                </div>
            </a>
        </div>

        <div class="sidebar-doctor-card">
            <div class="doctor-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
            <div class="doctor-card-info">
                <div class="doctor-name">Dr. {{ Auth::user()->name }}</div>
                <div class="doctor-role">Physiotherapist</div>
            </div>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section-label">Main</div>
            <a href="{{ route('dashboard') }}" class="nav-item active">
                <span class="nav-item-icon">🏠</span> Dashboard
            </a>

            <div class="nav-section-label">Management</div>
            <a href="{{ route('admin.patients.index') }}" class="nav-item">
                <span class="nav-item-icon">👥</span> Patients
            </a>
            <a href="{{ route('admin.treatment-calendar') }}" class="nav-item">
                <span class="nav-item-icon">📅</span> Treatment Calendar
            </a>
            <a href="{{ route('doctor.mi-schedule') }}" class="nav-item">
                <span class="nav-item-icon">🕒</span> My Schedule
            </a>

            <div class="nav-section-label">Account</div>
            <a href="{{ route('profile.edit') }}" class="nav-item">
                <span class="nav-item-icon">👤</span> My Profile
            </a>
        </nav>

        <div class="sidebar-footer">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" style="width:100%; background:rgba(239,68,68,0.1); color:#f87171; border:none; border-radius:9px; padding:0.7rem 1rem; font-size:13.5px; font-weight:600; cursor:pointer; display:flex; align-items:center; gap:8px; transition:all 0.2s;"
                    onmouseover="this.style.background='rgba(239,68,68,0.2)'"
                    onmouseout="this.style.background='rgba(239,68,68,0.1)'">
                    🚪 Log Out
                </button>
            </form>
        </div>
    </aside>

    {{-- ── MAIN ── --}}
    <main class="doc-main">

        {{-- Top Bar --}}
        <div class="doc-topbar">
            <div>
                <div class="topbar-title">Welcome back, Dr. {{ Auth::user()->name }} 👋</div>
                <div class="topbar-date">{{ now()->format('l, F j, Y') }}</div>
            </div>
            <div class="topbar-actions">
                <span class="topbar-badge">🔥 {{ $citasHoy->count() }} Appts Today</span>
                <a href="{{ route('doctor.appointments-hoy') }}" class="btn-sm btn-blue">View Today</a>
            </div>
        </div>

        <div class="doc-content">

            {{-- Stats Row --}}
            <div class="stats-row">
                <div class="stat-card blue">
                    <div class="stat-icon blue">📅</div>
                    <div class="stat-value">{{ $citasHoy->count() }}</div>
                    <div class="stat-label">Today's Appointments</div>
                </div>
                <div class="stat-card amber">
                    <div class="stat-icon amber">⏳</div>
                    <div class="stat-value">{{ $cantidadPendientes }}</div>
                    <div class="stat-label">Pending Appointments</div>
                </div>
                <div class="stat-card green">
                    <div class="stat-icon green">👥</div>
                    <div class="stat-value">{{ $totalPacientesMes }}</div>
                    <div class="stat-label">Patients This Month</div>
                </div>
                <div class="stat-card purple">
                    <div class="stat-icon purple">✅</div>
                    <div class="stat-value">{{ $stats['citas_proximas'] ?? 0 }}</div>
                    <div class="stat-label">Upcoming Appointments</div>
                </div>
            </div>

            {{-- Content Grid --}}
            <div class="content-grid">

                {{-- Today's Appointments --}}
                <div class="panel">
                    <div class="panel-header">
                        <div class="panel-title">
                            🔥 Today's Schedule
                            <span class="panel-badge">{{ $citasHoy->count() }} appts</span>
                        </div>
                        <a href="{{ route('doctor.appointments-hoy') }}" class="btn-sm btn-blue">View All</a>
                    </div>
                    <div class="panel-body">
                        @if($citasHoy->count() > 0)
                            @foreach($citasHoy->take(5) as $appt)
                                <div class="appt-item">
                                    <div class="appt-time-col">
                                        <div class="appt-time-val">{{ substr($appt->hora_cita, 0, 5) }}</div>
                                        <div class="appt-time-label">Time</div>
                                    </div>
                                    <div class="appt-body">
                                        <div class="appt-name">{{ $appt->names }} {{ $appt->last_names }}</div>
                                        <div class="appt-meta">📞 {{ $appt->phone }} &nbsp;|&nbsp; 🏥 {{ $appt->specialty->name }}</div>
                                        <div class="appt-reason">{{ Str::limit($appt->reason, 80) }}</div>
                                        <div class="appt-actions">
                                            @php
                                                $status = strtolower($appt->status);
                                                $badgeClass = match($status) {
                                                    'confirmed','confirmada' => 'badge-confirmed',
                                                    'completed','completada' => 'badge-completed',
                                                    'cancelled','cancelada'  => 'badge-cancelled',
                                                    default                  => 'badge-pending',
                                                };
                                            @endphp
                                            <span class="badge {{ $badgeClass }}">{{ ucfirst($appt->status) }}</span>
                                            @if(!in_array($status, ['confirmada','confirmed']))
                                                <form action="{{ route('doctor.confirmar-appointment', $appt->id) }}" method="POST" style="display:inline">
                                                    @csrf
                                                    <button class="btn-sm btn-confirm">✓ Confirm</button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="empty-state">
                                <div class="empty-icon">😎</div>
                                <div class="empty-title">No appointments today</div>
                                <div class="empty-sub">Enjoy your free day!</div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Right Column --}}
                <div>

                    {{-- Recent Clinical Histories --}}
                    <div class="panel">
                        <div class="panel-header">
                            <div class="panel-title">📋 Recent Histories</div>
                        </div>
                        <div class="panel-body">
                            @forelse($historialesRecientes ?? [] as $h)
                                <div style="padding: 0.75rem 0; border-bottom: 1px solid #f1f5f9;">
                                    <div style="font-size:13px; font-weight:600; color:#0f172a;">
                                        {{ $h->patient->name ?? '—' }} {{ $h->patient->last_name ?? '' }}
                                    </div>
                                    <div style="font-size:12px; color:#64748b; margin-top:2px;">
                                        {{ Str::limit($h->diagnostico ?? '—', 55) }}
                                    </div>
                                    <div style="font-size:11px; color:#94a3b8; margin-top:3px;">
                                        {{ $h->created_at->format('d M Y') }}
                                    </div>
                                </div>
                            @empty
                                <div style="text-align:center; padding:1.5rem; color:#9ca3af; font-size:13px;">
                                    No recent histories yet.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

        </div>{{-- /doc-content --}}
    </main>
</div>

@endsection
