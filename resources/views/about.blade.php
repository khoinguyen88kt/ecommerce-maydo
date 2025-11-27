@extends('layouts.app')

@section('title', 'Về Chúng Tôi - Suit Configurator | 20+ Năm Kinh Nghiệm May Đo Vest')
@section('description', 'Câu chuyện thương hiệu Suit Configurator - 20+ năm kinh nghiệm may đo vest cao cấp. 10,000+ khách hàng hài lòng. Đội ngũ thợ may lành nghề hàng đầu Việt Nam.')
@section('keywords', 'về suit configurator, câu chuyện thương hiệu, may đo vest uy tín, thợ may vest cao cấp, showroom vest nam')
@section('og_image', asset('images/og-about.jpg'))

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
      "name": "Về chúng tôi",
      "item": "{{ route('about') }}"
  }
  ]
}
</script>

{{-- AboutPage Schema --}}
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "AboutPage",
  "name": "Về Suit Configurator",
  "description": "Câu chuyện thương hiệu Suit Configurator - 20+ năm kinh nghiệm may đo vest cao cấp",
  "url": "{{ route('about') }}",
  "mainEntity": {
  "@@type": "Organization",
  "name": "Suit Configurator",
  "foundingDate": "2005",
  "numberOfEmployees": {
      "@@type": "QuantitativeValue",
      "value": "50"
  },
  "aggregateRating": {
      "@@type": "AggregateRating",
      "ratingValue": "5",
      "reviewCount": "10000",
      "bestRating": "5"
  }
  }
}
</script>
@endsection

@section('content')
{{-- Hero Section --}}
<section class="relative h-96 bg-neutral-900 overflow-hidden">
  <div class="absolute inset-0 bg-gradient-to-r from-neutral-900 via-neutral-900/80 to-transparent">
  <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=1920" alt="Tailor workshop" class="w-full h-full object-cover opacity-50">
  </div>
  <div class="relative container mx-auto px-4 h-full flex items-center">
  <div class="max-w-2xl text-white">
      <h1 class="text-5xl font-serif font-bold mb-6">Về chúng tôi</h1>
      <p class="text-xl text-neutral-300 leading-relaxed">
    Kiến tạo phong cách - Định hình thành công
      </p>
  </div>
  </div>
</section>

{{-- Story Section --}}
<section class="py-20 bg-white">
  <div class="container mx-auto px-4">
  <div class="grid lg:grid-cols-2 gap-16 items-center">
      <div>
    <span class="text-primary-600 font-medium tracking-wider uppercase text-sm">Câu chuyện của chúng tôi</span>
    <h2 class="text-4xl font-serif font-bold mt-3 mb-6">Đam mê từ những mũi kim đầu tiên</h2>
    <div class="space-y-4 text-neutral-600 leading-relaxed">
          <p>
      Suit Configurator ra đời từ niềm đam mê bất tận với nghệ thuật may đo và khát vọng mang đến cho quý ông Việt Nam những bộ vest hoàn hảo nhất.
          </p>
          <p>
      Với đội ngũ thợ may lành nghề hơn 20 năm kinh nghiệm, chúng tôi tự hào đã phục vụ hơn 10,000 khách hàng, từ những doanh nhân thành đạt đến các chú rể trong ngày trọng đại.
          </p>
          <p>
      Mỗi bộ vest tại Suit Configurator không chỉ là trang phục - đó là tuyên ngôn về phong cách, sự tự tin và đẳng cấp của người sở hữu.
          </p>
    </div>
      </div>
      <div class="relative">
    <img src="https://images.unsplash.com/photo-1558171813-4c088753af8f?w=800" alt="Tailor at work" class="rounded-2xl shadow-2xl">
    <div class="absolute -bottom-8 -left-8 bg-primary-600 text-white p-8 rounded-2xl shadow-xl">
          <div class="text-5xl font-bold">20+</div>
          <div class="text-primary-100">Năm kinh nghiệm</div>
    </div>
      </div>
  </div>
  </div>
</section>

{{-- Values Section --}}
<section class="py-20 bg-neutral-50">
  <div class="container mx-auto px-4">
  <div class="text-center mb-16">
      <span class="text-primary-600 font-medium tracking-wider uppercase text-sm">Giá trị cốt lõi</span>
      <h2 class="text-4xl font-serif font-bold mt-3">Điều làm nên sự khác biệt</h2>
  </div>
  <div class="grid md:grid-cols-3 gap-8">
      <div class="bg-white p-8 rounded-2xl shadow-sm hover:shadow-lg transition-shadow">
    <div class="w-16 h-16 bg-primary-100 rounded-2xl flex items-center justify-center mb-6">
          <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
          </svg>
    </div>
    <h3 class="text-xl font-semibold mb-3">Công nghệ hiện đại</h3>
    <p class="text-neutral-600">
          Trình thiết kế 3D trực tuyến cho phép bạn tùy chỉnh và xem trước bộ vest của mình trước khi đặt hàng.
    </p>
      </div>
      <div class="bg-white p-8 rounded-2xl shadow-sm hover:shadow-lg transition-shadow">
    <div class="w-16 h-16 bg-primary-100 rounded-2xl flex items-center justify-center mb-6">
          <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
    </div>
    <h3 class="text-xl font-semibold mb-3">Giá trị hợp lý</h3>
    <p class="text-neutral-600">
          Với mô hình may đo trực tiếp, chúng tôi cắt giảm chi phí trung gian để mang đến mức giá tốt nhất.
    </p>
      </div>
      <div class="bg-white p-8 rounded-2xl shadow-sm hover:shadow-lg transition-shadow">
    <div class="w-16 h-16 bg-primary-100 rounded-2xl flex items-center justify-center mb-6">
          <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
          </svg>
    </div>
    <h3 class="text-xl font-semibold mb-3">Bảo hành trọn đời</h3>
    <p class="text-neutral-600">
          Mỗi sản phẩm được bảo hành chất lượng suốt đời, kèm dịch vụ sửa chữa và chỉnh size miễn phí.
    </p>
      </div>
  </div>
  </div>
</section>

{{-- Team Section --}}
<section class="py-20 bg-white">
  <div class="container mx-auto px-4">
  <div class="text-center mb-16">
      <span class="text-primary-600 font-medium tracking-wider uppercase text-sm">Đội ngũ</span>
      <h2 class="text-4xl font-serif font-bold mt-3">Những nghệ nhân tài hoa</h2>
  </div>
  <div class="grid md:grid-cols-4 gap-8">
      @php
      $team = [
    ['name' => 'Nguyễn Văn Duy', 'role' => 'Founder & Master Tailor', 'exp' => '25 năm kinh nghiệm'],
    ['name' => 'Trần Thị Minh', 'role' => 'Head of Design', 'exp' => '15 năm kinh nghiệm'],
    ['name' => 'Lê Hoàng Nam', 'role' => 'Senior Pattern Maker', 'exp' => '18 năm kinh nghiệm'],
    ['name' => 'Phạm Thu Hà', 'role' => 'Customer Experience Lead', 'exp' => '10 năm kinh nghiệm'],
      ];
      @endphp
      @foreach($team as $member)
      <div class="text-center group">
    <div class="relative mb-6 overflow-hidden rounded-2xl">
          <div class="aspect-[3/4] bg-neutral-200"></div>
          <div class="absolute inset-0 bg-gradient-to-t from-neutral-900/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-6">
      <p class="text-white text-sm">{{ $member['exp'] }}</p>
          </div>
    </div>
    <h3 class="text-lg font-semibold">{{ $member['name'] }}</h3>
    <p class="text-neutral-500">{{ $member['role'] }}</p>
      </div>
      @endforeach
  </div>
  </div>
</section>

{{-- Stats Section --}}
<section class="py-20 bg-primary-600 text-white">
  <div class="container mx-auto px-4">
  <div class="grid md:grid-cols-4 gap-8 text-center">
      <div>
    <div class="text-5xl font-bold mb-2">10,000+</div>
    <div class="text-primary-100">Khách hàng hài lòng</div>
      </div>
      <div>
    <div class="text-5xl font-bold mb-2">50+</div>
    <div class="text-primary-100">Loại vải cao cấp</div>
      </div>
      <div>
    <div class="text-5xl font-bold mb-2">98%</div>
    <div class="text-primary-100">Tỷ lệ hài lòng</div>
      </div>
      <div>
    <div class="text-5xl font-bold mb-2">5 ⭐</div>
    <div class="text-primary-100">Đánh giá trung bình</div>
      </div>
  </div>
  </div>
</section>

{{-- CTA Section --}}
<section class="py-20 bg-neutral-900 text-white">
  <div class="container mx-auto px-4 text-center">
  <h2 class="text-4xl font-serif font-bold mb-6">Sẵn sàng tạo bộ vest của bạn?</h2>
  <p class="text-xl text-neutral-400 mb-8 max-w-2xl mx-auto">
      Hãy bắt đầu hành trình thiết kế ngay hôm nay và trải nghiệm sự khác biệt.
  </p>
  <a href="{{ route('configurator.index') }}" class="inline-block btn-primary text-lg px-10 py-4">
      Thiết kế ngay
  </a>
  </div>
</section>
@endsection
