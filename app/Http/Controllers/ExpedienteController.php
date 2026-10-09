<?php
namespace App\Http\Controllers;

use App\Models\{CitaVeterinaria,ExpedienteClinico};
use App\Services\{AuditoriaService,RecetaPdfService};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ExpedienteController extends Controller
{
    public function store(Request $request,CitaVeterinaria $cita,RecetaPdfService $pdf,AuditoriaService $audit)
    {
        abort_unless($cita->pago_confirmado,403,'La cita debe estar pagada antes de capturar el expediente.');
        $v=$request->validate([
            'edad_mascota'=>'nullable|string|max:80','sexo_mascota'=>'nullable|string|max:24',
            'peso_mascota'=>'nullable|numeric|min:0|max:9999','sintomas'=>'nullable|string|max:10000',
            'antecedentes'=>'nullable|string|max:10000','diagnostico'=>'required|string|max:10000',
            'tratamiento'=>'required|string|max:10000','medicamento'=>'nullable|string|max:255',
            'dosis'=>'nullable|string|max:255','frecuencia'=>'nullable|string|max:255',
            'duracion'=>'nullable|string|max:255','observaciones'=>'nullable|string|max:10000',
            'constantes_ph'=>'nullable|numeric|between:0,14','constantes_temp'=>'nullable|numeric|between:-5,60',
        ]);
        $record=ExpedienteClinico::updateOrCreate(['cita_id'=>$cita->id],$v+['veterinario_id'=>$request->user()->id]);
        $pdf->generar($record);
        $cita->update(['estatus'=>'Atendida']);
        $audit->registrar($request,'expediente.emitido:'.$record->id);
        return back()->with('success','Expediente clínico y receta PDF generados.');
    }

    public function receta(Request $request,ExpedienteClinico $expediente,RecetaPdfService $pdf)
    {
        abort_unless(in_array(mb_strtolower($request->user()->role),['veterinario','administrador'],true)
            ||$expediente->cita->user_id===$request->user()->id,403);
        abort_if(!$expediente->receta_pdf_path||!Storage::disk('local')->exists($expediente->receta_pdf_path),404);
        abort_unless($pdf->validar($expediente),409,'El hash de integridad no coincide.');
        return Storage::disk('local')->download($expediente->receta_pdf_path,'receta-'.$expediente->id.'.pdf');
    }

    public function documentoCita(Request $request,CitaVeterinaria $cita,int $documento)
    {
        $staff=in_array(mb_strtolower($request->user()->role),['veterinario','administrador'],true);
        abort_unless($staff||$cita->user_id===$request->user()->id,403);
        $documents=$cita->documentos??[];
        abort_unless(isset($documents[$documento]),404);
        $file=$documents[$documento];
        abort_unless(Storage::disk('local')->exists($file['path']),404);
        return Storage::disk('local')->download($file['path'],$file['name'],[
            'Content-Type'=>$file['mime']??'application/octet-stream',
        ]);
    }
}
