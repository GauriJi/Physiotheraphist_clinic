<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            // Therapy Plan info
            $table->string('therapy_plan_name')->nullable()->after('emergency_phone');
            $table->string('therapy_diagnosis')->nullable()->after('therapy_plan_name');
            $table->text('therapy_goal')->nullable()->after('therapy_diagnosis');
            $table->date('therapy_start_date')->nullable()->after('therapy_goal');
            $table->date('therapy_end_date')->nullable()->after('therapy_start_date');

            // Session Counters
            $table->unsignedInteger('sessions_purchased')->default(0)->after('therapy_end_date');
            $table->unsignedInteger('sessions_completed')->default(0)->after('sessions_purchased');
            $table->unsignedInteger('missed_days')->default(0)->after('sessions_completed');
            $table->date('last_visit_date')->nullable()->after('missed_days');
        });
    }

    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn([
                'therapy_plan_name', 'therapy_diagnosis', 'therapy_goal',
                'therapy_start_date', 'therapy_end_date',
                'sessions_purchased', 'sessions_completed',
                'missed_days', 'last_visit_date',
            ]);
        });
    }
};
