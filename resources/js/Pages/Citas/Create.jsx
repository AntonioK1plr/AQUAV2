import React,{useState} from 'react';
import {Head,useForm,usePage} from '@inertiajs/react';
import SiteLayout from '@/Layouts/SiteLayout';

export default function Create({costo}){
 const[fecha,setFecha]=useState(''),[hora,setHora]=useState('');
 const{data,setData,post,processing,errors}=useForm({
  nombre_mascota:'',especie:'',edad_mascota:'',sexo_mascota:'',peso_mascota:'',
  motivo:'',sintomas:'',antecedentes:'',ph_acuario:'',temp_acuario:'',
  fecha_hora:'',documentos:[],
 });
 const{flash}=usePage().props;
 const weekend=fecha&&[0,6].includes(new Date(fecha+'T12:00:00').getDay());
 const start=weekend?10:9,end=weekend?17:23;
 const today=new Date().toLocaleDateString('en-CA');
 const current=new Date().toLocaleTimeString('en-GB',{hour:'2-digit',minute:'2-digit'});
 const slots=Array.from({length:(end-start)*2},(_,i)=>String(start+Math.floor(i/2)).padStart(2,'0')+':'+(i%2?'30':'00')).filter(t=>fecha!==today||t>current);
 function chooseTime(value){setHora(value);setData('fecha_hora',value?fecha+'T'+value:'')}
 function field(name,label,type='text',placeholder=''){
  return <label key={name} className="block text-sm font-semibold">{label}<input type={type} step={type==='number'?'any':undefined} className="field mt-1" value={data[name]} onChange={e=>setData(name,e.target.value)} placeholder={placeholder}/>{errors[name]&&<small className="text-rose-600">{errors[name]}</small>}</label>
 }
 return <SiteLayout title="Agendar cita veterinaria" active="Clínica"><Head title="Agendar cita | AQUARIUM"/>
  <main className="mx-auto max-w-7xl px-5 py-8"><p className="text-xs font-bold uppercase tracking-widest text-cyan-700">Servicios clínicos especializados</p>
   <h1 className="mt-1 text-3xl font-black">Agendar cita médica veterinaria</h1>
   <p className="mt-2 text-sm text-slate-500">Comparte los datos de tu paciente y adjunta recetas o estudios previos para el veterinario.</p>
   {flash?.success&&<p className="mt-4 rounded-xl bg-emerald-50 p-4 text-emerald-800">{flash.success}</p>}
   <form onSubmit={e=>{e.preventDefault();post('/citas',{forceFormData:true})}} className="mt-6 grid gap-6 lg:grid-cols-[1fr_330px]">
    <section className="space-y-5 rounded-2xl bg-white p-6 shadow-sm">
     <div><h2 className="font-bold">Datos del paciente</h2><p className="mt-1 text-xs text-slate-500">El tutor y sus datos de contacto se asocian a la cuenta que inició sesión.</p></div>
     <div className="grid gap-4 sm:grid-cols-2">
      {field('nombre_mascota','Nombre del paciente')}
      {field('especie','Especie','text','Ej. Pez payaso')}
      {field('edad_mascota','Edad aproximada','text','Ej. 8 meses')}
      <label className="block text-sm font-semibold">Sexo<select className="field mt-1" value={data.sexo_mascota} onChange={e=>setData('sexo_mascota',e.target.value)}><option value="">No indicado</option><option>Macho</option><option>Hembra</option><option>No identificado</option></select>{errors.sexo_mascota&&<small className="text-rose-600">{errors.sexo_mascota}</small>}</label>
      {field('peso_mascota','Peso (g)','number','Ej. 25')}
      {field('ph_acuario','pH actual del acuario','number','0–14')}
      {field('temp_acuario','Temperatura actual (°C)','number','°C')}
      <label className="block text-sm font-semibold sm:col-span-2">Motivo de consulta<textarea required className="field mt-1 min-h-20" value={data.motivo} onChange={e=>setData('motivo',e.target.value)} placeholder="Describe qué atención necesita el paciente."/>{errors.motivo&&<small className="text-rose-600">{errors.motivo}</small>}</label>
      <label className="block text-sm font-semibold sm:col-span-2">Síntomas actuales<textarea className="field mt-1 min-h-20" value={data.sintomas} onChange={e=>setData('sintomas',e.target.value)} placeholder="Cuándo comenzaron y qué cambios observaste."/>{errors.sintomas&&<small className="text-rose-600">{errors.sintomas}</small>}</label>
      <label className="block text-sm font-semibold sm:col-span-2">Antecedentes clínicos<textarea className="field mt-1 min-h-20" value={data.antecedentes} onChange={e=>setData('antecedentes',e.target.value)} placeholder="Enfermedades, medicamentos, tratamientos y cambios recientes."/>{errors.antecedentes&&<small className="text-rose-600">{errors.antecedentes}</small>}</label>
      <label className="block text-sm font-semibold sm:col-span-2">Recetas o estudios previos (PDF o Word)<input type="file" multiple accept=".pdf,.doc,.docx,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document" className="field mt-1" onChange={e=>setData('documentos',Array.from(e.target.files||[]))}/><small className="mt-1 block font-normal text-slate-500">Hasta 3 archivos, máximo 1 MB cada uno. Solo el tutor y personal clínico autorizado podrán descargarlos.</small>{errors.documentos&&<small className="block text-rose-600">{errors.documentos}</small>}{errors['documentos.0']&&<small className="block text-rose-600">{errors['documentos.0']}</small>}{data.documentos.length>0&&<small className="mt-1 block font-normal text-slate-600">{data.documentos.map(f=>f.name).join(' · ')}</small>}</label>
     </div>
     <div className="rounded-xl bg-slate-50 p-4"><h2 className="font-bold">Disponibilidad y agenda</h2><p className="mt-1 text-xs text-slate-500">Lunes a viernes 09:00–23:00 · Sábado y domingo 10:00–17:00</p><div className="mt-3 grid gap-3 sm:grid-cols-2"><label className="text-sm font-semibold">Fecha<input required type="date" min={today} value={fecha} onChange={e=>{setFecha(e.target.value);setHora('');setData('fecha_hora','')}} className="field mt-1"/></label><label className="text-sm font-semibold">Horario<select required disabled={!fecha} value={hora} onChange={e=>chooseTime(e.target.value)} className="field mt-1"><option value="">Selecciona un horario</option>{slots.map(t=><option key={t}>{t}</option>)}</select>{errors.fecha_hora&&<small className="text-rose-600">{errors.fecha_hora}</small>}</label></div></div>
    </section>
    <aside className="h-fit rounded-2xl bg-white p-5 shadow-sm"><h2 className="font-bold">Resumen de costo</h2><p className="mt-4 flex justify-between text-sm"><span>Consulta veterinaria</span><span>{'$'}{Number(costo).toFixed(2)} MXN</span></p><p className="mt-2 flex justify-between text-sm"><span>IVA trasladado (16%)</span><span>Incluido</span></p><p className="mt-4 flex justify-between border-t pt-4 text-lg font-black"><span>Total</span><span className="text-cyan-700">{'$'}{Number(costo).toFixed(2)} MXN</span></p><div className="mt-4 rounded-lg bg-cyan-50 p-3 text-xs text-slate-600">La reservación dura 30 minutos. El pago se confirma en caja.</div><button disabled={processing||!data.fecha_hora} className="mt-4 w-full rounded-lg bg-[#07192d] px-5 py-3 font-bold text-white disabled:opacity-40">Confirmar y agendar</button></aside>
   </form>
  </main>
 </SiteLayout>
}
