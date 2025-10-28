@extends('adminlte::page')

@section('title', 'Relatório de Parceiros')

@section('content_header')
<h1 class="text-bold">Relatório dos parceiros</h1>
@stop

@section('content')
{{-- Card de Filtros --}}
<div class="card">
   <div class="card-header">
      <h3 class="card-title">Filtros</h3>
   </div>
   <div class="card-body">
      <form method="GET" action="{{ route('relatorios.relatorio_parceiro') }}">
         <div class="row d-flex align-items-center">
            {{-- Filtro de Nome --}}
            <div class="col-md-5">
               <div class="form-group">
                  <label for="nome_parceiro">Nome do parceiro:</label>
                  <input type="text" name="nome_parceiro" id="nome_parceiro" class="form-control"
                     value="{{ request('nome_parceiro') }}" placeholder="Digite o nome do parceiro">
               </div>
            </div>

            {{-- Filtro de Status --}}
            <div class="col-md-5">
               <div class="form-group">
                  <label>Status da parceria:</label>
                  <div class="d-flex">
                     <div class="form-check mr-3">
                        <input class="form-check-input" type="radio" name="status_parceria" id="status_ativo" value="ativo"
                           {{ request('status_parceria') == 'ativo' ? 'checked' : '' }}>
                        <label class="form-check-label" for="status_ativo">Ativo</label>
                     </div>
                     <div class="form-check mr-3">
                        <input class="form-check-input" type="radio" name="status_parceria" id="status_inativo" value="inativo"
                           {{ request('status_parceria') == 'inativo' ? 'checked' : '' }}>
                        <label class="form-check-label" for="status_inativo">Inativo</label>
                     </div>
                     <div class="form-check">
                        <input class="form-check-input" type="radio" name="status_parceria" id="status_todos" value="todos"
                           {{ !request()->filled('status_parceria') || request('status_parceria') == 'todos' ? 'checked' : '' }}>
                        <label class="form-check-label" for="status_todos">Todos</label>
                     </div>
                  </div>
               </div>
            </div>

            {{-- Botão de Filtrar --}}
            <div class="col-md-2">
               <div class="form-group pt-4">
                  <button type="submit" class="btn btn-success btn-block">
                     <i class="fas fa-search"></i> Filtrar
                  </button>
               </div>
            </div>
         </div>
      </form>
   </div>
</div>

{{-- Card da Tabela de Resultados --}}
<div class="card">
   <div class="card-body table-responsive p-0">
      <table class="table table-hover table-striped">
         <thead>
            <tr>
               <th>Nome do Parceiro</th>
               <th>Telefone</th>
               <th style="width: 10%;">Status</th>
            </tr>
         </thead>
         <tbody>
            @forelse ($parceiros as $parceiro)
            <tr>
               <td class="align-middle">{{ $parceiro->name }}</td>
               <td class="align-middle">{{ $parceiro->telefone }}</td>
               <td class="align-middle">
                  @if ($parceiro->status == 1)
                  <span class="badge badge-success text-uppercase" style="width: 100%; padding: 8px 0;">Ativo</span>
                  @else
                  <span class="badge badge-danger text-uppercase" style="width: 100%; padding: 8px 0;">Inativo</span>
                  @endif
               </td>
            </tr>
            @empty
            <tr>
               <td colspan="3" class="text-center">Nenhum parceiro encontrado para os filtros selecionados.</td>
            </tr>
            @endforelse
         </tbody>
      </table>
   </div>
</div>
@stop