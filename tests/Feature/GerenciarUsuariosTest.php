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
