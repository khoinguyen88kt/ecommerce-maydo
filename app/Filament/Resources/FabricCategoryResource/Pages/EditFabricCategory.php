<?php

namespace App\Filament\Resources\FabricCategoryResource\Pages;

use App\Filament\Resources\FabricCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFabricCategory extends EditRecord
{
  protected static string $resource = FabricCategoryResource::class;

  protected function getHeaderActions(): array
  {
  return [
      Actions\DeleteAction::make(),
  ];
  }

  protected function getRedirectUrl(): string
  {
  return $this->getResource()::getUrl('index');
  }
}
