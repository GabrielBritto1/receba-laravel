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
      Schema::create('cestas', function (Blueprint $table) {
         $table->id();
         $table->dateTime('data_recebimento')->nullable();
         $table->dateTime('data_em_rota')->nullable();
         $table->dateTime('data_entrega')->nullable();
         $table->string('ponto_origem')->default('IFES');
         $table->string('status')->default('Não saiu para entrega');
         $table->foreignId('parceiro_id')->constrained('parceiros')->onDelete('cascade');
         $table->foreignId('familia_id')->nullable()->constrained('familias')->onDelete('cascade');
         $table->timestamps();
      });
   }

   /**
    * Reverse the migrations.
    */
   public function down(): void
   {
      Schema::dropIfExists('cestas');
   }
};
