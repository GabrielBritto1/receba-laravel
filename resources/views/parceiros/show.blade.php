@extends('adminlte::page')
@section('title', 'Parceiros - RECeBa')
@section('content_header')
<h1 class="text-bold"><i class="fas fa-address-card"></i> Parceiro - {{ $parceiro->name }}</h1>
@stop

@section('content')
<div class="card">
   <div class="card-body">
      <div class="row mb-2">
         <div class="col">
            <label for="name">Nome</label>
            <input disabled class="form-control" type="text" name="name" placeholder="Nome" value="{{ $parceiro->name ?? old('name') }}">
         </div>
         <div class="col">
            <label for="endereco">Endereço</label>
            <input disabled class="form-control" type="text" name="endereco" placeholder="Endereço" value="{{ $parceiro->endereco ?? old('endereco') }}">
         </div>
      </div>
      <div class="row mb-2">
         <div class="col">
            <label for="telefone">Telefone</label>
            <input disabled class="form-control" type="text" name="telefone" placeholder="Telefone" value="{{ $parceiro->telefone ?? old('telefone') }}">
         </div>
         <div class="col">
            <label for="cep">CEP</label>
            <input disabled class="form-control" type="text" name="cep" placeholder="CEP" value="{{ $parceiro->cep ?? old('cep') }}">
         </div>
      </div>
      <div class="row mb-2">
         <div class="col">
            <label for="cnpj">CNPJ</label>
            <input disabled class="form-control" type="text" name="cnpj" placeholder="CNPJ" value="{{ $parceiro->cnpj ?? old('cnpj') }}">
         </div>
      </div>
      <div class="row mb-2">
         <div class="col">
            @if ($parceiro->users->count() > 1)
            <label for="users">Coordenadores(as)</label>
            @else
            <label for="users">Coordenador(a)</label>
            @endif
            @forelse ($parceiro->users as $coordenador)
            <input disabled class="form-control" type="text" name="users" placeholder="Coordenadores(as)" value="{{ $coordenador->name }}">
            @empty
            <p>Não há coordenadores</p>
            @endforelse
         </div>
         <div class="col">
            @if ($parceiro->users->count() > 1)
            <label for="users">Secretários(as)</label>
            @else
            <label for="users">Secretário(a)</label>
            @endif
            @forelse ($parceiro->users as $secretario)
            <input disabled class="form-control" type="text" name="users" placeholder="Secretários(as)" value="">
            @empty
            <p>Não há Secretários(as)</p>
            @endforelse
         </div>
      </div>
   </div>
</div>
@stop