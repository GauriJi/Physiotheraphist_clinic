<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->string('photo')->nullable()->after('phone');
            $table->string('blood_group', 10)->nullable()->after('photo');
            $table->string('emergency_contact')->nullable()->after('blood_group');
            $table->string('emergency_phone', 20)->nullable()->after('emergency_contact');
        });
    }

    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn(['photo', 'blood_group', 'emergency_contact', 'emergency_phone']);
        });
    }
};
