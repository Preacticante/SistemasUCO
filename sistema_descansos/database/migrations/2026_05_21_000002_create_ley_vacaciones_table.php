<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ley_vacaciones', function (Blueprint $table) {
            $table->integer('anios_antiguedad')->primary();
            $table->integer('dias_derecho')->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ley_vacaciones');
    }
};
