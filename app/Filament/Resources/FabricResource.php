<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FabricResource\Pages;
use App\Models\Fabric;
use App\Models\FabricCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class FabricResource extends Resource
{
  protected static ?string $model = Fabric::class;

  protected static ?string $navigationIcon = 'heroicon-o-swatch';

  protected static ?string $navigationGroup = 'Quản lý vải';

  protected static ?string $navigationLabel = 'Vải';

  protected static ?string $modelLabel = 'Vải';

  protected static ?string $pluralModelLabel = 'Danh sách vải';

  protected static ?int $navigationSort = 2;

  public static function form(Form $form): Form
  {
    return $form
      ->schema([
        Forms\Components\Section::make('Thông tin cơ bản')
          ->schema([
            Forms\Components\Select::make('fabric_category_id')
              ->label('Danh mục')
              ->options(FabricCategory::active()->pluck('name_vi', 'id'))
              ->required()
              ->searchable(),

            Forms\Components\TextInput::make('code')
              ->label('Mã vải')
              ->required()
              ->unique(ignoreRecord: true)
              ->maxLength(50)
              ->placeholder('VD: FAB-001'),

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

            Forms\Components\ColorPicker::make('color_hex')
              ->label('Màu chính'),
          ])->columns(2),

        Forms\Components\Section::make('Hình ảnh')
          ->schema([
            Forms\Components\FileUpload::make('texture_image')
              ->label('Ảnh texture vải')
              ->image()
              ->directory('fabrics/textures')
              ->imageResizeMode('cover')
              ->imageCropAspectRatio('1:1')
              ->imageResizeTargetWidth('512')
              ->imageResizeTargetHeight('512'),

            Forms\Components\FileUpload::make('thumbnail')
              ->label('Ảnh thumbnail')
              ->image()
              ->directory('fabrics/thumbnails')
              ->imageResizeMode('cover')
              ->imageCropAspectRatio('1:1')
              ->imageResizeTargetWidth('200')
              ->imageResizeTargetHeight('200'),
          ])->columns(2),

        Forms\Components\Section::make('Mô tả')
          ->schema([
            Forms\Components\Textarea::make('description')
              ->label('Mô tả (English)')
              ->rows(3),

            Forms\Components\Textarea::make('description_vi')
              ->label('Mô tả (Tiếng Việt)')
              ->rows(3),
          ])->columns(2),

        Forms\Components\Section::make('Thông tin kỹ thuật')
          ->schema([
            Forms\Components\TextInput::make('material_composition')
              ->label('Thành phần')
              ->placeholder('VD: 100% Wool')
              ->maxLength(255),

            Forms\Components\TextInput::make('weight')
              ->label('Trọng lượng')
              ->placeholder('VD: 260gsm')
              ->maxLength(100),

            Forms\Components\TextInput::make('origin')
              ->label('Xuất xứ')
              ->placeholder('VD: Italy')
              ->maxLength(100),

            Forms\Components\TextInput::make('stock_quantity')
              ->label('Số lượng tồn')
              ->numeric()
              ->default(100),
          ])->columns(2),

        Forms\Components\Section::make('Giá & Cài đặt')
          ->schema([
            Forms\Components\TextInput::make('price_modifier')
              ->label('Giá cộng thêm (₫)')
              ->numeric()
              ->default(0)
              ->suffix('₫')
              ->helperText('Giá sẽ được cộng vào giá cơ bản của vest'),

            Forms\Components\TextInput::make('sort_order')
              ->label('Thứ tự sắp xếp')
              ->numeric()
              ->default(0),

            Forms\Components\Toggle::make('is_active')
              ->label('Kích hoạt')
              ->default(true),

            Forms\Components\Toggle::make('is_featured')
              ->label('Nổi bật')
              ->default(false),
          ])->columns(2),
      ]);
  }

  public static function table(Table $table): Table
  {
    return $table
      ->columns([
        Tables\Columns\ImageColumn::make('thumbnail')
          ->label('Ảnh')
          ->circular(),

        Tables\Columns\TextColumn::make('code')
          ->label('Mã')
          ->searchable()
          ->sortable(),

        Tables\Columns\TextColumn::make('name_vi')
          ->label('Tên')
          ->searchable()
          ->sortable(),

        Tables\Columns\TextColumn::make('category.name_vi')
          ->label('Danh mục')
          ->sortable(),

        Tables\Columns\ColorColumn::make('color_hex')
          ->label('Màu'),

        Tables\Columns\TextColumn::make('price_modifier')
          ->label('Giá +')
          ->formatStateUsing(fn($state) => $state > 0 ? '+' . number_format($state) . '₫' : '-')
          ->sortable(),

        Tables\Columns\TextColumn::make('stock_quantity')
          ->label('Tồn kho')
          ->sortable(),

        Tables\Columns\IconColumn::make('is_active')
          ->label('Kích hoạt')
          ->boolean(),

        Tables\Columns\IconColumn::make('is_featured')
          ->label('Nổi bật')
          ->boolean(),
      ])
      ->filters([
        Tables\Filters\SelectFilter::make('fabric_category_id')
          ->label('Danh mục')
          ->options(FabricCategory::pluck('name_vi', 'id')),

        Tables\Filters\TernaryFilter::make('is_active')
          ->label('Kích hoạt'),

        Tables\Filters\TernaryFilter::make('is_featured')
          ->label('Nổi bật'),
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
      'index' => Pages\ListFabrics::route('/'),
      'create' => Pages\CreateFabric::route('/create'),
      'edit' => Pages\EditFabric::route('/{record}/edit'),
    ];
  }
}
