<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
   public $timestamps = false;

   protected $fillable = [
      'user_id',
      'user_name',
      'action',
      'description',
      'url',
      'ip_address',
   ];

   protected $casts = [
      'created_at' => 'datetime',
   ];

   public function user()
   {
      return $this->belongsTo(User::class);
   }
}
