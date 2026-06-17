<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('periodos_vacacionales', function (Blueprint $blueprint) {
            // Cambiamos las columnas para que acepten NULL
            $blueprint->date('fecha_inicio')->nullable()->change();
            $blueprint->date('fecha_fin')->nullable()->change();
            $blueprint->date('fecha_regreso')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('periodos_vacacionales', function (Blueprint $blueprint) {
            // Por si quieres revertir el cambio en el futuro (volverlas obligatorias)
            $blueprint->date('fecha_inicio')->nullable(false)->change();
            $blueprint->date('fecha_fin')->nullable(false)->change();
            $blueprint->date('fecha_regreso')->nullable(false)->change();
        });
    }
};