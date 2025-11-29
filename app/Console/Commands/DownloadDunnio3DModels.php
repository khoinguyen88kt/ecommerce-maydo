<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\ThreeD\ProductType3D;
use App\Models\ThreeD\PartCategory3D;
use App\Models\ThreeD\PartOption3D;
use App\Models\ThreeD\ModelMesh3D;
use App\Models\Fabric;

/**
 * Download 3D models from Dunnio Tailor
 *
 * Based on analysis of https://dunniotailor.com/custom-suits
 * Models are stored at: https://t4t.vn/sites/default/files/fashion/meshes/3d/Render/Models/
 * Textures are stored at: https://dunniotailor.com/sites/default/files/
 */
class DownloadDunnio3DModels extends Command
{
  protected $signature = 'dunnio:download-models
                            {--product=all : Product to download (jacket, pant, vest, all)}
                            {--dry-run : List files without downloading}
                            {--seed : Also seed database with model data}';

  protected $description = 'Download 3D models from Dunnio Tailor for the configurator';

  /**
   * Base URL for Dunnio 3D models
   */
  protected string $modelsBaseUrl = 'https://t4t.vn/sites/default/files/fashion/meshes/3d/Render/Models';

  /**
   * Base URL for Dunnio fabrics API
   */
  protected string $fabricsApiUrl = 'https://dunniotailor.com/materials/load/ajax/all_fabric';

  /**
   * Exact model URLs from Dunnio network requests
   * These are the actual working URLs captured from the site
   */
  protected array $jacketModels = [
    // Front Bottom
    'Jacket/Front/Bottom/2Button/Curved.gltf',
    'Jacket/Front/Bottom/2Button/Square.gltf',
    'Jacket/Front/Bottom/3Button/Curved.gltf',
    'Jacket/Front/Bottom/3Button/Square.gltf',
    // Front Buttons
    'Jacket/Front/Button/2Button/S4.gltf',
    'Jacket/Front/Button/3Button/S4.gltf',
    // Front Thread
    'Jacket/Front/Thread/2Button.gltf',
    'Jacket/Front/Thread/3Button.gltf',
    // Lapel - Regular
    'Jacket/Lapel/Regular/Upper/2Button/NL1.gltf',
    'Jacket/Lapel/Regular/Upper/2Button/CL1.gltf',
    'Jacket/Lapel/Regular/Upper/2Button/PL1.gltf',
    'Jacket/Lapel/Regular/Lower/2Button/NL1.gltf',
    'Jacket/Lapel/Regular/Lower/2Button/CL1.gltf',
    'Jacket/Lapel/Regular/Lower/2Button/PL1.gltf',
    // Lapel - Larger
    'Jacket/Lapel/Larger/Upper/2Button/NL1.gltf',
    'Jacket/Lapel/Larger/Upper/2Button/CL1.gltf',
    'Jacket/Lapel/Larger/Upper/2Button/PL1.gltf',
    'Jacket/Lapel/Larger/Lower/2Button/NL1.gltf',
    'Jacket/Lapel/Larger/Lower/2Button/CL1.gltf',
    'Jacket/Lapel/Larger/Lower/2Button/PL1.gltf',
    // Lapel - Small
    'Jacket/Lapel/Small/Upper/2Button/NL1.gltf',
    'Jacket/Lapel/Small/Lower/2Button/NL1.gltf',
    // Lapel - Shawl
    'Jacket/Lapel/Shawl/Upper/2Button/SL1.gltf',
    'Jacket/Lapel/Shawl/Lower/2Button/SL1.gltf',
    // Sleeve
    'Jacket/Sleeve/Sleeve.gltf',
    'Jacket/Sleeve/Standard/3Button/S4.gltf',
    'Jacket/Sleeve/Standard/4Button/S4.gltf',
    'Jacket/Sleeve/Standard/5Button/S4.gltf',
    'Jacket/Sleeve/Standard/LastButton/S4.gltf',
    'Jacket/Sleeve/Standard/Thread/3Button.gltf',
    'Jacket/Sleeve/Standard/Thread/4Button.gltf',
    'Jacket/Sleeve/Standard/Thread/5Button.gltf',
    'Jacket/Sleeve/Standard/Thread/LastThread.gltf',
    // Pocket
    'Jacket/Pocket/PK-1.gltf',
    'Jacket/Pocket/PK-2.gltf',
    'Jacket/Pocket/PK-3.gltf',
    'Jacket/Pocket/PK-4.gltf',
    'Jacket/Pocket/ChestPocket.gltf',
    // Vent
    'Jacket/Vent/SideVent.gltf',
    'Jacket/Vent/CenterVent.gltf',
    'Jacket/Vent/NoVent.gltf',
    // Lining
    'Jacket/Lining/Sleeve.gltf',
    'Jacket/Lining/FullyLined/Curved.gltf',
    'Jacket/Lining/FullyLined/Square.gltf',
    'Jacket/Lining/HalfLined/Curved.gltf',
    'Jacket/Lining/HalfLined/Square.gltf',
    // Brand
    'Jacket/Brand/Label.gltf',
  ];

  protected array $pantModels = [
    // Style
    'Pant/Style/Flat/Dave.gltf',
    'Pant/Style/1Pleats/Dave.gltf',
    'Pant/Style/2Pleats/Dave.gltf',
    // Waistband
    'Pant/Waistband/Square.gltf',
    'Pant/Waistband/Rounded.gltf',
    'Pant/Waistband/Button/S4.gltf',
    'Pant/Waistband/Button/Thread.gltf',
    // Belt loops
    'Pant/Beltloops/Single.gltf',
    'Pant/Beltloops/Double.gltf',
    // Pocket
    'Pant/Pocket/Slanted.gltf',
    'Pant/Pocket/Straight.gltf',
    'Pant/Pocket/Rounded.gltf',
    // Back Pocket
    'Pant/BackPocket/Right/Single.gltf',
    'Pant/BackPocket/Right/Double.gltf',
    'Pant/BackPocket/Left/Single.gltf',
    // Lining
    'Pant/Lining/Dave.gltf',
  ];

  protected array $vestModels = [
    // Style
    'Vest/Style/5Button/Body.gltf',
    'Vest/Style/6Button/Body.gltf',
    // Lapel
    'Vest/Lapel/Notch.gltf',
    'Vest/Lapel/Peak.gltf',
    'Vest/Lapel/Shawl.gltf',
    'Vest/Lapel/NoLapel.gltf',
    // Pocket
    'Vest/Pocket/Welt.gltf',
    'Vest/Pocket/Flap.gltf',
    // Back
    'Vest/Back/Same.gltf',
    'Vest/Back/Lining.gltf',
    // Button
    'Vest/Button/S4.gltf',
  ];

  /**
   * Button textures
   */
  protected array $buttonTextures = [
    'Jacket/Front/Button/2Button/S14.png',
    'Jacket/Front/Button/3Button/S14.png',
    'Jacket/Sleeve/Standard/4Button/S14.png',
    'Jacket/Sleeve/Standard/LastButton/S14.png',
    'Pant/Waistband/Button/S14.png',
  ];

  public function handle(): int
  {
    $product = $this->option('product');
    $dryRun = $this->option('dry-run');
    $seed = $this->option('seed');

    $this->info('🎨 Dunnio 3D Model Downloader');
    $this->info('Models URL: ' . $this->modelsBaseUrl);
    $this->newLine();

    $stats = [
      'total' => 0,
      'downloaded' => 0,
      'skipped' => 0,
      'failed' => [],
    ];

    // Determine which products to download
    $products = $product === 'all' ? ['jacket', 'pant', 'vest'] : [strtolower($product)];

    foreach ($products as $prod) {
      $models = match ($prod) {
        'jacket' => $this->jacketModels,
        'pant' => $this->pantModels,
        'vest' => $this->vestModels,
        default => [],
      };

      if (empty($models)) {
        $this->warn("Unknown product: {$prod}");
        continue;
      }

      $this->info("📦 Processing: " . ucfirst($prod));
      $this->downloadModels($models, $dryRun, $stats);
      $this->newLine();
    }

    // Download button textures
    $this->info("🔘 Downloading button textures...");
    $this->downloadModels($this->buttonTextures, $dryRun, $stats);
    $this->newLine();

    // Download fabrics
    $this->info("🎨 Downloading fabric textures...");
    $this->downloadFabrics($dryRun, $stats);
    $this->newLine();

    // Download environment map
    $this->info("🌍 Downloading environment map...");
    $this->downloadEnvironmentMap($dryRun);
    $this->newLine();

    // Summary
    $this->displaySummary($stats, $dryRun);

    // Seed database if requested
    if ($seed && !$dryRun) {
      $this->newLine();
      $this->info('🌱 Seeding database with model data...');
      $this->seedDatabase();
    }

    return self::SUCCESS;
  }

  /**
   * Download a list of models
   */
  protected function downloadModels(array $models, bool $dryRun, array &$stats): void
  {
    foreach ($models as $modelPath) {
      $stats['total']++;
      $url = "{$this->modelsBaseUrl}/{$modelPath}";
      $localPath = "3d-models/{$modelPath}";

      if ($dryRun) {
        $this->line("  📄 {$modelPath}");
        continue;
      }

      // Check if already exists
      if (Storage::disk('public')->exists($localPath)) {
        $stats['skipped']++;
        $this->line("  ⏭️  {$modelPath} (exists)");
        continue;
      }

      // Download file
      if ($this->downloadFile($url, $localPath)) {
        $stats['downloaded']++;
        $this->line("  ✅ {$modelPath}");

        // Also download associated .bin file if it's a gltf
        if (str_ends_with($modelPath, '.gltf')) {
          $binUrl = str_replace('.gltf', '.bin', $url);
          $binPath = str_replace('.gltf', '.bin', $localPath);
          if ($this->downloadFile($binUrl, $binPath)) {
            $this->line("  ✅ " . str_replace('.gltf', '.bin', $modelPath));
          }
        }
      } else {
        $stats['failed'][] = $modelPath;
        $this->warn("  ❌ {$modelPath}");
      }
    }
  }

  /**
   * Download fabrics from Dunnio API
   */
  protected function downloadFabrics(bool $dryRun, array &$stats): void
  {
    if ($dryRun) {
      $this->line("  Would download fabrics from API...");
      return;
    }

    try {
      // Fetch fabric data from Dunnio API
      $response = Http::timeout(60)
        ->withHeaders([
          'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7)',
          'Referer' => 'https://dunniotailor.com/custom-suits',
          'X-Requested-With' => 'XMLHttpRequest',
        ])
        ->asForm()
        ->post($this->fabricsApiUrl, [
          'nid' => [17, 5],
          'model_id' => [29, 27],
        ]);

      if (!$response->successful()) {
        $this->warn("  ❌ Failed to fetch fabric data from API");
        return;
      }

      $data = $response->json();
      if (!isset($data['data'])) {
        $this->warn("  ❌ Invalid fabric API response");
        return;
      }

      $fabrics = json_decode($data['data'], true);
      $this->info("  Found " . count($fabrics) . " fabrics");

      $downloaded = 0;
      foreach ($fabrics as $fabricId => $fabric) {
        if (!isset($fabric['product_texture']['filename'])) {
          continue;
        }

        $filename = $fabric['product_texture']['filename'];
        $textureUrl = "https://dunniotailor.com/sites/default/files/{$filename}";
        $localPath = "fabrics/textures/{$filename}";

        // Check if already exists
        if (Storage::disk('public')->exists($localPath)) {
          continue;
        }

        if ($this->downloadFile($textureUrl, $localPath)) {
          $downloaded++;
          $this->line("  ✅ {$filename}");
        }

        // Download thumbnail
        if (isset($fabric['product_texture']['thumbnail'])) {
          $thumbUrl = $fabric['product_texture']['thumbnail'];
          $thumbFilename = 'thumb_' . $filename;
          $thumbPath = "fabrics/thumbnails/{$thumbFilename}";

          if (!Storage::disk('public')->exists($thumbPath)) {
            $this->downloadFile($thumbUrl, $thumbPath);
          }
        }
      }

      $this->info("  Downloaded {$downloaded} new fabric textures");

      // Save fabric data to JSON for later seeding
      Storage::disk('public')->put(
        '3d-models/fabrics-data.json',
        json_encode($fabrics, JSON_PRETTY_PRINT)
      );
      $this->line("  ✅ Saved fabric data to fabrics-data.json");
    } catch (\Exception $e) {
      $this->error("  ❌ Error downloading fabrics: " . $e->getMessage());
    }
  }

  /**
   * Download environment map for PBR rendering
   */
  protected function downloadEnvironmentMap(bool $dryRun): void
  {
    $envUrl = 'https://dunniotailor.com/sites/default/files/environments/studio.env';
    $localPath = '3d-models/environments/studio.env';

    if ($dryRun) {
      $this->line("  📄 environments/studio.env");
      return;
    }

    if (Storage::disk('public')->exists($localPath)) {
      $this->line("  ⏭️  studio.env (exists)");
      return;
    }

    if ($this->downloadFile($envUrl, $localPath)) {
      $this->line("  ✅ studio.env");
    } else {
      $this->warn("  ❌ studio.env");
    }
  }

  /**
   * Download a file and save to storage
   */
  protected function downloadFile(string $url, string $localPath): bool
  {
    try {
      $response = Http::timeout(30)
        ->withHeaders([
          'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36',
          'Referer' => 'https://dunniotailor.com/',
          'Accept' => '*/*',
        ])
        ->get($url);

      if ($response->successful()) {
        // Ensure directory exists
        $dir = dirname($localPath);
        Storage::disk('public')->makeDirectory($dir);

        // Save file
        Storage::disk('public')->put($localPath, $response->body());
        return true;
      }
    } catch (\Exception $e) {
      // Silent fail for missing files
    }

    return false;
  }

  /**
   * Display download summary
   */
  protected function displaySummary(array $stats, bool $dryRun): void
  {
    $this->info('═══════════════════════════════════════');
    $this->info('📊 Summary');
    $this->info('═══════════════════════════════════════');
    $this->info("Total files:    {$stats['total']}");

    if (!$dryRun) {
      $this->info("Downloaded:     {$stats['downloaded']}");
      $this->info("Skipped:        {$stats['skipped']}");
      $this->info("Failed:         " . count($stats['failed']));

      if (count($stats['failed']) > 0) {
        $this->newLine();
        $this->warn('Failed files:');
        foreach ($stats['failed'] as $file) {
          $this->line("  - {$file}");
        }
      }

      $this->newLine();
      $this->info('📁 Files saved to: storage/app/public/3d-models/');
    }
  }

  /**
   * Seed the database with downloaded model data
   */
  protected function seedDatabase(): void
  {
    DB::beginTransaction();

    try {
      // Seed Jacket
      $this->seedJacket();
      // Seed Pant
      $this->seedPant();
      // Seed Vest
      $this->seedVest();
      // Seed fabrics
      $this->seedFabrics();

      DB::commit();
      $this->info('✅ Database seeded successfully!');
    } catch (\Exception $e) {
      DB::rollBack();
      $this->error('❌ Database seeding failed: ' . $e->getMessage());
    }
  }

  /**
   * Seed Jacket product type
   */
  protected function seedJacket(): void
  {
    $jacket = ProductType3D::updateOrCreate(
      ['code' => 'jacket'],
      ['name' => 'Jacket', 'name_vi' => 'Áo vest', 'sort_order' => 1, 'is_active' => true]
    );

    // Front Style
    $frontStyle = PartCategory3D::updateOrCreate(
      ['product_type_id' => $jacket->id, 'code' => 'front-style'],
      ['name' => 'Front Style', 'name_vi' => 'Kiểu dáng trước', 'sort_order' => 1, 'is_active' => true]
    );
    $this->createOptions($frontStyle, [
      ['name' => '2 Button Curved', 'name_vi' => '2 nút bo tròn', 'model_path' => '3d-models/Jacket/Front/Bottom/2Button/Curved.gltf', 'is_default' => true],
      ['name' => '2 Button Square', 'name_vi' => '2 nút vuông', 'model_path' => '3d-models/Jacket/Front/Bottom/2Button/Square.gltf'],
      ['name' => '3 Button Curved', 'name_vi' => '3 nút bo tròn', 'model_path' => '3d-models/Jacket/Front/Bottom/3Button/Curved.gltf'],
      ['name' => '3 Button Square', 'name_vi' => '3 nút vuông', 'model_path' => '3d-models/Jacket/Front/Bottom/3Button/Square.gltf'],
    ]);

    // Lapel
    $lapel = PartCategory3D::updateOrCreate(
      ['product_type_id' => $jacket->id, 'code' => 'lapel'],
      ['name' => 'Lapel', 'name_vi' => 'Ve áo', 'sort_order' => 2, 'is_active' => true]
    );
    $this->createOptions($lapel, [
      ['name' => 'Notch Lapel', 'name_vi' => 'Ve cổ điển', 'model_path' => '3d-models/Jacket/Lapel/Regular/Upper/2Button/NL1.gltf', 'is_default' => true],
      ['name' => 'Peak Lapel', 'name_vi' => 'Ve nhọn', 'model_path' => '3d-models/Jacket/Lapel/Regular/Upper/2Button/PL1.gltf'],
      ['name' => 'Shawl Lapel', 'name_vi' => 'Ve khăn choàng', 'model_path' => '3d-models/Jacket/Lapel/Shawl/Upper/2Button/SL1.gltf'],
    ]);

    // Pocket
    $pocket = PartCategory3D::updateOrCreate(
      ['product_type_id' => $jacket->id, 'code' => 'pocket'],
      ['name' => 'Pocket', 'name_vi' => 'Túi áo', 'sort_order' => 3, 'is_active' => true]
    );
    $this->createOptions($pocket, [
      ['name' => 'Flap Pocket', 'name_vi' => 'Túi có nắp', 'model_path' => '3d-models/Jacket/Pocket/PK-1.gltf', 'is_default' => true],
      ['name' => 'Jetted Pocket', 'name_vi' => 'Túi hàn', 'model_path' => '3d-models/Jacket/Pocket/PK-2.gltf'],
      ['name' => 'Patch Pocket', 'name_vi' => 'Túi chắp', 'model_path' => '3d-models/Jacket/Pocket/PK-3.gltf'],
      ['name' => 'Hacking Pocket', 'name_vi' => 'Túi nghiêng', 'model_path' => '3d-models/Jacket/Pocket/PK-4.gltf'],
    ]);

    // Vent
    $vent = PartCategory3D::updateOrCreate(
      ['product_type_id' => $jacket->id, 'code' => 'vent'],
      ['name' => 'Vent', 'name_vi' => 'Xẻ tà', 'sort_order' => 4, 'is_active' => true]
    );
    $this->createOptions($vent, [
      ['name' => 'Side Vent', 'name_vi' => 'Xẻ hai bên', 'model_path' => '3d-models/Jacket/Vent/SideVent.gltf', 'is_default' => true],
      ['name' => 'Center Vent', 'name_vi' => 'Xẻ giữa', 'model_path' => '3d-models/Jacket/Vent/CenterVent.gltf'],
      ['name' => 'No Vent', 'name_vi' => 'Không xẻ', 'model_path' => '3d-models/Jacket/Vent/NoVent.gltf'],
    ]);

    // Sleeve Buttons
    $sleeveButtons = PartCategory3D::updateOrCreate(
      ['product_type_id' => $jacket->id, 'code' => 'sleeve-buttons'],
      ['name' => 'Sleeve Buttons', 'name_vi' => 'Nút tay áo', 'sort_order' => 5, 'is_active' => true]
    );
    $this->createOptions($sleeveButtons, [
      ['name' => '3 Buttons', 'name_vi' => '3 nút', 'model_path' => '3d-models/Jacket/Sleeve/Standard/3Button/S4.gltf'],
      ['name' => '4 Buttons', 'name_vi' => '4 nút', 'model_path' => '3d-models/Jacket/Sleeve/Standard/4Button/S4.gltf', 'is_default' => true],
      ['name' => '5 Buttons', 'name_vi' => '5 nút', 'model_path' => '3d-models/Jacket/Sleeve/Standard/5Button/S4.gltf'],
    ]);

    // Lining
    $lining = PartCategory3D::updateOrCreate(
      ['product_type_id' => $jacket->id, 'code' => 'lining'],
      ['name' => 'Lining', 'name_vi' => 'Lót trong', 'sort_order' => 6, 'is_active' => true]
    );
    $this->createOptions($lining, [
      ['name' => 'Fully Lined', 'name_vi' => 'Lót toàn bộ', 'model_path' => '3d-models/Jacket/Lining/FullyLined/Curved.gltf', 'is_default' => true],
      ['name' => 'Half Lined', 'name_vi' => 'Lót nửa', 'model_path' => '3d-models/Jacket/Lining/HalfLined/Curved.gltf'],
    ]);

    $this->line("  ✅ Seeded Jacket with 6 categories");
  }

  /**
   * Seed Pant product type
   */
  protected function seedPant(): void
  {
    $pant = ProductType3D::updateOrCreate(
      ['code' => 'pant'],
      ['name' => 'Pant', 'name_vi' => 'Quần', 'sort_order' => 2, 'is_active' => true]
    );

    // Style
    $style = PartCategory3D::updateOrCreate(
      ['product_type_id' => $pant->id, 'code' => 'style'],
      ['name' => 'Style', 'name_vi' => 'Kiểu dáng', 'sort_order' => 1, 'is_active' => true]
    );
    $this->createOptions($style, [
      ['name' => 'Flat Front', 'name_vi' => 'Phẳng', 'model_path' => '3d-models/Pant/Style/Flat/Dave.gltf', 'is_default' => true],
      ['name' => 'Single Pleat', 'name_vi' => 'Một ly', 'model_path' => '3d-models/Pant/Style/1Pleats/Dave.gltf'],
      ['name' => 'Double Pleat', 'name_vi' => 'Hai ly', 'model_path' => '3d-models/Pant/Style/2Pleats/Dave.gltf'],
    ]);

    // Waistband
    $waistband = PartCategory3D::updateOrCreate(
      ['product_type_id' => $pant->id, 'code' => 'waistband'],
      ['name' => 'Waistband', 'name_vi' => 'Cạp quần', 'sort_order' => 2, 'is_active' => true]
    );
    $this->createOptions($waistband, [
      ['name' => 'Square', 'name_vi' => 'Vuông', 'model_path' => '3d-models/Pant/Waistband/Square.gltf', 'is_default' => true],
      ['name' => 'Rounded', 'name_vi' => 'Bo tròn', 'model_path' => '3d-models/Pant/Waistband/Rounded.gltf'],
    ]);

    // Belt Loops
    $beltLoops = PartCategory3D::updateOrCreate(
      ['product_type_id' => $pant->id, 'code' => 'belt-loops'],
      ['name' => 'Belt Loops', 'name_vi' => 'Quai đeo', 'sort_order' => 3, 'is_active' => true]
    );
    $this->createOptions($beltLoops, [
      ['name' => 'Single', 'name_vi' => 'Đơn', 'model_path' => '3d-models/Pant/Beltloops/Single.gltf', 'is_default' => true],
      ['name' => 'Double', 'name_vi' => 'Đôi', 'model_path' => '3d-models/Pant/Beltloops/Double.gltf'],
    ]);

    // Front Pocket
    $frontPocket = PartCategory3D::updateOrCreate(
      ['product_type_id' => $pant->id, 'code' => 'front-pocket'],
      ['name' => 'Front Pocket', 'name_vi' => 'Túi trước', 'sort_order' => 4, 'is_active' => true]
    );
    $this->createOptions($frontPocket, [
      ['name' => 'Slanted', 'name_vi' => 'Nghiêng', 'model_path' => '3d-models/Pant/Pocket/Slanted.gltf', 'is_default' => true],
      ['name' => 'Straight', 'name_vi' => 'Thẳng', 'model_path' => '3d-models/Pant/Pocket/Straight.gltf'],
      ['name' => 'Rounded', 'name_vi' => 'Bo tròn', 'model_path' => '3d-models/Pant/Pocket/Rounded.gltf'],
    ]);

    // Back Pocket
    $backPocket = PartCategory3D::updateOrCreate(
      ['product_type_id' => $pant->id, 'code' => 'back-pocket'],
      ['name' => 'Back Pocket', 'name_vi' => 'Túi sau', 'sort_order' => 5, 'is_active' => true]
    );
    $this->createOptions($backPocket, [
      ['name' => 'Single Button', 'name_vi' => 'Một nút', 'model_path' => '3d-models/Pant/BackPocket/Right/Single.gltf', 'is_default' => true],
      ['name' => 'Double Button', 'name_vi' => 'Hai nút', 'model_path' => '3d-models/Pant/BackPocket/Right/Double.gltf'],
    ]);

    $this->line("  ✅ Seeded Pant with 5 categories");
  }

  /**
   * Seed Vest product type
   */
  protected function seedVest(): void
  {
    $vest = ProductType3D::updateOrCreate(
      ['code' => 'vest'],
      ['name' => 'Vest', 'name_vi' => 'Gi-lê', 'sort_order' => 3, 'is_active' => true]
    );

    // Style
    $style = PartCategory3D::updateOrCreate(
      ['product_type_id' => $vest->id, 'code' => 'style'],
      ['name' => 'Style', 'name_vi' => 'Kiểu dáng', 'sort_order' => 1, 'is_active' => true]
    );
    $this->createOptions($style, [
      ['name' => '5 Button', 'name_vi' => '5 nút', 'model_path' => '3d-models/Vest/Style/5Button/Body.gltf', 'is_default' => true],
      ['name' => '6 Button', 'name_vi' => '6 nút', 'model_path' => '3d-models/Vest/Style/6Button/Body.gltf'],
    ]);

    // Lapel
    $lapel = PartCategory3D::updateOrCreate(
      ['product_type_id' => $vest->id, 'code' => 'lapel'],
      ['name' => 'Lapel', 'name_vi' => 'Ve áo', 'sort_order' => 2, 'is_active' => true]
    );
    $this->createOptions($lapel, [
      ['name' => 'No Lapel', 'name_vi' => 'Không ve', 'model_path' => '3d-models/Vest/Lapel/NoLapel.gltf', 'is_default' => true],
      ['name' => 'Notch', 'name_vi' => 'Ve cổ điển', 'model_path' => '3d-models/Vest/Lapel/Notch.gltf'],
      ['name' => 'Peak', 'name_vi' => 'Ve nhọn', 'model_path' => '3d-models/Vest/Lapel/Peak.gltf'],
      ['name' => 'Shawl', 'name_vi' => 'Ve khăn choàng', 'model_path' => '3d-models/Vest/Lapel/Shawl.gltf'],
    ]);

    // Pocket
    $pocket = PartCategory3D::updateOrCreate(
      ['product_type_id' => $vest->id, 'code' => 'pocket'],
      ['name' => 'Pocket', 'name_vi' => 'Túi', 'sort_order' => 3, 'is_active' => true]
    );
    $this->createOptions($pocket, [
      ['name' => 'Welt', 'name_vi' => 'Túi hàn', 'model_path' => '3d-models/Vest/Pocket/Welt.gltf', 'is_default' => true],
      ['name' => 'Flap', 'name_vi' => 'Túi có nắp', 'model_path' => '3d-models/Vest/Pocket/Flap.gltf'],
    ]);

    // Back
    $back = PartCategory3D::updateOrCreate(
      ['product_type_id' => $vest->id, 'code' => 'back'],
      ['name' => 'Back', 'name_vi' => 'Lưng', 'sort_order' => 4, 'is_active' => true]
    );
    $this->createOptions($back, [
      ['name' => 'Same Fabric', 'name_vi' => 'Cùng vải', 'model_path' => '3d-models/Vest/Back/Same.gltf', 'is_default' => true],
      ['name' => 'Lining', 'name_vi' => 'Vải lót', 'model_path' => '3d-models/Vest/Back/Lining.gltf'],
    ]);

    $this->line("  ✅ Seeded Vest with 4 categories");
  }

  /**
   * Create options for a category
   */
  protected function createOptions(PartCategory3D $category, array $options): void
  {
    $order = 1;
    foreach ($options as $option) {
      PartOption3D::updateOrCreate(
        [
          'part_category_id' => $category->id,
          'code' => \Str::slug($option['name']),
        ],
        [
          'name' => $option['name'],
          'name_vi' => $option['name_vi'] ?? $option['name'],
          'model_file' => $option['model_path'],
          'sort_order' => $order++,
          'is_default' => $option['is_default'] ?? false,
          'is_active' => true,
        ]
      );
    }
  }

  /**
   * Seed fabrics from downloaded JSON data
   */
  protected function seedFabrics(): void
  {
    $jsonPath = '3d-models/fabrics-data.json';

    if (!Storage::disk('public')->exists($jsonPath)) {
      $this->warn("  No fabric data found to seed");
      return;
    }

    // Get or create a default fabric category
    $defaultCategory = \App\Models\FabricCategory::firstOrCreate(
      ['slug' => 'imported'],
      ['name' => 'Imported', 'name_vi' => 'Vải nhập khẩu', 'sort_order' => 1, 'is_active' => true]
    );

    $fabrics = json_decode(Storage::disk('public')->get($jsonPath), true);
    $count = 0;

    foreach ($fabrics as $fabricId => $fabricData) {
      $filename = $fabricData['product_texture']['filename'] ?? null;
      if (!$filename) continue;

      Fabric::updateOrCreate(
        ['code' => $fabricData['sku']],
        [
          'fabric_category_id' => $defaultCategory->id,
          'name' => $fabricData['title'],
          'name_vi' => $fabricData['title'],
          'slug' => \Str::slug($fabricData['sku']),
          'description' => $fabricData['field_product_description'] ?? null,
          'description_vi' => $fabricData['field_product_description'] ?? null,
          'material_composition' => $fabricData['field_product_material'] ?? null,
          'weight' => (int)($fabricData['field_product_weight'] ?? 0),
          'color_hex' => $fabricData['field_product_color']['rgb'] ?? null,
          'texture_image' => "fabrics/textures/{$filename}",
          'thumbnail' => "fabrics/thumbnails/thumb_{$filename}",
          'price_modifier' => 99,
          'is_active' => true,
        ]
      );
      $count++;
    }

    $this->line("  ✅ Seeded {$count} fabrics from Dunnio data");
  }
}
