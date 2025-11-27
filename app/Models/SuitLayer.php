<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class SuitLayer extends Model
{
  use HasFactory;

  protected $fillable = [
  'suit_model_id',
  'fabric_id',
  'view',
  'part',
  'option_value_slug',
  'image_path',
  'z_index',
  'is_active',
  ];

  protected $casts = [
  'is_active' => 'boolean',
  ];

  protected $appends = ['image_url'];

  public function suitModel(): BelongsTo
  {
  return $this->belongsTo(SuitModel::class);
  }

  public function fabric(): BelongsTo
  {
  return $this->belongsTo(Fabric::class);
  }

  public function scopeActive($query)
  {
  return $query->where('is_active', true);
  }

  public function scopeForView($query, string $view)
  {
  return $query->where('view', $view);
  }

  public function scopeOrdered($query)
  {
  return $query->orderBy('z_index');
  }

  public function getImageUrlAttribute(): ?string
  {
  if (!$this->image_path) {
      return null;
  }

  if (str_starts_with($this->image_path, 'http')) {
      return $this->image_path;
  }

  return Storage::url($this->image_path);
  }
}
