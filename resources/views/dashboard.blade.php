@extends('adminlte::page')
@section('title', 'Painel - RECeBa')
@section('content_header')
<h1></h1>
@endsection
@section('content')
<div class="row">
   <div class="col-lg-3 col-6">
      <!-- small box -->
      <div class="small-box bg-success">
         <div class="inner">
            <h3>{{ $cestas }}</h3>
            <p>Cestas Entregues</p>
         </div>
         <div class="icon">
            <i class="fas fa-shopping-basket"></i>
         </div>
         <a href="#" class="small-box-footer">Mais informações <i class="fas fa-arrow-circle-right"></i></a>
      </div>
   </div>
   <!-- ./col -->
   @can('Administrador')
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
   @endcan
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
<div class="card">
   <div class="card-header">
      <h3 class="card-title">Atividades Recentes</h3>
   </div>
   <div class="card-body">
      <div class="row">
         <div class="col-md-6">
            <canvas id="navegadoresChart"></canvas>
         </div>
         <div class="col-md-6">
            <canvas id="navegadoresChart2"></canvas>
         </div>
      </div>
   </div>
</div>
@stop

@section('js')
<script>
   const anosCesta = JSON.parse('{!! json_encode($anosCesta) !!}');
   const cestas = JSON.parse('{!! json_encode($cestasEntreguesPorAno) !!}');
   const ctx = document.getElementById('navegadoresChart').getContext('2d');
   new Chart(ctx, {
      type: 'line',
      data: {
         labels: anosCesta,
         datasets: [{
            label: 'Cestas Entregues',
            data: cestas,
            borderColor: [
               '#28a745 ',
            ],
            fill: false,
            tension: 0.1
         }]
      },
      options: {
         responsive: true,
         plugins: {
            legend: {
               position: 'bottom'
            }
         }
      },
      scales: {
         y: {
            beginAtZero: true,
            ticks: {
               precision: 0,
            }
         }
      }
   });

   const ctx2 = document.getElementById('navegadoresChart2').getContext('2d');
   new Chart(ctx2, {
      type: 'bar',
      data: {
         labels: ['IFES', 'Própria'],
         datasets: [{
            data: [60, 25, 15],
            backgroundColor: [
               '#28a745',
               '#dc3545',
            ]
         }]
      },
      options: {
         responsive: true,
         plugins: {
            legend: {
               position: 'bottom'
            }
         }
      }
   });
</script>
@stop