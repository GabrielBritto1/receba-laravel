<?php

namespace App\Providers;

use App\Models\Solicitacao;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
   public function register(): void
   {
      //
   }

   public function boot(): void
   {
      Gate::define('Administrador', function (User $user): bool {
         return $user->hasRole('Administrador');
      });

      Gate::define('owner', function (User $user, object $register): bool {
         return $user->id === $register->user_id;
      });

      View::composer('adminlte::partials.navbar.navbar', function ($view) {
         if (!Auth::check()) {
            $view->with('notificacoes', [])->with('totalNotificacoes', 0);
            return;
         }

         $user = Auth::user();
         $notificacoes = [];

         if ($user->hasRole('Administrador')) {
            $solicitacoes = Solicitacao::cestas()
               ->with('parceiro.sigla')
               ->whereIn('status', ['Em Análise', 'Aceita', 'Montada'])
               ->orderBy('updated_at', 'desc')
               ->get();

            foreach ($solicitacoes as $sol) {
               $sigla = $sol->parceiro->sigla?->name ?? $sol->parceiro->name;
               $notificacoes[] = [
                  'titulo'  => $sigla,
                  'detalhe' => "{$sol->quantidade_solicitada} cesta(s) solicitada(s)",
                  'status'  => $sol->status,
                  'url'     => route('solicitacoes.gerenciar_solicitacoes'),
                  'icon'    => 'fas fa-shopping-basket',
                  'bg'      => $this->bgPorStatus($sol->status),
                  'tempo'   => ($sol->updated_at ?? $sol->created_at)?->diffForHumans() ?? '',
               ];
            }
         } else {
            $parceiro = $user->parceiros->first();
            if ($parceiro) {
               $cestas = Solicitacao::cestas()
                  ->where('parceiro_id', $parceiro->id)
                  ->whereIn('status', ['Em Análise', 'Aceita', 'Montada'])
                  ->orderBy('updated_at', 'desc')
                  ->get();

               foreach ($cestas as $sol) {
                  $detalhe = $sol->quantidade_aceita
                     ? "{$sol->quantidade_solicitada} solicitada(s) · {$sol->quantidade_aceita} aceita(s)"
                     : "{$sol->quantidade_solicitada} cesta(s) solicitada(s)";
                  $notificacoes[] = [
                     'titulo'  => 'Solicitação de Cesta',
                     'detalhe' => $detalhe,
                     'status'  => $sol->status,
                     'url'     => route('solicitacoes.index'),
                     'icon'    => 'fas fa-shopping-basket',
                     'bg'      => $this->bgPorStatus($sol->status),
                     'tempo'   => ($sol->updated_at ?? $sol->created_at)?->diffForHumans() ?? '',
                  ];
               }

               $itens = Solicitacao::itens()
                  ->with('item')
                  ->where('parceiro_id', $parceiro->id)
                  ->whereIn('status', ['Em Análise', 'Aceita', 'Montada'])
                  ->orderBy('updated_at', 'desc')
                  ->get();

               foreach ($itens as $sol) {
                  $nome = $sol->item?->nome ?? 'Item';
                  $detalhe = $sol->quantidade_aceita
                     ? "{$sol->quantidade_solicitada} solicitado(s) · {$sol->quantidade_aceita} aceito(s)"
                     : "{$sol->quantidade_solicitada} unidade(s) solicitada(s)";
                  $notificacoes[] = [
                     'titulo'  => $nome,
                     'detalhe' => $detalhe,
                     'status'  => $sol->status,
                     'url'     => route('itens.index'),
                     'icon'    => 'fas fa-box',
                     'bg'      => $this->bgPorStatus($sol->status),
                     'tempo'   => ($sol->updated_at ?? $sol->created_at)?->diffForHumans() ?? '',
                  ];
               }
            }
         }

         $view->with('notificacoes', $notificacoes)->with('totalNotificacoes', count($notificacoes));
      });
   }

   private function bgPorStatus(string $status): string
   {
      return match ($status) {
         'Em Análise' => '#fd7e14',
         'Aceita'     => '#17a2b8',
         'Montada'    => '#007bff',
         default      => '#6c757d',
      };
   }
}
