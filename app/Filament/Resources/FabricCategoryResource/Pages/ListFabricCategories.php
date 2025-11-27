<?php

namespace App\Filament\Resources\FabricCategoryResource\Pages;

use App\Filament\Resources\FabricCategoryResource;
use Filament\Resources\Pages\ListRecords;

class ListFabricCategories extends ListRecords
{
  protected static string $resource = FabricCategoryResource::class;

  protected function getHeaderActions(): array
  {
  return [
      \Filament\Actions\CreateAction::make(),
  ];
  }
}
