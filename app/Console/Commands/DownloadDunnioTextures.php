<?php

namespace App\Console\Commands;

use App\Models\Fabric;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DownloadDunnioTextures extends Command
{
  protected $signature = 'fabrics:download-dunnio {--limit=20 : Number of textures to download}';
  protected $description = 'Download fabric textures from Dunnio Tailor and update database';

  // Texture files from dunniotailor.com
  private array $dunnioTextures = [
    ['file' => 'lu1m14.jpg', 'color' => '#20124d', 'name' => 'Navy Blue Twill'],
    ['file' => 'lu1m15.jpg', 'color' => '#000000', 'name' => 'Black Twill'],
    ['file' => 'lu1m16.jpg', 'color' => '#bcbcbc', 'name' => 'Light Grey Twill'],
    ['file' => 'lu1m20.jpg', 'color' => '#4c1130', 'name' => 'Burgundy Twill'],
    ['file' => 'lu1m21.jpg', 'color' => '#4c1130', 'name' => 'Deep Burgundy'],
    ['file' => 'lu1m26.jpg', 'color' => '#d5a6bd', 'name' => 'Pink Twill'],
    ['file' => 'lu1m27.jpg', 'color' => '#b6d7a8', 'name' => 'Sage Green'],
    ['file' => 'lu1m7.jpg', 'color' => '#ccb59d', 'name' => 'Beige Tan'],
    ['file' => 'sp1m120.jpg', 'color' => '#351c75', 'name' => 'Deep Purple'],
    ['file' => 'sp1m15.jpg', 'color' => '#9fc5e8', 'name' => 'Light Blue Oxford'],
    ['file' => 'sp1m16.jpg', 'color' => '#9fc5e8', 'name' => 'Sky Blue Oxford'],
    ['file' => 'sp1m19.jpg', 'color' => '#cfe2f3', 'name' => 'Ice Blue Chambray'],
    ['file' => 'sp1m29.jpg', 'color' => '#444444', 'name' => 'Charcoal Barathea'],
    ['file' => 'sp1m39.jpg', 'color' => '#000000', 'name' => 'Black Classic'],
    ['file' => 'sp1m47.jpg', 'color' => '#2a0e66', 'name' => 'Midnight Purple'],
    ['file' => 'sp1m48.jpg', 'color' => '#2a0e66', 'name' => 'Deep Violet'],
    ['file' => 'sp1m5.jpg', 'color' => '#4c1130', 'name' => 'Wine Red'],
    ['file' => 'sp1m56.jpg', 'color' => '#000000', 'name' => 'Jet Black'],
    ['file' => 'sp1m6.jpg', 'color' => '#990000', 'name' => 'Crimson Red'],
    ['file' => 'sp1m61.jpg', 'color' => '#6a329f', 'name' => 'Royal Purple'],
    ['file' => 'sp1m62.jpg', 'color' => '#0b5394', 'name' => 'Royal Blue'],
    ['file' => 'sp1m7.jpg', 'color' => '#134f5c', 'name' => 'Teal Green'],
    ['file' => 'sp1m80_0.jpg', 'color' => '#3d85c6', 'name' => 'Steel Blue'],
    ['file' => 'sp1m91.jpg', 'color' => '#cc0000', 'name' => 'Bright Red'],
    ['file' => 'sp1m93.jpg', 'color' => '#674ea7', 'name' => 'Amethyst'],
    ['file' => 'sp1m94.jpg', 'color' => '#0b5394', 'name' => 'Navy Blue'],
    ['file' => 'no1m3.jpg', 'color' => '#741b47', 'name' => 'Plum'],
    ['file' => 'no1m58.jpg', 'color' => '#0b5394', 'name' => 'Ocean Blue'],
    ['file' => 'no1m62.jpg', 'color' => '#cfe2f3', 'name' => 'Powder Blue'],
    ['file' => 'no1m67.jpg', 'color' => '#5b5b5b', 'name' => 'Medium Grey'],
    ['file' => 'ro1m44.jpg', 'color' => '#444444', 'name' => 'Slate Grey'],
    ['file' => 'ro1m46.jpg', 'color' => '#444444', 'name' => 'Graphite'],
    ['file' => 'ro1m52.jpg', 'color' => '#021523', 'name' => 'Midnight Blue'],
    ['file' => 'ro1m57.jpg', 'color' => '#444444', 'name' => 'Stone Grey'],
    ['file' => 'ro1m61.jpg', 'color' => '#073763', 'name' => 'Deep Navy'],
    ['file' => 'ro1m66.jpg', 'color' => '#16537e', 'name' => 'Marine Blue'],
    ['file' => 'ro1m67.jpg', 'color' => '#0b5394', 'name' => 'Cobalt Blue'],
    ['file' => 'ro1m73.jpg', 'color' => '#16537e', 'name' => 'Admiral Blue'],
    ['file' => 'ro1m82.jpg', 'color' => '#073763', 'name' => 'Prussian Blue'],
    ['file' => 'nhc33.jpg', 'color' => '#000000', 'name' => 'Black Denim'],
    ['file' => 'nhc34.jpg', 'color' => '#05233e', 'name' => 'Dark Indigo Denim'],
    ['file' => 'nhc37.jpg', 'color' => '#022341', 'name' => 'Deep Blue Denim'],
    ['file' => 'nhc38.jpg', 'color' => '#3b0e1a', 'name' => 'Maroon Denim'],
    ['file' => 'nhc40.jpg', 'color' => '#021220', 'name' => 'Dark Navy Khaki'],
    ['file' => 'nhc43.jpg', 'color' => '#783f04', 'name' => 'Brown Khaki'],
  ];

  public function handle()
  {
    $limit = (int) $this->option('limit');
    $textures = array_slice($this->dunnioTextures, 0, $limit);
    $fabrics = Fabric::orderBy('id')->limit($limit)->get();

    $this->info("Downloading {$limit} textures from Dunnio Tailor...");
    $this->info("Updating {$fabrics->count()} fabrics in database...");

    $bar = $this->output->createProgressBar(count($textures));
    $bar->start();

    $success = 0;
    $failed = 0;

    foreach ($textures as $index => $texture) {
      try {
        $url = 'https://dunniotailor.com/sites/default/files/' . $texture['file'];
        $filename = $texture['file'];
        $localPath = 'fabrics/textures/' . $filename;

        // Download image
        $response = Http::timeout(30)->get($url);

        if ($response->successful()) {
          // Save to storage
          Storage::disk('public')->put($localPath, $response->body());

          // Update corresponding fabric if exists
          if (isset($fabrics[$index])) {
            $fabric = $fabrics[$index];
            $fabric->texture_image = $localPath;
            $fabric->thumbnail = $localPath;
            $fabric->color_hex = $texture['color'];
            $fabric->save();

            $this->line("\n  Updated fabric: {$fabric->name} -> {$texture['name']}");
          }

          $success++;
        } else {
          $this->error("\n  Failed to download: {$url} - HTTP {$response->status()}");
          $failed++;
        }
      } catch (\Exception $e) {
        $this->error("\n  Error: {$e->getMessage()}");
        $failed++;
      }

      $bar->advance();
    }

    $bar->finish();
    $this->newLine(2);

    $this->info("✅ Successfully downloaded: {$success}");
    if ($failed > 0) {
      $this->warn("❌ Failed: {$failed}");
    }

    $this->info("\nTextures saved to: storage/app/public/fabrics/textures/");

    return Command::SUCCESS;
  }
}
