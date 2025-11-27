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
  Schema::table('suit_models', function (Blueprint $table) {
      $table->string('style')->default('simple')->after('thumbnail'); // simple, crossed, mao
      $table->integer('button_count')->default(2)->after('style'); // 1, 2, 3, 4, 6
      $table->string('lapel_type')->default('standard')->after('button_count'); // standard, peak
  });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
  Schema::table('suit_models', function (Blueprint $table) {
      $table->dropColumn(['style', 'button_count', 'lapel_type']);
  });
  }
};
