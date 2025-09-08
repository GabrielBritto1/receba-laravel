@extends('adminlte::page')
@section('title', 'Parceiros - RECeBa')
@section('content_header')
<h1 class="text-bold"><i class="fas fa-address-card"></i> Parceiros</h1>
@stop
@section('content')
<div class="card">
   <div class="card-header">
      <div class="card-tools d-flex justify-content-end">
         <button type="button" class="btn btn-success btn-sm mr-2 text-bold" data-toggle="modal" data-target="#cadastrarCoordenador">
            <i class="fas fa-plus"></i> Adicionar Coordenador
         </button>
         <a href="#" class="btn btn-success btn-sm mr-2 text-bold">
            <i class="fas fa-plus"></i> Adicionar Secretário(a)
         </a>
         <button type="button" class="btn btn-success btn-sm mr-2 text-bold" data-toggle="modal" data-target="#cadastrarParceiro">
            <i class="fas fa-plus"></i> Adicionar Parceiro
         </button>
      </div>
   </div>
   <div class="card-body pt-1">
      <div class="row">
         <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap table-striped">
               <thead>
                  <tr>
                     <th>Nome</th>
                     <th>Endereço</th>
                     <th>Telefone</th>
                     <th>CEP</th>
                     <th>CNPJ</th>
                     <th>Status</th>
                     <th></th>
                  </tr>
               </thead>
               <tbody>
                  @forelse($parceiros as $parceiro)
                  <tr>
                     <td class="align-middle">{{ $parceiro->name }}</td>
                     <td class="align-middle">{{ $parceiro->endereco }}</td>
                     <td class="align-middle">{{ $parceiro->telefone }}</td>
                     <td class="align-middle">{{ $parceiro->cep }}</td>
                     <td class="align-middle">
                        @if($parceiro->cnpj == null)
                        Não possui CNPJ
                        @else
                        {{ $parceiro->cnpj }}
                        @endif
                     </td>
                     <td class="align-middle">
                        @if($parceiro->status == 1)
                        <span class="badge text-uppercase badge-success">Ativo</span>
                        @else
                        <span class="badge text-uppercase badge-danger">Inativo</span>
                        @endif
                     </td>
                     <td>
                        <div class="btn-group float-right">
                           <a href="{{ route('parceiros.show', $parceiro->id) }}" class="btn btn-success btn-md">
                              <i class="fas fa-eye"></i>
                           </a>
                           <a href="{{ route('parceiros.edit', $parceiro->id) }}" class="btn btn-warning btn-md text-white">
                              <i class="fas fa-edit"></i>
                           </a>
                           <button type="button" class="btn btn-info btn-md ativar-btn" data-id="{{ $parceiro->id }}">
                              <i class="fas fa-check"></i>
                           </button>
                           <button type="button" class="btn btn-danger btn-md deletar-btn" data-id="{{ $parceiro->id }}">
                              <i class="fas fa-trash"></i>
                           </button>
                        </div>
                     </td>
                  </tr>
                  @empty
                  <tr>
                     <td colspan="7" class="text-center">Nenhum parceiro cadastrado.</td>
                  </tr>
                  @endforelse
               </tbody>
            </table>
         </div>
      </div>
   </div>
</div>

<!-- MODAIS -->
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
                  <label for="telefone">Telefone</label>
                  <input type="text" class="form-control" id="telefone" name="telefone" maxlength="15" placeholder="Digite o telefone" required>
               </div>
               <div class="form-group">
                  <label for="cep">CEP</label>
                  <input type="text" class="form-control" id="cep" name="cep" maxlength="10" placeholder="Digite o CEP" required>
               </div>
               <div class="form-group">
                  <label for="cnpj">CNPJ</label>
                  <input type="text" class="form-control" id="cnpj" name="cnpj" maxlength="18" placeholder="Digite o CNPJ">
               </div>
               <div class="form-group">
                  <label for="user_id">Coordenador(a)</label>
                  <select name="user_id" id="user_id" class="form-control">
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
               <div class="form-group">
                  <label for="user_id">Secretário(a)</label>
                  <select disabled name="user_id" id="user_id" class="form-control">
                     <option value="">Selecione um secretário(a)</option>
                  </select>
               </div>
         </div>
         <div class="modal-footer">
            <button type="submit" class="btn btn-success">Cadastrar Parceiro</button>
         </div>
         </form>
      </div>
   </div>
</div>

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
                  <input type="text" class="form-control" id="name" name="name" placeholder="Digite o nome do parceiro" required>
               </div>
               <div class="form-group">
                  <label for="email">Email</label>
                  <input type="email" class="form-control" id="email" name="email" placeholder="Digite o email do coordenador" required>
               </div>
               <div class="form-group">
                  <div class="card">

                  </div>
               </div>
         </div>
         <div class="modal-footer">
            <button type="submit" class="btn btn-success">Cadastrar Coordenador</button>
         </div>
         </form>
      </div>
   </div>
</div>
@stop

@section('js')
<script>
   $('.ativar-btn').on('click', function() {
      let id = $(this).data('id');
      Swal.fire({
         icon: 'question',
         title: 'Voce deseja ativar este parceiro?',
         showCancelButton: true,
         confirmButtonColor: '#28a745',
         confirmButtonText: 'Sim',
         cancelButtonText: 'Não',
         allowOutsideClick: false,
         allowEscapeKey: false,
      }).then((result) => {
         if (result.isConfirmed) {
            Swal.fire({
               title: 'Processando...',
               text: 'Aguarde o status do parceiro ser mudado.',
               allowOutsideClick: false,
               allowEscapeKey: false,
               allowEnterKey: false,
               didOpen: () => {
                  Swal.showLoading();
               }
            });
            $.ajax({
               url: `/parceiros/${id}/toggleStatus`,
               method: 'POST',
               data: {
                  _token: '{{ csrf_token() }}'
               },
               success: function() {
                  Swal.fire({
                     title: 'Ativado!',
                     text: 'Status do parceiro mudado com sucesso.',
                     icon: 'success'
                  }).then(() => {
                     location.reload();
                  })
               },
               error: function() {
                  Swal.fire({
                     title: 'Erro!',
                     text: 'Houve um problema ao mudar o status do parceiro.',
                     icon: 'error'
                  })
               }
            });
         }
      });
   });
</script>

<script>
   $('.deletar-btn').on('click', function() {
      let id = $(this).data('id');
      Swal.fire({
         icon: 'info',
         title: 'Voce deseja excluir este parceiro?',
         showCancelButton: true,
         confirmButtonColor: '#dc3545',
         confirmButtonText: 'Sim',
         cancelButtonText: 'Não',
         allowOutsideClick: false,
         allowEscapeKey: false,
      }).then((result) => {
         if (result.isConfirmed) {
            Swal.fire({
               icon: 'question',
               title: 'Voce realmente deseja excluir este parceiro?',
               showCancelButton: true,
               confirmButtonColor: '#dc3545',
               confirmButtonText: 'Sim',
               cancelButtonText: 'Não',
               allowOutsideClick: false,
               allowEscapeKey: false,
            }).then((result) => {
               if (result.isConfirmed) {
                  Swal.fire({
                     title: 'Processando...',
                     text: 'Aguarde o parceiro ser excluido.',
                     allowOutsideClick: false,
                     allowEscapeKey: false,
                     allowEnterKey: false,
                     didOpen: () => {
                        Swal.showLoading();
                     }
                  });
                  $.ajax({
                     url: `/parceiros/${id}/destroy`,
                     method: 'POST',
                     data: {
                        _token: '{{ csrf_token() }}',
                        _method: 'DELETE'
                     },
                     success: function() {
                        Swal.fire({
                           icon: 'success',
                           title: 'Parceiro excluido com sucesso!',
                        }).then(() => {
                           location.reload();
                        })
                     },
                     error: function() {
                        Swal.fire({
                           icon: 'error',
                           title: 'Erro!',
                           text: 'Houve um problema ao excluir o parceiro.',
                        })
                     }
                  });
               }
            });
         }
      });
   });
</script>

@if (session('success') && session('success_action') === 'store')
<script>
   Swal.fire({
      icon: 'success',
      title: 'Sucesso',
      text: "{{ session('success') }}",
   });
</script>
@endif

@if (session('success') && session('success_action') === 'storeCoordenador')
<script>
   Swal.fire({
      icon: 'success',
      title: 'Sucesso',
      text: "{{ session('success') }}",
   });
</script>
@endif
@stop