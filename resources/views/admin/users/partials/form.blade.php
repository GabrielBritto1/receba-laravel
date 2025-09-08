<x-alert />

@csrf
<div class="row">
   <div class="col">
      <div class="form-group">
         <label for="name">Nome</label>
         <input class="form-control" type="text" name="name" placeholder="Nome" value="{{ $user->name ?? old('name') }}">
      </div>
   </div>
   <div class="col">
      <div class="form-group">
         <label for="email">Email</label>
         <input class="form-control" type="email" name="email" placeholder="Email" value="{{ $user->email ??old('email') }}">
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