<div class="modal fade" id="modalCadastrarItem" tabindex="-1" aria-labelledby="modalCadastrarItemLabel" aria-hidden="true">
   <div class="modal-dialog modal-xl">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title text-bold" id="modalCadastrarItemLabel">Solicitar Itens ao IFES</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            <form action="{{ route('itens.store') }}" method="POST" id="form-cadastrar-item">
               @csrf
               <div class="row">
                  <div class="col">
                     <div class="form-group">
                        <label for="parceiro_item">Parceiro</label>
                        <input type="text" class="form-control" id="parceiro_item" disabled value="{{ optional($parceiro)->name ?: 'Parceiro não encontrado' }}">
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-12">
                     <div class="form-group">
                        <label for="item_id">Item</label>
                        <select class="form-control" id="item_id" name="item_id" required>
                           <option value="" disabled selected>Selecione um item...</option>
                           @foreach($itensDisponiveis as $itemDisponivel)
                           <option value="{{ $itemDisponivel->id }}">
                              {{ $itemDisponivel->nome }}
                              (em estoque: {{ $itemDisponivel->quantidade }})
                           </option>
                           @endforeach
                        </select>
                        @if($itensDisponiveis->isEmpty())
                        <small class="text-danger">Nenhum item disponível para solicitação no momento.</small>
                        @endif
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-12 col-md-6">
                     <div class="form-group">
                        <label for="data_previsao_entrega_item">Data prevista de recebimento</label>
                        <input type="datetime-local" class="form-control" id="data_previsao_entrega_item" name="data_previsao_entrega" required>
                     </div>
                  </div>
                  <div class="col-12 col-md-6">
                     <div class="form-group">
                        <label for="quantidade_solicitada_item">Quantidade solicitada</label>
                        <input type="number" class="form-control" id="quantidade_solicitada_item" name="quantidade_solicitada" min="1" required>
                     </div>
                  </div>
               </div>
            </form>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal" id="btn-fechar-item">Fechar</button>
            <button type="submit" class="btn btn-success" form="form-cadastrar-item" id="btn-solicitar-item"
               {{ $itensDisponiveis->isEmpty() ? 'disabled' : '' }}>
               <i class="fas fa-check mr-1"></i> Solicitar Itens ao IFES
            </button>
         </div>
      </div>
   </div>
</div>

<script>
document.getElementById('form-cadastrar-item').addEventListener('submit', function () {
   var btn = document.getElementById('btn-solicitar-item');
   btn.disabled = true;
   btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Sua solicitação está sendo feita...';
   document.getElementById('btn-fechar-item').disabled = true;
});
</script>
