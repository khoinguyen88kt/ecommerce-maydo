<?php

namespace App\Filament\Resources\FabricResource\Pages;

use App\Filament\Resources\FabricResource;
use Filament\Resources\Pages\ListRecords;

class ListFabrics extends ListRecords
{
  protected static string $resource = FabricResource::class;

  protected function getHeaderActions(): array
  {
  return [
      \Filament\Actions\CreateAction::make(),
  ];
  }
}
