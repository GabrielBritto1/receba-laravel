@extends('adminlte::page')
@section('title', 'Parceiros - RECeBa')
@section('content_header')
<h1 class="text-bold"><i class="fas fa-address-card"></i> Meu Parceiro</h1>
@stop
@section('content')
<div class="card">
   <div class="card-body pt-1">
      <div class="row">
         <div class="card-body table-responsive p-0">
            <table class="table text-nowrap">
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
                     <td class="align-middle">{{ $parceiro->telefone_formatado }}</td>
                     <td class="align-middle">{{ $parceiro->cep_formatado }}</td>
                     <td class="align-middle">
                        @if($parceiro->cnpj == null)
                        Não possui CNPJ
                        @else
                        {{ $parceiro->cnpj_formatado }}
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
                           <a href="{{ route('parceiros.show', $parceiro->id) }}" class="btn btn-sm btn-success btn-md">
                              <i class="fas fa-eye"></i>
                           </a>
                           <a href="{{ route('parceiros.edit', $parceiro->id) }}" class="btn btn-sm btn-warning btn-md text-white">
                              <i class="fas fa-edit"></i>
                           </a>
                           <button type="button" class="btn btn-sm btn-secondary btn-md text-white" onclick="storeSecretarioAssociar({{ $parceiro->id }})">
                              <i class="fas fa-user-plus"></i>
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

<div class="card">
   <div class="card-header">
      <h5 class="text-bold text-uppercase">Integrantes</h5>
   </div>
   <div class="card-body pt-1">
      <div class="row">
         <div class="card-body table-responsive p-0">
            <table class="table text-nowrap">
               <thead>
                  <tr>
                     <th>Nome</th>
                     <th>Cargo</th>
                  </tr>
               </thead>
               <tbody>
                  @forelse($integrantes as $integrante)
                  <tr>
                     <td class="align-middle">{{ $integrante->name }}</td>
                     <td class="align-middle">
                        <span class="badge badge-secondary text-uppercase">{{ $integrante->getRoleNames()->first() }}</span>
                     </td>
                  </tr>
                  @empty
                  <tr>
                     <td colspan="2" class="text-center">Nenhum integrante.</td>
                  </tr>
                  @endforelse
               </tbody>
            </table>
         </div>
      </div>
   </div>
</div>

@include('components.modals.secretario-associar-modal')
@stop

@section('js')
<script src="/assets/js/parceiro.js"></script>
<script src="/assets/js/pagination.js"></script>

@if (session('success') && session('success_action') === 'storeSecretario')
<script>
   Swal.fire({
      icon: 'success',
      title: 'Sucesso',
      text: "{{ session('success') }}",
   });
</script>
@endif
@stop