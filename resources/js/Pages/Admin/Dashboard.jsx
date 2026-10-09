import React from 'react';
import {Head,Link} from '@inertiajs/react';
import OperationsLayout from '@/Layouts/OperationsLayout';
const sections=[
 {title:'Administrar empleados',text:'Crear cuentas de Cajero, Almacenista y Veterinario. Solo el administrador puede dar de alta personal.',href:'/admin/empleados',action:'Gestionar personal',icon:'♙'},
 {title:'Productos e inventario',text:'Agregar productos, actualizar existencias y mantener la informacion del catalogo.',href:'/admin/productos',action:'Abrir productos',icon:'◈'},
 {title:'Caja POS',text:'Consultar la terminal de ventas y los pagos registrados en tienda.',href:'/pos',action:'Ir a caja',icon:'▣'},
 {title:'Agenda veterinaria',text:'Revisar citas, expedientes clinicos y recetas.',href:'/veterinaria/agenda',action:'Abrir agenda',icon:'♡'},
 {title:'Almacen y picking',text:'Dar seguimiento a pedidos pagados y su preparacion.',href:'/almacen/picking',action:'Abrir almacen',icon:'▤'},
];
export default function Dashboard(){return <OperationsLayout title="Panel de administracion" active="/admin"><Head title="Administracion | AQUARIUM"/><div className="mx-auto max-w-6xl"><div className="mb-6 rounded-2xl border border-cyan-400/20 bg-gradient-to-r from-[#10283d] to-[#0b1c2d] p-6"><p className="text-xs font-bold uppercase tracking-widest text-cyan-300">Control de sucursal</p><h2 className="mt-2 text-2xl font-black">Panel de administracion</h2><p className="mt-2 max-w-2xl text-sm text-slate-300">Administra el personal y los modulos de AQUARIUM desde un espacio exclusivo para administradores.</p></div><div className="grid gap-4 md:grid-cols-2 xl:grid-cols-3">{sections.map(section=><article key={section.href} className="rounded-2xl border border-white/10 bg-[#0c2033] p-5"><span className="grid h-10 w-10 place-items-center rounded-xl bg-cyan-300/10 text-xl text-cyan-300">{section.icon}</span><h3 className="mt-4 font-bold">{section.title}</h3><p className="mt-2 min-h-12 text-sm leading-6 text-slate-400">{section.text}</p><Link href={section.href} className="mt-4 inline-flex rounded-lg bg-cyan-400 px-4 py-2 text-sm font-bold text-slate-950">{section.action}</Link></article>)}</div></div></OperationsLayout>}
