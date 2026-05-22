<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('registros_descanso', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('empleado_id')->index();
            $table->integer('anio_calendario');
            $table->tinyInteger('mes');
            $table->integer('dias_tomados')->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registros_descanso');
    }
};
