<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ExpedienteClinico extends Model { protected $table='expedientes_clinicos'; protected $fillable=['cita_id','diagnostico','tratamiento','constantes_ph','constantes_temp','receta_pdf_path','hash_nom151']; public function cita(){return $this->belongsTo(CitaVeterinaria::class,'cita_id');} }
