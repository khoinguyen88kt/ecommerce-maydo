<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FabricCategory extends Model
{
  use HasFactory;

  protected $fillable = [
  'name',
  'name_vi',
  'slug',
  'description',
  'description_vi',
  'is_active',
  'sort_order',
  ];

  protected $casts = [
  'is_active' => 'boolean',
  ];

  public function fabrics(): HasMany
  {
  return $this->hasMany(Fabric::class);
  }

  public function activeFabrics(): HasMany
  {
  return $this->fabrics()->where('is_active', true)->orderBy('sort_order');
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
}
