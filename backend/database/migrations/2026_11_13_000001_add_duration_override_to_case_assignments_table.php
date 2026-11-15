<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('case_assignments', function (Blueprint $table) {
            $table->unsignedInteger('duracion_minutos')->nullable()->after('curso');
        });
    }

    public function down(): void
    {
        Schema::table('case_assignments', function (Blueprint $table) {
            $table->dropColumn('duracion_minutos');
        });
    }
};
