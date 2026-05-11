@extends('adminlte::page')

@section('title', 'Relatório de Famílias')

@section('content_header')
<h1 class="text-bold">Relatório de famílias</h1>
@stop

@section('content')
<div class="card">
   <div class="card-header">
      <h3 class="card-title">Filtros</h3>
   </div>
   <div class="card-body">
      <form method="GET" action="{{ route('relatorios.relatorio_familia') }}">
         <div class="row align-items-end">
            <div class="col-md-4">
               <div class="form-group">
                  <label for="nome_representante">Nome do representante:</label>
                  <input type="text" name="nome_representante" id="nome_representante" class="form-control" value="{{ request('nome_representante') }}" placeholder="Digite o nome do representante">
               </div>
            </div>

            @can('Administrador')
            <div class="col-md-4">
               <div class="form-group">
                  <label for="parceiro_id">Parceiros:</label>
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

            <div class="col-md-3">
               <div class="form-group">
                  <label>Status da família:</label>
                  <div class="d-flex flex-column">
                     <div class="form-check">
                        <input class="form-check-input" type="radio" name="status_familia" id="status_ativa" value="ativa" {{ request('status_familia') === 'ativa' ? 'checked' : '' }}>
                        <label class="form-check-label" for="status_ativa">Ativa</label>
                     </div>
                     <div class="form-check">
                        <input class="form-check-input" type="radio" name="status_familia" id="status_inativa" value="inativa" {{ request('status_familia') === 'inativa' ? 'checked' : '' }}>
                        <label class="form-check-label" for="status_inativa">Inativa</label>
                     </div>
                     <div class="form-check">
                        <input class="form-check-input" type="radio" name="status_familia" id="status_todas" value="todos" {{ !request()->filled('status_familia') || request('status_familia') === 'todos' ? 'checked' : '' }}>
                        <label class="form-check-label" for="status_todas">Todas</label>
                     </div>
                  </div>
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
   <div class="card-body table-responsive p-0">
      <table class="table table-hover table-striped">
         <thead>
            <tr>
               <th>Nome do representante</th>
               <th>Parceiro</th>
               <th>Telefone</th>
               <th style="width: 12%;">Status</th>
            </tr>
         </thead>
         <tbody>
            @forelse ($familias as $familia)
            @php
            $representante = $familia->representante;
            $parceiro = $familia->parceiro;
            @endphp
            <tr>
               <td class="align-middle">{{ $representante->nome ?? '-' }}</td>
               <td class="align-middle">
                  <span class="badge p-2" style="background-color: {{ optional(optional($parceiro)->sigla)->color ?? '#6c757d' }}; color: #fff;">
                     {{ optional(optional($parceiro)->sigla)->name ?? $parceiro->name ?? 'Sem parceiro' }}
                  </span>
               </td>
               <td class="align-middle">{{ $representante->telefone ?? '-' }}</td>
               <td class="align-middle">
                  @if ($familia->status)
                  <span class="badge badge-success text-uppercase" style="width: 100%; padding: 8px 0;">Ativa</span>
                  @else
                  <span class="badge badge-danger text-uppercase" style="width: 100%; padding: 8px 0;">Inativa</span>
                  @endif
               </td>
            </tr>
            @empty
            <tr>
               <td colspan="4" class="text-center">Nenhuma família encontrada para os filtros selecionados.</td>
            </tr>
            @endforelse
         </tbody>
      </table>
   </div>
</div>
@stop
