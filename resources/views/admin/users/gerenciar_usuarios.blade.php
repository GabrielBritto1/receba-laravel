@extends('adminlte::page')

@section('title', 'Gerenciar Usuários')
@section('content_header')
<h1 class="text-bold"><i class="fas fa-id-badge"></i> Gerenciar Usuários</h1>
@stop
@section('content')
<div class="card">
   <div class="card-body">
      <select class="form-control" id="id-usuario">
         <option value="" disabled selected>Selecione o usuário</option>
         @forelse($users as $user)
         <option value="{{ $user->id }}">{{ $user->name }}</option>
         @empty
         <option value="">Nenhum usuário cadastrado</option>
         @endforelse
      </select>

      <div class="dados-usuario"></div>
   </div>
</div>
@stop

@section('js')
<script>
   const usuarios = JSON.parse('{!! json_encode($users) !!}');
   $('#id-usuario').on('change', function() {
      const id = $(this).val();
      if (id == '') {
         $('.dados-usuario').html('');
         return;
      }
      const user = usuarios.find(user => user.id == id);
      const userId = (id) => `/users/${id}`;
      $('.dados-usuario').html(`
      <form action="${userId(user.id)}" method="POST">
         <input type="hidden" name="_token" value="{{ csrf_token() }}">
         <input type="hidden" name="_method" value="PUT">
            <div class="row">
               <div class="col">
                  <div class="form-group">
                     <label for="name">Nome</label>
                     <input class="form-control" type="text" name="name" placeholder="Nome" value="${user.name ?? old('name')}">
                  </div>
               </div>
            <div class="col">
               <div class="form-group">
                  <label for="email">Email</label>
                  <input class="form-control" type="email" name="email" placeholder="Email" value="${user.email ?? old('email')}">
               </div>
            </div>
         </div>
         <div class="row">
            <div class="col">
               <div class="form-group">
                  <label for="cpf">CPF</label>
                  <input class="form-control" type="text" name="cpf" maxlength="14" placeholder="CPF" value="">
               </div>
            </div>
            <div class="col">
               <div class="form-group">
                  <label for="telefone">Telefone</label>
                  <input class="form-control" type="text" name="telefone" maxlength="15" placeholder="Telefone" value="">
               </div>
            </div>
         </div>
         <div class="row">
            <div class="col">
               <div class="form-group">
                  <label for="password">Senha</label>
                  <input class="form-control" type="password" name="password" placeholder="Senha">
               </div>
            </div>
         </div>
         <button class="btn btn-success text-bold" type="submit">Salvar</button>
      </form>
      `);
   })
</script>
@stop