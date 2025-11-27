<?php

namespace App\Filament\Resources\FabricCategoryResource\Pages;

use App\Filament\Resources\FabricCategoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFabricCategory extends CreateRecord
{
  protected static string $resource = FabricCategoryResource::class;

  protected function getRedirectUrl(): string
  {
  return $this->getResource()::getUrl('index');
  }
}
