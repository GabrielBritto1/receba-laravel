<?php

namespace App\Http\Controllers;

use App\Models\Cesta;
use App\Models\Solicitacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SolicitacaoController extends Controller
{
   /**
    * Display a listing of the resource.
    */
   public function index()
   {
      $solicitacoes = Solicitacao::orderBy('created_at', 'desc')->get();
      $parceiro = Auth::user()->parceiros->first();
      return view('solicitacoes.index', compact('solicitacoes', 'parceiro'));
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
   public function store(Request $request)
   {
      $user = Auth::user();
      $parceiros = $user->parceiros;
      if ($parceiros->isEmpty()) {
         throw new \Exception('O usuário logado não está vinculado a nenhum parceiro.');
      }
      $parceiroId = $parceiros->first()->id;

      $validated = $request->validate([
         'data_previsao_entrega' => 'required|date_format:Y-m-d\TH:i',
         'quantidade_solicitada' => 'required|string',
      ]);

      $solicitacao = Solicitacao::create([
         'data_previsao_entrega' => $validated['data_previsao_entrega'],
         'quantidade_solicitada' => $validated['quantidade_solicitada'],
         'parceiro_id' => $parceiroId,
      ]);

      return redirect()->route('solicitacoes.index')->with('success', 'Cesta solicitada com sucesso, aguarde a aprovação!');
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

   public function gerenciarSolicitacoes()
   {
      $this->authorize('Administrador');
      $solicitacaoEmAnalise = Solicitacao::where('status', 'Em Análise')->orderBy('created_at', 'desc')->get();
      $solicitacaoAceita = Solicitacao::where('status', 'Aceita')->orderBy('created_at', 'desc')->get();
      $solicitacaoMontada = Solicitacao::where('status', 'Montada')->orderBy('created_at', 'desc')->get();
      $solicitacaoEntregue = Solicitacao::where('status', 'Entregue')->orderBy('created_at', 'desc')->get();
      $solicitacaoNaoAceita = Solicitacao::where('quantidade_nao_aceita', '>', 0)->orderBy('created_at', 'desc')->get();

      return view('solicitacoes.gerenciar_solicitacoes', compact('solicitacaoEmAnalise', 'solicitacaoAceita', 'solicitacaoMontada', 'solicitacaoEntregue', 'solicitacaoNaoAceita'));
   }

   public function atualizarStatusSolicitacao(Request $request, Solicitacao $solicitacao)
   {
      $validated = $request->validate([
         'status' => 'required|string',
         'quantidade_aceita' => 'nullable|string|integer|lte:' . $solicitacao->quantidade_solicitada,
         'data_aceito' => 'nullable|date_format:Y-m-d\TH:i',
         'data_montada' => 'nullable|date_format:Y-m-d\TH:i',
         'data_entrega' => 'nullable|date_format:Y-m-d\TH:i',
      ]);
      $solicitacao->status = $validated['status'];

      if (isset($validated['quantidade_aceita'])) {
         $quantidadeAceita = (int)$validated['quantidade_aceita'];
         $solicitacao->quantidade_aceita = $validated['quantidade_aceita'];

         $solicitacao->quantidade_nao_aceita = (int)$solicitacao->quantidade_solicitada - $quantidadeAceita;
      }

      if (isset($validated['quantidade_aceita'])) {
         $solicitacao->quantidade_aceita = $validated['quantidade_aceita'];
      }
      if (isset($validated['data_aceito'])) {
         $solicitacao->data_aceito = $validated['data_aceito'];
      }
      if (isset($validated['data_montada'])) {
         $solicitacao->data_montada = $validated['data_montada'];
      }
      if (isset($validated['data_entrega'])) {
         $solicitacao->data_entrega = $validated['data_entrega'];
      }

      if ($validated['status'] == 'Entregue' && $solicitacao->quantidade_aceita > 0) {
         for ($i = 0; $i < $solicitacao->quantidade_aceita; $i++) {
            Cesta::create([
               'data_recebimento' => $solicitacao->data_entrega,
               'status' => 'Não saiu para entrega',
               'ponto_origem' => 'IFES',
               'parceiro_id' => $solicitacao->parceiro_id,
            ]);
         }
      }

      $solicitacao->save();

      return redirect()->route('solicitacoes.gerenciar_solicitacoes')->with('success', 'Status da cesta atualizado com sucesso!');
   }
}
