@extends('adminlte::page')
@section('title', 'Painel - RECeBa')
@section('content_header')
<h1>Painel <small class="text-muted" style="font-size:.6em;">visão geral</small></h1>
@endsection
@section('css')
<style>
   .dashboard-chart-card .chart-box {
      position: relative;
      height: 320px;
   }

   .dashboard-chart-card .chart-title {
      font-size: 1rem;
      font-weight: 600;
      margin-bottom: 0.25rem;
      font-family: 'Poppins', sans-serif;
   }

   .dashboard-chart-card .chart-subtitle {
      color: #6c757d;
      font-size: 0.875rem;
      margin-bottom: 1rem;
   }

   @media (max-width: 767.98px) {
      .dashboard-chart-card .chart-box {
         height: 260px;
      }
   }
</style>
@endsection
@section('content')
<div class="row">
   <div class="col-lg-3 col-6">
      <div class="small-box bg-success">
         <div class="inner">
            <h3>{{ $cestas }}</h3>
            <p>Cestas Entregues</p>
         </div>
         <div class="icon">
            <i class="fas fa-shopping-basket"></i>
         </div>
         <a href="{{ route('cestas.index') }}" class="small-box-footer">Mais informações <i class="fas fa-arrow-circle-right"></i></a>
      </div>
   </div>

   @can('Administrador')
   <div class="col-lg-3 col-6">
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

   <div class="col-lg-3 col-6">
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

   <div class="col-lg-3 col-6">
      <div class="small-box bg-warning">
         <div class="inner">
            <h3>{{ $solicitacoesPendentes }}</h3>
            <p>Solicitações Pendentes</p>
         </div>
         <div class="icon">
            <i class="fas fa-clock"></i>
         </div>
         @can('Administrador')
            <a href="{{ route('solicitacoes.gerenciar_solicitacoes') }}" class="small-box-footer">Gerenciar <i class="fas fa-arrow-circle-right"></i></a>
         @else
            <a href="{{ route('solicitacoes.index') }}" class="small-box-footer">Ver solicitações <i class="fas fa-arrow-circle-right"></i></a>
         @endcan
      </div>
   </div>
</div>

<div class="card dashboard-chart-card">
   <div class="card-header">
      <h3 class="card-title">Visão de Entregas</h3>
   </div>
   <div class="card-body">
      <div class="row">
         <div class="col-md-6">
            <div class="chart-title">Evolução mensal</div>
            <div class="chart-subtitle">Últimos 12 meses de cestas entregues.</div>
            <div class="chart-box">
               <canvas id="chart-entregas"></canvas>
            </div>
         </div>
         <div class="col-md-6">
            <div class="chart-title">Origem das entregas</div>
            <div class="chart-subtitle">Distribuição por ponto de origem registrado.</div>
            <div class="chart-box">
               <canvas id="chart-origem"></canvas>
            </div>
         </div>
      </div>
   </div>
</div>

<div class="card dashboard-chart-card mt-0">
   <div class="card-header">
      <h3 class="card-title">Solicitações</h3>
   </div>
   <div class="card-body">
      <div class="row">
         <div class="col-md-8">
            <div class="chart-title">Solicitações criadas por mês</div>
            <div class="chart-subtitle">Últimos 12 meses — cestas e itens.</div>
            <div class="chart-box">
               <canvas id="chart-solicitacoes-mes"></canvas>
            </div>
         </div>
         <div class="col-md-4">
            <div class="chart-title">Distribuição por status</div>
            <div class="chart-subtitle">Situação atual de todas as solicitações.</div>
            <div class="chart-box">
               <canvas id="chart-solicitacoes-status"></canvas>
            </div>
         </div>
      </div>
   </div>
</div>
@stop

@section('js')
<script>
   const chartLabels       = {!! json_encode($chartLabels) !!};
   const chartDeliveries   = {!! json_encode($chartDeliveries) !!};
   const chartOriginLabels = {!! json_encode($chartOriginLabels) !!};
   const chartOriginTotals = {!! json_encode($chartOriginTotals) !!};
   const chartRequestData  = {!! json_encode($chartRequestData) !!};
   const chartStatusLabels = {!! json_encode($chartStatusLabels) !!};
   const chartStatusTotals = {!! json_encode($chartStatusTotals) !!};

   Chart.defaults.global.defaultFontFamily = "'Poppins', 'Segoe UI', sans-serif";
   Chart.defaults.global.defaultFontColor = '#6c757d';
   Chart.defaults.global.elements.line.borderJoinStyle = 'round';
   Chart.defaults.global.elements.line.borderCapStyle = 'round';

   var tooltipDefaults = {
      backgroundColor: '#343a40',
      titleFontColor: '#ffffff',
      bodyFontColor: '#ffffff',
      displayColors: false
   };

   /* ── 1. Linha: Entregas por mês ────────────────────────────── */
   var ctx1 = document.getElementById('chart-entregas').getContext('2d');
   var gradEntregas = ctx1.createLinearGradient(0, 0, 0, 320);
   gradEntregas.addColorStop(0, 'rgba(40,167,69,0.30)');
   gradEntregas.addColorStop(1, 'rgba(40,167,69,0.02)');

   new Chart(ctx1, {
      type: 'line',
      data: {
         labels: chartLabels,
         datasets: [{
            label: 'Cestas Entregues',
            data: chartDeliveries,
            borderColor: '#28a745',
            backgroundColor: gradEntregas,
            borderWidth: 3,
            pointRadius: 4,
            pointHoverRadius: 6,
            pointBackgroundColor: '#ffffff',
            pointBorderColor: '#28a745',
            pointBorderWidth: 2,
            lineTension: 0.35,
            fill: true
         }]
      },
      options: {
         responsive: true,
         maintainAspectRatio: false,
         legend: { display: false },
         tooltips: tooltipDefaults,
         scales: {
            yAxes: [{ ticks: { beginAtZero: true, precision: 0, padding: 10 }, gridLines: { color: 'rgba(0,0,0,0.06)', drawBorder: false } }],
            xAxes: [{ gridLines: { display: false }, ticks: { padding: 8 } }]
         }
      }
   });

   /* ── 2. Donut: Origem das entregas ─────────────────────────── */
   var brandColors = ['#28a745','#dc3545','#fd7e14','#007bff','#6610f2','#17a2b8'];
   var pieColors = (chartOriginLabels.length ? chartOriginLabels : ['Sem dados']).map(function(_, i) {
      return brandColors[i % brandColors.length];
   });

   new Chart(document.getElementById('chart-origem').getContext('2d'), {
      type: 'doughnut',
      data: {
         labels: chartOriginLabels.length ? chartOriginLabels : ['Sem dados'],
         datasets: [{ data: chartOriginTotals.length ? chartOriginTotals : [1], backgroundColor: pieColors, borderWidth: 0 }]
      },
      options: {
         responsive: true,
         maintainAspectRatio: false,
         cutoutPercentage: 58,
         legend: { position: 'bottom', labels: { boxWidth: 12, padding: 18 } },
         tooltips: { backgroundColor: '#343a40', titleFontColor: '#fff', bodyFontColor: '#fff' }
      }
   });

   /* ── 3. Barras: Solicitações por mês ───────────────────────── */
   var ctx3 = document.getElementById('chart-solicitacoes-mes').getContext('2d');
   var gradReq = ctx3.createLinearGradient(0, 0, 0, 320);
   gradReq.addColorStop(0, 'rgba(0,123,255,0.75)');
   gradReq.addColorStop(1, 'rgba(0,123,255,0.35)');

   new Chart(ctx3, {
      type: 'bar',
      data: {
         labels: chartLabels,
         datasets: [{
            label: 'Solicitações',
            data: chartRequestData,
            backgroundColor: gradReq,
            borderColor: '#007bff',
            borderWidth: 1,
            borderRadius: 4
         }]
      },
      options: {
         responsive: true,
         maintainAspectRatio: false,
         legend: { display: false },
         tooltips: tooltipDefaults,
         scales: {
            yAxes: [{ ticks: { beginAtZero: true, precision: 0, padding: 10 }, gridLines: { color: 'rgba(0,0,0,0.06)', drawBorder: false } }],
            xAxes: [{ gridLines: { display: false }, ticks: { padding: 8 } }]
         }
      }
   });

   /* ── 4. Donut: Status das solicitações ─────────────────────── */
   var statusColorMap = {
      'Em Análise': '#fd7e14',
      'Aceita':     '#007bff',
      'Montada':    '#6610f2',
      'Entregue':   '#28a745',
      'Recusada':   '#dc3545'
   };
   var statusColors = (chartStatusLabels.length ? chartStatusLabels : ['Sem dados']).map(function(s) {
      return statusColorMap[s] || '#adb5bd';
   });

   new Chart(document.getElementById('chart-solicitacoes-status').getContext('2d'), {
      type: 'doughnut',
      data: {
         labels: chartStatusLabels.length ? chartStatusLabels : ['Sem dados'],
         datasets: [{ data: chartStatusTotals.length ? chartStatusTotals : [1], backgroundColor: statusColors, borderWidth: 0 }]
      },
      options: {
         responsive: true,
         maintainAspectRatio: false,
         cutoutPercentage: 58,
         legend: { position: 'bottom', labels: { boxWidth: 12, padding: 14 } },
         tooltips: { backgroundColor: '#343a40', titleFontColor: '#fff', bodyFontColor: '#fff' }
      }
   });
</script>
@stop
