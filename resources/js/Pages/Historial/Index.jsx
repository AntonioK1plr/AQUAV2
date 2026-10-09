import React from 'react';
import {Head, Link} from '@inertiajs/react';
import SiteLayout from '@/Layouts/SiteLayout';

export default function Index({pedidos = [], citas = []}) {
 return <SiteLayout title="Mi cuenta" active="Inicio">
  <Head title="Mi cuenta | AQUARIUM"/>
  <main className="mx-auto max-w-7xl px-5 py-8">
   <p className="text-xs font-bold uppercase tracking-widest text-cyan-700">Mi portal</p>
   <h1 className="mt-1 text-3xl font-black">Mi cuenta de cliente responsable</h1>
   <p className="mt-2 text-sm text-slate-500">Consulta tus pedidos y horarios de cita.</p>
   <div className="mt-6 grid gap-6 lg:grid-cols-[300px_1fr]">
    <aside className="h-fit rounded-2xl bg-white p-5 shadow-sm">
     <div className="flex items-center gap-3"><span className="grid h-12 w-12 place-items-center rounded-full bg-cyan-100 font-bold text-cyan-900">AQ</span><div><b>Cliente AQUARIUM</b><p className="text-xs text-slate-500">Cuenta responsable</p></div></div>
     <Link href="/perfil" className="mt-5 block rounded-lg bg-[#07192d] px-4 py-3 text-center text-sm font-bold text-white">Editar perfil</Link>
     <Link href="/citas/nueva" className="mt-2 block rounded-lg border border-slate-200 px-4 py-3 text-center text-sm font-semibold">Agendar consulta</Link>
    </aside>
    <section className="space-y-6">
     <div className="rounded-2xl bg-white p-5 shadow-sm">
      <div className="mb-4 flex items-center justify-between"><h2 className="text-lg font-bold">Pedidos</h2><Link href="/catalogo" className="text-sm font-bold text-cyan-800">Ir al cat&aacute;logo</Link></div>
      {pedidos.length ? pedidos.map(p => <article key={p.id} className="mb-3 flex flex-wrap items-center justify-between gap-3 rounded-xl border border-slate-200 p-4"><div><b>Pedido #AQ-{String(p.id).padStart(6, '0')}</b><p className="mt-1 text-sm text-slate-500">{p.estatus} &middot; {p.tipo_entrega === 'click_collect' ? 'Click & Collect' : 'Env\u00edo a domicilio'} &middot; ${Number(p.total).toFixed(2)} MXN</p>{!p.metodo_pago && <p className="text-xs text-amber-700">Pago pendiente de confirmaci&oacute;n en caja</p>}</div><div className="flex items-center gap-3">{p.codigo_qr && p.estatus !== 'Expirado' && <img className="h-16 w-16" src={'/pedidos/' + p.id + '/pase'} alt="Pase de recolecci&oacute;n"/>}<span className="text-xs font-bold text-cyan-800">{p.fecha_recoleccion || 'Ver detalle'}</span></div></article>) : <p className="rounded-lg bg-slate-50 p-5 text-sm text-slate-500">Todav&iacute;a no tienes pedidos.</p>}
     </div>
     <div className="rounded-2xl bg-white p-5 shadow-sm">
      <h2 className="mb-4 text-lg font-bold">Mis citas</h2>
      {citas.length ? citas.map(c => <article key={c.id} className="mb-3 rounded-xl border border-slate-200 p-4">
       <div className="flex flex-wrap justify-between gap-2"><div><b>Cita veterinaria</b><p className="mt-1 text-sm text-slate-600">Asiste: {c.nombre_asistente}</p><p className="mt-1 text-sm text-slate-500">{new Date(c.fecha_hora).toLocaleString('es-MX')} &middot; {c.estatus}</p></div><span className="text-xs font-semibold">{c.pago_confirmado ? 'Pago confirmado' : 'Pago pendiente en caja'}</span></div>
       {c.documentos_count > 0 && <div className="mt-3 flex flex-wrap items-center gap-2"><span className="text-xs text-slate-500">Documentos que entregaste:</span>{Array.from({length: c.documentos_count}, (_, i) => <a key={i} className="rounded-lg bg-cyan-50 px-3 py-2 text-sm font-semibold text-cyan-800" href={'/citas/' + c.id + '/documentos/' + i} download>Descargar documento {i + 1}</a>)}</div>}
      </article>) : <p className="rounded-lg bg-slate-50 p-5 text-sm text-slate-500">Todav&iacute;a no tienes citas.</p>}
     </div>
    </section>
   </div>
  </main>
 </SiteLayout>;
}
