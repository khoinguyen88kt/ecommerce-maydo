<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FabricCategoryResource\Pages;
use App\Models\FabricCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class FabricCategoryResource extends Resource
{
  protected static ?string $model = FabricCategory::class;

  protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

  protected static ?string $navigationGroup = 'Quản lý vải';

  protected static ?string $navigationLabel = 'Danh mục vải';

  protected static ?string $modelLabel = 'Danh mục vải';

  protected static ?string $pluralModelLabel = 'Danh mục vải';

  protected static ?int $navigationSort = 1;

  public static function form(Form $form): Form
  {
    return $form
      ->schema([
        Forms\Components\Section::make('Thông tin danh mục')
          ->schema([
            Forms\Components\TextInput::make('name')
              ->label('Tên (English)')
              ->required()
              ->maxLength(255),

            Forms\Components\TextInput::make('name_vi')
              ->label('Tên (Tiếng Việt)')
              ->required()
              ->maxLength(255)
              ->live(onBlur: true)
              ->afterStateUpdated(fn($state, Forms\Set $set) =>
              $set('slug', Str::slug($state))),

            Forms\Components\TextInput::make('slug')
              ->label('Slug')
              ->required()
              ->unique(ignoreRecord: true)
              ->maxLength(255),

            Forms\Components\Textarea::make('description')
              ->label('Mô tả (English)')
              ->rows(3),

            Forms\Components\Textarea::make('description_vi')
              ->label('Mô tả (Tiếng Việt)')
              ->rows(3),
          ])->columns(2),

        Forms\Components\Section::make('Cài đặt')
          ->schema([
            Forms\Components\Toggle::make('is_active')
              ->label('Kích hoạt')
              ->default(true),

            Forms\Components\TextInput::make('sort_order')
              ->label('Thứ tự sắp xếp')
              ->numeric()
              ->default(0),
          ])->columns(2),
      ]);
  }

  public static function table(Table $table): Table
  {
    return $table
      ->columns([
        Tables\Columns\TextColumn::make('name_vi')
          ->label('Tên')
          ->searchable()
          ->sortable(),

        Tables\Columns\TextColumn::make('slug')
          ->label('Slug')
          ->searchable(),

        Tables\Columns\TextColumn::make('fabrics_count')
          ->label('Số vải')
          ->counts('fabrics'),

        Tables\Columns\IconColumn::make('is_active')
          ->label('Kích hoạt')
          ->boolean(),

        Tables\Columns\TextColumn::make('sort_order')
          ->label('Thứ tự')
          ->sortable(),

        Tables\Columns\TextColumn::make('updated_at')
          ->label('Cập nhật')
          ->dateTime('d/m/Y H:i')
          ->sortable(),
      ])
      ->filters([
        Tables\Filters\TernaryFilter::make('is_active')
          ->label('Kích hoạt'),
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
      'index' => Pages\ListFabricCategories::route('/'),
      'create' => Pages\CreateFabricCategory::route('/create'),
      'edit' => Pages\EditFabricCategory::route('/{record}/edit'),
    ];
  }
}
