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
        Schema::create('medical_records', function (Blueprint $table) {
            $table->id();

            //  Relaciones con la tabla de usuarios
            $table->foreignId('user_id')->comment('Profesional de salud')->constrained('users')->onDelete('cascade');
            $table->foreignId('patient_id')->comment('Estudiante o paciente')->constrained('users')->onDelete('cascade');

            // Clasificación de gravedad
            $table->unsignedTinyInteger('esi_triage');

            // 🩺 Signos Vitales
            $table->string('blood_pressure')->nullable();
            $table->unsignedSmallInteger('heart_rate')->nullable();
            $table->unsignedSmallInteger('respiratory_rate')->nullable();
            $table->decimal('temperature', 4, 1)->nullable();

            // Antropometría
            $table->decimal('weight', 5, 2)->nullable();
            $table->decimal('height', 5, 2)->nullable();
            $table->decimal('bmi', 4, 2)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medical_records');
    }
};