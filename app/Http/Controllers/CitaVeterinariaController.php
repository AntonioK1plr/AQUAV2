<?php
namespace App\Http\Controllers;

use App\Models\CitaVeterinaria;
use App\Rules\OperatingHours;
use App\Services\AuditoriaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class CitaVeterinariaController extends Controller
{
    public function create()
    {
        return Inertia::render('Citas/Create', ['costo' => (float) config('aquarium.consultation_fee', 500)]);
    }

    public function store(Request $request, AuditoriaService $audit)
    {
        $data = $request->validate([
            'nombre_asistente' => 'required|string|max:255',
            'fecha_hora' => ['required', 'date', 'after:now', new OperatingHours],
        ]);

        $appointment = DB::transaction(function () use ($data, $request) {
            $at = \Carbon\Carbon::parse($data['fecha_hora']);
            if (DB::getDriverName() === 'pgsql') {
                DB::select('select pg_advisory_xact_lock(hashtext(?))', [$at->toDateTimeString()]);
            }

            $occupied = CitaVeterinaria::where('fecha_hora', $at)
                ->where('estatus', 'Pendiente')
                ->where(fn ($query) => $query->whereNull('reservado_hasta')->orWhere('reservado_hasta', '>', now()))
                ->exists();

            if ($occupied) {
                throw ValidationException::withMessages(['fecha_hora' => 'Ese horario ya está ocupado.']);
            }

            return CitaVeterinaria::create($data + [
                'user_id' => $request->user()->id,
                'estatus' => 'Pendiente',
                'costo' => config('aquarium.consultation_fee', 500),
                'pago_confirmado' => false,
                'reservado_hasta' => now()->addMinutes(30),
            ]);
        });

        $audit->registrar($request, 'cita.creada:' . $appointment->id);

        return back()->with('success', 'Cita reservada 30 minutos; confirma el pago en caja.');
    }

    public function agenda(Request $request)
    {
        $date=\Carbon\Carbon::parse($request->input('fecha',today()->toDateString()));
        $citas=CitaVeterinaria::with(['user.citas.expediente','expediente'])
            ->whereDate('fecha_hora',$date)->orderBy('fecha_hora')->get();
        return Inertia::render('Veterinaria/Agenda',['citas'=>$citas,'fecha'=>$date->toDateString()]);
    }

    public function update(Request $request,CitaVeterinaria $cita,AuditoriaService $audit)
    {
        $v=$request->validate([
            'estatus'=>['required',Rule::in(['Pendiente','Atendida','Cancelada'])],
            'motivo_cancelacion'=>'required_if:estatus,Cancelada|nullable|string|max:1000',
            'reembolso_autorizado'=>'sometimes|boolean',
        ]);
        if(($v['estatus']??'')==='Cancelada'&&empty($v['motivo_cancelacion']))
            throw ValidationException::withMessages(['motivo_cancelacion'=>'Se requiere justificar la cancelación.']);
        if(($v['estatus']??'')==='Atendida'&&!$cita->pago_confirmado)
            throw ValidationException::withMessages(['estatus'=>'La consulta debe estar pagada para marcarla como atendida.']);
        if($cita->pago_confirmado&&($v['estatus']??'')==='Cancelada'&&!($v['reembolso_autorizado']??false))
            throw ValidationException::withMessages(['reembolso_autorizado'=>'El veterinario debe autorizar el reembolso total.']);
        $cita->update($v+['reservado_hasta'=>null]);
        $audit->registrar($request,'cita.actualizada:'.$cita->id);
        return back();
    }
}
