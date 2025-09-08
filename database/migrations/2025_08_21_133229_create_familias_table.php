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
      Schema::create('familias', function (Blueprint $table) {
         $table->id();
         $table->string('numero_casa')->nullable();
         $table->string('bairro');
         $table->string('cidade');
         $table->string('nis')->unique()->nullable();
         $table->string('doenca')->nullable();
         $table->string('medicamento')->nullable();
         $table->text('descricao')->nullable();
         $table->string('cad_unico')->nullable();
         $table->string('reside')->nullable();
         $table->decimal('aluguel', 8, 2)->nullable();
         $table->boolean('status')->default(true);
         $table->string('endereco');
         $table->foreignId('parceiro_id')->constrained('parceiros')->onDelete('cascade');
         $table->timestamps();
      });
   }

   /**
    * Reverse the migrations.
    */
   public function down(): void
   {
      Schema::dropIfExists('familias');
   }
};
