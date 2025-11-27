<?php

namespace App\Filament\Resources\OptionTypeResource\Pages;

use App\Filament\Resources\OptionTypeResource;
use Filament\Resources\Pages\ListRecords;

class ListOptionTypes extends ListRecords
{
  protected static string $resource = OptionTypeResource::class;

  protected function getHeaderActions(): array
  {
  return [
      \Filament\Actions\CreateAction::make(),
  ];
  }
}
