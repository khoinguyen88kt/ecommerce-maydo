@extends('layouts.app')

@section('title', 'Giỏ Hàng | Suit Configurator')
@section('description', 'Xem giỏ hàng và tiến hành thanh toán cho đơn hàng vest may đo của bạn tại Suit Configurator.')
@section('keywords', 'giỏ hàng vest, thanh toán vest, đặt vest may đo, mua vest online')

@section('structured_data')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "BreadcrumbList",
  "itemListElement": [
  {
      "@@type": "ListItem",
      "position": 1,
      "name": "Trang chủ",
      "item": "{{ route('home') }}"
  },
  {
      "@@type": "ListItem",
      "position": 2,
      "name": "Giỏ hàng",
      "item": "{{ route('cart.index') }}"
  }
  ]
}
</script>
@endsection

@section('content')
<div x-data="cartManager()" class="min-h-screen bg-neutral-50">
  <nav class="bg-white border-b border-neutral-200">
  <div class="container mx-auto px-4 py-3">
      <ol class="flex items-center space-x-2 text-sm">
    <li><a href="{{ route('home') }}" class="text-neutral-500 hover:text-primary-600">Trang chủ</a></li>
    <li class="text-neutral-400">/</li>
    <li class="text-primary-600 font-medium">Giỏ hàng</li>
      </ol>
  </div>
  </nav>

  <div class="container mx-auto px-4 py-8">
  <h1 class="text-3xl font-serif font-bold mb-8">Giỏ hàng của bạn</h1>

  @if($items->count() > 0)
  <div class="flex flex-col lg:flex-row gap-8">
      <div class="lg:w-2/3">
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
          @foreach($items as $item)
          <div class="p-6 border-b border-neutral-100 last:border-0" x-data="{ quantity: {{ $item->quantity }} }">
      <div class="flex gap-4">
              <div class="w-24 h-32 bg-neutral-100 rounded-lg overflow-hidden flex-shrink-0">
        <div class="w-full h-full flex items-center justify-center text-neutral-400">
                  <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                  </svg>
        </div>
              </div>
              <div class="flex-grow">
        <div class="flex justify-between">
                  <div>
          <h3 class="font-semibold text-lg">{{ $item->suitModel->name_vi ?? 'Vest may đo' }}</h3>
          <p class="text-sm text-neutral-500 mt-1">Vải: {{ $item->fabric->name_vi ?? 'N/A' }}</p>
                  </div>
                  <button x-on:click="removeItem({{ $item->id }})" class="text-neutral-400 hover:text-red-500">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
          </svg>
                  </button>
        </div>
        <div class="flex items-center justify-between mt-4">
                  <div class="flex items-center border border-neutral-200 rounded-lg">
          <button x-on:click="quantity = Math.max(1, quantity - 1); updateQuantity({{ $item->id }}, quantity)" x-bind:disabled="quantity <= 1" class="px-3 py-2 text-neutral-600 hover:text-primary-600 disabled:opacity-50">-</button>
          <span class="px-4 py-2 font-medium" x-text="quantity"></span>
          <button x-on:click="quantity = Math.min(10, quantity + 1); updateQuantity({{ $item->id }}, quantity)" x-bind:disabled="quantity >= 10" class="px-3 py-2 text-neutral-600 hover:text-primary-600 disabled:opacity-50">+</button>
                  </div>
                  <p class="text-lg font-bold text-primary-600">{{ number_format($item->total_price, 0, ',', '.') }} ₫</p>
        </div>
              </div>
      </div>
          </div>
          @endforeach
    </div>
    <div class="mt-6">
          <a href="{{ route('configurator.index') }}" class="inline-flex items-center text-primary-600 font-medium hover:text-primary-700">
      ← Tiếp tục thiết kế
          </a>
    </div>
      </div>

      <div class="lg:w-1/3">
    <div class="bg-white rounded-2xl shadow-sm p-6 sticky top-24">
          <h2 class="text-xl font-semibold mb-6">Tổng đơn hàng</h2>
          <div class="mb-6">
      <label class="block text-sm font-medium text-neutral-700 mb-2">Mã giảm giá</label>
      <div class="flex gap-2">
              <input type="text" x-model="discountCode" placeholder="Nhập mã giảm giá" class="flex-grow px-4 py-2 border border-neutral-200 rounded-lg">
              <button x-on:click="applyDiscount()" class="px-4 py-2 bg-neutral-100 text-neutral-700 font-medium rounded-lg hover:bg-neutral-200">Áp dụng</button>
      </div>
          </div>
          <div class="space-y-3 border-t border-neutral-200 pt-4">
      <div class="flex justify-between text-neutral-600">
              <span>Tạm tính</span>
              <span>{{ number_format($cart->subtotal ?? 0, 0, ',', '.') }} ₫</span>
      </div>
      <div class="flex justify-between text-neutral-600">
              <span>Phí vận chuyển</span>
              <span class="text-green-600">Miễn phí</span>
      </div>
          </div>
          <div class="border-t border-neutral-200 mt-4 pt-4">
      <div class="flex justify-between items-center">
              <span class="text-lg font-semibold">Tổng cộng</span>
              <span class="text-2xl font-bold text-primary-600">{{ number_format($cart->total ?? 0, 0, ',', '.') }} ₫</span>
      </div>
          </div>
          <a href="{{ route('checkout.index') }}" class="block w-full mt-6 btn-primary text-center text-lg py-4">Tiến hành thanh toán</a>
    </div>
      </div>
  </div>
  @else
  <div class="bg-white rounded-2xl shadow-sm p-12 text-center">
      <div class="w-24 h-24 mx-auto mb-6 bg-neutral-100 rounded-full flex items-center justify-center">
    <svg class="w-12 h-12 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
    </svg>
      </div>
      <h2 class="text-2xl font-semibold mb-2">Giỏ hàng trống</h2>
      <p class="text-neutral-600 mb-8">Bạn chưa có sản phẩm nào trong giỏ hàng</p>
      <a href="{{ route('configurator.index') }}" class="btn-primary">Bắt đầu thiết kế vest</a>
  </div>
  @endif
  </div>
</div>

@push('scripts')
<script>
function cartManager() {
  return {
  discountCode: '',
  async updateQuantity(itemId, newQuantity) {
      try {
    const response = await fetch(`/api/cart/item/${itemId}`, {
          method: 'PATCH',
          headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
          body: JSON.stringify({ quantity: newQuantity })
    });
    if ((await response.json()).success) window.location.reload();
      } catch (error) { console.error('Error:', error); }
  },
  async removeItem(itemId) {
      if (!confirm('Bạn có chắc muốn xóa sản phẩm này?')) return;
      try {
    const response = await fetch(`/api/cart/item/${itemId}`, {
          method: 'DELETE',
          headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
    });
    if ((await response.json()).success) window.location.reload();
      } catch (error) { console.error('Error:', error); }
  },
  async applyDiscount() {
      if (!this.discountCode.trim()) return;
      try {
    const response = await fetch('/api/cart/discount', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
          body: JSON.stringify({ code: this.discountCode })
    });
    const data = await response.json();
    if (data.success) window.location.reload();
    else alert(data.message || 'Mã không hợp lệ');
      } catch (error) { console.error('Error:', error); }
  }
  }
}
</script>
@endpush
@endsection
