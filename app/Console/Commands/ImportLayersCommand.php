<?php

namespace App\Console\Commands;

use App\Models\SuitModel;
use App\Models\Fabric;
use App\Models\SuitLayer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ImportLayersCommand extends Command
{
  protected $signature = 'layers:import {dataFile}';
  protected $description = 'Import generated layers from JSON data file to database';

  public function handle()
  {
    $dataFile = $this->argument('dataFile');

    if (!file_exists($dataFile)) {
      $this->error("Data file not found: {$dataFile}");
      return 1;
    }

    $this->info('📥 Importing layers to database...');

    $data = json_decode(file_get_contents($dataFile), true);
    $totalLayers = 0;
    $bar = $this->output->createProgressBar(count($data));

    foreach ($data as $result) {
      $modelName = $result['modelName'];
      $fabricName = $result['fabricName'];
      $layers = $result['layers'];

      // Find or create suit model
      $suitModel = SuitModel::where('slug', $modelName)->first();
      if (!$suitModel) {
        $this->warn("Suit model not found: {$modelName}, skipping...");
        continue;
      }

      // Find fabric by code or name
      $fabric = Fabric::where('code', $fabricName)
        ->orWhere('slug', $fabricName)
        ->first();
      if (!$fabric) {
        $this->warn("Fabric not found: {$fabricName}, skipping...");
        continue;
      }

      // Import layers
      foreach ($layers as $layer) {
        SuitLayer::updateOrCreate(
          [
            'suit_model_id' => $suitModel->id,
            'fabric_id' => $fabric->id,
            'view' => $layer['view'],
            'part' => $layer['part'],
          ],
          [
            'image_path' => '/images/configurator/generated/'
              . $modelName . '/'
              . $fabricName . '/'
              . $layer['filename'],
            'z_index' => $layer['z_index'],
            'is_active' => true,
          ]
        );
        $totalLayers++;
      }

      $bar->advance();
    }

    $bar->finish();
    $this->newLine(2);
    $this->info("✅ Successfully imported {$totalLayers} layers!");

    return 0;
  }
}
