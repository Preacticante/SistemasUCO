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
        if (Schema::hasTable('periodos_vacacionales')) {
            Schema::table('periodos_vacacionales', function (Blueprint $table) {
                if (!Schema::hasColumn('periodos_vacacionales', 'observaciones')) {
                    $table->text('observaciones')->nullable()->after('dias');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('periodos_vacacionales')) {
            Schema::table('periodos_vacacionales', function (Blueprint $table) {
                if (Schema::hasColumn('periodos_vacacionales', 'observaciones')) {
                    $table->dropColumn('observaciones');
                }
            });
        }
    }
};
