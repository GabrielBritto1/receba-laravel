<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   /**
    * Run the migrations.
    */
   public function up(): void
   {
      Schema::create('representantes', function (Blueprint $table) {
         $table->id();
         $table->string('cpf')->unique();
         $table->string('nome');
         $table->date('data_nascimento');
         $table->string('uf', 2);
         $table->string('estado_civil');
         $table->string('telefone');
         $table->string('escolaridade');
         $table->string('qualificacao')->nullable();
         $table->string('filiacao');
         $table->string('cpf_conjuge')->nullable();
         $table->string('nome_conjuge')->nullable();
         $table->date('data_nascimento_conjuge')->nullable();
         $table->string('rg')->nullable();
         $table->foreignId('familia_id')->constrained('familias')->onDelete('cascade');
         $table->timestamps();
      });
   }

   /**
    * Reverse the migrations.
    */
   public function down(): void
   {
      Schema::dropIfExists('representantes');
   }
};
