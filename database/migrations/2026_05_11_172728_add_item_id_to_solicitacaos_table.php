<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
   {
      Schema::table('solicitacaos', function (Blueprint $table) {
         $table->foreignId('item_id')->nullable()->after('tipo')->constrained('items')->nullOnDelete();
      });
   }

   public function down(): void
   {
      Schema::table('solicitacaos', function (Blueprint $table) {
         $table->dropForeign(['item_id']);
         $table->dropColumn('item_id');
      });
   }
};
