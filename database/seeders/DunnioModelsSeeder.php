<?php

namespace Database\Seeders;

use App\Models\SuitModel;
use App\Models\ThreeDModel;
use Illuminate\Database\Seeder;

class DunnioModelsSeeder extends Seeder
{
  /**
   * Seed Dunnio 3D models from downloaded GLB files.
   */
  public function run(): void
  {
    $this->command->info('Creating Dunnio Suit Models...');

    // Create Jacket SuitModel
    $jacket = SuitModel::updateOrCreate(
      ['slug' => 'dunnio-jacket'],
      [
        'name' => 'Dunnio Jacket',
        'name_vi' => 'Áo Vest Dunnio',
        'description' => 'High quality 3D jacket model from Dunnio Tailor',
        'description_vi' => 'Mô hình áo vest 3D chất lượng cao từ Dunnio Tailor',
        'style' => 'simple',
        'button_count' => 2,
        'lapel_type' => 'notch',
        'base_price' => 5000000,
        'is_active' => true,
        'is_featured' => true,
      ]
    );
    $this->command->info("  ✓ Created SuitModel: {$jacket->name} (ID: {$jacket->id})");

    // Create Pant SuitModel
    $pant = SuitModel::updateOrCreate(
      ['slug' => 'dunnio-pant'],
      [
        'name' => 'Dunnio Pant',
        'name_vi' => 'Quần Âu Dunnio',
        'description' => 'High quality 3D pant model from Dunnio Tailor',
        'description_vi' => 'Mô hình quần âu 3D chất lượng cao từ Dunnio Tailor',
        'style' => 'simple',
        'button_count' => 1,
        'lapel_type' => 'standard',
        'base_price' => 2000000,
        'is_active' => true,
        'is_featured' => false,
      ]
    );
    $this->command->info("  ✓ Created SuitModel: {$pant->name} (ID: {$pant->id})");

    // Create Complete Suit SuitModel
    $suit = SuitModel::updateOrCreate(
      ['slug' => 'dunnio-complete-suit'],
      [
        'name' => 'Dunnio Complete Suit',
        'name_vi' => 'Bộ Vest Dunnio Hoàn Chỉnh',
        'description' => 'Complete 3D suit model with jacket and pant from Dunnio Tailor',
        'description_vi' => 'Mô hình bộ vest 3D hoàn chỉnh với áo và quần từ Dunnio Tailor',
        'style' => 'simple',
        'button_count' => 2,
        'lapel_type' => 'notch',
        'base_price' => 7000000,
        'is_active' => true,
        'is_featured' => true,
      ]
    );
    $this->command->info("  ✓ Created SuitModel: {$suit->name} (ID: {$suit->id})");

    $this->command->info('Creating ThreeDModel records...');

    // Jacket 3D Model
    ThreeDModel::updateOrCreate(
      ['suit_model_id' => $jacket->id],
      [
        'glb_file' => '3d-models/jacket-complete.glb',
        'parts_mapping' => [
          'jacket_front' => 'Front_Curved',
          'jacket_lapel_upper' => 'Lapel_Upper',
          'jacket_lapel_lower' => 'Lapel_Lower',
          'jacket_sleeve' => 'Sleeve',
          'jacket_pocket' => 'Pocket_PK1',
          'jacket_chest_pocket' => 'ChestPocket',
          'jacket_vent' => 'SideVent',
          'jacket_lining' => 'Lining_Curved',
          'jacket_lining_sleeve' => 'Lining_Sleeve',
          'jacket_brand' => 'Label',
        ],
        'notes' => 'Imported from Dunnio Tailor',
        'is_processed' => false,
        'layers_count' => 10,
      ]
    );
    $this->command->info('  ✓ Created ThreeDModel for Jacket');

    // Pant 3D Model
    ThreeDModel::updateOrCreate(
      ['suit_model_id' => $pant->id],
      [
        'glb_file' => '3d-models/pant-complete.glb',
        'parts_mapping' => [
          'pant_style' => 'Style_Dave',
          'pant_waistband' => 'Waistband_Square',
          'pant_pocket' => 'Pocket_Slanted',
          'pant_back_pocket' => 'BackPocket_Single',
          'pant_lining' => 'Lining_Dave',
          'pant_beltloops' => 'Beltloops_Single',
        ],
        'notes' => 'Imported from Dunnio Tailor',
        'is_processed' => false,
        'layers_count' => 6,
      ]
    );
    $this->command->info('  ✓ Created ThreeDModel for Pant');

    // Complete Suit 3D Model
    ThreeDModel::updateOrCreate(
      ['suit_model_id' => $suit->id],
      [
        'glb_file' => '3d-models/suit-complete.glb',
        'parts_mapping' => [
          // Jacket parts
          'jacket_front' => 'Front_Curved',
          'jacket_lapel_upper' => 'Lapel_Upper',
          'jacket_lapel_lower' => 'Lapel_Lower',
          'jacket_sleeve' => 'Sleeve',
          'jacket_pocket' => 'Pocket_PK1',
          'jacket_chest_pocket' => 'ChestPocket',
          'jacket_vent' => 'SideVent',
          'jacket_lining' => 'Lining_Curved',
          'jacket_lining_sleeve' => 'Lining_Sleeve',
          'jacket_brand' => 'Label',
          // Pant parts
          'pant_style' => 'Style_Dave',
          'pant_waistband' => 'Waistband_Square',
          'pant_pocket' => 'Pocket_Slanted',
          'pant_back_pocket' => 'BackPocket_Single',
          'pant_lining' => 'Lining_Dave',
          'pant_beltloops' => 'Beltloops_Single',
        ],
        'notes' => 'Imported from Dunnio Tailor - Complete suit with jacket and pant',
        'is_processed' => false,
        'layers_count' => 16,
      ]
    );
    $this->command->info('  ✓ Created ThreeDModel for Complete Suit');

    $this->command->info('');
    $this->command->info('Summary:');
    $this->command->info("  - SuitModels: " . SuitModel::count());
    $this->command->info("  - ThreeDModels: " . ThreeDModel::count());
    $this->command->info('');
    $this->command->info('Done! You can now view the models at /admin/three-d-models');
  }
}
