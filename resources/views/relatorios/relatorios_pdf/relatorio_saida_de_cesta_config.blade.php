@extends('adminlte::page')

@section('title', 'Configuração de PDF - Saída de Cestas')

@section('content_header')
<h1 class="text-bold">Configuração de PDF</h1>
@stop

@section('content')
<div class="card">
   <div class="card-body">
      <form method="GET" action="{{ route('relatorios_pdf.gerar_relatorio_saida_de_cesta_pdf') }}" target="_blank">
         <div class="row">
            <div class="col-md-4">
               <div class="form-group">
                  <label for="nome_representante">Nome do responsável da família:</label>
                  <input type="text" name="nome_representante" id="nome_representante" class="form-control" value="{{ request('nome_representante') }}">
               </div>
            </div>

            <div class="col-md-4">
               <div class="form-group">
                  <label for="parceiro_id">Parceiros:</label>
                  <select name="parceiro_id" id="parceiro_id" class="form-control" {{ Auth::user()->can('Administrador') ? '' : 'disabled' }}>
                     <option value="">Todos os parceiros</option>
                     @foreach ($parceiros as $parceiro)
                     <option value="{{ $parceiro->id }}" {{ (string) request('parceiro_id') === (string) $parceiro->id ? 'selected' : '' }}>{{ $parceiro->name }}</option>
                     @endforeach
                  </select>
                  @unless (Auth::user()->can('Administrador'))
                  <input type="hidden" name="parceiro_id" value="{{ optional($parceiros->first())->id }}">
                  @endunless
               </div>
            </div>

            <div class="col-md-4">
               <div class="form-group">
                  <label for="ano_selecionado">Selecione o período:</label>
                  <select name="ano_selecionado" id="ano_selecionado" class="form-control">
                     <option value="todos_periodos" {{ request('ano_selecionado', 'todos_periodos') === 'todos_periodos' ? 'selected' : '' }}>Todos os períodos</option>
                     <option value="periodo_atual" {{ request('ano_selecionado') === 'periodo_atual' ? 'selected' : '' }}>Período Atual (Últimos 12 meses)</option>
                     @foreach ($anosDisponiveis as $ano)
                     <option value="{{ $ano }}" {{ (string) request('ano_selecionado') === (string) $ano ? 'selected' : '' }}>Ano de {{ $ano }}</option>
                     @endforeach
                  </select>
               </div>
            </div>
         </div>

         <div class="row">
            <div class="col-md-4">
               <div class="form-group">
                  <label for="data_inicial">Período personalizado - data inicial:</label>
                  <input type="date" name="data_inicial" id="data_inicial" class="form-control" value="{{ request('data_inicial') }}">
               </div>
            </div>

            <div class="col-md-4">
               <div class="form-group">
                  <label for="data_final">Período personalizado - data final:</label>
                  <input type="date" name="data_final" id="data_final" class="form-control" value="{{ request('data_final') }}">
                  <small class="form-text text-muted">Se preenchido, o período personalizado tem prioridade sobre o seletor acima.</small>
               </div>
            </div>
         </div>

         <hr>

         <div class="row">
            <div class="col-md-6">
               <div class="form-group">
                  <label class="d-block">Ordenar por:</label>
                  <div class="form-check form-check-inline">
                     <input class="form-check-input" type="checkbox" name="ordenar_data_entrega" id="ordenar_data_entrega" value="1" {{ request()->has('ordenar_data_entrega') || !request()->hasAny(['ordenar_data_entrega', 'ordem_alfabetica']) ? 'checked' : '' }}>
                     <label class="form-check-label" for="ordenar_data_entrega">Data de Entrega</label>
                  </div>
                  <div class="form-check form-check-inline">
                     <input class="form-check-input" type="checkbox" name="ordem_alfabetica" id="ordem_alfabetica" value="1" {{ request()->has('ordem_alfabetica') || !request()->hasAny(['ordenar_data_entrega', 'ordem_alfabetica']) ? 'checked' : '' }}>
                     <label class="form-check-label" for="ordem_alfabetica">Ordem Alfabética</label>
                  </div>
               </div>
            </div>

            <div class="col-md-6">
               <div class="form-group">
                  <label class="d-block">Ponto de origem:</label>
                  <div class="form-check form-check-inline">
                     <input class="form-check-input" type="checkbox" name="origem_propria" id="origem_propria" value="1" {{ request()->has('origem_propria') || !request()->hasAny(['origem_propria', 'origem_ifes']) ? 'checked' : '' }}>
                     <label class="form-check-label" for="origem_propria">Própria</label>
                  </div>
                  <div class="form-check form-check-inline">
                     <input class="form-check-input" type="checkbox" name="origem_ifes" id="origem_ifes" value="1" {{ request()->has('origem_ifes') || !request()->hasAny(['origem_propria', 'origem_ifes']) ? 'checked' : '' }}>
                     <label class="form-check-label" for="origem_ifes">IFES</label>
                  </div>
               </div>
            </div>
         </div>

         <button type="submit" class="btn btn-success">
            Gerar PDF
         </button>
      </form>
   </div>
</div>
@stop