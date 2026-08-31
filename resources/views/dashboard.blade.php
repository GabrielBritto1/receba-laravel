@extends('adminlte::page')
@section('title', 'Painel - RECeBa')
@section('content_header')
<h1></h1>
@endsection
@section('css')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<style>
   .mapa-entregas-intro {
      padding: 1rem 1.25rem 0.75rem;
   }
   .mapa-entregas-title {
      font-family: 'Poppins', sans-serif;
      font-weight: 700;
      font-size: 1.05rem;
      color: #2c3e2f;
      margin-bottom: 0.35rem;
   }
   .mapa-entregas-subtitle {
      font-size: 0.875rem;
      line-height: 1.5;
      color: #6c757d;
      max-width: 62ch;
      margin-bottom: 0;
   }
   .mapa-entregas-wrap {
      position: relative;
   }
   #mapa-entregas {
      height: 460px;
      border-radius: 0 0 4px 4px;
   }
   #mapa-entregas .leaflet-pane,
   #mapa-entregas .leaflet-tile,
   #mapa-entregas .leaflet-marker-icon,
   #mapa-entregas .leaflet-marker-shadow,
   #mapa-entregas .leaflet-tile-container,
   #mapa-entregas .leaflet-map-pane svg,
   #mapa-entregas .leaflet-map-pane canvas,
   #mapa-entregas .leaflet-zoom-box,
   #mapa-entregas .leaflet-image-layer,
   #mapa-entregas .leaflet-layer {
      position: absolute;
   }
   .mapa-legenda {
      background: rgba(255, 255, 255, 0.94);
      border-radius: 6px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.18);
      padding: 0.65rem 0.85rem;
      font-size: 0.78rem;
      color: #495057;
      max-width: 190px;
      margin: 10px;
   }
   .mapa-legenda-titulo {
      font-weight: 700;
      font-size: 0.72rem;
      text-transform: uppercase;
      letter-spacing: 0.03em;
      color: #2c3e2f;
      margin-bottom: 0.45rem;
   }
   .mapa-legenda-item {
      display: flex;
      align-items: center;
      margin-bottom: 0.3rem;
      white-space: nowrap;
   }
   .mapa-legenda-item:last-child {
      margin-bottom: 0;
   }
   .mapa-legenda-dot {
      flex: 0 0 auto;
      border-radius: 50%;
      margin-right: 0.5rem;
   }
   .mapa-legenda-aproximado {
      display: flex;
      align-items: center;
      margin-top: 0.5rem;
      padding-top: 0.5rem;
      border-top: 1px solid rgba(0, 0, 0, 0.08);
      color: #b8860b;
   }
   .mapa-legenda-aproximado .mapa-legenda-dot {
      background: #adb5bd;
      width: 10px;
      height: 10px;
   }
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

<div class="card mt-0">
   <div class="card-header">
      <h3 class="card-title">Mapa de Entregas</h3>
      <div class="card-tools d-flex align-items-center">
         @php $semCoord = $mapaEntregas->where('aproximado', true)->count(); @endphp
         <span class="badge badge-primary mr-1">{{ $mapaEntregas->count() }} {{ Str::plural('endereço', $mapaEntregas->count()) }}</span>
         @if($semCoord > 0)
            <span class="badge badge-warning mr-2">{{ $semCoord }} aproximada(s)</span>
         @endif
         <a href="{{ route('dashboard.entregas.exportar') }}" class="btn btn-sm btn-outline-secondary" title="Baixar relação de entregas (endereço e quantidade) em CSV">
            <i class="fas fa-file-csv mr-1"></i> Baixar dados
         </a>
      </div>
   </div>
   <div class="card-body p-0">
      @if($mapaEntregas->isEmpty())
         <div class="p-4 text-center text-muted">
            <i class="fas fa-map-marker-alt fa-2x mb-2 d-block"></i>
            Nenhuma entrega com coordenadas ainda.<br>
            Execute <code>php artisan familias:geocodificar</code> para geocodificar os endereços.
         </div>
      @else
         <div class="mapa-entregas-intro">
            <p class="mapa-entregas-title">Distribuição das Cestas Básicas Entregues</p>
            <p class="mapa-entregas-subtitle">
               Cada círculo representa um endereço de entrega — quanto maior e mais escuro, mais cestas foram entregues ali.
               @if($bairroConcentracao)
                  A maior concentração de atendimento está no bairro <strong>{{ $bairroConcentracao['bairro'] }}</strong>,
                  com {{ $bairroConcentracao['total'] }} {{ Str::plural('cesta', $bairroConcentracao['total']) }} entregues.
               @endif
            </p>
         </div>
         <div class="mapa-entregas-wrap">
            <div id="mapa-entregas"></div>
         </div>
      @endif
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
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
   document.addEventListener('DOMContentLoaded', function () {
      var pontos = {!! json_encode($mapaEntregas->values()) !!};
      var el = document.getElementById('mapa-entregas');

      if (!el || pontos.length === 0) return;

      // Escala de cor (azul claro → azul escuro) por quantidade de cestas entregues no endereço,
      // no mesmo espírito de um mapa de símbolos proporcionais: quanto mais escuro e maior, mais concentrado.
      // Azul foi escolhido por contrastar bem com o mapa base (que já usa laranja/vermelho nas vias
      // e verde nas áreas vegetadas) — evita que os círculos se percam no fundo do mapa.
      var rampa = ['#86b6ef', '#5598e7', '#2a78d6', '#1c5cab', '#104281'];
      var qtds = pontos.map(function (p) { return p.qtd; });
      var qtdMax = Math.max.apply(null, qtds);
      var qtdMin = Math.min.apply(null, qtds);

      // Faixas (breaks) dinâmicas com base nos dados reais, sem repetir o mesmo limite em mais de uma cor.
      var faixas = [];
      for (var i = 1; i <= rampa.length; i++) {
         var limite = Math.round(qtdMin + (qtdMax - qtdMin) * (i / rampa.length));
         if (faixas.indexOf(limite) === -1) faixas.push(limite);
      }
      faixas[faixas.length - 1] = qtdMax;
      var cores = rampa.slice(0, faixas.length);

      function corPara(qtd) {
         for (var i = 0; i < faixas.length; i++) {
            if (qtd <= faixas[i]) return cores[i];
         }
         return cores[cores.length - 1];
      }

      var raioMin = 7, raioMax = 30;
      function raioPara(qtd) {
         if (qtdMax === qtdMin) return (raioMin + raioMax) / 2;
         var proporcao = Math.sqrt((qtd - qtdMin) / (qtdMax - qtdMin));
         return raioMin + (raioMax - raioMin) * proporcao;
      }

      var mapa = L.map(el, { scrollWheelZoom: false });

      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
         attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
         maxZoom: 18,
      }).addTo(mapa);

      var grupo = L.featureGroup();
      var temAproximado = false;

      pontos.forEach(function (f) {
         if (f.aproximado) temAproximado = true;

         var circulo = L.circleMarker([f.lat, f.lng], {
            radius: raioPara(f.qtd),
            fillColor: corPara(f.qtd),
            fillOpacity: f.aproximado ? 0.55 : 0.82,
            color: '#ffffff',
            weight: f.aproximado ? 1.5 : 2,
            dashArray: f.aproximado ? '3, 3' : null,
         });

         var popup = '<strong>' + f.nome + '</strong>'
            + '<br>' + f.qtd + ' ' + (f.qtd === 1 ? 'cesta entregue' : 'cestas entregues')
            + (f.end ? '<br><small>' + f.end + '</small>' : '')
            + (f.ultima ? '<br><small class="text-muted">Última entrega: ' + f.ultima + '</small>' : '')
            + (f.aproximado ? '<br><small class="text-warning"><i>⚠ Localização aproximada (centro de Alegre)</i></small>' : '');

         circulo.bindPopup(popup);
         circulo.addTo(grupo);
      });

      grupo.addTo(mapa);

      // Enquadra a área de maior concentração (mediana dos pontos) em vez de todos os pontos:
      // um único endereço geocodificado errado e muito distante não deve forçar o zoom para fora da região atendida.
      function mediana(valores) {
         var ordenados = valores.slice().sort(function (a, b) { return a - b; });
         var meio = Math.floor(ordenados.length / 2);
         return ordenados.length % 2 ? ordenados[meio] : (ordenados[meio - 1] + ordenados[meio]) / 2;
      }

      var latMediana = mediana(pontos.map(function (p) { return p.lat; }));
      var lngMediana = mediana(pontos.map(function (p) { return p.lng; }));
      var pontosCentrais = pontos.filter(function (p) {
         return Math.abs(p.lat - latMediana) < 0.5 && Math.abs(p.lng - lngMediana) < 0.5;
      });
      var pontosParaEnquadrar = pontosCentrais.length > 0 ? pontosCentrais : pontos;

      mapa.fitBounds(L.latLngBounds(pontosParaEnquadrar.map(function (p) { return [p.lat, p.lng]; })).pad(0.15));
      mapa.invalidateSize();

      // Legenda de símbolos proporcionais (tamanho + cor = quantidade de cestas entregues no local)
      var Legenda = L.Control.extend({
         options: { position: 'topleft' },
         onAdd: function () {
            var div = L.DomUtil.create('div', 'mapa-legenda');
            var html = '<div class="mapa-legenda-titulo">Cestas entregues</div>';
            var anterior = qtdMin;

            faixas.forEach(function (limite, i) {
               var rotulo = (i === 0)
                  ? (anterior === limite ? String(limite) : anterior + '–' + limite)
                  : (faixas[i - 1] + 1) + (limite > faixas[i - 1] + 1 ? '–' + limite : '');
               var raioAmostra = 6 + i * 3;

               html += '<div class="mapa-legenda-item">'
                  + '<span class="mapa-legenda-dot" style="width:' + (raioAmostra * 2) + 'px;height:' + (raioAmostra * 2) + 'px;background:' + cores[i] + ';"></span>'
                  + '<span>' + rotulo + '</span>'
                  + '</div>';
            });

            if (temAproximado) {
               html += '<div class="mapa-legenda-aproximado"><span class="mapa-legenda-dot"></span><span>Localização aproximada</span></div>';
            }

            div.innerHTML = html;
            L.DomEvent.disableClickPropagation(div);
            return div;
         }
      });

      mapa.addControl(new Legenda());
   });
</script>
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
