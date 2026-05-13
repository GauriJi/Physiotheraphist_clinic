@extends('admin.layouts.sidebar')
@section('title','Add Patient')
@section('page-title','Add New Patient')
@section('breadcrumb','Admin / Patients / Add')

@section('content')
<div style="max-width:760px;">

<form action="{{ route('admin.patients.store') }}" method="POST" enctype="multipart/form-data">
@csrf

{{-- PERSONAL INFO --}}
<div class="card" style="margin-bottom:1.25rem;">
    <div class="card-header"><div class="card-title">👤 Personal Information</div></div>
    <div class="card-body">
        <div class="grid-2">
            <div class="form-group">
                <label class="form-label">First Name *</label>
                <input name="name" value="{{ old('name') }}" class="form-control" placeholder="John" required>
            </div>
            <div class="form-group">
                <label class="form-label">Last Name</label>
                <input name="last_name" value="{{ old('last_name') }}" class="form-control" placeholder="Doe">
            </div>
        </div>
        <div class="grid-2">
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input name="email" type="email" value="{{ old('email') }}" class="form-control" placeholder="patient@email.com">
            </div>
            <div class="form-group">
                <label class="form-label">Phone Number *</label>
                <input name="phone" value="{{ old('phone') }}" class="form-control" placeholder="+91 98765 43210" required>
            </div>
        </div>
        <div class="grid-2">
            <div class="form-group">
                <label class="form-label">ID Card / Aadhaar</label>
                <input name="id_card" value="{{ old('id_card') }}" class="form-control" placeholder="ID number">
            </div>
            <div class="form-group">
                <label class="form-label">Date of Birth</label>
                <input name="fecha_nacimiento" type="date" value="{{ old('fecha_nacimiento') }}" class="form-control">
            </div>
        </div>
        <div class="grid-2">
            <div class="form-group">
                <label class="form-label">Gender</label>
                <select name="sexo" class="form-control">
                    <option value="">— Select —</option>
                    <option value="male"   {{ old('sexo')==='male'?'selected':'' }}>Male</option>
                    <option value="female" {{ old('sexo')==='female'?'selected':'' }}>Female</option>
                    <option value="other"  {{ old('sexo')==='other'?'selected':'' }}>Other</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Blood Group</label>
                <select name="blood_group" class="form-control">
                    <option value="">— Select —</option>
                    @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg)
                        <option value="{{ $bg }}" {{ old('blood_group')===$bg?'selected':'' }}>{{ $bg }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="form-label">Address</label>
            <input name="address" value="{{ old('address') }}" class="form-control" placeholder="Street, City, State, PIN">
        </div>
    </div>
</div>

{{-- PHOTO --}}
<div class="card" style="margin-bottom:1.25rem;">
    <div class="card-header"><div class="card-title">📷 Patient Photo</div></div>
    <div class="card-body" style="display:flex;align-items:center;gap:1.5rem;">
        <img id="photoPreview" src="https://ui-avatars.com/api/?name=P&background=3b82f6&color=fff&size=100"
             style="width:90px;height:90px;border-radius:50%;object-fit:cover;border:3px solid #e2e8f0;">
        <div>
            <input type="file" name="photo" id="photoInput" accept="image/*" style="display:none" onchange="previewPhoto(this)">
            <button type="button" class="btn btn-ghost" onclick="document.getElementById('photoInput').click()">📷 Upload Photo</button>
            <div class="form-text" style="margin-top:.4rem;">JPG, PNG — max 2MB (optional)</div>
        </div>
    </div>
</div>

{{-- EMERGENCY CONTACT --}}
<div class="card" style="margin-bottom:1.25rem;">
    <div class="card-header"><div class="card-title">🚨 Emergency Contact</div></div>
    <div class="card-body">
        <div class="grid-2">
            <div class="form-group">
                <label class="form-label">Contact Name</label>
                <input name="emergency_contact" value="{{ old('emergency_contact') }}" class="form-control" placeholder="Jane Doe">
            </div>
            <div class="form-group">
                <label class="form-label">Contact Phone</label>
                <input name="emergency_phone" value="{{ old('emergency_phone') }}" class="form-control" placeholder="+91 98765 43210">
            </div>
        </div>
    </div>
</div>

{{-- THERAPY PLAN --}}
<div class="card" style="margin-bottom:1.25rem;">
    <div class="card-header">
        <div class="card-title">🩺 Therapy Plan <span style="font-size:12px;font-weight:400;color:#94a3b8;">(optional — can be added later)</span></div>
    </div>
    <div class="card-body">
        <div class="grid-2">
            <div class="form-group">
                <label class="form-label">Plan Name</label>
                <input name="therapy_plan_name" value="{{ old('therapy_plan_name') }}" class="form-control" placeholder="e.g. Lower Back Rehabilitation">
            </div>
            <div class="form-group">
                <label class="form-label">Diagnosis</label>
                <input name="therapy_diagnosis" value="{{ old('therapy_diagnosis') }}" class="form-control" placeholder="e.g. Lumbar Disc Herniation">
            </div>
        </div>
        <div class="form-group">
            <label class="form-label">Therapy Goal</label>
            <textarea name="therapy_goal" class="form-control" rows="2" placeholder="Describe the therapy objectives…">{{ old('therapy_goal') }}</textarea>
        </div>
        <div class="grid-3">
            <div class="form-group">
                <label class="form-label">Start Date</label>
                <input name="therapy_start_date" type="date" value="{{ old('therapy_start_date') }}" class="form-control">
            </div>
            <div class="form-group">
                <label class="form-label">End Date</label>
                <input name="therapy_end_date" type="date" value="{{ old('therapy_end_date') }}" class="form-control">
            </div>
            <div class="form-group">
                <label class="form-label">Sessions Purchased</label>
                <input name="sessions_purchased" type="number" min="0" value="{{ old('sessions_purchased', 0) }}" class="form-control" placeholder="0">
                <div class="form-text">Total sessions the patient has bought</div>
            </div>
        </div>
    </div>
</div>

<div style="display:flex;gap:.75rem;">
    <button type="submit" class="btn btn-primary">✅ Add Patient</button>
    <a href="{{ route('admin.patients.index') }}" class="btn btn-ghost">← Cancel</a>
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
