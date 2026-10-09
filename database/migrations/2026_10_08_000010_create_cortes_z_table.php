<?php
use Illuminate\Database\Migrations\Migration; use Illuminate\Database\Schema\Blueprint; use Illuminate\Support\Facades\Schema;
return new class extends Migration {public function up():void{Schema::create('cortes_z',function(Blueprint $t){$t->id();$t->foreignId('user_id')->constrained()->restrictOnDelete();$t->json('reporte');$t->timestamp('created_at')->useCurrent()->index();});}public function down():void{Schema::dropIfExists('cortes_z');}};
