@extends('adminlte::page')
@section('title', 'Relatório Visual - RECeBa')
@section('content_header')
<h1 class="text-bold">Relatório de Saída de Cesta</h1>
@stop
@section('content')
<div class="card">
   <div class="card-body">
      <div class="row">
         <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap table-striped">
               <thead>
                  <tr>
                     <th>Representante</th>
                     <th>Parceiro</th>
                     @for($day = 11; $day >= 0; $day--)
                     <th class="text-uppercase">
                        {{ Carbon\Carbon::setLocale('pt_BR') }}
                        {{ Carbon\Carbon::now()->subMonths($day)->translatedFormat('M/Y') }}
                     </th>
                     @endfor
                  </tr>
               </thead>
               <tbody>
                  <tr>
                     <td class="align-middle">Paulin</td>
                     <td class="align-middle">Parceiro</td>
                     @for($day = 11; $day >= 0; $day--)
                     <td class="align-middle">
                        {{ rand(1, 100) }}
                     </td>
                     @endfor
                  </tr>
               </tbody>
            </table>
         </div>
      </div>
   </div>
</div>

<div class="card">
   <div class="row justify-content-center">
      <div class="col-md-6">
         <canvas id="navegadoresChart"></canvas>
      </div>
   </div>
</div>
@stop

@section('js')
<script>
   const ctx = document.getElementById('navegadoresChart').getContext('2d');
   new Chart(ctx, {
      type: 'pie',
      data: {
         labels: ['Chrome', 'Firefox', 'Edge'],
         datasets: [{
            data: [60, 25, 15], // valores de exemplo
            backgroundColor: [
               '#4285F4', // Chrome
               '#FF7139', // Firefox
               '#0078D7' // Edge
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