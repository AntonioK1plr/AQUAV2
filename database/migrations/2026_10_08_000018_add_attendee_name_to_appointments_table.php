<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('citas_veterinarias', function (Blueprint $table) {
            $table->string('nombre_asistente', 255)->nullable()->after('user_id');
            $table->string('nombre_mascota')->nullable()->change();
            $table->string('especie')->nullable()->change();
            $table->text('motivo')->nullable()->change();
        });
    }

    public function down(): void
    {
        DB::table('citas_veterinarias')->whereNull('nombre_mascota')->update(['nombre_mascota' => 'Pendiente']);
        DB::table('citas_veterinarias')->whereNull('especie')->update(['especie' => 'Pendiente']);
        DB::table('citas_veterinarias')->whereNull('motivo')->update(['motivo' => 'Pendiente']);

        Schema::table('citas_veterinarias', function (Blueprint $table) {
            $table->string('nombre_mascota')->nullable(false)->change();
            $table->string('especie')->nullable(false)->change();
            $table->text('motivo')->nullable(false)->change();
            $table->dropColumn('nombre_asistente');
        });
    }
};
