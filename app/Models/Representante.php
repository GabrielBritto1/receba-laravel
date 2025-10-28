<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Representante extends Model
{
   protected $fillable = [
      'cpf',
      'nome',
      'data_nascimento',
      'uf',
      'estado_civil',
      'telefone',
      'escolaridade',
      'qualificacao',
      'filiacao',
      'cpf_conjuge',
      'nome_conjuge',
      'data_nascimento_conjuge',
      'rg',
   ];

   protected $casts = [
      'data_nascimento' => 'datetime',
      'data_nascimento_conjuge' => 'datetime',
   ];

   public function familias()
   {
      return $this->hasMany(Familia::class);
   }
}
