<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('clinical_cases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('docente_id')->constrained('users')->restrictOnDelete();
            $table->string('codigo', 40)->unique();
            $table->string('titulo', 180);
            $table->longText('descripcion');
            $table->longText('antecedentes')->nullable();
            $table->string('nivel', 25)->default('4.º medio');
            $table->string('tipo', 40)->default('Tarea');
            $table->string('estado', 25)->default('Borrador');
            $table->string('servicio', 60)->default('Servicio de urgencias');
            $table->unsignedInteger('duracion');
            $table->string('paciente', 150)->nullable();
            $table->unsignedSmallInteger('edad')->nullable();
            $table->string('sexo', 35)->nullable();
            $table->string('prevision', 40)->nullable();
            $table->string('esi', 12)->nullable();
            $table->timestamps();
        });

        Schema::create('case_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinical_case_id')->constrained('clinical_cases')->restrictOnDelete();
            $table->foreignId('docente_id')->constrained('users')->restrictOnDelete();
            $table->string('nivel', 25);
            $table->string('curso', 50);
            $table->dateTime('inicio');
            $table->dateTime('termino');
            $table->string('estado', 25)->default('Programada');
            $table->text('observaciones')->nullable();
            $table->timestamps();
            $table->index(['nivel', 'curso', 'inicio', 'termino'], 'assignments_access_index');
        });

        Schema::create('student_cases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('case_assignment_id')->constrained('case_assignments')->restrictOnDelete();
            $table->foreignId('estudiante_id')->constrained('users')->restrictOnDelete();
            $table->dateTime('started_at');
            $table->dateTime('submitted_at')->nullable();
            $table->string('estado', 25)->default('En desarrollo');
            $table->timestamps();
            $table->unique(['case_assignment_id', 'estudiante_id']);
        });

        Schema::create('case_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_case_id')->constrained('student_cases')->cascadeOnDelete();
            $table->string('clave', 45);
            $table->string('storage_key', 160);
            $table->string('storage_type', 10);
            $table->longText('payload');
            $table->timestamps();
            $table->unique(['student_case_id', 'clave']);
        });

        Schema::create('case_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_case_id')->unique()->constrained('student_cases')->cascadeOnDelete();
            $table->foreignId('docente_id')->constrained('users')->restrictOnDelete();
            $table->json('criterios');
            $table->json('notas')->nullable();
            $table->text('retroalimentacion')->nullable();
            $table->decimal('porcentaje', 5, 2)->nullable();
            $table->decimal('nota', 3, 1)->nullable();
            $table->string('estado', 20)->default('borrador');
            $table->dateTime('finalizada_at')->nullable();
            $table->timestamps();
        });

        Schema::create('clinical_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('docente_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('clinical_case_id')->nullable()->constrained('clinical_cases')->nullOnDelete();
            $table->dateTime('fecha_documento')->nullable();
            $table->string('titulo', 120);
            $table->text('descripcion')->nullable();
            $table->string('tipo', 25);
            $table->string('nivel', 25);
            $table->string('estado', 20)->default('Borrador');
            $table->string('archivo_path')->nullable();
            $table->string('archivo_nombre')->nullable();
            $table->string('archivo_mime', 100)->nullable();
            $table->unsignedBigInteger('archivo_size')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clinical_materials');
        Schema::dropIfExists('case_evaluations');
        Schema::dropIfExists('case_sections');
        Schema::dropIfExists('student_cases');
        Schema::dropIfExists('case_assignments');
        Schema::dropIfExists('clinical_cases');
    }
};
