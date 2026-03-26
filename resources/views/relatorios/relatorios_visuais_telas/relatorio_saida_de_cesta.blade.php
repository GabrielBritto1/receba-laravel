@extends('adminlte::page')

@section('title', 'Relatório de Saída de Cestas')

@section('content_header')
<h1 class="text-bold">Relatório de saída de cestas</h1>
@stop

@section('content')
<div class="card">
   <div class="card-header">
      <h3 class="card-title">Filtros</h3>
   </div>
   <div class="card-body">
      {{-- Formulário de Filtros --}}
      <form method="GET" action="{{ route('relatorios.relatorio_saida_de_cesta') }}">
         <div class="row">
            <div class="col-md-4">
               {{-- Filtro de nome continua visível para todos --}}
               <div class="form-group">
                  <label>Nome do responsável da família:</label>
                  <input type="text" name="nome_representante" class="form-control" value="{{ request('nome_representante') }}">
               </div>
            </div>

            {{-- O filtro de parceiro só aparece se o usuário for admin --}}
            @can('Administrador')
            <div class="col-md-4">
               <div class="form-group">
                  <label>Parceiro da família:</label>
                  <select name="parceiro_id" class="form-control">
                     <option value="">Todos os parceiros</option>
                     @foreach ($parceiros as $parceiro)
                     <option value="{{ $parceiro->id }}" {{ request('parceiro_id') == $parceiro->id ? 'selected' : '' }}>
                        {{ $parceiro->name }}
                     </option>
                     @endforeach
                  </select>
               </div>
            </div>
            @endcan

            <div class="col-md-4">
               <div class="form-group">
                  <label>Selecione o período:</label>
                  {{-- O nome do campo mudou para 'ano_selecionado' --}}
                  <select name="ano_selecionado" class="form-control">
                     <option value="periodo_atual">Período Atual (12 meses)</option>
                     @foreach ($anosDisponiveis as $ano)
                     <option value="{{ $ano }}" {{ request('ano_selecionado') == $ano ? 'selected' : '' }}>
                        Ano de {{ $ano }}
                     </option>
                     @endforeach
                  </select>
               </div>
            </div>
         </div>
         <div class="row">
            <div class="col-md-12">
               <button type="submit" class="btn btn-success float-right">Filtrar</button>
            </div>
         </div>
      </form>
   </div>
</div>

<div class="card">
   <div class="card-body table-responsive">
      <table class="table table-bordered table-hover text-center">
         <thead>
            <tr style="background-color: #f8f9fa;">
               <th class="text-left">Nome do representante</th>
               <th class="text-left">Parceiro</th>
               {{-- Cabeçalho da tabela gerado dinamicamente com os meses --}}
               @foreach ($meses as $mes)
               <th>{{ Str::upper($mes->translatedFormat('M/Y')) }}</th>
               @endforeach
            </tr>
         </thead>
         <tbody>
            @forelse ($familiasAgrupadas as $familiaId => $entregas)
            @php
            // Pegamos os dados da primeira entrega para informações da linha (nome, parceiro)
            $primeiraEntrega = $entregas->first();
            $representante = $primeiraEntrega->familia->representante;
            $parceiro = $primeiraEntrega->parceiro;
            @endphp
            <tr>
               <td class="text-left align-middle">{{ $representante->nome }}</td>
               <td class="text-left align-middle">
                  <span class="badge p-2" style="background-color: {{ $parceiro->sigla->color ?? '#6c757d' }}; color: #fff;">
                     {{ $parceiro->sigla->name ?? $parceiro->name }}
                  </span>
               </td>

               {{-- Células de dados geradas dinamicamente --}}
               @foreach ($meses as $mes)
               <td>
                  {{-- Filtramos as entregas SÓ para o mês atual do loop --}}
                  @php
                  $entregasNoMes = $entregas->filter(function ($entrega) use ($mes) {
                  return $entrega->data_entrega->isSameMonth($mes);
                  });
                  @endphp

                  @if ($entregasNoMes->isNotEmpty())
                  {{-- Se houver entregas, exibe os dias --}}
                  @foreach ($entregasNoMes as $entrega)
                  <span class="badge bg-success font-weight-bold">{{ $entrega->data_entrega->format('d') }}</span>
                  @endforeach
                  @else
                  -
                  @endif
               </td>
               @endforeach
            </tr>
            @empty
            <tr>
               {{-- A quantidade de colunas é 2 (Nome, Parceiro) + a contagem de meses --}}
               <td colspan="{{ 2 + $meses->count() }}" class="text-center">Nenhum dado encontrado para os filtros selecionados.</td>
            </tr>
            @endforelse
         </tbody>
      </table>
   </div>
</div>
@stop