<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('physiotherapists', function (Blueprint $table) {
            $table->time('horario_inicio')->nullable()->after('user_id');
            $table->time('horario_fin')->nullable()->after('horario_inicio');
        });
    }

    public function down()
    {
        Schema::table('physiotherapists', function (Blueprint $table) {
            $table->dropColumn(['horario_inicio', 'horario_fin']);
        });
    }
};
