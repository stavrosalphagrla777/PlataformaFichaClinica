<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration{
    public function up():void {Schema::create('audit_events',function(Blueprint $t){
        $t->id();$t->foreignId('actor_id')->nullable()->constrained('users')->nullOnDelete();
        $t->foreignId('target_id')->nullable()->constrained('users')->nullOnDelete();
        $t->string('action',60);$t->string('description',255)->nullable();$t->timestamps();
    });}
    public function down():void {Schema::dropIfExists('audit_events');}
};
