<?php

namespace App\Models\ThreeD;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Cache for loaded model combinations
 *
 * @property int $id
 * @property string $cache_key
 * @property int $product_type_id
 * @property array $configuration
 * @property string|null $combined_model_path
 * @property \Carbon\Carbon $last_accessed_at
 */
class ModelCache3D extends Model
{
  protected $table = 'model_cache_3d';

  protected $fillable = [
    'cache_key',
    'product_type_id',
    'configuration',
    'combined_model_path',
    'last_accessed_at',
  ];

  protected $casts = [
    'configuration' => 'array',
    'last_accessed_at' => 'datetime',
  ];

  // Relationships

  public function productType(): BelongsTo
  {
    return $this->belongsTo(ProductType3D::class, 'product_type_id');
  }

    // Methods

  /**
   * Touch the last_accessed_at timestamp
   */
  public function touchAccess(): void
  {
    $this->update(['last_accessed_at' => now()]);
  }

  /**
   * Find or create cache entry
   */
  public static function findOrCreateForConfiguration(
    ProductType3D $productType,
    array $configuration
  ): self {
    $cacheKey = md5(json_encode([
      'product' => $productType->id,
      'config' => $configuration,
    ]));

    $cache = self::firstOrCreate(
      ['cache_key' => $cacheKey],
      [
        'product_type_id' => $productType->id,
        'configuration' => $configuration,
        'last_accessed_at' => now(),
      ]
    );

    $cache->touchAccess();

    return $cache;
  }

  /**
   * Cleanup old cache entries
   */
  public static function cleanup(int $daysOld = 30): int
  {
    return self::where('last_accessed_at', '<', now()->subDays($daysOld))
      ->delete();
  }
}
