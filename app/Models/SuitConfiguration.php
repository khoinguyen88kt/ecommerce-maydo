<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class SuitConfiguration extends Model
{
  use HasFactory;

  protected $fillable = [
  'user_id',
  'suit_model_id',
  'fabric_id',
  'share_code',
  'selected_options',
  'total_price',
  'screenshot',
  'notes',
  ];

  protected $casts = [
  'selected_options' => 'array',
  'total_price' => 'decimal:0',
  ];

  protected static function boot()
  {
  parent::boot();

  static::creating(function ($configuration) {
      if (empty($configuration->share_code)) {
    $configuration->share_code = Str::random(8);
      }
  });
  }

  public function user(): BelongsTo
  {
  return $this->belongsTo(User::class);
  }

  public function suitModel(): BelongsTo
  {
  return $this->belongsTo(SuitModel::class);
  }

  public function fabric(): BelongsTo
  {
  return $this->belongsTo(Fabric::class);
  }

  public function cartItems(): HasMany
  {
  return $this->hasMany(CartItem::class);
  }

  public function orderItems(): HasMany
  {
  return $this->hasMany(OrderItem::class);
  }

  public function getFormattedTotalPriceAttribute(): string
  {
  return number_format($this->total_price, 0, ',', '.') . ' ₫';
  }

  public function getShareUrlAttribute(): string
  {
  return route('configurator.shared', $this->share_code);
  }
}
