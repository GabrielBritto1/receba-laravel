<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Coordenador;
use App\Models\Parceiro;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
   public function index()
   {
      $users = User::paginate(15);
      return view('admin.users.index', compact('users'));
   }

   public function create()
   {
      return view('admin.users.create');
   }

   public function store(StoreUserRequest $request)
   {
      User::create($request->validated());
      return redirect()->route('users.index')->with('success', 'Usuário criado com sucesso!');
   }

   public function edit(string $id)
   {
      if (!$user = User::find($id)) {
         return redirect()->route('users.index')->with('message', 'Usuário não encontrado!');
      }
      return view('admin.users.edit', compact('user'));
   }

   public function update(UpdateUserRequest $request, string $id)
   {
      if (!$user = User::find($id)) {
         return redirect()->back()->with('message', 'Usuário não encontrado!');
      }

      $data = $request->only('name', 'email');
      if ($request->password) {
         $data['password'] = bcrypt($request->password);
      }
      $user->update($data);

      return redirect()->route('users.configuracao', $user->id)->with('success', 'Usuário editado com sucesso!');
   }

   public function show(string $id)
   {
      if (!$user = User::find($id)) {
         return redirect()->route('users.index')->with('message', 'Usuário não encontrado!');
      }
      return view('admin.users.show', compact('user'));
   }

   public function destroy(string $id)
   {
      // if (Gate::allows('Administrador')) {
      //     return back()->with('message', 'Você não é um administrador!');
      // }

      // if (Gate::denies('Administrador')) {
      //     return back()->with('message', 'Você não é um administrador!');
      // }

      if (!$user = User::find($id)) {
         return redirect()->route('users.index')->with('message', 'Usuário não encontrado!');
      }

      if (Auth::user()->id === $user->id) {
         return back()->with('message', 'Você não pode excluir seu próprio usuário!');
      }
      $user->delete();

      return redirect()->route('users.index')->with('success', 'Usuário deletado com sucesso!');
   }

   public function configuracao(string $id)
   {
      $user = User::findOrFail($id);
      if (!$user = User::find($id)) {
         return redirect()->route('/dashboard')->with('message', 'Usuário não encontrado!');
      }
      return view('admin.users.configuracao', compact('user'));
   }

   public function gerenciarUsuarios()
   {
      $users = User::all();
      return view('admin.users.gerenciar_usuarios', compact('users'));
   }
   public function gerenciarSiglas()
   {
      $parceiros = Parceiro::with('sigla')->get();
      return view('admin.users.gerenciar_siglas', compact('parceiros'));
   }

   public function storeCoordenador(Request $request)
   {
      $request->validate([
         'name' => 'required',
         'email' => 'required',
         'endereco' => 'required',
         'telefone' => 'required',
         'cpf' => 'required'
      ]);

      $user = User::create([
         'name' => $request->name,
         'email' => $request->email,
         'password' => Hash::make($request->email),
      ]);
      $user->coordenador()->create([
         'endereco' => $request->endereco,
         'telefone' => $request->telefone,
         'cpf' => $request->cpf,
      ]);
      $role = Role::where('name', 'Coordenador')->first();
      if ($role) {
         $user->assignRole($role);
      }
      return redirect()->route('parceiros.index')->with(['success' => 'Coordenador inserido com sucesso!', 'success_action' => 'storeCoordenador']);
   }

   public function storeSecretario(Request $request)
   {
      $request->validate([
         'name' => 'required',
         'email' => 'required',
         'endereco' => 'required',
         'telefone' => 'required',
         'cpf' => 'required'
      ]);

      $user = User::create([
         'name' => $request->name,
         'email' => $request->email,
         'password' => Hash::make($request->email),
      ]);
      $user->secretario()->create([
         'endereco' => $request->endereco,
         'telefone' => $request->telefone,
         'cpf' => $request->cpf,
      ]);
      $role = Role::where('name', 'Secretario')->first();
      if ($role) {
         $user->assignRole($role);
      }
      return redirect()->route('parceiros.index')->with(['success' => 'Secretário inserido com sucesso!', 'success_action' => 'storeSecretario']);
   }

   public function storeSecretarioAssociar(Request $request)
   {
      $request->validate([
         'name' => 'required',
         'email' => 'required',
         'endereco' => 'required',
         'telefone' => 'required',
         'cpf' => 'required',
         'parceiro_id' => 'required|exists:parceiros,id'
      ]);

      $user = User::create([
         'name' => $request->name,
         'email' => $request->email,
         'password' => Hash::make($request->email),
      ]);
      $user->secretario()->create([
         'endereco' => $request->endereco,
         'telefone' => $request->telefone,
         'cpf' => $request->cpf,
      ]);

      $user->parceiros()->attach($request->parceiro_id);

      $role = Role::where('name', 'Secretario')->first();
      if ($role) {
         $user->assignRole($role);
      }

      if (Auth::user()->hasRole('Administrador')) {
         return redirect()->route('parceiros.index')->with(['success' => 'Secretário inserido com sucesso!', 'success_action' => 'storeSecretario']);
      } else {
         return redirect()->route('parceiros.meu_parceiro')->with(['success' => 'Secretário inserido com sucesso!', 'success_action' => 'storeSecretario']);
      }
   }
}
