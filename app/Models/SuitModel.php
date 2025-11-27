<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class SuitModel extends Model
{
  use HasFactory;

  protected $fillable = [
  'name',
  'name_vi',
  'slug',
  'description',
  'description_vi',
  'thumbnail',
  'base_price',
  'layer_config',
  'is_active',
  'is_featured',
  'sort_order',
  ];

  protected $casts = [
  'base_price' => 'decimal:0',
  'layer_config' => 'array',
  'is_active' => 'boolean',
  'is_featured' => 'boolean',
  ];

  protected $appends = ['thumbnail_url', 'display_name', 'formatted_base_price'];

  public function optionTypes(): BelongsToMany
  {
  return $this->belongsToMany(OptionType::class, 'suit_model_option_types')
      ->withPivot('is_required')
      ->withTimestamps();
  }

  public function layers(): HasMany
  {
  return $this->hasMany(SuitLayer::class);
  }

  public function configurations(): HasMany
  {
  return $this->hasMany(SuitConfiguration::class);
  }

  public function scopeActive($query)
  {
  return $query->where('is_active', true);
  }

  public function scopeFeatured($query)
  {
  return $query->where('is_featured', true);
  }

  public function scopeOrdered($query)
  {
  return $query->orderBy('sort_order')->orderBy('name_vi');
  }

  public function getDisplayNameAttribute(): string
  {
  return app()->getLocale() === 'vi' ? $this->name_vi : $this->name;
  }

  public function getThumbnailUrlAttribute(): ?string
  {
  if (!$this->thumbnail) {
      return null;
  }

  if (str_starts_with($this->thumbnail, 'http')) {
      return $this->thumbnail;
  }

  return Storage::url($this->thumbnail);
  }

  public function getFormattedBasePriceAttribute(): string
  {
  return number_format($this->base_price, 0, ',', '.') . ' ₫';
  }

  /**
   * Get layers for a specific fabric and view
   */
  public function getLayersForFabric(int $fabricId, string $view = 'front'): array
  {
  return $this->layers()
      ->where('fabric_id', $fabricId)
      ->where('view', $view)
      ->where('is_active', true)
      ->orderBy('z_index')
      ->get()
      ->toArray();
  }

  /**
   * Get all option types with their values for this suit model
   */
  public function getOptionsWithValues()
  {
  return $this->optionTypes()
      ->where('option_types.is_active', true)
      ->orderBy('option_types.sort_order')
      ->with(['values' => function ($query) {
    $query->where('is_active', true)->orderBy('sort_order');
      }])
      ->get();
  }
}
