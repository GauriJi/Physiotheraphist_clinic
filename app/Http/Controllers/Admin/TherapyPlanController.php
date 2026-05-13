<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\TherapyPlan;
use App\Models\TherapySession;
use App\Models\Physiotherapist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TherapyPlanController extends Controller
{
    // ── List all plans for a patient ──────────────────────────────────────────

    public function index(Patient $patient)
    {
        $plans = $patient->therapyPlans()->with(['sessions'])->get();
        return view('admin.therapy.index', compact('patient', 'plans'));
    }

    // ── Create form ───────────────────────────────────────────────────────────

    public function create(Patient $patient)
    {
        $physiotherapists = Physiotherapist::orderBy('name')->get();
        return view('admin.therapy.create', compact('patient', 'physiotherapists'));
    }

    // ── Store: create plan + auto-generate all sessions ───────────────────────

    public function store(Request $request, Patient $patient)
    {
        $request->validate([
            'plan_name'          => 'required|string|max:200',
            'diagnosis'          => 'nullable|string|max:300',
            'goal'               => 'nullable|string|max:1000',
            'notes'              => 'nullable|string|max:2000',
            'total_sessions'     => 'required|integer|min:1|max:365',
            'sessions_frequency' => 'required|integer|min:1|max:30',
            'skip_sundays'       => 'nullable|boolean',
            'session_time'       => 'nullable|date_format:H:i',
            'session_duration'   => 'nullable|integer|min:5|max:480',
            'start_date'         => 'required|date',
            'physiotherapist_id' => 'nullable|exists:physiotherapists,id',
        ]);

        $startDate    = Carbon::parse($request->start_date);
        $total        = (int) $request->total_sessions;
        $freqDays     = (int) $request->sessions_frequency;
        $skipSundays  = (bool) $request->skip_sundays;
        $sessionTime  = $request->session_time;
        $duration     = (int) ($request->session_duration ?? 60);

        // Generate all session dates
        $dates = TherapyPlan::generateSessionDates($startDate, $total, $freqDays, $skipSundays);
        $endDate = end($dates);

        DB::transaction(function () use (
            $request, $patient, $dates, $endDate,
            $freqDays, $skipSundays, $sessionTime, $duration, $total
        ) {
            // Create the plan
            $plan = TherapyPlan::create([
                'patient_id'         => $patient->id,
                'physiotherapist_id' => $request->physiotherapist_id,
                'plan_name'          => $request->plan_name,
                'diagnosis'          => $request->diagnosis,
                'goal'               => $request->goal,
                'notes'              => $request->notes,
                'total_sessions'     => $total,
                'sessions_frequency' => $freqDays,
                'skip_sundays'       => $skipSundays,
                'session_time'       => $sessionTime,
                'session_duration'   => $duration,
                'start_date'         => $dates[0],
                'end_date'           => $endDate,
                'status'             => 'active',
            ]);

            // Bulk-insert all sessions
            $now = now();
            $sessionsData = [];
            foreach ($dates as $i => $date) {
                $sessionsData[] = [
                    'therapy_plan_id' => $plan->id,
                    'patient_id'      => $patient->id,
                    'session_number'  => $i + 1,
                    'scheduled_date'  => $date->format('Y-m-d'),
                    'scheduled_time'  => $sessionTime,
                    'duration'        => $duration,
                    'status'          => 'upcoming',
                    'created_at'      => $now,
                    'updated_at'      => $now,
                ];
            }
            TherapySession::insert($sessionsData);

            // Update patient session counter on patient record
            $patient->update([
                'sessions_purchased' => $patient->sessions_purchased + $total,
            ]);
        });

        return redirect()
            ->route('admin.therapy.index', $patient)
            ->with('success', "Therapy plan created! {$total} sessions auto-scheduled.");
    }

    // ── Show: calendar + session list ─────────────────────────────────────────

    public function show(TherapyPlan $plan)
    {
        $plan->load(['patient', 'physiotherapist', 'sessions.markedBy']);
        $patient = $plan->patient;

        // Group sessions by date for calendar
        $sessionsByDate = $plan->sessions
            ->keyBy(fn($s) => $s->scheduled_date->format('Y-m-d'));

        // Stats
        $stats = [
            'total'       => $plan->total_sessions,
            'completed'   => $plan->completed_count,
            'missed'      => $plan->missed_count,
            'rescheduled' => $plan->rescheduled_count,
            'upcoming'    => $plan->upcoming_count,
            'remaining'   => $plan->remaining_count,
            'progress'    => $plan->progress_percent,
            'next'        => $plan->nextSession(),
        ];

        // Calendar months needed
        $startMonth = $plan->start_date->copy()->startOfMonth();
        $endMonth   = ($plan->end_date ?? $plan->start_date)->copy()->endOfMonth();

        return view('admin.therapy.show', compact(
            'plan', 'patient', 'sessionsByDate', 'stats', 'startMonth', 'endMonth'
        ));
    }

    // ── Destroy ───────────────────────────────────────────────────────────────

    public function destroy(TherapyPlan $plan)
    {
        $patient = $plan->patient;
        $plan->delete(); // cascades to sessions
        return redirect()
            ->route('admin.therapy.index', $patient)
            ->with('success', 'Therapy plan deleted.');
    }
}
