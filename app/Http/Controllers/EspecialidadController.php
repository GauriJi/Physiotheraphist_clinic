<?php

namespace App\Http\Controllers;

use App\Models\Specialty;
use Illuminate\Http\Request;

class EspecialidadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $specialties = Specialty::withCount('physiotherapists')->paginate(10);
        return view('specialties.index', compact('specialties'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('specialties.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:specialties',
            'descripcion' => 'required|string'
        ]);

        Specialty::create($validated);
        return redirect()->route('specialties.index')->with('success', 'Specialty creada exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Specialty $specialty)
    {
        $specialty->load('physiotherapists');
        return view('specialties.show', compact('specialty'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Specialty $specialty)
    {
        return view('specialties.edit', compact('specialty'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Specialty $specialty)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:specialties,name,' . $specialty->id,
            'descripcion' => 'required|string'
        ]);

        $specialty->update($validated);
        return redirect()->route('specialties.show', $specialty)->with('success', 'Specialty actualizada exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Specialty $specialty)
    {
        $specialty->delete();
        return redirect()->route('specialties.index')->with('success', 'Specialty eliminada exitosamente');
    }
}
