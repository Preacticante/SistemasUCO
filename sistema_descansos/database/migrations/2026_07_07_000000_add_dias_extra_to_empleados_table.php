<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('empleados', function (Blueprint $table) {
            if (! Schema::hasColumn('empleados', 'dias_extra')) {
                $table->integer('dias_extra')->default(0)->after('usuario_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('empleados', function (Blueprint $table) {
            if (Schema::hasColumn('empleados', 'dias_extra')) {
                $table->dropColumn('dias_extra');
            }
        });
    }
};
