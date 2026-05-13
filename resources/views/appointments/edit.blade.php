@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <h1 class="mb-4">Edit Appointment</h1>

            @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Errores:</strong>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('appointments.update', $appointment->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="patient_id" class="form-label">Patient</label>
                            <select class="form-select @error('patient_id') is-invalid @enderror" id="patient_id" name="patient_id" required>
                                <option value="">Seleccione un patient</option>
                                @foreach ($patients as $patient)
                                <option value="{{ $patient->id }}" {{ $appointment->patient_id == $patient->id ? 'selected' : '' }}>
                                    {{ $patient->user->name ?? 'N/A' }}
                                </option>
                                @endforeach
                            </select>
                            @error('patient_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="physiotherapist_id" class="form-label">Physiotherapist</label>
                            <select class="form-select @error('physiotherapist_id') is-invalid @enderror" id="physiotherapist_id" name="physiotherapist_id" required>
                                <option value="">Seleccione un physiotherapist</option>
                                @foreach ($physiotherapists as $physiotherapist)
                                <option value="{{ $physiotherapist->id }}" {{ $appointment->physiotherapist_id == $physiotherapist->id ? 'selected' : '' }}>
                                    {{ $physiotherapist->user->name ?? 'N/A' }}
                                </option>
                                @endforeach
                            </select>
                            @error('physiotherapist_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="fecha_cita" class="form-label">{{ __('messages.date') }}</label>
                            <input type="date" class="form-control @error('fecha_cita') is-invalid @enderror"
                                id="fecha_cita" name="fecha_cita" value="{{ $appointment->fecha_cita }}" required>
                            @error('fecha_cita')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="hora_cita" class="form-label">{{ __('messages.time') }}</label>
                            <input type="time" class="form-control @error('hora_cita') is-invalid @enderror"
                                id="hora_cita" name="hora_cita" value="{{ $appointment->hora_cita }}" required>
                            @error('hora_cita')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="reason" class="form-label">Motivo</label>
                            <textarea class="form-control @error('reason') is-invalid @enderror"
                                id="reason" name="reason" rows="3" required>{{ $appointment->reason }}</textarea>
                            @error('reason')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">{{ __('messages.status') }}</label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="">Seleccione</option>
                                <option value="pendiente" {{ $appointment->status == 'pendiente' ? 'selected' : '' }}>{{ __('messages.pending') }}</option>
                                <option value="completada" {{ $appointment->status == 'completada' ? 'selected' : '' }}>{{ __('messages.completed') }}</option>
                                <option value="cancelada" {{ $appointment->status == 'cancelada' ? 'selected' : '' }}>{{ __('messages.cancelled') }}</option>
                            </select>
                            @error('status')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success">Update</button>
                            <a href="{{ route('appointments.show', $appointment->id) }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
