import React from 'react';
import {Head,Link,router,usePage} from '@inertiajs/react';
const menu=[
 {label:'Resumen',href:'/admin',icon:'▦',roles:['Administrador']},
 {label:'Caja POS',href:'/pos',icon:'▣',roles:['Cajero','Administrador']},
 {label:'Pagos veterinarios',href:'/pos/citas',icon:'✚',roles:['Cajero','Administrador']},
 {label:'Picking y almacen',href:'/almacen/picking',icon:'▤',roles:['Almacenista','Administrador']},
 {label:'Agenda clinica',href:'/veterinaria/agenda',icon:'♡',roles:['Veterinario','Administrador']},
 {label:'Productos',href:'/admin/productos',icon:'◈',roles:['Administrador']},
 {label:'Personal',href:'/admin/empleados',icon:'♙',roles:['Administrador']},
];
export default function OperationsLayout({children,title='Operaciones',active=''}) {
 const {auth}=usePage().props,user=auth?.user,visible=menu.filter(item=>item.roles.includes(user?.role));
 return <div className="min-h-screen bg-[#071321] text-slate-100"><div className="flex min-h-screen">
  <aside className="hidden w-64 shrink-0 flex-col border-r border-white/10 bg-[#091a2c] p-4 lg:flex">
   <Link href="/" className="mb-8 flex items-center gap-3 px-2 pt-2"><span className="grid h-9 w-9 place-items-center rounded-full bg-[#00d2dc] font-black text-[#062039]">AQ</span><span><b className="block">AQUARIUM</b><small className="text-[10px] uppercase tracking-widest text-[#00d2dc]">Portal de {user?.role||'operaciones'}</small></span></Link>
   <p className="mb-2 px-3 text-[10px] font-bold uppercase tracking-[.2em] text-slate-500">Tu espacio de trabajo</p>
   <nav className="space-y-1">{visible.map(item=><Link key={item.href} href={item.href} className={active===item.href?'flex items-center gap-3 rounded-lg bg-[#123148] px-3 py-3 text-sm font-semibold text-[#00d2dc]':'flex items-center gap-3 rounded-lg px-3 py-3 text-sm text-slate-300 hover:bg-white/5 hover:text-white'}><span className="w-5 text-center">{item.icon}</span>{item.label}</Link>)}</nav>
   <div className="mt-auto rounded-xl bg-[#10283d] p-3"><p className="text-sm font-semibold">{user?.name||'Personal'}</p><p className="mt-1 text-xs text-[#00d2dc]">{user?.role}</p><button onClick={()=>router.post('/logout')} className="mt-3 text-xs text-slate-400 hover:text-white">Cerrar sesion</button></div>
  </aside>
  <div className="min-w-0 flex-1"><header className="flex flex-wrap items-center justify-between gap-3 border-b border-white/10 bg-[#091a2c] px-5 py-4"><div><p className="text-[10px] uppercase tracking-widest text-slate-500">AQUARIUM / {user?.role}</p><h1 className="font-bold">{title}</h1></div><div className="text-xs text-slate-400">{user?.name}</div></header>
   <nav className="flex gap-2 overflow-x-auto border-b border-white/10 bg-[#091a2c] px-4 py-2 lg:hidden">{visible.map(item=><Link key={item.href} href={item.href} className={active===item.href?'whitespace-nowrap rounded bg-[#123148] px-3 py-2 text-xs text-[#00d2dc]':'whitespace-nowrap rounded px-3 py-2 text-xs text-slate-300'}>{item.label}</Link>)}</nav>
   <main className="p-4 sm:p-6">{children}</main>
  </div></div></div>;
}
