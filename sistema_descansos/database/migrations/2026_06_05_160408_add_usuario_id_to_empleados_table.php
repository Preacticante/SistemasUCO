<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('empleados', function (Blueprint $table) {
            // Creamos la columna idéntica al ID de la tabla usuarios/usuario
            // 'after' sirve para acomodarla visualmente después del ID del empleado
            $table->unsignedBigInteger('usuario_id')->nullable()->after('id');

            // Opcional (Llave foránea): Vincula esta columna con la tabla de tus usuarios
            // Si tu tabla de perfiles se llama 'usuario', cambia 'users' por 'usuario'
            $table->foreign('usuario_id')->references('id')->on('usuario')->onDelete('set null');
        });

        if (!Schema::hasTable('periodos_vacacionales')) {
        Schema::create('periodos_vacacionales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empleado_id');
            // ... todos tus campos actuales ...
            $table->timestamps();
        });
    }

    }

    public function down(): void
    {
        Schema::table('empleados', function (Blueprint $table) {
            $table->dropForeign(['usuario_id']);
            $table->dropColumn('usuario_id');
        });
    }
};