<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Pedido extends Model { protected $table='pedidos'; protected $fillable=['user_id','tipo_entrega','estatus','fecha_recoleccion','hora_recoleccion','codigo_qr','total','subtotal','iva','expirado_at','reservado_hasta','metodo_pago','carta_responsiva','direccion_entrega']; protected function casts():array{return ['fecha_recoleccion'=>'date','expirado_at'=>'datetime','reservado_hasta'=>'datetime','carta_responsiva'=>'boolean','total'=>'decimal:2','subtotal'=>'decimal:2','iva'=>'decimal:2'];} public function user(){return $this->belongsTo(User::class);} public function items(){return $this->hasMany(PedidoItem::class);} }
