<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components;

class ViewOrder extends ViewRecord
{
  protected static string $resource = OrderResource::class;

  protected function getHeaderActions(): array
  {
  return [
      Actions\EditAction::make(),
  ];
  }

  public function infolist(Infolist $infolist): Infolist
  {
  return $infolist
      ->schema([
    Components\Section::make('Thông tin đơn hàng')
          ->schema([
      Components\TextEntry::make('order_number')
              ->label('Mã đơn hàng'),
      Components\TextEntry::make('order_status')
              ->label('Trạng thái')
              ->badge()
              ->formatStateUsing(fn ($record) => $record->status_label),
      Components\TextEntry::make('payment_method')
              ->label('Phương thức thanh toán')
              ->formatStateUsing(fn ($record) => $record->payment_method_label),
      Components\TextEntry::make('payment_status')
              ->label('Trạng thái thanh toán')
              ->badge()
              ->formatStateUsing(fn ($record) => $record->payment_status_label),
          ])->columns(2),

    Components\Section::make('Thông tin khách hàng')
          ->schema([
      Components\TextEntry::make('customer_name')
              ->label('Tên khách hàng'),
      Components\TextEntry::make('customer_email')
              ->label('Email'),
      Components\TextEntry::make('customer_phone')
              ->label('Số điện thoại'),
      Components\TextEntry::make('shipping_address')
              ->label('Địa chỉ giao hàng')
              ->columnSpanFull(),
          ])->columns(2),

    Components\Section::make('Chi tiết sản phẩm')
          ->schema([
      Components\RepeatableEntry::make('items')
              ->label('')
              ->schema([
        Components\TextEntry::make('suitModel.name_vi')
                  ->label('Sản phẩm'),
        Components\TextEntry::make('fabric.name_vi')
                  ->label('Vải'),
        Components\TextEntry::make('quantity')
                  ->label('Số lượng'),
        Components\TextEntry::make('unit_price')
                  ->label('Đơn giá')
                  ->formatStateUsing(fn ($state) => number_format($state) . ' ₫'),
        Components\TextEntry::make('total_price')
                  ->label('Thành tiền')
                  ->formatStateUsing(fn ($state) => number_format($state) . ' ₫'),
              ])->columns(5),
          ]),

    Components\Section::make('Tổng tiền')
          ->schema([
      Components\TextEntry::make('subtotal')
              ->label('Tạm tính')
              ->formatStateUsing(fn ($state) => number_format($state) . ' ₫'),
      Components\TextEntry::make('shipping_fee')
              ->label('Phí vận chuyển')
              ->formatStateUsing(fn ($state) => number_format($state) . ' ₫'),
      Components\TextEntry::make('discount')
              ->label('Giảm giá')
              ->formatStateUsing(fn ($state) => number_format($state) . ' ₫'),
      Components\TextEntry::make('total')
              ->label('Tổng cộng')
              ->formatStateUsing(fn ($state) => number_format($state) . ' ₫')
              ->weight('bold'),
          ])->columns(4),

    Components\Section::make('Ghi chú')
          ->schema([
      Components\TextEntry::make('customer_notes')
              ->label('Ghi chú của khách'),
      Components\TextEntry::make('admin_notes')
              ->label('Ghi chú nội bộ'),
          ])->columns(2),
      ]);
  }
}
