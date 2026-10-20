<<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('signos_vitales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medical_record_id')->constrained('medical_records')->onDelete('cascade');            $table->foreignId('registrado_por_id')->constrained('users');
            $table->timestamp('fecha_hora_registro');
            
            $table->unsignedSmallInteger('frecuencia_cardiaca');
            $table->string('presion_arterial', 10);
            $table->unsignedSmallInteger('frecuencia_respiratoria');
            $table->decimal('temperatura', 4, 1);
            $table->unsignedSmallInteger('saturacion_oxigeno');
            $table->unsignedSmallInteger('glicemia_hgt')->nullable();
            
            $table->decimal('peso', 5, 2)->nullable();
            $table->decimal('talla', 3, 2)->nullable();
            $table->decimal('imc', 4, 1)->nullable();
            $table->string('clasificacion_imc')->nullable();
            
            $table->enum('estado_hemodinamico', ['normal', 'alerta', 'critico'])->default('normal');
            $table->text('observaciones')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('signos_vitales');
    }
};