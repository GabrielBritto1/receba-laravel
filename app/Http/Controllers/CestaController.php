<?php

namespace App\Http\Controllers;

use App\Models\Cesta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CestaController extends Controller
{
   /**
    * Display a listing of the resource.
    */
   public function index()
   {
      $cestas = Cesta::orderBy('created_at', 'desc')->get();
      $parceiro = Auth::user()->parceiros->first();
      return view('cestas.index', compact('cestas', 'parceiro'));
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
         'data_entrega' => 'required|date',
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
}
