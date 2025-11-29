<?php

namespace App\Filament\Resources\ThreeDModelResource\Widgets;

use Filament\Widgets\Widget;
use App\Models\ThreeDModel;

class ThreeDViewer extends Widget
{
  protected static string $view = 'filament.widgets.three-d-viewer';

  public ?ThreeDModel $record = null;

  protected int | string | array $columnSpan = 'full';

  public function mount(?ThreeDModel $record = null): void
  {
    $this->record = $record;
  }

  public function getGlbUrl(): ?string
  {
    if (!$this->record || !$this->record->glb_file) {
      return null;
    }

    // Serve from storage
    return route('glb.serve', ['path' => $this->record->glb_file]);
  }
}
