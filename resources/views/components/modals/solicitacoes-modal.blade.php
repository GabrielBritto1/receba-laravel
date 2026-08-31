<div class="modal fade" id="modalCadastrarCesta" tabindex="-1" aria-labelledby="modalCadastrarCestaLabel" aria-hidden="true">
   <div class="modal-dialog modal-xl">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title text-bold" id="modalCadastrarCestaLabel">Solicitar Cesta ao IFES</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            @if ($errors->any())
            <div class="alert alert-danger">
               <h5 class="text-bold"><i class="fas fa-exclamation-triangle mr-1"></i>Corrija os campos abaixo:</h5>
               <ul class="mb-0">
                  @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                  @endforeach
               </ul>
            </div>
            @endif
            <form action="{{ route('solicitacoes.store') }}" method="POST" id="form-cadastrar-cesta">
               @csrf
               <div class="row">
                  <div class="col">
                     <div class="form-group">
                        <label for="parceiro">Parceiro</label>
                        <input type="text" class="form-control" disabled value="{{ optional($parceiro)->name ?: 'Parceiro não encontrado' }}">
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-12 col-md-6">
                     <div class="form-group">
                        <label for="data_previsao_entrega">Data da Entrega Parcial</label>
                        <input type="datetime-local" class="form-control" id="data_previsao_entrega" name="data_previsao_entrega" value="{{ old('data_previsao_entrega') }}" required>
                     </div>
                  </div>
                  <div class="col-12 col-md-6">
                     <div class="form-group">
                        <label for="quantidade_solicitada">Solicitar uma Quantidade de Cesta</label>
                        <input type="text" class="form-control" id="quantidade_solicitada" name="quantidade_solicitada" value="{{ old('quantidade_solicitada') }}" required>
                     </div>
                  </div>
               </div>
            </form>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal" id="btn-fechar-cesta">Fechar</button>
            <button type="submit" class="btn btn-success" form="form-cadastrar-cesta" id="btn-solicitar-cesta">
               <i class="fas fa-check mr-1"></i> Solicitar Cesta ao IFES
            </button>
         </div>
      </div>
   </div>
</div>

<script>
document.getElementById('form-cadastrar-cesta').addEventListener('submit', function () {
   var btn = document.getElementById('btn-solicitar-cesta');
   btn.disabled = true;
   btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Sua solicitação está sendo feita...';
   document.getElementById('btn-fechar-cesta').disabled = true;
});
</script>