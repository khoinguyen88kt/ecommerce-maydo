<?php

namespace App\Filament\Resources\SuitModelResource\Pages;

use App\Filament\Resources\SuitModelResource;
use Filament\Resources\Pages\ListRecords;

class ListSuitModels extends ListRecords
{
  protected static string $resource = SuitModelResource::class;

  protected function getHeaderActions(): array
  {
  return [
      \Filament\Actions\CreateAction::make(),
  ];
  }
}
