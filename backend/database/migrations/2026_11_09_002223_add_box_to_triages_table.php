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
        Schema::table('triages', function (Blueprint $table) {
            // 🚀 Agregar campo box (asignación automática)
            $table->string('box', 10)->nullable()->after('tiempo_maximo_espera_min');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('triages', function (Blueprint $table) {
            // Eliminar el campo box si se revierte la migración
            $table->dropColumn('box');
        });
    }
};