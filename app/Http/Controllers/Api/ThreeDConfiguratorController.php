<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ThreeDConfiguratorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * API Controller for 3D Configurator
 */
class ThreeDConfiguratorController extends Controller
{
  public function __construct(
    protected ThreeDConfiguratorService $configuratorService
  ) {}

  /**
   * Get all product types
   */
  public function productTypes(): JsonResponse
  {
    $types = $this->configuratorService->getProductTypes();

    return response()->json([
      'success' => true,
      'data' => $types,
    ]);
  }

  /**
   * Get configuration data for a product type
   */
  public function getConfiguration(Request $request, string $productType): JsonResponse
  {
    try {
      $data = $this->configuratorService->getConfigurationData(
        $productType,
        $request->input('fabric_id')
      );

      return response()->json([
        'success' => true,
        'data' => $data,
      ]);
    } catch (\InvalidArgumentException $e) {
      return response()->json([
        'success' => false,
        'error' => $e->getMessage(),
      ], 404);
    }
  }

  /**
   * Get model files for specific configuration
   */
  public function getModelFiles(Request $request, string $productType): JsonResponse
  {
    $request->validate([
      'config' => 'required|array',
    ]);

    try {
      $files = $this->configuratorService->getModelFiles(
        $productType,
        $request->input('config')
      );

      return response()->json([
        'success' => true,
        'data' => $files,
      ]);
    } catch (\InvalidArgumentException $e) {
      return response()->json([
        'success' => false,
        'error' => $e->getMessage(),
      ], 404);
    }
  }

  /**
   * Update configuration option
   */
  public function updateOption(Request $request, string $productType): JsonResponse
  {
    $request->validate([
      'config' => 'required|array',
      'category' => 'required|string',
      'option' => 'required|string',
    ]);

    try {
      $result = $this->configuratorService->updateOption(
        $productType,
        $request->input('config'),
        $request->input('category'),
        $request->input('option')
      );

      return response()->json([
        'success' => true,
        'data' => $result,
      ]);
    } catch (\InvalidArgumentException $e) {
      return response()->json([
        'success' => false,
        'error' => $e->getMessage(),
      ], 400);
    }
  }

  /**
   * Calculate price for configuration
   */
  public function calculatePrice(Request $request, string $productType): JsonResponse
  {
    $request->validate([
      'config' => 'required|array',
      'fabric_id' => 'nullable|integer|exists:fabrics,id',
    ]);

    try {
      $price = $this->configuratorService->calculatePrice(
        $productType,
        $request->input('config'),
        $request->input('fabric_id')
      );

      return response()->json([
        'success' => true,
        'data' => $price,
      ]);
    } catch (\InvalidArgumentException $e) {
      return response()->json([
        'success' => false,
        'error' => $e->getMessage(),
      ], 404);
    }
  }

  /**
   * Save configuration
   */
  public function saveConfiguration(Request $request, string $productType): JsonResponse
  {
    $request->validate([
      'config' => 'required|array',
      'fabric_id' => 'nullable|integer|exists:fabrics,id',
      'name' => 'nullable|string|max:255',
    ]);

    try {
      $configuration = $this->configuratorService->saveConfiguration(
        $productType,
        $request->input('config'),
        $request->input('fabric_id'),
        $request->user()?->id,
        $request->input('name')
      );

      return response()->json([
        'success' => true,
        'data' => $configuration,
      ]);
    } catch (\InvalidArgumentException $e) {
      return response()->json([
        'success' => false,
        'error' => $e->getMessage(),
      ], 400);
    }
  }

  /**
   * Get available fabrics
   */
  public function getFabrics(string $productType): JsonResponse
  {
    try {
      $fabrics = $this->configuratorService->getAvailableFabrics($productType);

      return response()->json([
        'success' => true,
        'data' => $fabrics,
      ]);
    } catch (\InvalidArgumentException $e) {
      return response()->json([
        'success' => false,
        'error' => $e->getMessage(),
      ], 404);
    }
  }
}
