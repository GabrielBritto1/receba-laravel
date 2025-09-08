<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFamiliaRequest extends FormRequest
{
   public function authorize(): bool
   {
      return true;
   }

   public function rules(): array
   {
      return [
         // Representante
         'name' => 'required|string|max:255',
         'rg' => 'nullable|string|max:255',
         'uf' => 'required|string|max:2', // Regra mais estrita para UF
         // A regra unique agora tem uma mensagem de erro customizada
         'cpf' => 'required|string|max:14|unique:representantes,cpf',
         'dt_nascimento' => 'required|date',
         'telefone' => 'required|string|max:20',
         'filiacao' => 'required|string|max:255',
         'estado_civil' => 'required|string|max:255',
         'nivel_escolaridade' => 'required|string|max:255',

         // Família
         'cidade' => 'required|string|max:255',
         'endereco' => 'required|string|max:255',
         'numero_casa' => 'required|string|max:255',
         'bairro' => 'required|string|max:255',
         'cadunico' => 'required|string',
         // A regra 'sometimes' garante que o NIS só seja validado se for enviado
         'nis' => 'sometimes|nullable|string|max:255|unique:familias,nis',
         'reside' => 'required|string',
         // A regra 'sometimes' garante que o valor do aluguel só seja validado se for enviado
         'alugada_valor' => 'sometimes|nullable|numeric',

         // Saúde e Observações
         'qual_doenca_comorbidade' => 'nullable|string|max:255',
         'qual_medicacao' => 'nullable|string|max:255',
         'observacao' => 'nullable|string',

         // Cônjuge
         'name_conjuge' => 'nullable|string|max:255',
         'cpf_conjuge' => 'nullable|string|max:14',
         'dt_nascimento_conjuge' => 'nullable|date',

         // --- A MUDANÇA PRINCIPAL ESTÁ AQUI ---
         // Usamos 'numeric' que é mais flexível que 'integer' para dados de formulário
         'idosos' => 'required|numeric|min:0',
         'filhos_0a5' => 'required|numeric|min:0',
         'filhos_6a12' => 'required|numeric|min:0',
         'filhos_13a16' => 'required|numeric|min:0',
         'filhos_acima16' => 'required|numeric|min:0',
         'pensao' => 'required|numeric|min:0',
         'aposentadoria' => 'required|numeric|min:0',
         'beneficio' => 'required|numeric|min:0',
         'salario' => 'required|numeric|min:0',
         'outros' => 'required|numeric|min:0',
      ];
   }

   /**
    * Adiciona mensagens de erro customizadas para melhorar o feedback.
    */
   public function messages(): array
   {
      return [
         'cpf.unique' => 'Este CPF já está cadastrado no sistema.',
         'nis.unique' => 'Este NIS já está cadastrado no sistema.',
      ];
   }
}
