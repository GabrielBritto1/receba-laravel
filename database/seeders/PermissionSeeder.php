<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
   /**
    * Seed the application's permissions.
    */
   public function run(): void
   {
      $permissions = [
         // CRUD Parceiros
         'parceiros.create',
         'parceiros.read',
         'parceiros.update',
         'parceiros.delete',

         // CRUD Famílias
         'familias.create',
         'familias.read',
         'familias.update',
         'familias.delete',

         // CRUD Relatórios
         'relatorios.create',
         'relatorios.read',
         'relatorios.update',
         'relatorios.delete',

         // Visibilidade de abas / menus (sidebar)
         'menu.dashboard.view',
         'menu.usuarios.view',
         'menu.meu.parceiro.view',
         'menu.parceiros.view',
         'menu.familias.view',
         'menu.cestas.view',
         'menu.solicitacoes.view',
         'menu.itens.view',
         'menu.entregas.view',
         'menu.gerenciamento.view',
         'menu.relatorios.view',
         'menu.relatorios.pdf.view',
         'menu.relatorios.planilha.view',
      ];

      foreach ($permissions as $name) {
         Permission::firstOrCreate([
            'name' => $name,
            'guard_name' => 'web',
         ]);
      }
   }
}

