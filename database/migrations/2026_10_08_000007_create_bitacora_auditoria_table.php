<?php
use Illuminate\Database\Migrations\Migration; use Illuminate\Database\Schema\Blueprint; use Illuminate\Support\Facades\Schema;
return new class extends Migration { public function up(): void { Schema::create('bitacora_auditoria', function(Blueprint $t){ $t->id(); $t->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); $t->string('evento'); $t->string('ip_address',45)->nullable(); $t->dateTime('fecha_hora')->useCurrent(); $t->char('hash_inmutable',64)->index(); $t->timestamps(); }); } public function down(): void { Schema::dropIfExists('bitacora_auditoria'); } };
