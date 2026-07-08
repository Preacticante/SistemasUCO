<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ajustes_dias_vacaciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('empleado_id');
            $table->integer('anio');
            $table->integer('dias');
            $table->string('motivo')->nullable();
            $table->timestamps();
            
            $table->foreign('empleado_id')->references('id')->on('empleados')->onDelete('cascade');
            $table->unique(['empleado_id', 'anio']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ajustes_dias_vacaciones');
    }
};
