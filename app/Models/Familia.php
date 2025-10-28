<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Familia extends Model
{
   protected $fillable = [
      'numero_casa',
      'bairro',
      'cidade',
      'nis',
      'doenca',
      'medicamento',
      'descricao',
      'cad_unico',
      'reside',
      'aluguel',
      'status',
      'endereco',
      'parceiro_id',
      'representante_id',
   ];

   public function representante()
   {
      return $this->belongsTo(Representante::class);
   }

   public function membroFamilia()
   {
      return $this->hasOne(MembroFamilia::class);
   }

   public function rendaFamilia()
   {
      return $this->hasOne(RendaFamilia::class);
   }

   public function parceiro()
   {
      return $this->belongsTo(Parceiro::class);
   }

   public function cestas()
   {
      return $this->hasMany(Cesta::class, 'familia_id');
   }
}
