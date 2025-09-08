<?php

namespace App\Http\Controllers;

use App\Models\Cesta;
use Illuminate\Http\Request;

class EntregaController extends Controller
{
   /**
    * Display a listing of the resource.
    */
   public function index()
   {
      return view('entregas.index');
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

   public function gerenciarCestas()
   {
      $cestaEmAnalise = Cesta::where('status', 'Em Análise')->orderBy('created_at', 'desc')->get();
      $cestaAceita = Cesta::where('status', 'Aceita')->orderBy('created_at', 'desc')->get();
      $cestaMontada = Cesta::where('status', 'Montada')->orderBy('created_at', 'desc')->get();
      $cestaEntregue = Cesta::where('status', 'Entregue')->orderBy('created_at', 'desc')->get();

      return view('entregas.gerenciar_cestas', compact('cestaEmAnalise', 'cestaAceita', 'cestaMontada', 'cestaEntregue'));
   }

   public function atualizarStatusCesta(Request $request, Cesta $cesta)
   {
      $validated = $request->validate([
         'status' => 'required|string',
      ]);
      $cesta->status = $validated['status'];
      $cesta->save();

      return redirect()->route('entregas.gerenciar_cestas')->with('success', 'Status da cesta atualizado com sucesso!');
   }

   public function gerenciarItens()
   {
      return view('entregas.gerenciar_itens');
   }
}
