<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('three_d_models', function (Blueprint $table) {
      $table->id();
      $table->foreignId('suit_model_id')->constrained()->cascadeOnDelete();
      $table->string('glb_file'); // Path to GLB file
      $table->string('preview_image')->nullable();
      $table->json('parts_mapping')->nullable(); // Mesh name to part name mapping
      $table->text('notes')->nullable();
      $table->boolean('is_processed')->default(false);
      $table->timestamp('processed_at')->nullable();
      $table->integer('layers_count')->default(0);
      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('three_d_models');
  }
};
