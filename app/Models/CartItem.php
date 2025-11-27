<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
  use HasFactory;

  protected $fillable = [
  'cart_id',
  'suit_configuration_id',
  'quantity',
  'measurements',
  'notes',
  ];

  protected $casts = [
  'measurements' => 'array',
  ];

  public function cart(): BelongsTo
  {
  return $this->belongsTo(Cart::class);
  }

  public function configuration(): BelongsTo
  {
  return $this->belongsTo(SuitConfiguration::class, 'suit_configuration_id');
  }

  public function getSubtotalAttribute(): float
  {
  return $this->configuration->total_price * $this->quantity;
  }

  public function getFormattedSubtotalAttribute(): string
  {
  return number_format($this->subtotal, 0, ',', '.') . ' ₫';
  }
}
