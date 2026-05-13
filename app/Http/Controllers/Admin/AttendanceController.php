<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\PatientAttendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    /**
     * Mark attendance for a patient (present or absent) for a given date.
     * One record per patient per day (enforced by DB unique constraint).
     */
    public function mark(Request $request, Patient $patient)
    {
        $request->validate([
            'visit_date' => 'required|date',
            'status'     => 'required|in:present,absent',
            'notes'      => 'nullable|string|max:500',
        ]);

        $date   = $request->visit_date;
        $status = $request->status;

        // Check for duplicate
        $existing = PatientAttendance::where('patient_id', $patient->id)
            ->where('visit_date', $date)
            ->first();

        if ($existing) {
            return back()->with('error', "Attendance for {$patient->full_name} on {$date} is already recorded.");
        }

        DB::transaction(function () use ($patient, $date, $status, $request) {
            if ($status === 'present') {
                // Determine the next session number
                $sessionNumber = $patient->sessions_completed + 1;

                PatientAttendance::create([
                    'patient_id'     => $patient->id,
                    'visit_date'     => $date,
                    'session_number' => $sessionNumber,
                    'status'         => 'present',
                    'notes'          => $request->notes,
                    'marked_by'      => Auth::id(),
                ]);

                // Increment completed sessions, update last visit
                $patient->increment('sessions_completed');
                $patient->update(['last_visit_date' => $date]);

            } else {
                // Absent — record but do NOT increment session count
                PatientAttendance::create([
                    'patient_id'     => $patient->id,
                    'visit_date'     => $date,
                    'session_number' => 0, // no session for absent
                    'status'         => 'absent',
                    'notes'          => $request->notes,
                    'marked_by'      => Auth::id(),
                ]);

                $patient->increment('missed_days');
            }
        });

        $label = $status === 'present' ? '✅ Present' : '❌ Absent';
        return back()->with('success', "Attendance marked as {$label} for {$patient->full_name} on {$date}.");
    }

    /**
     * Undo / delete an attendance record and reverse the patient counters.
     */
    public function undo(PatientAttendance $attendance)
    {
        DB::transaction(function () use ($attendance) {
            $patient = $attendance->patient;

            if ($attendance->status === 'present') {
                // Decrement sessions_completed (min 0)
                if ($patient->sessions_completed > 0) {
                    $patient->decrement('sessions_completed');
                }
                // Recalculate last_visit_date
                $prevVisit = PatientAttendance::where('patient_id', $patient->id)
                    ->where('status', 'present')
                    ->where('id', '!=', $attendance->id)
                    ->orderByDesc('visit_date')
                    ->first();
                $patient->update(['last_visit_date' => $prevVisit?->visit_date]);

            } else {
                if ($patient->missed_days > 0) {
                    $patient->decrement('missed_days');
                }
            }

            $attendance->delete();
        });

        return back()->with('success', 'Attendance record removed and counters updated.');
    }
}
