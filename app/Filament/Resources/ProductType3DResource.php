<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductType3DResource\Pages;
use App\Models\ThreeD\ProductType3D;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ProductType3DResource extends Resource
{
  protected static ?string $model = ProductType3D::class;

  protected static ?string $navigationIcon = 'heroicon-o-cube';

  protected static ?string $navigationGroup = '3D Configurator';

  protected static ?string $navigationLabel = 'Product Types';

  protected static ?int $navigationSort = 1;

  protected static ?string $modelLabel = '3D Product Type';

  protected static ?string $pluralModelLabel = '3D Product Types';

  public static function form(Form $form): Form
  {
    return $form
      ->schema([
        Forms\Components\Section::make('Basic Information')
          ->schema([
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
              ->unique(ignoreRecord: true)
              ->maxLength(50)
              ->helperText('Unique identifier (e.g., jacket, pant, vest)'),

            Forms\Components\Textarea::make('description')
              ->label('Description')
              ->rows(3)
              ->columnSpanFull(),
          ])
          ->columns(2),

        Forms\Components\Section::make('3D Model Settings')
          ->schema([
            Forms\Components\TextInput::make('base_path')
              ->label('Base Path')
              ->required()
              ->helperText('Path to model files relative to 3d-models folder (e.g., Jacket/)'),

            Forms\Components\TextInput::make('preview_image')
              ->label('Preview Image URL'),

            Forms\Components\KeyValue::make('texture_settings')
              ->label('Default Texture Settings')
              ->keyLabel('Property')
              ->valueLabel('Value')
              ->helperText('Default texture settings for this product type'),
          ]),

        Forms\Components\Section::make('Status')
          ->schema([
            Forms\Components\Toggle::make('is_active')
              ->label('Active')
              ->default(true),

            Forms\Components\TextInput::make('sort_order')
              ->label('Sort Order')
              ->numeric()
              ->default(0),
          ])
          ->columns(2),
      ]);
  }

  public static function table(Table $table): Table
  {
    return $table
      ->columns([
        Tables\Columns\TextColumn::make('code')
          ->label('Code')
          ->searchable()
          ->sortable(),

        Tables\Columns\TextColumn::make('name')
          ->label('Name')
          ->searchable(),

        Tables\Columns\TextColumn::make('name_vi')
          ->label('Name (VI)')
          ->searchable(),

        Tables\Columns\TextColumn::make('base_path')
          ->label('Base Path')
          ->limit(30),

        Tables\Columns\TextColumn::make('partCategories_count')
          ->counts('partCategories')
          ->label('Categories'),

        Tables\Columns\IconColumn::make('is_active')
          ->label('Active')
          ->boolean(),

        Tables\Columns\TextColumn::make('sort_order')
          ->label('Order')
          ->sortable(),
      ])
      ->filters([
        Tables\Filters\TernaryFilter::make('is_active')
          ->label('Active'),
      ])
      ->actions([
        Tables\Actions\EditAction::make(),
        Tables\Actions\Action::make('manage_parts')
          ->label('Manage Parts')
          ->icon('heroicon-o-cog')
          ->url(fn(ProductType3D $record) => PartCategory3DResource::getUrl('index', ['product_type' => $record->id])),
      ])
      ->bulkActions([
        Tables\Actions\BulkActionGroup::make([
          Tables\Actions\DeleteBulkAction::make(),
        ]),
      ])
      ->defaultSort('sort_order');
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
      'index' => Pages\ListProductType3DS::route('/'),
      'create' => Pages\CreateProductType3D::route('/create'),
      'edit' => Pages\EditProductType3D::route('/{record}/edit'),
    ];
  }
}
