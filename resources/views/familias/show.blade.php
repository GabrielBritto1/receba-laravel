@extends('adminlte::page')
@section('title', 'Familia - RECeBa')
@section('content_header')
<h1 class="text-bold"><i class="fas fa-user-friends"></i> Família de {{ $familia->representante->nome }}</h1>
@stop
@section('content')
<div class="mb-3">
   <a href="{{ route('familias.index') }}" class="btn btn-secondary">&larr; Voltar para lista</a>
</div>

<div class="card">
   <div class="card-body">
      <h4 class="text-bold">1. Dados do representante</h4>
      <div class="row">
         <div class="col-md-4 form-group">
            <label>Nome</label>
            <input class="form-control" disabled value="{{ $familia->representante->nome }}">
         </div>
         <div class="col-md-2 form-group">
            <label>CPF</label>
            <input class="form-control" disabled value="{{ $familia->cpf_formatado }}">
         </div>
         <div class="col-md-2 form-group">
            <label>Telefone</label>
            <input class="form-control" disabled value="{{ $familia->telefone_formatado }}">
         </div>
         <div class="col-md-2 form-group">
            <label>RG</label>
            <input class="form-control" disabled value="{{ $familia->representante->rg ?? '-' }}">
         </div>
         <div class="col-md-2 form-group">
            <label>Data nascimento</label>
            <input class="form-control" disabled value="{{ optional($familia->representante->data_nascimento)->format('d/m/Y') ?? '-' }}">
         </div>
      </div>

      <h4 class="text-bold mt-3">2. Dados do cônjuge</h4>
      <div class="row">
         <div class="col-md-4 form-group">
            <label>Nome do cônjuge</label>
            <input class="form-control" disabled value="{{ $familia->representante->nome_conjuge ?? 'Não informado' }}">
         </div>
         <div class="col-md-4 form-group">
            <label>CPF do cônjuge</label>
            <input class="form-control" disabled value="{{ $familia->representante->cpf_conjuge ?? 'Não informado' }}">
         </div>
         <div class="col-md-4 form-group">
            <label>Data nascimento cônjuge</label>
            <input class="form-control" disabled value="{{ optional($familia->representante->data_nascimento_conjuge)->format('d/m/Y') ?? 'Não informado' }}">
         </div>
      </div>

      <h4 class="text-bold mt-3">3. Endereço da família</h4>
      <div class="row">
         <div class="col-md-5 form-group">
            <label>Endereço</label>
            <input class="form-control" disabled value="{{ $familia->endereco }}">
         </div>
         <div class="col-md-1 form-group">
            <label>Nº</label>
            <input class="form-control" disabled value="{{ $familia->numero_casa }}">
         </div>
         <div class="col-md-2 form-group">
            <label>Bairro</label>
            <input class="form-control" disabled value="{{ $familia->bairro }}">
         </div>
         <div class="col-md-2 form-group">
            <label>Cidade</label>
            <input class="form-control" disabled value="{{ $familia->cidade }}">
         </div>
         <div class="col-md-2 form-group">
            <label>Parceiro</label>
            <input class="form-control" disabled value="{{ $familia->parceiro->name ?? $familia->parceiro->nome ?? 'Não vinculado' }}">
         </div>
      </div>

      <div class="row">
         <div class="col-md-2 form-group">
            <label>Reside</label>
            <input class="form-control" disabled value="{{ $familia->reside ?? '-' }}">
         </div>
         <div class="col-md-2 form-group">
            <label>Aluguel</label>
            <input class="form-control" disabled value="{{ $familia->aluguel ?? '-' }}">
         </div>
         <div class="col-md-2 form-group">
            <label>Cad Único</label>
            <input class="form-control" disabled value="{{ $familia->cad_unico ?? 'Não informado' }}">
         </div>
         <div class="col-md-2 form-group">
            <label>NIS</label>
            <input class="form-control" disabled value="{{ $familia->nis ?? '-' }}">
         </div>
         <div class="col-md-4 form-group">
            <label>Doença / Medicamento</label>
            <input class="form-control" disabled value="{{ $familia->doenca ?? '-' }} / {{ $familia->medicamento ?? '-' }}">
         </div>
      </div>

      <div class="form-group">
         <label>Observações</label>
         <textarea class="form-control" rows="3" disabled>{{ $familia->descricao ?? '-' }}</textarea>
      </div>

      <h4 class="text-bold mt-3">4. Composição de Membros</h4>
      <div class="row">
         <div class="col-md-2 form-group"><label>Idosos</label><input class="form-control" disabled value="{{ $familia->membroFamilia->idosos ?? 0 }}"></div>
         <div class="col-md-2 form-group"><label>Filhos 0-5</label><input class="form-control" disabled value="{{ $familia->membroFamilia->filhos_0a5 ?? 0 }}"></div>
         <div class="col-md-2 form-group"><label>Filhos 6-12</label><input class="form-control" disabled value="{{ $familia->membroFamilia->filhos_6a12 ?? 0 }}"></div>
         <div class="col-md-2 form-group"><label>Filhos 13-16</label><input class="form-control" disabled value="{{ $familia->membroFamilia->filhos_13a16 ?? 0 }}"></div>
         <div class="col-md-2 form-group"><label>Filhos +16</label><input class="form-control" disabled value="{{ $familia->membroFamilia->filhos_acima16 ?? 0 }}"></div>
         <div class="col-md-2 form-group"><label>Total</label><input class="form-control" disabled value="{{ ($familia->membroFamilia->idosos ?? 0) + ($familia->membroFamilia->filhos_0a5 ?? 0) + ($familia->membroFamilia->filhos_6a12 ?? 0) + ($familia->membroFamilia->filhos_13a16 ?? 0) + ($familia->membroFamilia->filhos_acima16 ?? 0) }}"></div>
      </div>

      <h4 class="text-bold mt-3">5. Renda Familiar</h4>
      <div class="row">
         <div class="col-md-2 form-group"><label>Pensão</label><input class="form-control" disabled value="{{ number_format($familia->rendaFamilia->pensao ?? 0, 2, ',', '.') }}"></div>
         <div class="col-md-2 form-group"><label>Aposentadoria</label><input class="form-control" disabled value="{{ number_format($familia->rendaFamilia->aposentadoria ?? 0, 2, ',', '.') }}"></div>
         <div class="col-md-2 form-group"><label>Benefício</label><input class="form-control" disabled value="{{ number_format($familia->rendaFamilia->beneficio ?? 0, 2, ',', '.') }}"></div>
         <div class="col-md-2 form-group"><label>Salário</label><input class="form-control" disabled value="{{ number_format($familia->rendaFamilia->salario ?? 0, 2, ',', '.') }}"></div>
         <div class="col-md-2 form-group"><label>Outros</label><input class="form-control" disabled value="{{ number_format($familia->rendaFamilia->outros ?? 0, 2, ',', '.') }}"></div>
         <div class="col-md-2 form-group"><label>Total</label><input class="form-control" disabled value="{{ number_format(($familia->rendaFamilia->pensao ?? 0)+($familia->rendaFamilia->aposentadoria ?? 0)+($familia->rendaFamilia->beneficio ?? 0)+($familia->rendaFamilia->salario ?? 0)+($familia->rendaFamilia->outros ?? 0), 2, ',', '.') }}"></div>
      </div>

      <h4 class="text-bold mt-3">6. Cestas</h4>
      @if($familia->cestas->isNotEmpty())
      <div class="table-responsive">
         <table class="table table-striped">
            <thead>
               <tr>
                  <th>ID</th>
                  <th>Parceiro</th>
                  <th>Status</th>
                  <th>Data Recebimento</th>
                  <th>Data em Rota</th>
                  <th>Data Entrega</th>
               </tr>
            </thead>
            <tbody>
               @foreach($familia->cestas as $cesta)
               <tr>
                  <td>{{ $cesta->id }}</td>
                  <td>{{ $cesta->parceiro->name ?? $cesta->parceiro->nome ?? 'N/A' }}</td>
                  <td>{{ $cesta->status ? 'Entregue' : 'Pendente' }}</td>
                  <td>{{ optional($cesta->data_recebimento)->format('d/m/Y') ?? '-' }}</td>
                  <td>{{ optional($cesta->data_em_rota)->format('d/m/Y') ?? '-' }}</td>
                  <td>{{ optional($cesta->data_entrega)->format('d/m/Y') ?? '-' }}</td>
               </tr>
               @endforeach
            </tbody>
         </table>
      </div>
      @else
      <p>Nenhuma cesta registrada para esta família.</p>
      @endif
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
@stop