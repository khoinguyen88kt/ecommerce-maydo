<?php

namespace App\Models\ThreeD;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Mesh definition for 3D model files
 *
 * @property int $id
 * @property int|null $part_option_id
 * @property int|null $part_sub_option_id
 * @property string $mesh_name
 * @property string $material_type
 * @property array|null $texture_settings
 * @property array|null $uv_transform
 * @property bool $apply_fabric_texture
 */
class ModelMesh3D extends Model
{
  protected $table = 'model_meshes_3d';

  protected $fillable = [
    'part_option_id',
    'part_sub_option_id',
    'mesh_name',
    'material_type',
    'texture_settings',
    'uv_transform',
    'apply_fabric_texture',
  ];

  protected $casts = [
    'texture_settings' => 'array',
    'uv_transform' => 'array',
    'apply_fabric_texture' => 'boolean',
  ];

  /**
   * Material types constants
   */
  public const MATERIAL_FABRIC = 'fabric';
  public const MATERIAL_LINING = 'lining';
  public const MATERIAL_BUTTON = 'button';
  public const MATERIAL_THREAD = 'thread';
  public const MATERIAL_CONTRAST = 'contrast';
  public const MATERIAL_METAL = 'metal';

  public static array $materialTypes = [
    self::MATERIAL_FABRIC => 'Fabric (Main)',
    self::MATERIAL_LINING => 'Lining',
    self::MATERIAL_BUTTON => 'Button',
    self::MATERIAL_THREAD => 'Thread/Stitching',
    self::MATERIAL_CONTRAST => 'Contrast Fabric',
    self::MATERIAL_METAL => 'Metal/Hardware',
  ];

  // Relationships

  public function option(): BelongsTo
  {
    return $this->belongsTo(PartOption3D::class, 'part_option_id');
  }

  public function subOption(): BelongsTo
  {
    return $this->belongsTo(PartSubOption3D::class, 'part_sub_option_id');
  }

    // Methods

  /**
   * Get merged texture settings with defaults
   */
  public function getTextureSettings(): array
  {
    $defaults = $this->getDefaultTextureSettings();
    return array_merge($defaults, $this->texture_settings ?? []);
  }

  /**
   * Get default texture settings based on material type
   */
  public function getDefaultTextureSettings(): array
  {
    return match ($this->material_type) {
      self::MATERIAL_FABRIC => [
        'scale' => ['u' => 5, 'v' => 5],
        'metallic' => 0,
        'roughness' => 1,
        'color' => '#ffffff',
      ],
      self::MATERIAL_LINING => [
        'scale' => ['u' => 5, 'v' => 5],
        'metallic' => 0,
        'roughness' => 1,
        'color' => '#3d85c6',
      ],
      self::MATERIAL_BUTTON => [
        'scale' => ['u' => 1, 'v' => 1],
        'metallic' => 0.7,
        'roughness' => 1,
        'color' => '#000000',
      ],
      self::MATERIAL_THREAD => [
        'scale' => ['u' => 1, 'v' => 1],
        'metallic' => 0,
        'roughness' => 1,
        'color' => '#676b6a',
      ],
      self::MATERIAL_METAL => [
        'scale' => ['u' => 1, 'v' => 1],
        'metallic' => 1,
        'roughness' => 0.2,
        'color' => '#ffffff',
      ],
      default => [
        'scale' => ['u' => 5, 'v' => 5],
        'metallic' => 0,
        'roughness' => 1,
        'color' => '#ffffff',
      ],
    };
  }

  /**
   * Get UV transform or defaults
   */
  public function getUvTransform(): array
  {
    return array_merge([
      'scale' => ['u' => 1, 'v' => 1],
      'offset' => ['u' => 0, 'v' => 0],
      'rotation' => 0,
    ], $this->uv_transform ?? []);
  }
}
