@extends('admin.layouts.sidebar')
@section('title','Reschedule Appointment')
@section('page-title','Reschedule Appointment')
@section('breadcrumb','Admin / Appointments / Reschedule')

@section('content')
<div style="max-width:820px;">

<form action="{{ route('shared.appointments.update', $appointment->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="card" style="margin-bottom:1.5rem;">
        <div class="card-header"><div class="card-title">📅 Appointment Details</div></div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger" style="color:red; margin-bottom:15px;">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label">Specialty *</label>
                    <select name="specialty_id" id="specialty_id" class="form-control" required>
                        <option value="">— Select Specialty —</option>
                        @foreach($specialties as $specialty)
                            <option value="{{ $specialty->id }}" {{ (old('specialty_id', $appointment->specialty_id) == $specialty->id) ? 'selected' : '' }}>
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
                            <option value="{{ $doctor->id }}" class="doc-option doc-spec-{{ $doctor->specialty_id }}" {{ (old('physiotherapist_id', $appointment->physiotherapist_id) == $doctor->id) ? 'selected' : '' }}>
                                Dr. {{ $doctor->name }} {{ $doctor->last_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label">Date *</label>
                    <input type="date" name="fecha_cita" class="form-control" value="{{ old('fecha_cita', $appointment->fecha_cita) }}" min="{{ date('Y-m-d') }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Time *</label>
                    <input type="time" name="hora_cita" class="form-control" value="{{ old('hora_cita', \Carbon\Carbon::parse($appointment->hora_cita)->format('H:i')) }}" required>
                </div>
            </div>
        </div>
    </div>

    <div style="display:flex; gap:1rem;">
        <button type="submit" class="btn btn-primary" style="padding:0.75rem 2rem; font-size:14px;">✅ Reschedule Appointment</button>
        <a href="javascript:history.back()" class="btn btn-ghost" style="padding:0.75rem 2rem; font-size:14px;">Cancel</a>
    </div>

</form>

</div>
@endsection

@push('scripts')
<script>
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

    // Trigger change event to filter initially based on current specialty
    document.getElementById('specialty_id').dispatchEvent(new Event('change'));
    // But since it resets the value, we need to set it back if it's already set!
    let initialDoc = "{{ old('physiotherapist_id', $appointment->physiotherapist_id) }}";
    if(initialDoc) {
         document.getElementById('physiotherapist_id').value = initialDoc;
    }
</script>
@endpush
