<?php

namespace App\Models\ThreeD;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

/**
 * Part Sub-Option for complex parts (Lapel Width, Button Style, etc.)
 *
 * @property int $id
 * @property int $part_option_id
 * @property string $code
 * @property string $name
 * @property string $name_vi
 * @property string $sub_category
 * @property string|null $model_file
 * @property array|null $mesh_config
 * @property float $price_modifier
 * @property bool $is_default
 * @property bool $is_active
 * @property int $sort_order
 */
class PartSubOption3D extends Model
{
  protected $table = 'part_sub_options_3d';

  protected $fillable = [
    'part_option_id',
    'code',
    'name',
    'name_vi',
    'sub_category',
    'model_file',
    'mesh_config',
    'price_modifier',
    'is_default',
    'is_active',
    'sort_order',
  ];

  protected $casts = [
    'mesh_config' => 'array',
    'price_modifier' => 'decimal:0',
    'is_default' => 'boolean',
    'is_active' => 'boolean',
  ];

  // Relationships

  public function option(): BelongsTo
  {
    return $this->belongsTo(PartOption3D::class, 'part_option_id');
  }

  public function meshes(): HasMany
  {
    return $this->hasMany(ModelMesh3D::class, 'part_sub_option_id');
  }

  // Scopes

  public function scopeActive($query)
  {
    return $query->where('is_active', true);
  }

  public function scopeForSubCategory($query, string $subCategory)
  {
    return $query->where('sub_category', $subCategory);
  }

  public function scopeOrdered($query)
  {
    return $query->orderBy('sort_order');
  }

  // Accessors

  public function getModelFileUrlAttribute(): ?string
  {
    if (!$this->model_file) {
      return null;
    }

    $productType = $this->option->category->productType;
    return Storage::disk('public')->url("3d-models/{$productType->base_path}/{$this->model_file}");
  }
}
