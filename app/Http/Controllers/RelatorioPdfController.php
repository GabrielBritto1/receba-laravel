<?php

namespace App\Http\Controllers;

use App\Models\Cesta;
use App\Models\Familia;
use App\Models\Parceiro;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RelatorioPdfController extends Controller
{
   public function relatorio_saida_de_cesta()
   {
      $anosDisponiveis = Cesta::selectRaw('YEAR(data_entrega) as ano')
         ->whereNotNull('data_entrega')
         ->distinct()
         ->orderBy('ano', 'desc')
         ->pluck('ano');

      $user = Auth::user();

      if ($user->can('Administrador')) {
         $parceiros = Parceiro::orderBy('name')->get();
      } else {
         $parceiroDoUsuario = $user->parceiros->first();
         $parceiros = $parceiroDoUsuario ? collect([$parceiroDoUsuario]) : collect();
      }

      return view('relatorios.relatorios_pdf.relatorio_saida_de_cesta_config', compact('anosDisponiveis', 'parceiros'));
   }

   public function gerar_relatorio_saida_de_cesta(Request $request)
   {
      ini_set('memory_limit', '512M');
      set_time_limit(300);

      $query = Cesta::query()
         ->leftJoin('familias', 'cestas.familia_id', '=', 'familias.id')
         ->leftJoin('representantes', 'familias.representante_id', '=', 'representantes.id')
         ->leftJoin('parceiros', 'cestas.parceiro_id', '=', 'parceiros.id')
         ->leftJoin('parceiro_siglas', 'parceiros.id', '=', 'parceiro_siglas.parceiro_id')
         ->whereNotNull('cestas.data_entrega')
         ->where('cestas.status', 'Entregue');

      if ($request->filled('nome_representante')) {
         $query->where('representantes.nome', 'like', '%' . $request->nome_representante . '%');
      }

      $user = Auth::user();

      if ($user->can('Administrador')) {
         if ($request->filled('parceiro_id')) {
            $query->where('cestas.parceiro_id', $request->parceiro_id);
         }
      } else {
         $parceiroDoUsuario = $user->parceiros->first();

         if ($parceiroDoUsuario) {
            $query->where('cestas.parceiro_id', $parceiroDoUsuario->id);
         } else {
            $query->whereRaw('1 = 0');
         }
      }

      $dataInicial = $request->filled('data_inicial')
         ? Carbon::parse($request->input('data_inicial'))->startOfDay()
         : null;
      $dataFinal = $request->filled('data_final')
         ? Carbon::parse($request->input('data_final'))->endOfDay()
         : null;
      $periodoSelecionado = $request->input('ano_selecionado', 'todos_periodos');

      if ($dataInicial || $dataFinal) {
         if ($dataInicial && $dataFinal) {
            if ($dataInicial->gt($dataFinal)) {
               [$dataInicial, $dataFinal] = [$dataFinal->copy()->startOfDay(), $dataInicial->copy()->endOfDay()];
            }

            $query->whereBetween('cestas.data_entrega', [$dataInicial, $dataFinal]);
         } elseif ($dataInicial) {
            $query->where('cestas.data_entrega', '>=', $dataInicial);
         } else {
            $query->where('cestas.data_entrega', '<=', $dataFinal);
         }
      } elseif ($periodoSelecionado === 'periodo_atual') {
         $query->whereBetween('cestas.data_entrega', [
            Carbon::now()->subMonths(11)->startOfMonth(),
            Carbon::now()->endOfMonth(),
         ]);
      } elseif ($periodoSelecionado !== 'todos_periodos' && is_numeric($periodoSelecionado)) {
         $query->whereYear('cestas.data_entrega', $periodoSelecionado);
      }

      $filtroOrigemFoiEnviado = $request->has('origem_propria') || $request->has('origem_ifes');
      $origemPropriaSelecionada = $filtroOrigemFoiEnviado ? $request->has('origem_propria') : true;
      $origemIfesSelecionada = $filtroOrigemFoiEnviado ? $request->has('origem_ifes') : true;

      $origensSelecionadas = collect([
         $origemPropriaSelecionada ? 'Propria' : null,
         $origemIfesSelecionada ? 'IFES' : null,
         $origemPropriaSelecionada ? 'Própria' : null,
      ])->filter()->unique()->values();

      if ($origensSelecionadas->isNotEmpty()) {
         $query->whereIn('cestas.ponto_origem', $origensSelecionadas);
      } else {
         $query->whereRaw('1 = 0');
      }

      if ($request->boolean('ordem_alfabetica', true)) {
         $query->orderBy('representantes.nome');
      }

      if ($request->boolean('ordenar_data_entrega', true)) {
         $query->orderBy('cestas.data_entrega');
      }

      if (! $request->boolean('ordem_alfabetica', true) && ! $request->boolean('ordenar_data_entrega', true)) {
         $query->orderBy('cestas.data_entrega');
      }

      $entregas = $query
         ->selectRaw("
            COALESCE(representantes.nome, 'Família não identificada') as representante_nome,
            COALESCE(parceiro_siglas.name, parceiros.name, 'Sem parceiro') as parceiro_nome,
            COALESCE(cestas.ponto_origem, 'N/A') as origem,
            cestas.data_em_rota,
            cestas.data_entrega,
            cestas.data_recebimento
         ")
         ->get()
         ->map(function ($entrega) {
            $origem = $entrega->origem ?? 'N/A';

            return [
               'representante_nome' => $entrega->representante_nome,
               'parceiro_nome' => $entrega->parceiro_nome,
               'origem' => $origem,
               'data_saida' => optional($entrega->data_em_rota)->format('d/m/Y - H:i') ?? 'N/A',
               'data_entrega' => optional($entrega->data_entrega)->format('d/m/Y - H:i') ?? 'N/A',
               'data_saida_ifes' => $origem === 'IFES'
                  ? (optional($entrega->data_recebimento)->format('d/m/Y - H:i') ?? 'N/A')
                  : 'N/A',
            ];
         })
         ->chunk(25);

      $pdf = Pdf::loadView('relatorios.relatorios_pdf.relatorio_saida_de_cesta_pdf', compact('entregas'))->setPaper('a4', 'landscape');

      return $pdf->stream('relatorio_saida_de_cesta_pdf.pdf');
   }

   public function relatorio_parceiro()
   {
      $parceiros = Parceiro::with(['users', 'sigla'])->withCount('familias')->orderBy('name')->get();
      $pdf = Pdf::loadView('relatorios.relatorios_pdf.relatorio_parceiro_pdf', compact('parceiros'))->setPaper('a4', 'landscape');
      return $pdf->stream('relatorio_parceiro_pdf.pdf');
   }

   public function relatorio_familia()
   {
      $user = Auth::user();
      $query = Familia::with(['representante', 'parceiro.sigla']);

      if (! $user->can('Administrador')) {
         $parceiroDoUsuario = $user->parceiros->first();

         if ($parceiroDoUsuario) {
            $query->where('parceiro_id', $parceiroDoUsuario->id);
         } else {
            $query->whereRaw('1 = 0');
         }
      }

      $familias = $query
         ->get()
         ->sortBy(function ($familia) {
            return optional($familia->representante)->nome;
         })
         ->values();

      $pdf = Pdf::loadView('relatorios.relatorios_pdf.relatorio_familia_pdf', compact('familias'))->setPaper('a4', 'landscape');

      return $pdf->stream('relatorio_familia_pdf.pdf');
   }

   public function relatorio_saida_de_cesta_por_parceiro()
   {
      $inicioPeriodo = Carbon::now()->subMonths(11)->startOfMonth();
      $fimPeriodo = Carbon::now()->endOfMonth();
      $meses = CarbonPeriod::create($inicioPeriodo, '1 month', $fimPeriodo);

      $query = Cesta::with(['parceiro.sigla'])
         ->whereNotNull('data_entrega')
         ->where('status', 'Entregue')
         ->whereBetween('data_entrega', [$inicioPeriodo, $fimPeriodo]);

      $user = Auth::user();

      if (! $user->can('Administrador')) {
         $parceiroDoUsuario = $user->parceiros->first();

         if ($parceiroDoUsuario) {
            $query->where('parceiro_id', $parceiroDoUsuario->id);
         } else {
            $query->whereRaw('1 = 0');
         }
      }

      $entregasAgrupadas = $query->get()->groupBy('parceiro_id');

      $pdf = Pdf::loadView('relatorios.relatorios_pdf.relatorio_saida_de_cesta_por_parceiro_pdf', compact('entregasAgrupadas', 'meses'))->setPaper('a4', 'landscape');

      return $pdf->stream('relatorio_saida_de_cesta_por_parceiro_pdf.pdf');
   }
}
