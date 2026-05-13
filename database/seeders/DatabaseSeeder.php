<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create default roles
        $this->call([
            \Database\Seeders\RolesTableSeeder::class,
            \Database\Seeders\EspecialidadesYFisioterapeutasSeeder::class,
        ]);

        // Create a test user if none exists
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
