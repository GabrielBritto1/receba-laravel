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
      Schema::create('solicitacaos', function (Blueprint $table) {
         $table->id();
         $table->dateTime('data_previsao_entrega');
         $table->dateTime('data_aceito')->nullable();
         $table->dateTime('data_montada')->nullable();
         $table->dateTime('data_entrega')->nullable();
         $table->string('quantidade_solicitada');
         $table->string('quantidade_aceita')->nullable();
         $table->string('quantidade_nao_aceita')->nullable();
         $table->string('status')->default('Em Análise');
         $table->foreignId('parceiro_id')->constrained('parceiros')->onDelete('cascade');
         $table->timestamps();
      });
   }

   /**
    * Reverse the migrations.
    */
   public function down(): void
   {
      Schema::dropIfExists('solicitacaos');
   }
};
