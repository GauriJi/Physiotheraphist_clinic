@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <h1 class="mb-4">Edit History Clínico</h1>

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
                    <form action="{{ route('histories.update', $history->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="patient_id" class="form-label">Patient</label>
                            <select class="form-select @error('patient_id') is-invalid @enderror" id="patient_id" name="patient_id" required>
                                <option value="">Seleccione un patient</option>
                                @foreach ($patients as $patient)
                                <option value="{{ $patient->id }}" {{ $history->patient_id == $patient->id ? 'selected' : '' }}>
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
                                <option value="{{ $physiotherapist->id }}" {{ $history->physiotherapist_id == $physiotherapist->id ? 'selected' : '' }}>
                                    {{ $physiotherapist->user->name ?? 'N/A' }}
                                </option>
                                @endforeach
                            </select>
                            @error('physiotherapist_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="descripcion" class="form-label">{{ __('messages.description') }}</label>
                            <textarea class="form-control @error('descripcion') is-invalid @enderror"
                                id="descripcion" name="descripcion" rows="3" required>{{ $history->descripcion }}</textarea>
                            @error('descripcion')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="diagnostico" class="form-label">{{ __('messages.diagnosis') }}</label>
                            <textarea class="form-control @error('diagnostico') is-invalid @enderror"
                                id="diagnostico" name="diagnostico" rows="3" required>{{ $history->diagnostico }}</textarea>
                            @error('diagnostico')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="tratamiento" class="form-label">{{ __('messages.treatment') }}</label>
                            <textarea class="form-control @error('tratamiento') is-invalid @enderror"
                                id="tratamiento" name="tratamiento" rows="3" required>{{ $history->tratamiento }}</textarea>
                            @error('tratamiento')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success">Update</button>
                            <a href="{{ route('histories.show', $history->id) }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
