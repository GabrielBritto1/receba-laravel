@extends('adminlte::page')
@section('title', 'Familias - RECeBa')
@section('content_header')
<h1 class="text-bold"><i class="fas fa-user-friends"></i> Famílias</h1>
@stop
@section('content')
<div class="card">
   <div class="card-header">
      <h3 class="card-title"><i class="fas fa-filter mr-1"></i> Filtros</h3>
   </div>
   <div class="card-body">
      <div class="row">
         <div class="col-md-4">
            <div class="form-group">
               <label>Nome do Representante:</label>
               <input type="text" id="filtro-nome" class="form-control form-control-sm" placeholder="Buscar por nome...">
            </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
               <label>Status:</label>
               <select id="filtro-status" class="form-control form-control-sm">
                  <option value="">Todos</option>
                  <option value="1">Ativo</option>
                  <option value="0">Inativo</option>
               </select>
            </div>
         </div>
         @can('Administrador')
         <div class="col-md-3">
            <div class="form-group">
               <label>Parceiro:</label>
               <select id="filtro-parceiro" class="form-control form-control-sm">
                  <option value="">Todos</option>
                  @foreach($parceiros as $p)
                  <option value="{{ $p->id }}">{{ $p->name }}</option>
                  @endforeach
               </select>
            </div>
         </div>
         @endcan
         <div class="col-md-2 d-flex align-items-end">
            <div class="form-group w-100">
               <button id="btn-filtrar" class="btn btn-success btn-sm btn-block">
                  <i class="fas fa-search"></i> Filtrar
               </button>
            </div>
         </div>
      </div>
   </div>
</div>

<div class="card">
   <div class="card-header">
      <div class="card-tools">
         @if($parceiro)
         <button type="button" class="btn btn-success btn-sm text-bold" data-toggle="modal" data-target="#modalCadastrarFamilia">
            <i class="fas fa-plus"></i> Cadastrar Família
         </button>
         @else
         <button class="btn btn-secondary btn-sm text-bold" disabled title="Você não está vinculado a nenhum parceiro">
            <i class="fas fa-plus"></i> Cadastrar Família
         </button>
         @endif
      </div>
   </div>
   <div class="card-body">
      <div class="table-responsive">
         <table class="table table-hover text-nowrap table-striped">
            <thead>
               <tr>
                  <th>Representante</th>
                  <th>CPF</th>
                  <th>Telefone</th>
                  <th>Parceiro</th>
                  <th>Status</th>
                  <th></th>
               </tr>
            </thead>
            <tbody id="list"></tbody>
         </table>
         <div id="paginationLinks" class="mt-2 text-center"></div>
      </div>
   </div>
</div>

@include('components.modals.familia-modal')
@stop

@section('js')
<script src="{{ asset('assets/js/familia.js') }}"></script>
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
      title: 'Erro ao cadastrar família',
      text: @json(session('error')),
   });
</script>
@endif

@if ($errors->any() || session('error'))
<script>
   $(document).ready(function () {
      $('#modalCadastrarFamilia').modal('show');
   });
</script>
@endif
@stop