<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Services\AuditoriaService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class EmpleadoController extends Controller
{
    private const ROLES = ['Administrador', 'Cajero', 'Almacenista', 'Veterinario'];

    public function index()
    {
        return Inertia::render('Admin/Empleados', [
            'empleados' => User::whereIn('role', self::ROLES)
                ->orderBy('name')
                ->get(['id', 'name', 'email', 'role', 'telefono', 'is_active']),
        ]);
    }

    public function store(Request $request, AuditoriaService $audit)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:12',
            'role' => ['required', Rule::in(['Cajero', 'Almacenista', 'Veterinario'])],
            'telefono' => 'nullable|string|max:32',
        ]);

        $employee = User::create($data + ['is_active' => true]);
        $audit->registrar($request, 'empleado.creado:' . $employee->id);

        return back()->with('success', 'Empleado creado.');
    }

    public function update(Request $request, User $empleado, AuditoriaService $audit)
    {
        abort_unless(in_array($empleado->role, self::ROLES, true), 404);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($empleado->id)],
            'telefono' => 'nullable|string|max:32',
            'role' => ['required', Rule::in(self::ROLES)],
            'is_active' => 'required|boolean',
        ]);

        $isDisablingOrDemoting = $empleado->role === 'Administrador'
            && ($data['role'] !== 'Administrador' || !$data['is_active']);

        if ($isDisablingOrDemoting && User::where('role', 'Administrador')->where('is_active', true)->count() <= 1) {
            return back()->withErrors(['role' => 'Debe permanecer al menos un administrador activo.']);
        }

        if ($empleado->is($request->user()) && ($data['role'] !== 'Administrador' || !$data['is_active'])) {
            return back()->withErrors(['role' => 'No puedes desactivar ni cambiar tu propio rol de administrador.']);
        }

        $empleado->update($data);
        $audit->registrar($request, 'empleado.actualizado:' . $empleado->id);

        return back()->with('success', 'Datos y estado del empleado actualizados.');
    }
}
