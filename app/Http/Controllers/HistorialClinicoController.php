<?php

namespace App\Http\Controllers;

use App\Models\MedicalHistory;
use App\Models\Patient;
use App\Models\Physiotherapist;
use Illuminate\Http\Request;

class HistorialClinicoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $histories = MedicalHistory::with('patient', 'physiotherapist')->paginate(10);
        return view('histories.index', compact('histories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $patients = Patient::all();
        $physiotherapists = Physiotherapist::all();
        return view('histories.create', compact('patients', 'physiotherapists'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'physiotherapist_id' => 'required|exists:physiotherapists,id',
            'descripcion' => 'required|string',
            'diagnostico' => 'required|string',
            'tratamiento' => 'required|string'
        ]);

        MedicalHistory::create($validated);
        return redirect()->route('histories.index')->with('success', 'History clínico creado exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(MedicalHistory $history)
    {
        $history->load('patient', 'physiotherapist');
        return view('histories.show', compact('history'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MedicalHistory $history)
    {
        $patients = Patient::all();
        $physiotherapists = Physiotherapist::all();
        return view('histories.edit', compact('history', 'patients', 'physiotherapists'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MedicalHistory $history)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'physiotherapist_id' => 'required|exists:physiotherapists,id',
            'descripcion' => 'required|string',
            'diagnostico' => 'required|string',
            'tratamiento' => 'required|string'
        ]);

        $history->update($validated);
        return redirect()->route('histories.show', $history)->with('success', 'History clínico actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MedicalHistory $history)
    {
        $history->delete();
        return redirect()->route('histories.index')->with('success', 'History clínico eliminado exitosamente');
    }
}
