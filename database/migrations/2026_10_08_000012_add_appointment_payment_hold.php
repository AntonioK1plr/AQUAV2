<?php
use Illuminate\Database\Migrations\Migration;use Illuminate\Database\Schema\Blueprint;use Illuminate\Support\Facades\Schema;
return new class extends Migration {public function up():void{Schema::table('citas_veterinarias',fn(Blueprint $t)=>$t->timestamp('reservado_hasta')->nullable()->index());}public function down():void{Schema::table('citas_veterinarias',fn(Blueprint $t)=>$t->dropColumn('reservado_hasta'));}};
