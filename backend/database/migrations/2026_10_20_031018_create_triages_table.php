<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('triages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medical_record_id')->constrained('medical_records')->onDelete('cascade');            $table->foreignId('estudiante_id')->constrained('users');
            
            $table->text('motivo_consulta');
            $table->unsignedTinyInteger('prioridad_esi');
            $table->unsignedTinyInteger('escala_eva')->default(0);
            
            $table->unsignedSmallInteger('frecuencia_cardiaca');
            $table->string('presion_arterial', 10);
            $table->unsignedSmallInteger('frecuencia_respiratoria');
            $table->unsignedSmallInteger('saturacion_oxigeno');
            $table->decimal('temperatura', 4, 1);
            $table->unsignedTinyInteger('escala_glasgow')->default(15);
            
            $table->decimal('peso', 5, 2)->nullable();
            $table->unsignedSmallInteger('talla')->nullable();
            
            $table->string('nivel_prioridad_calculado');
            $table->unsignedInteger('tiempo_maximo_espera_min');
            $table->json('riesgos_identificados')->nullable();
            $table->text('observaciones')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('triages');
    }
};