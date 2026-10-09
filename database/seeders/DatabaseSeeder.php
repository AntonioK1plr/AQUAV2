<?php
namespace Database\Seeders;

use App\Models\Producto;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('AQUARIUM_ADMIN_EMAIL');
        $password = env('AQUARIUM_ADMIN_PASSWORD');
        if ($email && $password) {
            User::updateOrCreate(['email' => $email], [
                'name' => env('AQUARIUM_ADMIN_NAME', 'Administrador AQUARIUM'),
                'password' => $password,
                'role' => 'Administrador',
            ]);
        }

        $products = [
            ['Pez Payaso Ocellaris', 'Pez marino criado en cautiverio. Requiere acuario maduro y aclimatación cuidadosa.', 480, 8, 'salada', 'AQUARIUM', 8.0, 8.4, 24, 27, 'Pacífico', true, 'AQ-PEZ-001', 'Marino A1'],
            ['Pez Ángel Emperador', 'Especie marina para acuaristas con experiencia y sistemas amplios.', 1250, 4, 'salada', 'AQUARIUM', 8.1, 8.4, 24, 27, 'Semiagresivo', true, 'AQ-PEZ-002', 'Marino A2'],
            ['Pez Cirujano Azul', 'Especie activa que necesita espacio de nado y parámetros estables.', 950, 5, 'salada', 'AQUARIUM', 8.1, 8.4, 24, 27, 'Activo', true, 'AQ-PEZ-003', 'Marino A3'],
            ['Betta Halfmoon', 'Pez de agua dulce. Mantener en acuario filtrado y sin compañeros incompatibles.', 320, 12, 'dulce', 'AQUARIUM', 6.5, 7.5, 24, 28, 'Territorial', true, 'AQ-PEZ-004', 'Dulce B1'],
            ['Filtro canister 1200 L/h', 'Filtración para acuarios medianos con medios mecánicos y biológicos.', 1890, 6, 'dulce', 'AquaClear', null, null, null, null, null, false, 'AQ-EQ-001', 'Equipo C1'],
            ['Alimento marino granulado', 'Alimento completo para peces marinos pequeños y medianos.', 260, 20, 'salada', 'Ocean Nutrition', null, null, null, null, null, false, 'AQ-AL-001', 'Alimento D1'],
            ['Acondicionador de agua 250 ml', 'Neutraliza cloro y cloraminas para preparar agua de acuario.', 185, 15, 'dulce', 'AQUARIUM', null, null, null, null, null, false, 'AQ-TR-001', 'Tratamiento E1'],
            ['Kit de pruebas pH y amonio', 'Pruebas para monitorear parámetros esenciales del agua.', 540, 9, 'dulce', 'AquaCheck', null, null, null, null, null, false, 'AQ-TE-001', 'Equipo C2'],
        ];
        foreach ($products as [$nombre, $descripcion, $precio, $stock, $tipo, $marca, $phMin, $phMax, $tempMin, $tempMax, $temperamento, $living, $barcode, $aisle]) {
            Producto::updateOrCreate(['codigo_barras' => $barcode], [
                'nombre' => $nombre, 'descripcion' => $descripcion, 'precio' => $precio, 'stock' => $stock,
                'tipo_agua' => $tipo, 'marca' => $marca, 'ph_min' => $phMin, 'ph_max' => $phMax,
                'temp_min' => $tempMin, 'temp_max' => $tempMax, 'temperamento' => $temperamento,
                'es_ser_vivo' => $living, 'pasillo' => $aisle,
            ]);
        }
    }
}
