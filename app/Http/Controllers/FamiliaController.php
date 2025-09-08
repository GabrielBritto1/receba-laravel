<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFamiliaRequest;
use App\Models\Endereco;
use App\Models\Familia;
use App\Models\Pessoa;
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
      $familias = Familia::all();
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

         // 1. Cria a Família (Aqui usamos '??' para campos que podem não vir)
         $familia = Familia::create([
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

         // 2. Cria o Representante (aqui os campos são obrigatórios, então não precisa de '??')
         $familia->representante()->create([
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
      //
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
}
