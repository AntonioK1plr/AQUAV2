<?php
use Illuminate\Database\Migrations\Migration;use Illuminate\Database\Schema\Blueprint;use Illuminate\Support\Facades\Schema;
return new class extends Migration {public function up():void{Schema::table('pedidos',fn(Blueprint $t)=>$t->text('direccion_entrega')->nullable());}public function down():void{Schema::table('pedidos',fn(Blueprint $t)=>$t->dropColumn('direccion_entrega'));}};
