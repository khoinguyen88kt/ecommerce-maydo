@extends('layouts.app')

@section('title', 'Bộ Sưu Tập Vải Cao Cấp Nhập Khẩu | Suit Configurator')
@section('description', 'Khám phá bộ sưu tập vải cao cấp nhập khẩu từ Ý, Anh, Nhật cho vest may đo. Wool, Cashmere, Linen và hơn 500 mẫu vải với giá từ ' . ($fabrics->min('price_per_meter') ? number_format($fabrics->min('price_per_meter'), 0, ',', '.') . '₫/m' : '') . '.')
@section('keywords', 'vải vest, vải wool, vải cashmere, vải linen, vải nhập khẩu Ý, vải Loro Piana, vải Zegna, vải vest cao cấp')
@section('og_image', asset('images/og-fabrics.jpg'))

@section('structured_data')
{{-- BreadcrumbList Schema --}}
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
      "name": "Bộ sưu tập vải",
      "item": "{{ route('fabrics.index') }}"
  }
  ]
}
</script>

{{-- CollectionPage Schema --}}
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "CollectionPage",
  "name": "Bộ Sưu Tập Vải Cao Cấp",
  "description": "Khám phá bộ sưu tập vải cao cấp nhập khẩu từ Ý, Anh, Nhật cho vest may đo",
  "url": "{{ route('fabrics.index') }}",
  "isPartOf": {
  "@@type": "WebSite",
  "name": "Suit Configurator",
  "url": "{{ config('app.url') }}"
  }
}
</script>
@endsection

@section('content')
{{-- Hero Section --}}
<section class="relative h-96 bg-neutral-900 overflow-hidden">
  <div class="absolute inset-0">
  <img src="https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=1920" alt="Fabric collection" class="w-full h-full object-cover opacity-40">
  </div>
  <div class="relative container mx-auto px-4 h-full flex items-center">
  <div class="max-w-2xl text-white">
      <span class="text-primary-400 font-medium tracking-wider uppercase text-sm">Chất liệu cao cấp</span>
      <h1 class="text-5xl font-serif font-bold mt-3 mb-6">Bộ sưu tập vải</h1>
      <p class="text-xl text-neutral-300 leading-relaxed">
    Hơn 500 mẫu vải được tuyển chọn kỹ lưỡng từ các nhà sản xuất hàng đầu thế giới.
      </p>
  </div>
  </div>
</section>

{{-- Categories --}}
<section class="py-12 bg-white border-b border-neutral-100">
  <div class="container mx-auto px-4">
  <div class="flex flex-wrap items-center justify-center gap-4">
      @foreach($categories as $category)
      <a href="{{ route('fabrics.index', ['category' => $category->slug]) }}"
    class="px-6 py-3 rounded-full border transition-colors {{ request('category') === $category->slug ? 'bg-primary-600 text-white border-primary-600' : 'border-neutral-200 hover:border-primary-600 hover:text-primary-600' }}">
    {{ $category->name_vi }}
      </a>
      @endforeach
  </div>
  </div>
</section>

{{-- Fabrics Grid --}}
<section class="py-16 bg-neutral-50">
  <div class="container mx-auto px-4">
  @if($fabrics->count() > 0)
  <div class="grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
      @foreach($fabrics as $fabric)
      <div class="group bg-white rounded-2xl shadow-sm overflow-hidden hover:shadow-lg transition-all">
    <div class="relative aspect-square">
          @if($fabric->image_url)
          <img src="{{ $fabric->image_url }}" alt="{{ $fabric->name_vi }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
          @else
          <div class="w-full h-full bg-neutral-200 flex items-center justify-center">
      <span class="text-neutral-400">Hình ảnh</span>
          </div>
          @endif
          @if($fabric->is_premium)
          <span class="absolute top-4 left-4 bg-amber-500 text-white text-xs font-semibold px-3 py-1 rounded-full">
      Premium
          </span>
          @endif
    </div>
    <div class="p-6">
          <div class="flex items-start justify-between gap-4 mb-3">
      <div>
              <h3 class="font-semibold text-lg">{{ $fabric->name_vi }}</h3>
              <p class="text-sm text-neutral-500">{{ $fabric->category->name_vi }}</p>
      </div>
      @if($fabric->color_code)
      <div class="w-8 h-8 rounded-full border-2 border-white shadow-md flex-shrink-0" style="background-color: {{ $fabric->color_code }}"></div>
      @endif
          </div>
          <p class="text-sm text-neutral-600 mb-4 line-clamp-2">{{ $fabric->description_vi }}</p>
          <div class="flex items-center justify-between">
      <div>
              <span class="text-lg font-bold text-primary-600">{{ number_format($fabric->price_per_meter, 0, ',', '.') }}₫</span>
              <span class="text-sm text-neutral-500">/m</span>
      </div>
      <a href="{{ route('configurator.index') }}?fabric={{ $fabric->id }}" class="text-sm font-medium text-primary-600 hover:text-primary-700">
              Chọn vải →
      </a>
          </div>
    </div>
      </div>
      @endforeach
  </div>

  {{-- Pagination --}}
  <div class="mt-12">
      {{ $fabrics->links() }}
  </div>
  @else
  <div class="text-center py-16">
      <p class="text-neutral-500">Không tìm thấy vải nào trong danh mục này.</p>
  </div>
  @endif
  </div>
</section>

{{-- Fabric Info --}}
<section class="py-20 bg-white">
  <div class="container mx-auto px-4">
  <div class="text-center mb-16">
      <h2 class="text-3xl font-serif font-bold">Chất liệu cao cấp</h2>
      <p class="text-neutral-600 mt-2">Hiểu về các loại vải để chọn lựa phù hợp nhất</p>
  </div>

  <div class="grid md:grid-cols-3 gap-8 max-w-5xl mx-auto">
      <div class="text-center">
    <div class="w-20 h-20 bg-primary-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
          <span class="text-3xl">🐑</span>
    </div>
    <h3 class="text-xl font-semibold mb-3">Wool (Len)</h3>
    <p class="text-neutral-600 text-sm leading-relaxed">
          Vải wool là lựa chọn phổ biến nhất cho vest. Thoáng khí, giữ form tốt, phù hợp quanh năm.
          Độ dày từ 220g - 340g/m² tùy loại.
    </p>
      </div>
      <div class="text-center">
    <div class="w-20 h-20 bg-primary-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
          <span class="text-3xl">🌿</span>
    </div>
    <h3 class="text-xl font-semibold mb-3">Linen (Lanh)</h3>
    <p class="text-neutral-600 text-sm leading-relaxed">
          Vải lanh mát mẻ, lý tưởng cho mùa hè và khí hậu nhiệt đới. Tạo vẻ ngoài casual,
          thoải mái mà vẫn lịch lãm.
    </p>
      </div>
      <div class="text-center">
    <div class="w-20 h-20 bg-primary-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
          <span class="text-3xl">💎</span>
    </div>
    <h3 class="text-xl font-semibold mb-3">Cashmere</h3>
    <p class="text-neutral-600 text-sm leading-relaxed">
          Vải cashmere cao cấp nhất, mềm mại và ấm áp. Phù hợp cho những dịp đặc biệt
          và mùa đông lạnh.
    </p>
      </div>
  </div>
  </div>
</section>

{{-- Origin --}}
<section class="py-20 bg-neutral-900 text-white">
  <div class="container mx-auto px-4">
  <div class="text-center mb-16">
      <h2 class="text-3xl font-serif font-bold">Nguồn gốc vải</h2>
      <p class="text-neutral-400 mt-2">Nhập khẩu từ các nhà sản xuất hàng đầu thế giới</p>
  </div>

  <div class="grid md:grid-cols-4 gap-8 max-w-4xl mx-auto">
      @php
      $origins = [
    ['flag' => '🇮🇹', 'country' => 'Ý', 'brands' => 'Loro Piana, Ermenegildo Zegna, Vitale Barberis'],
    ['flag' => '🇬🇧', 'country' => 'Anh', 'brands' => 'Holland & Sherry, Dormeuil'],
    ['flag' => '🇯🇵', 'country' => 'Nhật', 'brands' => 'Miyuki, Nikke'],
    ['flag' => '🇨🇳', 'country' => 'Trung Quốc', 'brands' => 'Nanapan, Sunshine'],
      ];
      @endphp
      @foreach($origins as $origin)
      <div class="text-center">
    <div class="text-5xl mb-4">{{ $origin['flag'] }}</div>
    <h3 class="text-xl font-semibold mb-2">{{ $origin['country'] }}</h3>
    <p class="text-neutral-400 text-sm">{{ $origin['brands'] }}</p>
      </div>
      @endforeach
  </div>
  </div>
</section>

{{-- CTA --}}
<section class="py-16 bg-primary-600 text-white">
  <div class="container mx-auto px-4 text-center">
  <h2 class="text-3xl font-serif font-bold mb-4">Sẵn sàng chọn vải cho bộ vest của bạn?</h2>
  <p class="text-xl text-primary-100 mb-8 max-w-2xl mx-auto">
      Bắt đầu thiết kế ngay hoặc đến showroom để cảm nhận chất vải thực tế.
  </p>
  <div class="flex flex-col sm:flex-row gap-4 justify-center">
      <a href="{{ route('configurator.index') }}" class="inline-block bg-white text-primary-600 px-8 py-4 rounded-lg font-semibold hover:bg-neutral-100 transition-colors">
    Thiết kế vest ngay
      </a>
      <a href="{{ route('contact') }}" class="inline-block border-2 border-white text-white px-8 py-4 rounded-lg font-semibold hover:bg-white/10 transition-colors">
    Đặt lịch thăm showroom
      </a>
  </div>
  </div>
</section>
@endsection
