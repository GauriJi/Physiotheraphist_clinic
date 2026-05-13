<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;

class PacienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $patients = Patient::paginate(10);
        return view('patients.index', compact('patients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all();
        return view('patients.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'fecha_nacimiento' => 'required|date',
            'address' => 'required|string',
            'phone' => 'required|string',
            'sexo' => 'required|in:M,F'
        ]);

        Patient::create($validated);
        return redirect()->route('patients.index')->with('success', 'Patient creado exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Patient $patient)
    {
        $patient->load('user', 'appointments', 'histories');
        return view('patients.show', compact('patient'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Patient $patient)
    {
        $users = User::all();
        return view('patients.edit', compact('patient', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'fecha_nacimiento' => 'required|date',
            'address' => 'required|string',
            'phone' => 'required|string',
            'sexo' => 'required|in:M,F'
        ]);

        $patient->update($validated);
        return redirect()->route('patients.show', $patient)->with('success', 'Patient actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Patient $patient)
    {
        $patient->delete();
        return redirect()->route('patients.index')->with('success', 'Patient eliminado exitosamente');
    }
}
