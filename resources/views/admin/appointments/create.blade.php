@extends('admin.layouts.sidebar')
@section('title','Create Appointment')
@section('page-title','Schedule Appointment')
@section('breadcrumb','Admin / Appointments / New')

@section('content')
<div style="max-width:820px;">

<div class="card" style="margin-bottom:1.5rem;">
    <div class="card-body" style="background:#f8fafc;border-bottom:1px solid #e2e8f0;padding:1rem 1.25rem;">
        <div class="form-group" style="margin:0;">
            <label class="form-label">Auto-fill from existing patient (Optional)</label>
            <select id="patientSelect" class="form-control" onchange="autoFillPatient()">
                <option value="">— Select a registered patient —</option>
                @foreach($patients as $p)
                    <option value="{{ $p->id }}" 
                            data-fname="{{ $p->name }}" 
                            data-lname="{{ $p->last_name }}" 
                            data-email="{{ $p->email }}" 
                            data-phone="{{ $p->phone }}" 
                            data-idcard="{{ $p->id_card }}">
                        {{ $p->full_name }} — {{ $p->phone }}
                    </option>
                @endforeach
            </select>
            <div class="form-text">Selecting a patient will auto-fill the form fields below.</div>
        </div>
    </div>
</div>

<form action="{{ route('admin.appointments.store') }}" method="POST">
    @csrf

    <div class="card" style="margin-bottom:1.5rem;">
        <div class="card-header"><div class="card-title">👤 Patient Details</div></div>
        <div class="card-body">
            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label">First Name *</label>
                    <input type="text" name="names" id="f_names" class="form-control" value="{{ old('names') }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Last Name *</label>
                    <input type="text" name="last_names" id="f_last_names" class="form-control" value="{{ old('last_names') }}" required>
                </div>
            </div>
            
            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label">Email Address *</label>
                    <input type="email" name="email" id="f_email" class="form-control" value="{{ old('email') }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Phone Number *</label>
                    <input type="text" name="phone" id="f_phone" class="form-control" value="{{ old('phone') }}" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">ID Card / Aadhaar *</label>
                <input type="text" name="id_card" id="f_id_card" class="form-control" value="{{ old('id_card') }}" required>
            </div>
        </div>
    </div>

    <div class="card" style="margin-bottom:1.5rem;">
        <div class="card-header"><div class="card-title">📅 Appointment Details</div></div>
        <div class="card-body">
            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label">Specialty *</label>
                    <select name="specialty_id" id="specialty_id" class="form-control" required>
                        <option value="">— Select Specialty —</option>
                        @foreach($specialties as $specialty)
                            <option value="{{ $specialty->id }}" {{ old('specialty_id') == $specialty->id ? 'selected' : '' }}>
                                {{ $specialty->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Physiotherapist *</label>
                    <select name="physiotherapist_id" id="physiotherapist_id" class="form-control" required>
                        <option value="">— Select Doctor —</option>
                        @foreach($physiotherapists as $doctor)
                            <option value="{{ $doctor->id }}" class="doc-option doc-spec-{{ $doctor->specialty_id }}" {{ old('physiotherapist_id') == $doctor->id ? 'selected' : '' }}>
                                Dr. {{ $doctor->name }} {{ $doctor->last_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label">Date *</label>
                    <input type="date" name="fecha_cita" class="form-control" value="{{ old('fecha_cita') }}" min="{{ date('Y-m-d') }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Time *</label>
                    <input type="time" id="hora_cita" name="hora_cita" class="form-control" value="{{ old('hora_cita') }}" required>
                    <div id="availability-badge" style="margin-top: 6px; font-size: 13px; font-weight: 500;"></div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Reason for Visit *</label>
                <textarea name="reason" class="form-control" rows="3" placeholder="Briefly describe the symptoms or reason for visit..." required>{{ old('reason') }}</textarea>
            </div>
        </div>
    </div>

    <div style="display:flex; gap:1rem;">
        <button type="submit" class="btn btn-primary" style="padding:0.75rem 2rem; font-size:14px;">✅ Schedule Appointment</button>
        <a href="{{ route('admin.appointments.index') }}" class="btn btn-ghost" style="padding:0.75rem 2rem; font-size:14px;">Cancel</a>
    </div>

</form>

</div>
@endsection

@push('scripts')
<script>
    function autoFillPatient() {
        const select = document.getElementById('patientSelect');
        const option = select.options[select.selectedIndex];
        
        if (select.value) {
            document.getElementById('f_names').value = option.getAttribute('data-fname') || '';
            document.getElementById('f_last_names').value = option.getAttribute('data-lname') || '';
            document.getElementById('f_email').value = option.getAttribute('data-email') || '';
            document.getElementById('f_phone').value = option.getAttribute('data-phone') || '';
            document.getElementById('f_id_card').value = option.getAttribute('data-idcard') || '';
        }
    }

    // Basic dynamic filtering for doctors based on specialty
    document.getElementById('specialty_id').addEventListener('change', function() {
        const specId = this.value;
        const docSelect = document.getElementById('physiotherapist_id');
        const options = docSelect.querySelectorAll('.doc-option');
        
        docSelect.value = ""; // Reset
        
        options.forEach(opt => {
            if (!specId) {
                opt.style.display = 'block';
            } else {
                if (opt.classList.contains('doc-spec-' + specId)) {
                    opt.style.display = 'block';
                } else {
                    opt.style.display = 'none';
                }
            }
        });
    });

    // Check Availability Logic
    const docSelect = document.getElementById('physiotherapist_id');
    const dateInput = document.querySelector('input[name="fecha_cita"]');
    const timeInput = document.getElementById('hora_cita');
    const badge = document.getElementById('availability-badge');

    function checkAvailability() {
        const docId = docSelect.value;
        const date = dateInput.value;

        if (!docId || !date) {
            badge.innerHTML = '';
            timeInput.removeAttribute('min');
            timeInput.removeAttribute('max');
            return;
        }

        badge.innerHTML = 'Checking availability...';

        fetch(`/api/physiotherapists/${docId}/availability?date=${date}`)
            .then(res => res.json())
            .then(data => {
                if (data.available) {
                    badge.innerHTML = `<span style="color: #10b981;">✅ Available: ${data.start} - ${data.end}</span>`;
                    timeInput.setAttribute('min', data.start);
                    timeInput.setAttribute('max', data.end);
                } else {
                    badge.innerHTML = `<span style="color: #ef4444;">❌ Not available on this day</span>`;
                    timeInput.removeAttribute('min');
                    timeInput.removeAttribute('max');
                }
            })
            .catch(err => {
                badge.innerHTML = '';
                console.error(err);
            });
    }

    docSelect.addEventListener('change', checkAvailability);
    dateInput.addEventListener('change', checkAvailability);
</script>
@endpush
