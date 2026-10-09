<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class VentaPos extends Model {public $timestamps=false;protected $table='ventas_pos';protected $fillable=['user_id','detalle','metodo','subtotal','iva','total','recibido','cambio','created_at'];protected function casts():array{return ['detalle'=>'array','created_at'=>'datetime'];} }
