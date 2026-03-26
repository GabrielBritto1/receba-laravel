<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
   /**
    * Seed the application's database.
    */
   public function run(): void
   {
      DB::table('users')->insert([
         'name' => 'Admin',
         'email' => $_ENV['LOGIN_USER_ADMIN'],
         'password' => Hash::make($_ENV['SENHA_USER_ADMIN'])
      ]);
      $adminRole = Role::create(['name' => 'Administrador']);
      $coordenadorRole = Role::create(['name' => 'Coordenador']);
      $secretarioRole = Role::create(['name' => 'Secretario']);

      $this->call(PermissionSeeder::class);

      // Admin tem todas as permissões por padrão
      $adminRole->givePermissionTo(Permission::all());

      $admin = User::first();
      if ($admin) {
         $admin->assignRole($adminRole);
      }
   }
}
