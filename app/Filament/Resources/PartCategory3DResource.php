<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PartCategory3DResource\Pages;
use App\Models\ThreeD\PartCategory3D;
use App\Models\ThreeD\ProductType3D;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PartCategory3DResource extends Resource
{
  protected static ?string $model = PartCategory3D::class;

  protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

  protected static ?string $navigationGroup = '3D Configurator';

  protected static ?string $navigationLabel = 'Part Categories';

  protected static ?int $navigationSort = 2;

  protected static ?string $modelLabel = '3D Part Category';

  protected static ?string $pluralModelLabel = '3D Part Categories';

  public static function form(Form $form): Form
  {
    return $form
      ->schema([
        Forms\Components\Section::make('Basic Information')
          ->schema([
            Forms\Components\Select::make('product_type_id')
              ->label('Product Type')
              ->relationship('productType', 'name')
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
              ->helperText('Unique within product type (e.g., style, lapel, pocket)'),

            Forms\Components\TextInput::make('icon')
              ->label('Icon')
              ->helperText('Heroicon name or custom icon class'),

            Forms\Components\Textarea::make('description')
              ->label('Description')
              ->rows(2)
              ->columnSpanFull(),
          ])
          ->columns(2),

        Forms\Components\Section::make('Behavior')
          ->schema([
            Forms\Components\Toggle::make('is_required')
              ->label('Required')
              ->default(true)
              ->helperText('Must have a selection'),

            Forms\Components\Toggle::make('allow_multiple')
              ->label('Allow Multiple')
              ->default(false)
              ->helperText('Can select multiple options'),

            Forms\Components\Toggle::make('affects_other_parts')
              ->label('Affects Other Parts')
              ->default(false)
              ->helperText('Changes to this category affect other parts'),

            Forms\Components\TagsInput::make('dependencies')
              ->label('Dependencies')
              ->helperText('Category codes this depends on')
              ->columnSpanFull(),
          ])
          ->columns(3),

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
        Tables\Columns\TextColumn::make('productType.name')
          ->label('Product Type')
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
          ->searchable()
          ->toggleable(isToggledHiddenByDefault: true),

        Tables\Columns\IconColumn::make('is_required')
          ->label('Required')
          ->boolean(),

        Tables\Columns\IconColumn::make('affects_other_parts')
          ->label('Affects Others')
          ->boolean(),

        Tables\Columns\TextColumn::make('options_count')
          ->counts('options')
          ->label('Options'),

        Tables\Columns\IconColumn::make('is_active')
          ->label('Active')
          ->boolean(),

        Tables\Columns\TextColumn::make('sort_order')
          ->label('Order')
          ->sortable(),
      ])
      ->filters([
        Tables\Filters\SelectFilter::make('product_type_id')
          ->label('Product Type')
          ->relationship('productType', 'name'),

        Tables\Filters\TernaryFilter::make('is_active')
          ->label('Active'),

        Tables\Filters\TernaryFilter::make('is_required')
          ->label('Required'),
      ])
      ->actions([
        Tables\Actions\EditAction::make(),
        Tables\Actions\Action::make('manage_options')
          ->label('Options')
          ->icon('heroicon-o-list-bullet')
          ->url(fn(PartCategory3D $record) => PartOption3DResource::getUrl('index', ['part_category' => $record->id])),
      ])
      ->bulkActions([
        Tables\Actions\BulkActionGroup::make([
          Tables\Actions\DeleteBulkAction::make(),
        ]),
      ])
      ->defaultSort('sort_order')
      ->modifyQueryUsing(function (Builder $query) {
        // Filter by product_type if provided in URL
        if (request()->has('product_type')) {
          $query->where('product_type_id', request('product_type'));
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
      'index' => Pages\ListPartCategory3DS::route('/'),
      'create' => Pages\CreatePartCategory3D::route('/create'),
      'edit' => Pages\EditPartCategory3D::route('/{record}/edit'),
    ];
  }
}
