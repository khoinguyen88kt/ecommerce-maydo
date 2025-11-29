<?php

namespace App\Services;

use App\Models\Fabric;
use App\Models\ThreeD\ModelCache3D;
use App\Models\ThreeD\ModelConfiguration3D;
use App\Models\ThreeD\PartCategory3D;
use App\Models\ThreeD\PartOption3D;
use App\Models\ThreeD\ProductType3D;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

/**
 * Service for 3D Configurator operations
 *
 * Handles loading, configuration, and rendering of modular 3D models
 */
class ThreeDConfiguratorService
{
  /**
   * Cache TTL in seconds (1 hour)
   */
  protected const CACHE_TTL = 3600;

  /**
   * Get all active product types
   */
  public function getProductTypes(): Collection
  {
    return Cache::remember('3d_product_types', self::CACHE_TTL, function () {
      return ProductType3D::active()
        ->ordered()
        ->get();
    });
  }

  /**
   * Get product type by code with all relationships
   */
  public function getProductType(string $code): ?ProductType3D
  {
    return Cache::remember("3d_product_type_{$code}", self::CACHE_TTL, function () use ($code) {
      return ProductType3D::where('code', $code)
        ->with([
          'partCategories' => fn($q) => $q->active()->ordered(),
          'partCategories.options' => fn($q) => $q->active()->ordered(),
          'partCategories.options.subOptions' => fn($q) => $q->active()->ordered(),
          'partCategories.options.meshes',
        ])
        ->first();
    });
  }

  /**
   * Get configuration data for frontend
   */
  public function getConfigurationData(string $productTypeCode, ?int $fabricId = null): array
  {
    $productType = $this->getProductType($productTypeCode);

    if (!$productType) {
      throw new \InvalidArgumentException("Product type '{$productTypeCode}' not found");
    }

    $fabric = $fabricId ? Fabric::find($fabricId) : null;

    return [
      'product_type' => $this->formatProductType($productType),
      'categories' => $this->formatCategories($productType->partCategories),
      'default_config' => $productType->getDefaultConfiguration(),
      'texture_settings' => $productType->getTextureSettings(),
      'fabric' => $fabric ? $this->formatFabric($fabric) : null,
      'base_url' => Storage::disk('public')->url("3d-models/{$productType->base_path}"),
    ];
  }

  /**
   * Get model files for a specific configuration
   */
  public function getModelFiles(string $productTypeCode, array $configuration): array
  {
    $productType = $this->getProductType($productTypeCode);

    if (!$productType) {
      throw new \InvalidArgumentException("Product type '{$productTypeCode}' not found");
    }

    $files = [];
    $baseUrl = Storage::disk('public')->url("3d-models/{$productType->base_path}");

    foreach ($productType->partCategories as $category) {
      $optionCode = $configuration[$category->code] ?? null;
      if (!$optionCode) continue;

      $option = $category->options->firstWhere('code', $optionCode);
      if (!$option) continue;

      // Main model file
      if ($option->model_file) {
        $files[] = [
          'category' => $category->code,
          'option' => $option->code,
          'url' => "{$baseUrl}/{$option->model_file}",
          'meshes' => $this->formatMeshes($option->meshes),
        ];
      }

      // Additional model files
      if ($option->model_files) {
        foreach ($option->model_files as $file) {
          $files[] = [
            'category' => $category->code,
            'option' => $option->code,
            'url' => "{$baseUrl}/" . ($file['path'] ?? $file),
            'mesh_name' => $file['mesh_name'] ?? null,
            'meshes' => [],
          ];
        }
      }

      // Sub-options files
      foreach ($option->subOptions as $subOption) {
        if ($subOption->model_file) {
          $files[] = [
            'category' => $category->code,
            'option' => $option->code,
            'sub_option' => $subOption->code,
            'sub_category' => $subOption->sub_category,
            'url' => "{$baseUrl}/{$subOption->model_file}",
            'meshes' => $this->formatMeshes($subOption->meshes),
          ];
        }
      }
    }

    return $files;
  }

  /**
   * Update configuration option
   */
  public function updateOption(
    string $productTypeCode,
    array $currentConfig,
    string $categoryCode,
    string $optionCode
  ): array {
    $productType = $this->getProductType($productTypeCode);

    if (!$productType) {
      throw new \InvalidArgumentException("Product type '{$productTypeCode}' not found");
    }

    // Find category and option
    $category = $productType->partCategories->firstWhere('code', $categoryCode);
    if (!$category) {
      throw new \InvalidArgumentException("Category '{$categoryCode}' not found");
    }

    $option = $category->options->firstWhere('code', $optionCode);
    if (!$option) {
      throw new \InvalidArgumentException("Option '{$optionCode}' not found");
    }

    // Check constraints
    if (!$option->isCompatibleWith($currentConfig)) {
      throw new \InvalidArgumentException("Option '{$optionCode}' is not compatible with current configuration");
    }

    // Update config
    $newConfig = $currentConfig;
    $newConfig[$categoryCode] = $optionCode;

    // Handle cascading changes if this affects other parts
    if ($category->affects_other_parts) {
      $newConfig = $this->handleCascadingChanges($productType, $newConfig, $categoryCode, $optionCode);
    }

    // Get files that changed
    $changedFiles = $this->getChangedFiles($productType, $currentConfig, $newConfig);

    return [
      'config' => $newConfig,
      'changed_files' => $changedFiles,
      'remove_files' => $this->getFilesToRemove($productType, $currentConfig, $newConfig),
    ];
  }

  /**
   * Calculate price for configuration
   */
  public function calculatePrice(string $productTypeCode, array $configuration, ?int $fabricId = null): array
  {
    $productType = $this->getProductType($productTypeCode);
    $fabric = $fabricId ? Fabric::find($fabricId) : null;

    $breakdown = [];
    $total = 0;

    // Fabric price
    if ($fabric) {
      $breakdown['fabric'] = [
        'name' => $fabric->name_vi,
        'price' => (float) $fabric->price_modifier,
      ];
      $total += $fabric->price_modifier;
    }

    // Option prices
    foreach ($configuration as $categoryCode => $optionCode) {
      $category = $productType->partCategories->firstWhere('code', $categoryCode);
      if (!$category) continue;

      $option = $category->options->firstWhere('code', $optionCode);
      if (!$option || $option->price_modifier == 0) continue;

      $breakdown[$categoryCode] = [
        'name' => $option->name_vi,
        'price' => (float) $option->price_modifier,
      ];
      $total += $option->price_modifier;
    }

    return [
      'breakdown' => $breakdown,
      'total' => $total,
    ];
  }

  /**
   * Save configuration
   */
  public function saveConfiguration(
    string $productTypeCode,
    array $configuration,
    ?int $fabricId = null,
    ?int $userId = null,
    ?string $name = null
  ): ModelConfiguration3D {
    $productType = $this->getProductType($productTypeCode);
    $price = $this->calculatePrice($productTypeCode, $configuration, $fabricId);

    return ModelConfiguration3D::create([
      'product_type_id' => $productType->id,
      'fabric_id' => $fabricId,
      'name' => $name,
      'selected_options' => $configuration,
      'calculated_price' => $price['total'],
      'user_id' => $userId,
    ]);
  }

  /**
   * Get available fabrics for product type
   */
  public function getAvailableFabrics(string $productTypeCode): Collection
  {
    // For now, return all active fabrics that are not lining
    return Fabric::where('is_active', true)
      ->where(function ($q) {
        $q->where('is_lining', false)->orWhereNull('is_lining');
      })
      ->with('fabricCategory')
      ->orderBy('sort_order')
      ->get();
  }

  // Private helper methods

  protected function formatProductType(ProductType3D $productType): array
  {
    return [
      'id' => $productType->id,
      'code' => $productType->code,
      'name' => $productType->name,
      'name_vi' => $productType->name_vi,
      'base_path' => $productType->base_path,
    ];
  }

  protected function formatCategories(Collection $categories): array
  {
    return $categories->map(fn($cat) => [
      'code' => $cat->code,
      'name' => $cat->name,
      'name_vi' => $cat->name_vi,
      'icon' => $cat->icon,
      'is_required' => $cat->is_required,
      'allow_multiple' => $cat->allow_multiple,
      'affects_other_parts' => $cat->affects_other_parts,
      'options' => $cat->options->map(fn($opt) => [
        'code' => $opt->code,
        'name' => $opt->name,
        'name_vi' => $opt->name_vi,
        'preview_image' => $opt->preview_image_url,
        'price_modifier' => (float) $opt->price_modifier,
        'is_default' => $opt->is_default,
        'constraints' => $opt->constraints,
        'sub_options' => $this->formatSubOptions($opt->subOptions),
      ])->toArray(),
    ])->toArray();
  }

  protected function formatSubOptions(Collection $subOptions): array
  {
    return $subOptions->groupBy('sub_category')
      ->map(fn($opts, $cat) => [
        'category' => $cat,
        'options' => $opts->map(fn($opt) => [
          'code' => $opt->code,
          'name' => $opt->name,
          'name_vi' => $opt->name_vi,
          'price_modifier' => (float) $opt->price_modifier,
          'is_default' => $opt->is_default,
        ])->values()->toArray(),
      ])
      ->values()
      ->toArray();
  }

  protected function formatMeshes(Collection $meshes): array
  {
    return $meshes->map(fn($mesh) => [
      'name' => $mesh->mesh_name,
      'material_type' => $mesh->material_type,
      'texture_settings' => $mesh->getTextureSettings(),
      'uv_transform' => $mesh->getUvTransform(),
      'apply_fabric_texture' => $mesh->apply_fabric_texture,
    ])->toArray();
  }

  protected function formatFabric(Fabric $fabric): array
  {
    return [
      'id' => $fabric->id,
      'name' => $fabric->name,
      'name_vi' => $fabric->name_vi,
      'code' => $fabric->code,
      'color_hex' => $fabric->color_hex,
      'texture_url' => $fabric->texture_image
        ? Storage::disk('public')->url($fabric->texture_image)
        : null,
      'thumbnail_url' => $fabric->thumbnail
        ? Storage::disk('public')->url($fabric->thumbnail)
        : null,
    ];
  }

  protected function handleCascadingChanges(
    ProductType3D $productType,
    array $config,
    string $changedCategory,
    string $newOption
  ): array {
    // Check each category for dependencies on the changed category
    foreach ($productType->partCategories as $category) {
      if ($category->code === $changedCategory) continue;
      if (!$category->dependencies) continue;

      if (in_array($changedCategory, $category->dependencies)) {
        // Re-validate current option
        $currentOption = $category->options->firstWhere('code', $config[$category->code] ?? null);

        if ($currentOption && !$currentOption->isCompatibleWith($config)) {
          // Switch to first compatible option
          $compatible = $category->options->first(fn($opt) => $opt->isCompatibleWith($config));
          if ($compatible) {
            $config[$category->code] = $compatible->code;
          }
        }
      }
    }

    return $config;
  }

  protected function getChangedFiles(ProductType3D $productType, array $oldConfig, array $newConfig): array
  {
    $changedCategories = [];

    foreach ($newConfig as $category => $option) {
      if (($oldConfig[$category] ?? null) !== $option) {
        $changedCategories[] = $category;
      }
    }

    return $this->getModelFiles($productType->code, $newConfig);
  }

  protected function getFilesToRemove(ProductType3D $productType, array $oldConfig, array $newConfig): array
  {
    $toRemove = [];

    foreach ($oldConfig as $category => $option) {
      if (($newConfig[$category] ?? null) !== $option) {
        $toRemove[] = [
          'category' => $category,
          'option' => $option,
        ];
      }
    }

    return $toRemove;
  }

  /**
   * Clear all caches
   */
  public function clearCache(): void
  {
    Cache::forget('3d_product_types');

    ProductType3D::all()->each(function ($type) {
      Cache::forget("3d_product_type_{$type->code}");
    });
  }
}
