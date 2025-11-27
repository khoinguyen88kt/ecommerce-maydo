@extends('layouts.app')

@section('title', 'Đơn hàng của tôi | Suit Configurator')

@section('content')
<div class="min-h-screen bg-neutral-50">
  {{-- Breadcrumb --}}
  <nav class="bg-white border-b border-neutral-200">
  <div class="container mx-auto px-4 py-3">
      <ol class="flex items-center space-x-2 text-sm">
    <li><a href="{{ route('home') }}" class="text-neutral-500 hover:text-primary-600">Trang chủ</a></li>
    <li class="text-neutral-400">/</li>
    <li class="text-primary-600 font-medium">Đơn hàng của tôi</li>
      </ol>
  </div>
  </nav>

  <div class="container mx-auto px-4 py-8">
  <h1 class="text-3xl font-serif font-bold mb-8">Đơn hàng của tôi</h1>

  @if($orders->count() > 0)
  <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
      <div class="overflow-x-auto">
    <table class="w-full">
          <thead class="bg-neutral-50 border-b border-neutral-200">
      <tr>
              <th class="text-left px-6 py-4 text-sm font-semibold text-neutral-600">Mã đơn hàng</th>
              <th class="text-left px-6 py-4 text-sm font-semibold text-neutral-600">Ngày đặt</th>
              <th class="text-left px-6 py-4 text-sm font-semibold text-neutral-600">Tổng tiền</th>
              <th class="text-left px-6 py-4 text-sm font-semibold text-neutral-600">Trạng thái</th>
              <th class="text-left px-6 py-4 text-sm font-semibold text-neutral-600">Thanh toán</th>
              <th class="text-right px-6 py-4 text-sm font-semibold text-neutral-600"></th>
      </tr>
          </thead>
          <tbody class="divide-y divide-neutral-100">
      @foreach($orders as $order)
      <tr class="hover:bg-neutral-50 transition-colors">
              <td class="px-6 py-4">
        <span class="font-medium text-primary-600">{{ $order->order_number }}</span>
              </td>
              <td class="px-6 py-4 text-neutral-600">
        {{ $order->created_at->format('d/m/Y H:i') }}
              </td>
              <td class="px-6 py-4 font-medium">
        {{ $order->formatted_total }}
              </td>
              <td class="px-6 py-4">
        @php
        $statusColors = [
                  'pending' => 'bg-yellow-100 text-yellow-700',
                  'confirmed' => 'bg-blue-100 text-blue-700',
                  'processing' => 'bg-purple-100 text-purple-700',
                  'tailoring' => 'bg-indigo-100 text-indigo-700',
                  'shipping' => 'bg-cyan-100 text-cyan-700',
                  'delivered' => 'bg-green-100 text-green-700',
                  'cancelled' => 'bg-red-100 text-red-700',
        ];
        @endphp
        <span class="inline-block px-3 py-1 text-sm rounded-full {{ $statusColors[$order->order_status] ?? 'bg-neutral-100 text-neutral-700' }}">
                  {{ $order->status_label }}
        </span>
              </td>
              <td class="px-6 py-4">
        @php
        $paymentColors = [
                  'pending' => 'text-yellow-600',
                  'paid' => 'text-green-600',
                  'failed' => 'text-red-600',
                  'refunded' => 'text-neutral-600',
        ];
        @endphp
        <span class="{{ $paymentColors[$order->payment_status] ?? 'text-neutral-600' }}">
                  {{ $order->payment_status_label }}
        </span>
              </td>
              <td class="px-6 py-4 text-right">
        <a href="{{ route('orders.show', $order->order_number) }}" class="text-primary-600 hover:text-primary-700 font-medium text-sm">
                  Xem chi tiết →
        </a>
              </td>
      </tr>
      @endforeach
          </tbody>
    </table>
      </div>
  </div>

  {{-- Pagination --}}
  <div class="mt-6">
      {{ $orders->links() }}
  </div>
  @else
  <div class="bg-white rounded-2xl shadow-sm p-12 text-center">
      <div class="w-24 h-24 mx-auto mb-6 bg-neutral-100 rounded-full flex items-center justify-center">
    <svg class="w-12 h-12 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
    </svg>
      </div>
      <h2 class="text-2xl font-semibold mb-2">Chưa có đơn hàng</h2>
      <p class="text-neutral-600 mb-8">Bạn chưa có đơn hàng nào. Hãy bắt đầu thiết kế vest của bạn!</p>
      <a href="{{ route('configurator.index') }}" class="btn-primary">
    Thiết kế vest ngay
      </a>
  </div>
  @endif
  </div>
</div>
@endsection
