@extends('adminlte::page')
@section('title', 'Gerenciar Itens - RECeBa')
@section('content_header')
<h1 class="text-bold"><i class="fas fa-circle"></i> Gerenciar Itens</h1>
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
                        <th>Quantidade</th>
                        <th>Status dos itens</th>
                        <th></th>
                     </tr>
                  </thead>
                  <tbody>
                     <tr>
                        <td class="align-middle">Parceiro</td>
                        <td class="align-middle">01/01/2023</td>
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
                        <th>Quantidade</th>
                        <th>Status das itens</th>
                        <th></th>
                     </tr>
                  </thead>
                  <tbody>
                     <tr>
                        <td class="align-middle">Parceiro</td>
                        <td class="align-middle">01/01/2023</td>
                        <td class="align-middle">10</td>
                        <td class="align-middle">
                           <span class="badge badge-primary text-uppercase">Em Montagem</span>
                        </td>
                        <td class="align-middle">
                           <a href="#" class="btn btn-warning btn-sm text-bold text-white float-right">
                              <i class="fas fa-check"></i> Concluir
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
                        <th>Quantidade</th>
                        <th>Status das itens</th>
                        <th></th>
                     </tr>
                  </thead>
                  <tbody>
                     <tr>
                        <td class="align-middle">Parceiro</td>
                        <td class="align-middle">01/01/2023</td>
                        <td class="align-middle">10</td>
                        <td class="align-middle">
                           <span class="badge badge-warning text-uppercase text-white">Montada</span>
                        </td>
                        <td class="align-middle">
                           <a href="#" class="btn btn-warning btn-sm text-bold text-white float-right">
                              <i class="fas fa-check"></i> Entregar
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
                        <th>Quantidade</th>
                        <th>Status das itens</th>
                     </tr>
                  </thead>
                  <tbody>
                     <tr>
                        <td class="align-middle">Parceiro</td>
                        <td class="align-middle">01/01/2023</td>
                        <td class="align-middle">10</td>
                        <td class="align-middle">
                           <span class="badge badge-success text-uppercase">Entregue</span>
                        </td>
                     </tr>
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
                        <th>Quantidade</th>
                        <th>Status das itens</th>
                     </tr>
                  </thead>
                  <tbody>
                     <tr>
                        <td class="align-middle">Parceiro</td>
                        <td class="align-middle">01/01/2023</td>
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