@extends('adminlte::page')
@section('title', 'Registrar Entrega - RECeBa')
@section('content_header')
<h1 class="text-bold"><i class="fas fa-calendar-plus"></i> Registrar Entrega</h1>
@stop

@section('css')
<style>
   .select2-container--default .select2-selection--single {
      height: calc(1.5em + .75rem + 2px);
      border: 1px solid #ced4da;
      border-radius: .25rem;
      display: flex;
      align-items: center;
   }
   .select2-container--default .select2-selection--single .select2-selection__rendered {
      line-height: 1.5;
      padding: 0 2rem 0 .75rem;
      color: #495057;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      width: 100%;
   }
   .select2-container--default .select2-selection--single .select2-selection__placeholder {
      color: #6c757d;
   }
   .select2-container--default .select2-selection--single .select2-selection__arrow {
      height: 100%;
      top: 0;
   }
   .select2-container--default.select2-container--focus .select2-selection--single,
   .select2-container--default.select2-container--open .select2-selection--single {
      border-color: #80bdff;
      box-shadow: 0 0 0 .2rem rgba(0,123,255,.25);
      outline: 0;
   }
   .select2-container--default .select2-results__option--highlighted[aria-selected] {
      background-color: #007bff;
   }
   .select2-results__option {
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
   }
</style>
@endsection

@section('content')
@can('Administrador')
<div class="card">
   <div class="card-header">
      <h3 class="card-title"><i class="fas fa-filter mr-1"></i> Filtros</h3>
   </div>
   <div class="card-body">
      <div class="row">
         <div class="col-md-5">
            <div class="form-group">
               <label>Parceiro:</label>
               <select id="filtro-parceiro-cesta" class="form-control form-control-sm">
                  <option value="">Todos</option>
                  @foreach($parceiros as $p)
                  <option value="{{ $p->id }}">{{ $p->name }}</option>
                  @endforeach
               </select>
            </div>
         </div>
         <div class="col-md-2 d-flex align-items-end">
            <div class="form-group w-100">
               <button id="btn-filtrar-cesta" class="btn btn-success btn-sm btn-block">
                  <i class="fas fa-search"></i> Filtrar
               </button>
            </div>
         </div>
      </div>
   </div>
</div>
@endcan

<section class="cestas_que_nao_sairam">
   <div class="card">
      <div class="card-header">
         <span class="text-muted text-uppercase">Cestas que ainda não sairam</span>
         <div class="card-tools">
            @if ($parceiro && $parceiro->status != 0)
            <a href="#" class="btn btn-success btn-sm text-bold" data-toggle="modal" data-target="#modalEntregarCesta">
               <i class="fas fa-plus"></i> Registrar Entrega Própria
            </a>
            @else
            <button class="btn btn-secondary btn-sm text-bold" disabled title="Você não está vinculado a nenhum parceiro">
               <i class="fas fa-plus"></i> Registrar Entrega Própria
            </button>
            @endif
         </div>
      </div>
      <div class="card-body pt-1">
         <div class="row">
            <div class="card-body table-responsive p-0">
               <table class="table table-hover text-nowrap table-striped">
                  <thead>
                     <tr>
                        <th>Parceiro</th>
                        <th>Data de Recebimento</th>
                        <th>Status das cestas</th>
                        <th></th>
                     </tr>
                  </thead>
                  <tbody id="listCestasNaoSairam"></tbody>
               </table>
               <div id="paginationNaoSairam" class="mt-2 text-center"></div>
            </div>
         </div>
      </div>
   </div>
</section>

<section class="cestas_que_sairam">
   <div class="card">
      <div class="card-header">
         <span class="text-muted text-uppercase">Cestas que sairam</span>
      </div>
      <div class="card-body pt-1">
         <div class="row">
            <div class="card-body table-responsive p-0">
               <table class="table table-hover text-nowrap table-striped">
                  <thead>
                     <tr>
                        <th>Data em Rota</th>
                        <th>Parceiro</th>
                        <th>Família</th>
                        <th>Status das cestas</th>
                        <th></th>
                     </tr>
                  </thead>
                  <tbody>
                     @forelse($cestasEmRota as $cesta)
                     <tr>
                        <td class="align-middle">{{ $cesta->parceiro->name }}</td>
                        <td class="align-middle">{{ $cesta->familia->representante->nome }}</td>
                        <td class="align-middle">
                           <span class="badge badge-warning text-uppercase text-white">{{ $cesta->status }}</span>
                        </td>
                        <td class="align-middle">
                           <form action="{{ route('cestas.entrega_ifes', $cesta->id) }}" method="POST" id="form-entregar-cesta-{{ $cesta->id }}">
                              @method('PUT')
                              @csrf
                              <input type="hidden" name="cesta_id" id="cesta_id" value="{{ $cesta->id }}">
                              <button type="button" class="btn btn-warning btn-sm text-white entregar-cesta" data-id="{{ $cesta->id }}">
                                 <i class="fas fa-check"></i>
                              </button>
                           </form>
                        </td>
                     </tr>
                     @empty
                     <tr>
                        <td colspan="5" class="text-center">Nenhuma cesta encontrada.</td>
                     </tr>
                     @endforelse
                  </tbody>
               </table>
            </div>
         </div>
      </div>
   </div>
</section>

<section class="cestas_entregues">
   <div class="card">
      <div class="card-header">
         <span class="text-muted text-uppercase align-middle mr-2">Cestas Entregues</span>
         <a href="#" class="btn btn-secondary btn-sm">
            <i class="fas fa-search"></i>
         </a>
      </div>
      <div class="card-body pt-1">
         <div class="row">
            <div class="card-body table-responsive p-0">
               <table class="table table-hover text-nowrap table-striped">
                  <thead>
                     <tr>
                        <th>Data de Entrega</th>
                        <th>Parceiro</th>
                        <th>Família</th>
                        <th>Ponto de Origem</th>
                        <th>Status</th>
                     </tr>
                  </thead>
                  <tbody id="listCestasEntregue"></tbody>
               </table>
               <div id="paginationEntregues" class="mt-2 text-center"></div>
            </div>
         </div>
      </div>
   </div>
</section>

@include('components.modals.entrega-propria-modal')
@stop

@section('js')
<script src="{{ asset('assets/js/cestas.js') }}"></script>
<script src="{{ asset('assets/js/pagination.js') }}"></script>

<script>
   $('#modalEntregarCesta').on('shown.bs.modal', function () {
      if (!$('#familia_id_propria').hasClass('select2-hidden-accessible')) {
         $('#familia_id_propria').select2({
            placeholder: 'Selecione uma Família',
            allowClear: true,
            dropdownParent: $('#modalEntregarCesta'),
         });
      }
      $('#familia_id_propria').on('change', function () {
         const familiaId = $(this).val();
         const container = $('#historico-cestas-modal');
         if (!familiaId) {
            container.html('');
            return;
         }
         container.html('<p class="text-muted"><i class="fa fa-spinner fa-pulse"></i> Buscando histórico...</p>');
         let url = "{{ route('familias.getCestas', ['familia' => ':id']) }}".replace(':id', familiaId);
         $.get(url, function (cestas) {
            container.html('');
            if (cestas.length === 0) {
               container.html('<p class="text-muted">Nenhuma cesta entregue para esta família.</p>');
               return;
            }
            const hoje = new Date();
            hoje.setHours(0, 0, 0, 0);
            let rows = '';
            cestas.forEach(function (cesta) {
               let cor = 'bg-secondary';
               if (cesta.data_entrega) {
                  const diff = (hoje - new Date(cesta.data_entrega)) / (1000 * 60 * 60 * 24);
                  cor = diff <= 30 ? 'bg-danger' : 'bg-success';
               }
               const saida = cesta.data_em_rota ? new Date(cesta.data_em_rota).toLocaleDateString('pt-BR', { timeZone: 'UTC' }) : '-';
               const entrega = cesta.data_entrega ? new Date(cesta.data_entrega).toLocaleDateString('pt-BR', { timeZone: 'UTC' }) : '-';
               rows += `<tr>
                  <td>${cesta.parceiro.name}</td>
                  <td class="text-white text-center font-weight-bold ${cor}">${saida}</td>
                  <td class="text-white text-center font-weight-bold ${cor}">${entrega}</td>
               </tr>`;
            });
            container.html(`
               <h6 class="mt-2">Histórico de Entregas</h6>
               <table class="table table-bordered table-sm">
                  <thead><tr><th>Parceiro</th><th>Data de Saída</th><th>Data de Entrega</th></tr></thead>
                  <tbody>${rows}</tbody>
               </table>
            `);
         }).fail(function () {
            container.html('<p class="text-danger">Erro ao buscar histórico.</p>');
         });
      });
   });

   $('#modalEntregarCesta').on('hidden.bs.modal', function () {
      $('#familia_id_propria').val(null).trigger('change');
      $('#historico-cestas-modal').html('');
   });
</script>

@if (session('success'))
<script>
   Swal.fire({
      icon: 'success',
      title: 'Sucesso',
      text: "{{ session('success') }}",
   });
</script>
@endif

@if (session('error'))
<script>
   Swal.fire({
      icon: 'error',
      title: 'Erro',
      text: @json(session('error')),
   });
</script>
@endif

@if ($errors->any())
<script>
   $(document).ready(function () {
      $('#modalEntregarCesta').modal('show');
   });
</script>
@endif
@endsection