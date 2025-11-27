<?php

namespace App\Filament\Resources\OptionTypeResource\Pages;

use App\Filament\Resources\OptionTypeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateOptionType extends CreateRecord
{
  protected static string $resource = OptionTypeResource::class;

  protected function getRedirectUrl(): string
  {
  return $this->getResource()::getUrl('index');
  }
}
