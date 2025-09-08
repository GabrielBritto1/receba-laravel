@extends('adminlte::page')
@section('title', 'Gerenciar Cestas - RECeBa')
@section('content_header')
<h1 class="text-bold"><i class="fas fa-circle"></i> Gerenciar Cestas</h1>
@stop
@section('content')
<section class="solicitacoes_em_analise">
   <div class="card">
      <div class="card-header">
         <span class="text-muted text-uppercase">Solicitações em Análise</span>
      </div>
      <div class="card-body pt-1">
         <div class="row">
            <div class="card-body table-responsive p-0">
               <table class="table table-hover text-nowrap table-striped">
                  <thead>
                     <tr>
                        <th>Parceiro</th>
                        <th>Data de Reserva</th>
                        <th>Data previsão das entregas para as familias</th>
                        <th>Quantidade</th>
                        <th>Status das cestas</th>
                        <th></th>
                     </tr>
                  </thead>
                  <tbody>
                     @forelse($cestaEmAnalise as $cesta)
                     <tr>
                        <td class="align-middle">{{ $cesta->parceiro->name }}</td>
                        <td class="align-middle">{{ $cesta->created_at->format('d/m/Y') }}</td>
                        <td class="align-middle">{{ $cesta->data_entrega->format('d/m/Y') }}</td>
                        <td class="align-middle">{{ $cesta->quantidade_total }}</td>
                        <td class="align-middle">
                           <span class="badge badge-primary text-uppercase" style="background-color: #FF9E4A;">{{ $cesta->status }}</span>
                        </td>
                        <td class="align-middle">
                           <a href="{{ route('entregas.alterar_status_cesta', ['cesta' => $cesta->id, 'status' => 'Aceita']) }}" class="btn btn-warning btn-sm text-bold text-white float-right">
                              <i class="fas fa-check"></i> Aprovar
                           </a>
                        </td>
                     </tr>
                     @empty
                     <tr>
                        <td class="align-middle text-center" colspan="6">Nenhuma solicitação em análise.</td>
                     </tr>
                     @endforelse
                     <tr>
                        <td class="align-middle">Parceiro</td>
                        <td class="align-middle">01/01/2023</td>
                        <td class="align-middle">01/02/2023</td>
                        <td class="align-middle">10</td>
                        <td class="align-middle">
                           <span class="badge badge-primary text-uppercase" style="background-color: #FF9E4A;">Em Análise</span>
                        </td>
                        <td class="align-middle d-flex justify-content-end">
                           <input type="text" name="qtd_aceita" class="form-control form-control-sm text-center text-bold col-1 mr-1">
                           <a href="#" class="btn btn-warning btn-sm text-bold text-white">
                              <i class="fas fa-check"></i> Aprovar
                           </a>
                        </td>
                     </tr>
                  </tbody>
               </table>
            </div>
         </div>
      </div>
   </div>
</section>

<section class="solicitacoes_em_montagem">
   <div class="card">
      <div class="card-header">
         <span class="text-muted text-uppercase">Solicitações em Montagem</span>
      </div>
      <div class="card-body pt-1">
         <div class="row">
            <div class="card-body table-responsive p-0">
               <table class="table table-hover text-nowrap table-striped">
                  <thead>
                     <tr>
                        <th>Parceiro</th>
                        <th>Data de Reserva</th>
                        <th>Data previsão das entregas para as familias</th>
                        <th>Quantidade</th>
                        <th>Status das cestas</th>
                        <th></th>
                     </tr>
                  </thead>
                  <tbody>
                     @forelse($cestaAceita as $cesta)
                     <tr>
                        <td class="align-middle">{{ $cesta->parceiro->name }}</td>
                        <td class="align-middle">{{ $cesta->created_at->format('d/m/Y') }}</td>
                        <td class="align-middle">{{ $cesta->data_entrega->format('d/m/Y') }}</td>
                        <td class="align-middle">{{ $cesta->quantidade_total }}</td>
                        <td class="align-middle">
                           <span class="badge badge-primary text-uppercase">Em Montagem</span>
                        </td>
                        <td class="align-middle">
                           <a href="{{ route('entregas.alterar_status_cesta', ['cesta' => $cesta->id, 'status' => 'Montada']) }}" class="btn btn-warning btn-sm text-bold text-white float-right">
                              <i class="fas fa-check"></i> Concluir
                           </a>
                        </td>
                     </tr>
                     @empty
                     <tr>
                        <td class="align-middle text-center" colspan="6">Nenhuma solicitação em montagem.</td>
                     </tr>
                     @endforelse
                  </tbody>
               </table>
            </div>
         </div>
      </div>
   </div>
</section>

<section class="solicitacoes_prontas_para_entrega">
   <div class="card">
      <div class="card-header">
         <span class="text-muted text-uppercase">Solicitações Prontas para Entrega</span>
      </div>
      <div class="card-body pt-1">
         <div class="row">
            <div class="card-body table-responsive p-0">
               <table class="table table-hover text-nowrap table-striped">
                  <thead>
                     <tr>
                        <th>Parceiro</th>
                        <th>Data de Reserva</th>
                        <th>Data previsão das entregas para as familias</th>
                        <th>Quantidade</th>
                        <th>Status das cestas</th>
                        <th></th>
                     </tr>
                  </thead>
                  <tbody>
                     @forelse($cestaMontada as $cesta)
                     <tr>
                        <td class="align-middle">{{ $cesta->parceiro->name }}</td>
                        <td class="align-middle">{{ $cesta->created_at->format('d/m/Y') }}</td>
                        <td class="align-middle">{{ $cesta->data_entrega->format('d/m/Y') }}</td>
                        <td class="align-middle">{{ $cesta->quantidade_total }}</td>
                        <td class="align-middle">
                           <span class="badge badge-warning text-uppercase text-white">{{ $cesta->status }}</span>
                        </td>
                        <td class="align-middle">
                           <a href="{{ route('entregas.alterar_status_cesta', ['cesta' => $cesta->id, 'status' => 'Entregue']) }}" class="btn btn-warning btn-sm text-bold text-white float-right">
                              <i class="fas fa-check"></i> Entregar
                           </a>
                        </td>
                     </tr>
                     @empty
                     <tr>
                        <td class="align-middle text-center" colspan="6">Nenhuma solicitação montada.</td>
                     </tr>
                     @endforelse
                  </tbody>
               </table>
            </div>
         </div>
      </div>
   </div>
</section>

<section class="solicitacoes_entregues">
   <div class="card">
      <div class="card-header">
         <span class="text-muted text-uppercase">Solicitações Entregues</span>
      </div>
      <div class="card-body pt-1">
         <div class="row">
            <div class="card-body table-responsive p-0">
               <table class="table table-hover text-nowrap table-striped">
                  <thead>
                     <tr>
                        <th>Parceiro</th>
                        <th>Data de Reserva</th>
                        <th>Data previsão das entregas para as familias</th>
                        <th>Quantidade</th>
                        <th>Status das cestas</th>
                     </tr>
                  </thead>
                  <tbody>
                     @forelse($cestaEntregue as $cesta)
                     <tr>
                        <td class="align-middle">{{ $cesta->parceiro->name }}</td>
                        <td class="align-middle">{{ $cesta->created_at->format('d/m/Y') }}</td>
                        <td class="align-middle">{{ $cesta->data_entrega->format('d/m/Y') }}</td>
                        <td class="align-middle">{{ $cesta->quantidade_total }}</td>
                        <td class="align-middle">
                           <span class="badge badge-success text-uppercase">{{ $cesta->status }}</span>
                        </td>
                     </tr>
                     @empty
                     <tr>
                        <td class="align-middle text-center" colspan="6">Nenhuma solicitação entregue.</td>
                     </tr>
                     @endforelse
                  </tbody>
               </table>
            </div>
         </div>
      </div>
   </div>
</section>

<section class="solicitacoes_nao_aceitas">
   <div class="card">
      <div class="card-header">
         <span class="text-muted text-uppercase">Solicitações Não Aceitas</span>
      </div>
      <div class="card-body pt-1">
         <div class="row">
            <div class="card-body table-responsive p-0">
               <table class="table table-hover text-nowrap table-striped">
                  <thead>
                     <tr>
                        <th>Parceiro</th>
                        <th>Data de Reserva</th>
                        <th>Data previsão das entregas para as familias</th>
                        <th>Quantidade</th>
                        <th>Status das cestas</th>
                     </tr>
                  </thead>
                  <tbody>
                     <tr>
                        <td class="align-middle">Parceiro</td>
                        <td class="align-middle">01/01/2023</td>
                        <td class="align-middle">01/02/2023</td>
                        <td class="align-middle">10</td>
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
</section>
@stop