<?php

/**
 * Script to migrate data from SQLite to MySQL
 * Run: php migrate-sqlite-to-mysql.php
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Starting migration from SQLite to MySQL...\n\n";

// Temporarily switch to SQLite
config(['database.default' => 'sqlite']);
config(['database.connections.sqlite.database' => database_path('database.sqlite')]);

$tables = [
  'suit_models',
  'fabric_categories', 
  'fabrics',
  'option_types',
  'option_values',
  'suit_model_option_types',
  'suit_layers',
  'users'
];

// Disable foreign key checks
config(['database.default' => 'mysql']);
DB::connection('mysql')->statement('SET FOREIGN_KEY_CHECKS=0;');

foreach ($tables as $table) {
  echo "Migrating table: {$table}\n";
  
  try {
    // Get data from SQLite
    config(['database.default' => 'sqlite']);
    $records = DB::connection('sqlite')->table($table)->get();
    $count = $records->count();
    
    if ($count === 0) {
      echo "  ⚠️  No data to migrate\n\n";
      continue;
    }
    
    echo "  Found {$count} records\n";
    
    // Switch to MySQL
    config(['database.default' => 'mysql']);
    
    // Truncate MySQL table first (except users if has admin)
    if ($table !== 'users') {
      DB::connection('mysql')->table($table)->truncate();
    } else {
      // Delete non-admin users
      DB::connection('mysql')->table($table)->where('is_admin', 0)->delete();
    }
    
    // Insert in chunks
    $chunkSize = 500;
    $chunks = $records->chunk($chunkSize);
    $processed = 0;
    
    foreach ($chunks as $chunk) {
      $data = $chunk->map(function($record) {
        return (array) $record;
      })->toArray();
      
      DB::connection('mysql')->table($table)->insert($data);
      $processed += count($data);
      echo "  Processed {$processed}/{$count}\n";
    }
    
    echo "  ✅ Completed\n\n";
    
  } catch (\Exception $e) {
    echo "  ❌ Error: " . $e->getMessage() . "\n\n";
  }
}

// Re-enable foreign key checks
DB::connection('mysql')->statement('SET FOREIGN_KEY_CHECKS=1;');

echo "\n✅ Migration completed successfully!\n";
