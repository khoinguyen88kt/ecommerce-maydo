<?php

namespace App\Filament\Resources\PartOption3DResource\Pages;

use App\Filament\Resources\PartOption3DResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPartOption3DS extends ListRecords
{
  protected static string $resource = PartOption3DResource::class;

  protected function getHeaderActions(): array
  {
    return [
      Actions\CreateAction::make(),
    ];
  }
}
