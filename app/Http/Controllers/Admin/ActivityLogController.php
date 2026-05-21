<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityLogController extends Controller
{
   public function list(Request $request)
   {
      abort_unless(Auth::user()->hasRole('Administrador'), 403);

      $query = ActivityLog::orderBy('created_at', 'desc');

      if ($request->filled('user')) {
         $query->where('user_name', 'like', '%' . $request->user . '%');
      }

      if ($request->filled('action')) {
         $query->where('action', $request->action);
      }

      $logs = $query->paginate(20);

      return response()->json([
         'status' => 'success',
         'logs' => $logs->map(fn($log) => [
            'id' => $log->id,
            'user_name' => $log->user_name ?? '—',
            'action' => $log->action,
            'description' => $log->description,
            'url' => $log->url,
            'ip_address' => $log->ip_address ?? '—',
            'created_at' => $log->created_at?->format('d/m/Y H:i:s'),
         ]),
         'pagination' => [
            'current_page' => $logs->currentPage(),
            'last_page' => $logs->lastPage(),
            'total' => $logs->total(),
         ],
      ]);
   }
}
