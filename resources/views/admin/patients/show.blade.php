@extends('admin.layouts.sidebar')
@section('title', $patient->full_name . ' — Profile')
@section('page-title', $patient->full_name)
@section('breadcrumb', 'Admin / Patients / Profile')

@section('content')

{{-- PROFILE HEADER --}}
<div class="card" style="margin-bottom:1.4rem;">
    <div style="padding:1.5rem;display:flex;align-items:center;gap:1.5rem;flex-wrap:wrap;">
        <img src="{{ $patient->photo_url }}"
             style="width:90px;height:90px;border-radius:50%;object-fit:cover;border:3px solid #e2e8f0;">
        <div style="flex:1;">
            <div style="display:flex;align-items:center;gap:.75rem;flex-wrap:wrap;">
                <div style="font-size:22px;font-weight:800;color:#0f172a;">{{ $patient->full_name }}</div>
                @if($patient->patient_uid)
                <span style="background:linear-gradient(135deg,#3b82f6,#6366f1);color:#fff;font-size:12px;font-weight:800;padding:4px 12px;border-radius:20px;letter-spacing:.04em;font-family:monospace;">🪪 {{ $patient->patient_uid }}</span>
                @endif
            </div>
            <div style="font-size:13.5px;color:#64748b;margin-top:3px;display:flex;flex-wrap:wrap;gap:.5rem;align-items:center;">
                @if($patient->email)   <span>✉️ {{ $patient->email }}</span> @endif
                @if($patient->phone)   <span>|</span><span>📞 {{ $patient->phone }}</span> @endif
                @if($patient->blood_group)
                    <span>|</span>
                    <span style="background:#fee2e2;color:#991b1b;font-size:11px;font-weight:700;padding:2px 8px;border-radius:20px;">
                        {{ $patient->blood_group }}
                    </span>
                @endif
            </div>
            <div style="margin-top:.5rem;display:flex;gap:.4rem;flex-wrap:wrap;">
                @if($patient->age)     <span style="font-size:12px;background:#f1f5f9;border-radius:20px;padding:2px 10px;color:#475569;">🎂 Age {{ $patient->age }}</span> @endif
                @if($patient->sexo)    <span style="font-size:12px;background:#f1f5f9;border-radius:20px;padding:2px 10px;color:#475569;">{{ ucfirst($patient->sexo) }}</span> @endif
                @if($patient->id_card) <span style="font-size:12px;background:#f1f5f9;border-radius:20px;padding:2px 10px;color:#475569;">🪪 {{ $patient->id_card }}</span> @endif
                @if($patient->address) <span style="font-size:12px;background:#f1f5f9;border-radius:20px;padding:2px 10px;color:#475569;">📍 {{ $patient->address }}</span> @endif
            </div>
        </div>
        <div style="display:flex;gap:.5rem;flex-wrap:wrap;">
            <a href="{{ route('admin.patients.edit', $patient) }}" class="btn btn-warning">✏️ Edit</a>
            <a href="{{ route('admin.invoices.create', ['patient_id'=>$patient->id]) }}" class="btn btn-success">💰 Invoice</a>
            <a href="{{ route('admin.patients.index') }}" class="btn btn-ghost">← Back</a>
        </div>
    </div>
</div>

{{-- TABS --}}
<div data-tabs="1">
<div class="tabs">
    <button class="tab-btn active" data-tab="appointments">📅 Appointments ({{ $appointments instanceof \Illuminate\Pagination\LengthAwarePaginator ? $appointments->total() : $appointments->count() }})</button>
    <button class="tab-btn" data-tab="therapy">🩺 Session Tracker</button>
    <button class="tab-btn" data-tab="documents">📁 Documents ({{ $patient->documents->count() }})</button>
    <button class="tab-btn" data-tab="invoices">💰 Invoices ({{ $patient->invoices->count() }})</button>
    <button class="tab-btn" data-tab="notes">📋 Notes ({{ $patient->notes->count() }})</button>
    <button class="tab-btn" data-tab="emergency">🚨 Emergency</button>
</div>

{{-- TAB: APPOINTMENTS --}}
<div id="tab-appointments" class="tab-panel active">
    <div class="card">
        <div style="overflow-x:auto;">
            @php $apptCount = $appointments instanceof \Illuminate\Pagination\LengthAwarePaginator ? $appointments->count() : $appointments->count(); @endphp
            @if($apptCount > 0)
            <table class="tbl">
                <thead>
                    <tr><th>Date</th><th>Time</th><th>Doctor</th><th>Specialty</th><th>Reason</th><th>Status</th></tr>
                </thead>
                <tbody>
                    @foreach($appointments as $appt)
                    <tr>
                        <td>{{ $appt->fecha_cita->format('d M Y') }}</td>
                        <td>{{ $appt->hora_cita }}</td>
                        <td>{{ ($appt->physiotherapist->name ?? '') . ' ' . ($appt->physiotherapist->last_name ?? '') }}</td>
                        <td>{{ $appt->specialty->name ?? '—' }}</td>
                        <td>{{ Str::limit($appt->reason, 40) }}</td>
                        <td>
                            @php $s = strtolower($appt->status); @endphp
                            <span class="badge badge-{{ $s==='pendiente'?'pending':($s==='confirmada'?'confirmed':($s==='completada'?'completed':'cancelled')) }}">
                                {{ ucfirst($appt->status) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @if($appointments instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div style="padding:1rem 1.25rem;">{{ $appointments->links() }}</div>
            @endif
            @else
                <div class="empty-state"><div class="empty-icon">📅</div><div class="empty-sub">No appointments recorded yet</div></div>
            @endif
        </div>
    </div>
</div>

{{-- TAB: DOCUMENTS --}}
<div id="tab-documents" class="tab-panel">
    <div class="card" style="margin-bottom:1rem;">
        <div class="card-header"><div class="card-title">📤 Upload Document</div></div>
        <div class="card-body">
            <form action="{{ route('admin.documents.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="patient_id" value="{{ $patient->id }}">
                <div class="grid-3">
                    <div class="form-group">
                        <label class="form-label">Title *</label>
                        <input name="title" class="form-control" placeholder="X-Ray Chest, Prescription…" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Type *</label>
                        <select name="type" class="form-control" required>
                            <option value="xray">X-Ray</option>
                            <option value="mri">MRI Scan</option>
                            <option value="prescription">Prescription</option>
                            <option value="report">Medical Report</option>
                            <option value="lab">Lab Result</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">File * (max 10MB)</label>
                        <input type="file" name="file" class="form-control" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Notes (optional)</label>
                    <input name="notes" class="form-control" placeholder="Additional notes…">
                </div>
                <button class="btn btn-primary" type="submit">📤 Upload</button>
            </form>
        </div>
    </div>

    <div class="card">
        @if($patient->documents->count() > 0)
        <table class="tbl">
            <thead>
                <tr><th>Title</th><th>Type</th><th>File</th><th>Uploaded</th><th>Notes</th><th>Actions</th></tr>
            </thead>
            <tbody>
                @foreach($patient->documents as $doc)
                <tr>
                    <td style="font-weight:600;">{{ $doc->title }}</td>
                    <td><span class="badge badge-confirmed">{{ $doc->type_label }}</span></td>
                    <td style="font-size:12px;color:#64748b;">{{ $doc->file_name }}</td>
                    <td style="font-size:12px;color:#64748b;">{{ $doc->created_at->format('d M Y') }}</td>
                    <td style="font-size:12px;color:#64748b;">{{ $doc->notes ?? '—' }}</td>
                    <td>
                        <div style="display:flex;gap:.4rem;">
                            @if($doc->is_image)
                                <a href="{{ $doc->file_url }}" target="_blank" class="btn btn-ghost btn-sm">🖼 View</a>
                            @else
                                <a href="{{ route('admin.documents.download', $doc) }}" class="btn btn-primary btn-sm">⬇ Download</a>
                            @endif
                            <form action="{{ route('admin.documents.destroy', $doc) }}" method="POST"
                                  onsubmit="return confirm('Delete this document?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm">🗑</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
            <div class="empty-state"><div class="empty-icon">📁</div><div class="empty-sub">No documents uploaded yet</div></div>
        @endif
    </div>
</div>

{{-- TAB: INVOICES --}}
<div id="tab-invoices" class="tab-panel">
    <div style="display:flex;justify-content:flex-end;margin-bottom:.75rem;">
        <a href="{{ route('admin.invoices.create', ['patient_id'=>$patient->id]) }}" class="btn btn-success">➕ New Invoice</a>
    </div>
    <div class="card">
        @if($patient->invoices->count() > 0)
        <table class="tbl">
            <thead>
                <tr><th>Invoice #</th><th>Date</th><th>Total</th><th>Paid</th><th>Balance</th><th>Status</th><th>Action</th></tr>
            </thead>
            <tbody>
                @foreach($patient->invoices as $inv)
                @php $badge = $inv->status_badge; @endphp
                <tr>
                    <td style="font-weight:700;color:#3b82f6;">{{ $inv->invoice_number }}</td>
                    <td style="font-size:12.5px;">{{ $inv->created_at->format('d M Y') }}</td>
                    <td style="font-weight:600;">₹{{ number_format($inv->total,2) }}</td>
                    <td style="color:#059669;font-weight:600;">₹{{ number_format($inv->paid_amount,2) }}</td>
                    <td style="color:#ef4444;font-weight:600;">₹{{ number_format($inv->balance,2) }}</td>
                    <td><span class="badge {{ $badge['class'] }}">{{ $badge['label'] }}</span></td>
                    <td><a href="{{ route('admin.invoices.show', $inv) }}" class="btn btn-ghost btn-sm">👁 View</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
            <div class="empty-state"><div class="empty-icon">💰</div><div class="empty-sub">No invoices yet</div></div>
        @endif
    </div>
</div>

{{-- TAB: DOCTOR NOTES --}}
<div id="tab-notes" class="tab-panel">
    <div class="card" style="margin-bottom:1rem;">
        <div class="card-header"><div class="card-title">📝 Add Treatment Note</div></div>
        <div class="card-body">
            <form action="{{ route('admin.notes.store') }}" method="POST">
                @csrf
                <input type="hidden" name="patient_id" value="{{ $patient->id }}">
                <div class="grid-2" style="margin-bottom:.5rem;">
                    <div class="form-group">
                        <label class="form-label">Session Status</label>
                        <select name="session_status" class="form-control" required>
                            <option value="stable">➡️ Stable</option>
                            <option value="improving">📈 Improving</option>
                            <option value="worsening">📉 Worsening</option>
                            <option value="recovered">✅ Recovered</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Next Session</label>
                        <input name="next_session" class="form-control" placeholder="e.g. 2 days, Mon 10am">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Treatment Notes *</label>
                    <textarea name="notes" class="form-control" rows="3"
                              placeholder="Describe treatment given, observations…" required></textarea>
                </div>
                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Exercise Recommendations</label>
                        <textarea name="exercises" class="form-control" rows="2" placeholder="Exercise plan…"></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Progress Notes</label>
                        <textarea name="progress" class="form-control" rows="2" placeholder="Patient progress observations…"></textarea>
                    </div>
                </div>
                <button class="btn btn-primary" type="submit">💾 Save Note</button>
            </form>
        </div>
    </div>

    @forelse($patient->notes as $note)
    @php $badge = $note->status_badge; @endphp
    <div class="card" style="margin-bottom:.9rem;">
        <div style="padding:1rem 1.25rem;">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:.75rem;flex-wrap:wrap;gap:.5rem;">
                <div style="display:flex;align-items:center;gap:.6rem;">
                    <span style="font-size:16px;">{{ $badge['emoji'] }}</span>
                    <span style="font-weight:700;color:#0f172a;font-size:13.5px;">{{ $badge['label'] }}</span>
                    @if($note->next_session)
                        <span style="font-size:12px;background:#f1f5f9;border-radius:20px;padding:2px 10px;color:#475569;">🕒 Next: {{ $note->next_session }}</span>
                    @endif
                </div>
                <div style="display:flex;align-items:center;gap:.75rem;">
                    <span style="font-size:11.5px;color:#94a3b8;">
                        {{ $note->created_at->format('d M Y, h:i A') }} — {{ $note->createdBy->name ?? 'Admin' }}
                    </span>
                    <form action="{{ route('admin.notes.destroy', $note) }}" method="POST"
                          onsubmit="return confirm('Delete this note?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm">🗑</button>
                    </form>
                </div>
            </div>
            <div style="font-size:13.5px;color:#374151;margin-bottom:.6rem;line-height:1.6;">
                <strong>Notes:</strong> {{ $note->notes }}
            </div>
            @if($note->exercises)
            <div style="background:#f0fdf4;border-left:3px solid #10b981;padding:.5rem .8rem;border-radius:6px;font-size:12.5px;color:#166534;margin-bottom:.5rem;">
                <strong>Exercises:</strong> {{ $note->exercises }}
            </div>
            @endif
            @if($note->progress)
            <div style="background:#eff6ff;border-left:3px solid #3b82f6;padding:.5rem .8rem;border-radius:6px;font-size:12.5px;color:#1e40af;">
                <strong>Progress:</strong> {{ $note->progress }}
            </div>
            @endif
        </div>
    </div>
    @empty
        <div class="card"><div class="empty-state"><div class="empty-icon">📋</div><div class="empty-sub">No notes yet</div></div></div>
    @endforelse
</div>

{{-- TAB: EMERGENCY --}}
<div id="tab-emergency" class="tab-panel">
    <div class="card">
        <div class="card-body">
            @if($patient->emergency_contact || $patient->emergency_phone)
            <div style="display:flex;gap:2rem;flex-wrap:wrap;">
                <div>
                    <div style="font-size:11px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;margin-bottom:.3rem;">Emergency Contact</div>
                    <div style="font-size:18px;font-weight:700;color:#0f172a;">{{ $patient->emergency_contact }}</div>
                </div>
                <div>
                    <div style="font-size:11px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;margin-bottom:.3rem;">Phone</div>
                    <div style="font-size:18px;font-weight:700;color:#0f172a;">{{ $patient->emergency_phone }}</div>
                </div>
            </div>
            @else
                <div class="empty-state">
                    <div class="empty-icon">🚨</div>
                    <div class="empty-sub">No emergency contact on file</div>
                    <a href="{{ route('admin.patients.edit', $patient) }}" class="btn btn-warning" style="margin-top:1rem;">Add Emergency Contact</a>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- TAB: SESSION TRACKER --}}
<div id="tab-therapy" class="tab-panel">

    @php $activePlan = $patient->activeTherapyPlan(); @endphp

    {{-- Quick stats from active plan --}}
    @if($activePlan)
    @php
        $aCompleted = $activePlan->sessions->where('status','completed')->count();
        $aTotal     = $activePlan->total_sessions;
        $aPct       = $aTotal > 0 ? round($aCompleted / $aTotal * 100) : 0;
        $aNext      = $activePlan->nextSession();
    @endphp
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(145px,1fr));gap:1rem;margin-bottom:1.25rem;">
        <div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:12px;padding:1rem;text-align:center;">
            <div style="font-size:11px;font-weight:700;color:#3b82f6;text-transform:uppercase;margin-bottom:.3rem;">Total</div>
            <div style="font-size:28px;font-weight:900;color:#1d4ed8;">{{ $aTotal }}</div>
        </div>
        <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:12px;padding:1rem;text-align:center;">
            <div style="font-size:11px;font-weight:700;color:#16a34a;text-transform:uppercase;margin-bottom:.3rem;">Completed</div>
            <div style="font-size:28px;font-weight:900;color:#15803d;">{{ $aCompleted }}</div>
        </div>
        <div style="background:#faf5ff;border:1px solid #e9d5ff;border-radius:12px;padding:1rem;text-align:center;">
            <div style="font-size:11px;font-weight:700;color:#9333ea;text-transform:uppercase;margin-bottom:.3rem;">Remaining</div>
            <div style="font-size:28px;font-weight:900;color:#7e22ce;">{{ $activePlan->remaining_count }}</div>
        </div>
        <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:12px;padding:1rem;text-align:center;">
            <div style="font-size:11px;font-weight:700;color:#dc2626;text-transform:uppercase;margin-bottom:.3rem;">Missed</div>
            <div style="font-size:28px;font-weight:900;color:#b91c1c;">{{ $activePlan->missed_count }}</div>
        </div>
        <div style="background:#fff7ed;border:1px solid #fed7aa;border-radius:12px;padding:1rem;text-align:center;">
            <div style="font-size:11px;font-weight:700;color:#ea580c;text-transform:uppercase;margin-bottom:.3rem;">Next Session</div>
            <div style="font-size:13px;font-weight:800;color:#c2410c;margin-top:.2rem;">
                {{ $aNext ? $aNext->scheduled_date->format('d M') : '—' }}
            </div>
        </div>
    </div>

    {{-- Progress Bar --}}
    <div class="card" style="margin-bottom:1.25rem;">
        <div style="padding:1rem 1.25rem;">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:.4rem;">
                <span style="font-size:13px;font-weight:700;color:#374151;">🏃 Therapy Progress — {{ $activePlan->plan_name }}</span>
                <span style="font-size:13px;font-weight:800;color:#3b82f6;">{{ $aPct }}%</span>
            </div>
            <div style="background:#e2e8f0;border-radius:999px;height:10px;overflow:hidden;">
                <div style="height:100%;width:{{ $aPct }}%;background:linear-gradient(90deg,#3b82f6,#6366f1);border-radius:999px;"></div>
            </div>
            <div style="font-size:11.5px;color:#94a3b8;margin-top:.4rem;">{{ $aCompleted }} of {{ $aTotal }} sessions completed</div>
        </div>
    </div>
    @endif

    {{-- Action Buttons --}}
    <div style="display:flex;gap:.75rem;flex-wrap:wrap;margin-bottom:1.25rem;">
        <a href="{{ route('admin.therapy.create', $patient) }}" class="btn btn-primary">➕ Create Therapy Plan</a>
        @if($patient->therapyPlans()->exists())
        <a href="{{ route('admin.therapy.index', $patient) }}" class="btn btn-ghost">📋 All Plans ({{ $patient->therapyPlans()->count() }})</a>
        @endif
        @if($activePlan)
        <a href="{{ route('admin.therapy.show', $activePlan) }}" class="btn btn-success">📅 Open Treatment Calendar</a>
        @endif
    </div>

    {{-- Plans list --}}
    @forelse($patient->therapyPlans as $pl)
    @php
        $plC = $pl->sessions->where('status','completed')->count();
        $plP = $pl->total_sessions > 0 ? round($plC/$pl->total_sessions*100) : 0;
        $scMap = ['active'=>'#10b981','completed'=>'#6366f1','cancelled'=>'#ef4444'];
    @endphp
    <div class="card" style="margin-bottom:.75rem;">
        <div style="padding:1rem 1.25rem;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.5rem;">
            <div style="flex:1;">
                <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:.25rem;">
                    <span style="font-weight:700;color:#0f172a;">{{ $pl->plan_name }}</span>
                    <span style="font-size:11px;font-weight:700;color:{{ $scMap[$pl->status] ?? '#94a3b8' }};background:#f8fafc;border-radius:20px;padding:1px 8px;">{{ ucfirst($pl->status) }}</span>
                </div>
                <div style="font-size:12px;color:#64748b;">{{ $pl->start_date->format('d M Y') }} → {{ $pl->end_date?->format('d M Y') ?? '?' }} &nbsp;·&nbsp; {{ $pl->total_sessions }} sessions</div>
                <div style="background:#e2e8f0;border-radius:999px;height:5px;overflow:hidden;margin-top:.4rem;width:200px;">
                    <div style="height:100%;width:{{ $plP }}%;background:#3b82f6;border-radius:999px;"></div>
                </div>
            </div>
            <a href="{{ route('admin.therapy.show', $pl) }}" class="btn btn-ghost btn-sm">📅 View</a>
        </div>
    </div>
    @empty
    <div class="card"><div class="empty-state"><div class="empty-icon">🩺</div><div class="empty-sub">No therapy plans yet. Create one above.</div></div></div>
    @endforelse

</div>

</div>{{-- /data-tabs --}}


@endsection
