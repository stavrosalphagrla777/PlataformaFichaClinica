<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration{
 public function up():void{
  Schema::table('fichas_clinicas',function(Blueprint $t){
   $t->foreignId('medical_record_id')->nullable()->unique()->constrained('medical_records')->nullOnDelete();
  });
 }
 public function down():void{
  Schema::table('fichas_clinicas',function(Blueprint $t){$t->dropConstrainedForeignId('medical_record_id');});
 }
};
