@extends('adminlte::page')
@section('title', 'Editar Família - RECeBa')
@section('content_header')
<h1 class="text-bold"><i class="fas fa-user-edit"></i> Editar Família de {{ $familia->representante->nome }}</h1>
@stop
@section('content')
<form action="{{ route('familias.update', $familia->id) }}" method="POST">
   @csrf
   @method('PUT')
   <div class="mb-3">
      <a href="{{ route('familias.show', $familia->id) }}" class="btn btn-secondary">&larr; Voltar</a>
      <button type="submit" class="btn btn-success float-right"><i class="fas fa-save"></i> Salvar Alterações</button>
   </div>
   <div class="card">
      <div class="card-body">
         <h4 class="text-bold">1. Dados do representante</h4>
         <div class="row">
            <div class="col-md-4 form-group">
               <label>Nome</label>
               <input name="representante_nome" class="form-control" value="{{ old('representante_nome', $familia->representante->nome) }}">
            </div>
            <div class="col-md-2 form-group">
               <label>CPF</label>
               <input name="representante_cpf" class="form-control" value="{{ old('representante_cpf', $familia->representante->cpf) }}">
            </div>
            <div class="col-md-2 form-group">
               <label>Telefone</label>
               <input name="representante_telefone" class="form-control" value="{{ old('representante_telefone', $familia->representante->telefone) }}">
            </div>
            <div class="col-md-2 form-group">
               <label>RG</label>
               <input name="representante_rg" class="form-control" value="{{ old('representante_rg', $familia->representante->rg) }}">
            </div>
            <div class="col-md-2 form-group">
               <label>Data nascimento</label>
               <input name="representante_data_nascimento" type="date" class="form-control" value="{{ old('representante_data_nascimento', optional($familia->representante->data_nascimento)->format('Y-m-d')) }}">
            </div>
         </div>
         <h4 class="text-bold mt-3">2. Dados do cônjuge</h4>
         <div class="row">
            <div class="col-md-4 form-group">
               <label>Nome do cônjuge</label>
               <input name="nome_conjuge" class="form-control" value="{{ old('nome_conjuge', $familia->representante->nome_conjuge) }}">
            </div>
            <div class="col-md-4 form-group">
               <label>CPF do cônjuge</label>
               <input name="cpf_conjuge" class="form-control" value="{{ old('cpf_conjuge', $familia->representante->cpf_conjuge) }}">
            </div>
            <div class="col-md-4 form-group">
               <label>Data nascimento cônjuge</label>
               <input name="data_nascimento_conjuge" type="date" class="form-control" value="{{ old('data_nascimento_conjuge', optional($familia->representante->data_nascimento_conjuge)->format('Y-m-d')) }}">
            </div>
         </div>
         <h4 class="text-bold mt-3">3. Endereço da família</h4>
         <div class="row">
            <div class="col-md-5 form-group">
               <label>Endereço</label>
               <input name="endereco" class="form-control" value="{{ old('endereco', $familia->endereco) }}">
            </div>
            <div class="col-md-1 form-group">
               <label>Nº</label>
               <input name="numero_casa" class="form-control" value="{{ old('numero_casa', $familia->numero_casa) }}">
            </div>
            <div class="col-md-2 form-group">
               <label>Bairro</label>
               <input name="bairro" class="form-control" value="{{ old('bairro', $familia->bairro) }}">
            </div>
            <div class="col-md-2 form-group">
               <label>Cidade</label>
               <input name="cidade" class="form-control" value="{{ old('cidade', $familia->cidade) }}">
            </div>
            <div class="col-md-2 form-group">
               <label>Parceiro</label>
               <input class="form-control" value="{{ $familia->parceiro->name ?? $familia->parceiro->nome ?? 'Não vinculado' }}" disabled>
            </div>
         </div>
         <div class="row">
            <div class="col-md-2 form-group">
               <label>Reside</label>
               <input name="reside" class="form-control" value="{{ old('reside', $familia->reside) }}">
            </div>
            <div class="col-md-2 form-group">
               <label>Aluguel</label>
               <input name="aluguel" class="form-control" value="{{ old('aluguel', $familia->aluguel) }}">
            </div>
            <div class="col-md-2 form-group">
               <label>Cad Único</label>
               <input name="cad_unico" class="form-control" value="{{ old('cad_unico', $familia->cad_unico) }}">
            </div>
            <div class="col-md-2 form-group">
               <label>NIS</label>
               <input name="nis" class="form-control" value="{{ old('nis', $familia->nis) }}">
            </div>
            <div class="col-md-4 form-group">
               <label>Doença / Medicamento</label>
               <input name="doenca_medicamento" class="form-control" value="{{ old('doenca_medicamento', ($familia->doenca ?? '').' / '.($familia->medicamento ?? '')) }}">
            </div>
         </div>
         <div class="form-group">
            <label>Observações</label>
            <textarea name="descricao" class="form-control" rows="3">{{ old('descricao', $familia->descricao) }}</textarea>
         </div>
         <h4 class="text-bold mt-3">4. Composição de Membros</h4>
         <div class="row">
            <div class="col-md-2 form-group"><label>Idosos</label><input name="idosos" class="form-control" value="{{ old('idosos', $familia->membroFamilia->idosos ?? 0) }}"></div>
            <div class="col-md-2 form-group"><label>Filhos 0-5</label><input name="filhos_0a5" class="form-control" value="{{ old('filhos_0a5', $familia->membroFamilia->filhos_0a5 ?? 0) }}"></div>
            <div class="col-md-2 form-group"><label>Filhos 6-12</label><input name="filhos_6a12" class="form-control" value="{{ old('filhos_6a12', $familia->membroFamilia->filhos_6a12 ?? 0) }}"></div>
            <div class="col-md-2 form-group"><label>Filhos 13-16</label><input name="filhos_13a16" class="form-control" value="{{ old('filhos_13a16', $familia->membroFamilia->filhos_13a16 ?? 0) }}"></div>
            <div class="col-md-2 form-group"><label>Filhos +16</label><input name="filhos_acima16" class="form-control" value="{{ old('filhos_acima16', $familia->membroFamilia->filhos_acima16 ?? 0) }}"></div>
            <div class="col-md-2 form-group"><label>Total</label><input class="form-control" value="{{ ($familia->membroFamilia->idosos ?? 0) + ($familia->membroFamilia->filhos_0a5 ?? 0) + ($familia->membroFamilia->filhos_6a12 ?? 0) + ($familia->membroFamilia->filhos_13a16 ?? 0) + ($familia->membroFamilia->filhos_acima16 ?? 0) }}" disabled></div>
         </div>
         <h4 class="text-bold mt-3">5. Renda Familiar</h4>
         <div class="row">
            <div class="col-md-2 form-group"><label>Pensão</label><input name="pensao" class="form-control" value="{{ old('pensao', $familia->rendaFamilia->pensao ?? 0) }}"></div>
            <div class="col-md-2 form-group"><label>Aposentadoria</label><input name="aposentadoria" class="form-control" value="{{ old('aposentadoria', $familia->rendaFamilia->aposentadoria ?? 0) }}"></div>
            <div class="col-md-2 form-group"><label>Benefício</label><input name="beneficio" class="form-control" value="{{ old('beneficio', $familia->rendaFamilia->beneficio ?? 0) }}"></div>
            <div class="col-md-2 form-group"><label>Salário</label><input name="salario" class="form-control" value="{{ old('salario', $familia->rendaFamilia->salario ?? 0) }}"></div>
            <div class="col-md-2 form-group"><label>Outros</label><input name="outros" class="form-control" value="{{ old('outros', $familia->rendaFamilia->outros ?? 0) }}"></div>
            <div class="col-md-2 form-group"><label>Total</label><input class="form-control" value="{{ ($familia->rendaFamilia->pensao ?? 0)+($familia->rendaFamilia->aposentadoria ?? 0)+($familia->rendaFamilia->beneficio ?? 0)+($familia->rendaFamilia->salario ?? 0)+($familia->rendaFamilia->outros ?? 0) }}" disabled></div>
         </div>
      </div>
   </div>
</form>
@stop