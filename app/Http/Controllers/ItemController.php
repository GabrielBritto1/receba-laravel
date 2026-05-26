<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Parceiro;
use App\Models\Solicitacao;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
   public function index()
   {
      $user = Auth::user();
      $parceiro = $user->parceiros->first();

      if ($user->hasRole('Administrador')) {
         $solicitacoes = Solicitacao::itens()->orderBy('created_at', 'desc')->paginate(10);
         $solicitacoesNaoAceitas = Solicitacao::itens()
            ->where('quantidade_nao_aceita', '>', 0)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
      } else {
         $solicitacoes = collect();
         $solicitacoesNaoAceitas = collect();

         if ($parceiro) {
            $solicitacoes = Solicitacao::itens()
               ->where('parceiro_id', $parceiro->id)
               ->orderBy('created_at', 'desc')
               ->paginate(15);
            $solicitacoesNaoAceitas = Solicitacao::itens()
               ->where('quantidade_nao_aceita', '>', 0)
               ->where('parceiro_id', $parceiro->id)
               ->orderBy('created_at', 'desc')
               ->paginate(15);
         }
      }

      $itensDisponiveis = Item::where('disponivel', true)->orderBy('nome')->get();
      $parceiros = Parceiro::orderBy('name')->get();

      return view('itens.index', compact('solicitacoes', 'solicitacoesNaoAceitas', 'parceiro', 'itensDisponiveis', 'parceiros'));
   }

   public function store(Request $request)
   {
      $user = Auth::user();
      $parceiros = $user->parceiros;

      if ($parceiros->isEmpty()) {
         throw new \Exception('O usuário logado não está vinculado a nenhum parceiro.');
      }

      $parceiroId = $parceiros->first()->id;

      $validated = $request->validate([
         'item_id' => 'required|exists:items,id',
         'data_previsao_entrega' => 'required|date_format:Y-m-d\TH:i',
         'quantidade_solicitada' => 'required|integer|min:1',
      ]);

      Solicitacao::create([
         'tipo' => 'item',
         'item_id' => $validated['item_id'],
         'data_previsao_entrega' => $validated['data_previsao_entrega'],
         'quantidade_solicitada' => $validated['quantidade_solicitada'],
         'parceiro_id' => $parceiroId,
      ]);

      $itemNome = Item::find($validated['item_id'])?->nome ?? 'desconhecido';
      ActivityLogger::log('solicitado', "Item solicitado: {$itemNome} (qtd: {$validated['quantidade_solicitada']})");
      return redirect()->route('itens.index')->with('success', 'Item solicitado com sucesso, aguarde a aprovação!');
   }

   public function list(Request $request)
   {
      $user = Auth::user();
      $parceiro = $user->parceiros->first();

      $query = Solicitacao::itens()->with(['parceiro.sigla', 'item'])
         ->where(fn($q) => $q->where('status', 'Em Análise')->orWhere('quantidade_aceita', '>', 0))
         ->orderBy('created_at', 'desc');

      if ($user->hasRole('Administrador')) {
         if ($request->filled('parceiro_id')) {
            $query->where('parceiro_id', $request->parceiro_id);
         }
      } else {
         $query->where('parceiro_id', $parceiro?->id ?? 0);
      }

      if ($request->filled('item_id')) {
         $query->where('item_id', $request->item_id);
      }
      if ($request->filled('status')) {
         $query->where('status', $request->status);
      }
      if ($request->filled('data_inicio')) {
         $query->whereDate('created_at', '>=', $request->data_inicio);
      }
      if ($request->filled('data_fim')) {
         $query->whereDate('created_at', '<=', $request->data_fim);
      }

      $solicitacoes = $query->paginate(15);

      return response()->json([
         'status' => 'success',
         'solicitacoes' => $solicitacoes->items(),
         'pagination' => [
            'current_page' => $solicitacoes->currentPage(),
            'last_page' => $solicitacoes->lastPage(),
         ],
      ]);
   }

   public function listNaoAceitas(Request $request)
   {
      $user = Auth::user();
      $parceiro = $user->parceiros->first();

      $query = Solicitacao::itens()->with(['parceiro.sigla', 'item'])
         ->where('quantidade_nao_aceita', '>', 0)
         ->orderBy('created_at', 'desc');

      if ($user->hasRole('Administrador')) {
         if ($request->filled('parceiro_id')) {
            $query->where('parceiro_id', $request->parceiro_id);
         }
      } else {
         $query->where('parceiro_id', $parceiro?->id ?? 0);
      }

      if ($request->filled('item_id')) {
         $query->where('item_id', $request->item_id);
      }
      if ($request->filled('data_inicio')) {
         $query->whereDate('created_at', '>=', $request->data_inicio);
      }
      if ($request->filled('data_fim')) {
         $query->whereDate('created_at', '<=', $request->data_fim);
      }

      $solicitacoesNaoAceitas = $query->paginate(15);

      return response()->json([
         'status' => 'success',
         'solicitacoesNaoAceitas' => $solicitacoesNaoAceitas->items(),
         'paginationNaoAceitas' => [
            'current_page' => $solicitacoesNaoAceitas->currentPage(),
            'last_page' => $solicitacoesNaoAceitas->lastPage(),
         ],
      ]);
   }

   // ── Catálogo de Itens ──────────────────────────────────────────────────────

   public function catalogo()
   {
      abort_unless(Auth::user()->hasRole('Administrador'), 403);

      $itens = Item::orderBy('nome')->get();

      return view('itens.catalogo', compact('itens'));
   }

   public function storeCatalogo(Request $request)
   {
      abort_unless(Auth::user()->hasRole('Administrador'), 403);

      $validated = $request->validate([
         'nome' => 'required|string|max:255',
         'quantidade' => 'required|integer|min:0',
      ]);

      Item::create([
         'nome' => $validated['nome'],
         'quantidade' => $validated['quantidade'],
         'disponivel' => $request->boolean('disponivel'),
      ]);

      ActivityLogger::log('criado', "Item adicionado ao catálogo: {$validated['nome']}");
      return redirect()->route('itens.catalogo')->with('success', 'Item cadastrado com sucesso!');
   }

   public function toggleDisponivel(Item $item)
   {
      abort_unless(Auth::user()->hasRole('Administrador'), 403);

      $item->disponivel = !$item->disponivel;
      $item->save();

      $status = $item->disponivel ? 'ativado' : 'desativado';
      ActivityLogger::log('atualizado', "Item {$status} no catálogo: {$item->nome}");
      return redirect()->route('itens.catalogo')->with('success', 'Disponibilidade atualizada!');
   }

   public function destroyCatalogo(Item $item)
   {
      abort_unless(Auth::user()->hasRole('Administrador'), 403);

      $item->delete();

      ActivityLogger::log('removido', "Item removido do catálogo: {$item->nome}");
      return redirect()->route('itens.catalogo')->with('success', 'Item removido com sucesso!');
   }

   // ── Gerenciar Solicitações ─────────────────────────────────────────────────

   public function gerenciarItens()
   {
      abort_unless(Auth::user()->hasRole('Administrador'), 403);

      $solicitacaoEmAnalise = Solicitacao::itens()->with(['parceiro.sigla', 'item'])->where('status', 'Em Análise')->orderBy('created_at', 'desc')->paginate(15);
      $solicitacaoAceita = Solicitacao::itens()->with(['parceiro.sigla', 'item'])->where('status', 'Aceita')->orderBy('created_at', 'desc')->paginate(15);
      $solicitacaoMontada = Solicitacao::itens()->with(['parceiro.sigla', 'item'])->where('status', 'Montada')->orderBy('created_at', 'desc')->paginate(15);
      $solicitacaoEntregue = Solicitacao::itens()->with(['parceiro.sigla', 'item'])->where('status', 'Entregue')->orderBy('created_at', 'desc')->paginate(15);
      $solicitacaoNaoAceita = Solicitacao::itens()->with(['parceiro.sigla', 'item'])->where('quantidade_nao_aceita', '>', 0)->orderBy('created_at', 'desc')->paginate(15);

      return view('solicitacoes.gerenciar_itens', compact(
         'solicitacaoEmAnalise',
         'solicitacaoAceita',
         'solicitacaoMontada',
         'solicitacaoEntregue',
         'solicitacaoNaoAceita'
      ));
   }

   public function atualizarStatusSolicitacao(Request $request, Solicitacao $solicitacao)
   {
      abort_unless(Auth::user()->hasRole('Administrador'), 403);

      $validated = $request->validate([
         'status' => 'required|string',
         'quantidade_aceita' => 'nullable|string|integer|lte:' . $solicitacao->quantidade_solicitada,
         'data_aceito' => 'nullable|date_format:Y-m-d\TH:i',
         'data_montada' => 'nullable|date_format:Y-m-d\TH:i',
         'data_entrega' => 'nullable|date_format:Y-m-d\TH:i',
      ]);

      if ($solicitacao->tipo !== 'item') {
         abort(404);
      }

      $solicitacao->status = $validated['status'];

      if (isset($validated['quantidade_aceita'])) {
         $quantidadeAceita = (int) $validated['quantidade_aceita'];
         $solicitacao->quantidade_aceita = $validated['quantidade_aceita'];
         $solicitacao->quantidade_nao_aceita = (int) $solicitacao->quantidade_solicitada - $quantidadeAceita;
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

      $solicitacao->save();
      ActivityLogger::log('atualizado', "Status da solicitação de item #{$solicitacao->id} atualizado para: {$solicitacao->status}");
      return redirect()->route('solicitacoes.gerenciar_itens')->with('success', 'Status do item atualizado com sucesso!');
   }
}
