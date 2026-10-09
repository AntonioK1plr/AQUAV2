<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class CorteZ extends Model {public $timestamps=false;protected $table='cortes_z';protected $fillable=['user_id','reporte','created_at'];protected function casts():array{return ['reporte'=>'array','created_at'=>'datetime'];}}
