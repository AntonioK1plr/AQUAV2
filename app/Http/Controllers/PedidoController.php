<?php
namespace App\Http\Controllers;

use App\Models\{Pedido, Producto};
use App\Rules\OperatingHours;
use App\Services\{AuditoriaService, PaseQrService, ValidacionBiologicaService};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PedidoController extends Controller
{
    public function store(Request $request, AuditoriaService $audit, ValidacionBiologicaService $biology)
    {
        $data = $request->validate([
            'tipo_entrega'=>'required|in:domicilio,click_collect', 'fecha_recoleccion'=>'required_if:tipo_entrega,click_collect|nullable|date|after_or_equal:today',
            'hora_recoleccion'=>'required_if:tipo_entrega,click_collect|nullable|date_format:H:i', 'items'=>'required|array|min:1',
            'direccion_entrega'=>'required_if:tipo_entrega,domicilio|nullable|string|max:2000',
            'items.*.producto_id'=>'required|integer|distinct|exists:productos,id', 'items.*.cantidad'=>'required|integer|min:1|max:100',
            'carta_responsiva'=>'sometimes|accepted',
        ]);
        if ($data['tipo_entrega']==='click_collect') {
            $datetime=$data['fecha_recoleccion'].' '.$data['hora_recoleccion'];
            app(OperatingHours::class)->validate('hora_recoleccion',$datetime,function($message){throw ValidationException::withMessages(['hora_recoleccion'=>$message]);});
        }
        $products=Producto::whereIn('id',collect($data['items'])->pluck('producto_id'))->get()->keyBy('id');
        $live=$products->contains(fn($product)=>$product->es_ser_vivo);
        if($live && !($data['carta_responsiva']??false)) throw ValidationException::withMessages(['carta_responsiva'=>'Debes aceptar la carta de cuidado responsable para comprar fauna viva.']);
        $living=$products->filter(fn($product)=>$product->es_ser_vivo)->values();
        foreach($living as $index=>$fish) {
            $incompatible=$biology->incompatibles($fish,$living->slice($index+1));
            if($incompatible) throw ValidationException::withMessages(['items'=>'Compatibilidad biológica: '.$fish->nombre.' y '.$incompatible[0]['producto'].' — '.$incompatible[0]['causa'].'.']);
        }
        $order=DB::transaction(function() use($request,$data,$audit,$products,$live) {
            if($data['tipo_entrega']==='click_collect') {
                DB::select('select pg_advisory_xact_lock(hashtext(?))',[$data['fecha_recoleccion'].' '.$data['hora_recoleccion']]);
                $capacity=(int)config('aquarium.pickup_capacity_per_slot',12);
                $occupied=Pedido::where('tipo_entrega','click_collect')->whereDate('fecha_recoleccion',$data['fecha_recoleccion'])->where('hora_recoleccion',$data['hora_recoleccion'])->whereNotIn('estatus',['Expirado','Entregado'])->where(fn($q)=>$q->whereNull('reservado_hasta')->orWhere('reservado_hasta','>',now()))->count();
                if($occupied >= $capacity) throw ValidationException::withMessages(['hora_recoleccion'=>'Ese horario ya alcanzó su aforo máximo.']);
            }
            $lines=[]; $subtotal=0;
            usort($data['items'],fn($a,$b)=>$a['producto_id']<=>$b['producto_id']);
            foreach($data['items'] as $item) {
                $product=Producto::whereKey($item['producto_id'])->lockForUpdate()->firstOrFail();
                if($product->stock < $item['cantidad']) throw ValidationException::withMessages(['items'=>'Inventario insuficiente para '.$product->nombre]);
                $product->decrement('stock',$item['cantidad']);
                $line=round((float)$product->precio*$item['cantidad'],2); $subtotal+=$line; $lines[]=[$product,$item['cantidad'],$line];
            }
            $subtotal=round($subtotal,2); $tax=round($subtotal*.16,2);
            $order=Pedido::create(['user_id'=>$request->user()->id,'tipo_entrega'=>$data['tipo_entrega'],'direccion_entrega'=>$data['direccion_entrega']??null,'estatus'=>'Pendiente','fecha_recoleccion'=>$data['fecha_recoleccion']??null,'hora_recoleccion'=>$data['hora_recoleccion']??null,'codigo_qr'=>Str::random(48),'subtotal'=>$subtotal,'iva'=>$tax,'total'=>$subtotal+$tax,'reservado_hasta'=>now()->addMinutes(30),'expirado_at'=>now()->addHours(48),'carta_responsiva'=>$live && ($data['carta_responsiva']??false)]);
            foreach($lines as [$product,$quantity,$line]) $order->items()->create(['producto_id'=>$product->id,'cantidad'=>$quantity,'precio_unitario'=>$product->precio,'subtotal'=>$line]);
            return $order;
        });
        $audit->registrar($request,'pedido.creado:'.$order->id);
        return back()->with('success','Inventario reservado por 30 minutos. Confirma el pedido con el personal de caja.')->with('pedido_id',$order->id);
    }

    public function historial(Request $request) { return \Inertia\Inertia::render('Historial/Index',['pedidos'=>$request->user()->pedidos()->with('items.producto')->latest()->get(),'citas'=>$request->user()->citas()->with('expediente')->latest('fecha_hora')->get()]); }
    public function pase(Request $request,Pedido $pedido,PaseQrService $qr) { abort_unless($request->user()->id===$pedido->user_id||in_array(mb_strtolower($request->user()->role),['cajero','administrador'],true),403);abort_if(!$pedido->codigo_qr||$pedido->estatus==='Expirado',404);return response($qr->svg($pedido->codigo_qr),200,['Content-Type'=>'image/svg+xml','Cache-Control'=>'private, no-store']); }
}
