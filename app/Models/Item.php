<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
   protected $fillable = [
      'nome',
      'quantidade',
      'disponivel',
   ];

   protected $casts = [
      'disponivel' => 'boolean',
   ];

   public function solicitacoes()
   {
      return $this->hasMany(Solicitacao::class);
   }
}
