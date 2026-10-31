<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nivel_educativo', 10)->nullable()->after('password');
            $table->string('curso', 50)->nullable()->after('nivel_educativo');
        });
    }
    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nivel_educativo', 'curso']);
        });
    }
};
