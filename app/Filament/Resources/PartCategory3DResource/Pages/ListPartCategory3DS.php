<?php

namespace App\Filament\Resources\PartCategory3DResource\Pages;

use App\Filament\Resources\PartCategory3DResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPartCategory3DS extends ListRecords
{
  protected static string $resource = PartCategory3DResource::class;

  protected function getHeaderActions(): array
  {
    return [
      Actions\CreateAction::make(),
    ];
  }
}
