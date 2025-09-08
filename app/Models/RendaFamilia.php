<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RendaFamilia extends Model
{
   protected $fillable = [
      'familia_id',
      'pensao',
      'salario',
      'beneficio',
      'aposentadoria',
      'outros'
   ];

   public function familia()
   {
      return $this->belongsTo(Familia::class);
   }
}
