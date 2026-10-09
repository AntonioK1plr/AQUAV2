<?php
namespace App\Http\Controllers;
use App\Services\AuditoriaService; use Illuminate\Http\Request; use Inertia\Inertia;
class PerfilController extends Controller {public function edit(Request $r){return Inertia::render('Perfil/Edit',['usuario'=>$r->user()->only('name','email','telefono','direccion')]);} public function update(Request $r,AuditoriaService $audit){$v=$r->validate(['name'=>'required|string|max:255','telefono'=>'nullable|string|max:32','direccion'=>'nullable|string|max:2000']);$r->user()->update($v);$audit->registrar($r,'perfil.actualizado');return back()->with('success','Perfil guardado.');}}
