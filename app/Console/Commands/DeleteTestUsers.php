<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class DeleteTestUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:delete-test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Elimina todos los users cuyo email contiene "test"';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = User::where('email', 'like', '%test%')->count();
        if ($count === 0) {
            $this->info('No se encontraron users de test para eliminar.');
            return 0;
        }
        $deleted = User::where('email', 'like', '%test%')->delete();
        $this->info("Users de test eliminados: {$deleted}");
        return 0;
    }
}
