<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cesta extends Model
{
   protected $fillable = [
      'data_recebimento',
      'data_em_rota',
      'data_entrega',
      'ponto_origem',
      'status',
      'parceiro_id',
      'familia_id',
   ];

   protected $casts = [
      'data_recebimento' => 'datetime',
      'data_em_rota' => 'datetime',
      'data_entrega' => 'datetime',
   ];

   public function parceiro()
   {
      return $this->belongsTo(Parceiro::class);
   }

   public function familia()
   {
      return $this->belongsTo(Familia::class);
   }
}
