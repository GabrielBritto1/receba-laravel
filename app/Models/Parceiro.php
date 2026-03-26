<?php

namespace App\Models;

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
      $value = preg_replace('/\D/', '', $this->telefone);

      return strlen($value) === 11
         ? preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $value)
         : preg_replace('/(\d{2})(\d{4})(\d{4})/', '($1) $2-$3', $value);
   }

   public function getCnpjFormatadoAttribute()
   {
      $value = preg_replace('/\D/', '', $this->cnpj);

      if (strlen($value) !== 14) return $this->cnpj;

      return preg_replace('/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/', '$1.$2.$3/$4-$5', $value);
   }

   public function getCepFormatadoAttribute()
   {
      $value = preg_replace('/\D/', '', $this->cep);

      if (strlen($value) !== 8) return $this->cep;

      return preg_replace('/(\d{5})(\d{3})/', '$1-$2', $value);
   }
}
