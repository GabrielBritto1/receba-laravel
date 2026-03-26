<?php

namespace App\Http\Controllers;

use App\Models\Cesta;
use App\Models\Parceiro;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RelatorioController extends Controller
{
   /**
    * Display a listing of the resource.
    */
   public function index()
   {
      //
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
      //
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

   public function RelatorioVisual()
   {
      return view('relatorios.relatorio_visual');
   }

   public function RelatorioPdf()
   {
      return view('relatorios.relatorio_pdf');
   }

   public function RelatorioPlanilha()
   {
      return view('relatorios.relatorio_planilha');
   }

   // RELATORIOS VISUAIS TELAS
   public function RelatorioSaidaDeCesta(Request $request)
   {
      // --- 1. BUSCA ANOS PARA O FILTRO ---
      $anosDisponiveis = Cesta::selectRaw('YEAR(data_entrega) as ano')
         ->whereNotNull('data_entrega')
         ->distinct()
         ->orderBy('ano', 'desc')
         ->pluck('ano');

      // --- 2. INÍCIO DA CONSULTA ---
      $query = Cesta::with(['familia.representante', 'parceiro.sigla'])
         ->whereNotNull('data_entrega')
         ->where('status', 'Entregue');

      // --- 3. FILTRO DE PERÍODO ---
      if ($request->filled('ano_selecionado') && $request->ano_selecionado != 'periodo_atual') {
         $ano = $request->ano_selecionado;
         $query->whereYear('data_entrega', $ano);
         $meses = CarbonPeriod::create(Carbon::create($ano, 1, 1), '1 month', Carbon::create($ano, 12, 1));
      } else {
         $inicioPeriodo = Carbon::now()->subMonths(11)->startOfMonth();
         $fimPeriodo = Carbon::now()->endOfMonth();
         $query->whereBetween('data_entrega', [$inicioPeriodo, $fimPeriodo]);
         $meses = CarbonPeriod::create($inicioPeriodo, '1 month', $fimPeriodo);
      }

      // --- 4. FILTRO DE AUTORIZAÇÃO E PARCEIRO ---
      $user = Auth::user();

      if ($user->can('Administrador')) {
         if ($request->filled('parceiro_id')) {
            $query->where('parceiro_id', $request->parceiro_id);
         }
      } else {
         $parceiroDoUsuario = $user->parceiros->first();
         if ($parceiroDoUsuario) {
            $query->where('parceiro_id', $parceiroDoUsuario->id);
         } else {
            $query->whereRaw('1 = 0');
         }
      }

      // ==========================================================
      //  ▼▼▼ FILTRO FALTANDO ADICIONADO AQUI ▼▼▼
      // ==========================================================
      if ($request->filled('nome_representante')) {
         $query->whereHas('familia.representante', function ($q) use ($request) {
            $q->where('nome', 'like', '%' . $request->nome_representante . '%');
         });
      }
      // ==========================================================

      $entregas = $query->get();

      // --- 5. AGRUPAMENTO DOS DADOS ---
      $familiasAgrupadas = $entregas->groupBy(function ($entrega) {
         return $entrega->familia_id . '-' . $entrega->parceiro_id;
      });

      $parceiros = Parceiro::orderBy('name')->get();

      return view('relatorios.relatorios_visuais_telas.relatorio_saida_de_cesta', [
         'familiasAgrupadas' => $familiasAgrupadas,
         'meses' => $meses,
         'parceiros' => $parceiros,
         'anosDisponiveis' => $anosDisponiveis,
      ]);
   }

   public function RelatorioParceiro(Request $request)
   {
      // 1. Inicia a consulta na tabela de parceiros
      $query = Parceiro::query();

      // 2. Aplica o filtro de NOME, se ele foi preenchido
      if ($request->filled('nome_parceiro')) {
         $query->where('name', 'like', '%' . $request->nome_parceiro . '%');
      }

      // 3. Aplica o filtro de STATUS, se não for 'todos'
      $statusFiltro = $request->input('status_parceria', 'todos'); // 'todos' é o padrão

      if ($statusFiltro === 'ativo') {
         $query->where('status', 1); // Assumindo que 1 = Ativo
      } elseif ($statusFiltro === 'inativo') {
         $query->where('status', 0); // Assumindo que 0 = Inativo
      }
      // Se for 'todos', nenhum filtro de status é aplicado.

      // 4. Executa a consulta e ordena pelo nome
      $parceiros = $query->orderBy('name', 'asc')->get();
      return view('relatorios.relatorios_visuais_telas.relatorio_parceiro', compact('parceiros'));
   }
}
