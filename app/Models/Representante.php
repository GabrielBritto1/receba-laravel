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
      'familia_id',
   ];

   public function familia()
   {
      return $this->belongsTo(Familia::class);
   }
}
