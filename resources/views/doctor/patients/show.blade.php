@extends('admin.layouts.sidebar')
@section('title', 'Clinical Profile')
@section('page-title', 'Clinical Profile')
@section('breadcrumb', 'Doctor / Patients / Profile')

@section('content')
<style>
    .profile-header {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        padding: 2rem;
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        border-radius: 16px;
        color: white;
        margin-bottom: 2rem;
        box-shadow: 0 10px 15px -3px rgba(15, 23, 42, 0.3);
        position: relative;
        overflow: hidden;
    }
    .profile-header::after {
        content: '';
        position: absolute;
        right: 0; top: 0; bottom: 0; width: 40%;
        background: url('{{ asset("images/clinic_bg_premium.png") }}') center/cover;
        opacity: 0.15;
        mask-image: linear-gradient(to right, transparent, black);
        -webkit-mask-image: linear-gradient(to right, transparent, black);
    }
    .profile-avatar-lg {
        width: 80px; height: 80px;
        background: linear-gradient(135deg, #3b82f6, #06b6d4);
        border-radius: 20px;
        display: flex; align-items: center; justify-content: center;
        font-size: 32px; font-weight: 800;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.2);
        z-index: 2;
    }
    .profile-info { z-index: 2; }
    .profile-info h2 { font-size: 24px; font-weight: 800; margin-bottom: 0.25rem; }
    .profile-info p { font-size: 14px; color: #cbd5e1; display: flex; gap: 1rem; align-items: center; }
    
    .timeline { position: relative; padding-left: 20px; margin-top: 1rem; }
    .timeline::before {
        content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 2px;
        background: #e2e8f0;
    }
    .timeline-item { position: relative; margin-bottom: 1.5rem; padding-left: 1rem; }
    .timeline-item::before {
        content: ''; position: absolute; left: -25px; top: 5px; width: 12px; height: 12px;
        border-radius: 50%; background: #3b82f6; border: 3px solid white;
        box-shadow: 0 0 0 1px #e2e8f0;
    }
    .prescription-card {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 1.25rem;
    }
    
    .status-badge {
        display: inline-block; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; text-transform: uppercase;
    }
    .status-improving { background: #dcfce7; color: #166534; }
    .status-stable { background: #fef3c7; color: #92400e; }
    .status-worsening { background: #fee2e2; color: #991b1b; }
    .status-recovered { background: #dbeafe; color: #1e40af; }
</style>

<div class="profile-header">
    <div class="profile-avatar-lg">{{ strtoupper(substr($patient->full_name, 0, 1)) }}</div>
    <div class="profile-info">
        <h2>{{ $patient->full_name }}</h2>
        <p>
            <span><i class="fa-solid fa-envelope"></i> {{ $patient->email }}</span>
            <span><i class="fa-solid fa-phone"></i> {{ $patient->phone }}</span>
            <span><i class="fa-solid fa-droplet" style="color: #ef4444;"></i> {{ $patient->blood_group ?? 'Unknown' }}</span>
            <span><i class="fa-solid fa-cake-candles"></i> {{ $patient->age }} years old</span>
        </p>
    </div>
</div>

<div class="tabs" data-tabs="true">
    <button class="tab-btn active" data-tab="prescriptions">Prescriptions & Notes</button>
    <button class="tab-btn" data-tab="medical">Medical Records</button>
    <button class="tab-btn" data-tab="appointments">Appointments History</button>
</div>

<div class="grid-3">
    <!-- Main Content Area -->
    <div style="grid-column: span 2;">
        
        <!-- PRESCRIPTIONS TAB -->
        <div id="tab-prescriptions" class="tab-panel active">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <h3 style="font-size: 18px; font-weight: 700; color: #0f172a;">Treatment Timeline</h3>
                <button onclick="document.getElementById('prescription-modal').classList.add('open')" class="btn btn-primary">
                    ✍️ Write Prescription
                </button>
            </div>
            
            <div class="timeline">
                @forelse($prescriptions as $note)
                    <div class="timeline-item">
                        <div style="font-size: 12px; color: #64748b; font-weight: 600; margin-bottom: 0.5rem;">
                            {{ $note->created_at->format('M d, Y - h:i A') }}
                        </div>
                        <div class="prescription-card">
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                                <div>
                                    <span class="status-badge status-{{ $note->session_status }}">{{ $note->session_status }}</span>
                                </div>
                                <a href="{{ route('doctor.patients.print-prescription', $note->id) }}" target="_blank" class="btn btn-ghost btn-sm" title="Print Prescription">
                                    🖨️ Print PDF
                                </a>
                            </div>
                            <div style="margin-bottom: 1rem;">
                                <strong style="font-size: 13px; color: #475569; display: block; margin-bottom: 0.25rem;">Clinical Notes:</strong>
                                <p style="font-size: 14px; color: #1e293b; white-space: pre-line;">{{ $note->notes }}</p>
                            </div>
                            @if($note->exercises)
                            <div style="margin-bottom: 1rem;">
                                <strong style="font-size: 13px; color: #475569; display: block; margin-bottom: 0.25rem;">Recommended Exercises:</strong>
                                <p style="font-size: 14px; color: #1e293b; white-space: pre-line;">{{ $note->exercises }}</p>
                            </div>
                            @endif
                            @if($note->next_session)
                            <div style="background: #eff6ff; padding: 0.75rem; border-radius: 8px; border: 1px solid #bfdbfe;">
                                <strong style="font-size: 13px; color: #1e40af;">Next Session:</strong> <span style="font-size: 13px; color: #1e3a8a;">{{ $note->next_session }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="empty-state" style="border: 1px dashed #cbd5e1; border-radius: 12px;">
                        <div class="empty-icon">📝</div>
                        <div class="empty-title">No Prescriptions Yet</div>
                        <div class="empty-sub">Write the first treatment note for this patient.</div>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- MEDICAL RECORDS TAB -->
        <div id="tab-medical" class="tab-panel">
            <h3 style="font-size: 18px; font-weight: 700; color: #0f172a; margin-bottom: 1.5rem;">Uploaded Documents & Reports</h3>
            @if($patient->documents->count() > 0)
                <div class="grid-2">
                    @foreach($patient->documents as $doc)
                        <div class="card" style="display: flex; align-items: center; gap: 1rem; padding: 1rem;">
                            <div style="width: 48px; height: 48px; background: #f1f5f9; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 24px;">
                                📄
                            </div>
                            <div style="flex: 1;">
                                <div style="font-weight: 600; font-size: 14px; color: #0f172a;">{{ $doc->name }}</div>
                                <div style="font-size: 12px; color: #64748b;">{{ strtoupper($doc->type) }} • {{ $doc->created_at->format('M d, Y') }}</div>
                            </div>
                            <a href="{{ Storage::url($doc->file_path) }}" target="_blank" class="btn btn-ghost btn-sm">View</a>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-icon">📁</div>
                    <div class="empty-title">No Documents Found</div>
                    <div class="empty-sub">The admin has not uploaded any reports for this patient.</div>
                </div>
            @endif
        </div>

        <!-- APPOINTMENTS TAB -->
        <div id="tab-appointments" class="tab-panel">
            <h3 style="font-size: 18px; font-weight: 700; color: #0f172a; margin-bottom: 1.5rem;">Appointment History</h3>
            <div class="card">
                <table class="tbl">
                    <thead>
                        <tr>
                            <th>Date & Time</th>
                            <th>Reason</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($appointments as $app)
                            <tr>
                                <td>
                                    <div style="font-weight: 600; color: #0f172a;">{{ $app->fecha_cita->format('M d, Y') }}</div>
                                    <div style="font-size: 12px; color: #64748b;">{{ $app->hora_cita }}</div>
                                </td>
                                <td>{{ $app->reason }}</td>
                                <td>
                                    @php 
                                        $s = strtolower($app->status); 
                                        $statusEng = ['pendiente' => 'Pending', 'confirmada' => 'Confirmed', 'completada' => 'Completed', 'cancelada' => 'Cancelled'];
                                    @endphp
                                    <span class="badge badge-{{ $s === 'pendiente' ? 'pending' : ($s === 'confirmada' ? 'confirmed' : ($s === 'completada' ? 'completed' : 'cancelled')) }}">
                                        {{ $statusEng[$s] ?? ucfirst($s) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" style="text-align:center; padding:2rem;">No appointments found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- Right Sidebar Area -->
    <div style="grid-column: span 1;">
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <div class="card-title">Emergency Contact</div>
            </div>
            <div class="card-body">
                @if($patient->emergency_contact)
                    <div style="font-weight: 600; color: #0f172a;">{{ $patient->emergency_contact }}</div>
                    <div style="font-size: 13px; color: #3b82f6; font-weight: 600; margin-top: 0.25rem;">
                        <i class="fa-solid fa-phone"></i> {{ $patient->emergency_phone }}
                    </div>
                @else
                    <div style="font-size: 13px; color: #64748b;">No emergency contact provided.</div>
                @endif
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <div class="card-title">Quick Actions</div>
            </div>
            <div class="card-body" style="display: flex; flex-direction: column; gap: 0.5rem;">
                <button onclick="document.getElementById('prescription-modal').classList.add('open')" class="btn btn-primary" style="justify-content: center;">✍️ Write Prescription</button>
            </div>
        </div>
    </div>
</div>

<!-- Prescription Modal -->
<div id="prescription-modal" class="modal-overlay">
    <div class="modal-box">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <div class="modal-title" style="margin: 0;">Write Prescription / Treatment Note</div>
            <button onclick="document.getElementById('prescription-modal').classList.remove('open')" style="background: none; border: none; font-size: 20px; cursor: pointer;">✕</button>
        </div>
        
        <form action="{{ route('doctor.patients.store-prescription', $patient->id) }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label class="form-label">Clinical Notes / Diagnosis *</label>
                <textarea name="notes" class="form-control" rows="4" required placeholder="Enter detailed treatment notes, diagnosis, or medication..."></textarea>
            </div>
            
            <div class="form-group">
                <label class="form-label">Recommended Exercises / Therapy</label>
                <textarea name="exercises" class="form-control" rows="3" placeholder="Enter exercises or physical therapy routines..."></textarea>
            </div>
            
            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label">Session Status *</label>
                    <select name="session_status" class="form-control" required>
                        <option value="improving">Improving</option>
                        <option value="stable">Stable</option>
                        <option value="worsening">Worsening</option>
                        <option value="recovered">Recovered</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Next Session Recommendation</label>
                    <input type="text" name="next_session" class="form-control" placeholder="e.g. In 2 weeks">
                </div>
            </div>
            
            <div style="margin-top: 1.5rem; text-align: right;">
                <button type="button" onclick="document.getElementById('prescription-modal').classList.remove('open')" class="btn btn-ghost">Cancel</button>
                <button type="submit" class="btn btn-success">Save Prescription</button>
            </div>
        </form>
    </div>
</div>

@endsection
