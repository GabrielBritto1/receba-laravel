<?php

namespace App\Models;

use App\Support\Formatter;
use Illuminate\Database\Eloquent\Model;

class Parceiro extends Model
{
   protected $fillable = [
      'name',
      'endereco',
      'telefone',
      'cep',
      'cnpj',
      'local_atuacao',
      'status',
   ];

   public function users()
   {
      return $this->belongsToMany(User::class, 'parceiro_user', 'parceiro_id', 'user_id');
   }

   public function sigla()
   {
      return $this->hasOne(ParceiroSigla::class);
   }

   public function familias()
   {
      return $this->hasMany(Familia::class);
   }

   public function cestas()
   {
      return $this->hasMany(Cesta::class);
   }

   public function solicitacoes()
   {
      return $this->hasMany(Solicitacao::class);
   }

   //FORMATTERS
   public function getTelefoneFormatadoAttribute()
   {
      return Formatter::telefone($this->telefone);
   }

   public function getCnpjFormatadoAttribute()
   {
      return Formatter::cnpj($this->cnpj);
   }

   public function getCepFormatadoAttribute()
   {
      return Formatter::cep($this->cep);
   }
}
