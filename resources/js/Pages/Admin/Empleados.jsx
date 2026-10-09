import React from 'react';
import { Head, useForm, Link } from '@inertiajs/react';

const roles = ['Administrador', 'Cajero', 'Almacenista', 'Veterinario'];

function EmployeeForm({ employee, onSubmit, submitLabel }) {
    const { data, setData, patch, processing, errors } = useForm({
        name: employee.name,
        email: employee.email,
        telefono: employee.telefono || '',
        role: employee.role,
        is_active: Boolean(employee.is_active),
    });

    return <form onSubmit={e => { e.preventDefault(); onSubmit(patch, data); }} className="grid gap-3 rounded-xl bg-slate-50 p-4 sm:grid-cols-2">
        <label className="text-sm font-semibold">Nombre<input className="field mt-1" value={data.name} onChange={e => setData('name', e.target.value)} />{errors.name && <small className="text-red-600">{errors.name}</small>}</label>
        <label className="text-sm font-semibold">Correo<input className="field mt-1" type="email" value={data.email} onChange={e => setData('email', e.target.value)} />{errors.email && <small className="text-red-600">{errors.email}</small>}</label>
        <label className="text-sm font-semibold">Teléfono<input className="field mt-1" value={data.telefono} onChange={e => setData('telefono', e.target.value)} />{errors.telefono && <small className="text-red-600">{errors.telefono}</small>}</label>
        <label className="text-sm font-semibold">Rol<select className="field mt-1" value={data.role} onChange={e => setData('role', e.target.value)}>{roles.map(role => <option key={role}>{role}</option>)}</select>{errors.role && <small className="text-red-600">{errors.role}</small>}</label>
        <label className="flex items-center gap-2 text-sm font-semibold sm:col-span-2"><input type="checkbox" checked={data.is_active} onChange={e => setData('is_active', e.target.checked)} /> Cuenta activa (desmarca para bloquear el acceso)</label>
        <button disabled={processing} className="rounded bg-[#00E5FF] px-4 py-2 font-bold">{submitLabel}</button>
    </form>;
}

export default function Empleados({ empleados, flash = {} }) {
    const { data, setData, post, processing, errors } = useForm({ name: '', email: '', telefono: '', role: 'Cajero', password: '' });
    return <><Head title="Empleados" /><main className="min-h-screen bg-slate-50 p-6"><div className="mx-auto max-w-5xl">
        <Link href="/admin" className="text-cyan-700">← Administración</Link><h1 className="my-5 text-3xl font-bold">Gestión de empleados</h1>
        {flash.success && <p role="status" className="mb-4 rounded-lg bg-emerald-100 p-3 text-emerald-900">{flash.success}</p>}
        <form onSubmit={e => { e.preventDefault(); post('/admin/empleados'); }} className="grid gap-3 rounded-xl bg-white p-5 shadow sm:grid-cols-2">
            <h2 className="text-xl font-bold sm:col-span-2">Agregar empleado</h2>
            <input className="field" placeholder="Nombre" value={data.name} onChange={e => setData('name', e.target.value)} />
            <input className="field" placeholder="Correo" type="email" value={data.email} onChange={e => setData('email', e.target.value)} />
            <input className="field" placeholder="Teléfono" value={data.telefono} onChange={e => setData('telefono', e.target.value)} />
            <select className="field" value={data.role} onChange={e => setData('role', e.target.value)}><option>Cajero</option><option>Almacenista</option><option>Veterinario</option></select>
            <input className="field" placeholder="Contraseña (12+ caracteres)" type="password" value={data.password} onChange={e => setData('password', e.target.value)} />
            <button disabled={processing} className="rounded bg-[#00E5FF] px-4 py-2 font-bold">Crear empleado</button>
            {Object.values(errors).map((error, i) => <p key={i} className="text-red-600 sm:col-span-2">{error}</p>)}
        </form>
        <section className="mt-7 space-y-4"><h2 className="text-xl font-bold">Cuentas operativas</h2>{empleados.map(employee => <article key={employee.id} className="rounded-xl bg-white p-5 shadow">
            <div className="mb-3 flex flex-wrap items-center justify-between gap-2"><div><h3 className="font-bold">{employee.name}</h3><p className="text-sm text-slate-600">{employee.email} | {employee.telefono || 'Sin teléfono'} | {employee.role}</p></div><span className={'rounded-full px-3 py-1 text-xs font-bold ' + (employee.is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800')}>{employee.is_active ? 'Activo' : 'Bloqueado'}</span></div>
            <EmployeeForm employee={employee} submitLabel="Guardar cambios" onSubmit={(patch) => patch('/admin/empleados/' + employee.id)} />
        </article>)}</section>
    </div></main></>;
}
