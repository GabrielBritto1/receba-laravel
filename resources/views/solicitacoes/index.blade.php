@extends('adminlte::page')
@section('title', 'Solicitar Cestas - RECeBa')
@section('content_header')
<h1 class="text-bold"><i class="fas fa-shopping-basket"></i> Solicitar Cestas</h1>
@stop
@section('content')
<div class="card">
   <div class="card-header">
      <h3 class="card-title"><i class="fas fa-filter mr-1"></i> Filtros</h3>
   </div>
   <div class="card-body">
      <div class="row">
         @can('Administrador')
         <div class="col-md-3">
            <div class="form-group">
               <label>Parceiro:</label>
               <select id="filtro-parceiro-sol" class="form-control form-control-sm">
                  <option value="">Todos</option>
                  @foreach($parceiros as $p)
                  <option value="{{ $p->id }}">{{ $p->name }}</option>
                  @endforeach
               </select>
            </div>
         </div>
         @endcan
         <div class="col-md-3">
            <div class="form-group">
               <label>Status:</label>
               <select id="filtro-status-sol" class="form-control form-control-sm">
                  <option value="">Todos</option>
                  <option value="Em Análise">Em Análise</option>
                  <option value="Aceita">Aceita</option>
                  <option value="Montada">Montada</option>
                  <option value="Entregue">Entregue</option>
               </select>
            </div>
         </div>
         <div class="col-md-2">
            <div class="form-group">
               <label>Data início:</label>
               <input type="date" id="filtro-data-inicio-sol" class="form-control form-control-sm">
            </div>
         </div>
         <div class="col-md-2">
            <div class="form-group">
               <label>Data fim:</label>
               <input type="date" id="filtro-data-fim-sol" class="form-control form-control-sm">
            </div>
         </div>
         <div class="col-md-2 d-flex align-items-end">
            <div class="form-group w-100">
               <button id="btn-filtrar-sol" class="btn btn-success btn-sm btn-block">
                  <i class="fas fa-search"></i> Filtrar
               </button>
            </div>
         </div>
      </div>
   </div>
</div>

<div class="card">
   <div class="card-header">
      <span class="text-muted text-uppercase">Solicitações</span>
      <div class="card-tools">
         @if($parceiro)
         <a href="#" class="btn btn-success btn-sm text-bold" data-toggle="modal" data-target="#modalCadastrarCesta">
            <i class="fas fa-plus"></i> Solicitar Cesta ao IFES
         </a>
         @else
         <button class="btn btn-secondary btn-sm text-bold" disabled title="Você não está vinculado a nenhum parceiro">
            <i class="fas fa-plus"></i> Solicitar Cesta ao IFES
         </button>
         @endif
      </div>
   </div>
   <div class="card-body pt-1">
      <div class="row">
         <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap">
               <thead>
                  <tr>
                     <th>Data da Reserva</th>
                     <th>Parceiro</th>
                     <th>Quantidade Total</th>
                     <th>Quantidade Aceita</th>
                     <th>Status</th>
                  </tr>
               </thead>
               <tbody id="listSolicitacoes"></tbody>
            </table>
            <div id="paginationLinksPrincipal" class="mt-2 text-center"></div>
         </div>
      </div>
   </div>
</div>

<div class="card">
   <div class="card-header">
      <span class="text-muted text-uppercase">Solicitações não Aceitas</span>
   </div>
   <div class="card-body pt-1">
      <div class="row">
         <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap">
               <thead>
                  <tr>
                     <th>Data da Reserva</th>
                     <th>Parceiro</th>
                     <th>Quantidade Total</th>
                     <th>Quantidade Não Aceita</th>
                     <th>Status</th>
                  </tr>
               </thead>
               <tbody id="listSolicitacoesNaoAceitas"></tbody>
            </table>
            <div id="paginationLinksSecundario" class="mt-2 text-center"></div>
         </div>
      </div>
   </div>
</div>

@include('components.modals.solicitacoes-modal')
@stop

@section('js')
<script src="{{ asset('assets/js/solicitacoes.js') }}"></script>
<script src="{{ asset('assets/js/pagination.js') }}"></script>

@if (session('success'))
<script>
   Swal.fire({
      icon: 'success',
      title: 'Sucesso',
      text: "{{ session('success') }}",
   });
</script>
@endif
@endsection