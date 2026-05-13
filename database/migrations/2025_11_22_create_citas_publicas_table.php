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
        Schema::create('public_appointments', function (Blueprint $table) {
            $table->id();
            $table->string('id_card')->unique();
            $table->string('names');
            $table->string('last_names');
            $table->string('email');
            $table->string('phone');
            $table->unsignedBigInteger('specialty_id');
            $table->unsignedBigInteger('physiotherapist_id');
            $table->date('fecha_cita');
            $table->time('hora_cita');
            $table->text('reason');
            $table->enum('status', ['pendiente', 'confirmada', 'cancelada'])->default('pendiente');
            $table->timestamps();

            // Claves foráneas
            $table->foreign('specialty_id')->references('id')->on('specialties')->onDelete('cascade');
            $table->foreign('physiotherapist_id')->references('id')->on('physiotherapists')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('public_appointments');
    }
};
