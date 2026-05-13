<?php

namespace Database\Seeders;

use App\Models\Specialty;
use App\Models\Physiotherapist;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class EspecialidadesYFisioterapeutasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create specialties
        $specialties = [
            ['name' => 'Traumatología', 'descripcion' => 'Tratamiento de lesiones óseas y articulares'],
            ['name' => 'Neurología', 'descripcion' => 'Rehabilitación del sistema nervioso'],
            ['name' => 'Cardiología', 'descripcion' => 'Rehabilitación cardiaca'],
            ['name' => 'Pediatría', 'descripcion' => 'Fisioterapia pediátrica'],
            ['name' => 'Geriatría', 'descripcion' => 'Atención a adultos mayores'],
            ['name' => 'Deportiva', 'descripcion' => 'Lesiones y recuperación deportiva'],
            ['name' => 'Respiratoria', 'descripcion' => 'Tratamiento de enfermedades respiratorias'],
            ['name' => 'Oncología', 'descripcion' => 'Rehabilitación post-cáncer'],
        ];

        $espec_ids = [];
        foreach ($specialties as $esp) {
            $created = Specialty::firstOrCreate(['name' => $esp['name']], $esp);
            $espec_ids[] = $created->id;
        }

        // Create physiotherapists de prueba
        $physiotherapists = [
            [
                'name' => 'Carlos',
                'last_name' => 'Martínez',
                'phone' => '+58 412-123-4567',
                'email' => 'carlos@fisiocarealya.com',
                'specialty_id' => $espec_ids[0],
                'numero_colegiado' => 'COL-2023-001',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'María',
                'last_name' => 'López',
                'phone' => '+58 412-234-5678',
                'email' => 'maria@fisiocarealya.com',
                'specialty_id' => $espec_ids[1],
                'numero_colegiado' => 'COL-2023-002',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Juan',
                'last_name' => 'García',
                'phone' => '+58 412-345-6789',
                'email' => 'juan@fisiocarealya.com',
                'specialty_id' => $espec_ids[5],
                'numero_colegiado' => 'COL-2023-003',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Ana',
                'last_name' => 'Rodríguez',
                'phone' => '+58 412-456-7890',
                'email' => 'ana@fisiocarealya.com',
                'specialty_id' => $espec_ids[3],
                'numero_colegiado' => 'COL-2023-004',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Pedro',
                'last_name' => 'Fernández',
                'phone' => '+58 412-567-8901',
                'email' => 'pedro@fisiocarealya.com',
                'specialty_id' => $espec_ids[2],
                'numero_colegiado' => 'COL-2023-005',
                'password' => Hash::make('password'),
            ],
        ];

        foreach ($physiotherapists as $fis) {
            Physiotherapist::firstOrCreate(['numero_colegiado' => $fis['numero_colegiado']], $fis);
        }

        $this->command->info('Specialties y Physiotherapists creados exitosamente!');
    }
}
