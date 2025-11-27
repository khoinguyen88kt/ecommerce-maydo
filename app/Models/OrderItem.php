<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
  use HasFactory;

  protected $fillable = [
  'order_id',
  'suit_configuration_id',
  'suit_model_name',
  'fabric_name',
  'selected_options',
  'measurements',
  'unit_price',
  'quantity',
  'total_price',
  'screenshot',
  'notes',
  ];

  protected $casts = [
  'selected_options' => 'array',
  'measurements' => 'array',
  'unit_price' => 'decimal:0',
  'total_price' => 'decimal:0',
  ];

  public function order(): BelongsTo
  {
  return $this->belongsTo(Order::class);
  }

  public function configuration(): BelongsTo
  {
  return $this->belongsTo(SuitConfiguration::class, 'suit_configuration_id');
  }

  public function getFormattedUnitPriceAttribute(): string
  {
  return number_format($this->unit_price, 0, ',', '.') . ' ₫';
  }

  public function getFormattedTotalPriceAttribute(): string
  {
  return number_format($this->total_price, 0, ',', '.') . ' ₫';
  }
}
