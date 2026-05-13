@extends('admin.layouts.sidebar')
@section('title','Patients')
@section('page-title','Patient Management')
@section('breadcrumb','Admin / Patients')

@section('content')

{{-- SEARCH + ADD --}}
<div class="card" style="margin-bottom:1.25rem;">
    <div class="card-body" style="padding:1rem 1.25rem;">
        <form method="GET" style="display:flex;gap:.75rem;flex-wrap:wrap;align-items:flex-end;">
            <div style="flex:1;min-width:200px;">
                <label class="form-label">Search Patients</label>
                <input name="search" value="{{ request('search') }}" class="form-control" placeholder="Name, email, phone or ID…">
            </div>
            <div>
                <label class="form-label">Blood Group</label>
                <select name="blood_group" class="form-control">
                    <option value="">All</option>
                    @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg)
                        <option value="{{ $bg }}" {{ request('blood_group')===$bg?'selected':'' }}>{{ $bg }}</option>
                    @endforeach
                </select>
            </div>
            <button class="btn btn-primary" type="submit">🔍 Search</button>
            <a href="{{ route('admin.patients.index') }}" class="btn btn-ghost">Reset</a>
            <a href="{{ route('admin.patients.create') }}" class="btn btn-success" style="margin-left:auto;">➕ Add Patient</a>
        </form>
    </div>
</div>

<div style="display:flex;gap:1rem;margin-bottom:1.25rem;flex-wrap:wrap;">
    <div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:10px;padding:.7rem 1.2rem;font-size:13px;font-weight:600;color:#1d4ed8;">
        👥 Total: {{ $patients->total() }} patients
    </div>
</div>

@if($patients->count() > 0)
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(270px,1fr));gap:1.1rem;margin-bottom:1.5rem;">
    @foreach($patients as $patient)
    <div class="card" style="transition:all .2s;"
         onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 12px 30px rgba(0,0,0,.08)'"
         onmouseout="this.style.transform='';this.style.boxShadow=''">
        <div style="padding:1.25rem;">
            <div style="display:flex;align-items:center;gap:.9rem;margin-bottom:1rem;">
                <img src="{{ $patient->photo_url }}" alt=""
                     style="width:52px;height:52px;border-radius:50%;object-fit:cover;border:2px solid #e2e8f0;">
                <div>
                    <div style="font-weight:700;color:#0f172a;font-size:14px;">{{ $patient->full_name }}</div>
                    @if($patient->patient_uid)
                    <div style="font-size:10.5px;font-weight:800;color:#6366f1;font-family:monospace;margin-top:2px;">{{ $patient->patient_uid }}</div>
                    @endif
                    <div style="font-size:12px;color:#64748b;">{{ $patient->email ?? 'No email' }}</div>
                    @if($patient->blood_group)
                        <span style="background:#fee2e2;color:#991b1b;font-size:10px;font-weight:700;padding:1px 7px;border-radius:20px;">
                            {{ $patient->blood_group }}
                        </span>
                    @endif
                </div>
            </div>

            <div style="font-size:12.5px;color:#64748b;display:flex;flex-direction:column;gap:4px;margin-bottom:1rem;">
                @if($patient->phone)   <span>📞 {{ $patient->phone }}</span> @endif
                @if($patient->age)     <span>🎂 Age {{ $patient->age }}</span> @endif
                @if($patient->id_card) <span>🪪 {{ $patient->id_card }}</span> @endif
                @if($patient->address) <span>📍 {{ Str::limit($patient->address, 38) }}</span> @endif
                @if($patient->sessions_purchased > 0)
                <span>🩺 Sessions: {{ $patient->sessions_completed }}/{{ $patient->sessions_purchased }} done</span>
                @endif
            </div>

            <div style="display:flex;gap:.5rem;">
                <a href="{{ route('admin.patients.show', $patient) }}" class="btn btn-primary btn-sm" style="flex:1;justify-content:center;">👁 View</a>
                <a href="{{ route('admin.patients.edit', $patient) }}" class="btn btn-warning btn-sm" style="flex:1;justify-content:center;">✏️ Edit</a>
                <form action="{{ route('admin.patients.destroy', $patient) }}" method="POST"
                      onsubmit="return confirm('Delete this patient? This cannot be undone.')">
                    @csrf @method('DELETE')
                    <button class="btn btn-danger btn-sm">🗑</button>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>
{{ $patients->links() }}
@else
    <div class="card">
        <div class="empty-state">
            <div class="empty-icon">👥</div>
            <div class="empty-title">No patients found</div>
            <div class="empty-sub">Add your first patient to get started</div>
            <a href="{{ route('admin.patients.create') }}" class="btn btn-primary" style="margin-top:1rem;">➕ Add Patient</a>
        </div>
    </div>
@endif

@endsection
