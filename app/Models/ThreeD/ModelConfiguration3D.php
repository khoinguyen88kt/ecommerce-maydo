<?php

namespace App\Models\ThreeD;

use App\Models\Fabric;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Saved configuration for 3D Configurator
 *
 * @property int $id
 * @property int $product_type_id
 * @property int|null $fabric_id
 * @property string|null $name
 * @property string|null $name_vi
 * @property array $selected_options
 * @property array|null $sub_options
 * @property array|null $contrast_settings
 * @property float $calculated_price
 * @property bool $is_default
 * @property bool $is_template
 * @property int|null $user_id
 */
class ModelConfiguration3D extends Model
{
  protected $table = 'model_configurations_3d';

  protected $fillable = [
    'product_type_id',
    'fabric_id',
    'name',
    'name_vi',
    'selected_options',
    'sub_options',
    'contrast_settings',
    'calculated_price',
    'is_default',
    'is_template',
    'user_id',
  ];

  protected $casts = [
    'selected_options' => 'array',
    'sub_options' => 'array',
    'contrast_settings' => 'array',
    'calculated_price' => 'decimal:0',
    'is_default' => 'boolean',
    'is_template' => 'boolean',
  ];

  // Relationships

  public function productType(): BelongsTo
  {
    return $this->belongsTo(ProductType3D::class, 'product_type_id');
  }

  public function fabric(): BelongsTo
  {
    return $this->belongsTo(Fabric::class);
  }

  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }

  // Scopes

  public function scopeTemplates($query)
  {
    return $query->where('is_template', true);
  }

  public function scopeDefaults($query)
  {
    return $query->where('is_default', true);
  }

  public function scopeForUser($query, $userId)
  {
    return $query->where('user_id', $userId);
  }

    // Methods

  /**
   * Calculate total price based on selected options
   */
  public function calculatePrice(): float
  {
    $basePrice = 0;

    // Get fabric price
    if ($this->fabric) {
      $basePrice = $this->fabric->price_modifier;
    }

    // Add option modifiers
    foreach ($this->selected_options as $categoryCode => $optionCode) {
      $option = PartOption3D::whereHas('category', function ($q) use ($categoryCode) {
        $q->where('code', $categoryCode)
          ->where('product_type_id', $this->product_type_id);
      })->where('code', $optionCode)->first();

      if ($option) {
        $basePrice += $option->price_modifier;
      }
    }

    // Add sub-option modifiers
    if ($this->sub_options) {
      foreach ($this->sub_options as $optionCode => $subOptions) {
        foreach ($subOptions as $subCategory => $subCode) {
          $subOption = PartSubOption3D::whereHas('option', function ($q) use ($optionCode) {
            $q->where('code', $optionCode);
          })
            ->where('sub_category', $subCategory)
            ->where('code', $subCode)
            ->first();

          if ($subOption) {
            $basePrice += $subOption->price_modifier;
          }
        }
      }
    }

    return $basePrice;
  }

  /**
   * Get all model files needed to render this configuration
   */
  public function getModelFiles(): array
  {
    return $this->productType->getModelParts($this->selected_options);
  }

  /**
   * Generate cache key for this configuration
   */
  public function getCacheKey(): string
  {
    $data = [
      'product' => $this->product_type_id,
      'options' => $this->selected_options,
      'sub_options' => $this->sub_options,
    ];

    return md5(json_encode($data));
  }

  /**
   * Create configuration from default settings
   */
  public static function createDefault(ProductType3D $productType, ?Fabric $fabric = null): self
  {
    $config = new self([
      'product_type_id' => $productType->id,
      'fabric_id' => $fabric?->id,
      'selected_options' => $productType->getDefaultConfiguration(),
      'is_default' => true,
    ]);

    $config->calculated_price = $config->calculatePrice();

    return $config;
  }
}
