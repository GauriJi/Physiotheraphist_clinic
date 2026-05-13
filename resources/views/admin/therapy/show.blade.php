@extends('admin.layouts.sidebar')
@section('title', $plan->plan_name . ' — Treatment Calendar')
@section('page-title', 'Treatment Calendar')
@section('breadcrumb', 'Admin / Patients / ' . $plan->patient->full_name . ' / Treatment Calendar')

@push('styles')
<style>
/* ── DASHBOARD STATS ── */
.ts-stats { display:grid; grid-template-columns:repeat(auto-fill,minmax(150px,1fr)); gap:1rem; margin-bottom:1.5rem; }
.ts-stat { background:#fff; border-radius:14px; border:1px solid #e2e8f0; padding:1.1rem 1.2rem; position:relative; overflow:hidden; }
.ts-stat::before { content:''; position:absolute; top:0; left:0; right:0; height:3px; }
.ts-stat.blue::before   { background:linear-gradient(90deg,#3b82f6,#06b6d4); }
.ts-stat.green::before  { background:linear-gradient(90deg,#10b981,#34d399); }
.ts-stat.red::before    { background:linear-gradient(90deg,#ef4444,#f87171); }
.ts-stat.amber::before  { background:linear-gradient(90deg,#f59e0b,#fbbf24); }
.ts-stat.purple::before { background:linear-gradient(90deg,#8b5cf6,#a78bfa); }
.ts-stat.teal::before   { background:linear-gradient(90deg,#0ea5e9,#38bdf8); }
.ts-stat-val   { font-size:26px; font-weight:900; color:#0f172a; line-height:1; }
.ts-stat-label { font-size:11.5px; color:#64748b; margin-top:3px; }
.ts-stat-icon  { font-size:18px; margin-bottom:.5rem; }

/* ── PROGRESS BAR ── */
.prog-wrap { background:#fff; border-radius:14px; border:1px solid #e2e8f0; padding:1.2rem 1.5rem; margin-bottom:1.5rem; }
.prog-bar-track { background:#e2e8f0; border-radius:999px; height:12px; overflow:hidden; margin:.6rem 0 .4rem; }
.prog-bar-fill  { height:100%; border-radius:999px; background:linear-gradient(90deg,#3b82f6,#6366f1); transition:width .6s ease; }

/* ── PLAN INFO BANNER ── */
.plan-banner {
    background:linear-gradient(135deg,#0f172a,#1e3a5f);
    border-radius:16px; padding:1.5rem 1.75rem; margin-bottom:1.5rem; color:#fff;
    display:flex; gap:1.5rem; flex-wrap:wrap; align-items:flex-start;
}
.plan-banner-title { font-size:20px; font-weight:800; margin-bottom:.3rem; }
.plan-banner-sub   { font-size:12.5px; color:#94a3b8; }
.plan-badge { display:inline-block; padding:3px 12px; border-radius:20px; font-size:11px; font-weight:700; text-transform:uppercase; }
.pb-active    { background:rgba(16,185,129,.2); color:#6ee7b7; border:1px solid rgba(16,185,129,.3); }
.pb-completed { background:rgba(99,102,241,.2); color:#a5b4fc; border:1px solid rgba(99,102,241,.3); }
.pb-cancelled { background:rgba(239,68,68,.2);  color:#fca5a5; border:1px solid rgba(239,68,68,.3); }

/* ── CALENDAR ── */
.cal-nav { display:flex; align-items:center; justify-content:space-between; margin-bottom:1rem; }
.cal-nav h3 { font-size:15px; font-weight:700; color:#0f172a; }
.cal-nav-btn { background:#f1f5f9; border:1px solid #e2e8f0; border-radius:8px; padding:5px 14px; cursor:pointer; font-size:13px; font-weight:600; color:#374151; transition:all .2s; }
.cal-nav-btn:hover { background:#e2e8f0; }

.cal-grid { display:grid; grid-template-columns:repeat(7,1fr); gap:4px; }
.cal-day-header { font-size:11px; font-weight:700; color:#94a3b8; text-align:center; padding:.4rem 0; text-transform:uppercase; letter-spacing:.05em; }
.cal-day {
    background:#f8fafc; border:1px solid #f1f5f9; border-radius:10px;
    min-height:64px; padding:6px 8px; position:relative; cursor:default;
    transition:all .2s;
}
.cal-day.has-session { cursor:pointer; }
.cal-day.has-session:hover { transform:translateY(-2px); box-shadow:0 4px 12px rgba(0,0,0,.1); }
.cal-day.other-month { opacity:.35; }
.cal-day.today { border-color:#3b82f6; background:#eff6ff; }
.cal-day-num { font-size:12px; font-weight:700; color:#374151; margin-bottom:4px; }
.cal-day.today .cal-day-num { color:#3b82f6; }
.cal-pip {
    width:100%; padding:2px 5px; border-radius:5px; font-size:10.5px;
    font-weight:700; text-align:center; margin-top:2px; white-space:nowrap; overflow:hidden;
}
.pip-upcoming    { background:#fef9c3; color:#92400e; border:1px solid #fde68a; }
.pip-completed   { background:#dcfce7; color:#166534; border:1px solid #bbf7d0; }
.pip-missed      { background:#fee2e2; color:#991b1b; border:1px solid #fecaca; }
.pip-rescheduled { background:#dbeafe; color:#1e40af; border:1px solid #bfdbfe; }
.pip-cancelled   { background:#f3f4f6; color:#4b5563; border:1px solid #e5e7eb; }

/* ── LEGEND ── */
.legend { display:flex; gap:1rem; flex-wrap:wrap; align-items:center; margin-bottom:1.25rem; }
.legend-item { display:flex; align-items:center; gap:5px; font-size:12px; font-weight:600; color:#374151; }
.legend-dot  { width:12px; height:12px; border-radius:3px; }

/* ── SESSION TABLE ── */
.session-status-btn {
    background:none; border:1px solid #e2e8f0; border-radius:7px;
    padding:4px 10px; font-size:11.5px; font-weight:600; cursor:pointer;
    transition:all .2s; color:#374151;
}
.session-status-btn:hover { background:#f1f5f9; }

/* ── MODAL ── */
.modal-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,.55); z-index:600; align-items:center; justify-content:center; }
.modal-overlay.open { display:flex; }
.modal-box { background:#fff; border-radius:16px; padding:2rem; max-width:500px; width:95%; max-height:90vh; overflow-y:auto; box-shadow:0 25px 60px rgba(0,0,0,.2); }
.modal-title { font-size:17px; font-weight:800; color:#0f172a; margin-bottom:1.25rem; display:flex; align-items:center; gap:.5rem; }

/* ── TABS ── */
.cal-tabs { display:flex; gap:4px; background:#f1f5f9; border-radius:10px; padding:4px; margin-bottom:1.25rem; }
.cal-tab { flex:1; padding:.5rem; border:none; background:none; border-radius:8px; font-size:13px; font-weight:600; color:#64748b; cursor:pointer; transition:all .2s; }
.cal-tab.active { background:#fff; color:#0f172a; box-shadow:0 1px 4px rgba(0,0,0,.1); }
.cal-panel { display:none; }
.cal-panel.active { display:block; }
</style>
@endpush

@section('content')

{{-- PLAN BANNER --}}
<div class="plan-banner">
    <div style="flex:1;">
        <div style="display:flex;align-items:center;gap:.75rem;margin-bottom:.4rem;flex-wrap:wrap;">
            <div class="plan-banner-title">{{ $plan->plan_name }}</div>
            <span class="plan-badge pb-{{ $plan->status }}">{{ ucfirst($plan->status) }}</span>
        </div>
        <div class="plan-banner-sub" style="margin-bottom:.5rem;">
            Patient: <strong style="color:#e2e8f0;">{{ $plan->patient->full_name }}</strong>
            @if($plan->patient->patient_uid)
                &nbsp;·&nbsp; {{ $plan->patient->patient_uid }}
            @endif
            @if($plan->physiotherapist)
                &nbsp;·&nbsp; Dr. {{ $plan->physiotherapist->name }} {{ $plan->physiotherapist->last_name }}
            @endif
        </div>
        @if($plan->diagnosis)
            <div class="plan-banner-sub">Diagnosis: <strong style="color:#cbd5e1;">{{ $plan->diagnosis }}</strong></div>
        @endif
        @if($plan->goal)
            <div style="margin-top:.5rem;background:rgba(255,255,255,.07);border-radius:8px;padding:.5rem .85rem;font-size:12.5px;color:#93c5fd;">
                🎯 {{ $plan->goal }}
            </div>
        @endif
    </div>
    <div style="display:flex;flex-direction:column;gap:.5rem;align-items:flex-end;flex-shrink:0;">
        <a href="{{ route('admin.therapy.create', $plan->patient) }}" class="btn btn-primary btn-sm">➕ New Plan</a>
        <a href="{{ route('admin.therapy.index', $plan->patient) }}" class="btn btn-ghost btn-sm">📋 All Plans</a>
        <a href="{{ route('admin.patients.show', $plan->patient) }}" class="btn btn-ghost btn-sm">← Back to Patient</a>
        <form action="{{ route('admin.therapy.destroy', $plan) }}" method="POST"
              onsubmit="return confirm('Delete this entire therapy plan and all its sessions?')">
            @csrf @method('DELETE')
            <button class="btn btn-danger btn-sm">🗑 Delete Plan</button>
        </form>
    </div>
</div>

{{-- STATS STRIP --}}
<div class="ts-stats">
    <div class="ts-stat blue">
        <div class="ts-stat-icon">📋</div>
        <div class="ts-stat-val">{{ $stats['total'] }}</div>
        <div class="ts-stat-label">Total Sessions</div>
    </div>
    <div class="ts-stat green">
        <div class="ts-stat-icon">✅</div>
        <div class="ts-stat-val">{{ $stats['completed'] }}</div>
        <div class="ts-stat-label">Completed</div>
    </div>
    <div class="ts-stat amber">
        <div class="ts-stat-icon">⏰</div>
        <div class="ts-stat-val">{{ $stats['upcoming'] }}</div>
        <div class="ts-stat-label">Upcoming</div>
    </div>
    <div class="ts-stat red">
        <div class="ts-stat-icon">❌</div>
        <div class="ts-stat-val">{{ $stats['missed'] }}</div>
        <div class="ts-stat-label">Missed</div>
    </div>
    <div class="ts-stat" style="--c:#3b82f6;" class="teal">
        <div class="ts-stat-icon" style="color:#3b82f6;">🔄</div>
        <div class="ts-stat-val" style="color:#1d4ed8;">{{ $stats['rescheduled'] }}</div>
        <div class="ts-stat-label">Rescheduled</div>
    </div>
    <div class="ts-stat purple">
        <div class="ts-stat-icon">🏁</div>
        <div class="ts-stat-val">{{ $stats['remaining'] }}</div>
        <div class="ts-stat-label">Remaining</div>
    </div>
</div>

{{-- PROGRESS BAR --}}
<div class="prog-wrap">
    <div style="display:flex;justify-content:space-between;align-items:center;">
        <span style="font-size:13px;font-weight:700;color:#374151;">🏃 Therapy Progress</span>
        <span style="font-size:14px;font-weight:800;color:#3b82f6;">{{ $stats['progress'] }}% Complete</span>
    </div>
    <div class="prog-bar-track">
        <div class="prog-bar-fill" style="width:{{ $stats['progress'] }}%;"></div>
    </div>
    <div style="display:flex;justify-content:space-between;font-size:11.5px;color:#94a3b8;">
        <span>{{ $stats['completed'] }} of {{ $stats['total'] }} sessions completed</span>
        @if($stats['next'])
        <span>Next: <strong style="color:#374151;">{{ $stats['next']->scheduled_date->format('d M Y') }}</strong>
            @if($plan->session_time) at {{ \Carbon\Carbon::parse($plan->session_time)->format('h:i A') }} @endif
        </span>
        @endif
    </div>
</div>

{{-- TABS: Calendar / Session List --}}
<div class="cal-tabs" id="mainTabs">
    <button class="cal-tab active" data-panel="calendar">📅 Treatment Calendar</button>
    <button class="cal-tab" data-panel="sessions">📋 Session History</button>
</div>

{{-- ══ CALENDAR PANEL ══════════════════════════════════════════════════════════════ --}}
<div class="cal-panel active" id="panel-calendar">
    <div class="card">
        <div class="card-body">

            {{-- Legend --}}
            <div class="legend">
                <div class="legend-item"><div class="legend-dot" style="background:#fef9c3;border:1.5px solid #fde68a;"></div> Upcoming</div>
                <div class="legend-item"><div class="legend-dot" style="background:#dcfce7;border:1.5px solid #bbf7d0;"></div> Completed</div>
                <div class="legend-item"><div class="legend-dot" style="background:#fee2e2;border:1.5px solid #fecaca;"></div> Missed</div>
                <div class="legend-item"><div class="legend-dot" style="background:#dbeafe;border:1.5px solid #bfdbfe;"></div> Rescheduled</div>
                <div class="legend-item"><div class="legend-dot" style="background:#f3f4f6;border:1.5px solid #e5e7eb;"></div> Cancelled</div>
            </div>

            {{-- Calendar months --}}
            @php
                use Carbon\Carbon;
                $today = Carbon::today();
                $sessionsByDate = $plan->sessions->keyBy(fn($s) => $s->scheduled_date->format('Y-m-d'));
                $calStart = $plan->start_date->copy()->startOfMonth();
                $calEnd   = ($plan->end_date ?? $plan->start_date)->copy()->endOfMonth();
                $cursor   = $calStart->copy();
            @endphp

            @while($cursor->lte($calEnd))
            @php
                $monthLabel = $cursor->format('F Y');
                $monthStart = $cursor->copy()->startOfMonth();
                $monthEnd   = $cursor->copy()->endOfMonth();
                $startDow   = $monthStart->dayOfWeek; // 0=Sun
            @endphp

            <div style="margin-bottom:2rem;">
                <div class="cal-nav">
                    <h3>{{ $monthLabel }}</h3>
                    <div style="font-size:12px;color:#94a3b8;">
                        @php
                            $mSessions = $plan->sessions->filter(fn($s) => $s->scheduled_date->month == $cursor->month && $s->scheduled_date->year == $cursor->year);
                        @endphp
                        {{ $mSessions->count() }} session{{ $mSessions->count() != 1 ? 's' : '' }} this month
                    </div>
                </div>

                <div class="cal-grid">
                    @foreach(['Sun','Mon','Tue','Wed','Thu','Fri','Sat'] as $h)
                        <div class="cal-day-header">{{ $h }}</div>
                    @endforeach

                    {{-- Empty cells before month start --}}
                    @for($e = 0; $e < $startDow; $e++)
                        <div class="cal-day other-month"></div>
                    @endfor

                    {{-- Days in month --}}
                    @for($d = 1; $d <= $monthEnd->day; $d++)
                        @php
                            $dateKey = $cursor->year . '-' . str_pad($cursor->month,2,'0',STR_PAD_LEFT) . '-' . str_pad($d,2,'0',STR_PAD_LEFT);
                            $sess    = $sessionsByDate[$dateKey] ?? null;
                            $isToday = ($dateKey === $today->format('Y-m-d'));
                        @endphp
                        <div class="cal-day {{ $isToday ? 'today' : '' }} {{ $sess ? 'has-session' : '' }}"
                             {{ $sess ? 'onclick="openModal(' . $sess->id . ')"' : '' }}>
                            <div class="cal-day-num">{{ $d }}</div>
                            @if($sess)
                                <div class="cal-pip pip-{{ $sess->status }}">
                                    {{ $sess->status_badge['emoji'] }} #{{ $sess->session_number }}
                                </div>
                            @endif
                        </div>
                    @endfor

                    {{-- Trailing empty cells --}}
                    @php $filled = $startDow + $monthEnd->day; $trail = (7 - ($filled % 7)) % 7; @endphp
                    @for($t = 0; $t < $trail; $t++)
                        <div class="cal-day other-month"></div>
                    @endfor
                </div>
            </div>

            @php $cursor->addMonth(); @endphp
            @endwhile

        </div>
    </div>
</div>

{{-- ══ SESSION LIST PANEL ══════════════════════════════════════════════════════════ --}}
<div class="cal-panel" id="panel-sessions">
    <div class="card">
        <div style="overflow-x:auto;">
            <table class="tbl">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Scheduled Date</th>
                        <th>Time</th>
                        <th>Status</th>
                        <th>Actual Date</th>
                        <th>Rescheduled From</th>
                        <th>Therapist Notes</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($plan->sessions as $sess)
                    @php $badge = $sess->status_badge; @endphp
                    <tr>
                        <td style="font-weight:700;color:#64748b;font-size:12px;">{{ $sess->session_number }}</td>
                        <td style="font-weight:600;">
                            {{ $sess->scheduled_date->format('d M Y') }}
                            <div style="font-size:11px;color:#94a3b8;">{{ $sess->scheduled_date->format('D') }}</div>
                        </td>
                        <td style="font-size:13px;color:#64748b;">
                            {{ $sess->scheduled_time ? \Carbon\Carbon::parse($sess->scheduled_time)->format('h:i A') : '—' }}
                        </td>
                        <td>
                            <span style="display:inline-flex;align-items:center;gap:4px;padding:4px 10px;border-radius:20px;font-size:11.5px;font-weight:700;background:{{ $badge['bg'] }};color:{{ $badge['color'] }};border:1px solid {{ $badge['border'] }};">
                                {{ $badge['emoji'] }} {{ $badge['label'] }}
                            </span>
                        </td>
                        <td style="font-size:13px;color:#64748b;">
                            {{ $sess->actual_date ? $sess->actual_date->format('d M Y') : '—' }}
                        </td>
                        <td style="font-size:12px;color:#94a3b8;">
                            {{ $sess->original_date ? $sess->original_date->format('d M Y') : '—' }}
                        </td>
                        <td style="font-size:12.5px;color:#374151;max-width:180px;">
                            {{ $sess->therapist_notes ? \Illuminate\Support\Str::limit($sess->therapist_notes, 50) : '—' }}
                        </td>
                        <td>
                            <div style="display:flex;gap:.3rem;flex-wrap:wrap;">
                                @if($sess->status !== 'cancelled')
                                <button class="session-status-btn" onclick="openModal({{ $sess->id }})">
                                    ✏️ Update
                                </button>
                                @endif
                                @if(in_array($sess->status, ['completed','missed','rescheduled','cancelled']))
                                <form action="{{ route('admin.therapy.session.revert', $sess) }}" method="POST"
                                      onsubmit="return confirm('Revert this session back to Upcoming?')">
                                    @csrf @method('PATCH')
                                    <button class="session-status-btn" style="color:#6b7280;">↩ Revert</button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ══ SESSION UPDATE MODAL ════════════════════════════════════════════════════════ --}}
<div class="modal-overlay" id="sessionModal">
    <div class="modal-box">
        <div class="modal-title" id="modalTitle">📝 Update Session</div>

        <form action="" method="POST" id="sessionUpdateForm">
            @csrf @method('PATCH')

            <div style="margin-bottom:1rem;">
                <div style="font-size:12px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;margin-bottom:.6rem;">Update Status To</div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:.5rem;">
                    <label style="display:flex;align-items:center;gap:.5rem;cursor:pointer;background:#f0fdf4;border:2px solid #bbf7d0;border-radius:9px;padding:.6rem .9rem;font-size:13px;font-weight:600;color:#15803d;">
                        <input type="radio" name="status" value="completed" required style="accent-color:#10b981;"> ✅ Completed
                    </label>
                    <label style="display:flex;align-items:center;gap:.5rem;cursor:pointer;background:#fef2f2;border:2px solid #fecaca;border-radius:9px;padding:.6rem .9rem;font-size:13px;font-weight:600;color:#b91c1c;">
                        <input type="radio" name="status" value="missed" style="accent-color:#ef4444;"> ❌ Missed
                    </label>
                    <label style="display:flex;align-items:center;gap:.5rem;cursor:pointer;background:#eff6ff;border:2px solid #bfdbfe;border-radius:9px;padding:.6rem .9rem;font-size:13px;font-weight:600;color:#1d4ed8;">
                        <input type="radio" name="status" value="rescheduled" style="accent-color:#3b82f6;"> 🔄 Rescheduled
                    </label>
                    <label style="display:flex;align-items:center;gap:.5rem;cursor:pointer;background:#f9fafb;border:2px solid #e5e7eb;border-radius:9px;padding:.6rem .9rem;font-size:13px;font-weight:600;color:#4b5563;">
                        <input type="radio" name="status" value="cancelled" style="accent-color:#6b7280;"> 🚫 Cancelled
                    </label>
                </div>
            </div>

            <div id="rescheduleField" style="display:none;" class="form-group">
                <label class="form-label">New Date *</label>
                <input type="date" name="reschedule_date" class="form-control"
                       min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                <span class="form-text">The session will be moved to this date</span>
            </div>

            <div class="form-group">
                <label class="form-label">Therapist Notes</label>
                <textarea name="therapist_notes" class="form-control" rows="3"
                          placeholder="Treatment given, observations, patient feedback…"></textarea>
            </div>

            <div style="display:flex;gap:.5rem;justify-content:flex-end;margin-top:1.25rem;">
                <button type="button" onclick="closeModal()" class="btn btn-ghost">Cancel</button>
                <button type="submit" class="btn btn-primary">💾 Save</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
// ── Session data (keyed by id) ─────────────────────────────────────────────────
const sessions = {
    @foreach($plan->sessions as $sess)
    {{ $sess->id }}: {
        id:      {{ $sess->id }},
        num:     {{ $sess->session_number }},
        date:    "{{ $sess->scheduled_date->format('d M Y') }}",
        status:  "{{ $sess->status }}",
        time:    "{{ $sess->scheduled_time ? \Carbon\Carbon::parse($sess->scheduled_time)->format('h:i A') : '' }}",
        url:     "{{ route('admin.therapy.session.update', $sess) }}",
    },
    @endforeach
};

function openModal(id) {
    const s = sessions[id];
    if (!s) return;
    document.getElementById('modalTitle').textContent = `✏️ Session #${s.num} — ${s.date}${s.time ? ' at ' + s.time : ''}`;
    document.getElementById('sessionUpdateForm').action = s.url;
    // Reset form
    document.querySelectorAll('#sessionUpdateForm input[name=status]').forEach(r => r.checked = false);
    document.querySelector('textarea[name=therapist_notes]').value = '';
    document.getElementById('rescheduleField').style.display = 'none';
    // Pre-select current status
    const radio = document.querySelector(`input[name=status][value="${s.status}"]`);
    if (radio) radio.checked = true;
    document.getElementById('sessionModal').classList.add('open');
}

function closeModal() {
    document.getElementById('sessionModal').classList.remove('open');
}

// Show reschedule date field only when "rescheduled" is selected
document.querySelectorAll('input[name=status]').forEach(r => {
    r.addEventListener('change', () => {
        document.getElementById('rescheduleField').style.display =
            document.querySelector('input[name=status]:checked')?.value === 'rescheduled' ? 'block' : 'none';
    });
});

// Close modal on backdrop click
document.getElementById('sessionModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});

// ── Tab switching ─────────────────────────────────────────────────────────────
document.querySelectorAll('.cal-tab').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.cal-tab').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.cal-panel').forEach(p => p.classList.remove('active'));
        btn.classList.add('active');
        document.getElementById('panel-' + btn.dataset.panel).classList.add('active');
    });
});
</script>
@endpush
