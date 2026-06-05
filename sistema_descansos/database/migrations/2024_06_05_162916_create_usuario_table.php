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
    Schema::create('usuario', function (Blueprint $table) {
        $table->id();
        $table->string('id_acceso')->unique()->nullable();
        $table->string('nombre_completo');
        $table->string('correo')->unique();
        $table->string('contrasena');
        $table->timestamp('ultimo_acceso')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuario');
    }
};
