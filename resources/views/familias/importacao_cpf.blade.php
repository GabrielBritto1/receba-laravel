@extends('adminlte::page')
@section('title', 'Detalhes da Família')
@section('content_header')
{{-- Usamos o nome do representante no título para clareza --}}
<h1 class="text-bold"><i class="fas fa-user-plus"></i> Associar Família de: {{ $familia->representante->nome }}</h1>
@stop

@section('content')
<div class="card">
   <div class="card-header">
      <h3 class="card-title">Confirmar Associação ao seu Parceiro</h3>
   </div>
   <div class="card-body">

      <div class="alert alert-info">
         <h5 class="alert-heading"><i class="icon fas fa-info"></i> Atenção!</h5>
         <p>O representante com o CPF **{{ $familia->representante->cpf }}** já está cadastrado no sistema.</p>
         <p class="mb-0">Revise os dados abaixo. Se estiver tudo correto, clique no botão "Sim, Associar ao Meu Parceiro" para criar um vínculo desta família com o seu parceiro.</p>
      </div>

      {{-- Formulário que será enviado para o método importStore --}}
      <form action="{{ route('familias.import.store') }}" method="POST">
         @csrf
         {{-- Enviamos o ID do representante para que o controller saiba quem associar --}}
         <input type="hidden" name="representante_id" value="{{ $familia->representante->id }}">

         {{-- DADOS DO REPRESENTANTE (DESABILITADO) --}}
         <h5 class="text-bold bg-light p-2 rounded mt-4">1. Dados do Representante</h5>
         <div class="row">
            <div class="col-md-6 form-group">
               <label>Nome Completo:</label>
               <input type="text" class="form-control" value="{{ $familia->representante->nome }}" disabled>
            </div>
            <div class="col-md-3 form-group">
               <label>CPF:</label>
               <input type="text" class="form-control" value="{{ $familia->representante->cpf }}" disabled>
            </div>
            <div class="col-md-3 form-group">
               <label>Data de Nascimento:</label>
               <input type="text" class="form-control" value="{{ $familia->representante->data_nascimento->format('d/m/Y') }}" disabled>
            </div>
         </div>

         {{-- DADOS DO CÔNJUGE (DESABILITADO) --}}
         <h5 class="text-bold bg-light p-2 rounded mt-3">2. Dados do Cônjuge</h5>
         <div class="row">
            <div class="col-md-6 form-group">
               <label>Nome do Cônjuge:</label>
               <input type="text" class="form-control" value="{{ $familia->representante->nome_conjuge ?? 'Não informado' }}" disabled>
            </div>
            <div class="col-md-3 form-group">
               <label>CPF do Cônjuge:</label>
               <input type="text" class="form-control" value="{{ $familia->representante->cpf_conjuge ?? 'Não informado' }}" disabled>
            </div>
            <div class="col-md-3 form-group">
               <label>Data de Nasc. do Cônjuge:</label>
               <input type="text" class="form-control" value="{{ $familia->representante->data_nascimento_conjuge?->format('d/m/Y') ?? 'Não informado' }}" disabled>
            </div>
         </div>

         {{-- DADOS DA FAMÍLIA (ENDEREÇO, ETC - DESABILITADO) --}}
         <h5 class="text-bold bg-light p-2 rounded mt-3">3. Dados da Família</h5>
         <div class="row">
            <div class="col-md-8 form-group">
               <label>Endereço:</label>
               <input type="text" class="form-control" value="{{ $familia->endereco }}" disabled>
            </div>
            <div class="col-md-4 form-group">
               <label>Número:</label>
               <input type="text" class="form-control" value="{{ $familia->numero_casa }}" disabled>
            </div>
            <div class="col-md-6 form-group">
               <label>Bairro:</label>
               <input type="text" class="form-control" value="{{ $familia->bairro }}" disabled>
            </div>
            <div class="col-md-6 form-group">
               <label>Cidade:</label>
               <input type="text" class="form-control" value="{{ $familia->cidade }}" disabled>
            </div>
         </div>

         {{-- Você pode adicionar mais seções aqui para exibir membroFamilia e rendaFamilia se desejar --}}

         <div class="mt-4">
            <a href="{{ route('familias.index') }}" class="btn btn-secondary">Cancelar e Voltar</a>
            <button type="submit" class="btn btn-success float-right">
               <i class="fas fa-check"></i> Sim, Associar ao Meu Parceiro
            </button>
         </div>
      </form>
   </div>
</div>
@stop