<?php

namespace App\Filament\Pages;

use App\Models\Fabric;
use App\Models\ThreeD\ProductType3D;
use App\Models\ThreeD\PartCategory3D;
use App\Models\ThreeD\PartOption3D;
use Filament\Pages\Page;
use Illuminate\Support\Collection;

class ThreeDConfiguratorPage extends Page
{
  protected static ?string $navigationIcon = 'heroicon-o-cube-transparent';

  protected static ?string $navigationLabel = '3D Configurator Preview';

  protected static ?string $navigationGroup = '3D Configurator';

  protected static ?int $navigationSort = 10;

  protected static ?string $title = '3D Configurator Preview';

  protected static ?string $slug = '3d-configurator-preview';

  protected static string $view = 'filament.pages.three-d-configurator';

  public Collection $productTypes;
  public Collection $fabrics;
  public ?ProductType3D $selectedProductType = null;
  public array $categories = [];
  public array $currentConfig = [];

  public function mount(): void
  {
    $this->productTypes = ProductType3D::where('is_active', true)
      ->orderBy('sort_order')
      ->get();

    $this->fabrics = Fabric::where('is_active', true)
      ->orderBy('name')
      ->get();

    // Default to first product type
    if ($this->productTypes->isNotEmpty()) {
      $this->selectedProductType = $this->productTypes->first();
      $this->loadCategories();
    }
  }

  public function selectProductType(int $productTypeId): void
  {
    $this->selectedProductType = ProductType3D::find($productTypeId);
    $this->loadCategories();
  }

  protected function loadCategories(): void
  {
    if (!$this->selectedProductType) {
      $this->categories = [];
      return;
    }

    $this->categories = PartCategory3D::with(['options.subOptions'])
      ->where('product_type_id', $this->selectedProductType->id)
      ->where('is_active', true)
      ->orderBy('sort_order')
      ->get()
      ->map(function ($category) {
        return [
          'id' => $category->id,
          'name' => $category->name,
          'name_vi' => $category->name_vi,
          'code' => $category->code,
          'display_type' => $category->display_type,
          'icon_path' => $category->icon_path,
          'options' => $category->options->map(function ($option) {
            return [
              'id' => $option->id,
              'name' => $option->name,
              'name_vi' => $option->name_vi,
              'code' => $option->code,
              'thumbnail_url' => $option->thumbnail_url,
              'icon_path' => $option->icon_path,
              'is_default' => $option->is_default,
              'price_modifier' => $option->price_modifier,
              'model_file' => $option->model_file,
              'sub_options' => $option->subOptions->map(function ($sub) {
                return [
                  'id' => $sub->id,
                  'name' => $sub->name,
                  'code' => $sub->code,
                  'value' => $sub->value,
                ];
              })->toArray(),
            ];
          })->toArray(),
        ];
      })
      ->toArray();

    // Set default config
    $this->currentConfig = [];
    foreach ($this->categories as $category) {
      $defaultOption = collect($category['options'])->firstWhere('is_default', true);
      if ($defaultOption) {
        $this->currentConfig[$category['code']] = $defaultOption['code'];
      } elseif (!empty($category['options'])) {
        $this->currentConfig[$category['code']] = $category['options'][0]['code'];
      }
    }
  }

  public function getConfigDataProperty(): array
  {
    return [
      'productTypes' => $this->productTypes->map(function ($productType) {
        return [
          'id' => $productType->id,
          'name' => $productType->name,
          'name_vi' => $productType->name_vi,
          'code' => $productType->code,
          'base_path' => $productType->base_path,
        ];
      })->toArray(),
      'productType' => $this->selectedProductType ? [
        'id' => $this->selectedProductType->id,
        'name' => $this->selectedProductType->name,
        'code' => $this->selectedProductType->code,
        'base_path' => $this->selectedProductType->base_path,
      ] : null,
      'categories' => $this->categories,
      'currentConfig' => $this->currentConfig,
      'fabrics' => $this->fabrics->map(function ($fabric) {
        return [
          'id' => $fabric->id,
          'name' => $fabric->name,
          'code' => $fabric->code,
          'color_hex' => $fabric->color_hex,
          'texture_url' => $fabric->texture_image ? '/storage/' . $fabric->texture_image : null,
          'thumbnail_url' => $fabric->thumbnail ? '/storage/' . $fabric->thumbnail : null,
          'price' => $fabric->price_modifier ?? 0,
          'material' => $fabric->material_composition,
        ];
      })->toArray(),
    ];
  }
}
