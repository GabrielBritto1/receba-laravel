# Gerenciar Usuários — Design Spec

**Data:** 2026-05-26
**Branch:** fixando-bugs

---

## Objetivo

Melhorar a página `/users/gerenciar_usuarios` substituindo o formulário gerado via `innerHTML` por uma interface reativa com painel dividido, gerenciamento de roles e salvamento AJAX.

---

## Arquitetura

### Abordagem escolhida
Alpine.js + JSON embutido no blade + axios PUT.

Os dados de todos os usuários (id, name, email, roles atuais) são serializados como JSON no blade pelo controller. Alpine.js gerencia o estado da página no cliente: filtro de busca, usuário selecionado e dados do formulário. O salvamento envia um `axios.put` sem recarregar a página.

### Fluxo de dados
```
blade renderizado → JSON de usuários embutido
        ↓
Alpine.js carrega JSON → popula lista
        ↓
usuário clica num item → Alpine seta selectedUser → form preenchido reativamente
        ↓
usuário edita campos → Alpine atualiza form state
        ↓
clique em Salvar → axios.put /users/{id} → controller processa → JSON response
        ↓
Alpine exibe toast de sucesso/erro → atualiza badge de role na lista
```

---

## Backend

### `UserController::gerenciarUsuarios()`

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

### `UserController::update()`

Estender o método existente para:
1. Retornar JSON quando a requisição for AJAX (`$request->expectsJson()`)
2. Sincronizar roles via `$user->syncRoles($request->input('roles', []))`

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

### `UpdateUserRequest`

`UpdateUserRequest` estende `StoreUserRequest`. Adicionar `roles` apenas no `UpdateUserRequest::rules()` (não no parent):
```php
'roles'   => 'nullable|array',
'roles.*' => 'string|exists:roles,name',
```

---

## Frontend

### Arquivo alterado
`resources/views/admin/users/gerenciar_usuarios.blade.php` — reescrita completa.

### Layout
Dois painéis dentro de um card:
- **Painel esquerdo (300px fixo):** campo de busca + lista de usuários rolável + contador de resultados no rodapé
- **Painel direito (flex:1):** formulário de edição ou estado vazio

### Componente Alpine.js (`x-data`)

Estado:
```js
{
    users: [],           // array do JSON embutido
    roles: [],           // array de roles disponíveis
    search: '',          // string de busca
    selected: null,      // objeto do usuário selecionado
    form: { name, email, role, password },
    saving: false,
    toast: { show: false, type: 'success', message: '' }
}
```

Computed (via `get`):
- `filteredUsers` — filtra `users` por `search` (nome ou email, case-insensitive)

Métodos:
- `selectUser(user)` — seta `selected` e preenche `form`
- `save()` — envia `axios.put`, gerencia `saving`, exibe toast, atualiza badge de role na lista
- `showToast(type, message)` — exibe toast por 3 segundos

### Lista de usuários (painel esquerdo)
- Campo input com `x-model="search"`
- Lista gerada com `x-for="user in filteredUsers"`
- Item ativo destacado com borda azul esquerda (`border-left: 3px solid #007bff`)
- Badge de role colorida por tipo:
  - `Administrador` → verde (`#28a745`)
  - `Coordenador` → azul-claro (`#17a2b8`)
  - `Secretario` → amarelo (`#ffc107`)
  - Sem role → cinza (`#6c757d`)
- Contador de resultados no rodapé: `X usuários encontrados`

### Formulário (painel direito)
- **Estado vazio:** mostrado quando `selected === null`. Ícone + texto "Selecione um usuário na lista para editar".
- **Estado editando:** mostrado quando `selected !== null`.
  - Cabeçalho: nome do usuário selecionado + ID
  - Campos: Nome, Email (grid 2 colunas), Role (select), Senha (opcional, placeholder "deixe em branco para não alterar")
  - Botões: "Salvar alterações" (desativado + spinner durante `saving`) e "Limpar"
  - Toast de feedback: verde para sucesso, vermelho para erro, desaparece em 3s

### Salvamento AJAX
```js
async save() {
    this.saving = true;
    try {
        await axios.put(`/users/${this.selected.id}`, {
            name: this.form.name,
            email: this.form.email,
            roles: this.form.role ? [this.form.role] : [],
            password: this.form.password || undefined,
        });
        // Nota: X-CSRF-TOKEN é configurado globalmente pelo bootstrap.js do Laravel — sem necessidade de header manual.
        // Atualiza roles na lista local
        const u = this.users.find(u => u.id === this.selected.id);
        if (u) u.roles = this.form.role ? [this.form.role] : [];
        this.showToast('success', 'Usuário atualizado com sucesso!');
    } catch (e) {
        const msg = e.response?.data?.message ?? 'Erro ao salvar.';
        this.showToast('error', msg);
    } finally {
        this.saving = false;
    }
}
```

---

## O que não muda

- Rotas existentes — nenhuma rota nova é criada
- Página `edit.blade.php` — não é alterada
- CPF e telefone — fora de escopo
- Paginação — lista completa no cliente (aceitável para <100 usuários)

---

## Arquivos a modificar

| Arquivo | Mudança |
|---|---|
| `app/Http/Controllers/Admin/UserController.php` | `gerenciarUsuarios()` + `update()` |
| `app/Http/Requests/UpdateUserRequest.php` | Adicionar `roles` à validação |
| `resources/views/admin/users/gerenciar_usuarios.blade.php` | Reescrita completa com Alpine.js |
