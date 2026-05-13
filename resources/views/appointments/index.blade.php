@extends('layouts.app')
@include('layouts.navbar_interno')
@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>Appointments</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('appointments.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Nueva Appointment
            </a>
        </div>
    </div>

    @if ($message = Session::get('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ $message }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Patient</th>
                    <th>Physiotherapist</th>
                    <th>{{ __('messages.date') }}</th>
                    <th>{{ __('messages.time') }}</th>
                    <th>{{ __('messages.status') }}</th>
                    <th>{{ __('messages.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($appointments as $appointment)
                <tr>
                    <td>{{ $appointment->id }}</td>
                    <td>{{ $appointment->patient->user->name ?? 'N/A' }}</td>
                    <td>{{ $appointment->physiotherapist->user->name ?? 'N/A' }}</td>
                    <td>{{ $appointment->fecha_cita }}</td>
                    <td>{{ $appointment->hora_cita }}</td>
                    <td>
                        @if ($appointment->status == 'pendiente')
                            <span class="badge bg-warning">{{ __('messages.pending') }}</span>
                        @elseif ($appointment->status == 'completada')
                            <span class="badge bg-success">{{ __('messages.completed') }}</span>
                        @else
                            <span class="badge bg-danger">{{ __('messages.cancelled') }}</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('appointments.show', $appointment->id) }}" class="btn btn-sm btn-info">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('appointments.edit', $appointment->id) }}" class="btn btn-sm btn-warning">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('appointments.destroy', $appointment->id) }}" method="POST" class="d-inline delete-appointment-form">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-sm btn-danger btn-cancel-appointment" data-appointment-id="{{ $appointment->id }}">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    @section('scripts')
                    <div class="modal fade" id="modalCancelarCita" tabindex="-1" aria-labelledby="modalCancelarCitaLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalCancelarCitaLabel">Confirm cancelación</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label=__('messages.close')></button>
                                </div>
                                <div class="modal-body">
                                    ¿Estás seguro de que deseas cancelar esta appointment?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No, volver</button>
                                    <button type="button" class="btn btn-danger" id="confirmCancelCita">Sí, cancelar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <script>
                    let citaFormToDelete = null;
                    document.querySelectorAll('.btn-cancel-appointment').forEach(btn => {
                        btn.addEventListener('click', function(e) {
                            e.preventDefault();
                            citaFormToDelete = this.closest('form');
                            var modal = new bootstrap.Modal(document.getElementById('modalCancelarCita'));
                            modal.show();
                        });
                    });
                    document.getElementById('confirmCancelCita').addEventListener('click', function() {
                        if (citaFormToDelete) citaFormToDelete.submit();
                    });
                    </script>
                    @endsection
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">No hay appointments registradas</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center">
        {{ $appointments->links() }}
    </div>
</div>
@endsection
