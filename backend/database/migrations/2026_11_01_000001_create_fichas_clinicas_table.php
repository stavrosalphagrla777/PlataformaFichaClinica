<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fichas_clinicas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Estudiante que crea la ficha
            $table->foreignId('paciente_id')->nullable(); // ID del paciente simulado
            $table->string('estado')->default('en_espera'); // en_espera, en_atencion, finalizada
            $table->string('motivo_consulta')->nullable();
            $table->text('antecedentes')->nullable();
            $table->text('alergias')->nullable();
            $table->text('medicamentos_habituales')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fichas_clinicas');
    }
};
