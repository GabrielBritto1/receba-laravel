@extends('adminlte::page')
@section('title', 'Relatório Visual - RECeBa')
@section('content_header')
<h1 class="text-bold"><i class="fas fa-file"></i> Relatórios Visuais</h1>
@stop
@section('content')
<div class="card">
   <div class="card-body">
      <div class="row">
         <div class="col-md-12">
            <div class="alert alert-info">
               <h5><i class="icon fas fa-info"></i> Aviso</h5>
               Este relatório é gerado em formato visual, ideal para visualização rápida e impressão. Para relatórios mais detalhados, utilize as opções de planilha ou PDF.
            </div>
         </div>
      </div>
      <div class="row">
         <div class="col col-sm col-md">
            <a href="{{ route('relatorios.relatorio_saida_de_cesta') }}">
               <div class="info-box">
                  <span class="info-box-icon bg-success elevation-1"><i class="fas fa-shopping-basket"></i></span>
                  <div class="info-box-content">
                     <span class="info-box-text text-bold text-dark">Relatório de Saída de Cestas</span>
                  </div>
                  <!-- /.info-box-content -->
               </div>
            </a>
            <!-- /.info-box -->
         </div>
         <!-- /.col -->
         <div class="col col-sm col-md">
            <a href="{{ route('relatorios.relatorio_parceiro') }}">
               <div class="info-box mb-3">
                  <span class="info-box-icon bg-success elevation-1"><i class="fas fa-address-card"></i></span>
                  <div class="info-box-content">
                     <span class="info-box-text text-bold text-dark">Relatório de Parceiros</span>
                  </div>
                  <!-- /.info-box-content -->
               </div>
            </a>
            <!-- /.info-box -->
         </div>
         <!-- /.col -->

         <!-- fix for small devices only -->
         <div class="clearfix hidden-md-up"></div>

         <div class="col col-sm col-md">
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
         <div class="col col-sm col-md">
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
         <div class="col col-sm col-md">
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