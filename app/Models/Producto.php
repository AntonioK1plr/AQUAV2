<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Producto extends Model { protected $table='productos'; protected $fillable=['nombre','descripcion','precio','stock','tipo_agua','marca','ph_min','ph_max','temp_min','temp_max','temperamento','es_ser_vivo','codigo_barras','pasillo']; protected function casts():array{return ['precio'=>'decimal:2','ph_min'=>'decimal:2','ph_max'=>'decimal:2','temp_min'=>'decimal:2','temp_max'=>'decimal:2','es_ser_vivo'=>'boolean'];} public function pedidoItems(){return $this->hasMany(PedidoItem::class);} }
