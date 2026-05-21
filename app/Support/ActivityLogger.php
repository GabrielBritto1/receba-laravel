<?php

namespace App\Support;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class ActivityLogger
{
   public static function log(string $action, string $description): void
   {
      try {
         $user = Auth::user();
         ActivityLog::create([
            'user_id' => $user?->id,
            'user_name' => $user?->name ?? 'Sistema',
            'action' => $action,
            'description' => $description,
            'url' => request()->fullUrl(),
            'ip_address' => request()->ip(),
         ]);
      } catch (\Throwable) {
         // nunca quebra a aplicação por causa de log
      }
   }
}
