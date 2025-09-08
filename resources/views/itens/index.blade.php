@extends('adminlte::page')
@section('title', 'Solicitar Itens - RECeBa')
@section('content_header')
<h1 class="text-bold"><i class="fas fa-shopping-bag"></i> Solicitar Itens</h1>
@stop
@section('content')
<div class="card">
   <div class="card-header">
      <span class="text-muted text-uppercase">Solicitações</span>
      <div class="card-tools">
         <a href="#" class="btn btn-success btn-sm text-bold">
            <i class="fas fa-plus"></i> Solicitar Itens ao IFES
         </a>
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
                  <tr>
                     <td class="align-middle">10/10/2021</td>
                     <td class="align-middle">10</td>
                     <td class="align-middle">8</td>
                     <td class="align-middle text-bold">Parceiro</td>
                     <td class="align-middle">
                        <span class="badge badge-primary text-uppercase" style="background-color: #FF9E4A;">Pendente</span>
                     </td>
                  </tr>
                  <tr>
                     <td class="align-middle">10/10/2021</td>
                     <td class="align-middle">10</td>
                     <td class="align-middle">8</td>
                     <td class="align-middle text-bold">IFES</td>
                     <td class="align-middle">
                        <span class="badge badge-info text-uppercase">Aceita</span>
                     </td>
                  </tr>
                  <tr>
                     <td class="align-middle">10/10/2021</td>
                     <td class="align-middle">10</td>
                     <td class="align-middle">8</td>
                     <td class="align-middle text-bold">IFES</td>
                     <td class="align-middle">
                        <span class="badge badge-warning text-uppercase text-white">Montada</span>
                     </td>
                  </tr>
                  <tr>
                     <td class="align-middle">10/10/2021</td>
                     <td class="align-middle">10</td>
                     <td class="align-middle">8</td>
                     <td class="align-middle text-bold">Parceiro</td>
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
@stop