<?php

namespace App\Http\Controllers;

use App\Models\Cesta;
use App\Models\Familia;
use App\Models\Parceiro;
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
      } else {
         $cestas = Cesta::where('status', 'Entregue')->where('parceiro_id', $parceiro->id)->count();
      }

      $parceiros = Parceiro::count();
      $familias = Familia::join('representantes', 'familias.id', '=', 'representantes.id')->distinct('cpf')->count();

      if ($user->can('Administrador')) {
         $cestaPorAno = Cesta::selectRaw('YEAR(data_entrega) as ano, COUNT(*) as total, ponto_origem')
            ->whereNotNull('data_entrega')
            ->groupBy('ano', 'ponto_origem')
            ->orderBy('ano', 'asc')
            ->get();
      } else {
         $cestaPorAno = Cesta::selectRaw('YEAR(data_entrega) as ano, COUNT(*) as total, ponto_origem')
            ->whereNotNull('data_entrega')
            ->where('parceiro_id', $parceiro->id)
            ->groupBy('ano', 'ponto_origem')
            ->orderBy('ano', 'asc')
            ->get();
      }
      $anosCesta = $cestaPorAno->pluck('ano');
      $cestasEntreguesPorAno = $cestaPorAno->pluck('total');
      $cestaPorAnoOrigem = $cestaPorAno->pluck('ponto_origem');

      return view('dashboard', compact('parceiros', 'familias', 'cestas', 'anosCesta', 'cestasEntreguesPorAno', 'cestaPorAnoOrigem'));
   }
}
