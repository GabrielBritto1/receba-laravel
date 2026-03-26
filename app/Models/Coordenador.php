<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coordenador extends Model
{
   protected $fillable = [
      'user_id',
      'endereco',
      'telefone',
      'cpf'
   ];

   public function user()
   {
      return $this->belongsTo(User::class);
   }
}
