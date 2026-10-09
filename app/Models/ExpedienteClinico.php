<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ExpedienteClinico extends Model {
 protected $table='expedientes_clinicos';
 protected $fillable=['cita_id','veterinario_id','edad_mascota','sexo_mascota','peso_mascota','sintomas','antecedentes','diagnostico','tratamiento','medicamento','dosis','frecuencia','duracion','observaciones','constantes_ph','constantes_temp','receta_pdf_path','hash_nom151'];
 protected function casts():array{return ['peso_mascota'=>'decimal:3','constantes_ph'=>'decimal:2','constantes_temp'=>'decimal:2'];}
 public function cita(){return $this->belongsTo(CitaVeterinaria::class,'cita_id');}
 public function veterinario(){return $this->belongsTo(User::class,'veterinario_id');}
}
