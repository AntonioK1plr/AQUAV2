<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class CitaVeterinaria extends Model { protected $table='citas_veterinarias'; protected $fillable=['user_id','nombre_mascota','especie','motivo','fecha_hora','estatus','costo','pago_confirmado','motivo_cancelacion','reembolso_autorizado','metodo_pago','reembolso_pagado','reservado_hasta']; protected function casts():array{return ['fecha_hora'=>'datetime','reservado_hasta'=>'datetime','costo'=>'decimal:2','pago_confirmado'=>'boolean','reembolso_autorizado'=>'boolean','reembolso_pagado'=>'boolean'];} public function user(){return $this->belongsTo(User::class);} public function expediente(){return $this->hasOne(ExpedienteClinico::class,'cita_id');} }
