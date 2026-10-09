<?php
namespace App\Http\Controllers;
use App\Models\User; use App\Services\AuditoriaService; use Illuminate\Http\Request;
class EmpleadoController extends Controller { public function index(){return \Inertia\Inertia::render('Admin/Empleados',['empleados'=>User::whereIn('role',['Cajero','Veterinario','Administrador'])->orderBy('name')->get(['id','name','email','role','telefono'])]);} public function store(Request $r,AuditoriaService $audit){$v=$r->validate(['name'=>'required|string|max:255','email'=>'required|email|unique:users,email','password'=>'required|string|min:12','role'=>'required|in:Cajero,Veterinario,Administrador','telefono'=>'nullable|string|max:32']);$u=User::create($v);$audit->registrar($r,'empleado.creado:'.$u->id);return back()->with('success','Empleado creado.');} }
