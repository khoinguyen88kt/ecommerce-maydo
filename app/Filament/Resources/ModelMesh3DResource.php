<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ModelMesh3DResource\Pages;
use App\Models\ThreeD\ModelMesh3D;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ModelMesh3DResource extends Resource
{
  protected static ?string $model = ModelMesh3D::class;

  protected static ?string $navigationIcon = 'heroicon-o-cube-transparent';

  protected static ?string $navigationGroup = '3D Configurator';

  protected static ?string $navigationLabel = 'Model Meshes';

  protected static ?int $navigationSort = 5;

  protected static ?string $modelLabel = '3D Model Mesh';

  protected static ?string $pluralModelLabel = '3D Model Meshes';

  public static function form(Form $form): Form
  {
    return $form
      ->schema([
        Forms\Components\Section::make('Association')
          ->schema([
            Forms\Components\Select::make('part_option_id')
              ->label('Part Option')
              ->relationship('option', 'name')
              ->searchable()
              ->preload()
              ->helperText('Leave empty if using sub-option'),

            Forms\Components\Select::make('part_sub_option_id')
              ->label('Sub Option')
              ->relationship('subOption', 'name')
              ->searchable()
              ->preload()
              ->helperText('Leave empty if using option'),
          ])
          ->columns(2),

        Forms\Components\Section::make('Mesh Settings')
          ->schema([
            Forms\Components\TextInput::make('mesh_name')
              ->label('Mesh Name')
              ->required()
              ->maxLength(255)
              ->helperText('Exact mesh name from GLB file'),

            Forms\Components\Select::make('material_type')
              ->label('Material Type')
              ->required()
              ->options([
                'fabric' => 'Fabric (Main)',
                'lining' => 'Lining',
                'button' => 'Button',
                'thread' => 'Thread',
                'contrast' => 'Contrast Fabric',
                'metal' => 'Metal',
              ]),

            Forms\Components\Toggle::make('apply_fabric_texture')
              ->label('Apply Fabric Texture')
              ->default(true)
              ->helperText('Apply selected fabric texture to this mesh'),
          ])
          ->columns(3),

        Forms\Components\Section::make('Texture Settings')
          ->schema([
            Forms\Components\KeyValue::make('texture_settings')
              ->label('Texture Settings')
              ->keyLabel('Property')
              ->valueLabel('Value')
              ->default([
                'roughness' => '0.7',
                'metallic' => '0.0',
              ])
              ->helperText('scale.u, scale.v, roughness, metallic, color'),

            Forms\Components\KeyValue::make('uv_transform')
              ->label('UV Transform')
              ->keyLabel('Property')
              ->valueLabel('Value')
              ->helperText('offset.u, offset.v, rotation'),
          ])
          ->columns(2),
      ]);
  }

  public static function table(Table $table): Table
  {
    return $table
      ->columns([
        Tables\Columns\TextColumn::make('option.name')
          ->label('Part Option')
          ->sortable()
          ->searchable()
          ->placeholder('—'),

        Tables\Columns\TextColumn::make('subOption.name')
          ->label('Sub Option')
          ->sortable()
          ->searchable()
          ->placeholder('—'),

        Tables\Columns\TextColumn::make('mesh_name')
          ->label('Mesh Name')
          ->searchable()
          ->sortable(),

        Tables\Columns\TextColumn::make('material_type')
          ->label('Material')
          ->badge()
          ->color(fn(string $state): string => match ($state) {
            'fabric' => 'success',
            'lining' => 'info',
            'button' => 'warning',
            'thread' => 'gray',
            'contrast' => 'danger',
            'metal' => 'primary',
            default => 'gray',
          }),

        Tables\Columns\IconColumn::make('apply_fabric_texture')
          ->label('Fabric Tex.')
          ->boolean(),

        Tables\Columns\TextColumn::make('created_at')
          ->label('Created')
          ->dateTime()
          ->sortable()
          ->toggleable(isToggledHiddenByDefault: true),
      ])
      ->filters([
        Tables\Filters\SelectFilter::make('material_type')
          ->label('Material Type')
          ->options([
            'fabric' => 'Fabric',
            'lining' => 'Lining',
            'button' => 'Button',
            'thread' => 'Thread',
            'contrast' => 'Contrast',
            'metal' => 'Metal',
          ]),

        Tables\Filters\TernaryFilter::make('apply_fabric_texture')
          ->label('Apply Fabric Texture'),
      ])
      ->actions([
        Tables\Actions\EditAction::make(),
        Tables\Actions\DeleteAction::make(),
      ])
      ->bulkActions([
        Tables\Actions\BulkActionGroup::make([
          Tables\Actions\DeleteBulkAction::make(),
        ]),
      ])
      ->modifyQueryUsing(function (Builder $query) {
        if (request()->has('part_option')) {
          $query->where('part_option_id', request('part_option'));
        }
      });
  }

  public static function getRelations(): array
  {
    return [];
  }

  public static function getPages(): array
  {
    return [
      'index' => Pages\ListModelMesh3DS::route('/'),
      'create' => Pages\CreateModelMesh3D::route('/create'),
      'edit' => Pages\EditModelMesh3D::route('/{record}/edit'),
    ];
  }
}
