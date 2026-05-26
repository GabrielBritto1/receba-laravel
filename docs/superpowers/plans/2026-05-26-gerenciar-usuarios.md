# Gerenciar Usuários — Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Substituir o formulário gerado via `innerHTML` na página `/users/gerenciar_usuarios` por uma interface reativa com painel dividido (Alpine.js), gerenciamento de roles (Spatie) e salvamento AJAX.

**Architecture:** JSON dos usuários (id, name, email, roles) é embutido no blade pelo controller. Alpine.js gerencia estado no cliente (busca, seleção, form). Salvar dispara `axios.put` para a rota existente `/users/{user}`, que agora responde com JSON quando `Accept: application/json` estiver presente.

**Tech Stack:** Laravel 11, Pest PHP, Alpine.js v3, axios, AdminLTE (Bootstrap 4), Spatie Laravel Permission

---

## File Map

| Arquivo | Ação | Responsabilidade |
|---|---|---|
| `app/Http/Requests/UpdateUserRequest.php` | Modificar | Adicionar validação do campo `roles` |
| `app/Http/Controllers/Admin/UserController.php` | Modificar | `gerenciarUsuarios()` e `update()` |
| `resources/views/admin/users/gerenciar_usuarios.blade.php` | Reescrever | Painel dividido com Alpine.js |
| `tests/Feature/GerenciarUsuariosTest.php` | Criar | Testes de integração do controller |

---

## Task 1: Adicionar validação de `roles` no UpdateUserRequest

**Files:**
- Modify: `app/Http/Requests/UpdateUserRequest.php`
- Test: `tests/Feature/GerenciarUsuariosTest.php`

- [ ] **Step 1: Criar o arquivo de testes**

Criar `tests/Feature/GerenciarUsuariosTest.php`:

```php
<?php

use App\Models\User;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    Role::create(['name' => 'Administrador', 'guard_name' => 'web']);
    Role::create(['name' => 'Coordenador',   'guard_name' => 'web']);
    Role::create(['name' => 'Secretario',    'guard_name' => 'web']);
});
```

- [ ] **Step 2: Escrever o teste de validação de role inválida**

Adicionar ao arquivo de testes:

```php
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
```

- [ ] **Step 3: Rodar o teste para confirmar que falha**

```bash
php artisan test tests/Feature/GerenciarUsuariosTest.php --filter="update rejeita role"
```

Resultado esperado: **FAIL** — `The selected roles.0 is invalid.` (ou similar)

> O teste falha porque `UpdateUserRequest` ainda não valida `roles`. A validação atual não conhece esse campo.

- [ ] **Step 4: Adicionar validação de `roles` no UpdateUserRequest**

Abrir `app/Http/Requests/UpdateUserRequest.php` e alterar o método `rules()`:

```php
public function rules(): array
{
    $rules = parent::rules();
    $rules['password'] = ['nullable', 'min:6', 'max:20'];
    $rules['roles']    = ['nullable', 'array'];
    $rules['roles.*']  = ['string', 'exists:roles,name'];
    return $rules;
}
```

- [ ] **Step 5: Rodar o teste para confirmar que passa**

```bash
php artisan test tests/Feature/GerenciarUsuariosTest.php --filter="update rejeita role"
```

Resultado esperado: **PASS**

- [ ] **Step 6: Commit**

```bash
git add app/Http/Requests/UpdateUserRequest.php tests/Feature/GerenciarUsuariosTest.php
git commit -m "feat: adicionar validação de roles no UpdateUserRequest"
```

---

## Task 2: Atualizar UserController::gerenciarUsuarios()

**Files:**
- Modify: `app/Http/Controllers/Admin/UserController.php`
- Test: `tests/Feature/GerenciarUsuariosTest.php`

- [ ] **Step 1: Escrever o teste para a view**

Adicionar ao arquivo de testes:

```php
test('gerenciar_usuarios passa users com roles para a view', function () {
    $user = User::factory()->create();
    $user->assignRole('Coordenador');

    $actor = User::factory()->create();

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
```

- [ ] **Step 2: Rodar os testes para confirmar que falham**

```bash
php artisan test tests/Feature/GerenciarUsuariosTest.php --filter="gerenciar_usuarios"
```

Resultado esperado: **FAIL** — a view atual não recebe `roles` e `users` não tem a chave `roles`.

- [ ] **Step 3: Atualizar gerenciarUsuarios() no controller**

Em `app/Http/Controllers/Admin/UserController.php`, substituir o método `gerenciarUsuarios()`:

```php
public function gerenciarUsuarios()
{
    $users = User::with('roles')->get()->map(fn($u) => [
        'id'    => $u->id,
        'name'  => $u->name,
        'email' => $u->email,
        'roles' => $u->roles->pluck('name'),
    ]);
    $roles = Role::all()->pluck('name');
    return view('admin.users.gerenciar_usuarios', compact('users', 'roles'));
}
```

> `Role` já está importado no topo do controller: `use Spatie\Permission\Models\Role;`

- [ ] **Step 4: Rodar os testes para confirmar que passam**

```bash
php artisan test tests/Feature/GerenciarUsuariosTest.php --filter="gerenciar_usuarios"
```

Resultado esperado: **PASS** (2 testes)

- [ ] **Step 5: Commit**

```bash
git add app/Http/Controllers/Admin/UserController.php tests/Feature/GerenciarUsuariosTest.php
git commit -m "feat: passar users com roles e lista de roles para view gerenciar_usuarios"
```

---

## Task 3: Atualizar UserController::update() para resposta AJAX + sync de roles

**Files:**
- Modify: `app/Http/Controllers/Admin/UserController.php`
- Test: `tests/Feature/GerenciarUsuariosTest.php`

- [ ] **Step 1: Escrever os testes para update() via AJAX**

Adicionar ao arquivo de testes:

```php
test('update via AJAX atualiza nome e email e retorna JSON', function () {
    $actor  = User::factory()->create();
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

    $response = $this
        ->actingAs($actor)
        ->withHeaders(['Accept' => 'application/json'])
        ->put(route('users.update', 999999), [
            'name'  => 'Nome',
            'email' => 'email@test.com',
        ]);

    $response->assertNotFound();
});
```

- [ ] **Step 2: Rodar os testes para confirmar que falham**

```bash
php artisan test tests/Feature/GerenciarUsuariosTest.php --filter="update via AJAX"
```

Resultado esperado: **FAIL** — o `update()` atual redireciona em vez de retornar JSON.

- [ ] **Step 3: Atualizar update() no controller**

Em `app/Http/Controllers/Admin/UserController.php`, substituir o método `update()`:

```php
public function update(UpdateUserRequest $request, string $id)
{
    if (!$user = User::find($id)) {
        return $request->expectsJson()
            ? response()->json(['message' => 'Usuário não encontrado.'], 404)
            : redirect()->back()->with('message', 'Usuário não encontrado!');
    }

    $data = $request->only('name', 'email');
    if ($request->filled('password')) {
        $data['password'] = bcrypt($request->password);
    }
    $user->update($data);
    $user->syncRoles($request->input('roles', []));

    return $request->expectsJson()
        ? response()->json(['message' => 'Usuário atualizado com sucesso!'])
        : redirect()->route('users.configuracao', $user->id)->with('success', 'Usuário editado com sucesso!');
}
```

- [ ] **Step 4: Rodar os testes para confirmar que passam**

```bash
php artisan test tests/Feature/GerenciarUsuariosTest.php
```

Resultado esperado: **PASS** (todos os testes do arquivo)

- [ ] **Step 5: Commit**

```bash
git add app/Http/Controllers/Admin/UserController.php tests/Feature/GerenciarUsuariosTest.php
git commit -m "feat: update() retorna JSON para AJAX e sincroniza roles via Spatie"
```

---

## Task 4: Reescrever a view gerenciar_usuarios.blade.php com Alpine.js

**Files:**
- Rewrite: `resources/views/admin/users/gerenciar_usuarios.blade.php`

Não há testes automatizados para esta task — a verificação é manual (Step 3).

- [ ] **Step 1: Reescrever o blade completo**

Substituir todo o conteúdo de `resources/views/admin/users/gerenciar_usuarios.blade.php`:

```blade
@extends('adminlte::page')

@section('title', 'Gerenciar Usuários')

@section('content_header')
<h1 class="text-bold"><i class="fas fa-id-badge"></i> Gerenciar Usuários</h1>
@stop

@section('content')
<div
    class="card"
    x-data="gerenciarUsuarios()"
>
    <div class="card-body p-0" style="display:flex; min-height:520px;">

        {{-- PAINEL ESQUERDO --}}
        <div style="width:300px;min-width:300px;border-right:1px solid #dee2e6;display:flex;flex-direction:column;">

            {{-- Busca --}}
            <div class="p-3 border-bottom">
                <div class="input-group input-group-sm">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                    </div>
                    <input
                        type="text"
                        class="form-control"
                        placeholder="Buscar por nome ou email..."
                        x-model="search"
                    >
                </div>
            </div>

            {{-- Lista --}}
            <div style="flex:1;overflow-y:auto;">
                <template x-for="user in filteredUsers" :key="user.id">
                    <div
                        class="px-3 py-2 border-bottom"
                        style="cursor:pointer;"
                        :style="selected && selected.id === user.id
                            ? 'background:#fff;border-left:3px solid #007bff;'
                            : 'background:#f8f9fa;border-left:3px solid transparent;'"
                        @click="selectUser(user)"
                    >
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="font-weight-bold text-sm" x-text="user.name"></div>
                                <div class="text-muted" style="font-size:11px;" x-text="user.email"></div>
                            </div>
                            <span
                                class="badge badge-pill"
                                :class="roleBadgeClass(user.roles)"
                                x-text="user.roles.length ? user.roles[0] : 'Sem role'"
                            ></span>
                        </div>
                    </div>
                </template>

                <div x-show="filteredUsers.length === 0" class="p-3 text-muted text-sm text-center">
                    Nenhum usuário encontrado.
                </div>
            </div>

            {{-- Rodapé contador --}}
            <div class="px-3 py-2 border-top bg-white text-muted" style="font-size:11px;">
                <span x-text="filteredUsers.length"></span> usuário(s) encontrado(s)
            </div>
        </div>

        {{-- PAINEL DIREITO --}}
        <div style="flex:1;padding:24px;overflow-y:auto;">

            {{-- Estado vazio --}}
            <div x-show="!selected" class="text-center text-muted pt-5">
                <i class="fas fa-hand-point-left fa-2x mb-3"></i>
                <p>Selecione um usuário na lista para editar.</p>
            </div>

            {{-- Formulário --}}
            <div x-show="selected" x-cloak>

                {{-- Cabeçalho --}}
                <div class="d-flex justify-content-between align-items-start mb-3 pb-2 border-bottom">
                    <div>
                        <h5 class="mb-0 font-weight-bold" x-text="'Editando: ' + (selected ? selected.name : '')"></h5>
                        <small class="text-muted" x-text="selected ? 'ID #' + selected.id : ''"></small>
                    </div>
                    {{-- Toast --}}
                    <div
                        x-show="toast.show"
                        x-transition
                        :class="toast.type === 'success' ? 'alert alert-success' : 'alert alert-danger'"
                        class="py-1 px-3 mb-0"
                        style="font-size:13px;"
                        x-text="toast.message"
                    ></div>
                </div>

                {{-- Nome e Email --}}
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold" style="font-size:12px;">Nome</label>
                            <input
                                type="text"
                                class="form-control form-control-sm"
                                x-model="form.name"
                                placeholder="Nome completo"
                            >
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold" style="font-size:12px;">Email</label>
                            <input
                                type="email"
                                class="form-control form-control-sm"
                                x-model="form.email"
                                placeholder="email@exemplo.com"
                            >
                        </div>
                    </div>
                </div>

                {{-- Role --}}
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold" style="font-size:12px;">Role</label>
                            <select class="form-control form-control-sm" x-model="form.role">
                                <option value="">— Sem role —</option>
                                <template x-for="r in roles" :key="r">
                                    <option :value="r" x-text="r"></option>
                                </template>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold" style="font-size:12px;">
                                Nova senha
                                <span class="font-weight-normal text-muted">(deixe em branco para não alterar)</span>
                            </label>
                            <input
                                type="password"
                                class="form-control form-control-sm"
                                x-model="form.password"
                                placeholder="••••••••"
                                autocomplete="new-password"
                            >
                        </div>
                    </div>
                </div>

                {{-- Botões --}}
                <div class="d-flex align-items-center mt-2">
                    <button
                        type="button"
                        class="btn btn-success btn-sm font-weight-bold mr-2"
                        :disabled="saving"
                        @click="save()"
                    >
                        <span x-show="!saving"><i class="fas fa-save mr-1"></i> Salvar alterações</span>
                        <span x-show="saving"><i class="fas fa-spinner fa-spin mr-1"></i> Salvando...</span>
                    </button>
                    <button
                        type="button"
                        class="btn btn-secondary btn-sm"
                        :disabled="saving"
                        @click="selected = null"
                    >
                        <i class="fas fa-times mr-1"></i> Fechar
                    </button>
                </div>

            </div>
        </div>

    </div>
</div>
@stop

@section('js')
<script>
function gerenciarUsuarios() {
    return {
        users:    {!! json_encode($users) !!},
        roles:    {!! json_encode($roles) !!},
        search:   '',
        selected: null,
        form:     { name: '', email: '', role: '', password: '' },
        saving:   false,
        toast:    { show: false, type: 'success', message: '' },

        get filteredUsers() {
            const q = this.search.toLowerCase().trim();
            if (!q) return this.users;
            return this.users.filter(u =>
                u.name.toLowerCase().includes(q) ||
                u.email.toLowerCase().includes(q)
            );
        },

        selectUser(user) {
            this.selected       = user;
            this.form.name      = user.name;
            this.form.email     = user.email;
            this.form.role      = user.roles.length ? user.roles[0] : '';
            this.form.password  = '';
        },

        async save() {
            this.saving = true;
            try {
                await axios.put(`/users/${this.selected.id}`, {
                    name:     this.form.name,
                    email:    this.form.email,
                    roles:    this.form.role ? [this.form.role] : [],
                    password: this.form.password || undefined,
                });

                // Atualiza badge de role na lista sem recarregar
                const u = this.users.find(u => u.id === this.selected.id);
                if (u) {
                    u.name  = this.form.name;
                    u.email = this.form.email;
                    u.roles = this.form.role ? [this.form.role] : [];
                }
                // Atualiza o objeto selected também
                this.selected.name  = this.form.name;
                this.selected.email = this.form.email;
                this.selected.roles = this.form.role ? [this.form.role] : [];

                this.showToast('success', 'Usuário atualizado com sucesso!');
            } catch (e) {
                const msg = e.response?.data?.message ?? 'Erro ao salvar.';
                this.showToast('error', msg);
            } finally {
                this.saving = false;
            }
        },

        showToast(type, message) {
            this.toast = { show: true, type, message };
            setTimeout(() => { this.toast.show = false; }, 3000);
        },

        roleBadgeClass(roles) {
            if (!roles || !roles.length) return 'badge-secondary';
            switch (roles[0]) {
                case 'Administrador': return 'badge-success';
                case 'Coordenador':   return 'badge-info';
                case 'Secretario':    return 'badge-warning';
                default:              return 'badge-secondary';
            }
        },
    };
}
</script>
@stop
```

- [ ] **Step 2: Adicionar `[x-cloak]` ao CSS do AdminLTE (se ainda não existir)**

Verificar se `public/css/app.css` ou o layout principal já tem:

```bash
grep -r "x-cloak" /Users/gabrielbritto/Documents/Projetos/receba-laravel/resources/ --include="*.css" --include="*.blade.php" | head -5
```

Se não encontrar nenhum resultado, adicionar ao `@section('css')` da view (acima do `@section('content_header')`):

```blade
@section('css')
<style>[x-cloak] { display: none !important; }</style>
@stop
```

- [ ] **Step 3: Verificar manualmente no browser**

Iniciar o servidor:

```bash
php artisan serve
```

Acessar `http://localhost:8000/users/gerenciar_usuarios` (autenticado).

Checklist de verificação:
- [ ] Painel esquerdo lista todos os usuários com badge de role colorida
- [ ] Campo de busca filtra por nome e por email em tempo real
- [ ] Contador "X usuário(s) encontrado(s)" atualiza junto com a busca
- [ ] Clicar num usuário preenche o formulário à direita imediatamente (sem requisição)
- [ ] Campo "role" mostra a role atual do usuário selecionado
- [ ] Salvar sem senha não altera o hash no banco
- [ ] Salvar com nova senha atualiza o hash no banco
- [ ] Trocar a role e salvar → badge na lista atualiza instantaneamente sem reload
- [ ] Toast "Usuário atualizado com sucesso!" aparece e some em 3s
- [ ] Botão "Salvar" fica desativado e mostra spinner durante o envio
- [ ] Clicar "Fechar" volta ao estado vazio

- [ ] **Step 4: Rodar a suite completa de testes para checar regressões**

```bash
php artisan test
```

Resultado esperado: todos os testes passando, incluindo os do `GerenciarUsuariosTest`.

- [ ] **Step 5: Commit**

```bash
git add resources/views/admin/users/gerenciar_usuarios.blade.php
git commit -m "feat: reescrever gerenciar_usuarios com painel dividido Alpine.js e AJAX save"
```
