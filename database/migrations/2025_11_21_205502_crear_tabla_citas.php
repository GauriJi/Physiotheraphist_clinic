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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();

            // Relaciones
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('physiotherapist_id');
            $table->unsignedBigInteger('specialty_id');

            // Datos de la appointment
            $table->date('date');
            $table->time('time');
            $table->enum('status', ['pendiente', 'confirmada', 'cancelada'])->default('pendiente');
            $table->text('reason')->nullable();

            $table->timestamps();

            // Llaves foráneas
            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
            $table->foreign('physiotherapist_id')->references('id')->on('physiotherapists')->onDelete('cascade');
            $table->foreign('specialty_id')->references('id')->on('specialties')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
