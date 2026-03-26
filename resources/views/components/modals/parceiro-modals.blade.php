<div class="modal fade" id="cadastrarCoordenador" tabindex="-1" aria-labelledby="cadastrarCoordenador" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="cadastrarCoordenador">Cadastrar Coordenador</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            <form action="{{ route('parceiros.storeCoordenador') }}" method="POST">
               @csrf
               <div class="form-group">
                  <label for="name">Nome</label>
                  <input type="text" class="form-control" id="name" name="name" placeholder="Nome do(a) coordenador(a)" required>
               </div>
               <div class="form-group">
                  <label for="email">Email</label>
                  <input type="email" class="form-control" id="email" name="email" placeholder="Email do(a) coordenador(a)" required>
               </div>
               <div class="form-group">
                  <label for="endereco">Endereço</label>
                  <input type="text" class="form-control" id="endereco" name="endereco" placeholder="Endereço do(a) coordenador(a)" required>
               </div>
               <div class="form-group">
                  <div class="row">
                     <div class="col">
                        <label for="cpf">CPF</label>
                        <input type="text" class="form-control" id="cpf" name="cpf" placeholder="CPF do(a) coordenador(a)" required>
                     </div>
                     <div class="col">
                        <label for="telefone">Telefone</label>
                        <input type="text" class="form-control" id="telefone" name="telefone" placeholder="Telefone do(a) coordenador(a)" required>
                     </div>
                  </div>
               </div>
         </div>
         <div class="modal-footer">
            <button type="submit" class="btn btn-success">Cadastrar Coordenador(a)</button>
         </div>
         </form>
      </div>
   </div>
</div>

<div class="modal fade" id="cadastrarSecretario" tabindex="-1" aria-labelledby="cadastrarSecretario" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="cadastrarSecretario">Cadastrar Secretário(a)</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            <form action="{{ route('parceiros.storeSecretario') }}" method="POST">
               @csrf
               <div class="form-group">
                  <label for="name">Nome</label>
                  <input type="text" class="form-control" id="name" name="name" placeholder="Nome do(a) secretário(a)" required>
               </div>
               <div class="form-group">
                  <label for="email">Email</label>
                  <input type="email" class="form-control" id="email" name="email" placeholder="Email do(a) secretário(a)" required>
               </div>
               <div class="form-group">
                  <label for="endereco">Endereço</label>
                  <input type="text" class="form-control" id="endereco" name="endereco" placeholder="Endereço do(a) secretário(a)" required>
               </div>
               <div class="form-group">
                  <div class="row">
                     <div class="col">
                        <label for="cpf">CPF</label>
                        <input type="text" class="form-control" id="cpf" name="cpf" placeholder="CPF do(a) secretário(a)" required>
                     </div>
                     <div class="col">
                        <label for="telefone">Telefone</label>
                        <input type="text" class="form-control" id="telefone" name="telefone" placeholder="Telefone do(a) secretário(a)" required>
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

<div class="modal fade" id="cadastrarParceiro" tabindex="-1" aria-labelledby="cadastrarParceiro" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="cadastrarParceiro">Cadastrar Parceiro</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            <form action="{{ route('parceiros.store') }}" method="POST">
               @csrf
               <div class="form-group">
                  <label for="name">Nome</label>
                  <input type="text" class="form-control" id="name" name="name" placeholder="Digite o nome do parceiro" required>
               </div>
               <div class="form-group">
                  <label for="endereco">Endereço</label>
                  <input type="text" class="form-control" id="endereco" name="endereco" placeholder="Digite o endereço" required>
               </div>
               <div class="form-group">
                  <div class="row">
                     <div class="col">
                        <label for="telefone">Telefone</label>
                        <input type="text" class="form-control" id="telefone" name="telefone" maxlength="15" placeholder="Digite o telefone" required>
                     </div>
                     <div class="col">
                        <label for="cep">CEP</label>
                        <input type="text" class="form-control" id="cep" name="cep" maxlength="10" placeholder="Digite o CEP" required>
                     </div>
                  </div>
               </div>
               <div class="form-group">
                  <div class="row">
                     <div class="col">
                        <label for="cnpj">CNPJ</label>
                        <input type="text" class="form-control" id="cnpj" name="cnpj" maxlength="18" placeholder="Digite o CNPJ">
                     </div>
                     <div class="col">
                        <label for="local_atuacao">Local de Atuação</label>
                        <select name="local_atuacao" id="local_atuacao" class="form-control">
                           <option disabled selected value="">Onde o parceiro atua?</option>
                           <option value="Alegre">Alegre</option>
                           <option value="Rive">Rive</option>
                           <option value="Guaçui">Guaçui</option>
                           <option value="Jerônimo Monteiro">Jerônimo Monteiro</option>
                        </select>
                        <!-- FUTURAMENTE COLOCAR UMA API DE CIDADES -->
                     </div>
                  </div>
               </div>
               <div class="form-group">
                  <div class="row">
                     <div class="col">
                        <label for="coordenador_id">Coordenador(a)</label>
                        <select name="coordenador_id" id="coordenador_id" class="form-control">
                           <option value="">Selecione um coordenador</option>
                           @forelse($coordenadores as $coordenador)
                           @if ($coordenador->parceiros->count() != 1)
                           <option value="{{ $coordenador->id }}">{{ $coordenador->name }}</option>
                           @endif
                           @empty
                           <option value="" disabled>Nenhum coordenador(a) cadastrado(a)</option>
                           @endforelse
                        </select>
                     </div>

                     <div class="col">
                        <label for="secretario_id">Secretário(a)</label>
                        <select name="secretario_id" id="secretario_id" class="form-control">
                           <option value="">Selecione um secretário(a)</option>
                           @forelse($secretarios as $secretario)
                           @if ($secretario->parceiros->count() != 1)
                           <option value="{{ $secretario->id }}">{{ $secretario->name }}</option>
                           @endif
                           @empty
                           <option value="" disabled>Nenhum secretário(a) cadastrado(a)</option>
                           @endforelse
                        </select>
                     </div>
                  </div>
               </div>
         </div>
         <div class="modal-footer">
            <button type="submit" class="btn btn-success">Cadastrar Parceiro</button>
         </div>
         </form>
      </div>
   </div>
</div>