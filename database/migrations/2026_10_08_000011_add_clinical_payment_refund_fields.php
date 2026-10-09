<?php
use Illuminate\Database\Migrations\Migration;use Illuminate\Database\Schema\Blueprint;use Illuminate\Support\Facades\Schema;
return new class extends Migration {public function up():void{Schema::table('citas_veterinarias',function(Blueprint $t){$t->string('metodo_pago',20)->nullable();$t->boolean('reembolso_pagado')->default(false);});}public function down():void{Schema::table('citas_veterinarias',fn(Blueprint $t)=>$t->dropColumn(['metodo_pago','reembolso_pagado']));}};
