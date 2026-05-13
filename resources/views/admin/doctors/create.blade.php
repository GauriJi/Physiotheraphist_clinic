@extends('admin.layouts.sidebar')
@section('title', 'Create Physiotherapist')
@section('page-title', 'Create Physiotherapist')
@section('breadcrumb', 'Admin / Physiotherapists / New')

@section('content')
<div style="max-width:820px;">

    <form action="{{ route('admin.doctors.store') }}" method="POST">
        @csrf

        {{-- Personal Information --}}
        <div class="card" style="margin-bottom:1.5rem;">
            <div class="card-header">
                <div class="card-title">👨‍⚕️ Personal Information</div>
            </div>
            <div class="card-body">
                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">First Name *</label>
                        <input type="text" name="name" value="{{ old('name') }}" required class="form-control" placeholder="e.g. John">
                        @error('name') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Last Name *</label>
                        <input type="text" name="last_name" value="{{ old('last_name') }}" required class="form-control" placeholder="e.g. Smith">
                        @error('last_name') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Email Address *</label>
                        <input type="email" name="email" value="{{ old('email') }}" required class="form-control" placeholder="doctor@clinic.com">
                        @error('email') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Phone Number *</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" required class="form-control" placeholder="+1 555 000 0000">
                        @error('phone') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Professional Details --}}
        <div class="card" style="margin-bottom:1.5rem;">
            <div class="card-header">
                <div class="card-title">🏥 Professional Details</div>
            </div>
            <div class="card-body">
                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Specialty *</label>
                        <input type="text" name="specialty_name" value="{{ old('specialty_name') }}" required class="form-control" placeholder="e.g. Sports Therapy">
                        @error('specialty_name') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">License Number *</label>
                        <input type="text" name="numero_colegiado" value="{{ old('numero_colegiado') }}" required class="form-control" placeholder="e.g. PT-12345">
                        @error('numero_colegiado') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Schedule Start</label>
                        <input type="time" name="horario_inicio" value="{{ old('horario_inicio') }}" class="form-control">
                        @error('horario_inicio') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Schedule End</label>
                        <input type="time" name="horario_fin" value="{{ old('horario_fin') }}" class="form-control">
                        @error('horario_fin') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div style="display:flex; gap:1rem;">
            <button type="submit" class="btn btn-primary" style="padding:.75rem 2rem; font-size:14px;">✅ Create Physiotherapist</button>
            <a href="{{ route('admin.doctors.index') }}" class="btn btn-ghost" style="padding:.75rem 2rem; font-size:14px;">Cancel</a>
        </div>

    </form>
</div>
@endsection
