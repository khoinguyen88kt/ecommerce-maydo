<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
  use HasFactory;

  protected $fillable = [
  'user_id',
  'session_id',
  ];

  public function user(): BelongsTo
  {
  return $this->belongsTo(User::class);
  }

  public function items(): HasMany
  {
  return $this->hasMany(CartItem::class);
  }

  public function getTotalAttribute(): float
  {
  return $this->items->sum(function ($item) {
      return $item->configuration->total_price * $item->quantity;
  });
  }

  public function getFormattedTotalAttribute(): string
  {
  return number_format($this->total, 0, ',', '.') . ' ₫';
  }

  public function getItemCountAttribute(): int
  {
  return $this->items->sum('quantity');
  }

  public static function getOrCreate(?int $userId = null, ?string $sessionId = null): self
  {
  if ($userId) {
      return self::firstOrCreate(['user_id' => $userId]);
  }

  if ($sessionId) {
      return self::firstOrCreate(['session_id' => $sessionId]);
  }

  return new self();
  }

  public function mergeCarts(Cart $guestCart): void
  {
  foreach ($guestCart->items as $item) {
      $this->items()->create([
    'suit_configuration_id' => $item->suit_configuration_id,
    'quantity' => $item->quantity,
    'measurements' => $item->measurements,
    'notes' => $item->notes,
      ]);
  }

  $guestCart->delete();
  }
}
