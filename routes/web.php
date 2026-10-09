<?php
use App\Http\Controllers\{AdminProductoController, CatalogoController, CitaVeterinariaController, EmpleadoController, ExpedienteController, OperacionController, PedidoController, PerfilController};
use Illuminate\Support\Facades\Route;

Route::middleware('catalog.access')->group(function () {
    Route::get('/', [CatalogoController::class, 'home'])->name('home');
    Route::get('/catalogo', [CatalogoController::class, 'index'])->name('catalogo.index');
    Route::get('/catalogo/{producto}', [CatalogoController::class, 'show'])->name('catalogo.show');
    Route::get('/carrito', fn () => \Inertia\Inertia::render('Carrito/Index'))->name('carrito');
    Route::get('/citas/nueva', [CitaVeterinariaController::class, 'create'])->name('citas.create');
    Route::post('/compatibilidad', [CatalogoController::class, 'compatibility'])->name('compatibilidad');
});

Route::middleware(['auth', 'catalog.access'])->group(function () {
    Route::get('/checkout', fn () => \Inertia\Inertia::render('Checkout/Index'))->name('checkout');
    Route::post('/pedidos', [PedidoController::class, 'store'])->name('pedidos.store');
    Route::get('/pedidos/{pedido}/confirmacion', [PedidoController::class, 'confirmation'])->name('pedidos.confirmacion');
    Route::get('/mi-historial', [PedidoController::class, 'historial'])->name('historial');
    Route::get('/pedidos/{pedido}/pase', [PedidoController::class, 'pase'])->name('pedidos.pase');
    Route::get('/perfil', [PerfilController::class, 'edit'])->name('perfil.edit');
    Route::patch('/perfil', [PerfilController::class, 'update'])->name('perfil.update');

});

Route::middleware(['auth', 'role:Cliente'])->post('/citas', [CitaVeterinariaController::class, 'store'])->name('citas.store');

Route::middleware(['auth', 'role:Veterinario'])->group(function () {
    Route::get('/veterinaria/agenda', [CitaVeterinariaController::class, 'agenda'])->name('vet.agenda');
    Route::patch('/veterinaria/citas/{cita}', [CitaVeterinariaController::class, 'update'])->name('vet.citas.update');
    Route::post('/veterinaria/citas/{cita}/expediente', [ExpedienteController::class, 'store'])->name('vet.expediente');
});
Route::middleware('auth')->get('/expedientes/{expediente}/receta', [ExpedienteController::class, 'receta'])->name('expedientes.receta');
Route::middleware('auth')->get('/citas/{cita}/documentos/{documento}', [ExpedienteController::class, 'documentoCita'])->whereNumber('documento')->name('citas.documento');

Route::middleware(['auth', 'role:Administrador'])->group(function () {
    Route::get('/admin', fn () => \Inertia\Inertia::render('Admin/Dashboard'))->name('admin.dashboard');
    Route::get('/admin/productos', [AdminProductoController::class, 'index'])->name('admin.productos');
    Route::post('/admin/productos', [AdminProductoController::class, 'store'])->name('admin.productos.store');
    Route::patch('/admin/productos/{producto}', [AdminProductoController::class, 'update'])->name('admin.productos.update');
    Route::get('/admin/empleados', [EmpleadoController::class, 'index'])->name('admin.empleados');
    Route::post('/admin/empleados', [EmpleadoController::class, 'store'])->name('admin.empleados.store');
    Route::patch('/admin/empleados/{empleado}', [EmpleadoController::class, 'update'])->name('admin.empleados.update');
});

Route::middleware(['auth', 'role:Cajero,Administrador'])->prefix('pos')->name('pos.')->group(function () {
    Route::get('/', [OperacionController::class, 'pos'])->name('index');
    Route::get('/citas', [OperacionController::class, 'clinicalPos'])->name('citas');
    Route::post('/producto-codigo', [OperacionController::class, 'productoPorCodigo'])->name('producto');
    Route::post('/cobrar', [OperacionController::class, 'cobrar'])->name('cobrar');
    Route::post('/pedidos/{pedido}/confirmar', [OperacionController::class, 'confirmarPedido'])->name('pedido.confirmar');
    Route::post('/citas/{cita}/cobrar', [OperacionController::class, 'cobrarCita'])->name('cita.cobrar');
    Route::post('/citas/{cita}/reembolsar', [OperacionController::class, 'reembolsarCita'])->name('cita.reembolsar');
    Route::post('/corte-z', [OperacionController::class, 'corte'])->name('corte');
    Route::post('/entregar', [OperacionController::class, 'entregar'])->name('entregar');
});

Route::middleware(['auth', 'role:Almacenista,Administrador'])->prefix('almacen')->name('almacen.')->group(function () {
    Route::get('/picking', [OperacionController::class, 'picking'])->name('picking');
    Route::patch('/pedidos/{pedido}', [OperacionController::class, 'estadoPedido'])->name('pedidos.estado');
});

require __DIR__ . '/auth.php';
