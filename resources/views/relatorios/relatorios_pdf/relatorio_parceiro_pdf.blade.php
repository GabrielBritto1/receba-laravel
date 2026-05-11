<!DOCTYPE html>
<html lang="pt-BR">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Relatório de Parceiro</title>

   <style>
      @import url('https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap');

      @page {
         margin: 28px 24px 18px 24px;
      }

      body {
         margin: 0;
         padding: 0;
         font-family: 'Roboto', sans-serif;
         font-size: 12px;
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

      .watermark {
         position: fixed;
         top: 34%;
         left: 50%;
         width: 260px;
         transform: translate(-50%, -50%);
         opacity: 0.08;
         z-index: -1;
      }

      table {
         width: 100%;
         border-collapse: collapse;
         margin-bottom: 12px;
      }

      thead {
         background-color: #f2f2f2;
         display: table-header-group;
      }

      thead th {
         padding: 6px 5px;
         font-size: 11px;
         text-align: center;
      }

      tbody td {
         text-align: center;
         padding: 5px;
         font-size: 11px;
         vertical-align: middle;
      }

      tbody tr:nth-child(even) {
         background-color: #fafafa;
      }

      tr {
         page-break-inside: avoid;
      }

      .col-index   { width: 3%; }
      .col-nome    { width: 22%; text-align: left !important; }
      .col-sigla   { width: 7%; }
      .col-cnpj    { width: 13%; }
      .col-tel     { width: 11%; }
      .col-local   { width: 14%; }
      .col-coord   { width: 16%; text-align: left !important; }
      .col-fam     { width: 6%; }
      .col-status  { width: 8%; }

      .badge {
         display: inline-block;
         padding: 1px 6px;
         border-radius: 4px;
         font-size: 10px;
         font-weight: bold;
         color: #fff;
         background-color: #555;
      }

      .status-ativo   { background-color: #28a745; color: #fff; border-radius: 4px; padding: 1px 6px; font-size: 10px; }
      .status-inativo { background-color: #dc3545; color: #fff; border-radius: 4px; padding: 1px 6px; font-size: 10px; }

      .text-muted { color: #888; font-style: italic; }
   </style>
</head>

<body>
   <img class="watermark" src="{{ public_path('assets/img/banner_vertical_pdf.png') }}" alt="">

   <div class="header">
      <img src="{{ public_path('assets/img/banner_vertical_pdf.png') }}" width="100px" alt="">
      <div class="header-text">
         <h1>Registro de Entrega de Cestas Básicas - RECeBa</h1>
         <i><small>Relatório de Parceiros</small></i>
         <br>
         <i><small>Gerado em: {{ date('d/m/Y') }}</small></i>
      </div>
   </div>

   <div class="content">
      <table border="1" cellpadding="0" cellspacing="0">
         <thead>
            <tr>
               <th class="col-nome">Nome</th>
               <th class="col-sigla">Sigla</th>
               <th class="col-cnpj">CNPJ</th>
               <th class="col-tel">Telefone</th>
               <th class="col-local">Local de Atuação</th>
               <th class="col-coord">Coordenadores</th>
               <th class="col-status">Status</th>
            </tr>
         </thead>
         <tbody>
            @forelse($parceiros as $index => $parceiro)
            <tr>
               <td class="col-nome">{{ $parceiro->name }}</td>
               <td class="col-sigla">
                  @if($parceiro->sigla)
                     <span class="badge" style="background-color: {{ $parceiro->sigla->color ?? '#555' }}">
                        {{ $parceiro->sigla->name }}
                     </span>
                  @else
                     <span class="text-muted">—</span>
                  @endif
               </td>
               <td class="col-cnpj">{{ $parceiro->cnpj_formatado ?: '—' }}</td>
               <td class="col-tel">{{ $parceiro->telefone_formatado ?: '—' }}</td>
               <td class="col-local">{{ $parceiro->local_atuacao ?: '—' }}</td>
               <td class="col-coord">
                  @forelse($parceiro->users as $coordenador)
                     {{ $coordenador->name }}@unless($loop->last), @endunless
                  @empty
                     <span class="text-muted">Sem coordenador</span>
                  @endforelse
               </td>
               <td class="col-status">
                  @if($parceiro->status)
                     <span class="status-ativo">Ativo</span>
                  @else
                     <span class="status-inativo">Inativo</span>
                  @endif
               </td>
            </tr>
            @empty
            <tr>
               <td colspan="9">
                  <strong>Nenhum registro encontrado.</strong>
               </td>
            </tr>
            @endforelse
         </tbody>
      </table>
   </div>
</body>

</html>
