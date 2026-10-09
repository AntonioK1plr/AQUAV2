<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class BitacoraAuditoria extends Model { protected $table='bitacora_auditoria'; protected $fillable=['user_id','evento','ip_address','fecha_hora','hash_inmutable']; protected function casts():array{return ['fecha_hora'=>'datetime'];} public function user(){return $this->belongsTo(User::class);} }
