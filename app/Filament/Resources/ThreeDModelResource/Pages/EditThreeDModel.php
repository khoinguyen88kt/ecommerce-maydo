<?php

namespace App\Filament\Resources\ThreeDModelResource\Pages;

use App\Filament\Resources\ThreeDModelResource;
use App\Filament\Resources\ThreeDModelResource\Widgets\ThreeDViewer;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditThreeDModel extends EditRecord
{
  protected static string $resource = ThreeDModelResource::class;

  protected function getHeaderActions(): array
  {
    return [
      Actions\DeleteAction::make(),
    ];
  }

  protected function getFooterWidgets(): array
  {
    return [
      ThreeDViewer::make(['record' => $this->record]),
    ];
  }

  public function getFooterWidgetsColumns(): int|array
  {
    return 1;
  }
}
