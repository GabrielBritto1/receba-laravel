@extends('adminlte::page')
@section('title', 'Gerenciar Siglas - RECeBa')
@section('content_header')
<h1 class="text-bold"><i class="fas fa-tag"></i> Gerenciar Siglas</h1>
@stop


@section('content')
<div class="card">
   <div class="card-body">
      <div class="table-responsive p-0">
         <table class="table table-hover text-nowrap table-striped">
            <thead>
               <tr>
                  <th>Nome</th>
                  <th>Sigla</th>
                  <th></th>
               </tr>
            </thead>
            <tbody>
               @forelse($parceiros as $parceiro)
               <tr>
                  <td class="align-middle">{{ $parceiro->name }}</td>
                  <td class="align-middle">
                     <span class="badge badge-primary text-uppercase text-dark" style="background-color: {{ $parceiro->sigla?->color }};">
                        {{ $parceiro->sigla?->name }}
                     </span>
                  </td>
                  <td class="align-middle">
                     <button class="btn btn-sm btn-warning text-white editarSigla" data-toggle="modal" data-target="#editarSigla" data-id="{{ $parceiro->id }}">
                        <i class="fas fa-edit"></i>
                     </button>
                  </td>
               </tr>
               @empty
               <tr>
                  <td colspan="4" class="text-center">Nenhum parceiro cadastrado.</td>
               </tr>
               @endforelse
            </tbody>
         </table>
      </div>
   </div>
</div>


<div class="modal fade" id="editarSigla" tabindex="-1" aria-labelledby="editarSigla" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="editarSigla">Gerenciar Sigla</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            <form id="form-alterar-sigla" method="POST">
               @csrf
               <div class="dados-parceiro"></div>
               <div class="modal-footer">
                  <button type="submit" class="btn btn-success">Salvar Sigla</button>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>
@stop


@section('js')
<script>
   const parceiros = @json($parceiros);
   $('.editarSigla').click(function() {
      const parceiro = parceiros.find(parceiro => parceiro.id == $(this).data('id'));
      $('#form-alterar-sigla').attr('action', `/parceiros/${parceiro.id}/sigla`);
      $('.dados-parceiro').html(`
     <div class="form-group">
        <label for="name">Nome</label>
        <input type="text" class="form-control" id="name" name="name" value="${parceiro.name}" disabled>
     </div>
     <div class="form-group">
        <label for="sigla">Sigla</label>
        <input type="text" class="form-control" id="sigla" name="sigla" value="${parceiro.sigla?.name ?? ''}" required>
     </div>
     <div class="form-group">
        <label for="color">Cor</label>
        <input type="color" class="form-control" id="color" name="color" value="${parceiro.sigla?.color ?? '#f1f1f1'}" required>
     </div>
  `);
   });
</script>
@stop