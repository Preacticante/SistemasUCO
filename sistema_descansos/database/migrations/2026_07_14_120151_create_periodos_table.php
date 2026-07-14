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
        Schema::create('periodos', function (Blueprint $table) {
            // id bigint unsigned NOT NULL AUTO_INCREMENT
            $table->id(); 
            
            // empleado_id int NOT NULL
            // Nota: Se usa integer() porque tu SQL original no es unsigned
            $table->integer('empleado_id'); 
            
            // anio int NOT NULL
            $table->integer('anio'); 
            
            // antiguedad_calculada int NOT NULL (Con tu comentario informativo)
            $table->integer('antiguedad_calculada')->comment('Guarda los años que cumplió'); 
            
            // dias_asignados int NOT NULL DEFAULT 0
            $table->integer('dias_asignados')->default(0); 
            
            // dias_disponibles int NOT NULL DEFAULT 0
            $table->integer('dias_disponibles')->default(0); 
            
            // created_at y updated_at timestamp NULL DEFAULT NULL
            $table->timestamps(); 
            
            // deleted_at timestamp NULL DEFAULT NULL (Para SoftDeletes)
            $table->softDeletes(); 

            // UNIQUE KEY empleado_anio_unique (empleado_id, anio)
            $table->unique(['empleado_id', 'anio'], 'empleado_anio_unique');

            // CONSTRAINT periodos_empleado_ibfk FOREIGN KEY ... REFERENCES empleados(id) ON DELETE CASCADE
            $table->foreign('empleado_id')
                  ->references('id')
                  ->on('empleados')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('periodos');
    }
};