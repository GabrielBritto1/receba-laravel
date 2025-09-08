<?php


namespace App\Http\Controllers;


use App\Models\Parceiro;
use App\Models\ParceiroSigla;
use App\Models\User;
use Illuminate\Http\Request;


class ParceiroController extends Controller
{
   /**
    * Display a listing of the resource.
    */
   public function index()
   {
      $parceiros = Parceiro::all();
      $coordenadores = User::whereHas('roles', function ($query) {
         $query->where('name', 'coordenador');
      })->get();
      return view('parceiros.index', compact('parceiros', 'coordenadores'));
   }


   /**
    * Show the form for creating a new resource.
    */
   public function create()
   {
      return view('parceiros.index');
   }


   /**
    * Store a newly created resource in storage.
    */
   public function store(Request $request)
   {
      $validated = $request->validate([
         'name' => 'required|string|max:255',
         'endereco' => 'required|string|max:255',
         'telefone' => 'required|string|max:15',
         'cep' => 'required|string|max:10',
         'cnpj' => 'nullable|string|max:18',
         'user_id' => 'required|exists:users,id',
      ]);


      $parceiro = Parceiro::create([
         'name' => $validated['name'],
         'endereco' => $validated['endereco'],
         'telefone' => $validated['telefone'],
         'cep' => $validated['cep'],
         'cnpj' => $validated['cnpj'] ?? null,
      ]);


      $parceiro->users()->attach($validated['user_id']);


      return redirect()->route('parceiros.index')->with(['success' => 'Parceiro criado com sucesso!', 'success_action' => 'store']);
   }


   /**
    * Display the specified resource.
    */
   public function show(string $id)
   {
      $parceiro = Parceiro::findOrFail($id);
      return view('parceiros.show', compact('parceiro'));
   }


   /**
    * Show the form for editing the specified resource.
    */
   public function edit(string $id)
   {
      $parceiro = Parceiro::findOrFail($id);
      return view('parceiros.edit', compact('parceiro'));
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
      $parceiro = Parceiro::findOrFail($id);
      $parceiro->delete();
      return redirect()->route('parceiros.index')->with(['success' => 'Parceiro excluído com sucesso!', 'success_action' => 'destroy']);
   }


   public function toggleStatus(string $id)
   {
      $parceiro = Parceiro::findOrFail($id);
      $parceiro->status = !$parceiro->status;
      $parceiro->save();


      return redirect()->route('parceiros.index')->with('success', 'Status atualizado com sucesso!');
   }


   public function alterarSigla(Request $request, string $id)
   {
      $parceiro = Parceiro::findOrFail($id);


      $validated = $request->validate([
         'sigla' => 'required|string|max:255',
         'color' => 'required|string|max:7',
      ]);


      // Atualiza ou cria a sigla do parceiro
      if ($parceiro->sigla) {
         $parceiro->sigla->update([
            'name' => $validated['sigla'],
            'color' => $validated['color'],
         ]);
      } else {
         $parceiro->sigla()->create([
            'name' => $validated['sigla'],
            'color' => $validated['color'],
         ]);
      }

      return redirect()->route('users.gerenciar_siglas')->with('success', 'Sigla atualizada com sucesso!');
   }
}
