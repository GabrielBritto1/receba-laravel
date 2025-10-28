@extends('adminlte::page')
@section('title', 'Parceiros - RECeBa')
@section('content_header')
<h1 class="text-bold"><i class="fas fa-address-card"></i> Parceiro - {{ $parceiro->name }}</h1>
@stop

@section('content')
<div class="card">
   <div class="card-body">
      <form action="{{ route('parceiros.update', $parceiro->id) }}" method="POST">
         @csrf
         @method('PUT')
         <div class="form-group">
            <div class="row">
               <div class="col">
                  <label for="name">Nome</label>
                  <input class="form-control" type="text" name="name" placeholder="Nome" value="{{ $parceiro->name ?? old('name') }}">
               </div>
               <div class="col">
                  <label for="endereco">Endereço</label>
                  <input class="form-control" type="text" name="endereco" placeholder="Endereço" value="{{ $parceiro->endereco ?? old('endereco') }}">
               </div>
            </div>
         </div>
         <div class="form-group">
            <div class="row">
               <div class="col">
                  <label for="telefone">Telefone</label>
                  <input class="form-control" type="text" name="telefone" placeholder="Telefone" value="{{ $parceiro->telefone ?? old('telefone') }}">
               </div>
               <div class="col">
                  <label for="cep">CEP</label>
                  <input class="form-control" type="text" name="cep" placeholder="CEP" value="{{ $parceiro->cep ?? old('cep') }}">
               </div>
            </div>
         </div>
         <div class="form-group">
            <div class="row">
               <div class="col">
                  <label for="cnpj">CNPJ</label>
                  <input class="form-control" type="text" name="cnpj" placeholder="CNPJ" value="{{ $parceiro->cnpj ?? old('cnpj') }}">
               </div>
            </div>
         </div>
         <div class="modal-footer">
            <button type="submit" class="btn btn-success">Editar Parceiro - "{{ $parceiro->name }}"</button>
         </div>
      </form>
   </div>
</div>
@stop