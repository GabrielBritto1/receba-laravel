<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Cesta;
use App\Models\Coordenador;
use App\Models\Familia;
use App\Models\Parceiro;
use App\Models\Solicitacao;
use App\Models\User;
use Carbon\Carbon;
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

      $timelinePartner = $user->parceiros->first();
      $weeklyTimeline = collect();
      $monthlyTimeline = collect();

      if ($timelinePartner) {
         $weeklyTimeline = $this->buildTimelineGroups($timelinePartner, Carbon::now()->subDays(7), 8);
         $monthlyTimeline = $this->buildTimelineGroups($timelinePartner, Carbon::now()->subDays(30), 14);
      }

      return view('admin.users.configuracao', compact('user', 'timelinePartner', 'weeklyTimeline', 'monthlyTimeline'));
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

   private function buildTimelineGroups(Parceiro $parceiro, Carbon $since, int $limit)
   {
      $events = collect();

      $familyEvents = Familia::with('representante:id,nome')
         ->where('parceiro_id', $parceiro->id)
         ->where('created_at', '>=', $since)
         ->get()
         ->map(function ($familia) {
            $name = optional($familia->representante)->nome ?: 'Família';

            return [
               'date' => $familia->created_at,
               'icon' => 'fas fa-user-plus',
               'background' => 'bg-info',
               'title' => 'Nova família cadastrada',
               'description' => $name . ' foi vinculada ao parceiro.',
            ];
         });

      $basketReceivedEvents = Cesta::where('parceiro_id', $parceiro->id)
         ->whereNotNull('data_recebimento')
         ->where('data_recebimento', '>=', $since)
         ->get()
         ->map(function ($cesta) {
            return [
               'date' => $cesta->data_recebimento,
               'icon' => 'fas fa-box-open',
               'background' => 'bg-primary',
               'title' => 'Cesta recebida',
               'description' => 'Uma cesta foi registrada no parceiro.',
            ];
         });

      $basketRouteEvents = Cesta::with('familia.representante:id,nome')
         ->where('parceiro_id', $parceiro->id)
         ->whereNotNull('data_em_rota')
         ->where('data_em_rota', '>=', $since)
         ->get()
         ->map(function ($cesta) {
            $name = optional(optional($cesta->familia)->representante)->nome ?: 'família não identificada';

            return [
               'date' => $cesta->data_em_rota,
               'icon' => 'fas fa-shipping-fast',
               'background' => 'bg-warning',
               'title' => 'Cesta saiu para entrega',
               'description' => 'Saída registrada para ' . $name . '.',
            ];
         });

      $basketDeliveredEvents = Cesta::with('familia.representante:id,nome')
         ->where('parceiro_id', $parceiro->id)
         ->whereNotNull('data_entrega')
         ->where('data_entrega', '>=', $since)
         ->get()
         ->map(function ($cesta) {
            $name = optional(optional($cesta->familia)->representante)->nome ?: 'família não identificada';

            return [
               'date' => $cesta->data_entrega,
               'icon' => 'fas fa-check',
               'background' => 'bg-success',
               'title' => 'Cesta entregue',
               'description' => 'Entrega concluída para ' . $name . '.',
            ];
         });

      $requestCreatedEvents = Solicitacao::where('parceiro_id', $parceiro->id)
         ->where('created_at', '>=', $since)
         ->get()
         ->map(function ($solicitacao) {
            $tipo = $solicitacao->tipo === 'item' ? 'item(ns)' : 'cesta(s)';

            return [
               'date' => $solicitacao->created_at,
               'icon' => 'fas fa-file-alt',
               'background' => 'bg-secondary',
               'title' => 'Solicitação criada',
               'description' => 'Pedido com ' . $solicitacao->quantidade_solicitada . ' ' . $tipo . ' solicitado(s).',
            ];
         });

      $requestAcceptedEvents = Solicitacao::where('parceiro_id', $parceiro->id)
         ->whereNotNull('data_aceito')
         ->where('data_aceito', '>=', $since)
         ->get()
         ->map(function ($solicitacao) {
            $tipo = $solicitacao->tipo === 'item' ? 'item(ns)' : 'cesta(s)';

            return [
               'date' => $solicitacao->data_aceito,
               'icon' => 'fas fa-thumbs-up',
               'background' => 'bg-primary',
               'title' => 'Solicitação aceita',
               'description' => 'Aceite registrado com ' . $solicitacao->quantidade_aceita . ' ' . $tipo . '.',
            ];
         });

      $requestDeliveredEvents = Solicitacao::where('parceiro_id', $parceiro->id)
         ->whereNotNull('data_entrega')
         ->where('data_entrega', '>=', $since)
         ->get()
         ->map(function ($solicitacao) {
            $tipo = $solicitacao->tipo === 'item' ? 'itens' : 'cestas';

            return [
               'date' => $solicitacao->data_entrega,
               'icon' => 'fas fa-hand-holding-heart',
               'background' => 'bg-success',
               'title' => 'Solicitação entregue',
               'description' => 'Entrega da solicitação de ' . $tipo . ' concluída.',
            ];
         });

      $events = $events
         ->merge($familyEvents)
         ->merge($basketReceivedEvents)
         ->merge($basketRouteEvents)
         ->merge($basketDeliveredEvents)
         ->merge($requestCreatedEvents)
         ->merge($requestAcceptedEvents)
         ->merge($requestDeliveredEvents)
         ->sortByDesc('date')
         ->take($limit)
         ->values();

      return $events
         ->groupBy(function ($event) {
            return $event['date']->format('Y-m-d');
         })
         ->map(function ($items, $date) {
            return [
               'label' => Carbon::parse($date)->translatedFormat('d \\d\\e F'),
               'items' => $items->values(),
            ];
         })
         ->values();
   }
}
