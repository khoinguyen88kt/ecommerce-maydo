<?php

namespace App\Filament\Resources\ThreeDModelResource\Pages;

use App\Filament\Resources\ThreeDModelResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListThreeDModels extends ListRecords
{
  protected static string $resource = ThreeDModelResource::class;

  protected function getHeaderActions(): array
  {
    return [
      Actions\CreateAction::make(),
    ];
  }
}
