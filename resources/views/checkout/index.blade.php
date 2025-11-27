@extends('layouts.app')

@section('title', 'Thanh toán | Suit Configurator')

@section('content')
<div class="min-h-screen bg-neutral-50">
  {{-- Breadcrumb --}}
  <nav class="bg-white border-b border-neutral-200">
  <div class="container mx-auto px-4 py-3">
      <ol class="flex items-center space-x-2 text-sm">
    <li><a href="{{ route('home') }}" class="text-neutral-500 hover:text-primary-600">Trang chủ</a></li>
    <li class="text-neutral-400">/</li>
    <li><a href="{{ route('cart.index') }}" class="text-neutral-500 hover:text-primary-600">Giỏ hàng</a></li>
    <li class="text-neutral-400">/</li>
    <li class="text-primary-600 font-medium">Thanh toán</li>
      </ol>
  </div>
  </nav>

  <div class="container mx-auto px-4 py-8">
  <h1 class="text-3xl font-serif font-bold mb-8">Thanh toán</h1>

  @if(session('error'))
  <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg text-red-600">
      {{ session('error') }}
  </div>
  @endif

  <form action="{{ route('checkout.process') }}" method="POST" x-data="checkoutForm()">
      @csrf

      <div class="flex flex-col lg:flex-row gap-8">
    {{-- Checkout Form --}}
    <div class="lg:w-2/3 space-y-6">
          {{-- Customer Information --}}
          <div class="bg-white rounded-2xl shadow-sm p-6">
      <h2 class="text-xl font-semibold mb-6 flex items-center">
              <span class="w-8 h-8 bg-primary-600 text-white rounded-full flex items-center justify-center text-sm mr-3">1</span>
              Thông tin khách hàng
      </h2>

      <div class="grid md:grid-cols-2 gap-4">
              <div>
        <label class="block text-sm font-medium text-neutral-700 mb-2">
                  Họ và tên <span class="text-red-500">*</span>
        </label>
        <input
                  type="text"
                  name="customer_name"
                  value="{{ old('customer_name', auth()->user()->name ?? '') }}"
                  class="w-full px-4 py-3 border border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('customer_name') border-red-500 @enderror"
                  required
        >
        @error('customer_name')
        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
        @enderror
              </div>

              <div>
        <label class="block text-sm font-medium text-neutral-700 mb-2">
                  Số điện thoại <span class="text-red-500">*</span>
        </label>
        <input
                  type="tel"
                  name="customer_phone"
                  value="{{ old('customer_phone', auth()->user()->phone ?? '') }}"
                  class="w-full px-4 py-3 border border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('customer_phone') border-red-500 @enderror"
                  required
        >
        @error('customer_phone')
        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
        @enderror
              </div>

              <div class="md:col-span-2">
        <label class="block text-sm font-medium text-neutral-700 mb-2">
                  Email <span class="text-red-500">*</span>
        </label>
        <input
                  type="email"
                  name="customer_email"
                  value="{{ old('customer_email', auth()->user()->email ?? '') }}"
                  class="w-full px-4 py-3 border border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('customer_email') border-red-500 @enderror"
                  required
        >
        @error('customer_email')
        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
        @enderror
              </div>
      </div>
          </div>

          {{-- Shipping Address --}}
          <div class="bg-white rounded-2xl shadow-sm p-6">
      <h2 class="text-xl font-semibold mb-6 flex items-center">
              <span class="w-8 h-8 bg-primary-600 text-white rounded-full flex items-center justify-center text-sm mr-3">2</span>
              Địa chỉ giao hàng
      </h2>

      <div class="grid md:grid-cols-2 gap-4">
              <div>
        <label class="block text-sm font-medium text-neutral-700 mb-2">
                  Tỉnh/Thành phố <span class="text-red-500">*</span>
        </label>
        <select
                  name="city"
                  x-model="city"
                  class="w-full px-4 py-3 border border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('city') border-red-500 @enderror"
                  required
        >
                  <option value="">-- Chọn tỉnh/thành --</option>
                  <option value="TP. Hồ Chí Minh">TP. Hồ Chí Minh</option>
                  <option value="Hà Nội">Hà Nội</option>
                  <option value="Đà Nẵng">Đà Nẵng</option>
                  <option value="Cần Thơ">Cần Thơ</option>
                  <option value="Hải Phòng">Hải Phòng</option>
                  <option value="Khác">Khác</option>
        </select>
        @error('city')
        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
        @enderror
              </div>

              <div>
        <label class="block text-sm font-medium text-neutral-700 mb-2">
                  Quận/Huyện <span class="text-red-500">*</span>
        </label>
        <input
                  type="text"
                  name="district"
                  value="{{ old('district') }}"
                  class="w-full px-4 py-3 border border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('district') border-red-500 @enderror"
                  required
        >
        @error('district')
        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
        @enderror
              </div>

              <div>
        <label class="block text-sm font-medium text-neutral-700 mb-2">
                  Phường/Xã
        </label>
        <input
                  type="text"
                  name="ward"
                  value="{{ old('ward') }}"
                  class="w-full px-4 py-3 border border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
        >
              </div>

              <div class="md:col-span-2">
        <label class="block text-sm font-medium text-neutral-700 mb-2">
                  Địa chỉ chi tiết <span class="text-red-500">*</span>
        </label>
        <input
                  type="text"
                  name="shipping_address"
                  value="{{ old('shipping_address') }}"
                  placeholder="Số nhà, tên đường..."
                  class="w-full px-4 py-3 border border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('shipping_address') border-red-500 @enderror"
                  required
        >
        @error('shipping_address')
        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
        @enderror
              </div>

              <div class="md:col-span-2">
        <label class="block text-sm font-medium text-neutral-700 mb-2">
                  Ghi chú đơn hàng
        </label>
        <textarea
                  name="customer_notes"
                  rows="3"
                  placeholder="Ghi chú về đơn hàng, thời gian giao hàng thuận tiện..."
                  class="w-full px-4 py-3 border border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent resize-none"
        >{{ old('customer_notes') }}</textarea>
              </div>
      </div>
          </div>

          {{-- Payment Method --}}
          <div class="bg-white rounded-2xl shadow-sm p-6">
      <h2 class="text-xl font-semibold mb-6 flex items-center">
              <span class="w-8 h-8 bg-primary-600 text-white rounded-full flex items-center justify-center text-sm mr-3">3</span>
              Phương thức thanh toán
      </h2>

      <div class="space-y-3">
              @php
              $paymentMethods = [
        ['value' => 'cod', 'label' => 'Thanh toán khi nhận hàng (COD)', 'icon' => 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z'],
        ['value' => 'bank_transfer', 'label' => 'Chuyển khoản ngân hàng', 'icon' => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z'],
        ['value' => 'momo', 'label' => 'Ví MoMo', 'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
        ['value' => 'vnpay', 'label' => 'VNPay', 'icon' => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z'],
              ];
              @endphp

              @foreach($paymentMethods as $method)
              <label class="flex items-center p-4 border border-neutral-200 rounded-lg cursor-pointer hover:border-primary-400 transition-colors has-[:checked]:border-primary-600 has-[:checked]:bg-primary-50">
        <input
                  type="radio"
                  name="payment_method"
                  value="{{ $method['value'] }}"
                  class="w-5 h-5 text-primary-600 border-neutral-300 focus:ring-primary-500"
                  {{ old('payment_method', 'cod') === $method['value'] ? 'checked' : '' }}
                  required
        >
        <svg class="w-6 h-6 mx-4 text-neutral-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $method['icon'] }}"/>
        </svg>
        <span class="font-medium">{{ $method['label'] }}</span>
              </label>
              @endforeach
      </div>
      @error('payment_method')
      <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
      @enderror
          </div>
    </div>

    {{-- Order Summary --}}
    <div class="lg:w-1/3">
          <div class="bg-white rounded-2xl shadow-sm p-6 sticky top-24">
      <h2 class="text-xl font-semibold mb-6">Đơn hàng của bạn</h2>

      {{-- Order Items --}}
      <div class="space-y-4 max-h-64 overflow-y-auto mb-6">
              @foreach($items as $item)
              <div class="flex items-center gap-3">
        <div class="w-16 h-20 bg-neutral-100 rounded-lg flex-shrink-0 overflow-hidden">
                  @if($item->configuration && $item->configuration->preview_image)
          <img src="{{ $item->configuration->preview_image }}" alt="Preview" class="w-full h-full object-cover">
                  @else
          <div class="w-full h-full flex items-center justify-center text-neutral-400">
                      <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                      </svg>
          </div>
                  @endif
        </div>
        <div class="flex-grow">
                  <p class="font-medium text-sm">{{ $item->suitModel->name_vi ?? 'Vest may đo' }}</p>
                  <p class="text-xs text-neutral-500">{{ $item->fabric->name_vi ?? 'N/A' }}</p>
                  <p class="text-xs text-neutral-500">SL: {{ $item->quantity }}</p>
        </div>
        <p class="font-medium text-sm">{{ number_format($item->total_price, 0, ',', '.') }}₫</p>
              </div>
              @endforeach
      </div>

      {{-- Totals --}}
      <div class="space-y-3 border-t border-neutral-200 pt-4">
              <div class="flex justify-between text-neutral-600">
        <span>Tạm tính</span>
        <span>{{ number_format($cart->subtotal, 0, ',', '.') }} ₫</span>
              </div>
              <div class="flex justify-between text-neutral-600">
        <span>Phí vận chuyển</span>
        <span>
                  @if($cart->shipping_fee > 0)
          {{ number_format($cart->shipping_fee, 0, ',', '.') }} ₫
                  @else
          <span class="text-green-600">Miễn phí</span>
                  @endif
        </span>
              </div>
              @if($cart->discount > 0)
              <div class="flex justify-between text-green-600">
        <span>Giảm giá</span>
        <span>-{{ number_format($cart->discount, 0, ',', '.') }} ₫</span>
              </div>
              @endif
      </div>

      <div class="border-t border-neutral-200 mt-4 pt-4">
              <div class="flex justify-between items-center">
        <span class="text-lg font-semibold">Tổng cộng</span>
        <span class="text-2xl font-bold text-primary-600">{{ number_format($cart->total, 0, ',', '.') }} ₫</span>
              </div>
      </div>

      <button
              type="submit"
              class="w-full mt-6 btn-primary text-center text-lg py-4"
      >
              Đặt hàng
      </button>

      <p class="mt-4 text-xs text-neutral-500 text-center">
              Bằng việc đặt hàng, bạn đồng ý với
              <a href="#" class="text-primary-600 hover:underline">Điều khoản dịch vụ</a>
              và
              <a href="#" class="text-primary-600 hover:underline">Chính sách bảo mật</a>
      </p>
          </div>
    </div>
      </div>
  </form>
  </div>
</div>

@push('scripts')
<script>
function checkoutForm() {
  return {
  city: '{{ old('city') }}'
  }
}
</script>
@endpush
@endsection
