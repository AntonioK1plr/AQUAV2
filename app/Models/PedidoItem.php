<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class PedidoItem extends Model { protected $table='pedido_items'; protected $fillable=['pedido_id','producto_id','cantidad','precio_unitario','subtotal']; protected function casts():array{return ['precio_unitario'=>'decimal:2','subtotal'=>'decimal:2'];} public function pedido(){return $this->belongsTo(Pedido::class);} public function producto(){return $this->belongsTo(Producto::class);} }
