<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TherapyPlan;
use App\Models\TherapySession;
use App\Models\Patient;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TreatmentCalendarController extends Controller
{
    /**
     * Global Treatment Calendar — shows all active therapy plans
     * with today's sessions, upcoming sessions, and a monthly overview.
     */
    public function index(Request $request)
    {
        $today    = Carbon::today();
        $month    = $request->input('month', $today->format('Y-m'));
        $monthDt  = Carbon::createFromFormat('Y-m', $month)->startOfMonth();

        // ── Today's Sessions ───────────────────────────────────────────────────
        $todaysSessions = TherapySession::with(['patient', 'plan'])
            ->whereDate('scheduled_date', $today)
            ->whereIn('status', ['upcoming', 'rescheduled'])
            ->orderBy('scheduled_time')
            ->get();

        // ── This month's sessions for the calendar grid ────────────────────────
        $monthSessions = TherapySession::with(['patient', 'plan'])
            ->whereYear('scheduled_date', $monthDt->year)
            ->whereMonth('scheduled_date', $monthDt->month)
            ->get()
            ->groupBy(fn($s) => $s->scheduled_date->format('Y-m-d'));

        // ── Overall Stats ──────────────────────────────────────────────────────
        $stats = [
            'active_plans'    => TherapyPlan::where('status', 'active')->count(),
            'total_sessions'  => TherapySession::count(),
            'completed_today' => TherapySession::whereDate('scheduled_date', $today)->where('status', 'completed')->count(),
            'upcoming_today'  => $todaysSessions->count(),
            'missed_total'    => TherapySession::where('status', 'missed')->count(),
            'upcoming_week'   => TherapySession::whereBetween('scheduled_date', [$today, $today->copy()->addDays(6)])
                                    ->whereIn('status', ['upcoming', 'rescheduled'])->count(),
        ];

        // ── Upcoming Sessions (next 14 days) ───────────────────────────────────
        $upcomingSessions = TherapySession::with(['patient', 'plan'])
            ->whereBetween('scheduled_date', [$today->copy()->addDay(), $today->copy()->addDays(14)])
            ->whereIn('status', ['upcoming', 'rescheduled'])
            ->orderBy('scheduled_date')
            ->orderBy('scheduled_time')
            ->get();

        // ── Active Plans summary ───────────────────────────────────────────────
        $activePlans = TherapyPlan::with(['patient', 'sessions'])
            ->where('status', 'active')
            ->latest()
            ->take(10)
            ->get();

        return view('admin.therapy.calendar', compact(
            'today', 'monthDt', 'monthSessions',
            'todaysSessions', 'upcomingSessions',
            'stats', 'activePlans', 'month'
        ));
    }
}
