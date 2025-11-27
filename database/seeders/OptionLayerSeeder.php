<?php

namespace Database\Seeders;

use App\Models\Fabric;
use App\Models\OptionValue;
use App\Models\SuitLayer;
use App\Models\SuitModel;
use Illuminate\Database\Seeder;

class OptionLayerSeeder extends Seeder
{
  private string $appUrl = 'https://www.vestonduynguyen.com/sites/default/files/application/3d/man';

  /**
   * Mapping of option values to their specific layer images
   */
  private array $optionLayerMap = [
  // JACKET STYLE
  'single_breasted' => [
      'front' => [
    ['part' => 'jacket_body', 'image' => 'jacket/{fabric}/front/classica_mediana_corpus_estrecho.1.png', 'z_index' => 3050],
    ['part' => 'lapel', 'image' => 'jacket/{fabric}/front/classica_mediana_solapa_estandar.1.png', 'z_index' => 3300],
    ['part' => 'collar', 'image' => 'jacket/{fabric}/front/classica_cuello_arriba.1.png', 'z_index' => 3350],
      ],
      'buttons' => 'jacket/botones/6_simple_cuerpo_medium_2.png',
  ],
  'double_breasted' => [
      'front' => [
    ['part' => 'jacket_body', 'image' => 'jacket/{fabric}/front/cruzada_larga_corpus_estrecho.1.png', 'z_index' => 3050],
    ['part' => 'lapel', 'image' => 'jacket/{fabric}/front/cruzada_larga_solapa_pico.1.png', 'z_index' => 3300],
    ['part' => 'collar', 'image' => 'jacket/{fabric}/front/cruzada_cuello_arriba.1.png', 'z_index' => 3350],
      ],
      'buttons' => 'jacket/botones/6_crossed_cuerpo_long_4.png',
  ],
  'mandarin' => [
      'front' => [
    ['part' => 'jacket_body', 'image' => 'jacket/{fabric}/front/mao_corpus_estrecho.1.png', 'z_index' => 3050],
      ],
      'buttons' => 'jacket/botones/6_simple_cuerpo_medium_5.png',
  ],

  // LAPEL TYPE
  'notch_lapel' => [
      'front' => [
    ['part' => 'lapel', 'image' => 'jacket/{fabric}/front/classica_mediana_solapa_estandar.1.png', 'z_index' => 3300],
      ],
  ],
  'peak_lapel' => [
      'front' => [
    ['part' => 'lapel', 'image' => 'jacket/{fabric}/front/classica_mediana_solapa_pico.1.png', 'z_index' => 3300],
      ],
  ],
  'shawl_lapel' => [
      'front' => [
    ['part' => 'lapel', 'image' => 'jacket/{fabric}/front/smoking_solapa_estandar.1.png', 'z_index' => 3300],
      ],
  ],

  // JACKET BUTTONS
  '1_button' => [
      'buttons' => 'jacket/botones/6_simple_cuerpo_medium_1.png',
  ],
  '2_buttons' => [
      'buttons' => 'jacket/botones/6_simple_cuerpo_medium_2.png',
  ],
  '3_buttons' => [
      'buttons' => 'jacket/botones/6_simple_cuerpo_medium_3.png',
  ],
  '4_buttons' => [
      'buttons' => 'jacket/botones/6_crossed_cuerpo_long_4.png',
  ],

  // BREAST POCKET
  'with_breast_pocket' => [
      'front' => [
    ['part' => 'breast_pocket', 'image' => 'jacket/{fabric}/front/bolsillo_pecho.1.png', 'z_index' => 3200],
      ],
  ],
  'no_breast_pocket' => [
      'remove' => ['breast_pocket'],
  ],

  // HIP POCKETS
  'no_hip_pockets' => [
      'remove' => ['pocket_right', 'pocket_left'],
  ],
  '2_welt_pockets' => [
      'front' => [
    ['part' => 'pocket_right', 'image' => 'jacket/{fabric}/front/bolsillos_con_solapa_abajo_der_estrecha.1.png', 'z_index' => 3600],
    ['part' => 'pocket_left', 'image' => 'jacket/{fabric}/front/bolsillos_con_solapa_abajo_izq.1.png', 'z_index' => 3600],
      ],
  ],

  // HIP POCKET STYLE
  'welt_with_flap' => [
      'front' => [
    ['part' => 'pocket_right', 'image' => 'jacket/{fabric}/front/bolsillos_con_solapa_abajo_der_estrecha.1.png', 'z_index' => 3600],
    ['part' => 'pocket_left', 'image' => 'jacket/{fabric}/front/bolsillos_con_solapa_abajo_izq.1.png', 'z_index' => 3600],
      ],
  ],
  'welt_no_flap' => [
      'front' => [
    ['part' => 'pocket_right', 'image' => 'jacket/{fabric}/front/bolsillos_sin_solapa_abajo_der_estrecha.1.png', 'z_index' => 3600],
    ['part' => 'pocket_left', 'image' => 'jacket/{fabric}/front/bolsillos_sin_solapa_abajo_izq.1.png', 'z_index' => 3600],
      ],
  ],
  'patch_pockets' => [
      'front' => [
    ['part' => 'pocket_right', 'image' => 'jacket/{fabric}/front/bolsillos_con_solapa_abajo_der_estrecha.1.png', 'z_index' => 3600],
    ['part' => 'pocket_left', 'image' => 'jacket/{fabric}/front/bolsillos_con_solapa_abajo_izq.1.png', 'z_index' => 3600],
      ],
  ],

  // BACK VENT
  'no_vent' => [
      'back' => [
    ['part' => 'jacket_back_body', 'image' => 'jacket/{fabric}/back/espalda_0_cortes_estrecha.2.png', 'z_index' => 3150],
      ],
  ],
  'center_vent' => [
      'back' => [
    ['part' => 'jacket_back_body', 'image' => 'jacket/{fabric}/back/espalda_1_corte_estrecha.2.png', 'z_index' => 3150],
      ],
  ],
  'side_vents' => [
      'back' => [
    ['part' => 'jacket_back_body', 'image' => 'jacket/{fabric}/back/espalda_2_cortes_estrecha.2.png', 'z_index' => 3150],
      ],
  ],

  // SLEEVE BUTTONS
  '2_sleeve_buttons' => [
      'buttons_sleeve' => 'jacket/botones/6_puno_2_0.png',
  ],
  '3_sleeve_buttons' => [
      'buttons_sleeve' => 'jacket/botones/6_puno_3_0.png',
  ],
  '4_sleeve_buttons' => [
      'buttons_sleeve' => 'jacket/botones/6_puno_4_0.png',
  ],

  // PANTS FIT
  'classic_pants' => [
      'front' => [
    ['part' => 'pants_body', 'image' => 'pants/{fabric}/front/pantalon_deportivo.1.png', 'z_index' => 200],
      ],
  ],
  'slim_pants' => [
      'front' => [
    ['part' => 'pants_body', 'image' => 'pants/{fabric}/front/pantalon_deportivo.1.png', 'z_index' => 200],
      ],
  ],

  // PANTS PLEATS
  'no_pleats' => [
      'front' => [
    ['part' => 'pants_body', 'image' => 'pants/{fabric}/front/pantalon_deportivo.1.png', 'z_index' => 200],
      ],
  ],
  '1_pleat' => [
      'front' => [
    ['part' => 'pants_body', 'image' => 'pants/{fabric}/front/pantalon_pinza_1.1.png', 'z_index' => 200],
      ],
  ],
  '2_pleats' => [
      'front' => [
    ['part' => 'pants_body', 'image' => 'pants/{fabric}/front/pantalon_pinza_2.1.png', 'z_index' => 200],
      ],
  ],

  // FRONT POCKETS
  'slant_pockets' => [
      'front' => [
    ['part' => 'pants_pocket', 'image' => 'pants/{fabric}/front/bolsillo_diagonal.1.png', 'z_index' => 700],
      ],
  ],
  'vertical_pockets' => [
      'front' => [
    ['part' => 'pants_pocket', 'image' => 'pants/{fabric}/front/bolsillo_recto.1.png', 'z_index' => 700],
      ],
  ],
  'frogmouth_pockets' => [
      'front' => [
    ['part' => 'pants_pocket', 'image' => 'pants/{fabric}/front/bolsillo_frances.1.png', 'z_index' => 700],
      ],
  ],

  // BACK POCKETS
  'no_back_pockets' => [
      'remove' => ['pants_back_pocket'],
  ],
  '1_back_pocket' => [
      'back' => [
    ['part' => 'pants_back_pocket', 'image' => 'pants/{fabric}/back/bol_der_traseros_sin_tapa.2.png', 'z_index' => 800],
      ],
  ],
  '2_back_pockets' => [
      'back' => [
    ['part' => 'pants_back_pocket', 'image' => 'pants/{fabric}/back/bol_der_traseros_sin_tapa.2.png', 'z_index' => 800],
    ['part' => 'pants_back_pocket_left', 'image' => 'pants/{fabric}/back/bol_izq_traseros_sin_tapa.2.png', 'z_index' => 800],
      ],
  ],

  // BACK POCKET STYLE
  'standard_back_pocket' => [
      'back' => [
    ['part' => 'pants_back_pocket', 'image' => 'pants/{fabric}/back/bol_der_traseros_sin_tapa.2.png', 'z_index' => 800],
      ],
  ],
  'flap_back_pocket' => [
      'back' => [
    ['part' => 'pants_back_pocket', 'image' => 'pants/{fabric}/back/bol_der_traseros_solapa.2.png', 'z_index' => 800],
      ],
  ],

  // PANTS CUFF
  'standard_hem' => [],
  'turnup_cuff' => [
      'front' => [
    ['part' => 'pants_cuff', 'image' => 'pants/{fabric}/front/vuelta_pantalon.1.png', 'z_index' => 250],
      ],
  ],
  ];

  public function run(): void
  {
  $suitModels = SuitModel::all();
  $fabrics = Fabric::all();

  $count = 0;

  foreach ($this->optionLayerMap as $optionSlug => $config) {
      // Skip if no front/back layers defined
      if (empty($config['front']) && empty($config['back']) && empty($config['buttons']) && empty($config['buttons_sleeve'])) {
    continue;
      }

      foreach ($suitModels as $model) {
    foreach ($fabrics as $fabric) {
          $fabricCode = $fabric->code ?? '102';

          // Process front view layers
          if (!empty($config['front'])) {
      foreach ($config['front'] as $layer) {
              $imagePath = str_replace('{fabric}', "{$fabricCode}_fabric", $layer['image']);

              SuitLayer::create([
        'suit_model_id' => $model->id,
        'fabric_id' => $fabric->id,
        'view' => 'front',
        'part' => $layer['part'] . '_opt',
        'option_value_slug' => $optionSlug,
        'image_path' => "{$this->appUrl}/{$imagePath}",
        'z_index' => $layer['z_index'],
        'is_active' => true,
              ]);
              $count++;
      }
          }

          // Process back view layers
          if (!empty($config['back'])) {
      foreach ($config['back'] as $layer) {
              $imagePath = str_replace('{fabric}', "{$fabricCode}_fabric", $layer['image']);

              SuitLayer::create([
        'suit_model_id' => $model->id,
        'fabric_id' => $fabric->id,
        'view' => 'back',
        'part' => $layer['part'] . '_opt',
        'option_value_slug' => $optionSlug,
        'image_path' => "{$this->appUrl}/{$imagePath}",
        'z_index' => $layer['z_index'],
        'is_active' => true,
              ]);
              $count++;
      }
          }
    }

    // Process button layers (shared across fabrics)
    if (!empty($config['buttons'])) {
          SuitLayer::create([
      'suit_model_id' => $model->id,
      'fabric_id' => null,
      'view' => 'front',
      'part' => 'buttons_body_opt',
      'option_value_slug' => $optionSlug,
      'image_path' => "{$this->appUrl}/{$config['buttons']}",
      'z_index' => 3900,
      'is_active' => true,
          ]);
          $count++;
    }

    if (!empty($config['buttons_sleeve'])) {
          SuitLayer::create([
      'suit_model_id' => $model->id,
      'fabric_id' => null,
      'view' => 'front',
      'part' => 'buttons_sleeve_opt',
      'option_value_slug' => $optionSlug,
      'image_path' => "{$this->appUrl}/{$config['buttons_sleeve']}",
      'z_index' => 3900,
      'is_active' => true,
          ]);
          $count++;
    }
      }
  }

  $this->command->info("Created {$count} option-specific layers!");
  }
}
