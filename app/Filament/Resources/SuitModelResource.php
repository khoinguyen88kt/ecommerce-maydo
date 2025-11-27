<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SuitModelResource\Pages;
use App\Models\SuitModel;
use App\Models\OptionType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class SuitModelResource extends Resource
{
  protected static ?string $model = SuitModel::class;

  protected static ?string $navigationIcon = 'heroicon-o-adjustments-horizontal';

  protected static ?string $navigationGroup = 'Quản lý vest';

  protected static ?string $navigationLabel = 'Mẫu vest';

  protected static ?string $modelLabel = 'Mẫu vest';

  protected static ?string $pluralModelLabel = 'Danh sách mẫu vest';

  protected static ?int $navigationSort = 1;

  public static function form(Form $form): Form
  {
    return $form
      ->schema([
        Forms\Components\Section::make('Thông tin cơ bản')
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

            Forms\Components\TextInput::make('base_price')
              ->label('Giá cơ bản (₫)')
              ->required()
              ->numeric()
              ->default(2500000)
              ->suffix('₫'),
          ])->columns(2),

        Forms\Components\Section::make('Hình ảnh')
          ->schema([
            Forms\Components\FileUpload::make('thumbnail')
              ->label('Ảnh đại diện')
              ->image()
              ->directory('suit-models/thumbnails')
              ->imageResizeMode('cover')
              ->imageCropAspectRatio('3:4')
              ->imageResizeTargetWidth('600')
              ->imageResizeTargetHeight('800'),
          ]),

        Forms\Components\Section::make('Mô tả')
          ->schema([
            Forms\Components\Textarea::make('description')
              ->label('Mô tả (English)')
              ->rows(3),

            Forms\Components\Textarea::make('description_vi')
              ->label('Mô tả (Tiếng Việt)')
              ->rows(3),
          ])->columns(2),

        Forms\Components\Section::make('Tùy chọn cho phép')
          ->schema([
            Forms\Components\CheckboxList::make('optionTypes')
              ->label('Các loại tùy chọn')
              ->relationship('optionTypes', 'name_vi')
              ->columns(3)
              ->gridDirection('row')
              ->helperText('Chọn các loại tùy chọn mà mẫu vest này hỗ trợ'),
          ]),

        Forms\Components\Section::make('Cấu hình layer (JSON)')
          ->schema([
            Forms\Components\Textarea::make('layer_config')
              ->label('Cấu hình layer')
              ->rows(10)
              ->json()
              ->helperText('Cấu hình các layer ảnh cho mỗi view và option'),
          ])
          ->collapsed(),

        Forms\Components\Section::make('Cài đặt')
          ->schema([
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
          ])->columns(3),
      ]);
  }

  public static function table(Table $table): Table
  {
    return $table
      ->columns([
        Tables\Columns\ImageColumn::make('thumbnail')
          ->label('Ảnh')
          ->height(80),

        Tables\Columns\TextColumn::make('name_vi')
          ->label('Tên')
          ->searchable()
          ->sortable(),

        Tables\Columns\TextColumn::make('base_price')
          ->label('Giá cơ bản')
          ->formatStateUsing(fn($state) => number_format($state) . ' ₫')
          ->sortable(),

        Tables\Columns\TextColumn::make('optionTypes')
          ->label('Số tùy chọn')
          ->formatStateUsing(fn($record) => $record->optionTypes->count()),

        Tables\Columns\IconColumn::make('is_active')
          ->label('Kích hoạt')
          ->boolean(),

        Tables\Columns\IconColumn::make('is_featured')
          ->label('Nổi bật')
          ->boolean(),

        Tables\Columns\TextColumn::make('updated_at')
          ->label('Cập nhật')
          ->dateTime('d/m/Y H:i')
          ->sortable(),
      ])
      ->filters([
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
      'index' => Pages\ListSuitModels::route('/'),
      'create' => Pages\CreateSuitModel::route('/create'),
      'edit' => Pages\EditSuitModel::route('/{record}/edit'),
    ];
  }
}
