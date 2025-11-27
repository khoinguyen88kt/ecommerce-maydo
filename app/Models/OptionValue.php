<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class OptionValue extends Model
{
  use HasFactory;

  protected $fillable = [
  'option_type_id',
  'name',
  'name_vi',
  'slug',
  'description',
  'description_vi',
  'preview_image',
  'price_modifier',
  'layer_key',
  'is_default',
  'is_active',
  'sort_order',
  ];

  protected $casts = [
  'price_modifier' => 'decimal:0',
  'is_default' => 'boolean',
  'is_active' => 'boolean',
  ];

  protected $appends = ['preview_image_url', 'display_name', 'formatted_price_modifier'];

  public function optionType(): BelongsTo
  {
  return $this->belongsTo(OptionType::class);
  }

  public function scopeActive($query)
  {
  return $query->where('is_active', true);
  }

  public function scopeOrdered($query)
  {
  return $query->orderBy('sort_order')->orderBy('name_vi');
  }

  public function getDisplayNameAttribute(): string
  {
  return app()->getLocale() === 'vi' ? $this->name_vi : $this->name;
  }

  public function getPreviewImageUrlAttribute(): ?string
  {
  if (!$this->preview_image) {
      return null;
  }

  if (str_starts_with($this->preview_image, 'http')) {
      return $this->preview_image;
  }

  return Storage::url($this->preview_image);
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
