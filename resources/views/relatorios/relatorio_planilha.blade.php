@extends('adminlte::page')
@section('title', 'Relatório Planilha - RECeBa')
@section('content_header')
<h1 class="text-bold"><i class="fas fa-file-excel"></i> Relatórios Planilhas</h1>
@stop
@section('content')
<div class="card">
   <div class="card-body">
      <div class="row">
         <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
               <span class="info-box-icon bg-success elevation-1"><i class="fas fa-shopping-basket"></i></span>
               <div class="info-box-content">
                  <span class="info-box-text text-bold">Relatório de Saída de Cestas</span>
               </div>
               <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
         </div>
         <!-- /.col -->
         <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
               <span class="info-box-icon bg-success elevation-1"><i class="fas fa-address-card"></i></span>
               <div class="info-box-content">
                  <span class="info-box-text text-bold">Relatório de Parceiros</span>
               </div>
               <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
         </div>
         <!-- /.col -->

         <!-- fix for small devices only -->
         <div class="clearfix hidden-md-up"></div>

         <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
               <span class="info-box-icon bg-success elevation-1"><i class="fas fa-user-friends"></i></span>

               <div class="info-box-content">
                  <span class="info-box-text text-bold">Relatório de Famílias</span>
               </div>
               <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
         </div>
         <!-- /.col -->
         <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
               <span class="info-box-icon bg-success elevation-1"><i class="fas fa-shopping-basket"></i></span>

               <div class="info-box-content">
                  <span class="info-box-text text-bold">Relatório de Saída de Cesta por Parceiro</span>
               </div>
               <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
         </div>
         <!-- /.col -->
         <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
               <span class="info-box-icon bg-success elevation-1"><i class="fas fa-shopping-bag"></i></span>
               <div class="info-box-content">
                  <span class="info-box-text text-bold">Relatório de Itens Entregues</span>
               </div>
               <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
         </div>
         <!-- /.col -->
      </div>
   </div>
</div>
@stop