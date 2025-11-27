@extends('layouts.app')

@section('title', 'Chi tiết đơn hàng ' . $order->order_number . ' | Suit Configurator')

@section('content')
<div class="min-h-screen bg-neutral-50">
  {{-- Breadcrumb --}}
  <nav class="bg-white border-b border-neutral-200">
  <div class="container mx-auto px-4 py-3">
      <ol class="flex items-center space-x-2 text-sm">
    <li><a href="{{ route('home') }}" class="text-neutral-500 hover:text-primary-600">Trang chủ</a></li>
    <li class="text-neutral-400">/</li>
    <li><a href="{{ route('orders.index') }}" class="text-neutral-500 hover:text-primary-600">Đơn hàng</a></li>
    <li class="text-neutral-400">/</li>
    <li class="text-primary-600 font-medium">{{ $order->order_number }}</li>
      </ol>
  </div>
  </nav>

  <div class="container mx-auto px-4 py-8">
  <div class="grid lg:grid-cols-3 gap-8">
      {{-- Main Content --}}
      <div class="lg:col-span-2 space-y-6">
    {{-- Order Header --}}
    <div class="bg-white rounded-2xl shadow-sm p-6">
          <div class="flex flex-wrap items-start justify-between gap-4 mb-6">
      <div>
              <h1 class="text-2xl font-serif font-bold">Đơn hàng {{ $order->order_number }}</h1>
              <p class="text-neutral-500 mt-1">Đặt ngày {{ $order->created_at->format('d/m/Y H:i') }}</p>
      </div>
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
      <span class="inline-block px-4 py-2 text-sm rounded-full font-medium {{ $statusColors[$order->order_status] ?? 'bg-neutral-100 text-neutral-700' }}">
              {{ $order->status_label }}
      </span>
          </div>

          {{-- Order Timeline --}}
          <div class="relative">
      <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-neutral-200"></div>
      <div class="space-y-6">
              @php
              $timeline = [
        ['status' => 'pending', 'label' => 'Đặt hàng', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
        ['status' => 'confirmed', 'label' => 'Xác nhận', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
        ['status' => 'tailoring', 'label' => 'Đang may', 'icon' => 'M12 4v16m8-8H4'],
        ['status' => 'shipping', 'label' => 'Đang giao', 'icon' => 'M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8'],
        ['status' => 'delivered', 'label' => 'Đã nhận', 'icon' => 'M5 13l4 4L19 7'],
              ];
              $statuses = ['pending', 'confirmed', 'processing', 'tailoring', 'shipping', 'delivered'];
              $currentIndex = array_search($order->order_status, $statuses);
              @endphp

              @foreach($timeline as $index => $step)
              <div class="relative flex items-center pl-10">
        <div class="absolute left-0 w-8 h-8 rounded-full flex items-center justify-center {{ $index <= $currentIndex ? 'bg-primary-500 text-white' : 'bg-neutral-200 text-neutral-500' }}">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $step['icon'] }}"/>
                  </svg>
        </div>
        <div>
                  <p class="font-medium {{ $index <= $currentIndex ? 'text-neutral-900' : 'text-neutral-400' }}">{{ $step['label'] }}</p>
        </div>
              </div>
              @endforeach
      </div>
          </div>
    </div>

    {{-- Order Items --}}
    <div class="bg-white rounded-2xl shadow-sm p-6">
          <h2 class="text-xl font-semibold mb-6">Sản phẩm đã đặt</h2>
          <div class="space-y-4">
      @foreach($order->items as $item)
      <div class="flex gap-4 p-4 bg-neutral-50 rounded-xl">
              @if($item->suit_preview_url)
              <img src="{{ $item->suit_preview_url }}" alt="{{ $item->suit_model_name }}" class="w-24 h-24 object-cover rounded-lg bg-neutral-100">
              @else
              <div class="w-24 h-24 bg-neutral-200 rounded-lg flex items-center justify-center">
        <svg class="w-12 h-12 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5"/>
        </svg>
              </div>
              @endif
              <div class="flex-1">
        <h3 class="font-semibold">{{ $item->suit_model_name }}</h3>
        <p class="text-sm text-neutral-600 mt-1">{{ $item->fabric_name }}</p>
        @if($item->options_summary)
        <p class="text-xs text-neutral-500 mt-1">{{ $item->options_summary }}</p>
        @endif
        <div class="flex items-center justify-between mt-3">
                  <span class="text-sm text-neutral-500">SL: {{ $item->quantity }}</span>
                  <span class="font-semibold text-primary-600">{{ number_format($item->total_price, 0, ',', '.') }}₫</span>
        </div>
              </div>
      </div>
      @endforeach
          </div>
    </div>

    {{-- Customer Info --}}
    <div class="bg-white rounded-2xl shadow-sm p-6">
          <h2 class="text-xl font-semibold mb-6">Thông tin khách hàng</h2>
          <div class="grid md:grid-cols-2 gap-6">
      <div>
              <h3 class="text-sm font-medium text-neutral-500 mb-2">Người nhận</h3>
              <p class="font-medium">{{ $order->customer_name }}</p>
              <p class="text-neutral-600">{{ $order->customer_phone }}</p>
              <p class="text-neutral-600">{{ $order->customer_email }}</p>
      </div>
      <div>
              <h3 class="text-sm font-medium text-neutral-500 mb-2">Địa chỉ giao hàng</h3>
              <p class="text-neutral-600">{{ $order->shipping_address }}</p>
              @if($order->shipping_district)
              <p class="text-neutral-600">{{ $order->shipping_district }}, {{ $order->shipping_city }}</p>
              @endif
      </div>
          </div>
          @if($order->notes)
          <div class="mt-6 pt-6 border-t border-neutral-100">
      <h3 class="text-sm font-medium text-neutral-500 mb-2">Ghi chú</h3>
      <p class="text-neutral-600">{{ $order->notes }}</p>
          </div>
          @endif
    </div>
      </div>

      {{-- Sidebar --}}
      <div class="space-y-6">
    {{-- Payment Summary --}}
    <div class="bg-white rounded-2xl shadow-sm p-6 sticky top-24">
          <h2 class="text-xl font-semibold mb-6">Thanh toán</h2>

          <div class="space-y-3 pb-4 border-b border-neutral-100">
      <div class="flex justify-between">
              <span class="text-neutral-600">Tạm tính</span>
              <span>{{ number_format($order->subtotal, 0, ',', '.') }}₫</span>
      </div>
      @if($order->discount_amount > 0)
      <div class="flex justify-between text-green-600">
              <span>Giảm giá</span>
              <span>-{{ number_format($order->discount_amount, 0, ',', '.') }}₫</span>
      </div>
      @endif
      <div class="flex justify-between">
              <span class="text-neutral-600">Phí vận chuyển</span>
              <span>{{ $order->shipping_fee > 0 ? number_format($order->shipping_fee, 0, ',', '.') . '₫' : 'Miễn phí' }}</span>
      </div>
          </div>

          <div class="flex justify-between items-center pt-4 mb-6">
      <span class="text-lg font-semibold">Tổng cộng</span>
      <span class="text-2xl font-bold text-primary-600">{{ $order->formatted_total }}</span>
          </div>

          {{-- Payment Status --}}
          <div class="p-4 rounded-xl {{ $order->payment_status === 'paid' ? 'bg-green-50' : 'bg-yellow-50' }}">
      <div class="flex items-center gap-3">
              @if($order->payment_status === 'paid')
              <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
              <div>
        <p class="font-medium text-green-700">Đã thanh toán</p>
        <p class="text-sm text-green-600">{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</p>
              </div>
              @else
              <svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
              <div>
        <p class="font-medium text-yellow-700">Chờ thanh toán</p>
        <p class="text-sm text-yellow-600">{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</p>
              </div>
              @endif
      </div>
          </div>

          {{-- Actions --}}
          <div class="mt-6 space-y-3">
      @if($order->order_status === 'pending')
      <button class="w-full btn-secondary text-red-600 border-red-200 hover:bg-red-50">
              Hủy đơn hàng
      </button>
      @endif
      <a href="{{ route('orders.index') }}" class="block w-full btn-ghost text-center">
              ← Quay lại danh sách
      </a>
          </div>
    </div>

    {{-- Need Help --}}
    <div class="bg-neutral-900 text-white rounded-2xl p-6">
          <h3 class="text-lg font-semibold mb-2">Cần hỗ trợ?</h3>
          <p class="text-neutral-400 text-sm mb-4">Liên hệ với chúng tôi để được hỗ trợ về đơn hàng</p>
          <a href="tel:0901234567" class="flex items-center gap-2 text-primary-400 hover:text-primary-300">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
      </svg>
      0901 234 567
          </a>
    </div>
      </div>
  </div>
  </div>
</div>
@endsection
