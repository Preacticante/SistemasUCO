<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dias_especiales', function (Blueprint $table) {
            $table->text('aplicado_a')->nullable()->after('observaciones');
        });
    }

    public function down(): void
    {
        Schema::table('dias_especiales', function (Blueprint $table) {
            $table->dropColumn('aplicado_a');
        });
    }
};
