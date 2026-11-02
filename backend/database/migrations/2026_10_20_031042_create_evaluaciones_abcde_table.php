<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('evaluaciones_abcde', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medical_record_id')->constrained('medical_records')->onDelete('cascade');            $table->foreignId('estudiante_id')->constrained('users');
            
            // A - Vía Aérea
            $table->enum('obstruccion_via_aerea', ['no', 'parcial', 'total'])->default('no');
            $table->enum('collar_cervical', ['no_indicado', 'indicado', 'colocado'])->default('no_indicado');
            $table->enum('aspiracion_secreciones', ['no_realizada', 'realizada'])->default('no_realizada');
            $table->enum('intubacion', ['no_realizada', 'orotraqueal', 'nasotraqueal'])->default('no_realizada');
            $table->text('observaciones_a')->nullable();
            
            // B - Respiración
            $table->enum('murmullo_pulmonar', ['normal', 'disminuido', 'ausente'])->default('normal');
            $table->enum('simetria_toracica', ['simetrica', 'asimetrica'])->default('simetrica');
            $table->boolean('oxigenoterapia')->default(false);
            $table->string('tipo_dispositivo_o2')->nullable();
            $table->unsignedSmallInteger('flujo_o2_lmin')->nullable();
            $table->enum('ventilacion', ['espontanea', 'asistida', 'mecanica'])->default('espontanea');
            $table->text('observaciones_b')->nullable();
            
            // C - Circulación
            $table->unsignedSmallInteger('pulso_frecuencia')->nullable();
            $table->enum('pulso_ritmo', ['regular', 'irregular'])->default('regular');
            $table->enum('pulso_calidad', ['fuerte', 'debil', 'filiforme'])->default('fuerte');
            $table->enum('hemorragias', ['no_presenta', 'externas', 'internas', 'controladas'])->default('no_presenta');
            $table->enum('llenado_capilar', ['menor_2seg', '2_4seg', 'mayor_4seg'])->default('menor_2seg');
            $table->unsignedTinyInteger('accesos_venosos_perifericos')->default(0);
            $table->text('observaciones_c')->nullable();
            
            // D - Neurológico
            $table->unsignedTinyInteger('glasgow_apertura_ocular');
            $table->unsignedTinyInteger('glasgow_respuesta_verbal');
            $table->unsignedTinyInteger('glasgow_respuesta_motora');
            $table->unsignedTinyInteger('total_glasgow');
            $table->enum('pupilas', ['iguales_reactivas', 'iguales_no_reactivas', 'anisocoricas', 'midriaticas', 'mioticas'])->default('iguales_reactivas');
            $table->enum('escala_avdi', ['alerta', 'verbal', 'dolor', 'no_responde'])->default('alerta');
            $table->text('observaciones_d')->nullable();
            
            // E - Exposición
            $table->boolean('lesiones_heridas')->default(false);
            $table->boolean('lesiones_fracturas')->default(false);
            $table->boolean('lesiones_contusiones')->default(false);
            $table->enum('temperatura_corporal', ['normal', 'hipotermia_leve', 'hipotermia_severa', 'hipertermia'])->default('normal');
            $table->boolean('quemaduras')->default(false);
            $table->unsignedTinyInteger('porcentaje_scq')->default(0);
            $table->text('observaciones_e')->nullable();
            
            $table->text('observaciones_generales')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('evaluaciones_abcde');
    }
};
