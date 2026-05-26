@extends('adminlte::page')

@section('title', 'Gerenciar Usuários')

@section('content_header')
<h1 class="text-bold"><i class="fas fa-id-badge"></i> Gerenciar Usuários</h1>
@stop

@section('css')
<style>[x-cloak] { display: none !important; }</style>
@stop

@section('content')
<div
    class="card"
    x-data="gerenciarUsuarios()"
    x-cloak
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
                                <div class="font-weight-bold" x-text="user.name"></div>
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

                <div x-show="filteredUsers.length === 0" class="p-3 text-muted text-center" style="font-size:12px;">
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
            <div x-show="selected">

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
