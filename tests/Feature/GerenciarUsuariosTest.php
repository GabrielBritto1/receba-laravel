<?php

use App\Models\User;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    Role::firstOrCreate(['name' => 'Administrador', 'guard_name' => 'web']);
    Role::firstOrCreate(['name' => 'Coordenador',   'guard_name' => 'web']);
    Role::firstOrCreate(['name' => 'Secretario',    'guard_name' => 'web']);
});

test('update rejeita role inexistente via AJAX', function () {
    $actor = User::factory()->create();
    $target = User::factory()->create();

    $response = $this
        ->actingAs($actor)
        ->withHeaders(['Accept' => 'application/json'])
        ->put(route('users.update', $target->id), [
            'name'  => $target->name,
            'email' => $target->email,
            'roles' => ['RoleQueNaoExiste'],
        ]);

    $response->assertUnprocessable();
});

test('gerenciar_usuarios passa users com roles para a view', function () {
    $user = User::factory()->create();
    $user->assignRole('Coordenador');

    $actor = User::factory()->create();
    $actor->assignRole('Administrador');

    $response = $this
        ->actingAs($actor)
        ->get(route('users.gerenciar_usuarios'));

    $response->assertOk();
    $response->assertViewHas('users', function ($users) use ($user) {
        $found = $users->firstWhere('id', $user->id);
        return $found && in_array('Coordenador', $found['roles']->toArray());
    });
});

test('gerenciar_usuarios passa lista de roles disponíveis para a view', function () {
    $actor = User::factory()->create();
    $actor->assignRole('Administrador');

    $response = $this
        ->actingAs($actor)
        ->get(route('users.gerenciar_usuarios'));

    $response->assertOk();
    $response->assertViewHas('roles', function ($roles) {
        return $roles->contains('Administrador')
            && $roles->contains('Coordenador')
            && $roles->contains('Secretario');
    });
});

test('gerenciar_usuarios rejeita usuário sem role Administrador', function () {
    $actor = User::factory()->create();
    // $actor has no role assigned — should be forbidden

    $response = $this
        ->actingAs($actor)
        ->get(route('users.gerenciar_usuarios'));

    $response->assertForbidden();
});
