<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('triages', function (Blueprint $table) {
            //Permitir que escala_eva sea nullable
            $table->integer('escala_eva')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('triages', function (Blueprint $table) {
            $table->integer('escala_eva')->nullable(false)->change();
        });
    }
};