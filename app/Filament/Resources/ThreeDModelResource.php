<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ThreeDModelResource\Pages;
use App\Models\ThreeDModel;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Process;

class ThreeDModelResource extends Resource
{
  protected static ?string $model = ThreeDModel::class;

  protected static ?string $navigationIcon = 'heroicon-o-cube';

  protected static ?string $navigationGroup = 'Quản lý 3D';

  protected static ?string $navigationLabel = '3D Models';

  protected static ?string $modelLabel = '3D Model';

  protected static ?string $pluralModelLabel = '3D Models';

  // Disable policy checks temporarily for testing
  public static function canViewAny(): bool
  {
    return true;
  }

  public static function canCreate(): bool
  {
    return true;
  }

  public static function canEdit($record): bool
  {
    return true;
  }

  public static function canDelete($record): bool
  {
    return true;
  }

  public static function form(Form $form): Form
  {
    return $form
      ->schema([
        // 3D Viewer Section (only show when editing)
        Forms\Components\Section::make('3D Model Viewer')
          ->schema([
            Forms\Components\View::make('filament.components.three-d-viewer')
              ->viewData(['record' => $form->getRecord()]),
          ])
          ->visible(fn($record) => $record !== null && $record->glb_file)
          ->collapsible()
          ->collapsed(false),

        Forms\Components\Section::make('Model Information')
          ->schema([
            Forms\Components\Select::make('suit_model_id')
              ->label('Suit Model')
              ->relationship('suitModel', 'name_vi')
              ->required()
              ->searchable(),

            Forms\Components\FileUpload::make('glb_file')
              ->label('GLB Model File')
              ->disk('local')
              ->directory('3d-models')
              ->acceptedFileTypes(['model/gltf-binary', 'application/octet-stream'])
              ->maxSize(50 * 1024) // 50MB
              ->required()
              ->helperText('Upload GLB 3D model file (max 50MB)'),

            Forms\Components\FileUpload::make('preview_image')
              ->label('Preview Image')
              ->image()
              ->disk('public')
              ->directory('3d-models/previews')
              ->helperText('Optional preview image'),
          ])->columns(1),

        Forms\Components\Section::make('Model Configuration')
          ->schema([
            Forms\Components\KeyValue::make('parts_mapping')
              ->label('Parts Mapping')
              ->keyLabel('Mesh Name')
              ->valueLabel('Part Name')
              ->helperText('Map 3D mesh names to part names (e.g., "Jacket_Body" => "jacket_body")'),

            Forms\Components\Textarea::make('notes')
              ->label('Notes')
              ->rows(3),
          ])->columns(1),

        Forms\Components\Section::make('Generation Status')
          ->schema([
            Forms\Components\Toggle::make('is_processed')
              ->label('Layers Generated')
              ->disabled(),

            Forms\Components\DateTimePicker::make('processed_at')
              ->label('Processed At')
              ->disabled(),

            Forms\Components\TextInput::make('layers_count')
              ->label('Generated Layers Count')
              ->disabled(),
          ])->columns(3)
          ->visible(fn($record) => $record !== null),
      ]);
  }

  public static function table(Table $table): Table
  {
    return $table
      ->columns([
        Tables\Columns\ImageColumn::make('preview_image')
          ->label('Preview')
          ->height(80),

        Tables\Columns\TextColumn::make('suitModel.name_vi')
          ->label('Suit Model')
          ->searchable()
          ->sortable(),

        Tables\Columns\TextColumn::make('glb_file')
          ->label('GLB File')
          ->formatStateUsing(fn($state) => basename($state)),

        Tables\Columns\IconColumn::make('is_processed')
          ->label('Processed')
          ->boolean(),

        Tables\Columns\TextColumn::make('layers_count')
          ->label('Layers')
          ->sortable(),

        Tables\Columns\TextColumn::make('created_at')
          ->label('Created')
          ->dateTime('d/m/Y H:i')
          ->sortable(),
      ])
      ->filters([
        Tables\Filters\SelectFilter::make('suit_model_id')
          ->relationship('suitModel', 'name_vi')
          ->label('Suit Model'),

        Tables\Filters\TernaryFilter::make('is_processed')
          ->label('Processed'),
      ])
      ->actions([
        Tables\Actions\Action::make('generate_layers')
          ->label('Generate Layers')
          ->icon('heroicon-o-photo')
          ->color('success')
          ->requiresConfirmation()
          ->modalHeading('Generate Layer Images')
          ->modalDescription('This will generate 2D layer images from the 3D model for all fabrics. This may take several minutes.')
          ->action(function (ThreeDModel $record) {
            try {
              // Run Node.js script
              $result = Process::path(base_path('scripts'))
                ->run([
                  'node',
                  'batch-generate.js',
                  '--model=' . storage_path('app/' . $record->glb_file)
                ]);

              if ($result->successful()) {
                $record->update([
                  'is_processed' => true,
                  'processed_at' => now(),
                ]);

                Notification::make()
                  ->success()
                  ->title('Layers generated successfully!')
                  ->send();
              } else {
                throw new \Exception($result->errorOutput());
              }
            } catch (\Exception $e) {
              Notification::make()
                ->danger()
                ->title('Generation failed')
                ->body($e->getMessage())
                ->send();
            }
          })
          ->visible(fn(ThreeDModel $record) => !$record->is_processed),

        Tables\Actions\EditAction::make(),
        Tables\Actions\DeleteAction::make(),
      ])
      ->bulkActions([
        Tables\Actions\BulkActionGroup::make([
          Tables\Actions\DeleteBulkAction::make(),
        ]),
      ]);
  }

  public static function getRelations(): array
  {
    return [
      //
    ];
  }

  public static function getPages(): array
  {
    return [
      'index' => Pages\ListThreeDModels::route('/'),
      'create' => Pages\CreateThreeDModel::route('/create'),
      'edit' => Pages\EditThreeDModel::route('/{record}/edit'),
    ];
  }
}
