@extends('adminlte::page')
@section('title', 'Solicitar Itens - RECeBa')
@section('content_header')
<h1 class="text-bold"><i class="fas fa-shopping-bag"></i> Solicitar Itens</h1>
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
               <select id="filtro-parceiro-item" class="form-control form-control-sm">
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
               <label>Item:</label>
               <select id="filtro-item-id" class="form-control form-control-sm">
                  <option value="">Todos</option>
                  @foreach($itensDisponiveis as $item)
                  <option value="{{ $item->id }}">{{ $item->nome }}</option>
                  @endforeach
               </select>
            </div>
         </div>
         <div class="col-md-2">
            <div class="form-group">
               <label>Data início:</label>
               <input type="date" id="filtro-data-inicio-item" class="form-control form-control-sm">
            </div>
         </div>
         <div class="col-md-2">
            <div class="form-group">
               <label>Data fim:</label>
               <input type="date" id="filtro-data-fim-item" class="form-control form-control-sm">
            </div>
         </div>
         <div class="col-md-2 d-flex align-items-end">
            <div class="form-group w-100">
               <button id="btn-filtrar-item" class="btn btn-success btn-sm btn-block">
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
         <a href="#" class="btn btn-success btn-sm text-bold" data-toggle="modal" data-target="#modalCadastrarItem">
            <i class="fas fa-plus"></i> Solicitar Itens ao IFES
         </a>
         @else
         <button class="btn btn-secondary btn-sm text-bold" disabled title="Você não está vinculado a nenhum parceiro">
            <i class="fas fa-plus"></i> Solicitar Itens ao IFES
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
                     <th>Item</th>
                     <th>Quantidade Total</th>
                     <th>Quantidade Aceita</th>
                     <th>Status</th>
                  </tr>
               </thead>
               <tbody id="listItens"></tbody>
            </table>
            <div id="paginationLinksItensPrincipal" class="mt-2 text-center"></div>
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
                     <th>Item</th>
                     <th>Quantidade Total</th>
                     <th>Quantidade Não Aceita</th>
                     <th>Status</th>
                  </tr>
               </thead>
               <tbody id="listItensNaoAceitos"></tbody>
            </table>
            <div id="paginationLinksItensSecundario" class="mt-2 text-center"></div>
         </div>
      </div>
   </div>
</div>

@include('components.modals.itens-modal')
@stop

@section('js')
<script src="{{ asset('assets/js/itens.js') }}"></script>
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
      $('#modalCadastrarItem').modal('show');
   });
</script>
@endif
@endsection
