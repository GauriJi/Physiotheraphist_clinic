<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('therapy_plans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('physiotherapist_id')->nullable();

            // Plan details
            $table->string('plan_name');
            $table->string('diagnosis')->nullable();
            $table->text('goal')->nullable();
            $table->text('notes')->nullable();

            // Session scheduling
            $table->unsignedInteger('total_sessions')->default(1);
            $table->unsignedInteger('sessions_frequency')->default(1); // days between sessions
            $table->boolean('skip_sundays')->default(true);
            $table->time('session_time')->nullable();
            $table->unsignedInteger('session_duration')->default(60); // minutes

            // Dates (auto-calculated on creation)
            $table->date('start_date');
            $table->date('end_date')->nullable(); // auto-calculated

            // Plan status
            $table->enum('status', ['active', 'completed', 'cancelled'])->default('active');

            $table->timestamps();

            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
            $table->foreign('physiotherapist_id')->references('id')->on('physiotherapists')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('therapy_plans');
    }
};
