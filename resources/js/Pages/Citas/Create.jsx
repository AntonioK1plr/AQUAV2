import React,{useState} from 'react';
import {Head,useForm,usePage} from '@inertiajs/react';
import SiteLayout from '@/Layouts/SiteLayout';

export default function Create({costo}){
 const[fecha,setFecha]=useState(''),[hora,setHora]=useState('');
 const{data,setData,post,processing,errors}=useForm({nombre_asistente:'',fecha_hora:''});
 const{auth,flash}=usePage().props;
 const weekend=fecha&&[0,6].includes(new Date(fecha+'T12:00:00').getDay());
 const start=weekend?10:9,end=weekend?17:23;
 const today=new Date().toLocaleDateString('en-CA');
 const current=new Date().toLocaleTimeString('en-GB',{hour:'2-digit',minute:'2-digit'});
 const slots=Array.from({length:(end-start)*2},(_,i)=>String(start+Math.floor(i/2)).padStart(2,'0')+':'+(i%2?'30':'00')).filter(t=>fecha!==today||t>current);
 function chooseTime(value){setHora(value);setData('fecha_hora',value?fecha+'T'+value:'')}
 return <SiteLayout title="Agendar cita veterinaria" active="Clínica"><Head title="Agendar cita | AQUARIUM"/>
  <main className="mx-auto max-w-7xl px-5 py-8">
   <p className="text-xs font-bold uppercase tracking-widest text-cyan-700">Servicios clínicos especializados</p>
   <h1 className="mt-1 text-3xl font-black">Agendar cita médica veterinaria</h1>
   <p className="mt-2 text-sm text-slate-500">Para reservar solo necesitamos el día, la hora y el nombre de quien asistirá. El veterinario llenará la ficha del paciente durante la consulta.</p>
   {flash?.success&&<p className="mt-4 rounded-xl bg-emerald-50 p-4 text-emerald-800">{flash.success}</p>}
   <form onSubmit={e=>{e.preventDefault();post('/citas')}} className="mt-6 grid gap-6 lg:grid-cols-[1fr_330px]">
    <section className="space-y-5 rounded-2xl bg-white p-6 shadow-sm">
     <h2 className="font-bold">Datos para reservar</h2>
     <label className="block text-sm font-semibold">Nombre de la persona que asistirá
      <input required maxLength="255" autoComplete="name" className="field mt-1" value={data.nombre_asistente} onChange={e=>setData('nombre_asistente',e.target.value)} placeholder="Nombre completo"/>
      {errors.nombre_asistente&&<small className="text-rose-600">{errors.nombre_asistente}</small>}
     </label>
     <div className="rounded-xl bg-slate-50 p-4">
      <h2 className="font-bold">Día y hora</h2>
      <p className="mt-1 text-xs text-slate-500">Lunes a viernes 09:00–23:00 · Sábado y domingo 10:00–17:00</p>
      <div className="mt-3 grid gap-3 sm:grid-cols-2">
       <label className="text-sm font-semibold">Día
        <input required type="date" min={today} value={fecha} onChange={e=>{setFecha(e.target.value);setHora('');setData('fecha_hora','')}} className="field mt-1"/>
       </label>
       <label className="text-sm font-semibold">Hora
        <select required disabled={!fecha} value={hora} onChange={e=>chooseTime(e.target.value)} className="field mt-1">
         <option value="">Selecciona una hora</option>{slots.map(t=><option key={t} value={t}>{t}</option>)}
        </select>
        {errors.fecha_hora&&<small className="text-rose-600">{errors.fecha_hora}</small>}
       </label>
      </div>
     </div>
     {!auth?.user&&<p className="rounded-lg bg-cyan-50 p-3 text-xs text-slate-600">Podrás completar la reserva después de iniciar sesión o crear tu cuenta.</p>}
    </section>
    <aside className="h-fit rounded-2xl bg-white p-5 shadow-sm">
     <h2 className="font-bold">Resumen de costo</h2>
     <p className="mt-4 flex justify-between text-sm"><span>Consulta veterinaria</span><span>{'$'}{Number(costo).toFixed(2)} MXN</span></p>
     <p className="mt-2 flex justify-between text-sm"><span>IVA trasladado (16%)</span><span>Incluido</span></p>
     <p className="mt-4 flex justify-between border-t pt-4 text-lg font-black"><span>Total</span><span className="text-cyan-700">{'$'}{Number(costo).toFixed(2)} MXN</span></p>
     <div className="mt-4 rounded-lg bg-cyan-50 p-3 text-xs text-slate-600">La reservación dura 30 minutos. El pago se confirma en caja.</div>
     <button disabled={processing||!data.fecha_hora||!data.nombre_asistente.trim()} className="mt-4 w-full rounded-lg bg-[#07192d] px-5 py-3 font-bold text-white disabled:opacity-40">Confirmar y agendar</button>
    </aside>
   </form>
  </main>
 </SiteLayout>
}
