<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
   /**
    * Register any application services.
    */
   public function register(): void
   {
      //
   }

   /**
    * Bootstrap any application services.
    */
   public function boot(): void
   {
      Gate::define('Administrador', function (User $user): bool {
         return $user->hasRole('Administrador');
      });

      Gate::define('owner', function (User $user, object $register): bool {
         return $user->id === $register->user_id;
      });

      // Com Spatie Permissions, o Gate usa automaticamente $user->can()
   }
}
