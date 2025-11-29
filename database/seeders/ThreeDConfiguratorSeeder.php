<?php

namespace Database\Seeders;

use App\Models\ThreeD\ModelMesh3D;
use App\Models\ThreeD\PartCategory3D;
use App\Models\ThreeD\PartOption3D;
use App\Models\ThreeD\PartSubOption3D;
use App\Models\ThreeD\ProductType3D;
use Illuminate\Database\Seeder;

class ThreeDConfiguratorSeeder extends Seeder
{
    public function run(): void
    {
        // Create Jacket Product Type
        $jacket = ProductType3D::create([
            'name' => 'Jacket',
            'name_vi' => 'Áo Vest',
            'code' => 'jacket',
            'description' => 'Suit jacket with multiple customization options',
            'base_path' => 'Jacket/',
            'texture_settings' => [
                'default_repeat' => ['u' => 32, 'v' => 32],
                'roughness' => 0.7,
                'metalness' => 0.0,
            ],
            'is_active' => true,
            'sort_order' => 1,
        ]);

        // Create Pant Product Type
        $pant = ProductType3D::create([
            'name' => 'Pant',
            'name_vi' => 'Quần Vest',
            'code' => 'pant',
            'description' => 'Suit trousers with various styles',
            'base_path' => 'Pant/',
            'texture_settings' => [
                'default_repeat' => ['u' => 32, 'v' => 32],
                'roughness' => 0.7,
                'metalness' => 0.0,
            ],
            'is_active' => true,
            'sort_order' => 2,
        ]);

        // Create Vest Product Type
        $vest = ProductType3D::create([
            'name' => 'Vest',
            'name_vi' => 'Áo Gile',
            'code' => 'vest',
            'description' => 'Waistcoat/vest for three-piece suits',
            'base_path' => 'Vest/',
            'texture_settings' => [
                'default_repeat' => ['u' => 32, 'v' => 32],
                'roughness' => 0.7,
                'metalness' => 0.0,
            ],
            'is_active' => true,
            'sort_order' => 3,
        ]);

        // ========================================
        // JACKET CATEGORIES AND OPTIONS
        // ========================================

        // Style Category
        $styleCategory = PartCategory3D::create([
            'product_type_id' => $jacket->id,
            'name' => 'Style',
            'name_vi' => 'Kiểu Dáng',
            'code' => 'style',
            'icon' => 'heroicon-o-squares-2x2',
            'is_required' => true,
            'affects_other_parts' => true,
            'sort_order' => 1,
        ]);

        // Style Options
        $twoButton = PartOption3D::create([
            'part_category_id' => $styleCategory->id,
            'name' => '2 Buttons',
            'name_vi' => '2 Khuy',
            'code' => '2button',
            'model_file' => 'Front/2Button.glb',
            'model_files' => [
                ['path' => 'Front/Bottom/2Button/Curved.glb', 'mesh_name' => 'bottom'],
                ['path' => 'Front/Button/2Button/S4.glb', 'mesh_name' => 'buttons'],
                ['path' => 'Front/Thread/2Button.glb', 'mesh_name' => 'thread'],
            ],
            'is_default' => true,
            'sort_order' => 1,
        ]);

        $threeButton = PartOption3D::create([
            'part_category_id' => $styleCategory->id,
            'name' => '3 Buttons',
            'name_vi' => '3 Khuy',
            'code' => '3button',
            'model_file' => 'Front/3Button.glb',
            'model_files' => [
                ['path' => 'Front/Bottom/3Button/Curved.glb', 'mesh_name' => 'bottom'],
                ['path' => 'Front/Button/3Button/S4.glb', 'mesh_name' => 'buttons'],
                ['path' => 'Front/Thread/3Button.glb', 'mesh_name' => 'thread'],
            ],
            'sort_order' => 2,
        ]);

        $doubleBreasted = PartOption3D::create([
            'part_category_id' => $styleCategory->id,
            'name' => 'Double Breasted',
            'name_vi' => '2 Hàng Khuy',
            'code' => 'double-breasted',
            'model_file' => 'Front/DoubleBreasted.glb',
            'model_files' => [
                ['path' => 'Front/Bottom/DoubleBreasted/Curved.glb', 'mesh_name' => 'bottom'],
                ['path' => 'Front/Button/DoubleBreasted/S4.glb', 'mesh_name' => 'buttons'],
            ],
            'sort_order' => 3,
        ]);

        // Lapel Category
        $lapelCategory = PartCategory3D::create([
            'product_type_id' => $jacket->id,
            'name' => 'Lapel',
            'name_vi' => 'Ve Áo',
            'code' => 'lapel',
            'icon' => 'heroicon-o-adjustments-horizontal',
            'is_required' => true,
            'dependencies' => ['style'],
            'sort_order' => 2,
        ]);

        // Lapel Options
        $notchLapel = PartOption3D::create([
            'part_category_id' => $lapelCategory->id,
            'name' => 'Notch Lapel',
            'name_vi' => 'Ve Bẻ',
            'code' => 'notch',
            'model_file' => 'Lapel/Regular/Upper/Notch.glb',
            'model_files' => [
                ['path' => 'Lapel/Regular/Lower/Notch.glb', 'mesh_name' => 'lapel_lower'],
            ],
            'is_default' => true,
            'sort_order' => 1,
        ]);

        // Lapel Sub-Options (Width)
        PartSubOption3D::create([
            'part_option_id' => $notchLapel->id,
            'name' => 'Small Width',
            'name_vi' => 'Ve Nhỏ',
            'code' => 'small',
            'sub_category' => 'width',
            'model_file' => 'Lapel/Small/Upper/Notch.glb',
            'sort_order' => 1,
        ]);

        PartSubOption3D::create([
            'part_option_id' => $notchLapel->id,
            'name' => 'Regular Width',
            'name_vi' => 'Ve Vừa',
            'code' => 'regular',
            'sub_category' => 'width',
            'is_default' => true,
            'sort_order' => 2,
        ]);

        PartSubOption3D::create([
            'part_option_id' => $notchLapel->id,
            'name' => 'Large Width',
            'name_vi' => 'Ve Rộng',
            'code' => 'large',
            'sub_category' => 'width',
            'model_file' => 'Lapel/Large/Upper/Notch.glb',
            'sort_order' => 3,
        ]);

        $peakLapel = PartOption3D::create([
            'part_category_id' => $lapelCategory->id,
            'name' => 'Peak Lapel',
            'name_vi' => 'Ve Nhọn',
            'code' => 'peak',
            'model_file' => 'Lapel/Regular/Upper/Peak.glb',
            'model_files' => [
                ['path' => 'Lapel/Regular/Lower/Peak.glb', 'mesh_name' => 'lapel_lower'],
            ],
            'sort_order' => 2,
        ]);

        $shawlLapel = PartOption3D::create([
            'part_category_id' => $lapelCategory->id,
            'name' => 'Shawl Lapel',
            'name_vi' => 'Ve Khăn',
            'code' => 'shawl',
            'model_file' => 'Lapel/Regular/Shawl.glb',
            'sort_order' => 3,
        ]);

        // Pocket Category
        $pocketCategory = PartCategory3D::create([
            'product_type_id' => $jacket->id,
            'name' => 'Pocket',
            'name_vi' => 'Túi',
            'code' => 'pocket',
            'icon' => 'heroicon-o-inbox',
            'is_required' => true,
            'sort_order' => 3,
        ]);

        // Pocket Options
        PartOption3D::create([
            'part_category_id' => $pocketCategory->id,
            'name' => 'Flap Pocket',
            'name_vi' => 'Túi Nắp',
            'code' => 'flap',
            'model_file' => 'Pocket/Flap.glb',
            'model_files' => [
                ['path' => 'Pocket/ChestPocket/Standard.glb', 'mesh_name' => 'chest_pocket'],
            ],
            'is_default' => true,
            'sort_order' => 1,
        ]);

        PartOption3D::create([
            'part_category_id' => $pocketCategory->id,
            'name' => 'Jetted Pocket',
            'name_vi' => 'Túi Viền',
            'code' => 'jetted',
            'model_file' => 'Pocket/Jetted.glb',
            'model_files' => [
                ['path' => 'Pocket/ChestPocket/Standard.glb', 'mesh_name' => 'chest_pocket'],
            ],
            'sort_order' => 2,
        ]);

        PartOption3D::create([
            'part_category_id' => $pocketCategory->id,
            'name' => 'Patch Pocket',
            'name_vi' => 'Túi Đắp',
            'code' => 'patch',
            'model_file' => 'Pocket/Patch.glb',
            'model_files' => [
                ['path' => 'Pocket/ChestPocket/Patch.glb', 'mesh_name' => 'chest_pocket'],
            ],
            'sort_order' => 3,
        ]);

        // Vent Category
        $ventCategory = PartCategory3D::create([
            'product_type_id' => $jacket->id,
            'name' => 'Vent',
            'name_vi' => 'Xẻ Sau',
            'code' => 'vent',
            'icon' => 'heroicon-o-arrows-up-down',
            'is_required' => true,
            'sort_order' => 4,
        ]);

        // Vent Options
        PartOption3D::create([
            'part_category_id' => $ventCategory->id,
            'name' => 'Side Vents',
            'name_vi' => 'Xẻ 2 Bên',
            'code' => 'side',
            'model_file' => 'Vent/SideVent.glb',
            'is_default' => true,
            'sort_order' => 1,
        ]);

        PartOption3D::create([
            'part_category_id' => $ventCategory->id,
            'name' => 'Center Vent',
            'name_vi' => 'Xẻ Giữa',
            'code' => 'center',
            'model_file' => 'Vent/CenterVent.glb',
            'sort_order' => 2,
        ]);

        PartOption3D::create([
            'part_category_id' => $ventCategory->id,
            'name' => 'No Vent',
            'name_vi' => 'Không Xẻ',
            'code' => 'none',
            'model_file' => 'Vent/NoVent.glb',
            'sort_order' => 3,
        ]);

        // Sleeve Category
        $sleeveCategory = PartCategory3D::create([
            'product_type_id' => $jacket->id,
            'name' => 'Sleeve',
            'name_vi' => 'Tay Áo',
            'code' => 'sleeve',
            'icon' => 'heroicon-o-hand-raised',
            'is_required' => true,
            'sort_order' => 5,
        ]);

        // Sleeve Options
        $standardSleeve = PartOption3D::create([
            'part_category_id' => $sleeveCategory->id,
            'name' => 'Standard',
            'name_vi' => 'Tiêu Chuẩn',
            'code' => 'standard',
            'model_file' => 'Sleeve/Sleeve.glb',
            'model_files' => [
                ['path' => 'Sleeve/Standard/4Button/S4.glb', 'mesh_name' => 'sleeve_buttons'],
            ],
            'is_default' => true,
            'sort_order' => 1,
        ]);

        // Sleeve Button Sub-Options
        PartSubOption3D::create([
            'part_option_id' => $standardSleeve->id,
            'name' => '3 Buttons',
            'name_vi' => '3 Khuy',
            'code' => '3button',
            'sub_category' => 'buttons',
            'model_file' => 'Sleeve/Standard/3Button/S4.glb',
            'sort_order' => 1,
        ]);

        PartSubOption3D::create([
            'part_option_id' => $standardSleeve->id,
            'name' => '4 Buttons',
            'name_vi' => '4 Khuy',
            'code' => '4button',
            'sub_category' => 'buttons',
            'is_default' => true,
            'sort_order' => 2,
        ]);

        PartSubOption3D::create([
            'part_option_id' => $standardSleeve->id,
            'name' => '5 Buttons',
            'name_vi' => '5 Khuy',
            'code' => '5button',
            'sub_category' => 'buttons',
            'model_file' => 'Sleeve/Standard/5Button/S4.glb',
            'sort_order' => 3,
        ]);

        // Lining Category
        $liningCategory = PartCategory3D::create([
            'product_type_id' => $jacket->id,
            'name' => 'Lining',
            'name_vi' => 'Lớp Lót',
            'code' => 'lining',
            'icon' => 'heroicon-o-square-2-stack',
            'is_required' => true,
            'dependencies' => ['style'],
            'sort_order' => 6,
        ]);

        // Lining Options
        PartOption3D::create([
            'part_category_id' => $liningCategory->id,
            'name' => 'Fully Lined',
            'name_vi' => 'Lót Toàn Bộ',
            'code' => 'full',
            'model_file' => 'Lining/FullyLined/Curved.glb',
            'model_files' => [
                ['path' => 'Lining/Sleeve.glb', 'mesh_name' => 'sleeve_lining'],
            ],
            'is_default' => true,
            'sort_order' => 1,
        ]);

        PartOption3D::create([
            'part_category_id' => $liningCategory->id,
            'name' => 'Half Lined',
            'name_vi' => 'Lót Nửa',
            'code' => 'half',
            'model_file' => 'Lining/HalfLined/Curved.glb',
            'model_files' => [
                ['path' => 'Lining/Sleeve.glb', 'mesh_name' => 'sleeve_lining'],
            ],
            'sort_order' => 2,
        ]);

        PartOption3D::create([
            'part_category_id' => $liningCategory->id,
            'name' => 'Unlined',
            'name_vi' => 'Không Lót',
            'code' => 'unlined',
            'sort_order' => 3,
        ]);

        // ========================================
        // PANT CATEGORIES AND OPTIONS
        // ========================================

        // Pant Style Category
        $pantStyleCategory = PartCategory3D::create([
            'product_type_id' => $pant->id,
            'name' => 'Style',
            'name_vi' => 'Kiểu Dáng',
            'code' => 'style',
            'icon' => 'heroicon-o-squares-2x2',
            'is_required' => true,
            'sort_order' => 1,
        ]);

        PartOption3D::create([
            'part_category_id' => $pantStyleCategory->id,
            'name' => 'Classic Fit',
            'name_vi' => 'Dáng Cổ Điển',
            'code' => 'classic',
            'model_file' => 'Classic.glb',
            'is_default' => true,
            'sort_order' => 1,
        ]);

        PartOption3D::create([
            'part_category_id' => $pantStyleCategory->id,
            'name' => 'Slim Fit',
            'name_vi' => 'Dáng Ôm',
            'code' => 'slim',
            'model_file' => 'Slim.glb',
            'sort_order' => 2,
        ]);

        // Pant Pleats Category
        $pantPleatsCategory = PartCategory3D::create([
            'product_type_id' => $pant->id,
            'name' => 'Pleats',
            'name_vi' => 'Ly',
            'code' => 'pleats',
            'icon' => 'heroicon-o-adjustments-horizontal',
            'is_required' => true,
            'sort_order' => 2,
        ]);

        PartOption3D::create([
            'part_category_id' => $pantPleatsCategory->id,
            'name' => 'Flat Front',
            'name_vi' => 'Không Ly',
            'code' => 'flat',
            'model_file' => 'Pleats/Flat.glb',
            'is_default' => true,
            'sort_order' => 1,
        ]);

        PartOption3D::create([
            'part_category_id' => $pantPleatsCategory->id,
            'name' => 'Single Pleat',
            'name_vi' => '1 Ly',
            'code' => 'single',
            'model_file' => 'Pleats/Single.glb',
            'sort_order' => 2,
        ]);

        PartOption3D::create([
            'part_category_id' => $pantPleatsCategory->id,
            'name' => 'Double Pleat',
            'name_vi' => '2 Ly',
            'code' => 'double',
            'model_file' => 'Pleats/Double.glb',
            'sort_order' => 3,
        ]);

        // Pant Hem Category
        $pantHemCategory = PartCategory3D::create([
            'product_type_id' => $pant->id,
            'name' => 'Hem',
            'name_vi' => 'Gấu Quần',
            'code' => 'hem',
            'icon' => 'heroicon-o-arrow-down-circle',
            'is_required' => true,
            'sort_order' => 3,
        ]);

        PartOption3D::create([
            'part_category_id' => $pantHemCategory->id,
            'name' => 'Plain',
            'name_vi' => 'Gấu Thường',
            'code' => 'plain',
            'model_file' => 'Hem/Plain.glb',
            'is_default' => true,
            'sort_order' => 1,
        ]);

        PartOption3D::create([
            'part_category_id' => $pantHemCategory->id,
            'name' => 'Cuff',
            'name_vi' => 'Gấu Xắn',
            'code' => 'cuff',
            'model_file' => 'Hem/Cuff.glb',
            'sort_order' => 2,
        ]);

        // ========================================
        // VEST CATEGORIES AND OPTIONS
        // ========================================

        // Vest Style Category
        $vestStyleCategory = PartCategory3D::create([
            'product_type_id' => $vest->id,
            'name' => 'Style',
            'name_vi' => 'Kiểu Dáng',
            'code' => 'style',
            'icon' => 'heroicon-o-squares-2x2',
            'is_required' => true,
            'sort_order' => 1,
        ]);

        PartOption3D::create([
            'part_category_id' => $vestStyleCategory->id,
            'name' => 'Single Breasted 5 Button',
            'name_vi' => '1 Hàng 5 Khuy',
            'code' => 'sb5',
            'model_file' => 'SingleBreasted5.glb',
            'is_default' => true,
            'sort_order' => 1,
        ]);

        PartOption3D::create([
            'part_category_id' => $vestStyleCategory->id,
            'name' => 'Single Breasted 6 Button',
            'name_vi' => '1 Hàng 6 Khuy',
            'code' => 'sb6',
            'model_file' => 'SingleBreasted6.glb',
            'sort_order' => 2,
        ]);

        PartOption3D::create([
            'part_category_id' => $vestStyleCategory->id,
            'name' => 'Double Breasted',
            'name_vi' => '2 Hàng Khuy',
            'code' => 'db',
            'model_file' => 'DoubleBreasted.glb',
            'sort_order' => 3,
        ]);

        // Vest Back Category
        $vestBackCategory = PartCategory3D::create([
            'product_type_id' => $vest->id,
            'name' => 'Back',
            'name_vi' => 'Lưng',
            'code' => 'back',
            'icon' => 'heroicon-o-arrows-pointing-in',
            'is_required' => true,
            'sort_order' => 2,
        ]);

        PartOption3D::create([
            'part_category_id' => $vestBackCategory->id,
            'name' => 'Same as Front',
            'name_vi' => 'Cùng Vải Mặt Trước',
            'code' => 'same',
            'model_file' => 'Back/Same.glb',
            'is_default' => true,
            'sort_order' => 1,
        ]);

        PartOption3D::create([
            'part_category_id' => $vestBackCategory->id,
            'name' => 'Lining Material',
            'name_vi' => 'Vải Lót',
            'code' => 'lining',
            'model_file' => 'Back/Lining.glb',
            'sort_order' => 2,
        ]);

        // ========================================
        // CREATE MESH DEFINITIONS FOR JACKET OPTIONS
        // ========================================

        // 2 Button Jacket Meshes
        ModelMesh3D::create([
            'part_option_id' => $twoButton->id,
            'mesh_name' => 'front_body',
            'material_type' => 'fabric',
            'apply_fabric_texture' => true,
            'texture_settings' => [
                'scale' => ['u' => 32, 'v' => 32],
                'roughness' => 0.7,
                'metallic' => 0.0,
            ],
        ]);

        ModelMesh3D::create([
            'part_option_id' => $twoButton->id,
            'mesh_name' => 'button_1',
            'material_type' => 'button',
            'apply_fabric_texture' => false,
            'texture_settings' => [
                'roughness' => 0.3,
                'metallic' => 0.8,
                'color' => '#1a1a1a',
            ],
        ]);

        ModelMesh3D::create([
            'part_option_id' => $twoButton->id,
            'mesh_name' => 'button_2',
            'material_type' => 'button',
            'apply_fabric_texture' => false,
            'texture_settings' => [
                'roughness' => 0.3,
                'metallic' => 0.8,
                'color' => '#1a1a1a',
            ],
        ]);

        ModelMesh3D::create([
            'part_option_id' => $twoButton->id,
            'mesh_name' => 'thread',
            'material_type' => 'thread',
            'apply_fabric_texture' => false,
            'texture_settings' => [
                'roughness' => 0.6,
                'metallic' => 0.0,
            ],
        ]);

        // Lapel Meshes
        ModelMesh3D::create([
            'part_option_id' => $notchLapel->id,
            'mesh_name' => 'lapel_upper',
            'material_type' => 'fabric',
            'apply_fabric_texture' => true,
            'texture_settings' => [
                'scale' => ['u' => 32, 'v' => 32],
                'roughness' => 0.7,
                'metallic' => 0.0,
            ],
        ]);

        ModelMesh3D::create([
            'part_option_id' => $notchLapel->id,
            'mesh_name' => 'lapel_lower',
            'material_type' => 'fabric',
            'apply_fabric_texture' => true,
        ]);

        ModelMesh3D::create([
            'part_option_id' => $peakLapel->id,
            'mesh_name' => 'lapel_upper',
            'material_type' => 'fabric',
            'apply_fabric_texture' => true,
        ]);

        ModelMesh3D::create([
            'part_option_id' => $shawlLapel->id,
            'mesh_name' => 'lapel',
            'material_type' => 'contrast',
            'apply_fabric_texture' => false,
            'texture_settings' => [
                'roughness' => 0.3,
                'metallic' => 0.1,
                'color' => '#1a1a1a',
            ],
        ]);

        // Sleeve Meshes
        ModelMesh3D::create([
            'part_option_id' => $standardSleeve->id,
            'mesh_name' => 'sleeve',
            'material_type' => 'fabric',
            'apply_fabric_texture' => true,
        ]);

        ModelMesh3D::create([
            'part_option_id' => $standardSleeve->id,
            'mesh_name' => 'sleeve_buttons',
            'material_type' => 'button',
            'apply_fabric_texture' => false,
        ]);

        $this->command->info('3D Configurator seeded successfully!');
        $this->command->info('Created:');
        $this->command->info('- 3 Product Types (Jacket, Pant, Vest)');
        $this->command->info('- ' . PartCategory3D::count() . ' Part Categories');
        $this->command->info('- ' . PartOption3D::count() . ' Part Options');
        $this->command->info('- ' . PartSubOption3D::count() . ' Sub Options');
        $this->command->info('- ' . ModelMesh3D::count() . ' Mesh Definitions');
    }
}
