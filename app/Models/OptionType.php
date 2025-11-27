<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OptionType extends Model
{
  use HasFactory;

  protected $fillable = [
  'name',
  'name_vi',
  'slug',
  'description',
  'description_vi',
  'icon',
  'type',
  'is_required',
  'is_active',
  'sort_order',
  ];

  protected $casts = [
  'is_required' => 'boolean',
  'is_active' => 'boolean',
  ];

  protected $appends = ['display_name'];

  public function values(): HasMany
  {
  return $this->hasMany(OptionValue::class);
  }

  public function activeValues(): HasMany
  {
  return $this->values()->where('is_active', true)->orderBy('sort_order');
  }

  public function suitModels(): BelongsToMany
  {
  return $this->belongsToMany(SuitModel::class, 'suit_model_option_types')
      ->withPivot('is_required')
      ->withTimestamps();
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

  public function getDefaultValue(): ?OptionValue
  {
  return $this->values()->where('is_default', true)->first()
      ?? $this->values()->first();
  }
}
