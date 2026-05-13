<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['nombre_rol' => 'patient', 'descripcion' => 'Patient (user registrado desde formulario público)'],
            ['nombre_rol' => 'doctor', 'descripcion' => 'Médico o physiotherapist con cuenta asignada manualmente'],
            ['nombre_rol' => 'admin', 'descripcion' => 'Administrador del sistema con acceso al panel administrativo'],
        ];

        foreach ($roles as $r) {
            Role::firstOrCreate(['nombre_rol' => $r['nombre_rol']], $r);
        }
    }
}
