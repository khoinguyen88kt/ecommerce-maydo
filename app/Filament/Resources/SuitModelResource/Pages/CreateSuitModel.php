<?php

namespace App\Filament\Resources\SuitModelResource\Pages;

use App\Filament\Resources\SuitModelResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSuitModel extends CreateRecord
{
  protected static string $resource = SuitModelResource::class;

  protected function getRedirectUrl(): string
  {
  return $this->getResource()::getUrl('index');
  }
}
