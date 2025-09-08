@extends('adminlte::page')
@section('title', 'Painel - RECeBa')
@section('content_header')
<h1 class="text-bold"><i class="fas fa-home"></i> Painel</h1>
@endsection
@section('content')
<div class="row">
   <div class="col-lg-3 col-6">
      <!-- small box -->
      <div class="small-box bg-success">
         <div class="inner">
            <h3>150</h3>
            <p>Cestas Entregues</p>
         </div>
         <div class="icon">
            <i class="fas fa-shopping-basket"></i>
         </div>
         <a href="#" class="small-box-footer">Mais informações <i class="fas fa-arrow-circle-right"></i></a>
      </div>
   </div>
   <!-- ./col -->
   <div class="col-lg-3 col-6">
      <!-- small box -->
      <div class="small-box bg-danger">
         <div class="inner">
            <h3>{{ $parceiros }}</h3>
            <p>Parceiros Cadastrados</p>
         </div>
         <div class="icon">
            <i class="fas fa-address-card"></i>
         </div>
         <a href="{{ route('parceiros.index') }}" class="small-box-footer">Mais informações <i class="fas fa-arrow-circle-right"></i></a>
      </div>
   </div>
   <!-- ./col -->
   <div class="col-lg-3 col-6">
      <!-- small box -->
      <div class="small-box bg-primary">
         <div class="inner">
            <h3>{{ $familias }}</h3>
            <p>Famílias Cadastradas</p>
         </div>
         <div class="icon">
            <i class="fas fa-user-friends"></i>
         </div>
         <a href="{{ route('familias.index') }}" class="small-box-footer">Mais informações <i class="fas fa-arrow-circle-right"></i></a>
      </div>
   </div>
   <!-- ./col -->
   <div class="col-lg-3 col-6">
      <!-- small box -->
      <div class="small-box bg-secondary">
         <div class="inner">
            <h3 class="d-none d-md-block">Configurações</h3>
            <p class="d-none d-md-block">ㅤ</p>

            <!-- Tela Móvel -->
            <h3 class="d-lg-none"><i class="fas fa-cog"></i></h3>
            <p class="d-lg-none">Configurações</p>
         </div>
         <div class="icon">
            <i class="fas fa-cog"></i>
         </div>

         <a href="{{ route('users.configuracao', Auth::user()->id) }}" class="small-box-footer">Ir para configurações <i class="fas fa-arrow-circle-right"></i></a>
      </div>
   </div>
   <!-- ./col -->
</div>
@stop