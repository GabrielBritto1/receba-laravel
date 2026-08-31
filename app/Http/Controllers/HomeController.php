<?php

namespace App\Http\Controllers;

use App\Models\Cesta;
use App\Models\Familia;
use App\Models\Parceiro;
use App\Models\Solicitacao;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class HomeController extends Controller
{
   /**
    * Create a new controller instance.
    *
    * @return void
    */
   public function __construct()
   {
      $this->middleware('auth');
   }

   /**
    * Show the application dashboard.
    *
    * @return \Illuminate\Contracts\Support\Renderable
    */
   public function index()
   {
      $user = Auth::user();
      $parceiro = $user->parceiros->first();

      if ($user->can('Administrador')) {
         $cestas = Cesta::where('status', 'Entregue')->count();
      } elseif ($parceiro) {
         $cestas = Cesta::where('status', 'Entregue')->where('parceiro_id', $parceiro->id)->count();
      } else {
         $cestas = 0;
      }

      $parceiros = Parceiro::count();

      $solicitacoesQuery = Solicitacao::where('status', 'Em Análise');
      if ($user->can('Administrador')) {
         $solicitacoesPendentes = $solicitacoesQuery->count();
      } elseif ($parceiro) {
         $solicitacoesPendentes = (clone $solicitacoesQuery)->where('parceiro_id', $parceiro->id)->count();
      } else {
         $solicitacoesPendentes = 0;
      }

      $familiasQuery = Familia::join('representantes', 'familias.id', '=', 'representantes.id');
      if ($user->can('Administrador')) {
         // admin ve o total, sem duplicar representantes com mais de uma familia
      } elseif ($parceiro) {
         $familiasQuery->where('familias.parceiro_id', $parceiro->id);
      } else {
         $familiasQuery->whereRaw('1 = 0');
      }
      $familias = $familiasQuery->distinct('representantes.cpf')->count('representantes.cpf');

      $cestasQuery = Cesta::query()
         ->whereNotNull('data_entrega')
         ->where('status', 'Entregue');

      if (! $user->can('Administrador') && $parceiro) {
         $cestasQuery->where('parceiro_id', $parceiro->id);
      } elseif (! $user->can('Administrador')) {
         $cestasQuery->whereRaw('1 = 0');
      }

      $entregasPorMes = (clone $cestasQuery)
         ->selectRaw('YEAR(data_entrega) as ano, MONTH(data_entrega) as mes, COUNT(*) as total')
         ->where('data_entrega', '>=', Carbon::now()->startOfMonth()->subMonths(11))
         ->groupBy('ano', 'mes')
         ->orderBy('ano')
         ->orderBy('mes')
         ->get()
         ->keyBy(function ($item) {
            return sprintf('%04d-%02d', $item->ano, $item->mes);
         });

      $meses = collect(range(11, 0))->map(function ($offset) {
         return Carbon::now()->startOfMonth()->subMonths($offset);
      });

      $chartLabels = $meses->map(function ($date) {
         return $date->translatedFormat('M/Y');
      })->values();

      $chartDeliveries = $meses->map(function ($date) use ($entregasPorMes) {
         $key = $date->format('Y-m');

         return (int) optional($entregasPorMes->get($key))->total;
      })->values();

      $origens = (clone $cestasQuery)
         ->selectRaw('ponto_origem, COUNT(*) as total')
         ->groupBy('ponto_origem')
         ->orderByDesc('total')
         ->get();

      $chartOriginLabels = $origens->pluck('ponto_origem')->map(function ($value) {
         return $value ?: 'Não informado';
      })->values();

      $chartOriginTotals = $origens->pluck('total')->map(function ($value) {
         return (int) $value;
      })->values();

      // Solicitações: base query respeitando o escopo do usuário
      $solicitacoesQuery = Solicitacao::query();
      if (! $user->can('Administrador') && $parceiro) {
         $solicitacoesQuery->where('parceiro_id', $parceiro->id);
      } elseif (! $user->can('Administrador')) {
         $solicitacoesQuery->whereRaw('1 = 0');
      }

      $solicitacoesPorMesRaw = (clone $solicitacoesQuery)
         ->selectRaw('YEAR(created_at) as ano, MONTH(created_at) as mes, COUNT(*) as total')
         ->where('created_at', '>=', Carbon::now()->startOfMonth()->subMonths(11))
         ->groupBy('ano', 'mes')
         ->orderBy('ano')
         ->orderBy('mes')
         ->get()
         ->keyBy(function ($item) {
            return sprintf('%04d-%02d', $item->ano, $item->mes);
         });

      $chartRequestData = $meses->map(function ($date) use ($solicitacoesPorMesRaw) {
         return (int) optional($solicitacoesPorMesRaw->get($date->format('Y-m')))->total;
      })->values();

      $statusRaw = (clone $solicitacoesQuery)
         ->selectRaw('status, COUNT(*) as total')
         ->groupBy('status')
         ->orderByDesc('total')
         ->get();

      $chartStatusLabels = $statusRaw->pluck('status')->values();
      $chartStatusTotals = $statusRaw->pluck('total')->map(fn($v) => (int) $v)->values();

      $mapaEntregas = collect();
      $bairroConcentracao = null;

      if (Schema::hasColumn('familias', 'latitude')) {
         // Centro de Alegre-ES como fallback para endereços sem coordenadas
         $alegreLat = -20.7618;
         $alegreLng = -41.5325;

         $entreguesQuery = Cesta::query()
            ->where('status', 'Entregue')
            ->whereNotNull('familia_id');

         if ($user->can('Administrador')) {
            // vê todas
         } elseif ($parceiro) {
            $entreguesQuery->where('parceiro_id', $parceiro->id);
         } else {
            $entreguesQuery->whereRaw('1 = 0');
         }

         $porFamilia = (clone $entreguesQuery)
            ->selectRaw('familia_id, COUNT(*) as total, MAX(data_entrega) as ultima_entrega')
            ->groupBy('familia_id')
            ->get()
            ->keyBy('familia_id');

         if ($porFamilia->isNotEmpty()) {
            $familiasEntregues = Familia::with('representante')
               ->whereIn('id', $porFamilia->keys())
               ->get();

            $mapaEntregas = $familiasEntregues->map(function ($f) use ($porFamilia, $alegreLat, $alegreLng) {
               $info = $porFamilia->get($f->id);

               return [
                  'lat'       => $f->latitude  ? (float) $f->latitude  : $alegreLat,
                  'lng'       => $f->longitude ? (float) $f->longitude : $alegreLng,
                  'nome'      => optional($f->representante)->nome ?? 'Família #' . $f->id,
                  'end'       => implode(', ', array_filter([$f->endereco, $f->numero_casa, $f->bairro, $f->cidade])),
                  'bairro'    => $f->bairro,
                  'qtd'       => (int) $info->total,
                  'ultima'    => optional($info->ultima_entrega)->format('d/m/Y'),
                  'aproximado' => ! ($f->latitude && $f->longitude),
               ];
            })->sortByDesc('qtd')->values();

            $bairroConcentracao = $mapaEntregas
               ->groupBy(fn($p) => $p['bairro'] ?: 'Não informado')
               ->map(fn($grupo, $bairro) => ['bairro' => $bairro, 'total' => $grupo->sum('qtd')])
               ->sortByDesc('total')
               ->first();
         }
      }

      return view('dashboard', compact(
         'parceiros',
         'familias',
         'cestas',
         'solicitacoesPendentes',
         'chartLabels',
         'chartDeliveries',
         'chartOriginLabels',
         'chartOriginTotals',
         'chartRequestData',
         'chartStatusLabels',
         'chartStatusTotals',
         'mapaEntregas',
         'bairroConcentracao'
      ));
   }

   /**
    * Exporta a relação de cestas entregues (endereço e quantidade) em CSV.
    *
    * @return \Symfony\Component\HttpFoundation\StreamedResponse
    */
   public function exportarEntregas()
   {
      $user = Auth::user();
      $parceiro = $user->parceiros->first();

      abort_unless($user->can('Administrador') || $parceiro, 403);

      $entreguesQuery = Cesta::query()
         ->where('status', 'Entregue')
         ->whereNotNull('familia_id');

      if (! $user->can('Administrador')) {
         $entreguesQuery->where('parceiro_id', $parceiro->id);
      }

      $porFamilia = (clone $entreguesQuery)
         ->selectRaw('familia_id, COUNT(*) as total, MAX(data_entrega) as ultima_entrega')
         ->groupBy('familia_id')
         ->get()
         ->keyBy('familia_id');

      $familias = Familia::with('representante')
         ->whereIn('id', $porFamilia->keys())
         ->get()
         ->sortByDesc(fn($f) => $porFamilia->get($f->id)->total);

      $nomeArquivo = 'entregas-cestas-' . now()->format('Y-m-d') . '.csv';

      $callback = function () use ($familias, $porFamilia) {
         $saida = fopen('php://output', 'w');
         fwrite($saida, "\xEF\xBB\xBF"); // BOM para acentuação correta no Excel

         fputcsv($saida, ['Família', 'Endereço', 'Número', 'Bairro', 'Cidade', 'CEP', 'Quantidade de Cestas Entregues', 'Última Entrega'], ';');

         foreach ($familias as $f) {
            $info = $porFamilia->get($f->id);

            fputcsv($saida, [
               optional($f->representante)->nome ?? 'Família #' . $f->id,
               $f->endereco,
               $f->numero_casa,
               $f->bairro,
               $f->cidade,
               $f->cep,
               $info->total,
               optional($info->ultima_entrega)->format('d/m/Y'),
            ], ';');
         }

         fclose($saida);
      };

      return response()->streamDownload($callback, $nomeArquivo, [
         'Content-Type' => 'text/csv; charset=UTF-8',
      ]);
   }
}
