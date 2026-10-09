<?php
namespace App\Console\Commands;

use App\Models\{CitaVeterinaria,Pedido,Producto};
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ExpireUncollectedOrders extends Command
{
    protected $signature='aquarium:expire-orders';
    protected $description='Release timed out inventory and appointment reservations';

    public function handle(): int
    {
        Pedido::whereNotIn('estatus',['Entregado','Expirado'])
            ->where(fn($q)=>$q->where(fn($x)=>$x->whereNotNull('reservado_hasta')->where('reservado_hasta','<=',now()))
                ->orWhere(fn($x)=>$x->whereNull('reservado_hasta')->where('tipo_entrega','click_collect')->whereNotNull('expirado_at')->where('expirado_at','<=',now())))
            ->chunkById(100,function($orders){
                foreach($orders as $candidate) DB::transaction(function()use($candidate){
                    $order=Pedido::with('items')->lockForUpdate()->find($candidate->id);
                    if(!$order||in_array($order->estatus,['Entregado','Expirado']))return;
                    $expiredReservation=$order->reservado_hasta&&$order->reservado_hasta->isPast();
                    $expiredPickup=$order->tipo_entrega==='click_collect'&&!$order->reservado_hasta&&$order->expirado_at&&$order->expirado_at->isPast();
                    if(!$expiredReservation&&!$expiredPickup)return;
                    foreach($order->items as $item)Producto::whereKey($item->producto_id)->increment('stock',$item->cantidad);
                    $order->update(['estatus'=>'Expirado','reservado_hasta'=>null]);
                });
            });

        CitaVeterinaria::where('estatus','Pendiente')->where('pago_confirmado',false)
            ->whereNotNull('reservado_hasta')->where('reservado_hasta','<=',now())->chunkById(100,function($appointments){
                foreach($appointments as $candidate) DB::transaction(function()use($candidate){
                    $appointment=CitaVeterinaria::lockForUpdate()->find($candidate->id);
                    if($appointment&&$appointment->estatus==='Pendiente'&&!$appointment->pago_confirmado&&$appointment->reservado_hasta?->isPast())
                        $appointment->update(['estatus'=>'Cancelada','motivo_cancelacion'=>'Reserva de pago vencida','reservado_hasta'=>null]);
                });
            });
        return self::SUCCESS;
    }
}
