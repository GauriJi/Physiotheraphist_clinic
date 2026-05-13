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
        Schema::create('medical_histories', function (Blueprint $table) {
            $table->id();

            // Relaciones
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('physiotherapist_id')->nullable();
            $table->unsignedBigInteger('appointment_id')->nullable();

            // Información clínica
            $table->text('diagnostico')->nullable();
            $table->text('tratamiento')->nullable();
            $table->text('observaciones')->nullable();

            // Fecha del registro
            $table->date('fecha_registro')->default(now());

            $table->timestamps();

            // Llaves foráneas
            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
            $table->foreign('physiotherapist_id')->references('id')->on('physiotherapists')->onDelete('set null');
            $table->foreign('appointment_id')->references('id')->on('appointments')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medical_histories');
    }
};
