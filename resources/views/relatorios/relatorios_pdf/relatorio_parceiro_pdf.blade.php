<!DOCTYPE html>
<html lang="pt-BR">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Relatório de Parceiro</title>

   <style>
      @import url('https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap');

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
      }

      thead {
         background-color: #f2f2f2;
      }

      tbody td {
         text-align: center;
      }
   </style>
</head>

<body>
   <div class="header">
      <img src="{{ public_path('assets/img/banner_vertical_pdf.png') }}" width="100px" alt="">
      <div class="header-text">
         <h1>Registro de Entrega de Cestas Básicas - RECeBa</h1>
         <i><small>Relatório de Parceiro</small></i>
         <br>
         <i><small>Gerado em: {{ date('d/m/Y') }}</small></i>
      </div>
   </div>

   <div class="content">
      <table border="1" cellpadding="5" cellspacing="0" style="width: 100%; border-collapse: collapse;">
         <thead>
            <tr>
               <th>Nome</th>
               <th>Coordenador</th>
               <th>Sigla</th>
            </tr>
         </thead>
         <tbody>
            @forelse($parceiros as $parceiro)
            <tr>
               <td>{{ $parceiro->name }}</td>
               @forelse($parceiro->users as $coordenador)
               <td>{{ $coordenador->name }}</td>
               @empty
               Sem usuário
               @endforelse
               <td>{{ $parceiro->sigla?->name }}</td>
            </tr>
            @empty
            <tr>
               <td colspan="3">
                  <strong>Nenhum registro encontrado.</strong>
               </td>
            </tr>
            @endforelse
         </tbody>
      </table>
   </div>
</body>

</html>