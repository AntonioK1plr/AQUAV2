<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up():void{
  Schema::table('citas_veterinarias',function(Blueprint $t){
   $t->string('edad_mascota',80)->nullable();$t->string('sexo_mascota',24)->nullable();$t->decimal('peso_mascota',8,3)->nullable();
   $t->text('sintomas')->nullable();$t->text('antecedentes')->nullable();$t->decimal('ph_acuario',4,2)->nullable();
   $t->decimal('temp_acuario',5,2)->nullable();$t->json('documentos')->nullable();
  });
  Schema::table('expedientes_clinicos',function(Blueprint $t){
   $t->string('edad_mascota',80)->nullable();$t->string('sexo_mascota',24)->nullable();$t->decimal('peso_mascota',8,3)->nullable();
   $t->text('sintomas')->nullable();$t->text('antecedentes')->nullable();$t->string('medicamento')->nullable();
   $t->string('dosis')->nullable();$t->string('frecuencia')->nullable();$t->string('duracion')->nullable();$t->text('observaciones')->nullable();
  });
 }
 public function down():void{
  Schema::table('citas_veterinarias',fn(Blueprint $t)=>$t->dropColumn(['edad_mascota','sexo_mascota','peso_mascota','sintomas','antecedentes','ph_acuario','temp_acuario','documentos']));
  Schema::table('expedientes_clinicos',fn(Blueprint $t)=>$t->dropColumn(['edad_mascota','sexo_mascota','peso_mascota','sintomas','antecedentes','medicamento','dosis','frecuencia','duracion','observaciones']));
 }
};
