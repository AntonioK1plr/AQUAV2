<!DOCTYPE html>
<html lang="es"><meta charset="utf-8">
<style>
body{font-family:DejaVu Sans,sans-serif;color:#0f172a;font-size:12px;line-height:1.5}
h1{color:#0B192C;border-bottom:3px solid #00E5FF;padding-bottom:8px}
h2{color:#0f766e;font-size:15px;margin:14px 0 4px}
.muted{color:#64748b}.box{border:1px solid #cbd5e1;padding:14px;margin:14px 0}
table{width:100%;border-collapse:collapse}td{width:50%;vertical-align:top;padding:5px 8px}
</style><body>
<h1>AQUARIUM · Receta veterinaria</h1>
<p class="muted">Documento clínico interno generado {{ $issuedAt->format('d/m/Y H:i') }}</p>
<div class="box"><h2>Paciente y tutor</h2><table>
<tr><td><b>Paciente:</b> {{ $record->cita->nombre_mascota }}</td><td><b>Especie:</b> {{ $record->cita->especie }}</td></tr>
<tr><td><b>Edad:</b> {{ $record->edad_mascota ?: ($record->cita->edad_mascota ?: 'No indicada') }}</td><td><b>Sexo:</b> {{ $record->sexo_mascota ?: ($record->cita->sexo_mascota ?: 'No indicado') }}</td></tr>
<tr><td><b>Peso:</b> {{ $record->peso_mascota ?: ($record->cita->peso_mascota ?: 'No indicado') }} g</td><td><b>Consulta:</b> {{ $record->cita->fecha_hora->format('d/m/Y H:i') }}</td></tr>
<tr><td><b>Tutor:</b> {{ $record->cita->user->name }}</td><td><b>Contacto:</b> {{ $record->cita->user->email }} · {{ $record->cita->user->telefono ?: 'Sin teléfono' }}</td></tr>
<tr><td colspan="2"><b>Veterinario responsable:</b> {{ $record->veterinario?->name ?: 'No indicado' }}</td></tr>
</table></div>
<h2>Motivo y signos clínicos</h2><p>{{ $record->cita->motivo }}</p>
<p><b>Síntomas:</b> {{ $record->sintomas ?: ($record->cita->sintomas ?: 'No indicados') }}</p>
<p><b>Antecedentes:</b> {{ $record->antecedentes ?: ($record->cita->antecedentes ?: 'Sin antecedentes reportados') }}</p>
<p><b>Parámetros del acuario:</b> pH {{ $record->cita->ph_acuario ?: 'N/D' }} · Temperatura {{ $record->cita->temp_acuario ?: 'N/D' }} °C</p>
<h2>Diagnóstico</h2><p>{{ $record->diagnostico }}</p>
<h2>Tratamiento e indicaciones</h2><p>{{ $record->tratamiento }}</p>
@if($record->medicamento || $record->dosis || $record->frecuencia || $record->duracion)
<div class="box"><h2>Prescripción</h2><p><b>Medicamento:</b> {{ $record->medicamento ?: 'No indicado' }}</p>
<p><b>Dosis:</b> {{ $record->dosis ?: 'No indicada' }} · <b>Frecuencia:</b> {{ $record->frecuencia ?: 'No indicada' }} · <b>Duración:</b> {{ $record->duracion ?: 'No indicada' }}</p></div>
@endif
<p><b>Parámetros revisados:</b> pH {{ $record->constantes_ph ?? 'N/D' }} · Temperatura {{ $record->constantes_temp ?? 'N/D' }} °C</p>
@if($record->observaciones)<h2>Observaciones</h2><p>{{ $record->observaciones }}</p>@endif
<p class="muted">Hash SHA-256 de integridad: se calcula tras generar el PDF y se conserva en el expediente.</p>
</body></html>
