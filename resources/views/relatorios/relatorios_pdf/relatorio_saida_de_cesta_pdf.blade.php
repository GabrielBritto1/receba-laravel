<!DOCTYPE html>
<html lang="pt-BR">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Relatório de Saída de Cestas</title>

   <style>
      @import url('https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap');

      @page {
         margin: 28px 24px 18px 24px;
      }

      body {
         margin: 0;
         padding: 0;
         font-family: 'Roboto', sans-serif;
      }

      .header {
         display: flex;
         margin-bottom: 20px;
      }

      .header img {
         position: absolute;
         opacity: 0.7;
      }

      .header-text {
         text-align: center;
         font-size: 15px;
         width: 100%;
      }

      thead {
         background-color: #f2f2f2;
         display: table-header-group;
      }

      tbody td {
         text-align: center;
      }

      table {
         margin-bottom: 12px;
      }

      tr {
         page-break-inside: avoid;
      }

      .watermark {
         position: fixed;
         top: 34%;
         left: 50%;
         width: 260px;
         transform: translate(-50%, -50%);
         opacity: 0.08;
         z-index: -1;
      }
   </style>
</head>

<body>
   <img class="watermark" src="{{ public_path('assets/img/banner_vertical_pdf.png') }}" alt="">

   <div class="header">
      <img src="{{ public_path('assets/img/banner_vertical_pdf.png') }}" width="100px" alt="">
      <div class="header-text">
         <h1>Registro de Entrega de Cestas Básicas - RECeBa</h1>
         <i><small>Relatório de Saída de Cestas</small></i>
         <br>
         <i><small>Gerado em: {{ date('d/m/Y') }}</small></i>
      </div>
   </div>

   <div class="content">
      @forelse($entregas as $entregasChunk)
      <table border="1" cellpadding="5" cellspacing="0" style="width: 100%; border-collapse: collapse;">
         <thead>
            <tr>
               <th>Família</th>
               <th>Parceiro</th>
               <th>Origem</th>
               <th>Data de Saída</th>
               <th>Data de Entrega</th>
               <th>Data de Saída IFES</th>
            </tr>
         </thead>
         <tbody>
            @foreach($entregasChunk as $entrega)
            <tr>
               <td>{{ $entrega['representante_nome'] }}</td>
               <td>{{ $entrega['parceiro_nome'] }}</td>
               <td>{{ $entrega['origem'] }}</td>
               <td>{{ $entrega['data_saida'] }}</td>
               <td>{{ $entrega['data_entrega'] }}</td>
               <td>{{ $entrega['data_saida_ifes'] }}</td>
            </tr>
            @endforeach
         </tbody>
      </table>
      @empty
      <table border="1" cellpadding="5" cellspacing="0" style="width: 100%; border-collapse: collapse;">
         <thead>
            <tr>
               <th>Família</th>
               <th>Parceiro</th>
               <th>Origem</th>
               <th>Data de Saída</th>
               <th>Data de Entrega</th>
               <th>Data de Saída IFES</th>
            </tr>
         </thead>
         <tbody>
            <tr>
               <td colspan="6">
                  <strong>Nenhum registro encontrado.</strong>
               </td>
            </tr>
         </tbody>
      </table>
      @endforelse
   </div>
</body>

</html>
