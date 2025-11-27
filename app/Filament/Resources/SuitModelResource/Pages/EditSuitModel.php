<?php

namespace App\Filament\Resources\SuitModelResource\Pages;

use App\Filament\Resources\SuitModelResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSuitModel extends EditRecord
{
  protected static string $resource = SuitModelResource::class;

  protected function getHeaderActions(): array
  {
  return [
      Actions\DeleteAction::make(),
  ];
  }

  protected function getRedirectUrl(): string
  {
  return $this->getResource()::getUrl('index');
  }
}
