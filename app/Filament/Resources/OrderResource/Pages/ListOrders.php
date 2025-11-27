<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListOrders extends ListRecords
{
  protected static string $resource = OrderResource::class;

  protected function getHeaderActions(): array
  {
  return [];
  }

  public function getTabs(): array
  {
  return [
      'all' => Tab::make('Tất cả')
    ->badge($this->getModel()::count()),

      'pending' => Tab::make('Chờ xử lý')
    ->badge($this->getModel()::where('order_status', 'pending')->count())
    ->badgeColor('warning')
    ->modifyQueryUsing(fn (Builder $query) => $query->where('order_status', 'pending')),

      'confirmed' => Tab::make('Đã xác nhận')
    ->badge($this->getModel()::where('order_status', 'confirmed')->count())
    ->badgeColor('info')
    ->modifyQueryUsing(fn (Builder $query) => $query->where('order_status', 'confirmed')),

      'processing' => Tab::make('Đang xử lý')
    ->badge($this->getModel()::whereIn('order_status', ['processing', 'tailoring'])->count())
    ->badgeColor('primary')
    ->modifyQueryUsing(fn (Builder $query) => $query->whereIn('order_status', ['processing', 'tailoring'])),

      'shipping' => Tab::make('Đang giao')
    ->badge($this->getModel()::where('order_status', 'shipping')->count())
    ->badgeColor('info')
    ->modifyQueryUsing(fn (Builder $query) => $query->where('order_status', 'shipping')),

      'delivered' => Tab::make('Hoàn thành')
    ->badge($this->getModel()::where('order_status', 'delivered')->count())
    ->badgeColor('success')
    ->modifyQueryUsing(fn (Builder $query) => $query->where('order_status', 'delivered')),

      'cancelled' => Tab::make('Đã hủy')
    ->badge($this->getModel()::where('order_status', 'cancelled')->count())
    ->badgeColor('danger')
    ->modifyQueryUsing(fn (Builder $query) => $query->where('order_status', 'cancelled')),
  ];
  }
}
