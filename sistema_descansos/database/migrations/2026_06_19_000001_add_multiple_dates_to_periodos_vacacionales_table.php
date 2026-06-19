<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('periodos_vacacionales', function (Blueprint $table) {
            $table->text('multiple_dates')->nullable()->after('observaciones');
        });
    }

    public function down(): void
    {
        Schema::table('periodos_vacacionales', function (Blueprint $table) {
            $table->dropColumn('multiple_dates');
        });
    }
};
