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
      Schema::create('membro_familias', function (Blueprint $table) {
         $table->id();
         $table->foreignId('familia_id')->constrained('familias')->onDelete('cascade');
         $table->unsignedInteger('idosos')->default(0);
         $table->unsignedInteger('filhos_0a5')->default(0);
         $table->unsignedInteger('filhos_6a12')->default(0);
         $table->unsignedInteger('filhos_13a16')->default(0);
         $table->unsignedInteger('filhos_acima16')->default(0);
         $table->timestamps();
      });
   }

   /**
    * Reverse the migrations.
    */
   public function down(): void
   {
      Schema::dropIfExists('membro_familias');
   }
};
