<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();

            // Datos básicos del patient
            $table->string('name');
            $table->string('last_name')->nullable();
            $table->string('id_card')->nullable()->unique();
            $table->string('phone')->nullable();
            $table->string('email')->nullable()->unique();
            $table->date('fecha_nacimiento')->nullable();

            // Datos médicos generales
            $table->string('address')->nullable();
            $table->string('sexo')->nullable(); // F, M, Otro

            // Por si algún día agregas autenticación para patients
            $table->string('password')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
