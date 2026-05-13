@extends('admin.layouts.sidebar')
@section('title', 'Therapy Plans — ' . $patient->full_name)
@section('page-title', 'Therapy Plans')
@section('breadcrumb', 'Admin / Patients / ' . $patient->full_name . ' / Therapy Plans')

@section('content')

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.25rem;flex-wrap:wrap;gap:.75rem;">
    <div>
        <div style="font-size:18px;font-weight:800;color:#0f172a;">🩺 Therapy Plans</div>
        <div style="font-size:13px;color:#64748b;margin-top:2px;">{{ $patient->full_name }} &nbsp;·&nbsp; {{ $patient->patient_uid }}</div>
    </div>
    <div style="display:flex;gap:.5rem;">
        <a href="{{ route('admin.therapy.create', $patient) }}" class="btn btn-primary">➕ Create New Plan</a>
        <a href="{{ route('admin.patients.show', $patient) }}" class="btn btn-ghost">← Back to Patient</a>
    </div>
</div>

@if($plans->isEmpty())
<div class="card">
    <div class="empty-state">
        <div class="empty-icon">🩺</div>
        <div class="empty-title">No Therapy Plans Yet</div>
        <div class="empty-sub">Create the first therapy plan to start auto-scheduling sessions</div>
        <a href="{{ route('admin.therapy.create', $patient) }}" class="btn btn-primary" style="margin-top:1rem;">➕ Create Therapy Plan</a>
    </div>
</div>
@else
    @foreach($plans as $plan)
    @php
        $completed = $plan->sessions->where('status','completed')->count();
        $total     = $plan->total_sessions;
        $pct       = $total > 0 ? round($completed / $total * 100) : 0;
        $statusColors = ['active'=>['#10b981','#f0fdf4','#bbf7d0'],'completed'=>['#6366f1','#f5f3ff','#ddd6fe'],'cancelled'=>['#ef4444','#fef2f2','#fecaca']];
        $sc = $statusColors[$plan->status] ?? ['#94a3b8','#f8fafc','#e2e8f0'];
    @endphp
    <div class="card" style="margin-bottom:1rem;">
        <div style="padding:1.25rem 1.4rem;">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:.75rem;margin-bottom:1rem;">
                <div>
                    <div style="display:flex;align-items:center;gap:.6rem;margin-bottom:.3rem;">
                        <span style="font-size:16px;font-weight:800;color:#0f172a;">{{ $plan->plan_name }}</span>
                        <span style="background:{{ $sc[1] }};color:{{ $sc[0] }};border:1px solid {{ $sc[2] }};font-size:11px;font-weight:700;padding:2px 10px;border-radius:20px;text-transform:uppercase;">{{ ucfirst($plan->status) }}</span>
                    </div>
                    <div style="font-size:13px;color:#64748b;">
                        {{ $plan->start_date->format('d M Y') }} → {{ $plan->end_date ? $plan->end_date->format('d M Y') : '?' }}
                        &nbsp;·&nbsp; {{ $plan->total_sessions }} sessions every {{ $plan->sessions_frequency === 1 ? 'day' : $plan->sessions_frequency . ' days' }}
                        @if($plan->session_time) &nbsp;·&nbsp; {{ \Carbon\Carbon::parse($plan->session_time)->format('h:i A') }} @endif
                    </div>
                    @if($plan->diagnosis)<div style="font-size:12.5px;color:#94a3b8;margin-top:2px;">{{ $plan->diagnosis }}</div>@endif
                </div>
                <div style="display:flex;gap:.4rem;">
                    <a href="{{ route('admin.therapy.show', $plan) }}" class="btn btn-primary btn-sm">📅 View Calendar</a>
                    <form action="{{ route('admin.therapy.destroy', $plan) }}" method="POST"
                          onsubmit="return confirm('Delete this plan and all its sessions?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm">🗑</button>
                    </form>
                </div>
            </div>
            {{-- Mini stats --}}
            <div style="display:flex;gap:1rem;flex-wrap:wrap;margin-bottom:.75rem;">
                <div style="font-size:12px;color:#64748b;"><span style="font-weight:700;color:#10b981;">✅ {{ $completed }}</span> Completed</div>
                <div style="font-size:12px;color:#64748b;"><span style="font-weight:700;color:#f59e0b;">⏰ {{ $plan->sessions->where('status','upcoming')->count() }}</span> Upcoming</div>
                <div style="font-size:12px;color:#64748b;"><span style="font-weight:700;color:#ef4444;">❌ {{ $plan->sessions->where('status','missed')->count() }}</span> Missed</div>
                <div style="font-size:12px;color:#64748b;"><span style="font-weight:700;color:#3b82f6;">🔄 {{ $plan->sessions->where('status','rescheduled')->count() }}</span> Rescheduled</div>
            </div>
            {{-- Progress bar --}}
            <div style="background:#e2e8f0;border-radius:999px;height:7px;overflow:hidden;">
                <div style="height:100%;width:{{ $pct }}%;background:linear-gradient(90deg,#3b82f6,#6366f1);border-radius:999px;"></div>
            </div>
            <div style="font-size:11.5px;color:#94a3b8;margin-top:.3rem;">{{ $pct }}% complete</div>
        </div>
    </div>
    @endforeach
@endif

@endsection
