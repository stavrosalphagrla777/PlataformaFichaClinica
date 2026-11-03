<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('signos_vitales', function (Blueprint $table) {
            $table->text('interpretacion_clinica')->nullable()->after('observaciones');
        });
    }

    public function down(): void
    {
        Schema::table('signos_vitales', function (Blueprint $table) {
            $table->dropColumn('interpretacion_clinica');
        });
    }
};