<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;

class HorarioController extends Controller
{
    // English day names (keys) mapped to Spanish DB values
    const DAYS = [
        'Monday'    => 'lunes',
        'Tuesday'   => 'martes',
        'Wednesday' => 'miercoles',
        'Thursday'  => 'jueves',
        'Friday'    => 'viernes',
        'Saturday'  => 'sabado',
        'Sunday'    => 'domingo',
    ];

    public function miHorario()
    {
        $user = auth()->user();

        if (!$user->physiotherapist) {
            return redirect()->route('dashboard')
                ->with('error', 'Your user account is not linked to a physiotherapist profile. Please ask an administrator to link your account.');
        }

        $fisioId = $user->physiotherapist->id;

        // Load all schedules for this doctor
        $rawSchedules = Schedule::where('physiotherapist_id', $fisioId)->get()->keyBy('dia');

        // Build an English-keyed map
        $scheduleByDay = [];
        foreach (self::DAYS as $english => $spanish) {
            $scheduleByDay[$english] = $rawSchedules->get($spanish);
        }

        return view('doctor.schedule', compact('scheduleByDay'));
    }

    public function actualizarMiHorario(Request $request)
    {
        $user = auth()->user();

        if (!$user->physiotherapist) {
            return redirect()->route('dashboard')
                ->with('error', 'Your user account is not linked to a physiotherapist profile. Please ask an administrator to link your account.');
        }

        $fisioId = $user->physiotherapist->id;

        foreach (self::DAYS as $english => $spanish) {
            $isAvailable = $request->has($english . '_available');
            $hora_inicio  = $request->input($english . '_start');
            $hora_fin     = $request->input($english . '_end');

            Schedule::updateOrCreate(
                [
                    'physiotherapist_id' => $fisioId,
                    'dia'                => $spanish,
                ],
                [
                    'disponible'  => $isAvailable,
                    'hora_inicio' => $isAvailable ? $hora_inicio : null,
                    'hora_fin'    => $isAvailable ? $hora_fin    : null,
                ]
            );
        }

        return redirect()
            ->route('doctor.mi-schedule')
            ->with('success', 'Your schedule has been updated successfully.');
    }

    public function checkAvailability(Request $request, $id)
    {
        $date = $request->query('date');
        if (!$date) {
            return response()->json(['error' => 'Date is required'], 400);
        }

        // Get English day from date
        $dayOfWeek = date('l', strtotime($date));
        // Get Spanish mapping
        $spanishDay = self::DAYS[$dayOfWeek] ?? null;

        if (!$spanishDay) {
            return response()->json(['error' => 'Invalid date'], 400);
        }

        $schedule = Schedule::where('physiotherapist_id', $id)
            ->where('dia', $spanishDay)
            ->first();

        if (!$schedule || !$schedule->disponible) {
            return response()->json([
                'available' => false,
                'message' => 'The doctor is not available on this day.'
            ]);
        }

        return response()->json([
            'available' => true,
            'start' => substr($schedule->hora_inicio, 0, 5),
            'end' => substr($schedule->hora_fin, 0, 5)
        ]);
    }
}
