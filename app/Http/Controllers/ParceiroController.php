<?php


namespace App\Http\Controllers;


use App\Models\Parceiro;
use App\Models\ParceiroSigla;
use App\Models\User;
use Illuminate\Http\Request;
use App\Support\ActivityLogger;
use Illuminate\Support\Facades\Auth;

class ParceiroController extends Controller
{
   /**
    * Display a listing of the resource.
    */
   public function index()
   {
      $user = Auth::user();
      if ($user->can('Administrador')) {
         $parceiros = Parceiro::all();
      } else {
         $parceiros = $user->parceiros->first();
         $parceiros = $parceiros ? [$parceiros] : [];
      }
      $coordenadores = User::whereHas('roles', function ($query) {
         $query->where('name', 'Coordenador');
      })->get();
      $secretarios = User::whereHas('roles', function ($query) {
         $query->where('name', 'Secretario');
      })->get();
      return view('parceiros.index', compact('parceiros', 'coordenadores', 'secretarios'));
   }

   public function list()
   {
      $parceiros = Parceiro::with('sigla')->orderBy('name')->paginate(15);

      return response()->json([
         'status' => 'success',
         'data' => $parceiros->items(),
         'pagination' => [
            'current_page' => $parceiros->currentPage(),
            'last_page' => $parceiros->lastPage(),
         ]
      ]);
   }

   public function meuParceiro()
   {
      $user = Auth::user();
      $parceiros = $user->parceiros->first();
      $parceiros = $parceiros ? [$parceiros] : [];

      $users = User::whereHas('parceiros', function ($query) use ($parceiros) {
         $query->whereIn('parceiro_id', collect($parceiros)->pluck('id'));
      })->get();

      $integrantes = $users->filter(function ($user) {
         return $user;
      });

      return view('parceiros.meu_parceiro', compact('parceiros', 'integrantes'));
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
         'local_atuacao' => 'required|string',

         'coordenador_id' => 'required|exists:users,id',
         'secretario_id' => 'nullable|exists:users,id',
      ]);

      $parceiro = Parceiro::create([
         'name' => $validated['name'],
         'endereco' => $validated['endereco'],
         'telefone' => $validated['telefone'],
         'cep' => $validated['cep'],
         'local_atuacao' => $validated['local_atuacao'],
         'cnpj' => $validated['cnpj'] ?? null,
      ]);

      $parceiro->users()->attach(array_filter([
         $validated['coordenador_id'],
         $validated['secretario_id'] ?? null,
      ]));

      ActivityLogger::log('criado', "Parceiro criado: {$parceiro->name}");
      return redirect()->route('parceiros.index')->with(['success' => 'Parceiro criado com sucesso!', 'success_action' => 'store']);
   }


   /**
    * Display the specified resource.
    */
   public function show(string $id)
   {
      $user = Auth::user();
      if ($user->hasRole('Administrador')) {
         $parceiro = Parceiro::with('users.roles')->findOrFail($id);
         $coordenadores = $parceiro->users->filter(function ($user) {
            return $user->hasRole('Coordenador');
         });
         $secretarios = $parceiro->users->filter(function ($user) {
            return $user->hasRole('Secretario');
         });
      } else {
         $parceiro = Auth::user()->parceiros->first();
         if (!$parceiro || $parceiro->id != $id) {
            abort(403, 'Você não tem permissão para acessar esse parceiro.');
         }
         $coordenadores = $parceiro->users->filter(function ($user) {
            return $user->hasRole('Coordenador');
         });
         $secretarios = $parceiro->users->filter(function ($user) {
            return $user->hasRole('Secretario');
         });
      }

      return view('parceiros.show', compact('parceiro', 'coordenadores', 'secretarios'));
   }


   /**
    * Show the form for editing the specified resource.
    */
   public function edit(string $id)
   {
      $user = Auth::user();
      if ($user->hasRole('Administrador')) {
         $parceiro = Parceiro::findOrFail($id);
      } else {
         $parceiro = Auth::user()->parceiros->first();
         if (!$parceiro || $parceiro->id != $id) {
            abort(403, 'Você não tem permissão para editar esse parceiro.');
         }
      }
      return view('parceiros.edit', compact('parceiro'));
   }


   /**
    * Update the specified resource in storage.
    */
   public function update(Request $request, string $id)
   {
      $parceiro = Parceiro::findOrFail($id);
      $validated = $request->validate([
         'name' => 'required|string|max:255',
         'endereco' => 'required|string|max:255',
         'telefone' => 'required|string|max:15',
         'cep' => 'required|string|max:10',
         'cnpj' => 'nullable|string|max:18',
      ]);
      $parceiro->update($validated);
      ActivityLogger::log('atualizado', "Parceiro atualizado: {$parceiro->name}");
      return redirect()->route('parceiros.index')->with('success', 'Parceiro atualizado com sucesso!');
   }


   /**
    * Remove the specified resource from storage.
    */
   public function destroy(string $id)
   {
      $parceiro = Parceiro::findOrFail($id);
      $nomeParceiro = $parceiro->name;
      $parceiro->delete();
      ActivityLogger::log('removido', "Parceiro excluído: {$nomeParceiro}");
      return redirect()->route('parceiros.index')->with(['success' => 'Parceiro excluído com sucesso!', 'success_action' => 'destroy']);
   }


   public function toggleStatus(string $id)
   {
      $parceiro = Parceiro::findOrFail($id);
      $parceiro->status = !$parceiro->status;
      $parceiro->save();
      $statusLabel = $parceiro->status ? 'ativado' : 'desativado';
      ActivityLogger::log('atualizado', "Parceiro {$statusLabel}: {$parceiro->name}");
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

      ActivityLogger::log('atualizado', "Sigla do parceiro {$parceiro->name} atualizada para: {$validated['sigla']}");
      return redirect()->route('users.gerenciar_siglas')->with('success', 'Sigla atualizada com sucesso!');
   }
}
