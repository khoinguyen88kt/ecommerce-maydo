<?php

namespace App\Filament\Resources\PartSubOption3DResource\Pages;

use App\Filament\Resources\PartSubOption3DResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPartSubOption3D extends EditRecord
{
  protected static string $resource = PartSubOption3DResource::class;

  protected function getHeaderActions(): array
  {
    return [
      Actions\DeleteAction::make(),
    ];
  }
}
