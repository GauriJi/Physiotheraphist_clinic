<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('therapy_sessions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('therapy_plan_id');
            $table->unsignedBigInteger('patient_id');

            // Session identity
            $table->unsignedInteger('session_number'); // 1, 2, 3 … N

            // Scheduling
            $table->date('scheduled_date');
            $table->time('scheduled_time')->nullable();
            $table->unsignedInteger('duration')->default(60); // minutes

            // Status
            $table->enum('status', ['upcoming', 'completed', 'missed', 'rescheduled', 'cancelled'])
                  ->default('upcoming');

            // Outcome
            $table->date('actual_date')->nullable();         // when actually completed
            $table->date('original_date')->nullable();       // original date if rescheduled
            $table->text('therapist_notes')->nullable();
            $table->unsignedBigInteger('marked_by')->nullable();

            $table->timestamps();

            $table->foreign('therapy_plan_id')->references('id')->on('therapy_plans')->onDelete('cascade');
            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
            $table->foreign('marked_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('therapy_sessions');
    }
};
