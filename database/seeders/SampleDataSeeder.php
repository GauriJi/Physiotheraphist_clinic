<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Physiotherapist;
use App\Models\Specialty;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\CitaPublica;
use App\Models\MedicalHistory;
use Carbon\Carbon;

class SampleDataSeeder extends Seeder
{
    public function run(): void
    {
        // Buscar médico de prueba
        $medicoEmail = 'doctor@example.test';
        $physiotherapist = Physiotherapist::where('email', $medicoEmail)->first();

        if (! $physiotherapist) {
            $this->command->error('No se encontró el physiotherapist de prueba: '.$medicoEmail);
            return;
        }

        // Ensure specialty
        $specialty = Specialty::firstOrCreate(['name' => 'General Physiotherapy'], ['descripcion' => 'General physiotherapy specialty']);

        // Create example patients
        $pacientesData = [
            ['name' => 'Laura', 'last_name' => 'Gomez', 'email' => 'laura@example.test', 'phone' => '600111222', 'id_card' => 'V-12345678', 'fecha_nacimiento' => '1990-05-12'],
            ['name' => 'Carlos', 'last_name' => 'Perez', 'email' => 'carlos@example.test', 'phone' => '600333444', 'id_card' => 'V-87654321', 'fecha_nacimiento' => '1985-10-02'],
            ['name' => 'Maria', 'last_name' => 'Rodriguez', 'email' => 'maria@example.test', 'phone' => '600555666', 'id_card' => 'V-11223344', 'fecha_nacimiento' => '1992-08-20'],
        ];

        $patients = [];
        foreach ($pacientesData as $p) {
            $patients[] = Patient::updateOrCreate(
                ['email' => $p['email']],
                array_merge($p, ['address' => 'Av. Principal 123', 'sexo' => 'F'])
            );
        }

        // Create appointments internas (tabla 'appointments') para los patients
        $dates = [Carbon::today(), Carbon::tomorrow(), Carbon::today()->addDays(3), Carbon::today()->subDays(7)];

        foreach ($patients as $i => $patient) {
            $appointment = Appointment::create([
                'patient_id' => $patient->id,
                'physiotherapist_id' => $physiotherapist->id,
                'specialty_id' => $specialty->id,
                'date' => $dates[$i % count($dates)]->toDateString(),
                'time' => sprintf('%02d:00', 9 + $i),
                'status' => $i === 2 ? 'confirmed' : 'pending',
                'reason' => 'Lower back pain and follow-up',
            ]);

            // Create clinical history
            MedicalHistory::create([
                'patient_id' => $patient->id,
                'physiotherapist_id' => $physiotherapist->id,
                'appointment_id' => $appointment->id,
                'diagnostico' => 'Chronic lumbago',
                'tratamiento' => 'Strengthening exercises and manual therapy',
                'observaciones' => 'Good evolution after 3 sessions',
                'fecha_registro' => $dates[$i % count($dates)]->toDateString(),
            ]);
        }

        // Create appointments públicas (para el listado del admin)
        CitaPublica::create([
            'id_card' => 'V-9990001',
            'names' => 'Patient Publico',
            'last_names' => 'Uno',
            'email' => 'paciente1@example.test',
            'phone' => '600777888',
            'specialty_id' => $specialty->id,
            'physiotherapist_id' => $physiotherapist->id,
            'fecha_cita' => Carbon::today()->addDay()->toDateString(),
            'hora_cita' => '10:30',
            'reason' => 'Evaluación inicial',
            'status' => 'pendiente',
        ]);

        CitaPublica::create([
            'id_card' => 'V-9990002',
            'names' => 'Patient Publico',
            'last_names' => 'Dos',
            'email' => 'paciente2@example.test',
            'phone' => '600111999',
            'specialty_id' => $specialty->id,
            'physiotherapist_id' => $physiotherapist->id,
            'fecha_cita' => Carbon::today()->addDays(5)->toDateString(),
            'hora_cita' => '14:00',
            'reason' => 'Control de progreso',
            'status' => 'confirmed',
        ]);

        $this->command->info('Sample Seed: Patients, Appointments and Histories created.');
    }
}
