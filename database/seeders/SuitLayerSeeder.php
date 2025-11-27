<?php

namespace Database\Seeders;

use App\Models\Fabric;
use App\Models\OptionType;
use App\Models\OptionValue;
use App\Models\SuitLayer;
use App\Models\SuitModel;
use Illuminate\Database\Seeder;

class SuitLayerSeeder extends Seeder
{
  /**
   * Base URL for images from vestonduynguyen.com
   */
  private string $baseUrl = 'https://www.vestonduynguyen.com/sites/default/files';

  /**
   * 3D configurator path
   */
  private string $appUrl = 'https://www.vestonduynguyen.com/sites/default/files/application/3d/man';

  /**
   * Real fabric codes from vestonduynguyen.com with names
   */
  private array $realFabricCodes = [
  ['code' => '102', 'name' => 'Wailua'],
  ['code' => '1041', 'name' => 'Maracaibo'],
  ['code' => '1043', 'name' => 'Blue Rain'],
  ['code' => '1046', 'name' => 'Virton'],
  ['code' => '1048', 'name' => 'Bernai'],
  ['code' => '1049', 'name' => 'Foscani'],
  ['code' => '1050', 'name' => 'Hagi'],
  ['code' => '1064', 'name' => 'Sandy'],
  ['code' => '1081', 'name' => 'Tamarindo'],
  ['code' => '1082', 'name' => 'Gaeta'],
  ['code' => '1083', 'name' => 'Dalmacia'],
  ['code' => '1084', 'name' => 'Noli'],
  ['code' => '1085', 'name' => 'Sierpe'],
  ['code' => '1088', 'name' => 'Caracas'],
  ['code' => '1114', 'name' => 'Moyross'],
  ['code' => '1115', 'name' => 'Carrick'],
  ['code' => '1117', 'name' => 'Hinton'],
  ['code' => '1119', 'name' => 'Xerox'],
  ['code' => '1120', 'name' => 'Larbert'],
  ['code' => '1217', 'name' => 'Yves Klein Light'],
  ];

  /**
   * Jacket styles configuration - extracted from vestonduynguyen.com
   * Each style has different corpus, lapel, collar images
   */
  private array $jacketStyles = [
  'simple' => [
      'corpus' => 'classica_mediana_corpus_estrecho',
      'collar' => 'classica_cuello_arriba',
      'lapels' => [
    'standard' => 'classica_mediana_solapa_estandar',
    'peak' => 'classica_mediana_solapa_pico',
      ],
      'buttons' => [
    1 => '6_simple_cuerpo_medium_1',
    2 => '6_simple_cuerpo_medium_2',
    3 => '6_simple_cuerpo_medium_3',
      ],
  ],
  'crossed' => [
      'corpus' => 'cruzada_larga_corpus_estrecho',
      'collar' => 'cruzada_cuello_arriba',
      'lapels' => [
    'standard' => 'cruzada_larga_solapa_estandar',
    'peak' => 'cruzada_larga_solapa_pico',
      ],
      'buttons' => [
    2 => '6_crossed_cuerpo_long_2',
    4 => '6_crossed_cuerpo_long_4',
    6 => '6_crossed_cuerpo_long_6',
      ],
  ],
  'mao' => [
      'corpus' => 'mao_corpus_estrecho',
      'collar' => null, // Mao style has no lapel
      'lapels' => [],
      'buttons' => [
    5 => '6_simple_cuerpo_medium_5',
      ],
  ],
  ];

  /**
   * Run the database seeds.
   */
  public function run(): void
  {
  // Clear existing layers
  SuitLayer::truncate();

  // Update fabrics with real codes and thumbnails
  $this->updateFabricsWithRealCodes();

  // Update/Create suit models with different styles
  $this->updateSuitModels();

  // Update option values with images
  $this->updateOptionImages();

  // Seed layers for each combination of suit model + fabric
  $suitModels = SuitModel::all();
  $fabrics = Fabric::all();

  foreach ($suitModels as $suitModel) {
      foreach ($fabrics as $fabric) {
    $this->seedLayersForModelAndFabric($suitModel, $fabric);
      }
      // Seed shared layers once per model
      $this->seedSharedLayers($suitModel);
  }

  $this->command->info('Suit layers seeded successfully with real images from vestonduynguyen.com!');
  $this->command->info('Each suit model now has its own unique style layers!');
  }

  /**
   * Update fabrics with real codes and thumbnails
   */
  private function updateFabricsWithRealCodes(): void
  {
  $fabricThumbnailBase = 'https://www.vestonduynguyen.com/sites/default/files/application/dimg/fabric/suit';

  $allFabrics = Fabric::all();

  foreach ($allFabrics as $index => $fabric) {
      $realFabric = $this->realFabricCodes[$index % count($this->realFabricCodes)];

      $fabric->update([
    'code' => $realFabric['code'],
    'thumbnail' => "{$fabricThumbnailBase}/{$realFabric['code']}_normal.jpg",
      ]);
  }

  $this->command->info('Fabrics updated with real codes!');
  }

  /**
   * Update suit models with different styles and thumbnails
   * Each model represents a different jacket style
   */
  private function updateSuitModels(): void
  {
  // Using different fabric codes for visual distinction in thumbnails
  // Each model uses a unique look with different lapel/button/fabric combo
  $models = [
      [
    'name_vi' => 'Vest 2 nút Classic',
    'name_en' => '2-Button Classic Suit',
    'style' => 'simple',
    'button_count' => 2,
    'lapel_type' => 'standard',
    // Dark blue fabric (1217) with standard lapel
    'thumbnail' => "{$this->appUrl}/jacket/1217_fabric/front/classica_mediana_corpus_estrecho.1.png",
      ],
      [
    'name_vi' => 'Vest 3 nút Business',
    'name_en' => '3-Button Business Suit',
    'style' => 'simple',
    'button_count' => 3,
    'lapel_type' => 'standard',
    // Light gray fabric (1048) for different look
    'thumbnail' => "{$this->appUrl}/jacket/1048_fabric/front/classica_mediana_corpus_estrecho.1.png",
      ],
      [
    'name_vi' => 'Vest Double Breasted',
    'name_en' => 'Double Breasted Suit',
    'style' => 'crossed',
    'button_count' => 4,
    'lapel_type' => 'peak',
    // Black fabric (102) - distinctive crossed style
    'thumbnail' => "{$this->appUrl}/jacket/102_fabric/front/cruzada_larga_corpus_estrecho.1.png",
      ],
      [
    'name_vi' => 'Vest Slim Fit Modern',
    'name_en' => 'Slim Fit Modern Suit',
    'style' => 'simple',
    'button_count' => 2,
    'lapel_type' => 'peak',
    // Dark fabric (1041) with peak lapel for modern look
    'thumbnail' => "{$this->appUrl}/jacket/1041_fabric/front/classica_mediana_corpus_estrecho.1.png",
      ],
      [
    'name_vi' => 'Tuxedo Cổ Điển',
    'name_en' => 'Classic Tuxedo',
    'style' => 'simple',
    'button_count' => 1,
    'lapel_type' => 'peak',
    // Elegant brown/tan fabric (1081) for tuxedo distinction
    'thumbnail' => "{$this->appUrl}/jacket/1081_fabric/front/classica_mediana_corpus_estrecho.1.png",
      ],
  ];

  foreach ($models as $modelData) {
      $model = SuitModel::where('name_vi', $modelData['name_vi'])->first();
      if ($model) {
    $model->update([
          'thumbnail' => $modelData['thumbnail'],
          'style' => $modelData['style'],
          'button_count' => $modelData['button_count'],
          'lapel_type' => $modelData['lapel_type'],
    ]);
      }
  }

  $this->command->info('Suit models updated with different styles and unique thumbnails!');
  }

  /**
   * Update option value images
   */
  private function updateOptionImages(): void
  {
  $optionImages = [
      // Lapel styles
      'Ve Nhọn (Notch)' => "{$this->appUrl}/jacket/1217_fabric/front/classica_mediana_solapa_estandar.1.png",
      'Ve Nhọn Sâu (Peak)' => "{$this->appUrl}/jacket/1217_fabric/front/classica_mediana_solapa_pico.1.png",
      'Ve Tròn (Shawl)' => "{$this->appUrl}/jacket/1217_fabric/front/classica_mediana_solapa_estandar.1.png",

      // Pocket styles
      'Túi Flap (Nắp)' => "{$this->appUrl}/jacket/1217_fabric/front/bolsillos_con_solapa_abajo_der_estrecha.1.png",
      'Túi Jetted (Không nắp)' => "{$this->appUrl}/jacket/1217_fabric/front/bolsillos_sin_solapa_abajo_der_estrecha.1.png",
      'Túi Patch' => "{$this->appUrl}/jacket/1217_fabric/front/bolsillos_con_solapa_abajo_der_estrecha.1.png",
      'Túi Ticket' => "{$this->appUrl}/jacket/1217_fabric/front/bolsillo_pecho.1.png",

      // Button count
      '1 nút' => "{$this->appUrl}/jacket/botones/6_simple_cuerpo_medium_1.png",
      '2 nút' => "{$this->appUrl}/jacket/botones/6_simple_cuerpo_medium_2.png",
      '3 nút' => "{$this->appUrl}/jacket/botones/6_simple_cuerpo_medium_3.png",
      'Double Breasted' => "{$this->appUrl}/jacket/botones/6_crossed_cuerpo_long_4.png",

      // Vent styles
      'Không xẻ' => "{$this->appUrl}/jacket/1217_fabric/back/espalda_0_cortes_estrecha.2.png",
      'Xẻ giữa (Center)' => "{$this->appUrl}/jacket/1217_fabric/back/espalda_1_corte_estrecha.2.png",
      'Xẻ 2 bên (Side)' => "{$this->appUrl}/jacket/1217_fabric/back/espalda_2_cortes_estrecha.2.png",

      // Fit styles
      'Regular Fit' => "{$this->appUrl}/jacket/1217_fabric/front/classica_mediana_corpus_estrecho.1.png",
      'Slim Fit' => "{$this->appUrl}/jacket/1217_fabric/front/classica_mediana_corpus_estrecho.1.png",
      'Tailored Fit' => "{$this->appUrl}/jacket/1217_fabric/front/classica_mediana_corpus_estrecho.1.png",

      // Pants pleat
      'Không ly (Flat Front)' => "{$this->appUrl}/pants/1217_fabric/front/pantalon_deportivo.1.png",
      '1 ly (Single Pleat)' => "{$this->appUrl}/pants/1217_fabric/front/pantalon_pinza_1.1.png",
      '2 ly (Double Pleat)' => "{$this->appUrl}/pants/1217_fabric/front/pantalon_pinza_2.1.png",
  ];

  foreach ($optionImages as $name => $imageUrl) {
      OptionValue::where('name_vi', $name)->update(['preview_image' => $imageUrl]);
  }

  $this->command->info('Option images updated!');
  }

  /**
   * Seed layers for a specific suit model and fabric combination
   */
  private function seedLayersForModelAndFabric(SuitModel $suitModel, Fabric $fabric): void
  {
  $fabricCode = $fabric->code;
  $style = $suitModel->style ?? 'simple';
  $buttonCount = $suitModel->button_count ?? 2;
  $lapelType = $suitModel->lapel_type ?? 'standard';

  $jacketPath = "{$this->appUrl}/jacket/{$fabricCode}_fabric";
  $pantsPath = "{$this->appUrl}/pants/{$fabricCode}_fabric";
  $waistcoatPath = "{$this->appUrl}/waistcoat/{$fabricCode}_fabric";

  $styleConfig = $this->jacketStyles[$style] ?? $this->jacketStyles['simple'];

  // ============== FRONT VIEW LAYERS ==============
  $frontLayers = [];

  // Jacket body - different for each style
  $frontLayers[] = [
      'part' => 'jacket_body',
      'image' => "{$jacketPath}/front/{$styleConfig['corpus']}.1.png",
      'z_index' => 3050,
  ];

  // Back panel
  $frontLayers[] = [
      'part' => 'jacket_back_panel',
      'image' => "{$jacketPath}/front/espalda_0_cortes_estrecha.1.png",
      'z_index' => 3100,
  ];

  // Breast pocket
  $frontLayers[] = [
      'part' => 'breast_pocket',
      'image' => "{$jacketPath}/front/bolsillo_pecho.1.png",
      'z_index' => 3200,
  ];

  // Lapel - different for each style and lapel type
  if (!empty($styleConfig['lapels'])) {
      $lapelImage = $styleConfig['lapels'][$lapelType] ?? $styleConfig['lapels']['standard'] ?? null;
      if ($lapelImage) {
    $frontLayers[] = [
          'part' => 'lapel',
          'image' => "{$jacketPath}/front/{$lapelImage}.1.png",
          'z_index' => 3300,
    ];
      }
  }

  // Collar - different for each style
  if ($styleConfig['collar']) {
      $frontLayers[] = [
    'part' => 'collar',
    'image' => "{$jacketPath}/front/{$styleConfig['collar']}.1.png",
    'z_index' => 3350,
      ];
  }

  // Pockets (same for all styles)
  $frontLayers[] = [
      'part' => 'pocket_right',
      'image' => "{$jacketPath}/front/bolsillos_con_solapa_abajo_der_estrecha.1.png",
      'z_index' => 3600,
  ];
  $frontLayers[] = [
      'part' => 'pocket_left',
      'image' => "{$jacketPath}/front/bolsillos_con_solapa_abajo_izq.1.png",
      'z_index' => 3600,
  ];

  // Sleeves
  $frontLayers[] = [
      'part' => 'sleeve_right_inner',
      'image' => "{$jacketPath}/front/brazo_der_forro_sin_botones.1.png",
      'z_index' => 2050,
  ];
  $frontLayers[] = [
      'part' => 'sleeve_right',
      'image' => "{$jacketPath}/front/brazo_der_exterior.1.png",
      'z_index' => 2100,
  ];
  $frontLayers[] = [
      'part' => 'sleeve_left_inner',
      'image' => "{$jacketPath}/front/brazo_izq_botones_forro.1.png",
      'z_index' => 3700,
  ];
  $frontLayers[] = [
      'part' => 'sleeve_left_buttons',
      'image' => "{$jacketPath}/front/brazo_izq_botones.1.png",
      'z_index' => 3750,
  ];

  foreach ($frontLayers as $layer) {
      SuitLayer::create([
    'suit_model_id' => $suitModel->id,
    'fabric_id' => $fabric->id,
    'view' => 'front',
    'part' => $layer['part'],
    'option_value_slug' => null,
    'image_path' => $layer['image'],
    'z_index' => $layer['z_index'],
    'is_active' => true,
      ]);
  }

  // Pants front layers
  $this->seedPantsLayers($suitModel, $fabric, $pantsPath, 'front');

  // Waistcoat front layers
  $this->seedWaistcoatLayers($suitModel, $fabric, $waistcoatPath, 'front');

  // ============== BACK VIEW LAYERS ==============
  $backLayers = [
      ['part' => 'jacket_back_body', 'image' => "{$jacketPath}/back/espalda_1_corte_estrecha.2.png", 'z_index' => 3150],
      ['part' => 'jacket_back_collar', 'image' => "{$jacketPath}/back/classica_cuello_arriba.2.png", 'z_index' => 3550],
      ['part' => 'sleeve_back_right', 'image' => "{$jacketPath}/back/brazo_der_exterior.2.png", 'z_index' => 2100],
      ['part' => 'sleeve_back_left', 'image' => "{$jacketPath}/back/brazo_izq_sin_botones.2.png", 'z_index' => 3750],
  ];

  foreach ($backLayers as $layer) {
      SuitLayer::create([
    'suit_model_id' => $suitModel->id,
    'fabric_id' => $fabric->id,
    'view' => 'back',
    'part' => $layer['part'],
    'option_value_slug' => null,
    'image_path' => $layer['image'],
    'z_index' => $layer['z_index'],
    'is_active' => true,
      ]);
  }

  // Pants back layers
  $this->seedPantsLayers($suitModel, $fabric, $pantsPath, 'back');

  // Waistcoat back layers
  $this->seedWaistcoatLayers($suitModel, $fabric, $waistcoatPath, 'back');
  }

  /**
   * Seed pants layers
   */
  private function seedPantsLayers(SuitModel $suitModel, Fabric $fabric, string $pantsPath, string $view): void
  {
  if ($view === 'front') {
      $layers = [
    ['part' => 'pants_body', 'image' => "{$pantsPath}/front/pantalon_deportivo.1.png", 'z_index' => 200],
    ['part' => 'pants_belt', 'image' => "{$pantsPath}/front/cinturon_flecha.1.png", 'z_index' => 400],
    ['part' => 'pants_pocket', 'image' => "{$pantsPath}/front/bolsillo_diagonal.1.png", 'z_index' => 700],
      ];
  } else {
      $layers = [
    ['part' => 'pants_back_body', 'image' => "{$pantsPath}/back/pantalon_deportivo.2.png", 'z_index' => 200],
    ['part' => 'pants_back_belt', 'image' => "{$pantsPath}/back/cinturon_cuadrado.2.png", 'z_index' => 400],
    ['part' => 'pants_back_pocket', 'image' => "{$pantsPath}/back/bol_der_traseros_sin_tapa.2.png", 'z_index' => 800],
      ];
  }

  foreach ($layers as $layer) {
      SuitLayer::create([
    'suit_model_id' => $suitModel->id,
    'fabric_id' => $fabric->id,
    'view' => $view,
    'part' => $layer['part'],
    'option_value_slug' => null,
    'image_path' => $layer['image'],
    'z_index' => $layer['z_index'],
    'is_active' => true,
      ]);
  }
  }

  /**
   * Seed waistcoat layers
   */
  private function seedWaistcoatLayers(SuitModel $suitModel, Fabric $fabric, string $waistcoatPath, string $view): void
  {
  if ($view === 'front') {
      $layers = [
    ['part' => 'waistcoat_body', 'image' => "{$waistcoatPath}/front/cruzado_5_bot.1.png", 'z_index' => 1100],
    ['part' => 'waistcoat_edge', 'image' => "{$waistcoatPath}/front/cruzado_redondeado.1.png", 'z_index' => 1200],
    ['part' => 'waistcoat_body_top', 'image' => "{$waistcoatPath}/front/cruzado_5_bot.1.png", 'z_index' => 1300],
    ['part' => 'waistcoat_pocket', 'image' => "{$waistcoatPath}/front/bolsillo_grande_solapa.1.png", 'z_index' => 1400],
      ];
  } else {
      $layers = [
    ['part' => 'waistcoat_back', 'image' => "{$waistcoatPath}/back/front_2.png", 'z_index' => 2000],
      ];
  }

  foreach ($layers as $layer) {
      SuitLayer::create([
    'suit_model_id' => $suitModel->id,
    'fabric_id' => $fabric->id,
    'view' => $view,
    'part' => $layer['part'],
    'option_value_slug' => null,
    'image_path' => $layer['image'],
    'z_index' => $layer['z_index'],
    'is_active' => true,
      ]);
  }
  }

  /**
   * Seed shared layers (shirt, tie, shoes, buttons, shadows)
   */
  private function seedSharedLayers(SuitModel $suitModel): void
  {
  $style = $suitModel->style ?? 'simple';
  $buttonCount = $suitModel->button_count ?? 2;

  // Get correct button image based on style and count
  $bodyButtonImage = $this->getBodyButtonImage($style, $buttonCount);

  $frontLayers = [
      ['part' => 'shoes', 'image' => "{$this->appUrl}/pants/shoes/shoes_black_1.png", 'z_index' => 100],
      ['part' => 'shirt_base', 'image' => "{$this->appUrl}/jacket/shirt/Front_debajo.png", 'z_index' => 1001],
      ['part' => 'shirt_shadow', 'image' => "{$this->appUrl}/jacket/shirt/sombra_chaqueta_sobre_pantalon.png", 'z_index' => 1002],
      ['part' => 'tie', 'image' => "{$this->appUrl}/jacket/tie/2.png", 'z_index' => 1002],
      ['part' => 'pants_buttons', 'image' => "{$this->appUrl}/pants/botones/6.1_b.png", 'z_index' => 900],
      ['part' => 'waistcoat_shirt_base', 'image' => "{$this->appUrl}/waistcoat/shirt/Front_debajo.png", 'z_index' => 1001],
      ['part' => 'waistcoat_shirt_shadow', 'image' => "{$this->appUrl}/waistcoat/shirt/sombra_chaleco_sobre_pantalon.png", 'z_index' => 1002],
      ['part' => 'waistcoat_shirt_top', 'image' => "{$this->appUrl}/waistcoat/shirt/Front_encima_suit3.png", 'z_index' => 1003],
      ['part' => 'waistcoat_buttons', 'image' => "{$this->appUrl}/waistcoat/botones/6_crossed_5.png", 'z_index' => 1500],
      ['part' => 'buttons_body', 'image' => "{$this->appUrl}/jacket/botones/{$bodyButtonImage}.png", 'z_index' => 3900],
      ['part' => 'buttons_sleeve', 'image' => "{$this->appUrl}/jacket/botones/6_puno_2_0.png", 'z_index' => 3900],
      ['part' => 'shirt_cuff', 'image' => "{$this->appUrl}/jacket/shirt/puno3.png", 'z_index' => 5010],
  ];

  $backLayers = [
      ['part' => 'shoes_back', 'image' => "{$this->appUrl}/pants/shoes/shoes_black_2.png", 'z_index' => 100],
      ['part' => 'pants_back_buttons', 'image' => "{$this->appUrl}/pants/botones/6_simple.2.png", 'z_index' => 900],
      ['part' => 'shirt_back', 'image' => "{$this->appUrl}/jacket/shirt/Back.png", 'z_index' => 1001],
      ['part' => 'shirt_back_shadow', 'image' => "{$this->appUrl}/jacket/shirt/sombra_chaqueta_sobre_pantalon.2.png", 'z_index' => 1002],
      ['part' => 'waistcoat_back_shirt', 'image' => "{$this->appUrl}/waistcoat/shirt/Back_suit3.png", 'z_index' => 1001],
      ['part' => 'waistcoat_back_shadow', 'image' => "{$this->appUrl}/waistcoat/shirt/sombra_chaleco_sobre_pantalon.2.png", 'z_index' => 1002],
      ['part' => 'waistcoat_lining_back', 'image' => "{$this->appUrl}/waistcoat/lining_54/espalda.2.png", 'z_index' => 2000],
  ];

  foreach ($frontLayers as $layer) {
      SuitLayer::create([
    'suit_model_id' => $suitModel->id,
    'fabric_id' => null,
    'view' => 'front',
    'part' => $layer['part'],
    'option_value_slug' => null,
    'image_path' => $layer['image'],
    'z_index' => $layer['z_index'],
    'is_active' => true,
      ]);
  }

  foreach ($backLayers as $layer) {
      SuitLayer::create([
    'suit_model_id' => $suitModel->id,
    'fabric_id' => null,
    'view' => 'back',
    'part' => $layer['part'],
    'option_value_slug' => null,
    'image_path' => $layer['image'],
    'z_index' => $layer['z_index'],
    'is_active' => true,
      ]);
  }
  }

  /**
   * Get correct body button image name based on style and button count
   */
  private function getBodyButtonImage(string $style, int $buttonCount): string
  {
  $styleConfig = $this->jacketStyles[$style] ?? $this->jacketStyles['simple'];

  if (isset($styleConfig['buttons'][$buttonCount])) {
      return $styleConfig['buttons'][$buttonCount];
  }

  // Default fallback
  return $style === 'crossed' ? '6_crossed_cuerpo_long_4' : '6_simple_cuerpo_medium_2';
  }
}
