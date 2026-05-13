<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->string('patient_uid', 20)->nullable()->unique()->after('id');
        });

        // Back-fill existing patients
        $patients = DB::table('patients')->orderBy('id')->get(['id']);
        foreach ($patients as $i => $p) {
            DB::table('patients')->where('id', $p->id)->update([
                'patient_uid' => 'PC-' . str_pad($p->id, 6, '0', STR_PAD_LEFT),
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn('patient_uid');
        });
    }
};
