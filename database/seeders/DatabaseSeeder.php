<?php
namespace Database\Seeders;
use App\Models\User; use Illuminate\Database\Seeder;
class DatabaseSeeder extends Seeder {public function run():void{$email=env('AQUARIUM_ADMIN_EMAIL');$password=env('AQUARIUM_ADMIN_PASSWORD');if(!$email||!$password)return;User::updateOrCreate(['email'=>$email],['name'=>env('AQUARIUM_ADMIN_NAME','Administrador AQUARIUM'),'password'=>$password,'role'=>'Administrador']);}}
