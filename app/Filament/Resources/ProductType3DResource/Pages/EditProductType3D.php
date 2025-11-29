<?php

namespace App\Filament\Resources\ProductType3DResource\Pages;

use App\Filament\Resources\ProductType3DResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProductType3D extends EditRecord
{
  protected static string $resource = ProductType3DResource::class;

  protected function getHeaderActions(): array
  {
    return [
      Actions\DeleteAction::make(),
    ];
  }
}
