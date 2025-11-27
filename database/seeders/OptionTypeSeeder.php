<?php

namespace Database\Seeders;

use App\Models\OptionType;
use App\Models\OptionValue;
use Illuminate\Database\Seeder;

class OptionTypeSeeder extends Seeder
{
  /**
   * Seed all option types and values based on vestonduynguyen.com reference
   * Full config menu structure with icon fonts
   */
  public function run(): void
  {
  // Clear existing data
  OptionValue::query()->delete();
  OptionType::query()->delete();

  $optionTypes = [
      // ==================== VEST (JACKET) OPTIONS ====================

      // 1. CHỌN BỘ - Suit Type
      [
    'name' => 'Suit Type',
    'name_vi' => 'Chọn Bộ',
    'slug' => 'suit_type',
    'description' => 'Choose suit combination',
    'description_vi' => 'Chọn bộ vest',
    'icon' => 'L', // Font icon
    'type' => 'radio',
    'is_required' => true,
    'sort_order' => 1,
    'values' => [
          ['name' => 'Jacket + Pants', 'name_vi' => 'Vest + Quần', 'slug' => 'jacket_pants', 'icon' => 'L', 'is_default' => true, 'price_modifier' => 0, 'sort_order' => 1],
          ['name' => 'Add Waistcoat', 'name_vi' => 'Thêm Ghi Lê', 'slug' => 'with_waistcoat', 'icon' => 'K', 'is_default' => false, 'price_modifier' => 800000, 'sort_order' => 2],
    ],
      ],

      // 2. KIỂU VEST - Jacket Style
      [
    'name' => 'Jacket Style',
    'name_vi' => 'Kiểu Vest',
    'slug' => 'jacket_style',
    'description' => 'Choose jacket style',
    'description_vi' => 'Chọn kiểu dáng vest',
    'icon' => 'a', // Font icon
    'type' => 'radio',
    'is_required' => true,
    'sort_order' => 2,
    'values' => [
          ['name' => 'Single Breasted', 'name_vi' => 'Một Hàng', 'slug' => 'single_breasted', 'icon' => 'a', 'is_default' => true, 'price_modifier' => 0, 'sort_order' => 1],
          ['name' => 'Double Breasted', 'name_vi' => 'Hai Hàng', 'slug' => 'double_breasted', 'icon' => 'f', 'is_default' => false, 'price_modifier' => 500000, 'sort_order' => 2],
          ['name' => 'Mandarin Collar', 'name_vi' => 'Châu Á', 'slug' => 'mandarin', 'icon' => 'g', 'is_default' => false, 'price_modifier' => 300000, 'sort_order' => 3],
    ],
      ],

      // 3. DÁNG VEST - Jacket Fit
      [
    'name' => 'Jacket Fit',
    'name_vi' => 'Dáng Vest',
    'slug' => 'jacket_fit',
    'description' => 'Choose jacket fit',
    'description_vi' => 'Chọn dáng vest',
    'icon' => 'b', // Font icon
    'type' => 'radio',
    'is_required' => true,
    'sort_order' => 3,
    'values' => [
          ['name' => 'Classic', 'name_vi' => 'Cổ Điển', 'slug' => 'classic_fit', 'icon' => 'a', 'is_default' => false, 'price_modifier' => 0, 'sort_order' => 1],
          ['name' => 'Slim Fit', 'name_vi' => 'Vừa Vặn', 'slug' => 'slim_fit', 'icon' => 'b', 'is_default' => true, 'price_modifier' => 0, 'sort_order' => 2],
    ],
      ],

      // 4. VE ÁO VEST - Lapel Type
      [
    'name' => 'Lapel Type',
    'name_vi' => 'Ve Áo Vest',
    'slug' => 'jacket_lapel_type',
    'description' => 'Choose lapel style',
    'description_vi' => 'Chọn kiểu ve áo',
    'icon' => 'c', // Font icon
    'type' => 'radio',
    'is_required' => true,
    'sort_order' => 4,
    'values' => [
          ['name' => 'Notch Lapel', 'name_vi' => 'Ve Xuôi', 'slug' => 'notch_lapel', 'icon' => 'c', 'is_default' => true, 'price_modifier' => 0, 'sort_order' => 1],
          ['name' => 'Peak Lapel', 'name_vi' => 'Ve Vếch', 'slug' => 'peak_lapel', 'icon' => 'd', 'is_default' => false, 'price_modifier' => 0, 'sort_order' => 2],
    ],
      ],

      // 5. NÚT ÁO KHOÁC - Jacket Buttons
      [
    'name' => 'Jacket Buttons',
    'name_vi' => 'Nút Áo Khoác',
    'slug' => 'jacket_buttons',
    'description' => 'Number of buttons',
    'description_vi' => 'Số lượng nút áo',
    'icon' => 'C', // Font icon
    'type' => 'radio',
    'is_required' => true,
    'sort_order' => 5,
    'values' => [
          ['name' => '1 Button', 'name_vi' => '1 Nút', 'slug' => '1_button', 'icon' => 'C', 'is_default' => false, 'price_modifier' => 0, 'sort_order' => 1],
          ['name' => '2 Buttons', 'name_vi' => '2 Nút', 'slug' => '2_buttons', 'icon' => 'C', 'is_default' => true, 'price_modifier' => 0, 'sort_order' => 2],
          ['name' => '3 Buttons', 'name_vi' => '3 Nút', 'slug' => '3_buttons', 'icon' => 'C', 'is_default' => false, 'price_modifier' => 0, 'sort_order' => 3],
          ['name' => '4 Buttons', 'name_vi' => '4 Nút', 'slug' => '4_buttons', 'icon' => 'C', 'is_default' => false, 'price_modifier' => 0, 'sort_order' => 4],
          ['name' => '5 Buttons', 'name_vi' => '5 Nút', 'slug' => '5_buttons', 'icon' => 'C', 'is_default' => false, 'price_modifier' => 0, 'sort_order' => 5],
    ],
      ],

      // 6. TÚI NGỰC - Breast Pocket
      [
    'name' => 'Breast Pocket',
    'name_vi' => 'Túi Ngực',
    'slug' => 'breast_pocket',
    'description' => 'Breast pocket option',
    'description_vi' => 'Túi ngực áo vest',
    'icon' => 'l', // Font icon
    'type' => 'radio',
    'is_required' => true,
    'sort_order' => 6,
    'values' => [
          ['name' => 'With Pocket', 'name_vi' => 'Có', 'slug' => 'with_breast_pocket', 'icon' => 'l', 'is_default' => true, 'price_modifier' => 0, 'sort_order' => 1],
          ['name' => 'Without Pocket', 'name_vi' => 'Không', 'slug' => 'no_breast_pocket', 'icon' => 'Z', 'is_default' => false, 'price_modifier' => 0, 'sort_order' => 2],
    ],
      ],

      // 7. TÚI HÔNG - Hip Pockets
      [
    'name' => 'Hip Pockets',
    'name_vi' => 'Túi Hông',
    'slug' => 'hip_pockets',
    'description' => 'Hip pockets style',
    'description_vi' => 'Kiểu túi hông',
    'icon' => 'l', // Font icon
    'type' => 'radio',
    'is_required' => true,
    'sort_order' => 7,
    'values' => [
          ['name' => 'No Pockets', 'name_vi' => 'Không Túi', 'slug' => 'no_hip_pockets', 'icon' => 'Z', 'is_default' => false, 'price_modifier' => 0, 'sort_order' => 1],
          ['name' => '2 Welt Pockets', 'name_vi' => '2 Túi Viền', 'slug' => '2_welt_pockets', 'icon' => 'l', 'is_default' => true, 'price_modifier' => 0, 'sort_order' => 2],
          ['name' => '3 Welt Pockets', 'name_vi' => '3 Túi Viền', 'slug' => '3_welt_pockets', 'icon' => 'm', 'is_default' => false, 'price_modifier' => 0, 'sort_order' => 3],
    ],
      ],

      // 8. KIỂU TÚI HÔNG - Hip Pocket Style
      [
    'name' => 'Hip Pocket Style',
    'name_vi' => 'Kiểu Túi Hông',
    'slug' => 'hip_pocket_style',
    'description' => 'Hip pocket style details',
    'description_vi' => 'Chi tiết kiểu túi hông',
    'icon' => 'l', // Font icon
    'type' => 'radio',
    'is_required' => false,
    'sort_order' => 8,
    'values' => [
          ['name' => 'Welt with Flap', 'name_vi' => 'Viền Có Nắp', 'slug' => 'welt_with_flap', 'icon' => 'l', 'is_default' => true, 'price_modifier' => 0, 'sort_order' => 1],
          ['name' => 'Welt without Flap', 'name_vi' => 'Viền Không Nắp', 'slug' => 'welt_no_flap', 'icon' => 'm', 'is_default' => false, 'price_modifier' => 0, 'sort_order' => 2],
          ['name' => 'Patch Pockets', 'name_vi' => 'Túi Ốp Ngoài', 'slug' => 'patch_pockets', 'icon' => 'n', 'is_default' => false, 'price_modifier' => 0, 'sort_order' => 3],
    ],
      ],

      // 9. THÂN SAU - Back Vent
      [
    'name' => 'Back Vent',
    'name_vi' => 'Thân Sau',
    'slug' => 'jacket_vent',
    'description' => 'Back vent style',
    'description_vi' => 'Kiểu sẻ thân sau',
    'icon' => 'p', // Font icon
    'type' => 'radio',
    'is_required' => true,
    'sort_order' => 9,
    'values' => [
          ['name' => 'No Vent', 'name_vi' => 'Áo Không Sẻ', 'slug' => 'no_vent', 'icon' => 'o', 'is_default' => false, 'price_modifier' => 0, 'sort_order' => 1],
          ['name' => 'Center Vent', 'name_vi' => 'Sẻ Sau Lưng', 'slug' => 'center_vent', 'icon' => 'p', 'is_default' => true, 'price_modifier' => 0, 'sort_order' => 2],
          ['name' => 'Side Vents', 'name_vi' => 'Sẻ Sườn Áo', 'slug' => 'side_vents', 'icon' => 'q', 'is_default' => false, 'price_modifier' => 0, 'sort_order' => 3],
    ],
      ],

      // 10. CÚC TAY ÁO - Sleeve Buttons
      [
    'name' => 'Sleeve Buttons',
    'name_vi' => 'Cúc Tay Áo',
    'slug' => 'jacket_sleeve_buttons',
    'description' => 'Number of sleeve buttons',
    'description_vi' => 'Số cúc tay áo',
    'icon' => 'j', // Font icon
    'type' => 'radio',
    'is_required' => true,
    'sort_order' => 10,
    'values' => [
          ['name' => 'No Buttons', 'name_vi' => 'Không Cúc', 'slug' => 'no_sleeve_buttons', 'icon' => 'C', 'is_default' => false, 'price_modifier' => 0, 'sort_order' => 1],
          ['name' => '2 Buttons', 'name_vi' => '2 Cúc', 'slug' => '2_sleeve_buttons', 'icon' => 'C', 'is_default' => true, 'price_modifier' => 0, 'sort_order' => 2],
          ['name' => '3 Buttons', 'name_vi' => '3 Cúc', 'slug' => '3_sleeve_buttons', 'icon' => 'C', 'is_default' => false, 'price_modifier' => 0, 'sort_order' => 3],
          ['name' => '4 Buttons', 'name_vi' => '4 Cúc', 'slug' => '4_sleeve_buttons', 'icon' => 'C', 'is_default' => false, 'price_modifier' => 0, 'sort_order' => 4],
    ],
      ],

      // 11. KIỂU SẺ TAY ÁO - Sleeve Vent Style
      [
    'name' => 'Sleeve Vent Style',
    'name_vi' => 'Kiểu Sẻ Tay Áo',
    'slug' => 'sleeve_vent_style',
    'description' => 'Sleeve vent style',
    'description_vi' => 'Kiểu sẻ tay áo',
    'icon' => 'A', // Font icon
    'type' => 'radio',
    'is_required' => false,
    'sort_order' => 11,
    'values' => [
          ['name' => 'Functional', 'name_vi' => 'Tay Áo Sẻ Thật', 'slug' => 'functional_sleeve', 'icon' => 'A', 'is_default' => false, 'price_modifier' => 200000, 'sort_order' => 1],
          ['name' => 'Non-functional', 'name_vi' => 'Tay Kín', 'slug' => 'non_functional_sleeve', 'icon' => 'i', 'is_default' => true, 'price_modifier' => 0, 'sort_order' => 2],
    ],
      ],

      // ==================== QUẦN (PANTS) OPTIONS ====================

      // 12. DÁNG QUẦN - Pants Fit
      [
    'name' => 'Pants Fit',
    'name_vi' => 'Dáng Quần',
    'slug' => 'pants_fit',
    'description' => 'Pants fit style',
    'description_vi' => 'Dáng quần',
    'icon' => 'b', // Font icon (man-pants)
    'type' => 'radio',
    'is_required' => true,
    'sort_order' => 12,
    'font_family' => 'man-pants',
    'values' => [
          ['name' => 'Classic', 'name_vi' => 'Cổ Điển', 'slug' => 'classic_pants', 'icon' => 'a', 'is_default' => false, 'price_modifier' => 0, 'sort_order' => 1],
          ['name' => 'Slim Fit', 'name_vi' => 'Slim Fit', 'slug' => 'slim_pants', 'icon' => 'b', 'is_default' => true, 'price_modifier' => 0, 'sort_order' => 2],
    ],
      ],

      // 13. KIỂU QUẦN - Pants Pleats
      [
    'name' => 'Pants Pleats',
    'name_vi' => 'Thân Trước Quần',
    'slug' => 'pants_peg',
    'description' => 'Pants pleat style',
    'description_vi' => 'Kiểu ly quần',
    'icon' => 's', // Font icon (man-pants)
    'type' => 'radio',
    'is_required' => true,
    'sort_order' => 13,
    'font_family' => 'man-pants',
    'values' => [
          ['name' => 'No Pleats', 'name_vi' => 'Không Ly', 'slug' => 'no_pleats', 'icon' => 's', 'is_default' => true, 'price_modifier' => 0, 'sort_order' => 1],
          ['name' => '1 Pleat', 'name_vi' => '1 Ly Thân Trước', 'slug' => '1_pleat', 'icon' => 'c', 'is_default' => false, 'price_modifier' => 0, 'sort_order' => 2],
          ['name' => '2 Pleats', 'name_vi' => '2 Ly Thân Trước', 'slug' => '2_pleats', 'icon' => 'd', 'is_default' => false, 'price_modifier' => 0, 'sort_order' => 3],
    ],
      ],

      // 14. TÚI THÂN TRƯỚC - Front Pockets
      [
    'name' => 'Front Pockets',
    'name_vi' => 'Túi Thân Trước',
    'slug' => 'pants_front_pocket',
    'description' => 'Front pocket style',
    'description_vi' => 'Kiểu túi thân trước',
    'icon' => 'e', // Font icon (man-pants)
    'type' => 'radio',
    'is_required' => true,
    'sort_order' => 14,
    'font_family' => 'man-pants',
    'values' => [
          ['name' => 'Slant Pockets', 'name_vi' => 'Túi Chéo', 'slug' => 'slant_pockets', 'icon' => 'e', 'is_default' => true, 'price_modifier' => 0, 'sort_order' => 1],
          ['name' => 'Vertical Pockets', 'name_vi' => 'Túi Dọc', 'slug' => 'vertical_pockets', 'icon' => 'g', 'is_default' => false, 'price_modifier' => 0, 'sort_order' => 2],
          ['name' => 'Frogmouth Pockets', 'name_vi' => 'Túi Hàm Ếch', 'slug' => 'frogmouth_pockets', 'icon' => 'f', 'is_default' => false, 'price_modifier' => 0, 'sort_order' => 3],
    ],
      ],

      // 15. TÚI THÂN SAU - Back Pockets
      [
    'name' => 'Back Pockets',
    'name_vi' => 'Túi Thân Sau',
    'slug' => 'pants_back_pocket',
    'description' => 'Back pocket style',
    'description_vi' => 'Kiểu túi thân sau quần',
    'icon' => 'o', // Font icon (man-pants)
    'type' => 'radio',
    'is_required' => true,
    'sort_order' => 15,
    'font_family' => 'man-pants',
    'values' => [
          ['name' => 'No Back Pockets', 'name_vi' => 'Không Túi Sau', 'slug' => 'no_back_pockets', 'icon' => 't', 'is_default' => false, 'price_modifier' => 0, 'sort_order' => 1],
          ['name' => '1 Welt Pocket', 'name_vi' => '1 Túi Viền', 'slug' => '1_back_pocket', 'icon' => 'n', 'is_default' => true, 'price_modifier' => 0, 'sort_order' => 2],
          ['name' => '2 Welt Pockets', 'name_vi' => '2 Túi Viền', 'slug' => '2_back_pockets', 'icon' => 'h', 'is_default' => false, 'price_modifier' => 0, 'sort_order' => 3],
    ],
      ],

      // 16. KIỂU TÚI SAU - Back Pocket Style
      [
    'name' => 'Back Pocket Style',
    'name_vi' => 'Kiểu Túi Sau',
    'slug' => 'back_pocket_style',
    'description' => 'Back pocket style details',
    'description_vi' => 'Chi tiết kiểu túi sau',
    'icon' => 'o', // Font icon (man-pants)
    'type' => 'radio',
    'is_required' => false,
    'sort_order' => 16,
    'font_family' => 'man-pants',
    'values' => [
          ['name' => 'Standard Welt', 'name_vi' => 'Túi Mặc Định', 'slug' => 'standard_back_pocket', 'icon' => 'o', 'is_default' => true, 'price_modifier' => 0, 'sort_order' => 1],
          ['name' => 'Patch Pockets', 'name_vi' => 'Túi Ốp Ngoài', 'slug' => 'patch_back_pocket', 'icon' => 'p', 'is_default' => false, 'price_modifier' => 0, 'sort_order' => 2],
          ['name' => 'Welt with Flap', 'name_vi' => 'Túi Có Nắp', 'slug' => 'flap_back_pocket', 'icon' => 'n', 'is_default' => false, 'price_modifier' => 0, 'sort_order' => 3],
    ],
      ],

      // 17. GẤU QUẦN - Pants Cuff
      [
    'name' => 'Pants Cuff',
    'name_vi' => 'Gấu Quần',
    'slug' => 'pants_cuff',
    'description' => 'Pants cuff style',
    'description_vi' => 'Kiểu gấu quần',
    'icon' => 'm', // Font icon (man-pants)
    'type' => 'radio',
    'is_required' => true,
    'sort_order' => 17,
    'font_family' => 'man-pants',
    'values' => [
          ['name' => 'Standard Hem', 'name_vi' => 'Gấu Mặc Định', 'slug' => 'standard_hem', 'icon' => 'm', 'is_default' => true, 'price_modifier' => 0, 'sort_order' => 1],
          ['name' => 'Turn-up Cuff', 'name_vi' => 'Gấu Gập LV', 'slug' => 'turnup_cuff', 'icon' => 'k', 'is_default' => false, 'price_modifier' => 50000, 'sort_order' => 2],
    ],
      ],
  ];

  foreach ($optionTypes as $typeData) {
      $values = $typeData['values'] ?? [];
      unset($typeData['values']);

      $optionType = OptionType::create([
    'name' => $typeData['name'],
    'name_vi' => $typeData['name_vi'],
    'slug' => $typeData['slug'],
    'description' => $typeData['description'] ?? null,
    'description_vi' => $typeData['description_vi'] ?? null,
    'icon' => $typeData['icon'] ?? null,
    'type' => $typeData['type'] ?? 'radio',
    'is_required' => $typeData['is_required'] ?? true,
    'is_active' => true,
    'sort_order' => $typeData['sort_order'] ?? 0,
      ]);

      foreach ($values as $valueData) {
    OptionValue::create([
          'option_type_id' => $optionType->id,
          'name' => $valueData['name'],
          'name_vi' => $valueData['name_vi'],
          'slug' => $valueData['slug'],
          'preview_image' => $valueData['preview_image'] ?? null,
          'price_modifier' => $valueData['price_modifier'] ?? 0,
          'layer_key' => $valueData['layer_key'] ?? null,
          'is_default' => $valueData['is_default'] ?? false,
          'is_active' => true,
          'sort_order' => $valueData['sort_order'] ?? 0,
    ]);
      }
  }

  $this->command->info('Option types and values seeded successfully!');
  $this->command->info('Total option types: ' . OptionType::count());
  $this->command->info('Total option values: ' . OptionValue::count());
  }
}
