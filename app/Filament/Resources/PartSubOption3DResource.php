<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PartSubOption3DResource\Pages;
use App\Models\ThreeD\PartSubOption3D;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PartSubOption3DResource extends Resource
{
  protected static ?string $model = PartSubOption3D::class;

  protected static ?string $navigationIcon = 'heroicon-o-adjustments-horizontal';

  protected static ?string $navigationGroup = '3D Configurator';

  protected static ?string $navigationLabel = 'Sub Options';

  protected static ?int $navigationSort = 4;

  protected static ?string $modelLabel = '3D Sub Option';

  protected static ?string $pluralModelLabel = '3D Sub Options';

  public static function form(Form $form): Form
  {
    return $form
      ->schema([
        Forms\Components\Section::make('Basic Information')
          ->schema([
            Forms\Components\Select::make('part_option_id')
              ->label('Parent Option')
              ->relationship('option', 'name')
              ->required()
              ->searchable()
              ->preload(),

            Forms\Components\TextInput::make('name')
              ->label('Name (English)')
              ->required()
              ->maxLength(255),

            Forms\Components\TextInput::make('name_vi')
              ->label('Name (Vietnamese)')
              ->required()
              ->maxLength(255),

            Forms\Components\TextInput::make('code')
              ->label('Code')
              ->required()
              ->maxLength(50)
              ->helperText('Unique within sub-category'),

            Forms\Components\TextInput::make('sub_category')
              ->label('Sub-Category')
              ->required()
              ->maxLength(50)
              ->helperText('Grouping (e.g., width, buttons, style)'),
          ])
          ->columns(2),

        Forms\Components\Section::make('Model Settings')
          ->schema([
            Forms\Components\TextInput::make('model_file')
              ->label('Model File')
              ->helperText('Path relative to product base path'),

            Forms\Components\KeyValue::make('mesh_config')
              ->label('Mesh Configuration')
              ->keyLabel('Property')
              ->valueLabel('Value'),
          ]),

        Forms\Components\Section::make('Pricing & Status')
          ->schema([
            Forms\Components\TextInput::make('price_modifier')
              ->label('Price Modifier')
              ->numeric()
              ->default(0)
              ->prefix('₫'),

            Forms\Components\Toggle::make('is_default')
              ->label('Default Option')
              ->default(false),

            Forms\Components\Toggle::make('is_active')
              ->label('Active')
              ->default(true),

            Forms\Components\TextInput::make('sort_order')
              ->label('Sort Order')
              ->numeric()
              ->default(0),
          ])
          ->columns(4),
      ]);
  }

  public static function table(Table $table): Table
  {
    return $table
      ->columns([
        Tables\Columns\TextColumn::make('option.name')
          ->label('Parent Option')
          ->sortable()
          ->searchable(),

        Tables\Columns\TextColumn::make('sub_category')
          ->label('Sub-Category')
          ->badge()
          ->sortable(),

        Tables\Columns\TextColumn::make('code')
          ->label('Code')
          ->searchable()
          ->sortable(),

        Tables\Columns\TextColumn::make('name')
          ->label('Name')
          ->searchable(),

        Tables\Columns\TextColumn::make('model_file')
          ->label('Model File')
          ->limit(20),

        Tables\Columns\TextColumn::make('price_modifier')
          ->label('Price +')
          ->money('VND'),

        Tables\Columns\IconColumn::make('is_default')
          ->label('Default')
          ->boolean(),

        Tables\Columns\IconColumn::make('is_active')
          ->label('Active')
          ->boolean(),

        Tables\Columns\TextColumn::make('sort_order')
          ->label('Order')
          ->sortable(),
      ])
      ->filters([
        Tables\Filters\SelectFilter::make('part_option_id')
          ->label('Parent Option')
          ->relationship('option', 'name'),

        Tables\Filters\TernaryFilter::make('is_active')
          ->label('Active'),
      ])
      ->actions([
        Tables\Actions\EditAction::make(),
      ])
      ->bulkActions([
        Tables\Actions\BulkActionGroup::make([
          Tables\Actions\DeleteBulkAction::make(),
        ]),
      ])
      ->defaultSort('sort_order')
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
      'index' => Pages\ListPartSubOption3DS::route('/'),
      'create' => Pages\CreatePartSubOption3D::route('/create'),
      'edit' => Pages\EditPartSubOption3D::route('/{record}/edit'),
    ];
  }
}
