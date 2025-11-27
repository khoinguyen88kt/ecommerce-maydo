<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Fabric extends Model
{
  use HasFactory;

  protected $fillable = [
  'fabric_category_id',
  'name',
  'name_vi',
  'slug',
  'code',
  'description',
  'description_vi',
  'color_hex',
  'texture_image',
  'thumbnail',
  'price_modifier',
  'material_composition',
  'weight',
  'origin',
  'is_active',
  'is_featured',
  'sort_order',
  'stock_quantity',
  ];

  protected $casts = [
  'price_modifier' => 'decimal:0',
  'is_active' => 'boolean',
  'is_featured' => 'boolean',
  ];

  protected $appends = ['texture_url', 'thumbnail_url', 'display_name', 'formatted_price_modifier'];

  public function category(): BelongsTo
  {
  return $this->belongsTo(FabricCategory::class, 'fabric_category_id');
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

  public function getTextureUrlAttribute(): ?string
  {
  if (!$this->texture_image) {
      return null;
  }

  if (str_starts_with($this->texture_image, 'http')) {
      return $this->texture_image;
  }

  return Storage::url($this->texture_image);
  }

  public function getThumbnailUrlAttribute(): ?string
  {
  if (!$this->thumbnail) {
      return $this->texture_url;
  }

  if (str_starts_with($this->thumbnail, 'http')) {
      return $this->thumbnail;
  }

  return Storage::url($this->thumbnail);
  }

  public function getFormattedPriceModifierAttribute(): string
  {
  if ($this->price_modifier == 0) {
      return '';
  }

  $prefix = $this->price_modifier > 0 ? '+' : '';
  return $prefix . number_format($this->price_modifier, 0, ',', '.') . ' ₫';
  }
}
