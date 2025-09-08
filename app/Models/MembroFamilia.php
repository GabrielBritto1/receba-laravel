<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MembroFamilia extends Model
{
   protected $fillable = [
      'familia_id',
      'idosos',
      'filhos_0a5',
      'filhos_6a12',
      'filhos_13a16',
      'filhos_acima16'
   ];

   public function familia()
   {
      return $this->belongsTo(Familia::class);
   }
}
