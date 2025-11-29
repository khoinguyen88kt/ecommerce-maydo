<?php

namespace App\Filament\Resources\ModelMesh3DResource\Pages;

use App\Filament\Resources\ModelMesh3DResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListModelMesh3DS extends ListRecords
{
  protected static string $resource = ModelMesh3DResource::class;

  protected function getHeaderActions(): array
  {
    return [
      Actions\CreateAction::make(),
    ];
  }
}
