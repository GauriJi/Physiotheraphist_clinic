<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Physiotherapist;
use Illuminate\Http\Request;

class CitaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $appointments = Appointment::with('patient', 'physiotherapist')->paginate(10);
        return view('appointments.index', compact('appointments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $patients = Patient::all();
        $physiotherapists = Physiotherapist::all();
        return view('appointments.create', compact('patients', 'physiotherapists'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'physiotherapist_id' => 'required|exists:physiotherapists,id',
            'fecha_cita' => 'required|date',
            'hora_cita' => 'required|date_format:H:i',
            'reason' => 'required|string',
            'status' => 'required|in:pendiente,completada,cancelada'
        ]);

        Appointment::create($validated);
        return redirect()->route('appointments.index')->with('success', 'Appointment created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Appointment $appointment)
    {
        $appointment->load('patient', 'physiotherapist');
        return view('appointments.show', compact('appointment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Appointment $appointment)
    {
        $patients = Patient::all();
        $physiotherapists = Physiotherapist::all();
        return view('appointments.edit', compact('appointment', 'patients', 'physiotherapists'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'physiotherapist_id' => 'required|exists:physiotherapists,id',
            'fecha_cita' => 'required|date',
            'hora_cita' => 'required|date_format:H:i',
            'reason' => 'required|string',
            'status' => 'required|in:pendiente,completada,cancelada'
        ]);

        $appointment->update($validated);
        return redirect()->route('appointments.show', $appointment)->with('success', 'Appointment updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appointment $appointment)
    {
        $appointment->delete();
        return redirect()->route('appointments.index')->with('success', 'Appointment deleted successfully');
    }

    /**
     * MÉTODOS PARA ADMINISTRADOR
     */

    /**
     * Listar todas las appointments (admin)
     */
    public function indexAdmin(Request $request)
    {
        $query = \App\Models\CitaPublica::with('physiotherapist', 'specialty');

        // Filtrar por status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filtrar por date
        if ($request->has('date') && $request->date) {
            $query->whereDate('fecha_cita', $request->date);
        }

        $appointments = $query->orderBy('fecha_cita', 'desc')->paginate(20);

        return view('admin.appointments.index', compact('appointments'));
    }

    /**
     * Confirm appointment (admin)
     */
    public function confirmarCitaAdmin($id)
    {
        $appointment = \App\Models\CitaPublica::findOrFail($id);
        $appointment->update(['status' => 'confirmada']);

        return back()->with('success', 'Appointment confirmed successfully');
    }

    /**
     * Cancel appointment (admin)
     */
    public function cancelarCitaAdmin($id)
    {
        $appointment = \App\Models\CitaPublica::findOrFail($id);
        $appointment->update(['status' => 'cancelada']);

        return back()->with('success', 'Appointment cancelled successfully');
    }

    /**
     * Create appointment (admin)
     */
    public function createAdmin()
    {
        $specialties = \App\Models\Specialty::all();
        $physiotherapists = \App\Models\Physiotherapist::all();
        $patients = \App\Models\Patient::orderBy('name')->get();

        return view('admin.appointments.create', compact('specialties', 'physiotherapists', 'patients'));
    }

    /**
     * Store appointment (admin)
     */
    public function storeAdmin(Request $request)
    {
        $validated = $request->validate([
            'names'              => 'required|string|max:100',
            'last_names'         => 'required|string|max:100',
            'email'              => 'required|email',
            'phone'              => 'required|string|max:20',
            'id_card'            => 'required|string|max:50',
            'specialty_id'       => 'required|exists:specialties,id',
            'physiotherapist_id' => 'required|exists:physiotherapists,id',
            'fecha_cita'         => 'required|date',
            'hora_cita'          => 'required|date_format:H:i',
            'reason'             => 'required|string|max:500',
        ]);

        // Check for double booking
        $exists = \App\Models\CitaPublica::where('physiotherapist_id', $validated['physiotherapist_id'])
            ->where('fecha_cita', $validated['fecha_cita'])
            ->where('hora_cita', $validated['hora_cita'])
            ->where('status', '!=', 'cancelada')
            ->exists();

        if ($exists) {
            return back()->withErrors(['hora_cita' => 'The selected doctor already has an appointment at this date and time.'])->withInput();
        }

        // Check availability
        $dayOfWeek = date('l', strtotime($validated['fecha_cita']));
        $spanishDay = \App\Http\Controllers\HorarioController::DAYS[$dayOfWeek];
        $schedule = \App\Models\Schedule::where('physiotherapist_id', $validated['physiotherapist_id'])
            ->where('dia', $spanishDay)
            ->first();

        if (!$schedule || !$schedule->disponible) {
            return back()->withErrors(['hora_cita' => 'The selected doctor is not available on this day.'])->withInput();
        }

        $start = substr($schedule->hora_inicio, 0, 5);
        $end = substr($schedule->hora_fin, 0, 5);
        $time = substr($validated['hora_cita'], 0, 5);

        if ($time < $start || $time > $end) {
            return back()->withErrors(['hora_cita' => "The selected time is outside the doctor's available hours ($start - $end)."])->withInput();
        }

        $validated['status'] = 'confirmada'; // Auto-confirm when admin creates it

        \App\Models\CitaPublica::create($validated);

        return redirect()->route('admin.appointments.index')
            ->with('success', 'Appointment successfully scheduled.');
    }

    /**
     * Show the form for editing the appointment (reschedule).
     */
    public function editCitaAdmin($id)
    {
        $appointment = \App\Models\CitaPublica::findOrFail($id);
        $specialties = \App\Models\Specialty::all();
        $physiotherapists = \App\Models\Physiotherapist::all();

        return view('admin.appointments.edit', compact('appointment', 'specialties', 'physiotherapists'));
    }

    /**
     * Update the appointment (reschedule).
     */
    public function updateCitaAdmin(Request $request, $id)
    {
        $appointment = \App\Models\CitaPublica::findOrFail($id);

        $validated = $request->validate([
            'specialty_id'       => 'required|exists:specialties,id',
            'physiotherapist_id' => 'required|exists:physiotherapists,id',
            'fecha_cita'         => 'required|date',
            'hora_cita'          => 'required|date_format:H:i',
        ]);

        // Check for double booking (excluding the current appointment)
        $exists = \App\Models\CitaPublica::where('physiotherapist_id', $validated['physiotherapist_id'])
            ->where('fecha_cita', $validated['fecha_cita'])
            ->where('hora_cita', $validated['hora_cita'])
            ->where('id', '!=', $appointment->id)
            ->where('status', '!=', 'cancelada')
            ->exists();

        if ($exists) {
            return back()->withErrors(['hora_cita' => 'The selected doctor already has an appointment at this date and time.'])->withInput();
        }

        // Check availability
        $dayOfWeek = date('l', strtotime($validated['fecha_cita']));
        $spanishDay = \App\Http\Controllers\HorarioController::DAYS[$dayOfWeek];
        $schedule = \App\Models\Schedule::where('physiotherapist_id', $validated['physiotherapist_id'])
            ->where('dia', $spanishDay)
            ->first();

        if (!$schedule || !$schedule->disponible) {
            return back()->withErrors(['hora_cita' => 'The selected doctor is not available on this day.'])->withInput();
        }

        $start = substr($schedule->hora_inicio, 0, 5);
        $end = substr($schedule->hora_fin, 0, 5);
        $time = substr($validated['hora_cita'], 0, 5);

        if ($time < $start || $time > $end) {
            return back()->withErrors(['hora_cita' => "The selected time is outside the doctor's available hours ($start - $end)."])->withInput();
        }

        $appointment->update($validated);

        // Try to detect where the request came from to redirect back
        if(str_contains(url()->previous(), 'doctor')) {
            return redirect()->route('doctor.appointments-hoy')->with('success', 'Appointment rescheduled successfully.');
        }

        return redirect()->route('admin.appointments.index')->with('success', 'Appointment rescheduled successfully.');
    }

    /**
     * Delete appointment completely.
     */
    public function destroyCitaAdmin($id)
    {
        $appointment = \App\Models\CitaPublica::findOrFail($id);
        $appointment->delete();

        return back()->with('success', 'Appointment deleted permanently.');
    }
}
