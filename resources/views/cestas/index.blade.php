@extends('adminlte::page')
@section('title', 'Registrar Entrega - RECeBa')
@section('content_header')
<h1 class="text-bold"><i class="fas fa-calendar-plus"></i> Registrar Entrega</h1>
@stop
@section('content')
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