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

test('update via AJAX atualiza nome e email e retorna JSON', function () {
    $actor  = User::factory()->create();
    $actor->assignRole('Administrador');
    $target = User::factory()->create();

    $response = $this
        ->actingAs($actor)
        ->withHeaders(['Accept' => 'application/json'])
        ->put(route('users.update', $target->id), [
            'name'  => 'Novo Nome',
            'email' => 'novo@email.com',
        ]);

    $response->assertOk()->assertJson(['message' => 'Usuário atualizado com sucesso!']);
    expect($target->fresh()->name)->toBe('Novo Nome');
    expect($target->fresh()->email)->toBe('novo@email.com');
});

test('update via AJAX sincroniza roles do usuário', function () {
    $actor  = User::factory()->create();
    $actor->assignRole('Administrador');
    $target = User::factory()->create();
    $target->assignRole('Administrador');

    $response = $this
        ->actingAs($actor)
        ->withHeaders(['Accept' => 'application/json'])
        ->put(route('users.update', $target->id), [
            'name'  => $target->name,
            'email' => $target->email,
            'roles' => ['Coordenador'],
        ]);

    $response->assertOk();
    expect($target->fresh()->hasRole('Coordenador'))->toBeTrue();
    expect($target->fresh()->hasRole('Administrador'))->toBeFalse();
});

test('update via AJAX sem password não altera a senha', function () {
    $actor    = User::factory()->create();
    $actor->assignRole('Administrador');
    $target   = User::factory()->create();
    $hashAntes = $target->password;

    $response = $this
        ->actingAs($actor)
        ->withHeaders(['Accept' => 'application/json'])
        ->put(route('users.update', $target->id), [
            'name'  => $target->name,
            'email' => $target->email,
        ]);

    $response->assertOk();
    expect($target->fresh()->password)->toBe($hashAntes);
});

test('update via AJAX com nova senha atualiza o hash', function () {
    $actor    = User::factory()->create();
    $actor->assignRole('Administrador');
    $target   = User::factory()->create();
    $hashAntes = $target->password;

    $response = $this
        ->actingAs($actor)
        ->withHeaders(['Accept' => 'application/json'])
        ->put(route('users.update', $target->id), [
            'name'     => $target->name,
            'email'    => $target->email,
            'password' => 'novasenha123',
        ]);

    $response->assertOk();
    expect($target->fresh()->password)->not->toBe($hashAntes);
});

test('update via AJAX retorna 404 para usuário inexistente', function () {
    $actor = User::factory()->create();
    $actor->assignRole('Administrador');

    $response = $this
        ->actingAs($actor)
        ->withHeaders(['Accept' => 'application/json'])
        ->put(route('users.update', 999999), [
            'name'  => 'Nome',
            'email' => 'email@test.com',
        ]);

    $response->assertNotFound();
});
