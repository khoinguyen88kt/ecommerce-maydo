<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class ThreeDModel extends Model
{
  protected $fillable = [
    'suit_model_id',
    'glb_file',
    'preview_image',
    'parts_mapping',
    'notes',
    'is_processed',
    'processed_at',
    'layers_count',
  ];

  protected $casts = [
    'parts_mapping' => 'array',
    'is_processed' => 'boolean',
    'processed_at' => 'datetime',
  ];

  public function suitModel(): BelongsTo
  {
    return $this->belongsTo(SuitModel::class);
  }

  public function getGlbUrlAttribute(): ?string
  {
    return $this->glb_file ? Storage::url($this->glb_file) : null;
  }

  public function getPreviewUrlAttribute(): ?string
  {
    return $this->preview_image ? Storage::url($this->preview_image) : null;
  }
}
