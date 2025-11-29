<?php

namespace App\Models\ThreeD;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

/**
 * Part Option for 3D Configurator (2Button, Peak Lapel, Side Vent, etc.)
 *
 * @property int $id
 * @property int $part_category_id
 * @property string $code
 * @property string $name
 * @property string $name_vi
 * @property string|null $description
 * @property string|null $preview_image
 * @property string|null $model_file
 * @property array|null $model_files
 * @property array|null $mesh_config
 * @property array|null $constraints
 * @property float $price_modifier
 * @property bool $is_default
 * @property bool $is_active
 * @property int $sort_order
 */
class PartOption3D extends Model
{
  protected $table = 'part_options_3d';

  protected $fillable = [
    'part_category_id',
    'code',
    'name',
    'name_vi',
    'description',
    'preview_image',
    'model_file',
    'model_files',
    'mesh_config',
    'constraints',
    'price_modifier',
    'is_default',
    'is_active',
    'sort_order',
  ];

  protected $casts = [
    'model_files' => 'array',
    'mesh_config' => 'array',
    'constraints' => 'array',
    'price_modifier' => 'decimal:0',
    'is_default' => 'boolean',
    'is_active' => 'boolean',
  ];

  // Relationships

  public function category(): BelongsTo
  {
    return $this->belongsTo(PartCategory3D::class, 'part_category_id');
  }

  public function subOptions(): HasMany
  {
    return $this->hasMany(PartSubOption3D::class, 'part_option_id')
      ->orderBy('sub_category')
      ->orderBy('sort_order');
  }

  public function meshes(): HasMany
  {
    return $this->hasMany(ModelMesh3D::class, 'part_option_id');
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

  public function getPreviewImageUrlAttribute(): ?string
  {
    if (!$this->preview_image) {
      return null;
    }
    return Storage::disk('public')->url($this->preview_image);
  }

  public function getModelFileUrlAttribute(): ?string
  {
    if (!$this->model_file) {
      return null;
    }

    $productType = $this->category->productType;
    return Storage::disk('public')->url("3d-models/{$productType->base_path}/{$this->model_file}");
  }

    // Methods

  /**
   * Check if this option is compatible with current configuration
   */
  public function isCompatibleWith(array $currentConfig): bool
  {
    if (empty($this->constraints)) {
      return true;
    }

    foreach ($this->constraints as $categoryCode => $allowedOptions) {
      if (!isset($currentConfig[$categoryCode])) {
        continue;
      }

      $currentOption = $currentConfig[$categoryCode];

      if (is_array($allowedOptions)) {
        if (!in_array($currentOption, $allowedOptions)) {
          return false;
        }
      } elseif ($allowedOptions !== $currentOption) {
        return false;
      }
    }

    return true;
  }

  /**
   * Get all model files needed for this option
   */
  public function getAllModelFiles(): array
  {
    $files = [];

    if ($this->model_file) {
      $files[] = [
        'path' => $this->model_file,
        'url' => $this->model_file_url,
        'primary' => true,
      ];
    }

    if ($this->model_files) {
      foreach ($this->model_files as $file) {
        $productType = $this->category->productType;
        $files[] = [
          'path' => $file['path'] ?? $file,
          'url' => Storage::disk('public')->url("3d-models/{$productType->base_path}/" . ($file['path'] ?? $file)),
          'primary' => false,
          'mesh_name' => $file['mesh_name'] ?? null,
          'material_slot' => $file['material_slot'] ?? null,
        ];
      }
    }

    return $files;
  }

  /**
   * Get sub-options grouped by sub-category
   */
  public function getSubOptionsGrouped(): array
  {
    return $this->subOptions()
      ->active()
      ->get()
      ->groupBy('sub_category')
      ->toArray();
  }

  /**
   * Get default sub-option for a sub-category
   */
  public function getDefaultSubOption(string $subCategory): ?PartSubOption3D
  {
    return $this->subOptions()
      ->where('sub_category', $subCategory)
      ->where('is_default', true)
      ->first()
      ?? $this->subOptions()
      ->where('sub_category', $subCategory)
      ->first();
  }
}
