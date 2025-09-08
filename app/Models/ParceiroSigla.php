<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParceiroSigla extends Model
{
   protected $fillable = [
      'name',
      'color',
      'parceiro_id'
   ];

   public function parceiro()
   {
      return $this->belongsTo(Parceiro::class);
   }
}
