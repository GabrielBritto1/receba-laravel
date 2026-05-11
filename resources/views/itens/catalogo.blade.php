@extends('adminlte::page')
@section('title', 'Catálogo de Itens - RECeBa')
@section('content_header')
<h1 class="text-bold"><i class="fas fa-boxes"></i> Catálogo de Itens</h1>
@stop
@section('content')

<div class="card">
   <div class="card-header">
      <span class="text-muted text-uppercase">Itens Cadastrados</span>
      <div class="card-tools">
         <a href="#" class="btn btn-success btn-sm text-bold" data-toggle="modal" data-target="#modalCadastrarItemCatalogo">
            <i class="fas fa-plus"></i> Adicionar Item
         </a>
      </div>
   </div>
   <div class="card-body pt-1">
      <div class="card-body table-responsive p-0">
         <table class="table table-hover text-nowrap table-striped">
            <thead>
               <tr>
                  <th>Nome</th>
                  <th class="text-center">Quantidade</th>
                  <th class="text-center">Disponível para Solicitar</th>
                  <th></th>
               </tr>
            </thead>
            <tbody>
               @forelse($itens as $item)
               <tr>
                  <td class="align-middle">{{ $item->nome }}</td>
                  <td class="align-middle text-center">{{ $item->quantidade }}</td>
                  <td class="align-middle text-center">
                     <form action="{{ route('itens.catalogo.toggle', $item) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-sm {{ $item->disponivel ? 'btn-success' : 'btn-secondary' }}"
                           title="{{ $item->disponivel ? 'Clique para desativar' : 'Clique para ativar' }}">
                           <i class="fas {{ $item->disponivel ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i>
                           {{ $item->disponivel ? 'Disponível' : 'Indisponível' }}
                        </button>
                     </form>
                  </td>
                  <td class="align-middle text-right">
                     <form action="{{ route('itens.catalogo.destroy', $item) }}" method="POST" class="d-inline"
                        onsubmit="return confirm('Tem certeza que deseja remover o item \'{{ $item->nome }}\'?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" title="Remover">
                           <i class="fas fa-trash"></i>
                        </button>
                     </form>
                  </td>
               </tr>
               @empty
               <tr>
                  <td colspan="4" class="text-center">Nenhum item cadastrado.</td>
               </tr>
               @endforelse
            </tbody>
         </table>
      </div>
   </div>
</div>

{{-- Modal Adicionar Item --}}
<div class="modal fade" id="modalCadastrarItemCatalogo" tabindex="-1" aria-labelledby="modalCadastrarItemCatalogoLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title text-bold" id="modalCadastrarItemCatalogoLabel">Adicionar Item ao Catálogo</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            <form action="{{ route('itens.catalogo.store') }}" method="POST" id="form-cadastrar-item-catalogo">
               @csrf
               <div class="form-group">
                  <label for="nome_item">Nome do Item</label>
                  <input type="text" class="form-control" id="nome_item" name="nome" required
                     placeholder="Ex: Arroz, Feijão, Óleo...">
               </div>
               <div class="form-group">
                  <label for="quantidade_item">Quantidade disponível em estoque</label>
                  <input type="number" class="form-control" id="quantidade_item" name="quantidade"
                     min="0" required value="0">
               </div>
               <div class="form-group">
                  <div class="custom-control custom-switch">
                     <input type="checkbox" class="custom-control-input" id="disponivel_item" name="disponivel" checked>
                     <label class="custom-control-label" for="disponivel_item">
                        Disponível para solicitação
                     </label>
                  </div>
                  <small class="text-muted">Quando ativado, o item aparece para os parceiros solicitarem.</small>
               </div>
            </form>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-success" form="form-cadastrar-item-catalogo">
               <i class="fas fa-save"></i> Salvar Item
            </button>
         </div>
      </div>
   </div>
</div>

@stop

@section('js')
@if (session('success'))
<script>
   Swal.fire({
      icon: 'success',
      title: 'Sucesso',
      text: "{{ session('success') }}",
   });
</script>
@endif
@endsection
