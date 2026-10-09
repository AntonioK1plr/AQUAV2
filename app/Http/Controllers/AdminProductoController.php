<?php
namespace App\Http\Controllers;
use App\Models\Producto;use App\Services\AuditoriaService;use Illuminate\Http\Request;use Illuminate\Validation\Rule;use Inertia\Inertia;
class AdminProductoController extends Controller
{
    private function rules(?int $id=null):array{return ['nombre'=>'required|string|max:255','descripcion'=>'nullable|string|max:10000','precio'=>'required|numeric|min:0','stock'=>'required|integer|min:0','tipo_agua'=>['required_if:es_ser_vivo,1','nullable',Rule::in(['dulce','salada'])],'marca'=>'nullable|string|max:120','ph_min'=>'required_if:es_ser_vivo,1|nullable|numeric|between:0,14','ph_max'=>'required_if:es_ser_vivo,1|nullable|numeric|between:0,14','temp_min'=>'required_if:es_ser_vivo,1|nullable|numeric|between:-5,60','temp_max'=>'required_if:es_ser_vivo,1|nullable|numeric|between:-5,60','temperamento'=>'required_if:es_ser_vivo,1|nullable|string|max:80','es_ser_vivo'=>'required|boolean','codigo_barras'=>['nullable','string','max:120',Rule::unique('productos','codigo_barras')->ignore($id)],'pasillo'=>'nullable|string|max:80'];}
    public function index(){return Inertia::render('Admin/Productos',['productos'=>Producto::orderBy('nombre')->get()]);}
    public function store(Request $r,AuditoriaService $audit){$v=$r->validate($this->rules());$p=Producto::create($v);$audit->registrar($r,'producto.creado:'.$p->id);return back()->with('success','Producto creado.');}
    public function update(Request $r,Producto $producto,AuditoriaService $audit){$v=$r->validate($this->rules($producto->id));$producto->update($v);$audit->registrar($r,'producto.actualizado:'.$producto->id);return back()->with('success','Producto actualizado.');}
}
