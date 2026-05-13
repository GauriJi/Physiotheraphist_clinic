<?php

namespace App\Http\Controllers;

use App\Models\Specialty;
use App\Models\Physiotherapist;
use App\Models\CitaPublica;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CitasPublicasController extends Controller
{
    /**
     * Mostrar formulario de agendamiento público
     */
    public function create()
    {
        $specialties = Specialty::all();
        $physiotherapists = Physiotherapist::with('specialty')->get();
        return view('appointments.agendar-publico', compact('specialties', 'physiotherapists'));
    }

    /**
     * Save nueva appointment pública
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_card' => 'required|string|max:20',
            'names' => 'required|string|max:255',
            'last_names' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'specialty_id' => 'required|exists:specialties,id',
            'physiotherapist_id' => 'required|exists:physiotherapists,id',
            'fecha_cita' => 'required|date|after_or_equal:today',
            'hora_cita' => 'required|date_format:H:i',
            'reason' => 'required|string|max:500',
        ], [
            'id_card.required' => 'La cédula es obligatoria',
            'names.required' => 'El name es obligatorio',
            'last_names.required' => 'El last_name es obligatorio',
            'email.required' => 'El email es obligatorio',
            'email.email' => 'El email debe ser válido',
            'phone.required' => 'El teléfono es obligatorio',
            'specialty_id.required' => 'Selecciona una specialty',
            'physiotherapist_id.required' => 'Selecciona un physiotherapist',
            'fecha_cita.required' => 'La date es obligatoria',
            'fecha_cita.after_or_equal' => 'La date no puede ser en el pasado',
            'hora_cita.required' => 'La time es obligatoria',
            'hora_cita.date_format' => 'El formato de time debe ser HH:MM',
            'reason.required' => 'Describe el reason de tu appointment',
        ]);

        // Check for double booking
        $exists = CitaPublica::where('physiotherapist_id', $validated['physiotherapist_id'])
            ->where('fecha_cita', $validated['fecha_cita'])
            ->where('hora_cita', $validated['hora_cita'])
            ->where('status', '!=', 'cancelada')
            ->exists();

        if ($exists) {
            return back()->with('error', 'El doctor ya tiene una cita programada a esa fecha y hora. Por favor selecciona otra.')->withInput();
        }

        // Check availability
        $dayOfWeek = date('l', strtotime($validated['fecha_cita']));
        $spanishDay = \App\Http\Controllers\HorarioController::DAYS[$dayOfWeek];
        $schedule = \App\Models\Schedule::where('physiotherapist_id', $validated['physiotherapist_id'])
            ->where('dia', $spanishDay)
            ->first();

        if (!$schedule || !$schedule->disponible) {
            return back()->with('error', 'El doctor seleccionado no está disponible en este día.')->withInput();
        }

        $start = substr($schedule->hora_inicio, 0, 5);
        $end = substr($schedule->hora_fin, 0, 5);
        $time = substr($validated['hora_cita'], 0, 5);

        if ($time < $start || $time > $end) {
            return back()->with('error', "La hora seleccionada está fuera del horario disponible del doctor ($start - $end).")->withInput();
        }

        try {
            // Create appointment pública
            $appointment = CitaPublica::create(array_merge($validated, ['status' => 'pendiente']));

            // Enviar email de confirmación
            $this->enviarCorreoConfirmacion($appointment);

            return redirect('/')->with('success', 'Appointment agendada exitosamente. Revisa tu email para la confirmación.');
        } catch (\Exception $e) {
            Log::error('Error al agendar appointment: ' . $e->getMessage());
            return back()->with('error', 'Error al agendar la appointment. Por favor intenta de nuevo.')->withInput();
        }
    }

    /**
     * Obtener physiotherapists de una specialty (AJAX)
     */
    public function obtenerFisioterapeutas($especialidadId)
    {
        $time = request('time');
        $date = request('date');
        $physiotherapists = Physiotherapist::where('specialty_id', $especialidadId)
            ->when($time, function ($query) use ($time) {
                $query->where('horario_inicio', '<=', $time)
                      ->where('horario_fin', '>=', $time);
            })
            ->when($time && $date, function($query) use ($time, $date) {
                $query->whereDoesntHave('citasPublicas', function($q) use ($time, $date) {
                     $q->where('fecha_cita', $date)
                       ->where('hora_cita', $time)
                       ->where('status', '!=', 'cancelada');
                });
            })
            ->select('id', 'name', 'last_name', 'specialty_id', 'horario_inicio', 'horario_fin')
            ->get();
        return response()->json($physiotherapists);
    }

    /**
     * Enviar email de confirmación
     */
    private function enviarCorreoConfirmacion($appointment)
    {
        $appointment->load('specialty', 'physiotherapist');

        $asunto = 'Confirmación de Appointment - FisioCare Ayla';
        $fecha_formateada = date('d/m/Y', strtotime($appointment->fecha_cita));

        $mensaje = "Estimado/a {$appointment->names} {$appointment->last_names},\n\n";
        $mensaje .= "Su appointment ha sido agendada exitosamente en FisioCare Ayla.\n\n";
        $mensaje .= "=== DETALLES DE LA CITA ===\n";
        $mensaje .= "ID Card: {$appointment->id_card}\n";
        $mensaje .= "Teléfono: {$appointment->phone}\n";
        $mensaje .= "Correo: {$appointment->email}\n\n";
        $mensaje .= "Physiotherapist: {$appointment->physiotherapist->name} {$appointment->physiotherapist->last_name}\n";
        $mensaje .= "Specialty: {$appointment->specialty->name}\n";
        $mensaje .= "Fecha: {$fecha_formateada}\n";
        $mensaje .= "Hora: {$appointment->hora_cita}\n";
        $mensaje .= "Motivo: {$appointment->reason}\n\n";
        $mensaje .= "Estado: Pendiente de Confirmación\n\n";
        $mensaje .= "=== IMPORTANTE ===\n";
        $mensaje .= "- Por favor, llegar 10 minutos antes de la appointment.\n";
        $mensaje .= "- Si necesitas cancelar o reprogramar, contacta a la clínica lo antes posible.\n\n";
        $mensaje .= "Teléfono de la clínica: +58 412-123-4567\n";
        $mensaje .= "Correo: info@fisiocarealya.com\n\n";
        $mensaje .= "¡Gracias por confiar en FisioCare Ayla!\n";
        $mensaje .= "Clínica de Fisioterapia\n";

        try {
            $headers = "From: noreply@fisiocarealya.com\r\n";
            $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
            $headers .= "X-Mailer: PHP/" . phpversion();

            mail($appointment->email, $asunto, $mensaje, $headers);
            Log::info('Correo de confirmación enviado a: ' . $appointment->email);
        } catch (\Exception $e) {
            Log::error('Error al enviar email: ' . $e->getMessage());
        }
    }
}
