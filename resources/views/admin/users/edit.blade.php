@extends('adminlte::page')
@section('title', 'Editar o Usuário')
@section('content_header')
<h1 class="text-bold"><i class="fas fa-user-edit"></i> Editar Usuário {{ $user->name }}</h1>
@stop

@section('content')
<div class="card">
   <div class="card-body">
      <form action="{{ route('users.update', $user->id) }}" method="POST">
         @method('PUT')
         @include('admin.users.partials.form')
      </form>
   </div>
</div>
@stop