<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cesta extends Model
{
   protected $fillable = [
      'data_entrega',
      'quantidade_total',
      'status',
      'ponto_origem',
      'parceiro_id',
   ];

   protected $casts = [
      'data_entrega' => 'date',
   ];

   public function parceiro()
   {
      return $this->belongsTo(Parceiro::class);
   }
}
