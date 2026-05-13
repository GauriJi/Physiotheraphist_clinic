@extends('admin.layouts.sidebar')
@section('title', 'My Schedule')
@section('page-title', 'My Availability Schedule')
@section('breadcrumb', 'Doctor / My Schedule')

@push('styles')
<style>
.schedule-grid {
    display: grid;
    gap: 1rem;
}
.day-card {
    background: #fff;
    border-radius: 14px;
    border: 1.5px solid #e2e8f0;
    overflow: hidden;
    transition: border-color .2s, box-shadow .2s;
}
.day-card.available {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59,130,246,.08);
}
.day-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: .9rem 1.3rem;
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
}
.day-header.available { background: #eff6ff; border-bottom-color: #bfdbfe; }
.day-name {
    font-size: 14px;
    font-weight: 700;
    color: #0f172a;
    display: flex;
    align-items: center;
    gap: 8px;
}
.day-body {
    padding: 1rem 1.3rem;
    display: none;
}
.day-body.show { display: flex; gap: 1.5rem; align-items: center; flex-wrap: wrap; }

/* Toggle switch */
.toggle-wrap { display: flex; align-items: center; gap: 10px; }
.toggle-label { font-size: 12.5px; font-weight: 600; color: #64748b; }
.toggle {
    position: relative;
    width: 46px; height: 24px;
}
.toggle input { opacity: 0; width: 0; height: 0; }
.toggle-slider {
    position: absolute; inset: 0;
    background: #cbd5e1; border-radius: 24px; cursor: pointer; transition: .3s;
}
.toggle-slider::before {
    content: '';
    position: absolute;
    width: 18px; height: 18px;
    left: 3px; bottom: 3px;
    background: #fff; border-radius: 50%; transition: .3s;
}
.toggle input:checked + .toggle-slider { background: #3b82f6; }
.toggle input:checked + .toggle-slider::before { transform: translateX(22px); }

.time-group {
    display: flex; align-items: center; gap: .5rem;
}
.time-group label { font-size: 12px; font-weight: 600; color: #64748b; white-space: nowrap; }
.time-input {
    padding: .45rem .7rem;
    border: 1.5px solid #e2e8f0;
    border-radius: 8px;
    font-size: 13px;
    font-family: 'Inter', sans-serif;
    transition: border-color .2s;
    width: 130px;
}
.time-input:focus { outline: none; border-color: #3b82f6; }
.time-sep { color: #94a3b8; font-weight: 600; }

.avail-badge {
    font-size: 11px; font-weight: 700; padding: 3px 10px;
    border-radius: 20px;
}
.avail-badge.on  { background: #dbeafe; color: #1d4ed8; }
.avail-badge.off { background: #f1f5f9; color: #94a3b8; }
</style>
@endpush

@section('content')
<div style="max-width:860px;">

    {{-- Info Banner --}}
    <div class="alert alert-info" style="margin-bottom:1.5rem;">
        ⏰ Set your weekly availability. Patients can only book appointments on days you mark as <strong>Available</strong>.
    </div>

    <form action="{{ route('doctor.actualizar-mi-schedule') }}" method="POST">
        @csrf

        <div class="schedule-grid">
            @foreach($scheduleByDay as $day => $entry)
            @php
                $isAvailable = $entry && $entry->disponible;
                $icons = [
                    'Monday'    => '🌅',
                    'Tuesday'   => '🌤',
                    'Wednesday' => '🌞',
                    'Thursday'  => '🌤',
                    'Friday'    => '🌇',
                    'Saturday'  => '🌴',
                    'Sunday'    => '🏠',
                ];
                $icon = $icons[$day] ?? '📅';
            @endphp

            <div class="day-card {{ $isAvailable ? 'available' : '' }}" id="card-{{ $day }}">
                <div class="day-header {{ $isAvailable ? 'available' : '' }}" id="header-{{ $day }}">
                    <div class="day-name">
                        <span>{{ $icon }}</span>
                        {{ $day }}
                    </div>
                    <div style="display:flex; align-items:center; gap:12px;">
                        <span class="avail-badge {{ $isAvailable ? 'on' : 'off' }}" id="badge-{{ $day }}">
                            {{ $isAvailable ? 'Available' : 'Off' }}
                        </span>
                        <label class="toggle">
                            <input type="checkbox"
                                   name="{{ $day }}_available"
                                   id="toggle-{{ $day }}"
                                   {{ $isAvailable ? 'checked' : '' }}
                                   onchange="toggleDay('{{ $day }}', this.checked)">
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                </div>

                <div class="day-body {{ $isAvailable ? 'show' : '' }}" id="body-{{ $day }}">
                    <div class="time-group">
                        <label>From</label>
                        <input type="time"
                               name="{{ $day }}_start"
                               value="{{ $entry && $entry->hora_inicio ? \Carbon\Carbon::parse($entry->hora_inicio)->format('H:i') : '09:00' }}"
                               class="time-input"
                               id="start-{{ $day }}">
                    </div>
                    <span class="time-sep">→</span>
                    <div class="time-group">
                        <label>To</label>
                        <input type="time"
                               name="{{ $day }}_end"
                               value="{{ $entry && $entry->hora_fin ? \Carbon\Carbon::parse($entry->hora_fin)->format('H:i') : '17:00' }}"
                               class="time-input"
                               id="end-{{ $day }}">
                    </div>
                    <div style="margin-left:auto; font-size:12px; color:#94a3b8;">
                        @if($entry && $entry->hora_inicio && $entry->hora_fin)
                            @php
                                $start = \Carbon\Carbon::parse($entry->hora_inicio);
                                $end   = \Carbon\Carbon::parse($entry->hora_fin);
                                $hours = $start->diffInMinutes($end) / 60;
                            @endphp
                            {{ number_format($hours, 1) }} hrs
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Summary Row --}}
        <div class="card" style="margin-top:1.5rem;">
            <div class="card-body" style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem;">
                <div style="font-size:13px; color:#64748b;">
                    🗓 Toggle days on/off and set your working hours, then save.
                </div>
                <div style="display:flex; gap:.75rem;">
                    <button type="submit" class="btn btn-primary" style="padding:.75rem 2rem; font-size:14px;">
                        💾 Save Schedule
                    </button>
                </div>
            </div>
        </div>

    </form>
</div>
@endsection

@push('scripts')
<script>
function toggleDay(day, isOn) {
    const card   = document.getElementById('card-' + day);
    const header = document.getElementById('header-' + day);
    const body   = document.getElementById('body-' + day);
    const badge  = document.getElementById('badge-' + day);

    if (isOn) {
        card.classList.add('available');
        header.classList.add('available');
        body.classList.add('show');
        badge.textContent = 'Available';
        badge.className = 'avail-badge on';
    } else {
        card.classList.remove('available');
        header.classList.remove('available');
        body.classList.remove('show');
        badge.textContent = 'Off';
        badge.className = 'avail-badge off';
    }
}
</script>
@endpush
