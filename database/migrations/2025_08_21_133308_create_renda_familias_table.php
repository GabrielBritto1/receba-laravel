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
      Schema::create('renda_familias', function (Blueprint $table) {
         $table->id();
         $table->foreignId('familia_id')->constrained('familias')->onDelete('cascade');
         $table->decimal('pensao', 8, 2)->default(0);
         $table->decimal('salario', 8, 2)->default(0);
         $table->decimal('beneficio', 8, 2)->default(0);
         $table->decimal('aposentadoria', 8, 2)->default(0);
         $table->decimal('outros', 8, 2)->default(0);
         $table->timestamps();
      });
   }

   /**
    * Reverse the migrations.
    */
   public function down(): void
   {
      Schema::dropIfExists('renda_familias');
   }
};
