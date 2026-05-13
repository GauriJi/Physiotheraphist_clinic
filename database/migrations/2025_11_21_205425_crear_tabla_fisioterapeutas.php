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
        Schema::create('physiotherapists', function (Blueprint $table) {
            $table->id();

            // Información personal
            $table->string('name');
            $table->string('last_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable()->unique();

            // Datos profesionales
            $table->unsignedBigInteger('specialty_id')->nullable(); // Relación con tabla specialties
            $table->string('numero_colegiado')->nullable(); // Si tienen número profesional

            // Opcional (si algún día quieres login para ellos)
            $table->string('password')->nullable();

            $table->timestamps();

            // Llave foránea
            $table->foreign('specialty_id')
                  ->references('id')
                  ->on('specialties')
                  ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('physiotherapists');
    }
};
