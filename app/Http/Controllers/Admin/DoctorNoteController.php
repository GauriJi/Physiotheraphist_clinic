<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DoctorNote;
use App\Models\Patient;
use App\Models\Physiotherapist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoctorNoteController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'patient_id'     => 'required|exists:patients,id',
            'notes'          => 'required|string',
            'exercises'      => 'nullable|string',
            'progress'       => 'nullable|string',
            'next_session'   => 'nullable|string|max:100',
            'session_status' => 'required|in:improving,stable,worsening,recovered',
        ]);

        DoctorNote::create([
            'patient_id'        => $request->patient_id,
            'appointment_id'    => $request->appointment_id,
            'physiotherapist_id'=> $request->physiotherapist_id,
            'notes'             => $request->notes,
            'exercises'         => $request->exercises,
            'progress'          => $request->progress,
            'next_session'      => $request->next_session,
            'session_status'    => $request->session_status,
            'created_by'        => Auth::id(),
        ]);

        return back()->with('success', 'Note added successfully.');
    }

    public function update(Request $request, DoctorNote $note)
    {
        $request->validate([
            'notes'          => 'required|string',
            'exercises'      => 'nullable|string',
            'progress'       => 'nullable|string',
            'next_session'   => 'nullable|string|max:100',
            'session_status' => 'required|in:improving,stable,worsening,recovered',
        ]);

        $note->update($request->only('notes','exercises','progress','next_session','session_status'));
        return back()->with('success', 'Note updated.');
    }

    public function destroy(DoctorNote $note)
    {
        $note->delete();
        return back()->with('success', 'Note deleted.');
    }
}
