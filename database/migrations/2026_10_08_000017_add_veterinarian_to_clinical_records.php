<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up():void{Schema::table('expedientes_clinicos',function(Blueprint $t){$t->foreignId('veterinario_id')->nullable()->constrained('users')->nullOnDelete();});}
 public function down():void{Schema::table('expedientes_clinicos',fn(Blueprint $t)=>$t->dropConstrainedForeignId('veterinario_id'));}
};
