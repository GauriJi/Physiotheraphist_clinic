<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;
use App\Models\Specialty;
use App\Models\Physiotherapist;

class TestUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles si no existen
        $rolAdmin = Role::firstOrCreate(['nombre_rol' => 'admin'], ['descripcion' => 'Administrador del sistema']);
        $rolMedico = Role::firstOrCreate(['nombre_rol' => 'doctor'], ['descripcion' => 'Médico / Physiotherapist']);

        // Credenciales de prueba
        $adminEmail = 'admin@example.test';
        $adminPassword = 'AdminPass123!';

        $medicoEmail = 'doctor@example.test';
        $medicoPassword = 'MedicoPass123!';

        // Create user admin
        $admin = User::updateOrCreate(
            ['email' => $adminEmail],
            [
                'name' => 'Admin Demo',
                'email' => $adminEmail,
                'password' => Hash::make($adminPassword),
                'role_id' => $rolAdmin->id,
            ]
        );

        // Create user doctor
        $medicoUser = User::updateOrCreate(
            ['email' => $medicoEmail],
            [
                'name' => 'Doctor Demo',
                'email' => $medicoEmail,
                'password' => Hash::make($medicoPassword),
                'role_id' => $rolMedico->id,
            ]
        );

        // Asegurar una specialty para el médico
        $specialty = Specialty::firstOrCreate(
            ['name' => 'Fisioterapia General'],
            ['descripcion' => 'Specialty de fisioterapia general']
        );

        // Create registro de physiotherapist asociado al email del médico (si no existe)
        $fisio = Physiotherapist::firstOrCreate(
            ['email' => $medicoEmail],
            [
                'name' => 'Doctor',
                'last_name' => 'Demo',
                'email' => $medicoEmail,
                'phone' => '600000000',
                'specialty_id' => $specialty->id,
                'numero_colegiado' => 'MD-TEST-001',
            ]
        );

        // Mostrar en consola (si se ejecuta por artisan db:seed)
        $this->command->info("Seeder de prueba: user admin => {$adminEmail} / {$adminPassword}");
        $this->command->info("Seeder de prueba: user doctor => {$medicoEmail} / {$medicoPassword}");
    }
}
