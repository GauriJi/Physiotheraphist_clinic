<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prescription - {{ $prescription->patient->full_name }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Inter', sans-serif; }
        body { background: #e2e8f0; padding: 2rem; color: #0f172a; }
        
        .print-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 3rem 4rem;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid #0d9488;
            padding-bottom: 1.5rem;
            margin-bottom: 2rem;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .brand img {
            width: 50px; height: 50px;
        }
        .brand-text h1 { font-size: 24px; font-weight: 800; color: #0f172a; letter-spacing: -0.025em; }
        .brand-text p { font-size: 12px; color: #64748b; }

        .clinic-info {
            text-align: right;
            font-size: 12px;
            color: #475569;
            line-height: 1.6;
        }

        .doctor-info {
            margin-bottom: 2rem;
        }
        .doctor-info h2 { font-size: 18px; font-weight: 700; color: #0f172a; }
        .doctor-info p { font-size: 13px; color: #64748b; }

        .patient-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 1rem 1.5rem;
            margin-bottom: 2rem;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }
        .p-field {
            font-size: 13px;
        }
        .p-field strong { color: #475569; display: inline-block; width: 100px; }
        .p-field span { color: #0f172a; font-weight: 600; }

        .rx-title {
            font-size: 32px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 1.5rem;
            font-family: serif;
            letter-spacing: -0.05em;
        }

        .section {
            margin-bottom: 2rem;
        }
        .section-title {
            font-size: 14px;
            font-weight: 700;
            color: #0f172a;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 0.5rem;
            margin-bottom: 1rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .section-content {
            font-size: 14px;
            line-height: 1.6;
            color: #1e293b;
            white-space: pre-line;
        }

        .footer {
            margin-top: 4rem;
            padding-top: 2rem;
            border-top: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }
        .signature {
            text-align: center;
        }
        .sig-line {
            width: 200px;
            border-bottom: 1px solid #0f172a;
            margin-bottom: 0.5rem;
        }
        .sig-name {
            font-size: 14px;
            font-weight: 600;
            color: #0f172a;
        }
        .sig-title {
            font-size: 12px;
            color: #64748b;
        }

        .meta {
            font-size: 12px;
            color: #94a3b8;
        }

        .print-btn {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            background: #0d9488;
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 10px 15px -3px rgba(13, 148, 136, 0.4);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s;
        }
        .print-btn:hover {
            transform: translateY(-2px);
            background: #0f766e;
        }

        @media print {
            body { background: white; padding: 0; }
            .print-container { box-shadow: none; padding: 0; max-width: 100%; }
            .print-btn { display: none; }
            @page { margin: 2cm; }
        }
    </style>
</head>
<body>

    <button onclick="window.print()" class="print-btn">
        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
        Print Prescription
    </button>

    <div class="print-container">
        <div class="header">
            <div class="brand">
                <img src="{{ asset('images/physiocare_logo_premium.png') }}" alt="PhysioCare">
                <div class="brand-text">
                    <h1>PhysioCare</h1>
                    <p>Advanced Physiotherapy & Rehabilitation</p>
                </div>
            </div>
            <div class="clinic-info">
                123 Health Avenue, Medical District<br>
                Cityville, ST 12345<br>
                Phone: +1 (555) 123-4567<br>
                contact@physiocare.com
            </div>
        </div>

        <div class="doctor-info">
            <h2>Dr. {{ $prescription->physiotherapist->name }} {{ $prescription->physiotherapist->last_name }}</h2>
            <p>{{ $prescription->physiotherapist->specialty->name ?? 'Physiotherapist' }}</p>
            <p>Reg No: {{ $prescription->physiotherapist->numero_colegiado }}</p>
        </div>

        <div class="patient-card">
            <div class="p-field"><strong>Patient Name:</strong> <span>{{ $prescription->patient->full_name }}</span></div>
            <div class="p-field"><strong>Date:</strong> <span>{{ $prescription->created_at->format('F d, Y') }}</span></div>
            <div class="p-field"><strong>Age/Sex:</strong> <span>{{ $prescription->patient->age }}Y / {{ ucfirst($prescription->patient->sexo ?? 'N/A') }}</span></div>
            <div class="p-field"><strong>Patient ID:</strong> <span>{{ $prescription->patient->id_card ?? 'N/A' }}</span></div>
        </div>

        <div class="rx-title">Rx</div>

        <div class="section">
            <div class="section-title">Clinical Diagnosis & Notes</div>
            <div class="section-content">{{ $prescription->notes }}</div>
        </div>

        @if($prescription->exercises)
        <div class="section">
            <div class="section-title">Recommended Therapy & Exercises</div>
            <div class="section-content">{{ $prescription->exercises }}</div>
        </div>
        @endif

        <div class="section">
            <div class="section-title">Treatment Plan</div>
            <div class="section-content">
                <strong>Current Status:</strong> {{ ucfirst($prescription->session_status) }}
                @if($prescription->next_session)
                <br><strong>Follow-up:</strong> {{ $prescription->next_session }}
                @endif
            </div>
        </div>

        <div class="footer">
            <div class="meta">
                Ref: RX-{{ str_pad($prescription->id, 6, '0', STR_PAD_LEFT) }}<br>
                Generated securely by PhysioCare Platform
            </div>
            <div class="signature">
                <div class="sig-line"></div>
                <div class="sig-name">Dr. {{ $prescription->physiotherapist->name }} {{ $prescription->physiotherapist->last_name }}</div>
                <div class="sig-title">Physiotherapist Signature</div>
            </div>
        </div>
    </div>

</body>
</html>
