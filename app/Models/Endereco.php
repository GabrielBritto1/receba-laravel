<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Endereco extends Model
{
   protected $fillable = [
      'endereco',
      'numero_casa',
      'bairro',
      'cidade',
      'estado',
      'cep',
   ];

   public function pessoas()
   {
      return $this->hasMany(Pessoa::class);
   }
}
