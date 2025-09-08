@extends('adminlte::page')
@section('title', 'Solicitar Cestas - RECeBa')
@section('content_header')
<h1 class="text-bold"><i class="fas fa-shopping-basket"></i> Solicitar Cestas</h1>
@stop
@section('content')
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
            <table class="table table-hover text-nowrap table-striped">
               <thead>
                  <tr>
                     <th>Data da Reserva</th>
                     <th>Quantidade Total</th>
                     <th>Quantidade Aceita</th>
                     <th>Em Posse</th>
                     <th>Status</th>
                  </tr>
               </thead>
               <tbody>
                  @forelse($cestas as $cesta)
                  <tr>
                     <td class="align-middle">{{ $cesta->created_at->format('d/m/Y') }}</td>
                     <td class="align-middle">{{ $cesta->quantidade_total }}</td>
                     <td class="align-middle">{{ $cesta->quantidade_total }}</td>
                     <td class="align-middle text-bold">{{ $cesta->ponto_origem }}</td>
                     @if ($cesta->status == 'Em Análise')
                     <td class="align-middle">
                        <span class="badge badge-primary text-uppercase" style="background-color: #FF9E4A;">Em Análise</span>
                     </td>
                     @elseif ($cesta->status == 'Aceita')
                     <td class="align-middle">
                        <span class="badge badge-info text-uppercase">Aceita</span>
                     </td>
                     @elseif ($cesta->status == 'Montada')
                     <td class="align-middle">
                        <span class="badge badge-warning text-uppercase text-white">Montada</span>
                     </td>
                     @elseif ($cesta->status == 'Entregue')
                     <td class="align-middle">
                        <span class="badge badge-success text-uppercase">Entregue</span>
                     </td>
                     @endif
                  </tr>
                  @empty
                  <tr>
                     <td colspan="5" class="text-center">Nenhuma solicitação encontrada.</td>
                  </tr>
                  @endforelse
               </tbody>
            </table>
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
            <table class="table table-hover text-nowrap table-striped">
               <thead>
                  <tr>
                     <th>Data da Reserva</th>
                     <th>Quantidade Total</th>
                     <th>Quantidade Aceita</th>
                     <th>Em Posse</th>
                     <th>Status</th>
                  </tr>
               </thead>
               <tbody>
                  <tr>
                     <td class="align-middle">10/10/2021</td>
                     <td class="align-middle">10</td>
                     <td class="align-middle">8</td>
                     <td class="align-middle text-bold">IFES</td>
                     <td class="align-middle">
                        <span class="badge badge-danger text-uppercase">Não Aceita</span>
                     </td>
                  </tr>
               </tbody>
            </table>
         </div>
      </div>
   </div>
</div>

<div class="modal fade" id="modalCadastrarCesta" tabindex="-1" aria-labelledby="modalCadastrarCestaLabel" aria-hidden="true">
   <div class="modal-dialog modal-xl">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title text-bold" id="modalCadastrarCestaLabel">Solicitar Cesta ao IFES</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            <form action="{{ route('cestas.store') }}" method="POST" id="form-cadastrar-cesta">
               @csrf
               <div class="row">
                  <div class="col">
                     <div class="form-group">
                        <label for="parceiro">Parceiro</label>
                        <input type="text" class="form-control" disabled value="{{ optional($parceiro)->name ?: 'Parceiro não encontrado' }}">
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-12 col-md-6">
                     <div class="form-group">
                        <label for="data_entrega">Data da Entrega Parcial</label>
                        <input type="date" class="form-control" id="data_entrega" name="data_entrega">
                     </div>
                  </div>
                  <div class="col-12 col-md-6">
                     <div class="form-group">
                        <label for="quantidade_total">Quantidade Total</label>
                        <input type="text" class="form-control" id="quantidade_total" name="quantidade_total">
                     </div>
                  </div>
               </div>
            </form>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            <button type="submit" class="btn btn-success" form="form-cadastrar-cesta">Solicitar Cesta ao IFES</button>
         </div>
      </div>
   </div>
</div>
@stop