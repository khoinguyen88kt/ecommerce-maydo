<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OptionTypeResource\Pages;
use App\Filament\Resources\OptionTypeResource\RelationManagers;
use App\Models\OptionType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class OptionTypeResource extends Resource
{
  protected static ?string $model = OptionType::class;

  protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

  protected static ?string $navigationGroup = 'Quản lý vest';

  protected static ?string $navigationLabel = 'Loại tùy chọn';

  protected static ?string $modelLabel = 'Loại tùy chọn';

  protected static ?string $pluralModelLabel = 'Các loại tùy chọn';

  protected static ?int $navigationSort = 2;

  public static function form(Form $form): Form
  {
    return $form
      ->schema([
        Forms\Components\Section::make('Thông tin cơ bản')
          ->schema([
            Forms\Components\TextInput::make('name')
              ->label('Tên (English)')
              ->required()
              ->maxLength(255)
              ->placeholder('VD: Lapel Style'),

            Forms\Components\TextInput::make('name_vi')
              ->label('Tên (Tiếng Việt)')
              ->required()
              ->maxLength(255)
              ->placeholder('VD: Kiểu ve áo')
              ->live(onBlur: true)
              ->afterStateUpdated(fn ($state, Forms\Set $set) =>
                $set('slug', Str::slug($state))),

            Forms\Components\TextInput::make('slug')
              ->label('Slug')
              ->required()
              ->unique(ignoreRecord: true)
              ->maxLength(255),

            Forms\Components\TextInput::make('icon')
              ->label('Icon (Heroicon name)')
              ->placeholder('VD: heroicon-o-squares-2x2')
              ->maxLength(100),
          ])->columns(2),

        Forms\Components\Section::make('Mô tả')
          ->schema([
            Forms\Components\Textarea::make('description')
              ->label('Mô tả (English)')
              ->rows(2),

            Forms\Components\Textarea::make('description_vi')
              ->label('Mô tả (Tiếng Việt)')
              ->rows(2),
          ])->columns(2),

        Forms\Components\Section::make('Cài đặt')
          ->schema([
            Forms\Components\Select::make('type')
              ->label('Loại chọn')
              ->options([
                'single' => 'Chọn một (Single)',
                'multiple' => 'Chọn nhiều (Multiple)',
              ])
              ->default('single')
              ->required(),

            Forms\Components\TextInput::make('sort_order')
              ->label('Thứ tự sắp xếp')
              ->numeric()
              ->default(0),

            Forms\Components\Toggle::make('is_required')
              ->label('Bắt buộc')
              ->default(false),

            Forms\Components\Toggle::make('is_active')
              ->label('Kích hoạt')
              ->default(true),
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

        Tables\Columns\TextColumn::make('type')
          ->label('Loại')
          ->badge()
          ->color(fn (string $state): string => match ($state) {
            'single' => 'info',
            'multiple' => 'success',
            default => 'gray',
          }),

        Tables\Columns\TextColumn::make('values_count')
          ->label('Số giá trị')
          ->counts('values'),

        Tables\Columns\IconColumn::make('is_required')
          ->label('Bắt buộc')
          ->boolean(),

        Tables\Columns\IconColumn::make('is_active')
          ->label('Kích hoạt')
          ->boolean(),

        Tables\Columns\TextColumn::make('sort_order')
          ->label('Thứ tự')
          ->sortable(),
      ])
      ->filters([
        Tables\Filters\SelectFilter::make('type')
          ->label('Loại')
          ->options([
            'single' => 'Chọn một',
            'multiple' => 'Chọn nhiều',
          ]),

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
      RelationManagers\ValuesRelationManager::class,
    ];
  }

  public static function getPages(): array
  {
    return [
      'index' => Pages\ListOptionTypes::route('/'),
      'create' => Pages\CreateOptionType::route('/create'),
      'edit' => Pages\EditOptionType::route('/{record}/edit'),
    ];
  }
}
