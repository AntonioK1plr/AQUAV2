<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration { public function up(): void { Schema::create('productos', function (Blueprint $t) { $t->id(); $t->string('nombre')->index(); $t->text('descripcion')->nullable(); $t->decimal('precio',10,2); $t->unsignedInteger('stock')->default(0); $t->string('tipo_agua',16)->nullable()->index(); $t->string('marca')->nullable()->index(); $t->decimal('ph_min',4,2)->nullable(); $t->decimal('ph_max',4,2)->nullable(); $t->decimal('temp_min',5,2)->nullable(); $t->decimal('temp_max',5,2)->nullable(); $t->string('temperamento')->nullable(); $t->boolean('es_ser_vivo')->default(false)->index(); $t->string('codigo_barras')->nullable()->unique(); $t->timestamps(); $t->index(['nombre','marca']); }); } public function down(): void { Schema::dropIfExists('productos'); } };
