@extends('adminlte::page')

@section('title', 'Relatório de Saída de Cesta por Parceiro')

@section('content_header')
<h1 class="text-bold">Relatório de saída de cestas por parceiro</h1>
@stop

@section('content')
<div class="card">
   <div class="card-header">
      <h3 class="card-title">Filtros</h3>
   </div>
   <div class="card-body">
      <form method="GET" action="{{ route('relatorios.relatorio_saida_de_cesta_por_parceiro') }}">
         <div class="row align-items-end">
            @can('Administrador')
            <div class="col-md-4">
               <div class="form-group">
                  <label for="parceiro_id">Parceiro:</label>
                  <select name="parceiro_id" id="parceiro_id" class="form-control">
                     <option value="">Todos os parceiros</option>
                     @foreach ($parceiros as $parceiro)
                     <option value="{{ $parceiro->id }}" {{ (string) request('parceiro_id') === (string) $parceiro->id ? 'selected' : '' }}>
                        {{ $parceiro->name }}
                     </option>
                     @endforeach
                  </select>
               </div>
            </div>
            @endcan

            <div class="col-md-4">
               <div class="form-group">
                  <label for="ano_selecionado">Selecione o período:</label>
                  <select name="ano_selecionado" id="ano_selecionado" class="form-control">
                     <option value="periodo_atual">Período Atual (12 meses)</option>
                     @foreach ($anosDisponiveis as $ano)
                     <option value="{{ $ano }}" {{ (string) request('ano_selecionado') === (string) $ano ? 'selected' : '' }}>
                        Ano de {{ $ano }}
                     </option>
                     @endforeach
                  </select>
               </div>
            </div>

            <div class="col-md-3">
               <div class="form-group">
                  <label for="ponto_origem">Ponto de origem:</label>
                  <select name="ponto_origem" id="ponto_origem" class="form-control">
                     <option value="">Todos os pontos</option>
                     @foreach ($pontosOrigem as $pontoOrigem)
                     <option value="{{ $pontoOrigem }}" {{ request('ponto_origem') === $pontoOrigem ? 'selected' : '' }}>
                        {{ $pontoOrigem }}
                     </option>
                     @endforeach
                  </select>
               </div>
            </div>

            <div class="col-md-1">
               <div class="form-group">
                  <button type="submit" class="btn btn-success btn-block">
                     <i class="fas fa-search"></i>
                  </button>
               </div>
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
               <th class="text-left">Parceiro</th>
               @foreach ($meses as $mes)
               <th>{{ Str::upper($mes->translatedFormat('M/Y')) }}</th>
               @endforeach
               <th>Total</th>
            </tr>
         </thead>
         <tbody>
            @forelse ($entregasAgrupadas as $entregas)
            @php
            $primeiraEntrega = $entregas->first();
            $parceiro = $primeiraEntrega->parceiro;
            @endphp
            <tr>
               <td class="text-left align-middle">
                  <span class="badge p-2" style="background-color: {{ optional(optional($parceiro)->sigla)->color ?? '#6c757d' }}; color: #fff;">
                     {{ optional(optional($parceiro)->sigla)->name ?? $parceiro->name ?? 'Sem parceiro' }}
                  </span>
               </td>

               @foreach ($meses as $mes)
               @php
               $entregasNoMes = $entregas->filter(function ($entrega) use ($mes) {
                  return $entrega->data_entrega->isSameMonth($mes);
               });
               @endphp
               <td class="align-middle">
                  @if ($entregasNoMes->isNotEmpty())
                  <span class="badge bg-success font-weight-bold">{{ $entregasNoMes->count() }}</span>
                  @else
                  -
                  @endif
               </td>
               @endforeach

               <td class="align-middle font-weight-bold">{{ $entregas->count() }}</td>
            </tr>
            @empty
            <tr>
               <td colspan="{{ 2 + $meses->count() }}" class="text-center">Nenhum dado encontrado para os filtros selecionados.</td>
            </tr>
            @endforelse
         </tbody>
      </table>
   </div>
</div>
@stop
