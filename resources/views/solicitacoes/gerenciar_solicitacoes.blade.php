@extends('adminlte::page')
@section('title', 'Gerenciar Cestas - RECeBa')
@section('content_header')
<h1 class="text-bold"><i class="fas fa-circle"></i> Gerenciar Cestas</h1>
@stop
@section('content')
<section class="solicitacoes_em_analise">
   <div class="card">
      <div class="card-header">
         <span class="text-muted text-uppercase">Solicitações em Análise</span>
      </div>
      <div class="card-body pt-1">
         <div class="row">
            <div class="card-body table-responsive p-0">
               <table class="table table-hover text-nowrap">
                  <thead>
                     <tr>
                        <th>Parceiro</th>
                        <th>Data de Reserva</th>
                        <th>Data previsão das entregas para as familias</th>
                        <th>Quantidade</th>
                        <th>Status das cestas</th>
                        <th></th>
                     </tr>
                  </thead>
                  <tbody>
                     @forelse($solicitacaoEmAnalise as $solicitacao)
                     <tr>
                        <td class="align-middle">
                           <span class="badge text-dark" style="background-color: {{ $solicitacao->parceiro->sigla?->color ?? '#f1f1f1' }};">
                              {{ $solicitacao->parceiro->sigla?->name ?? $solicitacao->parceiro->name }}
                           </span>
                        </td>
                        <td class="align-middle">{{ $solicitacao->created_at->format('d/m/Y - H:i') }}</td>
                        <td class="align-middle">{{ $solicitacao->data_previsao_entrega->format('d/m/Y - H:i') }}</td>
                        <td class="align-middle">{{ $solicitacao->quantidade_solicitada }}</td>
                        <td class="align-middle">
                           <span class="badge badge-primary text-uppercase" style="background-color: #FF9E4A;">{{ $solicitacao->status }}</span>
                        </td>
                        <form action="{{ route('solicitacoes.alterar_status_solicitacao', $solicitacao) }}" method="POST" class="d-flex">
                           <td class="align-middle d-flex justify-content-end">
                              @csrf
                              @method('PUT')
                              <input type="hidden" name="status" value="Aceita">
                              <input type="datetime-local" hidden name="data_aceito" value="{{ now()->format('Y-m-d\TH:i') }}">
                              <input type="text" name="quantidade_aceita" required class="form-control form-control-sm text-center text-bold col-1 mr-1">
                              <button type="submit" class="btn btn-warning btn-sm text-bold text-white">
                                 <i class="fas fa-check"></i> Aprovar
                              </button>
                           </td>
                        </form>
                     </tr>
                     @empty
                     <tr>
                        <td class="align-middle text-center" colspan="6">Nenhuma solicitação em análise.</td>
                     </tr>
                     @endforelse
                  </tbody>
               </table>
            </div>
         </div>
      </div>
   </div>
</section>

<section class="solicitacoes_em_montagem">
   <div class="card">
      <div class="card-header">
         <span class="text-muted text-uppercase">Solicitações em Montagem</span>
      </div>
      <div class="card-body pt-1">
         <div class="row">
            <div class="card-body table-responsive p-0">
               <table class="table table-hover text-nowrap">
                  <thead>
                     <tr>
                        <th>Parceiro</th>
                        <th>Data de Reserva</th>
                        <th>Data previsão das entregas para as familias</th>
                        <th>Quantidade</th>
                        <th>Status das cestas</th>
                        <th></th>
                     </tr>
                  </thead>
                  <tbody>
                     @forelse($solicitacaoAceita as $solicitacao)
                     <tr>
                        <td class="align-middle">
                           <span class="badge text-dark" style="background-color: {{ $solicitacao->parceiro->sigla?->color ?? '#f1f1f1' }};">
                              {{ $solicitacao->parceiro->sigla?->name ?? $solicitacao->parceiro->name }}
                           </span>
                        </td>
                        <td class="align-middle">{{ $solicitacao->created_at->format('d/m/Y - H:i') }}</td>
                        <td class="align-middle">{{ $solicitacao->data_previsao_entrega->format('d/m/Y - H:i') }}</td>
                        <td class="align-middle">{{ $solicitacao->quantidade_aceita }}</td>
                        <td class="align-middle">
                           <span class="badge badge-primary text-uppercase">Em Montagem</span>
                        </td>
                        <form action="{{ route('solicitacoes.alterar_status_solicitacao', $solicitacao) }}" method="POST" class="d-flex">
                           <td class="align-middle d-flex justify-content-end">
                              @csrf
                              @method('PUT')
                              <input type="hidden" name="status" value="Montada">
                              <input type="datetime-local" hidden name="data_montada" value="{{ now()->format('Y-m-d\TH:i') }}">
                              <button type="submit" class="btn btn-warning btn-sm text-bold text-white">
                                 <i class="fas fa-check"></i> Concluir
                              </button>
                           </td>
                        </form>
                     </tr>
                     @empty
                     <tr>
                        <td class="align-middle text-center" colspan="6">Nenhuma solicitação em montagem.</td>
                     </tr>
                     @endforelse
                  </tbody>
               </table>
            </div>
         </div>
      </div>
   </div>
</section>

<section class="solicitacoes_prontas_para_entrega">
   <div class="card">
      <div class="card-header">
         <span class="text-muted text-uppercase">Solicitações Prontas para Entrega</span>
      </div>
      <div class="card-body pt-1">
         <div class="row">
            <div class="card-body table-responsive p-0">
               <table class="table table-hover text-nowrap">
                  <thead>
                     <tr>
                        <th>Parceiro</th>
                        <th>Data de Reserva</th>
                        <th>Data previsão das entregas para as familias</th>
                        <th>Quantidade</th>
                        <th>Status das cestas</th>
                        <th></th>
                     </tr>
                  </thead>
                  <tbody>
                     @forelse($solicitacaoMontada as $solicitacao)
                     <tr>
                        <td class="align-middle">
                           <span class="badge text-dark" style="background-color: {{ $solicitacao->parceiro->sigla?->color ?? '#f1f1f1' }};">
                              {{ $solicitacao->parceiro->sigla?->name ?? $solicitacao->parceiro->name }}
                           </span>
                        </td>
                        <td class="align-middle">{{ $solicitacao->created_at->format('d/m/Y - H:i') }}</td>
                        <td class="align-middle">{{ $solicitacao->data_previsao_entrega->format('d/m/Y - H:i') }}</td>
                        <td class="align-middle">{{ $solicitacao->quantidade_aceita }}</td>
                        <td class="align-middle">
                           <span class="badge badge-warning text-uppercase text-white">{{ $solicitacao->status }}</span>
                        </td>
                        <form action="{{ route('solicitacoes.alterar_status_solicitacao', $solicitacao) }}" method="POST" class="d-flex">
                           <td class="align-middle d-flex justify-content-end">
                              @csrf
                              @method('PUT')
                              <input type="hidden" name="status" value="Entregue">
                              <input type="datetime-local" hidden name="data_entrega" value="{{ now()->format('Y-m-d\TH:i') }}">
                              <button type="submit" class="btn btn-warning btn-sm text-bold text-white">
                                 <i class="fas fa-check"></i> Entregar
                              </button>
                           </td>
                        </form>
                     </tr>
                     @empty
                     <tr>
                        <td class="align-middle text-center" colspan="6">Nenhuma solicitação montada.</td>
                     </tr>
                     @endforelse
                  </tbody>
               </table>
            </div>
         </div>
      </div>
   </div>
</section>

<section class="solicitacoes_entregues">
   <div class="card">
      <div class="card-header">
         <span class="text-muted text-uppercase">Solicitações Entregues</span>
      </div>
      <div class="card-body pt-1">
         <div class="row">
            <div class="card-body table-responsive p-0">
               <table class="table table-hover text-nowrap">
                  <thead>
                     <tr>
                        <th>Parceiro</th>
                        <th>Data de Reserva</th>
                        <th>Data previsão das entregas para as familias</th>
                        <th>Quantidade</th>
                        <th>Status das cestas</th>
                     </tr>
                  </thead>
                  <tbody>
                     @forelse($solicitacaoEntregue as $solicitacao)
                     <tr>
                        <td class="align-middle">
                           <span class="badge text-dark" style="background-color: {{ $solicitacao->parceiro->sigla?->color ?? '#f1f1f1' }};">
                              {{ $solicitacao->parceiro->sigla?->name ?? $solicitacao->parceiro->name }}
                           </span>
                        </td>
                        <td class="align-middle">{{ $solicitacao->created_at->format('d/m/Y - H:i') }}</td>
                        <td class="align-middle">{{ $solicitacao->data_previsao_entrega->format('d/m/Y - H:i') }}</td>
                        <td class="align-middle">{{ $solicitacao->quantidade_aceita }}</td>
                        <td class="align-middle">
                           <span class="badge badge-success text-uppercase">{{ $solicitacao->status }}</span>
                        </td>
                     </tr>
                     @empty
                     <tr>
                        <td class="align-middle text-center" colspan="6">Nenhuma solicitação entregue.</td>
                     </tr>
                     @endforelse
                  </tbody>
               </table>
            </div>
         </div>
      </div>
   </div>
</section>

<section class="solicitacoes_nao_aceitas">
   <div class="card">
      <div class="card-header">
         <span class="text-muted text-uppercase">Solicitações Não Aceitas</span>
      </div>
      <div class="card-body pt-1">
         <div class="row">
            <div class="card-body table-responsive p-0">
               <table class="table table-hover text-nowrap">
                  <thead>
                     <tr>
                        <th>Parceiro</th>
                        <th>Data de Reserva</th>
                        <th>Quantidade Solicitada</th>
                        <th>Quantidade Não Aceita</th>
                        <th>Status das cestas</th>
                     </tr>
                  </thead>
                  <tbody>
                     @forelse($solicitacaoNaoAceita as $solicitacao)
                     <tr>
                        <td class="align-middle">
                           <span class="badge text-dark" style="background-color: {{ $solicitacao->parceiro->sigla?->color ?? '#f1f1f1' }};">
                              {{ $solicitacao->parceiro->sigla?->name ?? $solicitacao->parceiro->name }}
                           </span>
                        </td>
                        <td class="align-middle">{{ $solicitacao->created_at->format('d/m/Y - H:i') }}</td>
                        <td class="align-middle">{{ $solicitacao->quantidade_solicitada }}</td>
                        <td class="align-middle">{{ $solicitacao->quantidade_nao_aceita }}</td>
                        <td class="align-middle">
                           <span class="badge badge-danger text-uppercase">Não Aceita</span>
                        </td>
                     </tr>
                     @empty
                     <tr>
                        <td class="align-middle text-center" colspan="6">Nenhuma solicitação nzo aceita.</td>
                     </tr>
                     @endforelse
                  </tbody>
               </table>
            </div>
         </div>
      </div>
   </div>
</section>
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