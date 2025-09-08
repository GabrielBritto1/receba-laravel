<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
   /**
    * Seed the application's database.
    */
   public function run(): void
   {
      DB::table('users')->insert([
         'name' => 'Admin',
         'email' => 'admin@admin.com',
         'password' => Hash::make('admin@admin.com')
      ]);
      DB::table('roles')->insert([
         'name' => 'is-admin',
      ]);
      DB::table('roles')->insert([
         'name' => 'coordenador',
      ]);
      DB::table('roles')->insert([
         'name' => 'secretario',
      ]);
      DB::table('abilities')->insert([
         'name' => 'is-admin',
      ]);
      DB::table('abilities')->insert([
         'name' => 'coordenador',
      ]);
      DB::table('role_user')->insert([
         'user_id' => 1,
         'role_id' => 1,
      ]);
      DB::table('ability_role')->insert([
         'role_id' => 1,
         'ability_id' => 1,
      ]);
      DB::table('ability_role')->insert([
         'role_id' => 2,
         'ability_id' => 2,
      ]);
   }
}
