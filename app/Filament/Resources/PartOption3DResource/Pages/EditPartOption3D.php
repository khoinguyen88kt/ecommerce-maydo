<?php

namespace App\Filament\Resources\PartOption3DResource\Pages;

use App\Filament\Resources\PartOption3DResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPartOption3D extends EditRecord
{
  protected static string $resource = PartOption3DResource::class;

  protected function getHeaderActions(): array
  {
    return [
      Actions\DeleteAction::make(),
    ];
  }
}
