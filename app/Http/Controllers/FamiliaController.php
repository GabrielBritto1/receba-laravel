<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFamiliaRequest;
use App\Models\Endereco;
use App\Models\Familia;
use App\Models\Pessoa;
use App\Models\Representante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class FamiliaController extends Controller
{
   /**
    * Display a listing of the resource.
    */
   public function index()
   {
      $user = Auth::user();
      $parceiro = $user->parceiros->first();
      if ($user->can('Administrador')) {
         $familias = Familia::all();
      } else {
         $familias = collect();
         if ($parceiro) {
            $cestasPorParceiro = Familia::where('parceiro_id', $parceiro->id)->get();
            $familias = $parceiro->familias;
         }
      }
      return view('familias.index', compact('familias'));
   }

   /**
    * Show the form for creating a new resource.
    */
   public function create()
   {
      //
   }

   /**
    * Store a newly created resource in storage.
    */
   public function store(StoreFamiliaRequest $request)
   {
      // A validação passou, e agora temos o $validatedData
      $validatedData = $request->validated();

      DB::beginTransaction();
      try {
         $user = Auth::user();
         $parceiros = $user->parceiros;
         if ($parceiros->isEmpty()) {
            throw new \Exception('O usuário logado não está vinculado a nenhum parceiro.');
         }
         $parceiroId = $parceiros->first()->id;

         // 1. Cria o Representante (Aqui usamos '??' para campos que podem não vir)
         $representante = Representante::create([
            'nome' => $validatedData['name'],
            'cpf' => $validatedData['cpf'],
            'rg' => $validatedData['rg'] ?? null,
            'data_nascimento' => $validatedData['dt_nascimento'],
            'uf' => $validatedData['uf'],
            'estado_civil' => $validatedData['estado_civil'],
            'telefone' => $validatedData['telefone'],
            'escolaridade' => $validatedData['nivel_escolaridade'],
            'filiacao' => $validatedData['filiacao'],
            'nome_conjuge' => $validatedData['name_conjuge'] ?? null,
            'cpf_conjuge' => $validatedData['cpf_conjuge'] ?? null,
            'data_nascimento_conjuge' => $validatedData['dt_nascimento_conjuge'] ?? null,
         ]);

         // 2. Cria a Família (aqui os campos são obrigatórios, então não precisa de '??')
         $familia = $representante->familias()->create([
            'parceiro_id' => $parceiroId,
            'numero_casa' => $validatedData['numero_casa'],
            'bairro' => $validatedData['bairro'],
            'cidade' => $validatedData['cidade'],
            'endereco' => $validatedData['endereco'],
            'nis' => $validatedData['nis'] ?? null,
            'cad_unico' => $validatedData['cadunico'],
            'reside' => $validatedData['reside'],
            'aluguel' => $validatedData['alugada_valor'] ?? null,
            'doenca' => $validatedData['qual_doenca_comorbidade'] ?? null,
            'medicamento' => $validatedData['qual_medicacao'] ?? null,
            'descricao' => $validatedData['observacao'] ?? null,
            'status' => true,
         ]);

         // ====================================================================
         // ▼▼▼ A CORREÇÃO PRINCIPAL ESTÁ AQUI ▼▼▼
         // ====================================================================

         // 3. Cria os Membros da Família usando '?? 0'
         $familia->membroFamilia()->create([
            'idosos' => $validatedData['idosos'] ?? 0,
            'filhos_0a5' => $validatedData['filhos_0a5'] ?? 0,
            'filhos_6a12' => $validatedData['filhos_6a12'] ?? 0,
            'filhos_13a16' => $validatedData['filhos_13a16'] ?? 0,
            'filhos_acima16' => $validatedData['filhos_acima16'] ?? 0,
         ]);

         // 4. Cria a Renda Familiar usando '?? 0'
         $familia->rendaFamilia()->create([
            'pensao' => $validatedData['pensao'] ?? 0,
            'aposentadoria' => $validatedData['aposentadoria'] ?? 0,
            'beneficio' => $validatedData['beneficio'] ?? 0,
            'salario' => $validatedData['salario'] ?? 0,
            'outros' => $validatedData['outros'] ?? 0,
         ]);

         // ====================================================================
         // ▲▲▲ FIM DA CORREÇÃO ▲▲▲
         // ====================================================================

         DB::commit();

         return redirect()->route('familias.index')->with('success', 'Família cadastrada com sucesso!');
      } catch (\Exception $e) {
         DB::rollBack();
         dd($e);
         Log::error('Erro ao cadastrar família: ' . $e->getMessage());
         return redirect()->back()->with('error', 'Erro ao cadastrar a família: ' . $e->getMessage())->withInput();
      }
   }

   /**
    * Display the specified resource.
    */
   public function show(string $id)
   {
      $familia = Familia::with('representante', 'membroFamilia', 'rendaFamilia')->findOrFail($id);
      return view('familias.show', compact('familia'));
   }

   /**
    * Show the form for editing the specified resource.
    */
   public function edit(string $id)
   {
      //
   }

   /**
    * Update the specified resource in storage.
    */
   public function update(Request $request, string $id)
   {
      //
   }

   /**
    * Remove the specified resource from storage.
    */
   public function destroy(string $id)
   {
      //
   }

   public function getCestas(Familia $familia)
   {
      $cestas = $familia->cestas()
         ->with('parceiro')
         ->latest('data_entrega')
         ->get();

      return response()->json($cestas);
   }

   public function importacaoCpf(Familia $familia)
   {
      $familia->load('representante');

      return view('familias.importacao_cpf', compact('familia'));
   }

   public function checkCpf(Request $request)
   {
      $cpf = $request->input('cpf');
      $cpfLimpo = preg_replace('/[^0-9]/', '', $cpf);

      // 1. Encontra o representante e já carrega a COLEÇÃO de suas famílias
      $representante = Representante::with('familias')->where('cpf', $cpfLimpo)->first();

      // 2. Verifica se o representante existe e se a COLEÇÃO de famílias não está vazia
      if ($representante && $representante->familias->isNotEmpty()) {

         $parceiroAtual = Auth::user()->parceiros->first();

         // 3. Procura na COLEÇÃO se alguma família já pertence ao parceiro atual
         $familiaNesteParceiro = $representante->familias->where('parceiro_id', $parceiroAtual->id)->first();

         if ($familiaNesteParceiro) {
            // ENCONTROU! O CPF já está associado a uma família neste parceiro.
            return response()->json([
               'exists' => true,
               'status' => 'already_associated'
            ]);
         } else {
            // Não encontrou neste parceiro, mas o CPF existe em outro. Pode importar.
            return response()->json([
               'exists' => true,
               'status' => 'can_import',
               // Pega o ID da primeira família encontrada para usar como base na página de importação
               'familia_id' => $representante->familias->first()->id
            ]);
         }
      }

      // Se o representante não foi encontrado ou não tem nenhuma família, retorna que não existe
      return response()->json(['exists' => false]);
   }

   public function importStore(Request $request)
   {
      $validated = $request->validate([
         'representante_id' => 'required|exists:representantes,id',
      ]);

      $parceiroAtual = Auth::user()->parceiros->first();

      if (!$parceiroAtual) {
         return redirect()->route('familias.index')->with('error', 'Seu usuário não está vinculado a um parceiro.');
      }

      $familiaJaExiste = Familia::where('representante_id', $validated['representante_id'])
         ->where('parceiro_id', $parceiroAtual->id)
         ->exists();

      if ($familiaJaExiste) {
         return redirect()->route('familias.index')->with('info', 'Esta família já está cadastrada para o seu parceiro.');
      }

      DB::beginTransaction();
      try {
         $representante = Representante::find($validated['representante_id']);

         // ===== CORREÇÃO PRINCIPAL AQUI =====
         // Usamos o relacionamento no plural 'familias' e pegamos o primeiro registro como modelo.
         $familiaOriginal = $representante->familias->first();

         // Verificação de segurança para garantir que a família original foi encontrada
         if (!$familiaOriginal) {
            throw new \Exception('A família original do representante não foi encontrada para a cópia.');
         }
         // ===== FIM DA CORREÇÃO =====

         $novaFamilia = Familia::create([
            'representante_id' => $representante->id,
            'parceiro_id' => $parceiroAtual->id,
            'endereco' => $familiaOriginal->endereco,
            'numero_casa' => $familiaOriginal->numero_casa,
            'bairro' => $familiaOriginal->bairro,
            'cidade' => $familiaOriginal->cidade,
            'reside' => $familiaOriginal->reside,
            'aluguel' => $familiaOriginal->aluguel,
            'descricao' => $familiaOriginal->descricao,
            'status' => true,
            'cad_unico' => $familiaOriginal->cad_unico,
            'doenca' => $familiaOriginal->doenca,
            'medicamento' => $familiaOriginal->medicamento,
            'nis' => $familiaOriginal->nis,
         ]);

         if ($familiaOriginal->membroFamilia) {
            $novaFamilia->membroFamilia()->create($familiaOriginal->membroFamilia->getAttributes());
         }
         if ($familiaOriginal->rendaFamilia) {
            $novaFamilia->rendaFamilia()->create($familiaOriginal->rendaFamilia->getAttributes());
         }

         DB::commit();
      } catch (\Exception $e) {
         DB::rollBack();

         // ADICIONE ESTE DD PARA VER QUALQUER ERRO QUE ACONTEÇA AQUI DENTRO
         dd($e);

         return redirect()->back()->with('error', 'Ocorreu um erro ao importar os dados da família.');
      }

      return redirect()->route('familias.index')->with('success', 'Família associada ao seu parceiro com sucesso!');
   }
}
