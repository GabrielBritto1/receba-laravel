<?php

namespace App\Http\Controllers;

use App\Models\Cesta;
use App\Models\Familia;
use App\Models\Parceiro;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CestaController extends Controller
{
   /**
    * Display a listing of the resource.
    */
   public function index()
   {
      $user = Auth::user();
      $parceiro = $user->parceiros->first();

      if ($user->hasRole('Administrador')) {
         $cestasNaoSairam = Cesta::latest()
            ->where('status', 'Não saiu para entrega')
            ->paginate(2);
         $cestasEmRota = Cesta::latest()
            ->where('status', 'Em rota')
            ->paginate(2);
         $cestasEntregue = Cesta::latest()
            ->where('status', 'Entregue')
            ->paginate(2);
         $familias = Familia::all();
      } else {
         $cestasPorParceiro = collect();
         $familias = collect();
         if ($parceiro) {
            $cestasNaoSairam = Cesta::latest()
               ->where('parceiro_id', $parceiro->id)
               ->where('status', 'Não saiu para entrega')
               ->paginate(2);
            $cestasEmRota = Cesta::latest()
               ->where('parceiro_id', $parceiro->id)
               ->where('status', 'Em rota')
               ->paginate(2);
            $cestasEntregue = Cesta::latest()
               ->where('parceiro_id', $parceiro->id)
               ->where('status', 'Entregue')
               ->paginate(2);
            $familias = $parceiro->familias;
         }
      }
      $parceiros = Parceiro::orderBy('name')->get();
      return view('cestas.index', compact('cestasNaoSairam', 'cestasEmRota', 'cestasEntregue', 'parceiro', 'familias', 'parceiros'));
   }

   public function list(Request $request)
   {
      $user = Auth::user();
      $parceiro = $user->parceiros->first();

      $newQuery = function () {
         return Cesta::with(['parceiro.sigla', 'familia.representante']);
      };

      $applyFilters = function ($query) use ($user, $parceiro, $request) {
         if ($user->hasRole('Administrador')) {
            if ($request->filled('parceiro_id')) {
               $query->where('parceiro_id', $request->parceiro_id);
            }
         } else {
            $query->where('parceiro_id', $parceiro?->id ?? 0);
         }
         return $query;
      };

      $cestasNaoSairam = $applyFilters($newQuery()->latest()->where('status', 'Não saiu para entrega'))->paginate(15);
      $cestasEmRota = $applyFilters($newQuery()->latest()->where('status', 'Em rota'))->paginate(15);
      $cestasEntregue = $applyFilters($newQuery()->orderBy('updated_at', 'desc')->where('status', 'Entregue'))->paginate(15);

      $familias = $user->hasRole('Administrador') ? Familia::all() : ($parceiro ? $parceiro->familias : collect());

      return response()->json([
         'status' => 'success',
         'cestasNaoSairam' => $cestasNaoSairam->items(),
         'cestasEmRota' => $cestasEmRota->items(),
         'cestasEntregue' => $cestasEntregue->items(),
         'familias' => $familias,
         'paginationNaoSairam' => [
            'current_page' => $cestasNaoSairam->currentPage(),
            'last_page' => $cestasNaoSairam->lastPage(),
         ],
         'paginationEmRota' => [
            'current_page' => $cestasEmRota->currentPage(),
            'last_page' => $cestasEmRota->lastPage(),
         ],
         'paginationEntregues' => [
            'current_page' => $cestasEntregue->currentPage(),
            'last_page' => $cestasEntregue->lastPage(),
         ]
      ]);
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
         'data_entrega' => 'required|date_format:Y-m-d\TH:i',
         'quantidade_total' => 'required|string',
      ]);

      $cesta = Cesta::create([
         'data_entrega' => $validated['data_entrega'],
         'quantidade_total' => $validated['quantidade_total'],
         'parceiro_id' => $parceiroId,
      ]);

      return redirect()->route('cestas.index')->with('success', 'Cesta solicitada com sucesso!');
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

   public function entregaCestaPropria(Request $request)
   {
      $user = Auth::user();
      $parceiroId = $user->parceiros->first()->id;

      $validated = $request->validate([
         'familia_id' => 'required|exists:familias,id',
         'data_entrega' => 'required|date_format:Y-m-d\TH:i',
      ]);

      Cesta::create([
         'familia_id' => $validated['familia_id'],
         'data_entrega' => $validated['data_entrega'],
         'data_em_rota' => $validated['data_entrega'],
         'ponto_origem' => 'Própria',
         'status' => 'Entregue',
         'parceiro_id' => $parceiroId,
      ]);

      return redirect()->route('cestas.index')->with('success', 'Cesta própria registrada com sucesso!');
   }

   public function entregaFamilia(string $id)
   {
      $cesta = Cesta::findOrFail($id);
      $user = Auth::user();
      $parceiro = $user->parceiros->first();
      if (!$parceiro) {
         throw new \Exception('O usuário logado não está vinculado a nenhum parceiro.');
      }
      $familias = $parceiro->familias;
      return view('cestas.entrega_familia', compact('cesta', 'familias'));
   }

   public function entregaFamiliaStore(Request $request, string $id)
   {
      $validated = $request->validate([
         'familia_id' => 'required|exists:familias,id',
      ]);

      $cesta = Cesta::findOrFail($id);
      $cesta->familia_id = $validated['familia_id'];
      $cesta->data_em_rota = Carbon::now()->format('Y-m-d');
      $cesta->status = 'Em rota';
      $cesta->save();

      return redirect()->route('cestas.index')->with('success', 'Cesta em rota!');
   }

   public function entregaFamiliaIfes(Request $request, Cesta $cesta)
   {
      $cesta->data_entrega = Carbon::now();
      $cesta->status = 'Entregue';
      $cesta->save();

      return redirect()->route('cestas.index')->with('success', 'Cesta entregue!');
   }
}
