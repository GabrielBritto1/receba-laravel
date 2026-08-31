@extends('adminlte::page')

@section('title', 'Gerenciar Usuários')

@section('content_header')
<h1 class="text-bold"><i class="fas fa-id-badge"></i> Gerenciar Usuários</h1>
@stop

@section('css')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
[x-cloak] { display: none !important; }

:root {
    --s-bg:          #f4f6f9;
    --s-bg2:         #eef1f5;
    --s-border:      #e2e6ec;
    --s-hover:       #eef4ee;
    --s-active:      #e3f3e4;
    --s-bright:      #212529;
    --s-mid:         #6c757d;
    --s-dim:         #99a1ab;
    --s-accent:      #28a745;
    --s-accent-glow: rgba(40,167,69,.14);

    --f-bg:          #ffffff;
    --f-bg2:         #f8f9fa;
    --f-border:      #e2e6ec;
    --f-text:        #212529;
    --f-text2:       #495057;
    --f-text3:       #939aa3;

    --font:          'Poppins', sans-serif;
    --r-sm:          8px;
}

/* ── shell ─────────────────────────────────────── */
.gu-shell {
    font-family: var(--font);
    display: flex;
}

/* ── left panel ────────────────────────────────── */
.gu-left {
    width: 300px;
    min-width: 300px;
    background: var(--s-bg);
    border-right: 1px solid var(--s-border);
    display: flex;
    flex-direction: column;
}

.gu-search-area {
    padding: 14px 14px 13px;
    border-bottom: 1px solid var(--s-border);
}

.gu-search-box { position: relative; }

.gu-search-ico {
    position: absolute; left: 11px; top: 50%;
    transform: translateY(-50%);
    color: var(--s-dim); font-size: 11px;
    pointer-events: none;
    transition: color .2s;
    z-index: 1;
}

.gu-search-input {
    width: 100%;
    background: #ffffff;
    border: 1px solid #d1d9e0;
    border-radius: var(--r-sm);
    padding: 9px 34px 9px 32px;
    font-size: 12.5px;
    color: var(--s-bright);
    font-family: var(--font);
    outline: none;
    transition: border-color .2s, box-shadow .2s;
}

.gu-search-input::placeholder { color: #adb5bd; }

.gu-search-input:focus {
    border-color: var(--s-accent);
    box-shadow: 0 0 0 3px var(--s-accent-glow);
}

.gu-search-input:focus ~ .gu-search-ico { color: var(--s-accent); }

.gu-search-clear {
    position: absolute; right: 9px; top: 50%;
    transform: translateY(-50%);
    width: 18px; height: 18px;
    border-radius: 50%;
    background: #dde1e7;
    border: none; color: var(--s-mid);
    font-size: 9px; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    transition: background .15s, color .15s;
}

.gu-search-clear:hover { background: #c9ced6; color: var(--s-bright); }

/* user list */
.gu-list { flex: 1; overflow-y: auto; max-height: 560px; }
.gu-list::-webkit-scrollbar { width: 3px; }
.gu-list::-webkit-scrollbar-thumb { background: #c9ced6; border-radius: 2px; }
.gu-list::-webkit-scrollbar-thumb:hover { background: #aab0b9; }

.gu-row {
    display: flex; align-items: center; gap: 10px;
    padding: 10px 14px;
    cursor: pointer;
    position: relative;
    border-left: 2.5px solid transparent;
    transition: background .14s, border-color .14s;
}

.gu-row::after {
    content: '';
    position: absolute; bottom: 0; left: 14px; right: 14px;
    height: 1px; background: var(--s-border);
}

.gu-row:last-child::after { display: none; }
.gu-row:hover { background: var(--s-hover); border-left-color: #c9ced6; }
.gu-row.is-active { background: var(--s-active); border-left-color: var(--s-accent); }
.gu-row.is-active .gu-row-name { color: #14532d; font-weight: 600; }

.gu-av {
    width: 35px; height: 35px;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 12px; font-weight: 700;
    font-family: var(--font);
    flex-shrink: 0; letter-spacing: -.3px;
}

.gu-row-info { flex: 1; min-width: 0; }

.gu-row-name {
    font-size: 13px; font-weight: 500;
    color: var(--s-bright);
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    line-height: 1.3;
}

.gu-row-email {
    font-size: 11px; color: var(--s-dim);
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    margin-top: 1px;
}

.gu-list-empty {
    padding: 40px 16px; text-align: center;
    color: var(--s-dim); font-size: 12px;
}

.gu-list-empty i { font-size: 18px; margin-bottom: 9px; display: block; }

/* ── right panel ───────────────────────────────── */
.gu-right {
    flex: 1;
    background: var(--f-bg);
    display: flex;
    flex-direction: column;
    overflow-y: auto;
}

/* mobile back btn */
.gu-back-btn {
    display: none;
    align-items: center; gap: 7px;
    padding: 14px 20px 0;
    background: none; border: none;
    font-size: 13px; font-weight: 500;
    color: var(--f-text2);
    font-family: var(--font);
    cursor: pointer;
    transition: color .14s;
}

.gu-back-btn:hover { color: var(--f-text); }
.gu-back-btn i { font-size: 11px; }

/* empty state */
.gu-empty {
    flex: 1;
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    text-align: center; padding: 40px;
    min-height: 460px;
}

.gu-empty-icon {
    width: 76px; height: 76px;
    border-radius: 20px;
    background: var(--f-bg2);
    border: 1.5px dashed var(--f-border);
    display: flex; align-items: center; justify-content: center;
    font-size: 28px; color: var(--f-text3);
    margin-bottom: 14px;
}

.gu-empty-title { font-size: 15px; font-weight: 600; color: var(--f-text); margin: 0; }
.gu-empty-sub   { font-size: 12.5px; color: var(--f-text3); margin: 7px 0 0; line-height: 1.55; }

/* form */
.gu-form { padding: 24px 28px 32px; }

/* form head */
.gu-fhead {
    display: flex; align-items: center;
    gap: 14px;
    padding-bottom: 18px;
    margin-bottom: 4px;
}

.gu-fhead-av {
    width: 48px; height: 48px;
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 15px; font-weight: 700;
    font-family: var(--font); flex-shrink: 0;
}

.gu-fhead-info { flex: 1; min-width: 0; }
.gu-fhead-name { font-size: 16px; font-weight: 700; color: var(--f-text); margin: 0; line-height: 1.2; }
.gu-fhead-meta { font-size: 12px; color: var(--f-text3); margin-top: 3px; }

/* section headers — mesmo padrão de familias/edit.blade.php */
.gu-sec-title {
    padding-bottom: 8px;
    margin: 22px 0 14px;
    border-bottom: 1px solid var(--f-border);
}

/* fields */
.gu-field label {
    font-size: 12.5px; font-weight: 600;
    color: var(--f-text2);
    display: block; margin-bottom: 6px;
}

.gu-field-wrap { position: relative; }

.gu-field-ico {
    position: absolute; right: 12px; top: 50%;
    transform: translateY(-50%); pointer-events: none;
    color: var(--f-text3); font-size: 12px;
    transition: color .15s;
}

.gu-input {
    width: 100%;
    background: var(--f-bg2);
    border: 1.5px solid var(--f-border);
    border-radius: var(--r-sm);
    padding: 10px 36px 10px 13px;
    font-size: 13.5px; color: var(--f-text);
    font-family: var(--font);
    outline: none;
    transition: border-color .18s, background .18s, box-shadow .18s;
    -webkit-appearance: none; appearance: none;
}

.gu-input:focus {
    border-color: var(--s-accent);
    background: #fff;
    box-shadow: 0 0 0 3.5px var(--s-accent-glow);
}

.gu-input:focus + .gu-field-ico { color: var(--s-accent); }
.gu-input::placeholder { color: #c3cad2; }

.gu-hint { font-size: 11.5px; color: var(--f-text3); margin-top: 5px; }

/* grid */
.gu-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }

/* buttons */
.gu-actions { display: flex; align-items: center; gap: 10px; margin-top: 26px; }

/* ── responsive ────────────────────────────────── */
@media (max-width: 768px) {
    .gu-shell { flex-direction: column; }

    .gu-left {
        width: 100%;
        min-width: 0;
        border-right: none;
        border-bottom: 1px solid var(--s-border);
    }

    .gu-list { max-height: none; }

    .gu-left.mob-hidden  { display: none; }
    .gu-right.mob-hidden { display: none; }
    .gu-back-btn         { display: flex; }

    .gu-right { min-height: 500px; }
    .gu-form  { padding: 18px 20px 28px; }

    .gu-grid { grid-template-columns: 1fr; gap: 12px; }
}

@media (max-width: 480px) {
    .gu-form { padding: 14px 16px 24px; }
}
</style>
@stop

@section('content')
<div class="card mb-0" x-data="gerenciarUsuarios()" x-cloak>
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-users mr-1"></i> Usuários do Sistema</h3>
        <div class="card-tools">
            <span class="badge badge-success" x-text="filteredUsers.length + ' usuário(s)'"></span>
        </div>
    </div>

    <div class="card-body p-0">
    <div class="gu-shell">

        {{-- ── LEFT ────────────────────────────────────── --}}
        <div class="gu-left" :class="{ 'mob-hidden': mobileView === 'detail' }">

            <div class="gu-search-area">
                <div class="gu-search-box">
                    <span class="gu-search-ico"><i class="fas fa-search"></i></span>
                    <input
                        type="text"
                        class="gu-search-input"
                        placeholder="Buscar nome ou email…"
                        x-model="search"
                        autocomplete="off"
                    >
                    <button
                        class="gu-search-clear"
                        x-show="search.length > 0"
                        @click="search = ''"
                        title="Limpar"
                    ><i class="fas fa-times"></i></button>
                </div>
            </div>

            <div class="gu-list">
                <template x-for="u in filteredUsers" :key="u.id">
                    <div
                        class="gu-row"
                        :class="{ 'is-active': selected && selected.id === u.id }"
                        @click="selectUser(u)"
                    >
                        <div class="gu-av" :style="avatarStyle(u.name)" x-text="initials(u.name)"></div>
                        <div class="gu-row-info">
                            <div class="gu-row-name" x-text="u.name"></div>
                            <div class="gu-row-email" x-text="u.email"></div>
                        </div>
                        <span
                            class="badge text-uppercase"
                            :class="chipClass(u.roles)"
                            x-text="u.roles && u.roles.length ? u.roles[0] : '—'"
                        ></span>
                    </div>
                </template>
                <div x-show="filteredUsers.length === 0" class="gu-list-empty">
                    <i class="fas fa-search"></i>
                    Nenhum resultado.
                </div>
            </div>
        </div>

        {{-- ── RIGHT ───────────────────────────────────── --}}
        <div class="gu-right" :class="{ 'mob-hidden': mobileView === 'list' }">

            {{-- mobile back --}}
            <button
                class="gu-back-btn"
                x-show="mobileView === 'detail'"
                @click="selected = null; mobileView = 'list'"
            >
                <i class="fas fa-arrow-left"></i> Voltar à lista
            </button>

            {{-- empty state --}}
            <div class="gu-empty" x-show="!selected">
                <div class="gu-empty-icon"><i class="fas fa-user-circle"></i></div>
                <p class="gu-empty-title">Nenhum usuário selecionado</p>
                <p class="gu-empty-sub">Selecione um usuário na lista ao lado<br>para visualizar e editar seus dados.</p>
            </div>

            {{-- form --}}
            <div class="gu-form" x-show="selected" x-transition.opacity.duration.150ms>

                <div class="gu-fhead">
                    <div class="gu-fhead-av" :style="selected ? avatarStyle(selected.name) : ''">
                        <span x-text="selected ? initials(selected.name) : ''"></span>
                    </div>
                    <div class="gu-fhead-info">
                        <p class="gu-fhead-name" x-text="selected ? selected.name : ''"></p>
                        <div class="gu-fhead-meta" x-text="selected ? (selected.parceiro || '— sem parceiro —') : ''"></div>
                    </div>
                </div>

                <h4 class="text-bold gu-sec-title">1. Dados básicos</h4>
                <div class="gu-grid">
                    <div class="gu-field">
                        <label>Nome completo</label>
                        <div class="gu-field-wrap">
                            <input type="text" class="gu-input" x-model="form.name" placeholder="Nome completo">
                            <span class="gu-field-ico"><i class="fas fa-user"></i></span>
                        </div>
                    </div>
                    <div class="gu-field">
                        <label>Endereço de email</label>
                        <div class="gu-field-wrap">
                            <input type="email" class="gu-input" x-model="form.email" placeholder="email@exemplo.com">
                            <span class="gu-field-ico"><i class="fas fa-envelope"></i></span>
                        </div>
                    </div>
                </div>

                <h4 class="text-bold gu-sec-title">2. Acesso e segurança</h4>
                <div class="gu-grid">
                    <div class="gu-field">
                        <label>Permissão</label>
                        <div class="gu-field-wrap">
                            <select class="gu-input" x-model="form.role" style="cursor:pointer;">
                                <option value="">— Sem permissão —</option>
                                <template x-for="r in roles" :key="r">
                                    <option :value="r" x-text="r"></option>
                                </template>
                            </select>
                            <span class="gu-field-ico"><i class="fas fa-shield-alt"></i></span>
                        </div>
                    </div>
                    <div class="gu-field">
                        <label>Nova senha</label>
                        <div class="gu-field-wrap">
                            <input type="password" class="gu-input" x-model="form.password" placeholder="••••••••" autocomplete="new-password">
                            <span class="gu-field-ico"><i class="fas fa-lock"></i></span>
                        </div>
                        <div class="gu-hint"><i class="fas fa-info-circle" style="margin-right:3px;opacity:.7;"></i>Deixe em branco para não alterar</div>
                    </div>
                </div>

                <div class="gu-actions">
                    <button class="btn btn-success" :disabled="saving" @click="save()">
                        <template x-if="!saving">
                            <span><i class="fas fa-check mr-1"></i>Salvar alterações</span>
                        </template>
                        <template x-if="saving">
                            <span><i class="fas fa-spinner fa-spin mr-1"></i>Salvando…</span>
                        </template>
                    </button>
                    <button class="btn btn-outline-secondary" :disabled="saving" @click="selected = null; mobileView = 'list'">
                        Cancelar
                    </button>
                </div>

            </div>
        </div>

    </div>
    </div>
</div>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/axios@1.6.0/dist/axios.min.js"></script>
<script>
const _csrf = document.querySelector('meta[name="csrf-token"]');
if (_csrf) axios.defaults.headers.common['X-CSRF-TOKEN'] = _csrf.content;
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

const _usersBaseUrl = @json(url('/users'));

const _PAL = [
    ['#dbeafe','#1d4ed8'],['#dcfce7','#15803d'],['#fef3c7','#b45309'],
    ['#fce7f3','#be185d'],['#ede9fe','#6d28d9'],['#ffedd5','#c2410c'],
    ['#e0f2fe','#0369a1'],['#f0fdf4','#166534'],['#fff7ed','#9a3412'],
    ['#f5f3ff','#5b21b6'],
];

function _avatarColor(name) {
    let h = 0;
    for (let i = 0; i < (name || '').length; i++) h = (name.charCodeAt(i) + ((h << 5) - h)) | 0;
    return _PAL[Math.abs(h) % _PAL.length];
}

function gerenciarUsuarios() {
    return {
        users:      {!! json_encode($users) !!},
        roles:      {!! json_encode($roles) !!},
        search:     '',
        selected:   null,
        mobileView: 'list',
        form:       { name: '', email: '', role: '', password: '' },
        saving:     false,

        get filteredUsers() {
            const q = this.search.toLowerCase().trim();
            if (!q) return this.users;
            return this.users.filter(u =>
                u.name.toLowerCase().includes(q) ||
                u.email.toLowerCase().includes(q)
            );
        },

        initials(name) {
            if (!name) return '?';
            const p = name.trim().split(/\s+/).filter(Boolean);
            return p.length === 1
                ? p[0][0].toUpperCase()
                : (p[0][0] + p[p.length - 1][0]).toUpperCase();
        },

        avatarStyle(name) {
            const [bg, fg] = _avatarColor(name);
            return `background:${bg};color:${fg};`;
        },

        chipClass(roles) {
            if (!roles || !roles.length) return 'badge-secondary';
            const r = roles[0];
            if (r === 'Administrador') return 'badge-success';
            if (r === 'Coordenador')   return 'badge-primary';
            if (r === 'Secretario')    return 'badge-warning';
            return 'badge-secondary';
        },

        selectUser(u) {
            this.selected      = u;
            this.form.name     = u.name;
            this.form.email    = u.email;
            this.form.role     = u.roles && u.roles.length ? u.roles[0] : '';
            this.form.password = '';
            this.mobileView    = 'detail';
        },

        async save() {
            this.saving = true;
            try {
                await axios.put(`${_usersBaseUrl}/${this.selected.id}`, {
                    name:     this.form.name,
                    email:    this.form.email,
                    roles:    this.form.role ? [this.form.role] : [],
                    password: this.form.password || undefined,
                });
                const u = this.users.find(u => u.id === this.selected.id);
                if (u) {
                    u.name  = this.form.name;
                    u.email = this.form.email;
                    u.roles = this.form.role ? [this.form.role] : [];
                }
                this.selected.name  = this.form.name;
                this.selected.email = this.form.email;
                this.selected.roles = this.form.role ? [this.form.role] : [];
                this.showToast('success', 'Usuário atualizado!');
            } catch (e) {
                this.showToast('error', e.response?.data?.message ?? 'Erro ao salvar.');
            } finally {
                this.saving = false;
            }
        },

        showToast(type, message) {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: type === 'success' ? 'success' : 'error',
                title: message,
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });
        },
    };
}
</script>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>
@stop
