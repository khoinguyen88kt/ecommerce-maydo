<?php

namespace App\Models\ThreeD;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Part Category for 3D Configurator (Style, Lapel, Pocket, Sleeve, Vent, etc.)
 *
 * @property int $id
 * @property int $product_type_id
 * @property string $code
 * @property string $name
 * @property string $name_vi
 * @property string|null $description
 * @property string|null $icon
 * @property string|null $path_segment
 * @property bool $is_required
 * @property bool $allow_multiple
 * @property bool $affects_other_parts
 * @property array|null $dependencies
 * @property int $sort_order
 * @property bool $is_active
 */
class PartCategory3D extends Model
{
  protected $table = 'part_categories_3d';

  protected $fillable = [
    'product_type_id',
    'code',
    'name',
    'name_vi',
    'description',
    'icon',
    'path_segment',
    'is_required',
    'allow_multiple',
    'affects_other_parts',
    'dependencies',
    'sort_order',
    'is_active',
  ];

  protected $casts = [
    'dependencies' => 'array',
    'is_required' => 'boolean',
    'allow_multiple' => 'boolean',
    'affects_other_parts' => 'boolean',
    'is_active' => 'boolean',
  ];

  // Relationships

  public function productType(): BelongsTo
  {
    return $this->belongsTo(ProductType3D::class, 'product_type_id');
  }

  public function options(): HasMany
  {
    return $this->hasMany(PartOption3D::class, 'part_category_id')
      ->orderBy('sort_order');
  }

  // Scopes

  public function scopeActive($query)
  {
    return $query->where('is_active', true);
  }

  public function scopeRequired($query)
  {
    return $query->where('is_required', true);
  }

  public function scopeOrdered($query)
  {
    return $query->orderBy('sort_order');
  }

    // Methods

  /**
   * Get the default option for this category
   */
  public function getDefaultOption(): ?PartOption3D
  {
    return $this->options()->where('is_default', true)->first()
      ?? $this->options()->first();
  }

  /**
   * Check if this category has dependencies on other categories
   */
  public function hasDependencies(): bool
  {
    return !empty($this->dependencies);
  }

  /**
   * Get options filtered by dependencies
   */
  public function getAvailableOptions(array $currentConfig = []): \Illuminate\Database\Eloquent\Collection
  {
    $options = $this->options()->active()->get();

    if (empty($currentConfig) || !$this->hasDependencies()) {
      return $options;
    }

    return $options->filter(function ($option) use ($currentConfig) {
      return $option->isCompatibleWith($currentConfig);
    });
  }
}
