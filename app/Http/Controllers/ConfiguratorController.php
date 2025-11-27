<?php

namespace App\Http\Controllers;

use App\Models\Fabric;
use App\Models\FabricCategory;
use App\Models\OptionType;
use App\Models\SuitConfiguration;
use App\Models\SuitLayer;
use App\Models\SuitModel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ConfiguratorController extends Controller
{
  /**
   * Display the configurator page
   */
  public function index()
  {
  $suitModels = SuitModel::where('is_active', true)
      ->orderBy('sort_order')
      ->get();

  $fabricCategories = FabricCategory::where('is_active', true)
      ->orderBy('sort_order')
      ->get();

  $fabrics = Fabric::with('category')
      ->where('is_active', true)
      ->orderBy('sort_order')
      ->get();

  $optionTypes = OptionType::with(['values' => function ($query) {
      $query->where('is_active', true)->orderBy('sort_order');
  }])
      ->where('is_active', true)
      ->where('slug', '!=', 'lining') // Exclude lining from main options
      ->orderBy('sort_order')
      ->get();

  // Get linings separately
  $linings = Fabric::where('is_active', true)
      ->where('is_lining', true)
      ->orderBy('sort_order')
      ->get();

  return view('configurator.index', compact(
      'suitModels',
      'fabricCategories',
      'fabrics',
      'optionTypes',
      'linings'
  ));
  }

  /**
   * Get layers for the current configuration (API)
   */
  public function getLayers(Request $request)
  {
  $validated = $request->validate([
      'model_id' => 'required|exists:suit_models,id',
      'fabric_id' => 'nullable|exists:fabrics,id',
      'view' => 'required|in:front,side,back',
      'options' => 'array',
  ]);

  $modelId = $validated['model_id'];
  $fabricId = $validated['fabric_id'] ?? null;
  $view = $validated['view'];
  $options = $validated['options'] ?? [];

  // Build the layer URLs based on configuration
  $layers = $this->buildLayers($modelId, $fabricId, $view, $options);

  return response()->json([
      'success' => true,
      'layers' => $layers
  ]);
  }

  /**
   * Build layer image paths based on configuration
   */
  private function buildLayers($modelId, $fabricId, $view, $options)
  {
  // Get base layers (không cần fabric, như shirt/shadow)
  // Exclude option-specific layers (part ends with _opt)
  $baseLayers = SuitLayer::where('suit_model_id', $modelId)
      ->whereNull('fabric_id')
      ->whereNull('option_value_slug')
      ->where('view', $view)
      ->where('is_active', true)
      ->orderBy('z_index')
      ->get();

  // Get fabric-specific layers (jacket, pants với fabric cụ thể)
  // Only get layers without option_value_slug (default layers)
  $fabricLayers = collect();
  if ($fabricId) {
      $fabricLayers = SuitLayer::where('suit_model_id', $modelId)
    ->where('fabric_id', $fabricId)
    ->whereNull('option_value_slug')
    ->where('view', $view)
    ->where('is_active', true)
    ->orderBy('z_index')
    ->get();
  }

  // Get option-based layers by looking up option value slug
  $optionLayers = collect();
  $replacedParts = []; // Track which parts are replaced by options

  if (!empty($options)) {
      foreach ($options as $optionSlug => $valueId) {
    // Get the option value to find its slug
    $optionValue = \App\Models\OptionValue::find($valueId);
    if ($optionValue) {
          // Get option layers - check for fabric-specific first, then shared
          $optLayers = SuitLayer::where('suit_model_id', $modelId)
      ->where('view', $view)
      ->where('option_value_slug', $optionValue->slug)
      ->where(function($q) use ($fabricId) {
              $q->where('fabric_id', $fabricId)
        ->orWhereNull('fabric_id');
      })
      ->where('is_active', true)
      ->orderBy('z_index')
      ->get();

          // Group by base part name (remove _opt suffix)
          $grouped = $optLayers->groupBy(function($l) {
      return str_replace('_opt', '', $l->part);
          });

          foreach ($grouped as $basePart => $layers) {
      // Prefer fabric-specific layer
      $layer = $layers->firstWhere('fabric_id', $fabricId) ?? $layers->first();
      if ($layer) {
              $optionLayers->push($layer);
              // Add both the base part name and the _opt version to replaced parts
              $replacedParts[] = $basePart;
      }
          }
    }
      }
  }

  // Make replacedParts unique
  $replacedParts = array_unique($replacedParts);

  // Check if mandarin collar is selected - if so, remove lapel and collar
  $partsToHide = [];
  if (!empty($options)) {
      foreach ($options as $optionSlug => $valueId) {
    $optionValue = \App\Models\OptionValue::find($valueId);
    if ($optionValue && $optionValue->slug === 'mandarin') {
          // Mandarin collar has no lapel or collar
          $partsToHide = ['lapel', 'collar'];
          break;
    }
      }
  }

  // Filter out base/fabric layers that have been replaced by options or should be hidden
  $filteredBaseLayers = $baseLayers->filter(function($layer) use ($replacedParts, $partsToHide) {
      $basePart = str_replace('_opt', '', $layer->part);
      if (in_array($basePart, $partsToHide)) {
    return false;
      }
      return !in_array($basePart, $replacedParts);
  });

  $filteredFabricLayers = $fabricLayers->filter(function($layer) use ($replacedParts, $partsToHide) {
      $basePart = str_replace('_opt', '', $layer->part);
      if (in_array($basePart, $partsToHide)) {
    return false;
      }
      return !in_array($basePart, $replacedParts);
  });

  // Merge all layers and sort by z_index
  $allLayers = $filteredBaseLayers
      ->merge($filteredFabricLayers)
      ->merge($optionLayers)
      ->sortBy('z_index')
      ->values()
      ->map(function ($layer) {
    return [
          'id' => $layer->id,
          'part' => str_replace('_opt', '', $layer->part),
          'image_path' => $layer->image_url,
          'z_index' => $layer->z_index,
    ];
      });

  return $allLayers;
  }

  /**
   * Save configuration and generate share code
   */
  public function save(Request $request)
  {
  $validated = $request->validate([
      'model_id' => 'required|exists:suit_models,id',
      'fabric_id' => 'required|exists:fabrics,id',
      'lining_id' => 'nullable|exists:fabrics,id',
      'options' => 'array',
      'price' => 'required|numeric|min:0',
  ]);

  // Generate unique share code
  $shareCode = Str::random(8);
  while (SuitConfiguration::where('share_code', $shareCode)->exists()) {
      $shareCode = Str::random(8);
  }

  $configuration = SuitConfiguration::create([
      'user_id' => auth()->id(),
      'suit_model_id' => $validated['model_id'],
      'fabric_id' => $validated['fabric_id'],
      'lining_fabric_id' => $validated['lining_id'],
      'options_data' => $validated['options'] ?? [],
      'total_price' => $validated['price'],
      'share_code' => $shareCode,
  ]);

  return response()->json([
      'success' => true,
      'share_code' => $shareCode,
      'configuration_id' => $configuration->id,
  ]);
  }

  /**
   * Load shared configuration
   */
  public function share($code)
  {
  $configuration = SuitConfiguration::where('share_code', $code)->firstOrFail();

  // Get all data needed for the configurator
  $suitModels = SuitModel::where('is_active', true)
      ->orderBy('sort_order')
      ->get();

  $fabricCategories = FabricCategory::where('is_active', true)
      ->orderBy('sort_order')
      ->get();

  $fabrics = Fabric::with('category')
      ->where('is_active', true)
      ->orderBy('sort_order')
      ->get();

  $optionTypes = OptionType::with(['values' => function ($query) {
      $query->where('is_active', true)->orderBy('sort_order');
  }])
      ->where('is_active', true)
      ->where('slug', '!=', 'lining')
      ->orderBy('sort_order')
      ->get();

  $linings = Fabric::where('is_active', true)
      ->where('is_lining', true)
      ->orderBy('sort_order')
      ->get();

  return view('configurator.index', compact(
      'suitModels',
      'fabricCategories',
      'fabrics',
      'optionTypes',
      'linings',
      'configuration'
  ));
  }
}
