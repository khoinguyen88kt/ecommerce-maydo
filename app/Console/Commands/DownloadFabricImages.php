<?php

namespace App\Console\Commands;

use App\Models\Fabric;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DownloadFabricImages extends Command
{
  protected $signature = 'fabrics:download-images';
  protected $description = 'Download external fabric images to local storage and update database';

  public function handle()
  {
    $fabrics = Fabric::whereNotNull('thumbnail')
      ->where('thumbnail', 'like', 'http%')
      ->get();

    $this->info("Found {$fabrics->count()} fabrics with external thumbnails");

    $bar = $this->output->createProgressBar($fabrics->count());
    $bar->start();

    $success = 0;
    $failed = 0;

    foreach ($fabrics as $fabric) {
      try {
        $url = $fabric->thumbnail;

        // Generate local filename
        $extension = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'jpg';
        $filename = Str::slug($fabric->name) . '-' . $fabric->id . '.' . $extension;
        $localPath = 'fabrics/thumbnails/' . $filename;

        // Download image
        $response = Http::timeout(30)->get($url);

        if ($response->successful()) {
          // Save to storage
          Storage::disk('public')->put($localPath, $response->body());

          // Update database
          $fabric->thumbnail = $localPath;

          // Also update texture_image if it's the same or null
          if (empty($fabric->texture_image) || $fabric->texture_image === $fabric->getOriginal('thumbnail')) {
            $fabric->texture_image = $localPath;
          }

          $fabric->save();
          $success++;
        } else {
          $this->error("\nFailed to download: {$url} - HTTP {$response->status()}");
          $failed++;
        }
      } catch (\Exception $e) {
        $this->error("\nError downloading {$fabric->name}: {$e->getMessage()}");
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

    return Command::SUCCESS;
  }
}
