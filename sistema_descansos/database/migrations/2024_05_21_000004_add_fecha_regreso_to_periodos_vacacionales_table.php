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
        // Esta migración ya no es necesaria porque las columnas se agregan en la migración principal
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No hace nada
    }
};
