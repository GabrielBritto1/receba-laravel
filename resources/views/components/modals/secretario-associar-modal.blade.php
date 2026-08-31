<div class="modal fade" id="associarSecretario" tabindex="-1" aria-labelledby="associarSecretario" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="associarSecretario">Cadastrar Secretário(a)</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            @if ($errors->any() && old('form_origin') === 'secretario_associar')
            <div class="alert alert-danger">
               <h5 class="text-bold"><i class="fas fa-exclamation-triangle mr-1"></i>Corrija os campos abaixo:</h5>
               <ul class="mb-0">
                  @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                  @endforeach
               </ul>
            </div>
            @endif
            <form action="{{ route('parceiros.storeSecretarioAssociar') }}" method="POST">
               @csrf
               <input type="hidden" name="form_origin" value="secretario_associar">
               <input type="hidden" name="parceiro_id" id="parceiro_id" value="{{ old('form_origin') === 'secretario_associar' ? old('parceiro_id') : '' }}">
               <div class="form-group">
                  <label for="name">Nome</label>
                  <input type="text" class="form-control" id="name" name="name" placeholder="Nome do(a) secretário(a)" value="{{ old('form_origin') === 'secretario_associar' ? old('name') : '' }}" required>
               </div>
               <div class="form-group">
                  <label for="email">Email</label>
                  <input type="email" class="form-control" id="email" name="email" placeholder="Email do(a) secretário(a)" value="{{ old('form_origin') === 'secretario_associar' ? old('email') : '' }}" required>
               </div>
               <div class="form-group">
                  <label for="endereco">Endereço</label>
                  <input type="text" class="form-control" id="endereco" name="endereco" placeholder="Endereço do(a) secretário(a)" value="{{ old('form_origin') === 'secretario_associar' ? old('endereco') : '' }}" required>
               </div>
               <div class="form-group">
                  <div class="row">
                     <div class="col">
                        <label for="cpf">CPF</label>
                        <input type="text" class="form-control" id="cpf" name="cpf" placeholder="CPF do(a) secretário(a)" value="{{ old('form_origin') === 'secretario_associar' ? old('cpf') : '' }}" required>
                     </div>
                     <div class="col">
                        <label for="telefone">Telefone</label>
                        <input type="text" class="form-control" id="telefone" name="telefone" placeholder="Telefone do(a) secretário(a)" value="{{ old('form_origin') === 'secretario_associar' ? old('telefone') : '' }}" required>
                     </div>
                  </div>
               </div>
         </div>
         <div class="modal-footer">
            <button type="submit" class="btn btn-success">Cadastrar Secretário(a)</button>
         </div>
         </form>
      </div>
   </div>
</div>