<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('case_assignments', function (Blueprint $table) {
            $table->foreignId('rubric_id')->nullable()->after('clinical_case_id')
                ->constrained('evaluation_rubrics')->nullOnDelete();
        });
    }
    public function down(): void {
        Schema::table('case_assignments', fn (Blueprint $table) => $table->dropConstrainedForeignId('rubric_id'));
    }
};
