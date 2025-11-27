@extends('layouts.app')

@section('title', 'Đặt hàng thành công | Suit Configurator')

@section('content')
<div class="min-h-screen bg-neutral-50 py-12">
  <div class="container mx-auto px-4">
  <div class="max-w-2xl mx-auto">
      {{-- Success Message --}}
      <div class="bg-white rounded-2xl shadow-sm p-8 text-center">
    <div class="w-20 h-20 mx-auto mb-6 bg-green-100 rounded-full flex items-center justify-center">
          <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
          </svg>
    </div>

    <h1 class="text-3xl font-serif font-bold text-green-600 mb-2">Đặt hàng thành công!</h1>
    <p class="text-neutral-600 mb-6">
          Cảm ơn bạn đã đặt hàng. Chúng tôi sẽ liên hệ xác nhận trong thời gian sớm nhất.
    </p>

    {{-- Order Info --}}
    <div class="bg-neutral-50 rounded-xl p-6 text-left mb-6">
          <div class="grid grid-cols-2 gap-4">
      <div>
              <p class="text-sm text-neutral-500">Mã đơn hàng</p>
              <p class="font-bold text-primary-600">{{ $order->order_number }}</p>
      </div>
      <div>
              <p class="text-sm text-neutral-500">Tổng tiền</p>
              <p class="font-bold">{{ $order->formatted_total }}</p>
      </div>
      <div>
              <p class="text-sm text-neutral-500">Phương thức thanh toán</p>
              <p class="font-medium">{{ $order->payment_method_label }}</p>
      </div>
      <div>
              <p class="text-sm text-neutral-500">Trạng thái</p>
              <span class="inline-block px-3 py-1 bg-yellow-100 text-yellow-700 text-sm rounded-full">
        {{ $order->status_label }}
              </span>
      </div>
          </div>
    </div>

    @if(session('bank_transfer'))
    {{-- Bank Transfer Info --}}
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 text-left mb-6">
          <h3 class="font-semibold text-blue-800 mb-4 flex items-center">
      <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
      </svg>
      Thông tin chuyển khoản
          </h3>
          <div class="space-y-2 text-sm">
      <p><strong>Ngân hàng:</strong> Vietcombank</p>
      <p><strong>Số tài khoản:</strong> 1234567890123</p>
      <p><strong>Chủ tài khoản:</strong> CÔNG TY SUIT CONFIGURATOR</p>
      <p><strong>Nội dung CK:</strong> <span class="font-mono bg-white px-2 py-1 rounded">{{ $order->order_number }}</span></p>
      <p><strong>Số tiền:</strong> {{ $order->formatted_total }}</p>
          </div>
          <p class="mt-4 text-blue-700 text-sm">
      * Vui lòng chuyển khoản trong vòng 24h để đơn hàng được xử lý.
          </p>
    </div>
    @endif

    {{-- Shipping Info --}}
    <div class="bg-neutral-50 rounded-xl p-6 text-left mb-6">
          <h3 class="font-semibold mb-3">Thông tin giao hàng</h3>
          <div class="text-sm space-y-1">
      <p><strong>Người nhận:</strong> {{ $order->customer_name }}</p>
      <p><strong>Điện thoại:</strong> {{ $order->customer_phone }}</p>
      <p><strong>Địa chỉ:</strong> {{ $order->shipping_address }}, {{ $order->ward ? $order->ward . ', ' : '' }}{{ $order->district }}, {{ $order->city }}</p>
          </div>
    </div>

    {{-- Timeline --}}
    <div class="text-left mb-8">
          <h3 class="font-semibold mb-4">Quy trình xử lý đơn hàng</h3>
          <div class="space-y-4">
      @php
      $steps = [
              ['title' => 'Đặt hàng thành công', 'desc' => 'Đơn hàng đã được ghi nhận', 'active' => true],
              ['title' => 'Xác nhận đơn hàng', 'desc' => 'Nhân viên sẽ liên hệ xác nhận', 'active' => false],
              ['title' => 'Đang may', 'desc' => 'Vest đang được may theo đơn', 'active' => false],
              ['title' => 'Giao hàng', 'desc' => 'Dự kiến 7-10 ngày làm việc', 'active' => false],
      ];
      @endphp

      @foreach($steps as $index => $step)
      <div class="flex items-start">
              <div class="flex-shrink-0 w-8 h-8 rounded-full {{ $step['active'] ? 'bg-green-500' : 'bg-neutral-200' }} flex items-center justify-center">
        @if($step['active'])
        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        @else
        <span class="text-neutral-500 text-sm">{{ $index + 1 }}</span>
        @endif
              </div>
              <div class="ml-4">
        <p class="font-medium {{ $step['active'] ? 'text-green-600' : 'text-neutral-500' }}">{{ $step['title'] }}</p>
        <p class="text-sm text-neutral-500">{{ $step['desc'] }}</p>
              </div>
      </div>
      @endforeach
          </div>
    </div>

    {{-- Actions --}}
    <div class="flex flex-col sm:flex-row gap-4 justify-center">
          <a href="{{ route('home') }}" class="btn-outline">
      Về trang chủ
          </a>
          @auth
          <a href="{{ route('orders.show', $order->order_number) }}" class="btn-primary">
      Xem chi tiết đơn hàng
          </a>
          @endauth
    </div>
      </div>

      {{-- Contact Support --}}
      <div class="mt-8 text-center text-sm text-neutral-600">
    <p>Bạn cần hỗ trợ? Liên hệ hotline: <a href="tel:0123456789" class="text-primary-600 font-medium">0123 456 789</a></p>
    <p>Hoặc email: <a href="mailto:support@suitconfigurator.vn" class="text-primary-600">support@suitconfigurator.vn</a></p>
      </div>
  </div>
  </div>
</div>
@endsection
