@extends('admin.layouts.sidebar')
@section('title','Edit Patient')
@section('page-title','Edit Patient')
@section('breadcrumb','Admin / Patients / Edit')

@section('content')
<div style="max-width:760px;">

<form action="{{ route('admin.patients.update', $patient) }}" method="POST" enctype="multipart/form-data">
@csrf @method('PATCH')

<div class="card" style="margin-bottom:1.25rem;">
    <div class="card-header"><div class="card-title">👤 Personal Information</div></div>
    <div class="card-body">
        <div class="grid-2">
            <div class="form-group">
                <label class="form-label">First Name *</label>
                <input name="name" value="{{ old('name', $patient->name) }}" class="form-control" required>
            </div>
            <div class="form-group">
                <label class="form-label">Last Name</label>
                <input name="last_name" value="{{ old('last_name', $patient->last_name) }}" class="form-control">
            </div>
        </div>
        <div class="grid-2">
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input name="email" type="email" value="{{ old('email', $patient->email) }}" class="form-control">
            </div>
            <div class="form-group">
                <label class="form-label">Phone Number *</label>
                <input name="phone" value="{{ old('phone', $patient->phone) }}" class="form-control" required>
            </div>
        </div>
        <div class="grid-2">
            <div class="form-group">
                <label class="form-label">ID Card / Aadhaar</label>
                <input name="id_card" value="{{ old('id_card', $patient->id_card) }}" class="form-control">
            </div>
            <div class="form-group">
                <label class="form-label">Date of Birth</label>
                <input name="fecha_nacimiento" type="date"
                       value="{{ old('fecha_nacimiento', $patient->fecha_nacimiento?->format('Y-m-d')) }}"
                       class="form-control">
            </div>
        </div>
        <div class="grid-2">
            <div class="form-group">
                <label class="form-label">Gender</label>
                <select name="sexo" class="form-control">
                    <option value="">— Select —</option>
                    @foreach(['male','female','other'] as $g)
                        <option value="{{ $g }}" {{ old('sexo',$patient->sexo)===$g?'selected':'' }}>{{ ucfirst($g) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Blood Group</label>
                <select name="blood_group" class="form-control">
                    <option value="">— Select —</option>
                    @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg)
                        <option value="{{ $bg }}" {{ old('blood_group',$patient->blood_group)===$bg?'selected':'' }}>{{ $bg }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="form-label">Address</label>
            <input name="address" value="{{ old('address', $patient->address) }}" class="form-control">
        </div>
    </div>
</div>

<div class="card" style="margin-bottom:1.25rem;">
    <div class="card-header"><div class="card-title">📷 Patient Photo</div></div>
    <div class="card-body" style="display:flex;align-items:center;gap:1.5rem;">
        <img id="photoPreview" src="{{ $patient->photo_url }}"
             style="width:90px;height:90px;border-radius:50%;object-fit:cover;border:3px solid #e2e8f0;">
        <div>
            <input type="file" name="photo" id="photoInput" accept="image/*" style="display:none" onchange="previewPhoto(this)">
            <button type="button" class="btn btn-ghost" onclick="document.getElementById('photoInput').click()">📷 Change Photo</button>
            <div class="form-text" style="margin-top:.4rem;">Leave blank to keep current photo</div>
        </div>
    </div>
</div>

<div class="card" style="margin-bottom:1.25rem;">
    <div class="card-header"><div class="card-title">🚨 Emergency Contact</div></div>
    <div class="card-body">
        <div class="grid-2">
            <div class="form-group">
                <label class="form-label">Contact Name</label>
                <input name="emergency_contact" value="{{ old('emergency_contact', $patient->emergency_contact) }}" class="form-control">
            </div>
            <div class="form-group">
                <label class="form-label">Contact Phone</label>
                <input name="emergency_phone" value="{{ old('emergency_phone', $patient->emergency_phone) }}" class="form-control">
            </div>
        </div>
    </div>
</div>

{{-- PATIENT ID (read-only) + THERAPY PLAN --}}
<div class="card" style="margin-bottom:1.25rem;">
    <div class="card-header"><div class="card-title">🩺 Therapy Plan & Session Info</div></div>
    <div class="card-body">
        {{-- Patient UID (read-only) --}}
        @if($patient->patient_uid)
        <div class="form-group" style="margin-bottom:1rem;">
            <label class="form-label">Patient ID</label>
            <div style="display:flex;align-items:center;gap:.75rem;">
                <input type="text" value="{{ $patient->patient_uid }}" class="form-control" style="font-family:monospace;font-weight:800;color:#6366f1;max-width:180px;" readonly>
                <span style="font-size:12px;color:#94a3b8;">Auto-generated — cannot be changed</span>
            </div>
        </div>
        @endif
        <div class="grid-2">
            <div class="form-group">
                <label class="form-label">Plan Name</label>
                <input name="therapy_plan_name" value="{{ old('therapy_plan_name', $patient->therapy_plan_name) }}" class="form-control" placeholder="e.g. Lower Back Rehabilitation">
            </div>
            <div class="form-group">
                <label class="form-label">Diagnosis</label>
                <input name="therapy_diagnosis" value="{{ old('therapy_diagnosis', $patient->therapy_diagnosis) }}" class="form-control" placeholder="e.g. Lumbar Disc Herniation">
            </div>
        </div>
        <div class="form-group">
            <label class="form-label">Therapy Goal</label>
            <textarea name="therapy_goal" class="form-control" rows="2" placeholder="Describe the therapy objectives…">{{ old('therapy_goal', $patient->therapy_goal) }}</textarea>
        </div>
        <div class="grid-3">
            <div class="form-group">
                <label class="form-label">Start Date</label>
                <input name="therapy_start_date" type="date" value="{{ old('therapy_start_date', $patient->therapy_start_date?->format('Y-m-d')) }}" class="form-control">
            </div>
            <div class="form-group">
                <label class="form-label">End Date</label>
                <input name="therapy_end_date" type="date" value="{{ old('therapy_end_date', $patient->therapy_end_date?->format('Y-m-d')) }}" class="form-control">
            </div>
            <div class="form-group">
                <label class="form-label">Sessions Purchased</label>
                <input name="sessions_purchased" type="number" min="0" value="{{ old('sessions_purchased', $patient->sessions_purchased) }}" class="form-control">
                <div class="form-text">Completed: {{ $patient->sessions_completed }} | Missed: {{ $patient->missed_days }}</div>
            </div>
        </div>
    </div>
</div>

<div style="display:flex;gap:.75rem;">
    <button type="submit" class="btn btn-primary">💾 Save Changes</button>
    <a href="{{ route('admin.patients.show', $patient) }}" class="btn btn-ghost">← Cancel</a>
</div>

</form>
</div>
@endsection

@push('scripts')
<script>
function previewPhoto(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => document.getElementById('photoPreview').src = e.target.result;
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
