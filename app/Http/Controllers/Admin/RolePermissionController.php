<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionController extends Controller
{
   public function index()
   {
      $roles = Role::orderBy('name')->get();
      $permissions = Permission::orderBy('name')->get();

      return view('admin.roles.permissions', compact('roles', 'permissions'));
   }

   public function update(Request $request)
   {
      $data = $request->validate([
         'roles' => 'array',
         'roles.*.permissions' => 'array',
         'roles.*.permissions.*' => 'integer|exists:permissions,id',
      ]);

      $rolesData = $data['roles'] ?? [];

      foreach ($rolesData as $roleId => $rolePayload) {
         $role = Role::find($roleId);

         if ($role) {
            $permissionIds = $rolePayload['permissions'] ?? [];
            $permissions = Permission::whereIn('id', $permissionIds)->get();
            $role->syncPermissions($permissions);
         }
      }

      return redirect()
         ->route('roles.permissions.index')
         ->with('success', 'Permissões atualizadas com sucesso!');
   }
}

