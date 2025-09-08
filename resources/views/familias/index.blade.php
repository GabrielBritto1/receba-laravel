@extends('adminlte::page')
@section('title', 'Familias - RECeBa')
@section('content_header')
<h1 class="text-bold"><i class="fas fa-user-friends"></i> Famílias</h1>
@stop
@section('content')
<div class="card">
   <div class="card-header">
      <div class="card-tools">
         <button type="button" class="btn btn-success btn-sm text-bold" data-toggle="modal" data-target="#modalCadastrarFamilia">
            <i class="fas fa-plus"></i> Cadastrar Família
         </button>
      </div>
   </div>
   <div class="card-body">
      <div class="table-responsive">
         <table class="table table-hover text-nowrap table-striped">
            <thead>
               <tr>
                  <th>Representante</th>
                  <th>CPF</th>
                  <th>Telefone</th>
                  <th>Parceiro</th>
                  <th>Status</th>
                  <th></th>
               </tr>
            </thead>
            <tbody>
               @forelse($familias as $familia)
               <tr>
                  <td class="align-middle">{{ $familia->representante->nome }}</td>
                  <td class="align-middle">{{ $familia->representante->cpf }}</td>
                  <td class="align-middle">{{ $familia->representante->telefone }}</td>
                  <td class="align-middle">
                     <span class="badge p-2" style="background-color: {{ $familia->parceiro->sigla->color }}; font-size: 16px; color: #fff;">{{ $familia->parceiro->sigla->name }}</span>
                  </td>
                  <td class="align-middle">
                     @if ($familia->status == 1)
                     <span class="badge text-uppercase badge-success">Ativo</span>
                     @else
                     <span class="badge text-uppercase badge-danger">Inativo</span>
                     @endif
                  </td>
                  <td>
                     <div class="btn-group float-right">
                        <a href="#" class="btn btn-success btn-md">
                           <i class="fas fa-eye"></i>
                        </a>
                        <a href="#" class="btn btn-warning btn-md text-white">
                           <i class="fas fa-edit"></i>
                        </a>
                        <a href="#" class="btn btn-info btn-md text-white">
                           <i class="fas fa-check"></i>
                        </a>
                        <a href="#" class="btn btn-danger btn-md">
                           <i class="fas fa-trash"></i>
                        </a>
                     </div>
                  </td>
               </tr>
               @empty
               <tr>
                  <td colspan="7" class="text-center">Nenhuma família cadastrada.</td>
               </tr>
               @endforelse
            </tbody>
         </table>
      </div>
   </div>
</div>

<div class="modal fade" id="modalCadastrarFamilia" tabindex="-1" aria-labelledby="modalCadastrarFamiliaLabel" aria-hidden="true">
   <div class="modal-dialog modal-xl"> {{-- Aumentado para XL para melhor visualização --}}
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title text-bold text-uppercase" id="modalCadastrarFamiliaLabel">Cadastrar Nova Família</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            <form action="{{ route('familias.store') }}" method="POST">
               @csrf

               {{-- Bloco para exibir erros de validação --}}
               @if ($errors->any())
               <div class="alert alert-danger">
                  <h5 class="text-bold">Ocorreram os seguintes erros:</h5>
                  <ul class="mb-0">
                     @foreach ($errors->all() as $error)
                     <li>{{ $error }}</li>
                     @endforeach
                  </ul>
               </div>
               @endif

               {{-- SEÇÃO 1: DADOS DO REPRESENTANTE --}}
               <h5 class="text-bold bg-light p-2 rounded">1. Dados do Representante</h5>
               <div class="row">
                  <div class="col-md-6 form-group">
                     <label for="name"><span class="text-danger">*</span>Nome Completo:</label>
                     <input type="text" class="form-control" name="name" id="name" placeholder="Nome do representante" value="{{ old('name') }}" required>
                  </div>
                  <div class="col-md-3 form-group">
                     <label for="cpf"><span class="text-danger">*</span>CPF:</label>
                     <input type="text" class="form-control" name="cpf" id="cpf" placeholder="000.000.000-00" value="{{ old('cpf') }}" required>
                  </div>
                  <div class="col-md-3 form-group">
                     <label for="dt_nascimento"><span class="text-danger">*</span>Data de Nascimento:</label>
                     <input type="date" class="form-control" name="dt_nascimento" id="dt_nascimento" value="{{ old('dt_nascimento') }}" required>
                  </div>
                  <div class="col-md-4 form-group">
                     <label for="rg">RG (Opcional):</label>
                     <input type="text" class="form-control" name="rg" id="rg" placeholder="Número do RG" value="{{ old('rg') }}">
                  </div>
                  <div class="col-md-2 form-group">
                     <label for="uf"><span class="text-danger">*</span>UF:</label>
                     <select name="uf" id="uf" class="form-control" required>
                        <option value="" disabled selected>Selecione...</option>
                        <option value="ES" @if(old('uf')=='ES' ) selected @endif>ES</option>
                        <option value="Outro" @if(old('uf')=='Outro' ) selected @endif>Outro</option>
                     </select>
                  </div>
                  <div class="col-md-6 form-group">
                     <label for="filiacao"><span class="text-danger">*</span>Filiação (Nome da Mãe):</label>
                     <input type="text" class="form-control" name="filiacao" id="filiacao" placeholder="Nome completo da mãe" value="{{ old('filiacao') }}" required>
                  </div>
                  <div class="col-md-4 form-group">
                     <label for="telefone"><span class="text-danger">*</span>Telefone:</label>
                     <input type="text" class="form-control" name="telefone" id="telefone" placeholder="(00) 00000-0000" value="{{ old('telefone') }}" required>
                  </div>
                  <div class="col-md-4 form-group">
                     <label for="estado_civil"><span class="text-danger">*</span>Estado Civil:</label>
                     <select name="estado_civil" id="estado_civil" class="form-control" required>
                        <option value="" disabled selected>Selecione...</option>
                        <option value="Solteiro" @if(old('estado_civil')=='Solteiro' ) selected @endif>Solteiro(a)</option>
                        <option value="Casado" @if(old('estado_civil')=='Casado' ) selected @endif>Casado(a)</option>
                        <option value="Divorciado" @if(old('estado_civil')=='Divorciado' ) selected @endif>Divorciado(a)</option>
                        <option value="Viuvo" @if(old('estado_civil')=='Viuvo' ) selected @endif>Viúvo(a)</option>
                     </select>
                  </div>
                  <div class="col-md-4 form-group">
                     <label for="nivel_escolaridade"><span class="text-danger">*</span>Escolaridade:</label>
                     <select name="nivel_escolaridade" id="nivel_escolaridade" class="form-control" required>
                        <option value="" disabled selected>Selecione...</option>
                        <option value="Ensino Fundamental" @if(old('nivel_escolaridade')=='Ensino Fundamental' ) selected @endif>Ensino Fundamental</option>
                        <option value="Ensino Médio" @if(old('nivel_escolaridade')=='Ensino Médio' ) selected @endif>Ensino Médio</option>
                        <option value="Ensino Superior" @if(old('nivel_escolaridade')=='Ensino Superior' ) selected @endif>Ensino Superior</option>
                        <option value="Outro" @if(old('nivel_escolaridade')=='Outro' ) selected @endif>Outro</option>
                     </select>
                  </div>
               </div>

               {{-- SEÇÃO 2: ENDEREÇO DA FAMÍLIA --}}
               <h5 class="text-bold bg-light p-2 rounded mt-3">2. Endereço</h5>
               <div class="row">
                  <div class="col-md-8 form-group">
                     <label for="endereco"><span class="text-danger">*</span>Endereço:</label>
                     <input type="text" class="form-control" name="endereco" id="endereco" placeholder="Rua, Avenida..." value="{{ old('endereco') }}" required>
                  </div>
                  <div class="col-md-4 form-group">
                     <label for="numero_casa"><span class="text-danger">*</span>Número:</label>
                     <input type="text" class="form-control" name="numero_casa" id="numero_casa" placeholder="Ex: 123 ou S/N" value="{{ old('numero_casa') }}" required>
                  </div>
                  <div class="col-md-6 form-group">
                     <label for="bairro"><span class="text-danger">*</span>Bairro:</label>
                     <input type="text" class="form-control" name="bairro" id="bairro" placeholder="Nome do bairro" value="{{ old('bairro') }}" required>
                  </div>
                  <div class="col-md-6 form-group">
                     <label for="reside"><span class="text-danger">*</span>Reside em:</label>
                     <select name="reside" id="reside" class="form-control" required>
                        <option value="" disabled selected>Selecione...</option>
                        <option value="Própria" @if(old('reside')=='Própria' ) selected @endif>Casa Própria</option>
                        <option value="Alugada" @if(old('reside')=='Alugada' ) selected @endif>Alugada</option>
                        <option value="Cedida" @if(old('reside')=='Cedida' ) selected @endif>Cedida / Emprestada</option>
                     </select>
                  </div>
                  <div class="col-md-6 form-group" id="campo_aluguel" style="display: none;">
                     <label for="alugada_valor">Valor do Aluguel (R$):</label>
                     <input type="number" step="0.01" class="form-control" name="alugada_valor" id="alugada_valor" placeholder="Ex: 500.00" value="{{ old('alugada_valor') }}">
                  </div>
                  <div class="col-md form-group">
                     <label for="cidade"><span class="text-danger">*</span>Cidade:</label>
                     <input type="text" class="form-control" name="cidade" id="cidade" placeholder="Nome da cidade..." value="{{ old('cidade') }}" required>
                  </div>
               </div>

               {{-- SEÇÃO 3: COMPOSIÇÃO E RENDA --}}
               <h5 class="text-bold bg-light p-2 rounded mt-3">3. Composição e Renda Familiar</h5>
               <div class="row">
                  <div class="col-md-6 form-group">
                     <label>Possui idosos na residência?</label>
                     <div>
                        <div class="form-check form-check-inline">
                           <input class="form-check-input" type="radio" name="possui_idoso" id="sim_idoso" value="sim">
                           <label class="form-check-label" for="sim_idoso">Sim</label>
                        </div>
                        <div class="form-check form-check-inline">
                           <input class="form-check-input" type="radio" name="possui_idoso" id="nao_idoso" value="nao" checked>
                           <label class="form-check-label" for="nao_idoso">Não</label>
                        </div>
                     </div>
                     <input type="number" class="form-control mt-2" name="idosos" id="quantidade_idosos" placeholder="Quantidade de idosos" value="{{ old('idosos', 0) }}" style="display: none;">
                  </div>

                  <div class="col-md-6 form-group">
                     <label for="cadunico"><span class="text-danger">*</span>Possui CadÚnico?</label>
                     <select name="cadunico" id="cadunico" class="form-control" required>
                        <option value="Não" @if(old('cadunico')=='Não' ) selected @endif>Não</option>
                        <option value="Sim" @if(old('cadunico')=='Sim' ) selected @endif>Sim</option>
                     </select>
                     <input type="text" class="form-control mt-2" name="nis" id="campo_nis" placeholder="Digite o NIS" value="{{ old('nis') }}" style="display: none;">
                  </div>
               </div>
               <div class="row">
                  <div class="col-md-12">
                     <label>Composição de Filhos:</label>
                     <div class="row">
                        <div class="col-md-3 form-group"><label>0 a 5 anos</label><input type="number" class="form-control" name="filhos_0a5" value="{{ old('filhos_0a5', 0) }}" min="0"></div>
                        <div class="col-md-3 form-group"><label>6 a 12 anos</label><input type="number" class="form-control" name="filhos_6a12" value="{{ old('filhos_6a12', 0) }}" min="0"></div>
                        <div class="col-md-3 form-group"><label>13 a 16 anos</label><input type="number" class="form-control" name="filhos_13a16" value="{{ old('filhos_13a16', 0) }}" min="0"></div>
                        <div class="col-md-3 form-group"><label>Acima de 16 anos</label><input type="number" class="form-control" name="filhos_acima16" value="{{ old('filhos_acima16', 0) }}" min="0"></div>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-12"><label>Fontes de Renda (R$):</label></div>
                  <div class="col-md-4 form-group"><label>Pensão</label><input type="number" step="0.01" class="form-control" name="pensao" placeholder="0.00" value="{{ old('pensao', 0) }}"></div>
                  <div class="col-md-4 form-group"><label>Aposentadoria</label><input type="number" step="0.01" class="form-control" name="aposentadoria" placeholder="0.00" value="{{ old('aposentadoria', 0) }}"></div>
                  <div class="col-md-4 form-group"><label>Benefício</label><input type="number" step="0.01" class="form-control" name="beneficio" placeholder="0.00" value="{{ old('beneficio', 0) }}"></div>
                  <div class="col-md-4 form-group"><label>Salário</label><input type="number" step="0.01" class="form-control" name="salario" placeholder="0.00" value="{{ old('salario', 0) }}"></div>
                  <div class="col-md-4 form-group"><label>Outros</label><input type="number" step="0.01" class="form-control" name="outros" placeholder="0.00" value="{{ old('outros', 0) }}"></div>
               </div>

               {{-- SEÇÃO 4: DADOS DO CÔNJUGE E OBSERVAÇÕES --}}
               <h5 class="text-bold bg-light p-2 rounded mt-3">4. Outras Informações</h5>
               <div class="row">
                  <div class="col-md-6 form-group">
                     <label>Possui alguma doença/comorbidade?</label>
                     <input type="text" class="form-control" name="qual_doenca_comorbidade" placeholder="Se sim, qual?" value="{{ old('qual_doenca_comorbidade') }}">
                  </div>
                  <div class="col-md-6 form-group">
                     <label>Usa alguma medicação contínua?</label>
                     <input type="text" class="form-control" name="qual_medicacao" placeholder="Se sim, qual?" value="{{ old('qual_medicacao') }}">
                  </div>
               </div>
               <div class="row">
                  <div class="col-md-6 form-group">
                     <label for="name_conjuge">Nome do Cônjuge (Opcional):</label>
                     <input type="text" class="form-control" name="name_conjuge" id="name_conjuge" value="{{ old('name_conjuge') }}">
                  </div>
                  <div class="col-md-3 form-group">
                     <label for="cpf_conjuge">CPF do Cônjuge:</label>
                     <input type="text" class="form-control" name="cpf_conjuge" id="cpf_conjuge" value="{{ old('cpf_conjuge') }}">
                  </div>
                  <div class="col-md-3 form-group">
                     <label for="dt_nascimento_conjuge">Data de Nasc. Cônjuge:</label>
                     <input type="date" class="form-control" name="dt_nascimento_conjuge" id="dt_nascimento_conjuge" value="{{ old('dt_nascimento_conjuge') }}">
                  </div>
               </div>
               <div class="row">
                  <div class="col-md-12 form-group">
                     <label for="observacao">Observações Adicionais:</label>
                     <textarea name="observacao" id="observacao" class="form-control" rows="3">{{ old('observacao') }}</textarea>
                  </div>
               </div>

               <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                  <button type="submit" class="btn btn-success">Cadastrar Família</button>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>
@stop

@section('js')
<script>
   $(document).ready(function() {

      // Lógica para mostrar/esconder campo de valor do aluguel
      $('#reside').on('change', function() {
         if ($(this).val() === 'Alugada') {
            $('#campo_aluguel').show();
         } else {
            $('#campo_aluguel').hide();
            $('#alugada_valor').val(''); // Limpa o valor se não for alugada
         }
      }).trigger('change'); // Dispara o evento ao carregar a página para o caso de old('reside') ser 'Alugada'

      // Lógica para mostrar/esconder campo NIS
      $('#cadunico').on('change', function() {
         if ($(this).val() === 'Sim') {
            $('#campo_nis').show();
         } else {
            $('#campo_nis').hide();
            $('#campo_nis').val(''); // Limpa o NIS se não tiver CadÚnico
         }
      }).trigger('change');

      // Lógica para mostrar/esconder campo de quantidade de idosos
      $('input[name="possui_idoso"]').on('change', function() {
         if ($('#sim_idoso').is(':checked')) {
            $('#quantidade_idosos').show();
         } else {
            $('#quantidade_idosos').hide();
            $('#quantidade_idosos').val('0'); // Define como 0 se não houver idosos
         }
      }).trigger('change');
   });
</script>
@stop