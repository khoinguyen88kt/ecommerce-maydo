<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PartOption3DResource\Pages;
use App\Models\ThreeD\PartOption3D;
use App\Models\ThreeD\PartCategory3D;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PartOption3DResource extends Resource
{
  protected static ?string $model = PartOption3D::class;

  protected static ?string $navigationIcon = 'heroicon-o-puzzle-piece';

  protected static ?string $navigationGroup = '3D Configurator';

  protected static ?string $navigationLabel = 'Part Options';

  protected static ?int $navigationSort = 3;

  protected static ?string $modelLabel = '3D Part Option';

  protected static ?string $pluralModelLabel = '3D Part Options';

  public static function form(Form $form): Form
  {
    return $form
      ->schema([
        Forms\Components\Section::make('Basic Information')
          ->schema([
            Forms\Components\Select::make('part_category_id')
              ->label('Part Category')
              ->relationship('category', 'name')
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
              ->helperText('Unique within category (e.g., 2button, peak-lapel)'),

            Forms\Components\Textarea::make('description')
              ->label('Description')
              ->rows(2)
              ->columnSpanFull(),
          ])
          ->columns(2),

        Forms\Components\Section::make('Model Files')
          ->schema([
            Forms\Components\TextInput::make('model_file')
              ->label('Primary Model File')
              ->helperText('Path relative to product base path (e.g., Front/2Button.glb)'),

            Forms\Components\Repeater::make('model_files')
              ->label('Additional Model Files')
              ->schema([
                Forms\Components\TextInput::make('path')
                  ->label('File Path')
                  ->required(),
                Forms\Components\TextInput::make('mesh_name')
                  ->label('Mesh Name'),
              ])
              ->columns(2)
              ->collapsible()
              ->collapsed(),

            Forms\Components\FileUpload::make('preview_image')
              ->label('Preview Image')
              ->image()
              ->directory('3d-previews'),
          ]),

        Forms\Components\Section::make('Mesh Configuration')
          ->schema([
            Forms\Components\KeyValue::make('mesh_config')
              ->label('Mesh Settings')
              ->keyLabel('Property')
              ->valueLabel('Value')
              ->helperText('Additional mesh configuration'),
          ]),

        Forms\Components\Section::make('Constraints')
          ->schema([
            Forms\Components\KeyValue::make('constraints')
              ->label('Constraints')
              ->keyLabel('Category Code')
              ->valueLabel('Required Option Code')
              ->helperText('Define which options this is compatible with'),
          ]),

        Forms\Components\Section::make('Pricing & Status')
          ->schema([
            Forms\Components\TextInput::make('price_modifier')
              ->label('Price Modifier')
              ->numeric()
              ->default(0)
              ->prefix('₫')
              ->helperText('Additional cost for this option'),

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
        Tables\Columns\TextColumn::make('category.productType.name')
          ->label('Product')
          ->sortable()
          ->toggleable(isToggledHiddenByDefault: true),

        Tables\Columns\TextColumn::make('category.name')
          ->label('Category')
          ->sortable()
          ->searchable(),

        Tables\Columns\TextColumn::make('code')
          ->label('Code')
          ->searchable()
          ->sortable(),

        Tables\Columns\TextColumn::make('name')
          ->label('Name')
          ->searchable(),

        Tables\Columns\TextColumn::make('name_vi')
          ->label('Name (VI)')
          ->toggleable(isToggledHiddenByDefault: true),

        Tables\Columns\ImageColumn::make('preview_image')
          ->label('Preview')
          ->circular(),

        Tables\Columns\TextColumn::make('model_file')
          ->label('Model File')
          ->limit(30),

        Tables\Columns\TextColumn::make('price_modifier')
          ->label('Price +')
          ->money('VND')
          ->sortable(),

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
        Tables\Filters\SelectFilter::make('part_category_id')
          ->label('Category')
          ->relationship('category', 'name'),

        Tables\Filters\TernaryFilter::make('is_active')
          ->label('Active'),

        Tables\Filters\TernaryFilter::make('is_default')
          ->label('Default'),
      ])
      ->actions([
        Tables\Actions\EditAction::make(),
        Tables\Actions\Action::make('manage_sub_options')
          ->label('Sub-Options')
          ->icon('heroicon-o-adjustments-horizontal')
          ->url(fn(PartOption3D $record) => PartSubOption3DResource::getUrl('index', ['part_option' => $record->id])),
        Tables\Actions\Action::make('manage_meshes')
          ->label('Meshes')
          ->icon('heroicon-o-cube-transparent')
          ->url(fn(PartOption3D $record) => ModelMesh3DResource::getUrl('index', ['part_option' => $record->id])),
      ])
      ->bulkActions([
        Tables\Actions\BulkActionGroup::make([
          Tables\Actions\DeleteBulkAction::make(),
        ]),
      ])
      ->defaultSort('sort_order')
      ->modifyQueryUsing(function (Builder $query) {
        if (request()->has('part_category')) {
          $query->where('part_category_id', request('part_category'));
        }
      });
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
      'index' => Pages\ListPartOption3DS::route('/'),
      'create' => Pages\CreatePartOption3D::route('/create'),
      'edit' => Pages\EditPartOption3D::route('/{record}/edit'),
    ];
  }
}
