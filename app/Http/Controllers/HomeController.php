<?php

namespace App\Http\Controllers;

use App\Models\Cesta;
use App\Models\Familia;
use App\Models\Parceiro;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
      $familias = Familia::join('representantes', 'familias.id', '=', 'representantes.id')->distinct('cpf')->count();

      if (! $user->can('Administrador') && ! $parceiro) {
         $familias = 0;
      }

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

      return view('dashboard', compact(
         'parceiros',
         'familias',
         'cestas',
         'chartLabels',
         'chartDeliveries',
         'chartOriginLabels',
         'chartOriginTotals'
      ));
   }
}
