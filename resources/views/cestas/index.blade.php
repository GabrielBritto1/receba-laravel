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
            <a href="#" class="btn btn-success btn-sm text-bold" data-toggle="modal" data-target="#modalEntregarCesta">
               <i class="fas fa-plus"></i> Registrar Entrega Própria
            </a>
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
                  <tbody>
                     @forelse($cestasPorParceiro as $cesta)
                     @if ($cesta->status === 'Não saiu para entrega')
                     <tr>
                        <td class="align-middle">{{ $cesta->parceiro->name }}</td>
                        <td class="align-middle">{{ $cesta->data_recebimento->format('d/m/Y') }}</td>
                        <td class="align-middle">
                           <span class="badge badge-danger text-uppercase">{{ $cesta->status }}</span>
                        </td>
                        <td class="align-middle">
                           <a href="{{ route('cestas.entrega_familia', $cesta->id) }}" class="btn btn-warning btn-sm text-white">
                              <i class="fas fa-shipping-fast"></i>
                           </a>
                        </td>
                     </tr>
                     @endif
                     @empty
                     <tr>
                        <td colspan="4" class="text-center">Nenhuma cesta encontrada.</td>
                     </tr>
                     @endforelse
                  </tbody>
               </table>
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
                        <th>Parceiro</th>
                        <th>Família</th>
                        <th>Status das cestas</th>
                        <th></th>
                     </tr>
                  </thead>
                  <tbody>
                     @forelse($cestasPorParceiro as $cesta)
                     @if ($cesta->status === 'Em rota')
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
                     @endif
                     @empty
                     <tr>
                        <td colspan="4" class="text-center">Nenhuma cesta encontrada.</td>
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
                        <th>Família</th>
                        <th>Parceiro</th>
                        <th>Ponto de Origem</th>
                        <th>Status</th>
                     </tr>
                  </thead>
                  <tbody>
                     @forelse($cestasPorParceiro as $cesta)
                     @if ($cesta->status === 'Entregue')
                     <tr>
                        <td class="align-middle">{{ $cesta->familia->representante->nome }}</td>
                        <td class="align-middle">
                           <span class="badge text-uppercase text-white"
                              style="background-color: {{ $cesta->parceiro->sigla->color ?? '#28a745' }};">{{ $cesta->parceiro->sigla->name ?? $cesta->parceiro->name }}</span>
                        </td>
                        <td class="align-middle">{{ $cesta->ponto_origem }}</td>
                        <td class="align-middle">
                           <span class="badge badge-success text-uppercase">{{ $cesta->status }}</span>
                        </td>
                     </tr>
                     @endif
                     @empty
                     <tr>
                        <td colspan="4" class="text-center">Nenhuma cesta encontrada.</td>
                     </tr>
                     @endforelse
                  </tbody>
               </table>
            </div>
         </div>
      </div>
   </div>
</section>

<div class="modal fade" id="modalEntregarCesta" tabindex="-1" aria-labelledby="modalEntregarCesta" aria-hidden="true">
   <div class="modal-dialog modal-xl">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title text-bold" id="modalEntregarCesta">Entregar Cesta Própria</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            <form action="{{ route('cestas.entregaCestaPropria') }}" method="POST">
               @csrf
               <div class="row">
                  <div class="col">
                     <div class="form-group">
                        <label for="familia_id">Família</label>
                        <select name="familia_id" id="familia_id" class="form-control">
                           <option selected disabled value="">Selecione uma Família</option>
                           @forelse($familias as $familia)
                           <option value="{{ $familia->id }}">{{ $familia->representante->nome }}</option>
                           @empty
                           <option value="">Nenhuma Família cadastrada</option>
                           @endforelse
                        </select>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col">
                     <div class="form-group">
                        <label for="data_entrega">Data da Entrega Para a Família</label>
                        <input type="datetime-local" class="form-control" id="data_entrega" name="data_entrega">
                     </div>
                  </div>
               </div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            <button type="submit" class="btn btn-success">Registrar Entrega Própria</button>
         </div>
         </form>
      </div>
   </div>
</div>
@stop

@section('js')
<script>
   $('.entregar-cesta').on('click', function() {
      const cestaId = $(this).data('id');
      Swal.fire({
         title: 'Você deseja confirmar a entrega desta cesta?',
         icon: 'question',
         showCancelButton: true,
         confirmButtonColor: '#28a745',
         confirmButtonText: 'Sim',
         cancelButtonText: 'Não',
      }).then((result) => {
         if (result.isConfirmed) {
            document.getElementById(`form-entregar-cesta-${cestaId}`).submit();
         }
      });
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
@endsection