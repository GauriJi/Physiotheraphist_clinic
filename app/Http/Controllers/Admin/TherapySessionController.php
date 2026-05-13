<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TherapySession;
use App\Models\TherapyPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TherapySessionController extends Controller
{
    /**
     * Update a session's status (completed / missed / rescheduled / cancelled).
     */
    public function update(Request $request, TherapySession $session)
    {
        $request->validate([
            'status'          => 'required|in:completed,missed,rescheduled,cancelled',
            'therapist_notes' => 'nullable|string|max:2000',
            'reschedule_date' => 'required_if:status,rescheduled|nullable|date|after:today',
        ]);

        $plan   = $session->plan;
        $status = $request->status;

        DB::transaction(function () use ($request, $session, $plan, $status) {

            switch ($status) {

                // ── COMPLETED ─────────────────────────────────────────────────
                case 'completed':
                    $session->update([
                        'status'          => 'completed',
                        'actual_date'     => today(),
                        'therapist_notes' => $request->therapist_notes,
                        'marked_by'       => Auth::id(),
                    ]);

                    // Update patient counters
                    $patient = $session->patient;
                    $patient->increment('sessions_completed');
                    $patient->update(['last_visit_date' => today()]);

                    // Check if all sessions in plan are done
                    $remaining = $plan->sessions()
                        ->whereIn('status', ['upcoming', 'rescheduled'])
                        ->count();
                    if ($remaining === 0) {
                        $plan->update(['status' => 'completed']);
                    }
                    break;

                // ── MISSED ────────────────────────────────────────────────────
                case 'missed':
                    $session->update([
                        'status'          => 'missed',
                        'therapist_notes' => $request->therapist_notes,
                        'marked_by'       => Auth::id(),
                    ]);

                    // Increment patient missed counter
                    $session->patient->increment('missed_days');

                    // Auto-append new session at the end
                    $lastSession = $plan->sessions()
                        ->whereIn('status', ['upcoming', 'rescheduled'])
                        ->orderByDesc('scheduled_date')
                        ->first();

                    $lastDate = $lastSession
                        ? Carbon::parse($lastSession->scheduled_date)
                        : Carbon::parse($plan->end_date ?? $session->scheduled_date);

                    $newDate = $plan->nextAvailableDate($lastDate);

                    $maxNum = $plan->sessions()->max('session_number');

                    TherapySession::create([
                        'therapy_plan_id' => $plan->id,
                        'patient_id'      => $session->patient_id,
                        'session_number'  => $maxNum + 1,
                        'scheduled_date'  => $newDate->format('Y-m-d'),
                        'scheduled_time'  => $plan->session_time,
                        'duration'        => $plan->session_duration,
                        'status'          => 'upcoming',
                    ]);

                    // Extend plan end date
                    $plan->update(['end_date' => $newDate->format('Y-m-d')]);
                    break;

                // ── RESCHEDULED ───────────────────────────────────────────────
                case 'rescheduled':
                    $originalDate = $session->scheduled_date->copy();
                    $newDate      = Carbon::parse($request->reschedule_date);

                    $session->update([
                        'status'          => 'rescheduled',
                        'scheduled_date'  => $newDate,
                        'original_date'   => $originalDate,
                        'therapist_notes' => $request->therapist_notes,
                        'marked_by'       => Auth::id(),
                    ]);

                    // Update plan end_date if the rescheduled date is later
                    if ($plan->end_date && $newDate->gt($plan->end_date)) {
                        $plan->update(['end_date' => $newDate->format('Y-m-d')]);
                    }
                    break;

                // ── CANCELLED ─────────────────────────────────────────────────
                case 'cancelled':
                    $session->update([
                        'status'          => 'cancelled',
                        'therapist_notes' => $request->therapist_notes,
                        'marked_by'       => Auth::id(),
                    ]);
                    break;
            }
        });

        $labels = [
            'completed'   => 'marked as Completed ✅',
            'missed'      => 'marked as Missed — a new session has been appended 🔄',
            'rescheduled' => 'rescheduled successfully 🔵',
            'cancelled'   => 'cancelled 🚫',
        ];

        return back()->with('success', "Session #{$session->session_number} {$labels[$status]}.");
    }

    /**
     * Undo / revert a session back to upcoming.
     */
    public function revert(TherapySession $session)
    {
        $wasCompleted = $session->status === 'completed';
        $wasMissed    = $session->status === 'missed';

        DB::transaction(function () use ($session, $wasCompleted, $wasMissed) {
            $patient = $session->patient;

            if ($wasCompleted) {
                if ($patient->sessions_completed > 0) {
                    $patient->decrement('sessions_completed');
                }
                // Recalculate last_visit_date
                $prev = TherapySession::where('patient_id', $patient->id)
                    ->where('status', 'completed')
                    ->where('id', '!=', $session->id)
                    ->orderByDesc('actual_date')
                    ->first();
                $patient->update(['last_visit_date' => $prev?->actual_date]);
            }

            if ($wasMissed && $patient->missed_days > 0) {
                $patient->decrement('missed_days');
            }

            $session->update([
                'status'          => 'upcoming',
                'actual_date'     => null,
                'original_date'   => null,
                'therapist_notes' => null,
                'marked_by'       => null,
            ]);

            // Also revert plan status if it was completed
            $plan = $session->plan;
            if ($plan->status === 'completed') {
                $plan->update(['status' => 'active']);
            }
        });

        return back()->with('success', "Session #{$session->session_number} reverted to Upcoming.");
    }
}
