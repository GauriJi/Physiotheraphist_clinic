@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>Histories Clínicos</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('histories.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Nuevo History
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
                    <th>{{ __('messages.diagnosis') }}</th>
                    <th>{{ __('messages.date') }}</th>
                    <th>{{ __('messages.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($histories as $history)
                <tr>
                    <td>{{ $history->id }}</td>
                    <td>{{ $history->patient->user->name ?? 'N/A' }}</td>
                    <td>{{ $history->physiotherapist->user->name ?? 'N/A' }}</td>
                    <td>{{ Str::limit($history->diagnostico, 50) }}</td>
                    <td>{{ $history->created_at->format('d/m/Y') }}</td>
                    <td>
                        <a href="{{ route('histories.show', $history->id) }}" class="btn btn-sm btn-info">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('histories.edit', $history->id) }}" class="btn btn-sm btn-warning">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('histories.destroy', $history->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Está seguro?')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">No hay histories registrados</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center">
        {{ $histories->links() }}
    </div>
</div>
@endsection
