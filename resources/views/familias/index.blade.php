@extends('adminlte::page')
@section('title', 'Familias - RECeBa')
@section('content_header')
<h1 class="text-bold"><i class="fas fa-user-friends"></i> Famílias</h1>
@stop
@section('content')
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
<script src="/assets/js/familia.js"></script>
<script src="/assets/js/pagination.js"></script>

@if (session('success'))
<script>
   Swal.fire({
      icon: 'success',
      title: 'Sucesso',
      text: "{{ session('success') }}",
   });
</script>
@endif
@stop