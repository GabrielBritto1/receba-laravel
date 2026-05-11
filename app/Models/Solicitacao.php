<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Solicitacao extends Model
{
   protected $fillable = [
      'tipo',
      'data_previsao_entrega',
      'data_aceito',
      'data_montada',
      'data_entrega',
      'quantidade_solicitada',
      'quantidade_aceita',
      'quantidade_nao_aceita',
      'status',
      'parceiro_id',
      'item_id',
   ];

   protected $casts = [
      'data_previsao_entrega' => 'datetime',
      'data_aceito' => 'datetime',
      'data_montada' => 'datetime',
      'data_entrega' => 'datetime',
   ];

   public function parceiro()
   {
      return $this->belongsTo(Parceiro::class);
   }

   public function item()
   {
      return $this->belongsTo(Item::class);
   }

   public function scopeCestas($query)
   {
      return $query->where('tipo', 'cesta');
   }

   public function scopeItens($query)
   {
      return $query->where('tipo', 'item');
   }
}
