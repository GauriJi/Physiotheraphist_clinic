<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('doctor_notes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('appointment_id')->nullable();
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('physiotherapist_id')->nullable();
            $table->text('notes');
            $table->text('exercises')->nullable();
            $table->text('progress')->nullable();
            $table->string('next_session')->nullable();
            $table->enum('session_status', ['improving', 'stable', 'worsening', 'recovered'])->default('stable');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctor_notes');
    }
};
