<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\CitaPublica;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $query = Patient::orderByDesc('created_at');

        if ($search = $request->search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('last_name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
                  ->orWhere('phone', 'like', "%$search%")
                  ->orWhere('id_card', 'like', "%$search%");
            });
        }

        if ($bg = $request->blood_group) {
            $query->where('blood_group', $bg);
        }

        $patients = $query->paginate(12)->withQueryString();
        return view('admin.patients.index', compact('patients'));
    }

    public function create()
    {
        return view('admin.patients.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'              => 'required|string|max:100',
            'last_name'         => 'nullable|string|max:100',
            'email'             => 'nullable|email|unique:patients,email',
            'phone'             => 'required|string|max:20',
            'id_card'           => 'nullable|string|max:50|unique:patients,id_card',
            'fecha_nacimiento'  => 'nullable|date',
            'sexo'              => 'nullable|in:male,female,other',
            'address'           => 'nullable|string|max:255',
            'blood_group'       => 'nullable|string|max:10',
            'emergency_contact' => 'nullable|string|max:100',
            'emergency_phone'   => 'nullable|string|max:20',
            'photo'             => 'nullable|image|max:2048',
            // Therapy Plan
            'therapy_plan_name'  => 'nullable|string|max:200',
            'therapy_diagnosis'  => 'nullable|string|max:200',
            'therapy_goal'       => 'nullable|string|max:1000',
            'therapy_start_date' => 'nullable|date',
            'therapy_end_date'   => 'nullable|date|after_or_equal:therapy_start_date',
            'sessions_purchased' => 'nullable|integer|min:0',
        ]);

        // Handle photo upload
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('patients/photos', 'public');
        }

        Patient::create([
            'name'              => $request->name,
            'last_name'         => $request->last_name,
            'email'             => $request->email,
            'phone'             => $request->phone,
            'id_card'           => $request->id_card,
            'fecha_nacimiento'  => $request->fecha_nacimiento,
            'sexo'              => $request->sexo,
            'address'           => $request->address,
            'blood_group'       => $request->blood_group,
            'emergency_contact' => $request->emergency_contact,
            'emergency_phone'   => $request->emergency_phone,
            'photo'             => $photoPath,
            // Therapy Plan
            'therapy_plan_name'  => $request->therapy_plan_name,
            'therapy_diagnosis'  => $request->therapy_diagnosis,
            'therapy_goal'       => $request->therapy_goal,
            'therapy_start_date' => $request->therapy_start_date,
            'therapy_end_date'   => $request->therapy_end_date,
            'sessions_purchased' => $request->sessions_purchased ?? 0,
        ]);

        return redirect()->route('admin.patients.index')
            ->with('success', 'Patient added successfully!');
    }

    public function show(Patient $patient)
    {
        $patient->load(['documents', 'invoices', 'notes.createdBy', 'attendances.markedBy', 'therapyPlans.sessions']);

        // Appointments linked by email
        $appointments = collect();
        if ($patient->email) {
            $appointments = CitaPublica::where('email', $patient->email)
                ->with(['physiotherapist', 'specialty'])
                ->orderByDesc('fecha_cita')
                ->paginate(5, ['*'], 'appts_page');
        }

        return view('admin.patients.show', compact('patient', 'appointments'));
    }

    public function edit(Patient $patient)
    {
        return view('admin.patients.edit', compact('patient'));
    }

    public function update(Request $request, Patient $patient)
    {
        $request->validate([
            'name'              => 'required|string|max:100',
            'last_name'         => 'nullable|string|max:100',
            'email'             => 'nullable|email|unique:patients,email,' . $patient->id,
            'phone'             => 'required|string|max:20',
            'id_card'           => 'nullable|string|max:50|unique:patients,id_card,' . $patient->id,
            'fecha_nacimiento'  => 'nullable|date',
            'sexo'              => 'nullable|in:male,female,other',
            'address'           => 'nullable|string|max:255',
            'blood_group'       => 'nullable|string|max:10',
            'emergency_contact' => 'nullable|string|max:100',
            'emergency_phone'   => 'nullable|string|max:20',
            'photo'             => 'nullable|image|max:2048',
            // Therapy Plan
            'therapy_plan_name'  => 'nullable|string|max:200',
            'therapy_diagnosis'  => 'nullable|string|max:200',
            'therapy_goal'       => 'nullable|string|max:1000',
            'therapy_start_date' => 'nullable|date',
            'therapy_end_date'   => 'nullable|date|after_or_equal:therapy_start_date',
            'sessions_purchased' => 'nullable|integer|min:0',
        ]);

        $data = $request->only([
            'name','last_name','email','phone','id_card',
            'fecha_nacimiento','sexo','address',
            'blood_group','emergency_contact','emergency_phone',
            // Therapy Plan
            'therapy_plan_name','therapy_diagnosis','therapy_goal',
            'therapy_start_date','therapy_end_date','sessions_purchased',
        ]);

        if ($request->hasFile('photo')) {
            if ($patient->photo) Storage::disk('public')->delete($patient->photo);
            $data['photo'] = $request->file('photo')->store('patients/photos', 'public');
        }

        $patient->update($data);

        return redirect()->route('admin.patients.show', $patient)
            ->with('success', 'Patient updated successfully.');
    }

    public function destroy(Patient $patient)
    {
        if ($patient->photo) Storage::disk('public')->delete($patient->photo);
        $patient->delete();

        return redirect()->route('admin.patients.index')
            ->with('success', 'Patient deleted.');
    }
}
