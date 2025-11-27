<?php

namespace Database\Seeders;

use App\Models\Fabric;
use App\Models\FabricCategory;
use App\Models\OptionType;
use App\Models\OptionValue;
use App\Models\SuitModel;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
  use WithoutModelEvents;

  /**
   * Seed the application's database.
   */
  public function run(): void
  {
  // Create admin user
  User::factory()->create([
      'name' => 'Admin',
      'email' => 'admin@suitconfigurator.vn',
      'password' => Hash::make('password'),
  ]);

  // Create test user
  User::factory()->create([
      'name' => 'Nguyễn Văn Test',
      'email' => 'test@example.com',
      'password' => Hash::make('password'),
  ]);

  $this->seedFabricCategories();
  $this->seedFabrics();
  $this->seedSuitModels();

  // Call the comprehensive OptionTypeSeeder
  $this->call(OptionTypeSeeder::class);
  }

  private function seedFabricCategories(): void
  {
  $categories = [
      [
    'name' => 'Italian Wool',
    'name_vi' => 'Vải Wool Ý',
    'slug' => 'vai-wool-y',
    'description' => 'Premium wool imported from Italy',
    'description_vi' => 'Vải wool cao cấp nhập khẩu từ Ý, độ bền cao, thoáng mát',
    'sort_order' => 1,
      ],
      [
    'name' => 'British Wool',
    'name_vi' => 'Vải Wool Anh',
    'slug' => 'vai-wool-anh',
    'description' => 'Traditional British wool, durable quality',
    'description_vi' => 'Vải wool truyền thống Anh Quốc, chất lượng bền bỉ',
    'sort_order' => 2,
      ],
      [
    'name' => 'Linen',
    'name_vi' => 'Vải Linen',
    'slug' => 'vai-linen',
    'description' => 'Natural linen, perfect for tropical climate',
    'description_vi' => 'Vải linen tự nhiên, phù hợp khí hậu nhiệt đới',
    'sort_order' => 3,
      ],
      [
    'name' => 'Cotton',
    'name_vi' => 'Vải Cotton',
    'slug' => 'vai-cotton',
    'description' => 'Premium cotton, breathable for summer',
    'description_vi' => 'Vải cotton cao cấp, thoáng mát cho mùa hè',
    'sort_order' => 4,
      ],
      [
    'name' => 'Blended Fabrics',
    'name_vi' => 'Vải Blend',
    'slug' => 'vai-blend',
    'description' => 'Mixed fabric compositions',
    'description_vi' => 'Vải pha trộn đa dạng thành phần',
    'sort_order' => 5,
      ],
  ];

  foreach ($categories as $category) {
      FabricCategory::create($category);
  }
  }

  private function seedFabrics(): void
  {
  $fabrics = [
      // Wool Ý
      ['name' => 'Navy Twill', 'name_vi' => 'Navy Twill', 'code' => 'IT-NV001', 'fabric_category_id' => 1, 'color' => '#1a237e', 'price_modifier' => 500000],
      ['name' => 'Charcoal Grey', 'name_vi' => 'Charcoal Grey', 'code' => 'IT-GR001', 'fabric_category_id' => 1, 'color' => '#424242', 'price_modifier' => 500000],
      ['name' => 'Midnight Blue', 'name_vi' => 'Midnight Blue', 'code' => 'IT-BL001', 'fabric_category_id' => 1, 'color' => '#0d47a1', 'price_modifier' => 600000],
      ['name' => 'Black Classic', 'name_vi' => 'Black Classic', 'code' => 'IT-BK001', 'fabric_category_id' => 1, 'color' => '#212121', 'price_modifier' => 450000],
      ['name' => 'Light Grey', 'name_vi' => 'Light Grey', 'code' => 'IT-LG001', 'fabric_category_id' => 1, 'color' => '#9e9e9e', 'price_modifier' => 500000],

      // Wool Anh
      ['name' => 'British Navy', 'name_vi' => 'British Navy', 'code' => 'UK-NV001', 'fabric_category_id' => 2, 'color' => '#283593', 'price_modifier' => 450000],
      ['name' => 'Oxford Grey', 'name_vi' => 'Oxford Grey', 'code' => 'UK-GR001', 'fabric_category_id' => 2, 'color' => '#546e7a', 'price_modifier' => 400000],
      ['name' => 'Pinstripe Navy', 'name_vi' => 'Pinstripe Navy', 'code' => 'UK-PS001', 'fabric_category_id' => 2, 'color' => '#1e3a5f', 'price_modifier' => 550000],
      ['name' => 'Check Brown', 'name_vi' => 'Check Brown', 'code' => 'UK-CB001', 'fabric_category_id' => 2, 'color' => '#5d4037', 'price_modifier' => 500000],

      // Linen
      ['name' => 'Natural Linen', 'name_vi' => 'Natural Linen', 'code' => 'LN-NT001', 'fabric_category_id' => 3, 'color' => '#d7ccc8', 'price_modifier' => 350000],
      ['name' => 'Navy Linen', 'name_vi' => 'Navy Linen', 'code' => 'LN-NV001', 'fabric_category_id' => 3, 'color' => '#3949ab', 'price_modifier' => 380000],
      ['name' => 'Light Blue Linen', 'name_vi' => 'Light Blue Linen', 'code' => 'LN-LB001', 'fabric_category_id' => 3, 'color' => '#64b5f6', 'price_modifier' => 350000],
      ['name' => 'Beige Linen', 'name_vi' => 'Beige Linen', 'code' => 'LN-BG001', 'fabric_category_id' => 3, 'color' => '#d7ccc8', 'price_modifier' => 320000],

      // Cotton
      ['name' => 'White Cotton', 'name_vi' => 'White Cotton', 'code' => 'CT-WT001', 'fabric_category_id' => 4, 'color' => '#fafafa', 'price_modifier' => 250000],
      ['name' => 'Navy Cotton', 'name_vi' => 'Navy Cotton', 'code' => 'CT-NV001', 'fabric_category_id' => 4, 'color' => '#303f9f', 'price_modifier' => 280000],
      ['name' => 'Khaki Cotton', 'name_vi' => 'Khaki Cotton', 'code' => 'CT-KH001', 'fabric_category_id' => 4, 'color' => '#c8b560', 'price_modifier' => 260000],
      ['name' => 'Stone Cotton', 'name_vi' => 'Stone Cotton', 'code' => 'CT-ST001', 'fabric_category_id' => 4, 'color' => '#a1887f', 'price_modifier' => 270000],

      // Blend
      ['name' => 'Wool-Silk Navy', 'name_vi' => 'Wool-Silk Navy', 'code' => 'BL-WS001', 'fabric_category_id' => 5, 'color' => '#1a237e', 'price_modifier' => 700000],
      ['name' => 'Wool-Cashmere Grey', 'name_vi' => 'Wool-Cashmere Grey', 'code' => 'BL-WC001', 'fabric_category_id' => 5, 'color' => '#616161', 'price_modifier' => 800000],
      ['name' => 'Linen-Cotton Beige', 'name_vi' => 'Linen-Cotton Beige', 'code' => 'BL-LC001', 'fabric_category_id' => 5, 'color' => '#d7ccc8', 'price_modifier' => 400000],
  ];

  foreach ($fabrics as $i => $fabric) {
      Fabric::create([
    'name' => $fabric['name'],
    'name_vi' => $fabric['name_vi'],
    'code' => $fabric['code'],
    'slug' => Str::slug($fabric['name']),
    'fabric_category_id' => $fabric['fabric_category_id'],
    'color_hex' => $fabric['color'],
    'price_modifier' => $fabric['price_modifier'],
    'material_composition' => '100% Wool',
    'weight' => rand(200, 350) . 'g/m²',
    'origin' => $fabric['fabric_category_id'] <= 2 ? 'Europe' : 'Asia',
    'is_active' => true,
    'is_featured' => $i < 6,
    'sort_order' => $i + 1,
      ]);
  }
  }

  private function seedSuitModels(): void
  {
  $models = [
      [
    'name' => 'Classic 2-Button Suit',
    'name_vi' => 'Vest 2 nút Classic',
    'slug' => 'vest-2-nut-classic',
    'description' => 'Classic style, suitable for all occasions',
    'description_vi' => 'Vest 2 nút classic là lựa chọn hoàn hảo cho phong cách chuyên nghiệp. Thiết kế tinh tế với đường cắt may chuẩn xác, tôn dáng người mặc.',
    'base_price' => 3500000,
    'is_featured' => true,
    'sort_order' => 1,
      ],
      [
    'name' => '3-Button Business Suit',
    'name_vi' => 'Vest 3 nút Business',
    'slug' => 'vest-3-nut-business',
    'description' => 'Premium business style',
    'description_vi' => 'Vest 3 nút Business mang đến vẻ ngoài lịch lãm, chuyên nghiệp. Thích hợp cho các buổi họp quan trọng và sự kiện trang trọng.',
    'base_price' => 3800000,
    'is_featured' => true,
    'sort_order' => 2,
      ],
      [
    'name' => 'Double Breasted Suit',
    'name_vi' => 'Vest Double Breasted',
    'slug' => 'vest-double-breasted',
    'description' => 'Classic elegant style',
    'description_vi' => 'Vest Double Breasted - kiểu áo 2 hàng nút mang phong cách quý tộc, thể hiện đẳng cấp và sự sang trọng của người mặc.',
    'base_price' => 4200000,
    'is_featured' => true,
    'sort_order' => 3,
      ],
      [
    'name' => 'Slim Fit Modern Suit',
    'name_vi' => 'Vest Slim Fit Modern',
    'slug' => 'vest-slim-fit-modern',
    'description' => 'Modern slim fit design',
    'description_vi' => 'Vest Slim Fit với thiết kế ôm sát, tôn dáng người mặc. Phong cách trẻ trung, năng động, phù hợp cho giới trẻ.',
    'base_price' => 3600000,
    'is_featured' => true,
    'sort_order' => 4,
      ],
      [
    'name' => 'Classic Tuxedo',
    'name_vi' => 'Tuxedo Cổ Điển',
    'slug' => 'tuxedo-co-dien',
    'description' => 'For gala and wedding',
    'description_vi' => 'Tuxedo cổ điển với ve áo satin bóng, hoàn hảo cho các buổi dạ tiệc, lễ cưới và sự kiện trang trọng.',
    'base_price' => 5500000,
    'is_featured' => false,
    'sort_order' => 5,
      ],
  ];

  foreach ($models as $model) {
      SuitModel::create($model);
  }
  }

}
