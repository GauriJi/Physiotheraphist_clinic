@extends('admin.layouts.sidebar')
@section('title', 'Treatment Calendar — PhysioCare')
@section('page-title', 'Treatment Calendar')
@section('breadcrumb', 'Admin / Treatment Calendar')

@push('styles')
<style>
/* ── HERO ── */
.tc-hero {
    background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 60%, #0c2340 100%);
    border-radius: 16px; padding: 1.75rem 2rem; margin-bottom: 1.5rem;
    display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;
    position: relative; overflow: hidden;
}
.tc-hero::before {
    content: '📅'; position: absolute; right: 1.5rem; top: 50%;
    transform: translateY(-50%); font-size: 90px; opacity: .06;
}
.tc-hero h1  { font-size: 22px; font-weight: 800; color: #fff; margin-bottom: .25rem; }
.tc-hero p   { font-size: 13px; color: #94a3b8; }
.tc-hero-date { font-size: 28px; font-weight: 900; color: #60a5fa; }
.tc-hero-sub  { font-size: 12px; color: #94a3b8; }

/* ── STATS ── */
.tc-stats { display: grid; grid-template-columns: repeat(auto-fill, minmax(155px, 1fr)); gap: 1rem; margin-bottom: 1.5rem; }
.tc-stat { background: #fff; border-radius: 14px; border: 1px solid #e2e8f0; padding: 1.1rem 1.2rem; position: relative; overflow: hidden; }
.tc-stat::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; }
.tc-stat.blue::before   { background: linear-gradient(90deg, #3b82f6, #06b6d4); }
.tc-stat.green::before  { background: linear-gradient(90deg, #10b981, #34d399); }
.tc-stat.amber::before  { background: linear-gradient(90deg, #f59e0b, #fbbf24); }
.tc-stat.red::before    { background: linear-gradient(90deg, #ef4444, #f87171); }
.tc-stat.purple::before { background: linear-gradient(90deg, #8b5cf6, #a78bfa); }
.tc-stat.teal::before   { background: linear-gradient(90deg, #0ea5e9, #38bdf8); }
.tc-stat-icon  { font-size: 20px; margin-bottom: .4rem; }
.tc-stat-val   { font-size: 28px; font-weight: 900; color: #0f172a; line-height: 1; }
.tc-stat-label { font-size: 11.5px; color: #64748b; margin-top: 3px; }

/* ── CALENDAR GRID ── */
.month-nav { display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem; }
.month-nav h3 { font-size: 16px; font-weight: 800; color: #0f172a; flex: 1; }
.month-nav-btn { background: #f1f5f9; border: 1px solid #e2e8f0; border-radius: 8px; padding: 6px 16px; cursor: pointer; font-size: 13px; font-weight: 600; color: #374151; text-decoration: none; transition: all .2s; }
.month-nav-btn:hover { background: #e2e8f0; }

.cal-grid { display: grid; grid-template-columns: repeat(7, 1fr); gap: 5px; }
.cal-hdr { font-size: 11px; font-weight: 700; color: #94a3b8; text-align: center; padding: .4rem 0; text-transform: uppercase; }
.cal-cell {
    background: #f8fafc; border: 1px solid #f1f5f9; border-radius: 10px;
    min-height: 72px; padding: 6px 7px; transition: all .2s; cursor: default;
}
.cal-cell.has-sessions { cursor: pointer; }
.cal-cell.has-sessions:hover { transform: translateY(-2px); box-shadow: 0 4px 14px rgba(0,0,0,.1); border-color: #bfdbfe; }
.cal-cell.other-month { opacity: .3; }
.cal-cell.today { border-color: #3b82f6 !important; background: #eff6ff; }
.cal-cell.today .cal-num { color: #2563eb; font-weight: 900; }
.cal-num { font-size: 12px; font-weight: 600; color: #374151; margin-bottom: 3px; }

.sess-pip {
    font-size: 9.5px; font-weight: 700; border-radius: 4px;
    padding: 1px 4px; margin-top: 2px; white-space: nowrap;
    overflow: hidden; text-overflow: ellipsis;
    display: flex; align-items: center; gap: 3px;
}
.pip-upcoming    { background: #fef9c3; color: #92400e; }
.pip-completed   { background: #dcfce7; color: #166534; }
.pip-missed      { background: #fee2e2; color: #991b1b; }
.pip-rescheduled { background: #dbeafe; color: #1e40af; }
.pip-cancelled   { background: #f3f4f6; color: #6b7280; }

/* ── SESSION ROWS ── */
.sess-row {
    display: flex; align-items: center; gap: .75rem; padding: .8rem 1rem;
    border-bottom: 1px solid #f1f5f9; transition: background .15s;
}
.sess-row:hover { background: #f8fafc; }
.sess-row:last-child { border-bottom: none; }
.sess-avatar {
    width: 36px; height: 36px; border-radius: 50%; object-fit: cover;
    flex-shrink: 0;
}
.sess-name   { font-size: 13.5px; font-weight: 700; color: #0f172a; }
.sess-meta   { font-size: 12px; color: #64748b; }
.sess-time   { font-size: 12px; font-weight: 700; color: #3b82f6; margin-left: auto; white-space: nowrap; }
.status-dot  { width: 9px; height: 9px; border-radius: 50%; flex-shrink: 0; }

/* ── PLAN CARD ── */
.plan-row { display: flex; align-items: center; gap: .75rem; padding: .85rem 1.1rem; border-bottom: 1px solid #f1f5f9; }
.plan-row:hover { background: #f8fafc; }
.plan-row:last-child { border-bottom: none; }
.plan-prog-bar { flex: 1; background: #e2e8f0; border-radius: 999px; height: 6px; overflow: hidden; }
.plan-prog-fill { height: 100%; border-radius: 999px; background: linear-gradient(90deg, #3b82f6, #6366f1); }

/* ── LEGEND ── */
.legend { display: flex; gap: .75rem; flex-wrap: wrap; margin-bottom: 1rem; }
.legend-item { display: flex; align-items: center; gap: 5px; font-size: 11.5px; font-weight: 600; color: #374151; }
.legend-dot  { width: 11px; height: 11px; border-radius: 3px; }

/* ── LAYOUT ── */
.tc-layout { display: grid; grid-template-columns: 1fr 360px; gap: 1.25rem; }
@media (max-width: 1100px) { .tc-layout { grid-template-columns: 1fr; } }
</style>
@endpush

@section('content')

{{-- HERO HEADER --}}
<div class="tc-hero">
    <div>
        <h1>📅 Treatment Calendar</h1>
        <p>Overview of all active therapy plans and scheduled sessions</p>
    </div>
    <div style="text-align:right;">
        <div class="tc-hero-date">{{ $today->format('d M Y') }}</div>
        <div class="tc-hero-sub">{{ $today->format('l') }} · Today</div>
    </div>
</div>

{{-- STATS STRIP --}}
<div class="tc-stats">
    <div class="tc-stat blue">
        <div class="tc-stat-icon">🩺</div>
        <div class="tc-stat-val">{{ $stats['active_plans'] }}</div>
        <div class="tc-stat-label">Active Plans</div>
    </div>
    <div class="tc-stat green">
        <div class="tc-stat-icon">✅</div>
        <div class="tc-stat-val">{{ $stats['completed_today'] }}</div>
        <div class="tc-stat-label">Done Today</div>
    </div>
    <div class="tc-stat amber">
        <div class="tc-stat-icon">⏰</div>
        <div class="tc-stat-val">{{ $stats['upcoming_today'] }}</div>
        <div class="tc-stat-label">Pending Today</div>
    </div>
    <div class="tc-stat teal">
        <div class="tc-stat-icon">📆</div>
        <div class="tc-stat-val">{{ $stats['upcoming_week'] }}</div>
        <div class="tc-stat-label">Next 7 Days</div>
    </div>
    <div class="tc-stat red">
        <div class="tc-stat-icon">❌</div>
        <div class="tc-stat-val">{{ $stats['missed_total'] }}</div>
        <div class="tc-stat-label">Total Missed</div>
    </div>
    <div class="tc-stat purple">
        <div class="tc-stat-icon">📋</div>
        <div class="tc-stat-val">{{ $stats['total_sessions'] }}</div>
        <div class="tc-stat-label">All Sessions</div>
    </div>
</div>

{{-- MAIN LAYOUT --}}
<div class="tc-layout">

    {{-- LEFT: MONTHLY CALENDAR --}}
    <div>
        <div class="card">
            <div class="card-header">
                {{-- Month nav --}}
                <div class="month-nav" style="width:100%;">
                    @php
                        $prevMonth = $monthDt->copy()->subMonth()->format('Y-m');
                        $nextMonth = $monthDt->copy()->addMonth()->format('Y-m');
                    @endphp
                    <a href="{{ route('admin.treatment-calendar') }}?month={{ $prevMonth }}" class="month-nav-btn">‹ Prev</a>
                    <h3 style="text-align:center;">{{ $monthDt->format('F Y') }}</h3>
                    <a href="{{ route('admin.treatment-calendar') }}?month={{ $nextMonth }}" class="month-nav-btn">Next ›</a>
                    <a href="{{ route('admin.treatment-calendar') }}" class="month-nav-btn" style="background:#eff6ff;color:#2563eb;border-color:#bfdbfe;">Today</a>
                </div>
            </div>
            <div class="card-body">
                {{-- Legend --}}
                <div class="legend">
                    <div class="legend-item"><div class="legend-dot" style="background:#fef9c3;border:1.5px solid #fde68a;"></div> Upcoming</div>
                    <div class="legend-item"><div class="legend-dot" style="background:#dcfce7;border:1.5px solid #bbf7d0;"></div> Completed</div>
                    <div class="legend-item"><div class="legend-dot" style="background:#fee2e2;border:1.5px solid #fecaca;"></div> Missed</div>
                    <div class="legend-item"><div class="legend-dot" style="background:#dbeafe;border:1.5px solid #bfdbfe;"></div> Rescheduled</div>
                </div>

                {{-- Calendar --}}
                @php
                    $startDow  = $monthDt->copy()->startOfMonth()->dayOfWeek;
                    $daysInMon = $monthDt->daysInMonth;
                    $todayKey  = $today->format('Y-m-d');
                @endphp
                <div class="cal-grid">
                    @foreach(['Sun','Mon','Tue','Wed','Thu','Fri','Sat'] as $h)
                        <div class="cal-hdr">{{ $h }}</div>
                    @endforeach

                    @for($e = 0; $e < $startDow; $e++)
                        <div class="cal-cell other-month"></div>
                    @endfor

                    @for($d = 1; $d <= $daysInMon; $d++)
                        @php
                            $dk   = $monthDt->format('Y') . '-' . str_pad($monthDt->month,2,'0',STR_PAD_LEFT) . '-' . str_pad($d,2,'0',STR_PAD_LEFT);
                            $daySessions = $monthSessions[$dk] ?? collect();
                            $isToday = $dk === $todayKey;
                        @endphp
                        <div class="cal-cell {{ $isToday ? 'today' : '' }} {{ $daySessions->count() > 0 ? 'has-sessions' : '' }}"
                             @if($daySessions->count() > 0)
                             onclick="showDayPanel('{{ $dk }}', {{ $daySessions->count() }})"
                             title="{{ $daySessions->count() }} session(s) on {{ \Carbon\Carbon::createFromFormat('Y-m-d', $dk)->format('d M') }}"
                             @endif>
                            <div class="cal-num">{{ $d }}</div>
                            @foreach($daySessions->take(3) as $s)
                                <div class="sess-pip pip-{{ $s->status }}">
                                    {{ $s->status_badge['emoji'] }} {{ \Illuminate\Support\Str::limit($s->patient->name, 7) }}
                                </div>
                            @endforeach
                            @if($daySessions->count() > 3)
                                <div class="sess-pip" style="background:#f1f5f9;color:#64748b;">+{{ $daySessions->count() - 3 }} more</div>
                            @endif
                        </div>
                    @endfor

                    @php $filled = $startDow + $daysInMon; $trail = (7 - ($filled % 7)) % 7; @endphp
                    @for($t = 0; $t < $trail; $t++)
                        <div class="cal-cell other-month"></div>
                    @endfor
                </div>
            </div>
        </div>
    </div>

    {{-- RIGHT SIDEBAR --}}
    <div>
        {{-- TODAY'S SESSIONS --}}
        <div class="card" style="margin-bottom:1rem;">
            <div class="card-header">
                <div class="card-title">⚡ Today's Sessions</div>
                <span style="font-size:12px;color:#94a3b8;">{{ $today->format('d M Y') }}</span>
            </div>
            @if($todaysSessions->count() > 0)
                @foreach($todaysSessions as $s)
                <div class="sess-row">
                    <img src="{{ $s->patient->photo_url }}" class="sess-avatar" alt="{{ $s->patient->name }}">
                    <div style="flex:1;min-width:0;">
                        <div class="sess-name">{{ $s->patient->full_name }}</div>
                        <div class="sess-meta">{{ $s->plan->plan_name }} · #{{ $s->session_number }}</div>
                    </div>
                    <div style="text-align:right;">
                        <div class="sess-time">
                            {{ $s->scheduled_time ? \Carbon\Carbon::parse($s->scheduled_time)->format('h:i A') : 'All day' }}
                        </div>
                        <a href="{{ route('admin.therapy.show', $s->plan) }}" style="font-size:11px;color:#3b82f6;">View →</a>
                    </div>
                </div>
                @endforeach
            @else
                <div class="empty-state" style="padding:1.5rem;">
                    <div class="empty-icon">🎉</div>
                    <div class="empty-sub">No sessions scheduled today</div>
                </div>
            @endif
        </div>

        {{-- UPCOMING 14 DAYS --}}
        <div class="card" style="margin-bottom:1rem;">
            <div class="card-header">
                <div class="card-title">📆 Next 14 Days</div>
                <span style="font-size:12px;color:#94a3b8;">{{ $upcomingSessions->count() }} sessions</span>
            </div>
            @if($upcomingSessions->count() > 0)
                @foreach($upcomingSessions->take(8) as $s)
                <div class="sess-row">
                    <div class="status-dot" style="background:{{ $s->calendar_color }};"></div>
                    <div style="flex:1;min-width:0;">
                        <div class="sess-name" style="font-size:13px;">{{ $s->patient->full_name }}</div>
                        <div class="sess-meta">{{ $s->scheduled_date->format('d M') }} · {{ $s->plan->plan_name }}</div>
                    </div>
                    <a href="{{ route('admin.therapy.show', $s->plan) }}" style="font-size:11.5px;color:#3b82f6;white-space:nowrap;">View →</a>
                </div>
                @endforeach
                @if($upcomingSessions->count() > 8)
                    <div style="padding:.6rem 1rem;font-size:12px;color:#94a3b8;text-align:center;">
                        + {{ $upcomingSessions->count() - 8 }} more sessions…
                    </div>
                @endif
            @else
                <div class="empty-state" style="padding:1.5rem;">
                    <div class="empty-icon">📆</div>
                    <div class="empty-sub">No upcoming sessions</div>
                </div>
            @endif
        </div>

        {{-- ACTIVE PLANS --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title">🩺 Active Plans</div>
                <a href="{{ route('admin.patients.index') }}" style="font-size:12px;color:#3b82f6;">All Patients →</a>
            </div>
            @if($activePlans->count() > 0)
                @foreach($activePlans as $pl)
                @php
                    $plC = $pl->sessions->where('status','completed')->count();
                    $plP = $pl->total_sessions > 0 ? round($plC / $pl->total_sessions * 100) : 0;
                @endphp
                <div class="plan-row">
                    <img src="{{ $pl->patient->photo_url }}" class="sess-avatar" alt="{{ $pl->patient->name }}">
                    <div style="flex:1;min-width:0;">
                        <div style="font-size:13px;font-weight:700;color:#0f172a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                            {{ $pl->patient->full_name }}
                        </div>
                        <div style="font-size:11.5px;color:#64748b;margin-bottom:4px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                            {{ $pl->plan_name }}
                        </div>
                        <div class="plan-prog-bar">
                            <div class="plan-prog-fill" style="width:{{ $plP }}%;"></div>
                        </div>
                        <div style="font-size:10.5px;color:#94a3b8;margin-top:2px;">{{ $plC }}/{{ $pl->total_sessions }} sessions · {{ $plP }}%</div>
                    </div>
                    <a href="{{ route('admin.therapy.show', $pl) }}" style="font-size:11.5px;color:#3b82f6;margin-left:.5rem;white-space:nowrap;">📅</a>
                </div>
                @endforeach
            @else
                <div class="empty-state" style="padding:1.5rem;">
                    <div class="empty-icon">🩺</div>
                    <div class="empty-sub">No active therapy plans</div>
                    <a href="{{ route('admin.patients.index') }}" class="btn btn-primary" style="margin-top:.75rem;font-size:12px;">Go to Patients</a>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- DAY DETAIL TOOLTIP/INFO --}}
<div id="dayInfo" style="display:none;position:fixed;bottom:1.5rem;right:1.5rem;background:#0f172a;color:#fff;border-radius:10px;padding:.75rem 1.1rem;font-size:13px;font-weight:600;z-index:999;box-shadow:0 8px 24px rgba(0,0,0,.3);">
    <span id="dayInfoText"></span>
    <button onclick="document.getElementById('dayInfo').style.display='none'" style="background:none;border:none;color:#94a3b8;cursor:pointer;margin-left:.75rem;font-size:15px;">✕</button>
</div>

@endsection

@push('scripts')
<script>
function showDayPanel(dateKey, count) {
    const box  = document.getElementById('dayInfo');
    const text = document.getElementById('dayInfoText');
    const [y, m, d] = dateKey.split('-');
    const date = new Date(y, m-1, d);
    const label = date.toLocaleDateString('en-GB', { weekday:'long', day:'numeric', month:'long' });
    text.textContent = `${label}: ${count} session${count > 1 ? 's' : ''} scheduled`;
    box.style.display = 'flex';
    box.style.alignItems = 'center';
    setTimeout(() => box.style.display = 'none', 4000);
}
</script>
@endpush
