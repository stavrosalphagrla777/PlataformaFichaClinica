<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up():void {
  Schema::create('evaluation_rubrics',function(Blueprint $table){
   $table->id();$table->foreignId('docente_id')->constrained('users')->restrictOnDelete();
   $table->string('nombre',160);$table->string('nivel',25);$table->json('criterios');$table->timestamps();
  });
  Schema::table('clinical_cases',function(Blueprint $table){$table->foreignId('rubric_id')->nullable()->constrained('evaluation_rubrics')->nullOnDelete();});
  Schema::table('case_evaluations',function(Blueprint $table){$table->json('rubrica_criterios')->nullable();});
 }
 public function down():void {
  Schema::table('case_evaluations',fn(Blueprint $t)=>$t->dropColumn('rubrica_criterios'));
  Schema::table('clinical_cases',fn(Blueprint $t)=>$t->dropConstrainedForeignId('rubric_id'));
  Schema::dropIfExists('evaluation_rubrics');
 }
};
