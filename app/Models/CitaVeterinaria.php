<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class CitaVeterinaria extends Model {
 protected $table='citas_veterinarias';
 protected $fillable=['user_id','nombre_asistente','nombre_mascota','especie','edad_mascota','sexo_mascota','peso_mascota','motivo','sintomas','antecedentes','ph_acuario','temp_acuario','documentos','fecha_hora','estatus','costo','pago_confirmado','motivo_cancelacion','reembolso_autorizado','metodo_pago','reembolso_pagado','reservado_hasta'];
 protected function casts():array{return ['fecha_hora'=>'datetime','reservado_hasta'=>'datetime','costo'=>'decimal:2','peso_mascota'=>'decimal:3','ph_acuario'=>'decimal:2','temp_acuario'=>'decimal:2','documentos'=>'array','pago_confirmado'=>'boolean','reembolso_autorizado'=>'boolean','reembolso_pagado'=>'boolean'];}
 public function user(){return $this->belongsTo(User::class);}
 public function expediente(){return $this->hasOne(ExpedienteClinico::class,'cita_id');}
}
