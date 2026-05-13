<?php

namespace App\Http\Controllers;

use App\Models\Physiotherapist;
use App\Models\User;
use App\Models\Specialty;
use App\Models\CitaPublica;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class FisioterapeutaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $physiotherapists = Physiotherapist::with('user', 'specialty')->paginate(10);
        return view('physiotherapists.index', compact('physiotherapists'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all();
        $specialties = Specialty::all();
        return view('physiotherapists.create', compact('users', 'specialties'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'specialty_id' => 'required|exists:specialties,id',
            'numero_colegiatura' => 'required|string|unique:physiotherapists',
            'horario_inicio' => 'required|date_format:H:i',
            'horario_fin' => 'required|date_format:H:i|after:horario_inicio',
        ]);

        Physiotherapist::create($validated);
        return redirect()->route('physiotherapists.index')->with('success', 'Physiotherapist created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Physiotherapist $physiotherapist)
    {
        $physiotherapist->load('user', 'specialty', 'appointments');
        return view('physiotherapists.show', compact('physiotherapist'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Physiotherapist $physiotherapist)
    {
        $users = User::all();
        $specialties = Specialty::all();
        return view('physiotherapists.edit', compact('physiotherapist', 'users', 'specialties'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Physiotherapist $physiotherapist)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'specialty_id' => 'required|exists:specialties,id',
            'numero_colegiatura' => 'required|string|unique:physiotherapists,numero_colegiatura,' . $physiotherapist->id,
            'horario_inicio' => 'required|date_format:H:i',
            'horario_fin' => 'required|date_format:H:i|after:horario_inicio',
        ]);

        $physiotherapist->update($validated);
        return redirect()->route('physiotherapists.show', $physiotherapist)->with('success', 'Physiotherapist updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Physiotherapist $physiotherapist)
    {
        $physiotherapist->delete();
        return redirect()->route('physiotherapists.index')->with('success', 'Physiotherapist deleted successfully');
    }

    /**
     * MÉTODOS PARA MÉDICOS
     */

    /**
     * Mostrar appointments de hoy del médico
     */
    public function citasHoy()
    {
        $user = Auth::user();
        $physiotherapist = Physiotherapist::where('email', $user->email)->first();

        if (!$physiotherapist) {
            return redirect('/dashboard')->with('error', 'Doctor profile not found');
        }

        $citasHoy = CitaPublica::where('physiotherapist_id', $physiotherapist->id)
            ->whereDate('fecha_cita', today())
            ->orderBy('hora_cita')
            ->get();

        $estadisticas = [
            'proximas' => CitaPublica::where('physiotherapist_id', $physiotherapist->id)
                ->where('fecha_cita', '>', today())
                ->count(),
            'pacientes_unicos' => CitaPublica::where('physiotherapist_id', $physiotherapist->id)
                ->distinct('email')
                ->count('email'),
        ];

        return view('doctor.appointments-hoy', compact('citasHoy', 'estadisticas'));
    }

    /**
     * Mostrar todas las appointments del médico
     */
    public function misCitas(Request $request)
    {
        $user = Auth::user();
        $physiotherapist = Physiotherapist::where('email', $user->email)->first();

        if (!$physiotherapist) {
            return redirect('/dashboard')->with('error', 'Doctor profile not found');
        }

        $query = CitaPublica::where('physiotherapist_id', $physiotherapist->id);

        // Filtrar por status si se proporciona
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Ordenar
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'date-asc':
                    $query->orderBy('fecha_cita', 'asc');
                    break;
                case 'time':
                    $query->orderBy('hora_cita');
                    break;
                default:
                    $query->orderBy('fecha_cita', 'desc');
            }
        } else {
            $query->orderBy('fecha_cita', 'desc');
        }

        $appointments = $query->paginate(15);

        return view('doctor.mis-appointments', compact('appointments'));
    }

    /**
     * Mostrar mis patients
     */
    public function misPacientes()
    {
        $user = Auth::user();
        $physiotherapist = Physiotherapist::where('email', $user->email)->first();

        if (!$physiotherapist) {
            return redirect('/dashboard')->with('error', 'Doctor profile not found');
        }


        // Obtener patients únicos
        $citasPacientes = CitaPublica::where('physiotherapist_id', $physiotherapist->id)
            ->selectRaw('DISTINCT email, names, last_names, phone')
            ->get();

        $patients = $citasPacientes->map(function($appointment) use ($physiotherapist) {
            $totalCitas = CitaPublica::where('physiotherapist_id', $physiotherapist->id)
                ->where('email', $appointment->email)
                ->count();

            $citasCompletadas = CitaPublica::where('physiotherapist_id', $physiotherapist->id)
                ->where('email', $appointment->email)
                ->where('status', 'confirmada')
                ->count();

            $citasProximas = CitaPublica::where('physiotherapist_id', $physiotherapist->id)
                ->where('email', $appointment->email)
                ->where('fecha_cita', '>=', today())
                ->where('status', '!=', 'cancelada')
                ->count();

            // Buscar el modelo Patient por email
            $pacienteModel = \App\Models\Patient::where('email', $appointment->email)->first();
            // Buscar el history clínico más reciente de ese patient (si existe)
            $history = $pacienteModel ? $pacienteModel->histories()->latest()->first() : null;

            return [
                'id' => $pacienteModel ? $pacienteModel->id : null,
                'name' => $appointment->names,
                'last_name' => $appointment->last_names,
                'email' => $appointment->email,
                'phone' => $appointment->phone,
                'citas_totales' => $totalCitas,
                'citas_completadas' => $citasCompletadas,
                'citas_proximas' => $citasProximas,
                'history_id' => $history ? $history->id : null,
            ];
        })->values();

        $estadisticas = [
            'total_pacientes' => $patients->count(),
            'citas_completadas' => CitaPublica::where('physiotherapist_id', $physiotherapist->id)
                ->where('status', 'confirmada')
                ->count(),
            'citas_proximas' => CitaPublica::where('physiotherapist_id', $physiotherapist->id)
                ->where('fecha_cita', '>=', today())
                ->count(),
        ];

        return view('doctor.mis-patients', compact('patients', 'estadisticas'));
    }

    /**
     * Confirm una appointment
     */
    public function confirmarCita($id)
    {
        $appointment = CitaPublica::findOrFail($id);
        $appointment->update(['status' => 'confirmada']);

        return back()->with('success', 'Appointment confirmed successfully');
    }

    /**
     * Agregar nota a una appointment
     */
    public function agregarNota(Request $request, $id)
    {
        $appointment = CitaPublica::findOrFail($id);
        $validated = $request->validate([
            'nota' => 'required|string|min:10',
        ]);

        $appointment->update([
            'doctor_notes' => $validated['nota'],
        ]);

        return back()->with('success', 'Note added successfully');
    }

    /**
     * MÉTODOS DE PERFIL CLÍNICO Y PRESCRIPCIONES
     */
    public function verPaciente($id)
    {
        $user = Auth::user();
        $physiotherapist = Physiotherapist::where('email', $user->email)->first();

        if (!$physiotherapist) {
            return redirect('/dashboard')->with('error', 'Doctor profile not found');
        }

        $patient = \App\Models\Patient::with(['histories', 'documents'])->findOrFail($id);

        // Get past appointments with this doctor
        $appointments = CitaPublica::where('email', $patient->email)
            ->where('physiotherapist_id', $physiotherapist->id)
            ->orderBy('fecha_cita', 'desc')
            ->get();

        // Get prescriptions / notes by this doctor for this patient
        $prescriptions = \App\Models\DoctorNote::where('patient_id', $patient->id)
            ->where('physiotherapist_id', $physiotherapist->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('doctor.patients.show', compact('patient', 'appointments', 'prescriptions', 'physiotherapist'));
    }

    public function storePrescription(Request $request, $id)
    {
        $user = Auth::user();
        $physiotherapist = Physiotherapist::where('email', $user->email)->first();

        $validated = $request->validate([
            'notes' => 'required|string',
            'exercises' => 'nullable|string',
            'progress' => 'nullable|string',
            'session_status' => 'required|in:improving,stable,worsening,recovered',
            'next_session' => 'nullable|string'
        ]);

        $validated['patient_id'] = $id;
        $validated['physiotherapist_id'] = $physiotherapist->id;
        $validated['created_by'] = $user->id;

        \App\Models\DoctorNote::create($validated);

        return back()->with('success', 'Prescription / Treatment Note added successfully.');
    }

    public function printPrescription($id)
    {
        $prescription = \App\Models\DoctorNote::with(['patient', 'physiotherapist'])->findOrFail($id);
        
        // Ensure the logged in doctor owns this prescription (or is admin)
        $user = Auth::user();
        if ($user->role->nombre_rol !== 'admin' && $prescription->createdBy->id !== $user->id) {
            abort(403);
        }

        return view('doctor.patients.prescription-print', compact('prescription'));
    }

    /**
     * MÉTODOS PARA ADMINISTRADOR
     */

    /**
     * Listar médicos para admin
     */
    public function indexAdmin()
    {
        $doctors = Physiotherapist::with('specialty')->paginate(10);
        return view('admin.doctors.index', compact('doctors'));
    }

    /**
     * Create nuevo médico (admin)
     */
    public function createAdmin()
    {
        $specialties = Specialty::all();
        return view('admin.doctors.create', compact('specialties'));
    }

    /**
     * Save nuevo médico (admin)
     */
    public function storeAdmin(Request $request)
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:100',
            'last_name'        => 'required|string|max:100',
            'email'            => 'required|email|unique:physiotherapists,email|unique:users,email',
            'phone'            => 'required|string|max:20',
            'specialty_name'   => 'required|string|max:100',
            'numero_colegiado' => 'required|string|unique:physiotherapists',
        ]);

        $specialty = \App\Models\Specialty::firstOrCreate(['name' => $validated['specialty_name']]);

        // Auto-create a login account for the doctor (role_id=2 = doctor)
        $user = User::create([
            'name'     => $validated['name'] . ' ' . $validated['last_name'],
            'email'    => $validated['email'],
            'password' => Hash::make('Doctor@1234'),  // default password
            'role_id'  => 2, // doctor role
        ]);

        // Create the physiotherapist and link to the user
        $validated['user_id'] = $user->id;
        $validated['specialty_id'] = $specialty->id;
        unset($validated['specialty_name']);
        Physiotherapist::create($validated);

        return redirect()->route('admin.doctors.index')
            ->with('success', 'Doctor created successfully. Login: ' . $validated['email'] . ' / Password: Doctor@1234');
    }

    /**
     * Edit médico (admin)
     */
    public function editAdmin($id)
    {
        $doctor = Physiotherapist::findOrFail($id);
        $specialties = Specialty::all();
        return view('admin.doctors.edit', compact('doctor', 'specialties'));
    }

    /**
     * Update médico (admin)
     */
    public function updateAdmin(Request $request, $id)
    {
        $doctor = Physiotherapist::findOrFail($id);
        $validated = $request->validate([
            'name'             => 'required|string|max:100',
            'last_name'        => 'required|string|max:100',
            'email'            => 'required|email|unique:physiotherapists,email,' . $id,
            'phone'            => 'required|string|max:20',
            'specialty_name'   => 'required|string|max:100',
            'numero_colegiado' => 'required|string|unique:physiotherapists,numero_colegiado,' . $id,
        ]);

        $specialty = \App\Models\Specialty::firstOrCreate(['name' => $validated['specialty_name']]);

        $validated['specialty_id'] = $specialty->id;
        unset($validated['specialty_name']);

        $doctor->update($validated);

        // Sync the linked user account name/email if it exists
        if ($doctor->user_id && $linkedUser = User::find($doctor->user_id)) {
            $linkedUser->update([
                'name'  => $validated['name'] . ' ' . $validated['last_name'],
                'email' => $validated['email'],
            ]);
        }

        return redirect()->route('admin.doctors.index')->with('success', 'Doctor updated successfully');
    }

    /**
     * Delete médico (admin)
     */
    public function destroyAdmin($id)
    {
        $doctor = Physiotherapist::findOrFail($id);
        $doctor->delete();

        return redirect()->route('admin.doctors.index')->with('success', 'Doctor deleted successfully');
    }
}
