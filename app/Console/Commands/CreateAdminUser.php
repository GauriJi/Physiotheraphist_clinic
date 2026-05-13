<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create-admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crea un user administrador de forma interactiva (solo para uso local)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->ask('Nombre completo');
        $email = $this->ask('Correo electrónico');
        $password = $this->secret('Password');
        $role_id = $this->ask('ID de role para administrador (ej: 1)');

        if (User::where('email', $email)->exists()) {
            $this->error('Ya existe un user con ese email.');
            return 1;
        }

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'role_id' => $role_id,
        ]);

        $this->info('User administrador creado correctamente: ' . $user->email);
        return 0;
    }
}
