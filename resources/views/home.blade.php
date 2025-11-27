@extends('layouts.app')

@section('title', 'May Đo Vest Nam Cao Cấp | Suit Configurator - Thiết Kế Vest Theo Phong Cách Riêng')
@section('description', 'Thiết kế vest nam may đo cao cấp theo phong cách riêng của bạn. Hơn 500 mẫu vải nhập khẩu từ Ý, Anh, Nhật. Bảo hành trọn đời. Giao hàng toàn quốc.')
@section('keywords', 'vest nam may đo, suit nam cao cấp, thiết kế vest online, may vest cưới, vest công sở, vải vest nhập khẩu, suit configurator')
@section('og_image', asset('images/og-home.jpg'))

@section('structured_data')
{{-- BreadcrumbList Schema --}}
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "BreadcrumbList",
  "itemListElement": [{
  "@@type": "ListItem",
  "position": 1,
  "name": "Trang chủ",
  "item": "{{ route('home') }}"
  }]
}
</script>

{{-- WebPage Schema --}}
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "WebPage",
  "name": "May Đo Vest Nam Cao Cấp | Suit Configurator",
  "description": "Thiết kế vest nam may đo cao cấp theo phong cách riêng của bạn",
  "url": "{{ route('home') }}",
  "mainEntity": {
  "@@type": "Service",
  "name": "Dịch vụ may đo vest nam",
  "description": "Thiết kế và may đo vest nam cao cấp theo số đo cá nhân",
  "provider": {
      "@@type": "ClothingStore",
      "name": "Suit Configurator"
  },
  "areaServed": {
      "@@type": "Country",
      "name": "Việt Nam"
  }
  }
}
</script>
@endsection

@section('content')
<section class="relative h-[90vh] min-h-[600px] flex items-center">
  <div class="absolute inset-0 bg-gradient-to-r from-neutral-900/80 to-neutral-900/40 z-10"></div>
  <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ asset('images/hero-suit.jpg') }}');"></div>
  <div class="container mx-auto px-4 relative z-20">
  <div class="max-w-2xl">
      <h1 class="text-4xl md:text-5xl lg:text-6xl font-serif font-bold text-white mb-6 leading-tight">
    May Đo Vest Nam<br>
    <span class="text-primary-400">Theo Phong Cách Riêng</span>
      </h1>
      <p class="text-lg text-neutral-200 mb-8 leading-relaxed">
    Tự thiết kế bộ vest hoàn hảo cho bạn.
      </p>
      <div class="flex flex-wrap gap-4">
    <a href="{{ route('configurator.index') }}" class="btn-primary text-lg px-8 py-4">Bắt đầu thiết kế</a>
    <a href="{{ route('fabrics.index') }}" class="btn-outline-light text-lg px-8 py-4">Xem bộ sưu tập vải</a>
      </div>
  </div>
  </div>
</section>

<section class="py-20 bg-white">
  <div class="container mx-auto px-4">
  <div class="text-center mb-16">
      <h2 class="text-3xl md:text-4xl font-serif font-bold mb-4">Quy Trình May Đo</h2>
      <p class="text-neutral-600 max-w-2xl mx-auto">Chỉ với 4 bước đơn giản</p>
  </div>
  <div class="grid md:grid-cols-4 gap-8">
      <div class="text-center">
    <div class="w-24 h-24 mx-auto mb-6 bg-primary-100 rounded-full flex items-center justify-center">
          <span class="text-2xl">1</span>
    </div>
    <h3 class="text-lg font-semibold mb-2">Chọn kiểu vest</h3>
      </div>
      <div class="text-center">
    <div class="w-24 h-24 mx-auto mb-6 bg-primary-100 rounded-full flex items-center justify-center">
          <span class="text-2xl">2</span>
    </div>
    <h3 class="text-lg font-semibold mb-2">Chọn vải</h3>
      </div>
      <div class="text-center">
    <div class="w-24 h-24 mx-auto mb-6 bg-primary-100 rounded-full flex items-center justify-center">
          <span class="text-2xl">3</span>
    </div>
    <h3 class="text-lg font-semibold mb-2">Tùy chỉnh</h3>
      </div>
      <div class="text-center">
    <div class="w-24 h-24 mx-auto mb-6 bg-primary-100 rounded-full flex items-center justify-center">
          <span class="text-2xl">4</span>
    </div>
    <h3 class="text-lg font-semibold mb-2">Đặt hàng</h3>
      </div>
  </div>
  </div>
</section>

<section class="py-20 bg-neutral-50">
  <div class="container mx-auto px-4">
  <h2 class="text-3xl md:text-4xl font-serif font-bold mb-12">Kiểu Vest Nổi Bật</h2>
  <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
      @forelse($featuredModels as $model)
      <a href="{{ route('configurator.index') }}?model={{ $model->id }}" class="group bg-white rounded-2xl shadow-md overflow-hidden hover:shadow-xl transition-all">
    <div class="aspect-[3/4] bg-neutral-100 overflow-hidden">
          <img src="{{ $model->thumbnail_url ?? asset('images/placeholder.jpg') }}" alt="{{ $model->name_vi }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
    </div>
    <div class="p-4">
          <h3 class="font-semibold text-lg">{{ $model->name_vi }}</h3>
          <p class="text-primary-600 font-bold mt-2">Từ {{ number_format($model->base_price, 0, ',', '.') }} ₫</p>
    </div>
      </a>
      @empty
      <div class="col-span-full text-center py-12 text-neutral-500">Chưa có mẫu vest nào.</div>
      @endforelse
  </div>
  </div>
</section>

<section class="py-20 bg-white">
  <div class="container mx-auto px-4">
  <h2 class="text-3xl md:text-4xl font-serif font-bold mb-12">Bộ Sưu Tập Vải</h2>
  <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-8 gap-4">
      @forelse($featuredFabrics as $fabric)
      <a href="{{ route('configurator.index') }}?fabric={{ $fabric->id }}" class="group">
    <div class="aspect-square rounded-xl overflow-hidden border-2 border-neutral-200 group-hover:border-primary-400">
          <img src="{{ $fabric->thumbnail_url ?? asset('images/placeholder.jpg') }}" alt="{{ $fabric->name_vi }}" class="w-full h-full object-cover">
    </div>
    <p class="text-sm font-medium mt-2 truncate">{{ $fabric->name_vi }}</p>
      </a>
      @empty
      <div class="col-span-full text-center py-12 text-neutral-500">Chưa có vải nào.</div>
      @endforelse
  </div>
  </div>
</section>

<section class="py-20 bg-neutral-900 text-white">
  <div class="container mx-auto px-4">
  <h2 class="text-3xl md:text-4xl font-serif font-bold mb-12 text-center">Danh Mục Vải</h2>
  <div class="grid md:grid-cols-3 lg:grid-cols-5 gap-6">
      @forelse($categories as $category)
      <a href="{{ route('fabrics.category', $category->slug) }}" class="block bg-neutral-800 rounded-xl p-6 text-center hover:bg-neutral-700">
    <h3 class="font-semibold mb-2">{{ $category->name_vi }}</h3>
    <p class="text-sm text-neutral-400">{{ $category->fabrics_count ?? 0 }} loại vải</p>
      </a>
      @empty
      <div class="col-span-full text-center py-12 text-neutral-500">Chưa có danh mục nào.</div>
      @endforelse
  </div>
  </div>
</section>

<section class="py-20 bg-primary-600">
  <div class="container mx-auto px-4 text-center">
  <h2 class="text-3xl md:text-4xl font-serif font-bold text-white mb-4">Sẵn sàng sở hữu bộ vest hoàn hảo?</h2>
  <p class="text-primary-100 mb-8">Bắt đầu thiết kế ngay hôm nay</p>
  <a href="{{ route('configurator.index') }}" class="inline-flex items-center px-8 py-4 bg-white text-primary-600 font-semibold rounded-full hover:bg-primary-50">Thiết kế vest ngay</a>
  </div>
</section>
@endsection
