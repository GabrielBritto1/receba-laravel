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
            <a href="#" class="btn btn-success btn-sm text-bold">
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
                        <th>Status das cestas</th>
                        <th></th>
                     </tr>
                  </thead>
                  <tbody>
                     <tr>
                        <td class="align-middle">Parceiro</td>
                        <td class="align-middle">
                           <span class="badge badge-danger text-uppercase">Não saiu para entrega</span>
                        </td>
                        <td class="align-middle">
                           <a href="#" class="btn btn-warning btn-sm text-white">
                              <i class="fas fa-shipping-fast"></i>
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
                        <th>Status das cestas</th>
                        <th></th>
                     </tr>
                  </thead>
                  <tbody>
                     <tr>
                        <td class="align-middle">Parceiro</td>
                        <td class="align-middle">
                           <span class="badge badge-warning text-uppercase">Em rota</span>
                        </td>
                        <td class="align-middle">
                           <a href="#" class="btn btn-warning btn-sm text-white">
                              <i class="fas fa-check"></i>
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
                     <tr>
                        <td class="align-middle">Juju pega Doce</td>
                        <td class="align-middle">
                           <span class="badge p-2" style="background-color: #fffb4a; font-size: 16px;">IFES - IESC</span>
                        </td>
                        <td class="align-middle">IFES</td>
                        <td class="align-middle">
                           <span class="badge badge-success text-uppercase">Cesta Entregue</span>
                        </td>
                     </tr>
                     <tr>
                        <td class="align-middle">Juju pega Doce</td>
                        <td class="align-middle">
                           <span class="badge p-2" style="background-color: #fffb4a; font-size: 16px;">IFES - IESC</span>
                        </td>
                        <td class="align-middle">Própria</td>
                        <td class="align-middle">
                           <span class="badge badge-success text-uppercase">Cesta Entregue</span>
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