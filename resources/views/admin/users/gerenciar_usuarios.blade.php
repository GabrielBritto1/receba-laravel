@extends('adminlte::page')

@section('title', 'Gerenciar Usuários')

@section('content_header')
<h1 class="text-bold"><i class="fas fa-id-badge"></i> Gerenciar Usuários</h1>
@stop

@section('css')
<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600&family=IBM+Plex+Mono:wght@500&display=swap" rel="stylesheet">
<style>
[x-cloak] { display: none !important; }

.gu-wrap {
    font-family: 'IBM Plex Sans', sans-serif;
    border: 1px solid #dde4ee;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 1px 4px rgba(0,0,0,0.05), 0 4px 16px rgba(0,0,0,0.05);
}

/* ── LEFT PANEL ─────────────────────────────── */
.gu-left {
    width: 290px;
    min-width: 290px;
    background: #f0f4fb;
    border-right: 1px solid #dde4ee;
    display: flex;
    flex-direction: column;
    min-height: 560px;
}

.gu-search-wrap {
    padding: 14px 14px 12px;
    border-bottom: 1px solid #dde4ee;
}
.gu-search-box { position: relative; }
.gu-search-icon-wrap {
    position: absolute; left: 10px; top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    font-size: 11px; pointer-events: none;
}
.gu-search-input {
    width: 100%;
    background: #fff;
    border: 1px solid #d1d9e6;
    border-radius: 8px;
    padding: 8px 12px 8px 32px;
    font-size: 12.5px;
    color: #1e293b;
    font-family: 'IBM Plex Sans', sans-serif;
    outline: none;
    transition: border-color .2s, box-shadow .2s;
}
.gu-search-input::placeholder { color: #b0bccf; }
.gu-search-input:focus {
    border-color: #6382ff;
    box-shadow: 0 0 0 3px rgba(99,130,255,.1);
}

.gu-list { flex: 1; overflow-y: auto; }
.gu-list::-webkit-scrollbar { width: 3px; }
.gu-list::-webkit-scrollbar-thumb { background: #c8d3e4; border-radius: 2px; }

.gu-user-row {
    display: flex; align-items: center; gap: 10px;
    padding: 10px 14px;
    cursor: pointer;
    border-left: 3px solid transparent;
    border-bottom: 1px solid #e8edf5;
    transition: background .13s, border-color .13s;
}
.gu-user-row:hover { background: #e6ecf7; }
.gu-user-row.is-active {
    background: #dce7ff;
    border-left-color: #4f6ef7;
}

.gu-avatar {
    width: 32px; height: 32px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 11px; font-weight: 600; flex-shrink: 0;
    font-family: 'IBM Plex Mono', monospace;
    letter-spacing: -.5px;
}
.gu-user-name {
    font-size: 13px; font-weight: 500; color: #1e293b;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.gu-user-email {
    font-size: 11px; color: #64748b;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-top: 1px;
}
.gu-info { flex: 1; min-width: 0; }

.gu-badge {
    font-size: 10px; font-weight: 600; padding: 2px 8px;
    border-radius: 20px; flex-shrink: 0; letter-spacing: .2px;
}
.gu-badge-admin { background: #d1fae5; color: #065f46; }
.gu-badge-coord { background: #dbeafe; color: #1e40af; }
.gu-badge-secr  { background: #fef3c7; color: #92400e; }
.gu-badge-none  { background: #f1f5f9; color: #94a3b8; }

.gu-list-empty {
    padding: 28px 16px; text-align: center;
    color: #94a3b8; font-size: 12px;
}
.gu-footer {
    padding: 9px 14px;
    border-top: 1px solid #dde4ee;
    font-size: 10.5px; color: #94a3b8;
    font-family: 'IBM Plex Mono', monospace;
    letter-spacing: .3px;
}

/* ── RIGHT PANEL ────────────────────────────── */
.gu-right {
    flex: 1; padding: 26px 30px; overflow-y: auto;
    background: #fff; min-height: 560px;
}

.gu-empty {
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    height: 100%; min-height: 420px; text-align: center;
}
.gu-empty-icon {
    width: 52px; height: 52px; border-radius: 14px;
    background: #f1f5f9; display: flex; align-items: center;
    justify-content: center; font-size: 20px; color: #bfc8d6;
    margin-bottom: 14px;
}
.gu-empty-ttl { font-size: 14px; font-weight: 500; color: #64748b; margin: 0 0 4px; }
.gu-empty-sub { font-size: 12px; color: #94a3b8; }

/* Form header */
.gu-fhead {
    display: flex; justify-content: space-between;
    align-items: flex-start; padding-bottom: 18px;
    margin-bottom: 20px; border-bottom: 1.5px solid #eceff4;
}
.gu-fname { font-size: 17px; font-weight: 600; color: #1a2035; margin: 0; }
.gu-fmeta { font-size: 11px; color: #94a3b8; margin-top: 3px; font-family: 'IBM Plex Mono', monospace; }

/* Toast */
.gu-toast {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 7px 13px; border-radius: 8px;
    font-size: 12px; font-weight: 500;
}
.gu-toast-ok  { background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; }
.gu-toast-err { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }

/* Section title */
.gu-stitle {
    font-size: 10px; font-weight: 600; text-transform: uppercase;
    letter-spacing: .8px; color: #94a3b8; margin: 18px 0 11px;
}

/* Form fields */
.gu-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
.gu-field label {
    font-size: 11px; font-weight: 600; text-transform: uppercase;
    letter-spacing: .5px; color: #64748b; display: block; margin-bottom: 5px;
}
.gu-field-inner { position: relative; }
.gu-field-icon {
    position: absolute; right: 11px; top: 50%;
    transform: translateY(-50%); color: #cbd5e1;
    font-size: 11px; pointer-events: none;
}
.gu-input {
    width: 100%; background: #fff;
    border: 1.5px solid #e4e8f0; border-radius: 8px;
    padding: 9px 32px 9px 12px;
    font-size: 13px; color: #1e293b;
    font-family: 'IBM Plex Sans', sans-serif;
    outline: none; transition: border-color .18s, box-shadow .18s;
    -webkit-appearance: none; appearance: none;
}
.gu-input:focus {
    border-color: #6382ff;
    box-shadow: 0 0 0 3px rgba(99,130,255,.12);
}
.gu-input::placeholder { color: #c8d0dc; }
.gu-hint { font-size: 11px; color: #94a3b8; margin-top: 4px; }

/* Divider */
.gu-div { height: 1px; background: #eceff4; margin: 22px 0; }

/* Buttons */
.gu-btn-primary {
    background: #4f6ef7; color: #fff; border: none;
    border-radius: 8px; padding: 10px 20px;
    font-size: 13px; font-weight: 500;
    cursor: pointer; display: inline-flex; align-items: center; gap: 7px;
    font-family: 'IBM Plex Sans', sans-serif;
    transition: background .15s, transform .1s;
}
.gu-btn-primary:hover:not(:disabled) { background: #3b5bdb; }
.gu-btn-primary:active:not(:disabled) { transform: scale(.98); }
.gu-btn-primary:disabled { opacity: .5; cursor: not-allowed; }

.gu-btn-ghost {
    background: transparent; color: #64748b;
    border: 1.5px solid #dde3ed; border-radius: 8px;
    padding: 10px 16px; font-size: 13px; font-weight: 500;
    cursor: pointer; font-family: 'IBM Plex Sans', sans-serif;
    transition: border-color .15s, color .15s;
}
.gu-btn-ghost:hover:not(:disabled) { border-color: #94a3b8; color: #374151; }
.gu-btn-ghost:disabled { opacity: .5; cursor: not-allowed; }
</style>
@stop

@section('content')
<div class="card gu-wrap" x-data="gerenciarUsuarios()" x-cloak>
    <div style="display:flex;">

        {{-- ── LEFT ─────────────────────────────────────── --}}
        <div class="gu-left">

            <div class="gu-search-wrap">
                <div class="gu-search-box">
                    <span class="gu-search-icon-wrap"><i class="fas fa-search"></i></span>
                    <input type="text" class="gu-search-input" placeholder="Buscar usuário..." x-model="search">
                </div>
            </div>

            <div class="gu-list">
                <template x-for="u in filteredUsers" :key="u.id">
                    <div class="gu-user-row" :class="{ 'is-active': selected && selected.id === u.id }" @click="selectUser(u)">
                        <div class="gu-avatar" :style="avatarStyle(u.name)" x-text="initials(u.name)"></div>
                        <div class="gu-info">
                            <div class="gu-user-name" x-text="u.name"></div>
                            <div class="gu-user-email" x-text="u.email"></div>
                        </div>
                        <span class="gu-badge" :class="badgeClass(u.roles)" x-text="u.roles && u.roles.length ? u.roles[0] : '—'"></span>
                    </div>
                </template>
                <div x-show="filteredUsers.length === 0" class="gu-list-empty">
                    Nenhum resultado.
                </div>
            </div>

            <div class="gu-footer">
                <span x-text="filteredUsers.length"></span> usuário(s)
            </div>
        </div>

        {{-- ── RIGHT ────────────────────────────────────── --}}
        <div class="gu-right">

            {{-- Estado vazio --}}
            <div class="gu-empty" x-show="!selected">
                <div class="gu-empty-icon"><i class="fas fa-users"></i></div>
                <p class="gu-empty-ttl">Nenhum usuário selecionado</p>
                <p class="gu-empty-sub">Escolha um usuário na lista ao lado</p>
            </div>

            {{-- Formulário --}}
            <div x-show="selected">

                <div class="gu-fhead">
                    <div>
                        <p class="gu-fname" x-text="selected ? selected.name : ''"></p>
                        <div class="gu-fmeta" x-text="selected ? 'ID #' + selected.id : ''"></div>
                    </div>
                    <div x-show="toast.show" x-transition class="gu-toast" :class="toast.type === 'success' ? 'gu-toast-ok' : 'gu-toast-err'">
                        <i :class="toast.type === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle'"></i>
                        <span x-text="toast.message"></span>
                    </div>
                </div>

                <div class="gu-stitle">Dados básicos</div>
                <div class="gu-grid">
                    <div class="gu-field">
                        <label>Nome</label>
                        <div class="gu-field-inner">
                            <input type="text" class="gu-input" x-model="form.name" placeholder="Nome completo">
                            <span class="gu-field-icon"><i class="fas fa-user"></i></span>
                        </div>
                    </div>
                    <div class="gu-field">
                        <label>Email</label>
                        <div class="gu-field-inner">
                            <input type="email" class="gu-input" x-model="form.email" placeholder="email@exemplo.com">
                            <span class="gu-field-icon"><i class="fas fa-envelope"></i></span>
                        </div>
                    </div>
                </div>

                <div class="gu-stitle">Acesso</div>
                <div class="gu-grid">
                    <div class="gu-field">
                        <label>Role</label>
                        <div class="gu-field-inner">
                            <select class="gu-input" x-model="form.role" style="cursor:pointer;">
                                <option value="">— Sem role —</option>
                                <template x-for="r in roles" :key="r">
                                    <option :value="r" x-text="r"></option>
                                </template>
                            </select>
                            <span class="gu-field-icon"><i class="fas fa-shield-alt"></i></span>
                        </div>
                    </div>
                    <div class="gu-field">
                        <label>Nova senha</label>
                        <div class="gu-field-inner">
                            <input type="password" class="gu-input" x-model="form.password" placeholder="••••••••" autocomplete="new-password">
                            <span class="gu-field-icon"><i class="fas fa-lock"></i></span>
                        </div>
                        <div class="gu-hint">Deixe em branco para não alterar</div>
                    </div>
                </div>

                <div class="gu-div"></div>

                <div style="display:flex; gap:10px; align-items:center;">
                    <button class="gu-btn-primary" :disabled="saving" @click="save()">
                        <span x-show="!saving"><i class="fas fa-save" style="margin-right:4px;"></i> Salvar alterações</span>
                        <span x-show="saving"><i class="fas fa-spinner fa-spin" style="margin-right:4px;"></i> Salvando...</span>
                    </button>
                    <button class="gu-btn-ghost" :disabled="saving" @click="selected = null">
                        <i class="fas fa-times" style="margin-right:4px;"></i> Fechar
                    </button>
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

const _PALETTE = [
    ['#dbeafe','#2563eb'],['#dcfce7','#16a34a'],['#fef3c7','#d97706'],
    ['#fce7f3','#db2777'],['#ede9fe','#7c3aed'],['#ffedd5','#ea580c'],
    ['#e0f2fe','#0284c7'],['#f0fdf4','#15803d'],
];

function _avatarColor(name) {
    let h = 0;
    for (let i = 0; i < (name||'').length; i++) h = (name.charCodeAt(i) + ((h << 5) - h)) | 0;
    return _PALETTE[Math.abs(h) % _PALETTE.length];
}

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

        badgeClass(roles) {
            if (!roles || !roles.length) return 'gu-badge-none';
            const r = roles[0];
            if (r === 'Administrador') return 'gu-badge-admin';
            if (r === 'Coordenador')   return 'gu-badge-coord';
            if (r === 'Secretario')    return 'gu-badge-secr';
            return 'gu-badge-none';
        },

        selectUser(u) {
            this.selected      = u;
            this.form.name     = u.name;
            this.form.email    = u.email;
            this.form.role     = u.roles && u.roles.length ? u.roles[0] : '';
            this.form.password = '';
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
                const u = this.users.find(u => u.id === this.selected.id);
                if (u) {
                    u.name  = this.form.name;
                    u.email = this.form.email;
                    u.roles = this.form.role ? [this.form.role] : [];
                }
                this.selected.name  = this.form.name;
                this.selected.email = this.form.email;
                this.selected.roles = this.form.role ? [this.form.role] : [];
                this.showToast('success', 'Usuário atualizado com sucesso!');
            } catch (e) {
                this.showToast('error', e.response?.data?.message ?? 'Erro ao salvar.');
            } finally {
                this.saving = false;
            }
        },

        showToast(type, message) {
            this.toast = { show: true, type, message };
            setTimeout(() => { this.toast.show = false; }, 3000);
        },
    };
}
</script>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>
@stop
