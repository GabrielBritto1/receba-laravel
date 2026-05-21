@extends('adminlte::page')

@section('title', 'Painel Administrativo')

@section('content_header')
   <h1 class="text-bold"><i class="fas fa-shield-alt"></i> Painel Administrativo</h1>
@stop

@section('content')

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">
   <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
   <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
</div>
@endif

<div class="card card-primary card-tabs">
   <div class="card-header p-0 pt-1 border-bottom-0">
      <ul class="nav nav-tabs" id="adminTabs" role="tablist">
         <li class="nav-item">
            <a class="nav-link active" id="tab-permissoes-link" data-toggle="pill" href="#tab-permissoes" role="tab">
               <i class="fas fa-key mr-1"></i> Controle de Permissões
            </a>
         </li>
         <li class="nav-item">
            <a class="nav-link" id="tab-log-link" data-toggle="pill" href="#tab-log" role="tab">
               <i class="fas fa-history mr-1"></i> Log de Atividades
            </a>
         </li>
      </ul>
   </div>

   <div class="card-body">
      <div class="tab-content" id="adminTabsContent">

         {{-- ── Tab: Permissões ─────────────────────────────────────────── --}}
         <div class="tab-pane fade show active" id="tab-permissoes" role="tabpanel">
            @php
               $permissionsGrouped = $permissions->groupBy(fn($p) => explode('.', $p->name)[0]);
               $roleColors = ['#007bff', '#28a745', '#fd7e14', '#6f42c1', '#dc3545', '#17a2b8'];
            @endphp

            <form method="POST" action="{{ route('roles.permissions.update') }}" id="form-permissions">
               @csrf
               <div class="table-responsive">
                  <table class="table table-sm table-bordered mb-0" id="permissions-table">
                     <thead class="thead-light">
                        <tr>
                           <th style="min-width:220px; vertical-align:middle;">
                              <small class="text-muted text-uppercase font-weight-bold">Permissão</small>
                           </th>
                           @foreach($roles as $i => $role)
                           <th class="text-center" style="min-width:130px;">
                              <span class="badge badge-pill text-white px-3 py-2"
                                 style="background-color: {{ $roleColors[$i % count($roleColors)] }}; font-size:.8rem;">
                                 {{ $role->name }}
                              </span>
                           </th>
                           @endforeach
                        </tr>
                     </thead>
                     <tbody>
                        @foreach($permissionsGrouped as $group => $groupPermissions)
                        <tr class="table-secondary">
                           <td colspan="{{ $roles->count() + 1 }}" class="py-1 px-3">
                              <small class="text-uppercase font-weight-bold text-muted">
                                 <i class="fas fa-tag mr-1"></i>
                                 {{ ucfirst(str_replace(['.', '_'], ' ', $group)) }}
                              </small>
                           </td>
                        </tr>
                        @foreach($groupPermissions as $permission)
                        <tr>
                           <td class="align-middle pl-4" style="font-size:.85rem;">
                              {{ ucwords(str_replace(['.', '_'], ' ', str_replace($group.'.', '', $permission->name))) }}
                              <small class="text-muted d-block" style="font-size:.72rem;">{{ $permission->name }}</small>
                           </td>
                           @foreach($roles as $i => $role)
                           <td class="text-center align-middle">
                              <div class="custom-control custom-switch d-flex justify-content-center">
                                 <input
                                    type="checkbox"
                                    class="custom-control-input"
                                    id="perm-{{ $role->id }}-{{ $permission->id }}"
                                    name="roles[{{ $role->id }}][permissions][]"
                                    value="{{ $permission->id }}"
                                    @if($role->permissions->contains($permission)) checked @endif
                                 >
                                 <label class="custom-control-label" for="perm-{{ $role->id }}-{{ $permission->id }}"></label>
                              </div>
                           </td>
                           @endforeach
                        </tr>
                        @endforeach
                        @endforeach
                     </tbody>
                  </table>
               </div>

               <div class="d-flex justify-content-end mt-3">
                  <button type="submit" class="btn btn-success px-4">
                     <i class="fas fa-save mr-1"></i> Salvar Alterações
                  </button>
               </div>
            </form>
         </div>

         {{-- ── Tab: Log de Atividades ──────────────────────────────────── --}}
         <div class="tab-pane fade" id="tab-log" role="tabpanel">

            {{-- Filtros --}}
            <div class="row mb-3">
               <div class="col-md-4">
                  <input type="text" class="form-control form-control-sm" id="filter-user"
                     placeholder="Filtrar por usuário...">
               </div>
               <div class="col-md-3">
                  <select class="form-control form-control-sm" id="filter-action">
                     <option value="">Todas as ações</option>
                     <option value="criado">Criado</option>
                     <option value="atualizado">Atualizado</option>
                     <option value="removido">Removido</option>
                     <option value="solicitado">Solicitado</option>
                  </select>
               </div>
               <div class="col-md-2">
                  <button class="btn btn-sm btn-primary" id="btn-filter-log">
                     <i class="fas fa-search mr-1"></i> Filtrar
                  </button>
               </div>
               <div class="col-md-3 text-right">
                  <small class="text-muted" id="log-total"></small>
               </div>
            </div>

            <div class="table-responsive">
               <table class="table table-sm table-hover table-striped">
                  <thead class="thead-light">
                     <tr>
                        <th style="width:120px;">Ação</th>
                        <th>Descrição</th>
                        <th>Usuário</th>
                        <th>URL / Origem</th>
                        <th style="width:90px;">IP</th>
                        <th style="width:140px;">Data/Hora</th>
                     </tr>
                  </thead>
                  <tbody id="log-table-body">
                     <tr>
                        <td colspan="6" class="text-center py-4">
                           <i class="fa fa-spinner fa-pulse fa-2x fa-fw text-muted"></i>
                        </td>
                     </tr>
                  </tbody>
               </table>
            </div>

            <div id="log-pagination" class="mt-2 text-center"></div>
         </div>

      </div>
   </div>
</div>

@stop

@section('js')
<script>
   @if (session('success'))
   Swal.fire({ icon: 'success', title: 'Sucesso', text: "{{ session('success') }}", timer: 3000, showConfirmButton: false });
   @endif

   // ── Log de Atividades ──────────────────────────────────────────────────────
   function actionBadge(action) {
      const map = {
         'criado':     ['success', 'plus-circle',  'Criado'],
         'atualizado': ['info',    'edit',          'Atualizado'],
         'removido':   ['danger',  'trash',         'Removido'],
         'solicitado': ['warning', 'shopping-bag',  'Solicitado'],
      };
      const [color, icon, label] = map[action] ?? ['secondary', 'circle', action];
      return `<span class="badge badge-${color}"><i class="fas fa-${icon} mr-1"></i>${label}</span>`;
   }

   function truncateUrl(url) {
      if (!url) return '—';
      try {
         const path = new URL(url).pathname;
         return `<span title="${url}" style="cursor:help;">${path}</span>`;
      } catch { return url; }
   }

   function loadLog(page = 1) {
      const user   = $('#filter-user').val();
      const action = $('#filter-action').val();
      $('#log-table-body').html('<tr><td colspan="6" class="text-center py-4"><i class="fa fa-spinner fa-pulse fa-2x fa-fw text-muted"></i></td></tr>');
      $('#log-pagination').html('');

      $.get('{{ route("admin.activity_log.list") }}', { page, user, action }, function(data) {
         if (data.status !== 'success') return;
         const logs = data.logs;
         const p    = data.pagination;

         $('#log-total').text(`Total: ${p.total} registros`);

         if (logs.length === 0) {
            $('#log-table-body').html('<tr><td colspan="6" class="text-center text-muted py-4">Nenhuma atividade encontrada.</td></tr>');
            return;
         }

         $('#log-table-body').html(logs.map(log => `
            <tr>
               <td class="align-middle">${actionBadge(log.action)}</td>
               <td class="align-middle" style="font-size:.85rem;">${log.description}</td>
               <td class="align-middle">
                  <i class="fas fa-user-circle text-muted mr-1"></i>
                  <small>${log.user_name}</small>
               </td>
               <td class="align-middle" style="font-size:.78rem; color:#666;">${truncateUrl(log.url)}</td>
               <td class="align-middle"><small class="text-muted">${log.ip_address}</small></td>
               <td class="align-middle"><small>${log.created_at}</small></td>
            </tr>
         `).join(''));

         renderPagination(p.current_page, p.last_page, 'log-pagination', 'loadLog');
      });
   }

   // Carrega o log quando a aba for aberta
   $('#tab-log-link').one('shown.bs.tab', function() { loadLog(); });

   $('#btn-filter-log').on('click', function() { loadLog(1); });

   $('#filter-user').on('keypress', function(e) {
      if (e.key === 'Enter') loadLog(1);
   });
</script>
@stop
