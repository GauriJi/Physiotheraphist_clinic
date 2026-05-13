@extends('admin.layouts.sidebar')
@section('title', 'Create Therapy Plan — ' . $patient->full_name)
@section('page-title', 'Create Therapy Plan')
@section('breadcrumb', 'Admin / Patients / ' . $patient->full_name . ' / New Plan')

@push('styles')
<style>
.plan-hero {
    background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 50%, #0f172a 100%);
    border-radius: 16px; padding: 2rem 2rem 1.5rem; margin-bottom: 1.75rem;
    position: relative; overflow: hidden;
}
.plan-hero::before {
    content: '🩺'; position: absolute; right: 2rem; top: 50%; transform: translateY(-50%);
    font-size: 80px; opacity: .08;
}
.plan-hero h1 { font-size: 22px; font-weight: 800; color: #fff; margin-bottom: .35rem; }
.plan-hero p  { font-size: 13px; color: #94a3b8; }

.form-section { background: #fff; border-radius: 14px; border: 1px solid #e2e8f0; margin-bottom: 1.25rem; overflow: hidden; }
.form-section-header {
    background: linear-gradient(90deg, #f8fafc, #fff);
    padding: .9rem 1.4rem; border-bottom: 1px solid #f1f5f9;
    display: flex; align-items: center; gap: 8px;
    font-size: 13.5px; font-weight: 700; color: #0f172a;
}
.form-section-body { padding: 1.4rem; }

.freq-badge {
    display: inline-flex; align-items: center; gap: 5px;
    background: #eff6ff; border: 1px solid #bfdbfe;
    color: #1d4ed8; border-radius: 8px; padding: 6px 14px;
    font-size: 12.5px; font-weight: 600; cursor: pointer; transition: all .2s;
}
.freq-badge input { display: none; }
.freq-badge.selected { background: #3b82f6; color: #fff; border-color: #3b82f6; }

.preview-box {
    background: linear-gradient(135deg, #f0fdf4, #eff6ff);
    border: 1px solid #bfdbfe; border-radius: 12px;
    padding: 1.25rem 1.4rem; margin-top: 1rem;
}
.preview-box h4 { font-size: 12px; font-weight: 700; color: #6366f1; text-transform: uppercase; letter-spacing: .06em; margin-bottom: .5rem; }
.preview-item { font-size: 13.5px; color: #374151; margin-bottom: .3rem; }
.preview-item strong { color: #0f172a; }
</style>
@endpush

@section('content')

<div class="plan-hero">
    <h1>📋 New Therapy Plan</h1>
    <p>Creating for: <strong style="color:#93c5fd;">{{ $patient->full_name }}</strong> &nbsp;·&nbsp; {{ $patient->patient_uid }}</p>
</div>

<form action="{{ route('admin.therapy.store', $patient) }}" method="POST" id="planForm">
@csrf

{{-- SECTION 1: Plan Details --}}
<div class="form-section">
    <div class="form-section-header">📋 Plan Details</div>
    <div class="form-section-body">
        <div class="grid-2">
            <div class="form-group">
                <label class="form-label">Plan Name *</label>
                <input name="plan_name" class="form-control" placeholder="e.g. Lower Back Rehabilitation"
                       value="{{ old('plan_name') }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Diagnosis</label>
                <input name="diagnosis" class="form-control" placeholder="e.g. Lumbar Disc Herniation"
                       value="{{ old('diagnosis') }}">
            </div>
        </div>
        <div class="form-group">
            <label class="form-label">Therapy Goal</label>
            <textarea name="goal" class="form-control" rows="2"
                      placeholder="e.g. Reduce pain, restore mobility to 80%...">{{ old('goal') }}</textarea>
        </div>
        <div class="form-group">
            <label class="form-label">Internal Notes (optional)</label>
            <textarea name="notes" class="form-control" rows="2"
                      placeholder="Additional information for the therapy team...">{{ old('notes') }}</textarea>
        </div>
        @if($physiotherapists->count() > 0)
        <div class="form-group">
            <label class="form-label">Assign Physiotherapist (optional)</label>
            <select name="physiotherapist_id" class="form-control">
                <option value="">— Not assigned —</option>
                @foreach($physiotherapists as $pt)
                    <option value="{{ $pt->id }}" {{ old('physiotherapist_id') == $pt->id ? 'selected' : '' }}>
                        {{ $pt->name }} {{ $pt->last_name }}
                    </option>
                @endforeach
            </select>
        </div>
        @endif
    </div>
</div>

{{-- SECTION 2: Session Schedule --}}
<div class="form-section">
    <div class="form-section-header">📅 Session Schedule</div>
    <div class="form-section-body">
        <div class="grid-3" style="margin-bottom:1rem;">
            <div class="form-group">
                <label class="form-label">Total Sessions *</label>
                <input type="number" name="total_sessions" id="totalSessions"
                       class="form-control" min="1" max="365"
                       value="{{ old('total_sessions', 30) }}" required>
                <span class="form-text">Number of therapy sessions in this plan</span>
            </div>
            <div class="form-group">
                <label class="form-label">Start Date *</label>
                <input type="date" name="start_date" id="startDate"
                       class="form-control" value="{{ old('start_date', date('Y-m-d')) }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Days Between Sessions *</label>
                <input type="number" name="sessions_frequency" id="sessFreq"
                       class="form-control" min="1" max="30"
                       value="{{ old('sessions_frequency', 1) }}" required>
                <span class="form-text">1 = daily, 2 = alternate days, 7 = weekly</span>
            </div>
        </div>
        <div class="grid-3" style="margin-bottom:1rem;">
            <div class="form-group">
                <label class="form-label">Session Time</label>
                <input type="time" name="session_time" id="sessionTime"
                       class="form-control" value="{{ old('session_time', '09:00') }}">
            </div>
            <div class="form-group">
                <label class="form-label">Duration (minutes)</label>
                <input type="number" name="session_duration" id="sessionDur"
                       class="form-control" min="5" max="480"
                       value="{{ old('session_duration', 60) }}">
            </div>
            <div class="form-group">
                <label class="form-label">Skip Sundays?</label>
                <div style="display:flex;align-items:center;gap:.75rem;margin-top:.5rem;">
                    <label style="display:flex;align-items:center;gap:.4rem;cursor:pointer;font-size:13.5px;font-weight:600;color:#374151;">
                        <input type="checkbox" name="skip_sundays" id="skipSundays" value="1"
                               {{ old('skip_sundays', '1') ? 'checked' : '' }}
                               style="width:16px;height:16px;accent-color:#3b82f6;">
                        Yes, skip Sundays
                    </label>
                </div>
                <span class="form-text">Sessions will not be scheduled on Sundays</span>
            </div>
        </div>

        {{-- Preview Box --}}
        <div class="preview-box" id="schedulePreview">
            <h4>📊 Schedule Preview</h4>
            <div class="preview-item">⏳ Calculating…</div>
        </div>
    </div>
</div>

{{-- SUBMIT --}}
<div style="display:flex;gap:.75rem;align-items:center;justify-content:flex-end;margin-bottom:2rem;">
    <a href="{{ route('admin.patients.show', $patient) }}" class="btn btn-ghost">← Cancel</a>
    <button type="submit" class="btn btn-primary" style="padding:.7rem 2rem;font-size:14px;">
        🚀 Create Plan & Auto-Schedule Sessions
    </button>
</div>

</form>
@endsection

@push('scripts')
<script>
// ── Live schedule preview ──────────────────────────────────────────────────────
function updatePreview() {
    const total     = parseInt(document.getElementById('totalSessions').value) || 0;
    const startVal  = document.getElementById('startDate').value;
    const freq      = parseInt(document.getElementById('sessFreq').value) || 1;
    const time      = document.getElementById('sessionTime').value;
    const dur       = parseInt(document.getElementById('sessionDur').value) || 60;
    const skipSun   = document.getElementById('skipSundays').checked;
    const preview   = document.getElementById('schedulePreview');

    if (!startVal || total < 1) {
        preview.innerHTML = '<h4>📊 Schedule Preview</h4><div class="preview-item">⏳ Fill in total sessions and start date…</div>';
        return;
    }

    // Calculate end date in JS
    let date = new Date(startVal);
    // skip sunday if needed for start
    if (skipSun && date.getDay() === 0) date.setDate(date.getDate() + 1);

    let endDate = new Date(date);
    for (let i = 1; i < total; i++) {
        endDate = new Date(endDate);
        endDate.setDate(endDate.getDate() + freq);
        if (skipSun) {
            while (endDate.getDay() === 0) endDate.setDate(endDate.getDate() + 1);
        }
    }

    const fmtDate = d => d.toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });
    const totalDays = Math.round((endDate - date) / 86400000);

    const timeLabel = time ? ` at ${formatTime12(time)}` : '';
    const durLabel  = dur ? ` · ${dur} min/session` : '';

    preview.innerHTML = `
        <h4>📊 Schedule Preview</h4>
        <div class="preview-item">📅 <strong>${total} sessions</strong> from <strong>${fmtDate(date)}</strong> to <strong>${fmtDate(endDate)}</strong></div>
        <div class="preview-item">🔄 Every <strong>${freq === 1 ? 'day' : freq + ' days'}</strong>${skipSun ? ' (Sundays skipped)' : ''}${timeLabel}${durLabel}</div>
        <div class="preview-item">📆 Spans approximately <strong>${totalDays} days</strong> (${Math.ceil(totalDays/30)} month${totalDays > 30 ? 's' : ''})</div>
    `;
}

function formatTime12(t) {
    const [h, m] = t.split(':').map(Number);
    const ampm = h >= 12 ? 'PM' : 'AM';
    const h12  = h % 12 || 12;
    return `${h12}:${String(m).padStart(2,'0')} ${ampm}`;
}

['totalSessions','startDate','sessFreq','sessionTime','sessionDur','skipSundays'].forEach(id => {
    const el = document.getElementById(id);
    if (el) el.addEventListener('change', updatePreview);
    if (el) el.addEventListener('input', updatePreview);
});
updatePreview();
</script>
@endpush
