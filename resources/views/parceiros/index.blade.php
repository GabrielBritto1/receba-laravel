@extends('adminlte::page')
@section('title', 'Parceiros - RECeBa')
@section('content_header')
<h1 class="text-bold"><i class="fas fa-address-card"></i> Parceiros</h1>
@stop
@section('content')
<div class="card">
   <div class="card-header">
      <div class="card-tools d-flex justify-content-end">
         @can('Administrador')
         <button type="button" class="btn btn-success btn-sm mr-2 text-bold" data-toggle="modal" data-target="#cadastrarCoordenador">
            <i class="fas fa-plus"></i> Adicionar Coordenador
         </button>
         <button type="button" class="btn btn-success btn-sm mr-2 text-bold" data-toggle="modal" data-target="#cadastrarSecretario">
            <i class="fas fa-plus"></i> Adicionar Secretário(a)
         </button>
         <button type="button" class="btn btn-success btn-sm mr-2 text-bold" data-toggle="modal" data-target="#cadastrarParceiro">
            <i class="fas fa-plus"></i> Adicionar Parceiro
         </button>
         @endcan
      </div>
   </div>
   <div class="card-body pt-1">
      <div class="row">
         <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap table-striped">
               <thead>
                  <tr>
                     <th>Nome</th>
                     <th>Sigla</th>
                     <th>Telefone</th>
                     <th>Local de Atuação</th>
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
</div>

@include('components.modals.parceiro-modals')
@include('components.modals.secretario-associar-modal')
@stop

@section('js')
<script src="/assets/js/parceiro.js"></script>
<script src="/assets/js/pagination.js"></script>

@if (session('success')
&& session('success_action') === 'store'
|| session('success_action') === 'storeCoordenador'
|| session('success_action') === 'storeSecretario')
<script>
   Swal.fire({
      icon: 'success',
      title: 'Sucesso',
      text: "{{ session('success') }}",
   });
</script>
@endif
@stop