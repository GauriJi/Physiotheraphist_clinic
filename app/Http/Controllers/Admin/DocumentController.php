<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PatientDocument;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class DocumentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'title'      => 'required|string|max:150',
            'type'       => 'required|in:xray,mri,prescription,report,lab,other',
            'file'       => 'required|file|max:10240', // 10MB
            'notes'      => 'nullable|string|max:500',
        ]);

        $file     = $request->file('file');
        $path     = $file->store('patients/documents/' . $request->patient_id, 'public');

        PatientDocument::create([
            'patient_id'     => $request->patient_id,
            'appointment_id' => $request->appointment_id,
            'title'          => $request->title,
            'type'           => $request->type,
            'file_path'      => $path,
            'file_name'      => $file->getClientOriginalName(),
            'mime_type'      => $file->getMimeType(),
            'notes'          => $request->notes,
            'uploaded_by'    => Auth::id(),
        ]);

        return back()->with('success', 'Document uploaded successfully.');
    }

    public function download(PatientDocument $document)
    {
        if (!Storage::disk('public')->exists($document->file_path)) {
            abort(404, 'File not found.');
        }
        return Storage::disk('public')->download($document->file_path, $document->file_name);
    }

    public function destroy(PatientDocument $document)
    {
        Storage::disk('public')->delete($document->file_path);
        $patientId = $document->patient_id;
        $document->delete();

        return back()->with('success', 'Document deleted.');
    }
}
