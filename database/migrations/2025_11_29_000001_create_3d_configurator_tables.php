<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * 3D Configurator System - Modular Architecture
 *
 * Based on Dunnio Tailor's modular 3D system where each product (Jacket, Pant, Vest)
 * is composed of multiple swappable parts (Front, Lapel, Sleeve, Pocket, etc.)
 *
 * This is completely separate from the layered images system.
 */
return new class extends Migration
{
  public function up(): void
  {
    // Product Types (Jacket, Pant, Vest, Shirt, etc.)
    Schema::create('product_types_3d', function (Blueprint $table) {
      $table->id();
      $table->string('code', 50)->unique(); // jacket, pant, vest, shirt
      $table->string('name');
      $table->string('name_vi');
      $table->text('description')->nullable();
      $table->string('base_path'); // Path to 3D models folder: Models/Jacket
      $table->json('default_config')->nullable(); // Default configuration JSON
      $table->json('texture_settings')->nullable(); // Default texture settings (scale, metallic, roughness)
      $table->boolean('is_active')->default(true);
      $table->integer('sort_order')->default(0);
      $table->timestamps();
    });

    // Part Categories (Style, Lapel, Pocket, Sleeve, Vent, etc.)
    Schema::create('part_categories_3d', function (Blueprint $table) {
      $table->id();
      $table->foreignId('product_type_id')->constrained('product_types_3d')->cascadeOnDelete();
      $table->string('code', 50); // style, lapel, pocket, sleeve, vent, bottom, lining
      $table->string('name');
      $table->string('name_vi');
      $table->text('description')->nullable();
      $table->string('icon')->nullable(); // Icon for UI
      $table->string('path_segment')->nullable(); // Folder path segment: Lapel, Pocket, etc.
      $table->boolean('is_required')->default(true);
      $table->boolean('allow_multiple')->default(false); // Can select multiple options?
      $table->boolean('affects_other_parts')->default(false); // Does changing this affect other parts?
      $table->json('dependencies')->nullable(); // Other categories this depends on
      $table->integer('sort_order')->default(0);
      $table->boolean('is_active')->default(true);
      $table->timestamps();

      $table->unique(['product_type_id', 'code']);
    });

    // Part Options (2Button, Peak Lapel, Side Vent, etc.)
    Schema::create('part_options_3d', function (Blueprint $table) {
      $table->id();
      $table->foreignId('part_category_id')->constrained('part_categories_3d')->cascadeOnDelete();
      $table->string('code', 50); // 2button, peak_lapel, side_vent
      $table->string('name');
      $table->string('name_vi');
      $table->text('description')->nullable();
      $table->string('preview_image')->nullable(); // Thumbnail for UI
      $table->string('model_file')->nullable(); // GLB/GLTF file path relative to base_path
      $table->json('model_files')->nullable(); // Multiple files if needed [{path, mesh_name, material_slot}]
      $table->json('mesh_config')->nullable(); // Mesh names and material mapping
      $table->json('constraints')->nullable(); // What other options this is compatible with
      $table->decimal('price_modifier', 12, 0)->default(0);
      $table->boolean('is_default')->default(false);
      $table->boolean('is_active')->default(true);
      $table->integer('sort_order')->default(0);
      $table->timestamps();

      $table->unique(['part_category_id', 'code']);
    });

    // Sub-options for complex parts (Lapel Width: Small/Regular/Large, Button Style: Standard/Working/Kissing)
    Schema::create('part_sub_options_3d', function (Blueprint $table) {
      $table->id();
      $table->foreignId('part_option_id')->constrained('part_options_3d')->cascadeOnDelete();
      $table->string('code', 50);
      $table->string('name');
      $table->string('name_vi');
      $table->string('sub_category'); // width, style, position
      $table->string('model_file')->nullable();
      $table->json('mesh_config')->nullable();
      $table->decimal('price_modifier', 12, 0)->default(0);
      $table->boolean('is_default')->default(false);
      $table->boolean('is_active')->default(true);
      $table->integer('sort_order')->default(0);
      $table->timestamps();

      $table->unique(['part_option_id', 'sub_category', 'code']);
    });

    // Mesh definitions for each model file
    Schema::create('model_meshes_3d', function (Blueprint $table) {
      $table->id();
      $table->foreignId('part_option_id')->nullable()->constrained('part_options_3d')->cascadeOnDelete();
      $table->foreignId('part_sub_option_id')->nullable()->constrained('part_sub_options_3d')->cascadeOnDelete();
      $table->string('mesh_name'); // Name in GLB file
      $table->string('material_type'); // fabric, lining, button, thread, contrast
      $table->json('texture_settings')->nullable(); // Override texture settings
      $table->json('uv_transform')->nullable(); // UV scale, rotation, offset
      $table->boolean('apply_fabric_texture')->default(true);
      $table->timestamps();

      $table->index(['part_option_id', 'mesh_name']);
    });

    // Saved configurations (user presets, defaults)
    Schema::create('model_configurations_3d', function (Blueprint $table) {
      $table->id();
      $table->foreignId('product_type_id')->constrained('product_types_3d')->cascadeOnDelete();
      $table->foreignId('fabric_id')->nullable()->constrained('fabrics')->nullOnDelete();
      $table->string('name')->nullable();
      $table->string('name_vi')->nullable();
      $table->json('selected_options'); // {category_code: option_code, ...}
      $table->json('sub_options')->nullable(); // {option_code: {sub_category: sub_code}, ...}
      $table->json('contrast_settings')->nullable(); // Contrast fabric settings
      $table->decimal('calculated_price', 12, 0)->default(0);
      $table->boolean('is_default')->default(false);
      $table->boolean('is_template')->default(false); // Template for customers to start from
      $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
      $table->timestamps();

      $table->index(['product_type_id', 'is_template']);
    });

    // Cache for loaded model combinations (performance optimization)
    Schema::create('model_cache_3d', function (Blueprint $table) {
      $table->id();
      $table->string('cache_key')->unique(); // Hash of configuration
      $table->foreignId('product_type_id')->constrained('product_types_3d')->cascadeOnDelete();
      $table->json('configuration');
      $table->string('combined_model_path')->nullable(); // Pre-combined GLB if generated
      $table->timestamp('last_accessed_at');
      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('model_cache_3d');
    Schema::dropIfExists('model_configurations_3d');
    Schema::dropIfExists('model_meshes_3d');
    Schema::dropIfExists('part_sub_options_3d');
    Schema::dropIfExists('part_options_3d');
    Schema::dropIfExists('part_categories_3d');
    Schema::dropIfExists('product_types_3d');
  }
};
