<?php

namespace App\Filament\Resources\OptionTypeResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ValuesRelationManager extends RelationManager
{
  protected static string $relationship = 'values';

  protected static ?string $title = 'Các giá trị';

  protected static ?string $modelLabel = 'Giá trị';

  protected static ?string $pluralModelLabel = 'Giá trị';

  public function form(Form $form): Form
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
              ->afterStateUpdated(fn ($state, Forms\Set $set) =>
        $set('slug', Str::slug($state))),

      Forms\Components\TextInput::make('slug')
              ->label('Slug')
              ->required()
              ->maxLength(255),

      Forms\Components\TextInput::make('layer_key')
              ->label('Layer Key')
              ->maxLength(100)
              ->helperText('Key để map với layer ảnh (VD: lapel_notch)'),
          ])->columns(2),

    Forms\Components\Section::make('Hình ảnh & Mô tả')
          ->schema([
      Forms\Components\FileUpload::make('preview_image')
              ->label('Ảnh preview')
              ->image()
              ->directory('option-values')
              ->imageResizeMode('cover')
              ->imageCropAspectRatio('1:1')
              ->imageResizeTargetWidth('200')
              ->imageResizeTargetHeight('200'),

      Forms\Components\Textarea::make('description_vi')
              ->label('Mô tả')
              ->rows(2),
          ])->columns(2),

    Forms\Components\Section::make('Giá & Cài đặt')
          ->schema([
      Forms\Components\TextInput::make('price_modifier')
              ->label('Giá cộng thêm (₫)')
              ->numeric()
              ->default(0)
              ->suffix('₫'),

      Forms\Components\TextInput::make('sort_order')
              ->label('Thứ tự')
              ->numeric()
              ->default(0),

      Forms\Components\Toggle::make('is_default')
              ->label('Mặc định')
              ->default(false),

      Forms\Components\Toggle::make('is_active')
              ->label('Kích hoạt')
              ->default(true),
          ])->columns(2),
      ]);
  }

  public function table(Table $table): Table
  {
  return $table
      ->recordTitleAttribute('name_vi')
      ->columns([
    Tables\Columns\ImageColumn::make('preview_image')
          ->label('Ảnh')
          ->circular(),

    Tables\Columns\TextColumn::make('name_vi')
          ->label('Tên')
          ->searchable(),

    Tables\Columns\TextColumn::make('slug')
          ->label('Slug'),

    Tables\Columns\TextColumn::make('layer_key')
          ->label('Layer Key'),

    Tables\Columns\TextColumn::make('price_modifier')
          ->label('Giá +')
          ->formatStateUsing(fn ($state) => $state > 0 ? '+' . number_format($state) . '₫' : '-'),

    Tables\Columns\IconColumn::make('is_default')
          ->label('Mặc định')
          ->boolean(),

    Tables\Columns\IconColumn::make('is_active')
          ->label('Kích hoạt')
          ->boolean(),

    Tables\Columns\TextColumn::make('sort_order')
          ->label('Thứ tự')
          ->sortable(),
      ])
      ->filters([
    //
      ])
      ->headerActions([
    Tables\Actions\CreateAction::make(),
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
      ->defaultSort('sort_order')
      ->reorderable('sort_order');
  }
}