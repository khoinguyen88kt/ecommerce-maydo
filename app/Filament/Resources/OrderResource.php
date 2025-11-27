<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class OrderResource extends Resource
{
  protected static ?string $model = Order::class;

  protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

  protected static ?string $navigationGroup = 'Đơn hàng';

  protected static ?string $navigationLabel = 'Đơn hàng';

  protected static ?string $modelLabel = 'Đơn hàng';

  protected static ?string $pluralModelLabel = 'Danh sách đơn hàng';

  protected static ?int $navigationSort = 1;

  public static function form(Form $form): Form
  {
  return $form
      ->schema([
    Forms\Components\Section::make('Thông tin đơn hàng')
          ->schema([
      Forms\Components\TextInput::make('order_number')
              ->label('Mã đơn hàng')
              ->disabled(),

      Forms\Components\Select::make('order_status')
              ->label('Trạng thái đơn hàng')
              ->options(Order::getStatusOptions())
              ->required(),

      Forms\Components\Select::make('payment_status')
              ->label('Trạng thái thanh toán')
              ->options(Order::getPaymentStatusOptions())
              ->required(),

      Forms\Components\Select::make('payment_method')
              ->label('Phương thức thanh toán')
              ->options([
        'momo' => 'Ví MoMo',
        'vnpay' => 'VNPay',
        'bank_transfer' => 'Chuyển khoản ngân hàng',
        'cod' => 'Thanh toán khi nhận hàng',
              ])
              ->disabled(),
          ])->columns(2),

    Forms\Components\Section::make('Thông tin khách hàng')
          ->schema([
      Forms\Components\TextInput::make('customer_name')
              ->label('Tên khách hàng')
              ->disabled(),

      Forms\Components\TextInput::make('customer_email')
              ->label('Email')
              ->disabled(),

      Forms\Components\TextInput::make('customer_phone')
              ->label('Số điện thoại')
              ->disabled(),

      Forms\Components\Textarea::make('shipping_address')
              ->label('Địa chỉ giao hàng')
              ->disabled()
              ->rows(2),
          ])->columns(2),

    Forms\Components\Section::make('Tổng tiền')
          ->schema([
      Forms\Components\TextInput::make('subtotal')
              ->label('Tạm tính')
              ->disabled()
              ->formatStateUsing(fn ($state) => number_format($state) . ' ₫'),

      Forms\Components\TextInput::make('shipping_fee')
              ->label('Phí vận chuyển')
              ->disabled()
              ->formatStateUsing(fn ($state) => number_format($state) . ' ₫'),

      Forms\Components\TextInput::make('discount')
              ->label('Giảm giá')
              ->disabled()
              ->formatStateUsing(fn ($state) => number_format($state) . ' ₫'),

      Forms\Components\TextInput::make('total')
              ->label('Tổng cộng')
              ->disabled()
              ->formatStateUsing(fn ($state) => number_format($state) . ' ₫'),
          ])->columns(2),

    Forms\Components\Section::make('Ghi chú')
          ->schema([
      Forms\Components\Textarea::make('customer_notes')
              ->label('Ghi chú của khách')
              ->disabled()
              ->rows(2),

      Forms\Components\Textarea::make('admin_notes')
              ->label('Ghi chú nội bộ')
              ->rows(3),
          ])->columns(2),
      ]);
  }

  public static function table(Table $table): Table
  {
  return $table
      ->columns([
    Tables\Columns\TextColumn::make('order_number')
          ->label('Mã đơn')
          ->searchable()
          ->sortable(),

    Tables\Columns\TextColumn::make('customer_name')
          ->label('Khách hàng')
          ->searchable(),

    Tables\Columns\TextColumn::make('customer_phone')
          ->label('SĐT')
          ->searchable(),

    Tables\Columns\TextColumn::make('total')
          ->label('Tổng tiền')
          ->formatStateUsing(fn ($state) => number_format($state) . ' ₫')
          ->sortable(),

    Tables\Columns\TextColumn::make('payment_method')
          ->label('Thanh toán')
          ->formatStateUsing(fn ($record) => $record->payment_method_label)
          ->badge(),

    Tables\Columns\TextColumn::make('payment_status')
          ->label('TT Thanh toán')
          ->formatStateUsing(fn ($record) => $record->payment_status_label)
          ->badge()
          ->color(fn (string $state): string => match ($state) {
      'pending' => 'warning',
      'paid' => 'success',
      'failed' => 'danger',
      'refunded' => 'info',
      default => 'gray',
          }),

    Tables\Columns\TextColumn::make('order_status')
          ->label('Trạng thái')
          ->formatStateUsing(fn ($record) => $record->status_label)
          ->badge()
          ->color(fn (string $state): string => match ($state) {
      'pending' => 'warning',
      'confirmed' => 'info',
      'processing' => 'primary',
      'tailoring' => 'primary',
      'shipping' => 'info',
      'delivered' => 'success',
      'cancelled' => 'danger',
      default => 'gray',
          }),

    Tables\Columns\TextColumn::make('created_at')
          ->label('Ngày tạo')
          ->dateTime('d/m/Y H:i')
          ->sortable(),
      ])
      ->filters([
    Tables\Filters\SelectFilter::make('order_status')
          ->label('Trạng thái')
          ->options(Order::getStatusOptions()),

    Tables\Filters\SelectFilter::make('payment_status')
          ->label('Thanh toán')
          ->options(Order::getPaymentStatusOptions()),

    Tables\Filters\SelectFilter::make('payment_method')
          ->label('Phương thức')
          ->options([
      'momo' => 'Ví MoMo',
      'vnpay' => 'VNPay',
      'bank_transfer' => 'Chuyển khoản',
      'cod' => 'COD',
          ]),
      ])
      ->actions([
    Tables\Actions\ViewAction::make(),
    Tables\Actions\EditAction::make(),
      ])
      ->bulkActions([
    Tables\Actions\BulkActionGroup::make([
          Tables\Actions\DeleteBulkAction::make(),
    ]),
      ])
      ->defaultSort('created_at', 'desc');
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
      'index' => Pages\ListOrders::route('/'),
      'view' => Pages\ViewOrder::route('/{record}'),
      'edit' => Pages\EditOrder::route('/{record}/edit'),
  ];
  }

  public static function getNavigationBadge(): ?string
  {
  return static::getModel()::where('order_status', 'pending')->count() ?: null;
  }

  public static function getNavigationBadgeColor(): ?string
  {
  return 'warning';
  }
}
