<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
  // Danh mục vải (Fabric Categories)
  Schema::create('fabric_categories', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('name_vi');
      $table->string('slug')->unique();
      $table->text('description')->nullable();
      $table->text('description_vi')->nullable();
      $table->boolean('is_active')->default(true);
      $table->integer('sort_order')->default(0);
      $table->timestamps();
  });

  // Vải (Fabrics)
  Schema::create('fabrics', function (Blueprint $table) {
      $table->id();
      $table->foreignId('fabric_category_id')->constrained()->onDelete('cascade');
      $table->string('name');
      $table->string('name_vi');
      $table->string('slug')->unique();
      $table->string('code')->unique();
      $table->text('description')->nullable();
      $table->text('description_vi')->nullable();
      $table->string('color_hex', 7)->nullable();
      $table->string('texture_image')->nullable();
      $table->string('thumbnail')->nullable();
      $table->decimal('price_modifier', 12, 0)->default(0);
      $table->string('material_composition')->nullable();
      $table->string('weight')->nullable();
      $table->string('origin')->nullable();
      $table->boolean('is_active')->default(true);
      $table->boolean('is_featured')->default(false);
      $table->integer('sort_order')->default(0);
      $table->integer('stock_quantity')->default(100);
      $table->timestamps();
  });

  // Mẫu vest (Suit Models)
  Schema::create('suit_models', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('name_vi');
      $table->string('slug')->unique();
      $table->text('description')->nullable();
      $table->text('description_vi')->nullable();
      $table->string('thumbnail')->nullable();
      $table->decimal('base_price', 12, 0);
      $table->json('layer_config')->nullable(); // Cấu hình các layer ảnh
      $table->boolean('is_active')->default(true);
      $table->boolean('is_featured')->default(false);
      $table->integer('sort_order')->default(0);
      $table->timestamps();
  });

  // Loại tùy chọn (Option Types: Ve áo, Túi, Cúc, Xẻ, Fit...)
  Schema::create('option_types', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('name_vi');
      $table->string('slug')->unique();
      $table->text('description')->nullable();
      $table->text('description_vi')->nullable();
      $table->string('icon')->nullable();
      $table->string('type')->default('single'); // single, multiple
      $table->boolean('is_required')->default(false);
      $table->boolean('is_active')->default(true);
      $table->integer('sort_order')->default(0);
      $table->timestamps();
  });

  // Giá trị tùy chọn (Option Values)
  Schema::create('option_values', function (Blueprint $table) {
      $table->id();
      $table->foreignId('option_type_id')->constrained()->onDelete('cascade');
      $table->string('name');
      $table->string('name_vi');
      $table->string('slug');
      $table->text('description')->nullable();
      $table->text('description_vi')->nullable();
      $table->string('preview_image')->nullable();
      $table->decimal('price_modifier', 12, 0)->default(0);
      $table->string('layer_key')->nullable(); // Key để map với layer ảnh
      $table->boolean('is_default')->default(false);
      $table->boolean('is_active')->default(true);
      $table->integer('sort_order')->default(0);
      $table->timestamps();

      $table->unique(['option_type_id', 'slug']);
  });

  // Liên kết Suit Model với Option Types cho phép
  Schema::create('suit_model_option_types', function (Blueprint $table) {
      $table->id();
      $table->foreignId('suit_model_id')->constrained()->onDelete('cascade');
      $table->foreignId('option_type_id')->constrained()->onDelete('cascade');
      $table->boolean('is_required')->default(false);
      $table->timestamps();

      $table->unique(['suit_model_id', 'option_type_id']);
  });

  // Layer ảnh cho mỗi fabric (Suit Layers)
  Schema::create('suit_layers', function (Blueprint $table) {
      $table->id();
      $table->foreignId('suit_model_id')->constrained()->onDelete('cascade');
      $table->foreignId('fabric_id')->nullable()->constrained()->onDelete('cascade');
      $table->string('view')->default('front'); // front, back, side
      $table->string('part'); // body, lapel, pocket, button, sleeve, etc.
      $table->string('option_value_slug')->nullable(); // null = base layer
      $table->string('image_path');
      $table->integer('z_index')->default(0);
      $table->boolean('is_active')->default(true);
      $table->timestamps();

      $table->index(['suit_model_id', 'fabric_id', 'view']);
  });
  }

  public function down(): void
  {
  Schema::dropIfExists('suit_layers');
  Schema::dropIfExists('suit_model_option_types');
  Schema::dropIfExists('option_values');
  Schema::dropIfExists('option_types');
  Schema::dropIfExists('suit_models');
  Schema::dropIfExists('fabrics');
  Schema::dropIfExists('fabric_categories');
  }
};
