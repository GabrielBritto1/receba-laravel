<div class="modal fade" id="modalEntregarCesta" tabindex="-1" aria-labelledby="modalEntregarCesta" aria-hidden="true">
   <div class="modal-dialog modal-xl">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title text-bold" id="modalEntregarCesta">Entregar Cesta Própria</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            <form action="{{ route('cestas.entregaCestaPropria') }}" method="POST">
               @csrf
               <div class="row">
                  <div class="col">
                     <div class="form-group">
                        <label for="familia_id">Família</label>
                        <select name="familia_id" id="familia_id" class="form-control">
                           <option selected disabled value="">Selecione uma Família</option>
                           @forelse($familias as $familia)
                           <option value="{{ $familia->id }}">{{ $familia->representante->nome }}</option>
                           @empty
                           <option value="">Nenhuma Família cadastrada</option>
                           @endforelse
                        </select>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col">
                     <div class="form-group">
                        <label for="data_entrega">Data da Entrega Para a Família</label>
                        <input type="datetime-local" class="form-control" id="data_entrega" name="data_entrega">
                     </div>
                  </div>
               </div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            <button type="submit" class="btn btn-success">Registrar Entrega Própria</button>
         </div>
         </form>
      </div>
   </div>
</div>