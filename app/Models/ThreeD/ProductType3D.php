<?php

namespace App\Models\ThreeD;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;

/**
 * Product Type for 3D Configurator (Jacket, Pant, Vest, Shirt)
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string $name_vi
 * @property string|null $description
 * @property string $base_path
 * @property array|null $default_config
 * @property array|null $texture_settings
 * @property bool $is_active
 * @property int $sort_order
 */
class ProductType3D extends Model
{
  protected $table = 'product_types_3d';

  protected $fillable = [
    'code',
    'name',
    'name_vi',
    'description',
    'base_path',
    'default_config',
    'texture_settings',
    'is_active',
    'sort_order',
  ];

  protected $casts = [
    'default_config' => 'array',
    'texture_settings' => 'array',
    'is_active' => 'boolean',
  ];

  /**
   * Default texture settings for fabric materials
   */
  public static array $defaultTextureSettings = [
    'scale' => ['u' => 5, 'v' => 5],
    'metallic' => 0,
    'roughness' => 1,
    'color' => '#ffffff',
  ];

  // Relationships

  public function partCategories(): HasMany
  {
    return $this->hasMany(PartCategory3D::class, 'product_type_id')
      ->orderBy('sort_order');
  }

  public function configurations(): HasMany
  {
    return $this->hasMany(ModelConfiguration3D::class, 'product_type_id');
  }

  public function cache(): HasMany
  {
    return $this->hasMany(ModelCache3D::class, 'product_type_id');
  }

  // Scopes

  public function scopeActive($query)
  {
    return $query->where('is_active', true);
  }

  public function scopeOrdered($query)
  {
    return $query->orderBy('sort_order');
  }

  // Accessors

  protected function fullBasePath(): Attribute
  {
    return Attribute::get(fn() => Storage::disk('public')->path("3d-models/{$this->base_path}"));
  }

  protected function baseUrl(): Attribute
  {
    return Attribute::get(fn() => Storage::disk('public')->url("3d-models/{$this->base_path}"));
  }

    // Methods

  /**
   * Get merged texture settings with defaults
   */
  public function getTextureSettings(): array
  {
    return array_merge(
      self::$defaultTextureSettings,
      $this->texture_settings ?? []
    );
  }

  /**
   * Get default configuration for this product type
   */
  public function getDefaultConfiguration(): array
  {
    if ($this->default_config) {
      return $this->default_config;
    }

    // Build default from part categories
    $config = [];
    foreach ($this->partCategories()->with('options')->get() as $category) {
      $defaultOption = $category->options->firstWhere('is_default', true)
        ?? $category->options->first();

      if ($defaultOption) {
        $config[$category->code] = $defaultOption->code;
      }
    }

    return $config;
  }

  /**
   * Get all parts required to render this product with given configuration
   */
  public function getModelParts(array $configuration = []): array
  {
    $config = array_merge($this->getDefaultConfiguration(), $configuration);
    $parts = [];

    foreach ($this->partCategories()->with(['options.subOptions', 'options.meshes'])->get() as $category) {
      $optionCode = $config[$category->code] ?? null;
      if (!$optionCode) continue;

      $option = $category->options->firstWhere('code', $optionCode);
      if (!$option) continue;

      $parts[] = [
        'category' => $category->code,
        'option' => $option->code,
        'model_file' => $option->model_file,
        'model_files' => $option->model_files,
        'meshes' => $option->meshes->map(fn($m) => [
          'name' => $m->mesh_name,
          'material_type' => $m->material_type,
          'texture_settings' => $m->texture_settings,
          'apply_fabric_texture' => $m->apply_fabric_texture,
        ])->toArray(),
      ];
    }

    return $parts;
  }
}
