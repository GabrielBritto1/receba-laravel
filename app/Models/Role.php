<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
   public function abilities()
   {
      return $this->belongsToMany(Ability::class);
   }

   public function getFormattedNameAttribute()
   {
      return match ($this->name) {
         'is-admin' => 'Administrador',
         'coordenador' => 'Coordenador',
         'secretario' => 'Secretario',
      };
   }
}
