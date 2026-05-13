<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment - FisioCare Ayla</title>
    <link rel="stylesheet" href="{{ asset('assets/css/home.css') }}">
    <style>
        .booking-container {
            min-height: 100vh;
            background: linear-gradient(135deg, rgba(0, 102, 204, 0.05), rgba(0, 212, 170, 0.05));
            padding: 40px 20px;
            margin-top: 60px;
        }

        .booking-card {
            background: var(--white);
            border-radius: 16px;
            padding: 3rem;
            box-shadow: var(--shadow-lg);
            max-width: 600px;
            margin: 0 auto;
        }

        .booking-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .booking-header h1 {
            font-family: 'Poppins', sans-serif;
            font-size: 32px;
            font-weight: 700;
            color: #126077;
        }

        .booking-header p {
            color: var(--gray-text);
            font-size: 16px;
        }

        .form-grid {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1.5rem;
        }

        .form-group {
            width: 100%;
            max-width: 350px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .form-group label,
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            text-align: center;
        }

        .form-actions {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1rem;
            margin-top: 2.5rem;
        }

        .form-group {
            margin-bottom: 0;
        }

        label {
            display: block;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 0.75rem;
            font-size: 14px;
        }

        input[type="date"],
        input[type="time"],
        select,
        textarea {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid var(--gray-medium);
            border-radius: 10px;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            transition: all 0.3s ease;
            background: var(--white);
        }

        input[type="date"]:focus,
        input[type="time"]:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(0, 102, 204, 0.1);
        }

        select {
            cursor: pointer;
        }

        textarea {
            resize: vertical;
            min-height: 100px;
        }

        .error {
            color: #ef4444;
            font-size: 13px;
            margin-top: 0.5rem;
            display: block;
        }

        .alert {
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 2rem;
            font-size: 14px;
        }

        .alert-danger {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .alert ul {
            margin: 0;
            padding-left: 20px;
        }

        .alert ul li {
            margin-bottom: 0.25rem;
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            margin-top: 2.5rem;
        }

        .btn-submit {
            flex: 1;
            padding: 14px;
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            color: var(--white);
            border: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: 'Poppins', sans-serif;
            box-shadow: 0 8px 20px rgba(0, 102, 204, 0.3);
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(0, 102, 204, 0.4);
        }

        .btn-cancel {
            flex: 1;
            padding: 14px;
            background: transparent;
            color: var(--primary);
            border: 2px solid var(--primary);
            border-radius: 10px;
            font-weight: 600;
            font-size: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: 'Poppins', sans-serif;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-cancel:hover {
            background: var(--primary);
            color: var(--white);
        }

        @media (max-width: 640px) {
            .booking-card {
                padding: 2rem;
            }

            .booking-header h1 {
                font-size: 24px;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .form-actions {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
   @include('layouts.navbar_interno')
    <div class="booking-container">
        <div class="booking-card">
            <div class="booking-header">
                <h1>Book Appointment</h1>
                <p>Book your physiotherapy consultation at FisioCare Ayla</p>
            </div>

            @if (session('errors') && session('errors')->any())
            <div class="alert alert-danger">
                <strong>Please correct the following errors:</strong>
                <ul>
                    @foreach (session('errors')->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('appointments.store') }}" method="POST">
                @csrf
                <!-- SECCIÓN: DATOS DE LA CITA -->
                <div class="form-section">
                    <div class="section-title">Appointment Data</div>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="patient_id">Patient *</label>
                            @if(Auth::user() && Auth::user()->role === 'patient')
                                <input type="text" value="{{ Auth::user()->name }}" class="form-control" disabled>
                                <input type="hidden" name="patient_id" value="{{ Auth::user()->patient->id }}">
                            @else
                                <select id="patient_id" name="patient_id" required>
                                    <option value="">Select a patient</option>
                                    @foreach ($patients as $patient)
                                    <option value="{{ $patient->id }}" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                                        {{ $patient->user->name ?? 'N/A' }}
                                    </option>
                                    @endforeach
                                </select>
                            @endif
                            @error('patient_id')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="physiotherapist_id">Physiotherapist *</label>
                            <select id="physiotherapist_id" name="physiotherapist_id" required>
                                <option value="">Select a physiotherapist</option>
                                @foreach ($physiotherapists as $physiotherapist)
                                <option value="{{ $physiotherapist->id }}" {{ old('physiotherapist_id') == $physiotherapist->id ? 'selected' : '' }}>
                                    {{ $physiotherapist->user->name ?? 'N/A' }}
                                </option>
                                @endforeach
                            </select>
                            @error('physiotherapist_id')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <!-- SECCIÓN: FECHA Y HORA -->
                <div class="form-section">
                    <div class="section-title">Date and Time</div>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="fecha_cita">Date *</label>
                            <input type="date" id="fecha_cita" name="fecha_cita" value="{{ old('fecha_cita') }}" required>
                            @error('fecha_cita')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="hora_cita">Time *</label>
                            <input type="time" id="hora_cita" name="hora_cita" value="{{ old('hora_cita') }}" required>
                            @error('hora_cita')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <!-- SECCIÓN: MOTIVO -->
                <div class="form-section">
                    <div class="section-title">Reason for Appointment</div>
                    <div class="form-grid full">
                        <div class="form-group">
                            <label for="reason">Reason *</label>
                            <textarea id="reason" name="reason" required placeholder="Briefly describe the reason for your appointment...">{{ old('reason') }}</textarea>
                            @error('reason')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <!-- SECCIÓN: ESTADO -->
                <div class="form-section">
                    <div class="section-title">{{ __('messages.status') }}</div>
                    <div class="form-grid full">
                        <div class="form-group">
                            <select id="status" name="status" required>
                                <option value="pendiente" {{ old('status', 'pendiente') == 'pendiente' ? 'selected' : '' }}>{{ __('messages.pending') }}</option>
                                <option value="completada" {{ old('status') == 'completada' ? 'selected' : '' }}>{{ __('messages.completed') }}</option>
                                <option value="cancelada" {{ old('status') == 'cancelada' ? 'selected' : '' }}>{{ __('messages.cancelled') }}</option>
                            </select>
                            @error('status')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn-submit">Confirm Appointment</button>
                    <a href="{{ route('appointments.index') }}" class="btn-cancel">Cancel</a>
                </div>
            </form>
