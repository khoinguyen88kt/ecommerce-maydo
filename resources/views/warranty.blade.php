@extends('layouts.app')

@section('title', 'Bảo Hành Trọn Đời | Suit Configurator - Cam Kết Chất Lượng')
@section('description', 'Chính sách bảo hành trọn đời cho vest may đo tại Suit Configurator. Sửa chữa miễn phí, đổi size khi thay đổi cơ thể, thay cúc và khóa kéo.')
@section('keywords', 'bảo hành vest, chính sách bảo hành, sửa vest, đổi size vest, bảo hành trọn đời')
@section('og_image', asset('images/og-warranty.jpg'))

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
      "name": "Bảo hành trọn đời",
      "item": "{{ route('warranty') }}"
  }
  ]
}
</script>
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "FAQPage",
  "mainEntity": [
  {
      "@@type": "Question",
      "name": "Bảo hành có thời hạn bao lâu?",
      "acceptedAnswer": {
    "@@type": "Answer",
    "text": "Chính sách bảo hành trọn đời có nghĩa là sản phẩm được bảo hành trong suốt vòng đời sử dụng hợp lý, thường là 10-15 năm."
      }
  },
  {
      "@@type": "Question",
      "name": "Tôi có phải trả phí bảo hành không?",
      "acceptedAnswer": {
    "@@type": "Answer",
    "text": "Các lỗi thuộc phạm vi bảo hành sẽ được sửa chữa miễn phí hoàn toàn."
      }
  },
  {
      "@@type": "Question",
      "name": "Làm sao để bảo quản vest đúng cách?",
      "acceptedAnswer": {
    "@@type": "Answer",
    "text": "Treo vest trên mắc áo có vai rộng, tránh gấp. Bảo quản nơi khô thoáng, tránh ánh nắng trực tiếp."
      }
  }
  ]
}
</script>
@endsection

@section('content')
<section class="relative py-20 bg-neutral-900 text-white">
  <div class="container mx-auto px-4 text-center">
  <div class="w-20 h-20 bg-primary-600 rounded-full flex items-center justify-center mx-auto mb-6">
      <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
      </svg>
  </div>
  <h1 class="text-5xl font-serif font-bold mb-4">Bảo hành trọn đời</h1>
  <p class="text-xl text-neutral-300 max-w-2xl mx-auto">Cam kết chất lượng vượt trội. Mọi sản phẩm đều được bảo hành trọn đời.</p>
  </div>
</section>

<section class="py-20 bg-white">
  <div class="container mx-auto px-4">
  <div class="max-w-4xl mx-auto">
      <div class="text-center mb-16">
    <h2 class="text-3xl font-serif font-bold">Phạm vi bảo hành</h2>
    <p class="text-neutral-600 mt-2">Chúng tôi bảo hành toàn diện cho sản phẩm của bạn</p>
      </div>

      <div class="grid md:grid-cols-2 gap-8">
    <div class="bg-green-50 rounded-2xl p-8">
          <div class="flex items-center gap-3 mb-6">
      <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
      </svg>
      <h3 class="text-xl font-semibold text-green-900">Được bảo hành</h3>
          </div>
          <ul class="space-y-4">
      <li class="flex items-start gap-3"><svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span class="text-green-800">Bung chỉ, sút nút áo</span></li>
      <li class="flex items-start gap-3"><svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span class="text-green-800">Lỗi kỹ thuật may</span></li>
      <li class="flex items-start gap-3"><svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span class="text-green-800">Sửa chữa nếu thay đổi số đo cơ thể</span></li>
      <li class="flex items-start gap-3"><svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span class="text-green-800">Thay khóa kéo, cúc áo</span></li>
          </ul>
    </div>

    <div class="bg-red-50 rounded-2xl p-8">
          <div class="flex items-center gap-3 mb-6">
      <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
      </svg>
      <h3 class="text-xl font-semibold text-red-900">Không bảo hành</h3>
          </div>
          <ul class="space-y-4">
      <li class="flex items-start gap-3"><svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg><span class="text-red-800">Hư hỏng do tai nạn, va đập mạnh</span></li>
      <li class="flex items-start gap-3"><svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg><span class="text-red-800">Vết bẩn, ố vàng do không giặt đúng cách</span></li>
      <li class="flex items-start gap-3"><svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg><span class="text-red-800">Cháy, rách do nguyên nhân bên ngoài</span></li>
      <li class="flex items-start gap-3"><svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg><span class="text-red-800">Hao mòn tự nhiên do sử dụng lâu dài</span></li>
          </ul>
    </div>
      </div>
  </div>
  </div>
</section>

<section class="py-20 bg-neutral-50">
  <div class="container mx-auto px-4">
  <div class="text-center mb-16">
      <h2 class="text-3xl font-serif font-bold">Quy trình bảo hành</h2>
      <p class="text-neutral-600 mt-2">Đơn giản và nhanh chóng</p>
  </div>

  <div class="max-w-4xl mx-auto">
      <div class="grid md:grid-cols-4 gap-8">
    <div class="text-center">
          <div class="w-16 h-16 bg-primary-600 rounded-full flex items-center justify-center mx-auto mb-4">
      <span class="text-white text-2xl font-bold">1</span>
          </div>
          <h3 class="font-semibold mb-2">Liên hệ</h3>
          <p class="text-sm text-neutral-600">Gọi hotline hoặc nhắn tin</p>
    </div>
    <div class="text-center">
          <div class="w-16 h-16 bg-primary-600 rounded-full flex items-center justify-center mx-auto mb-4">
      <span class="text-white text-2xl font-bold">2</span>
          </div>
          <h3 class="font-semibold mb-2">Đánh giá</h3>
          <p class="text-sm text-neutral-600">Chuyên gia kiểm tra</p>
    </div>
    <div class="text-center">
          <div class="w-16 h-16 bg-primary-600 rounded-full flex items-center justify-center mx-auto mb-4">
      <span class="text-white text-2xl font-bold">3</span>
          </div>
          <h3 class="font-semibold mb-2">Xử lý</h3>
          <p class="text-sm text-neutral-600">Sửa chữa trong 3-7 ngày</p>
    </div>
    <div class="text-center">
          <div class="w-16 h-16 bg-primary-600 rounded-full flex items-center justify-center mx-auto mb-4">
      <span class="text-white text-2xl font-bold">4</span>
          </div>
          <h3 class="font-semibold mb-2">Nhận lại</h3>
          <p class="text-sm text-neutral-600">Giao tận nơi miễn phí</p>
    </div>
      </div>
  </div>
  </div>
</section>

<section class="py-20 bg-white">
  <div class="container mx-auto px-4">
  <div class="text-center mb-16">
      <h2 class="text-3xl font-serif font-bold">Câu hỏi thường gặp</h2>
  </div>

  <div class="max-w-3xl mx-auto space-y-4" x-data="{ open: 1 }">
      <div class="border border-neutral-200 rounded-xl overflow-hidden">
    <button x-on:click="open = open === 1 ? 0 : 1" class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-neutral-50">
          <span class="font-medium">Bảo hành có thời hạn bao lâu?</span>
          <svg class="w-5 h-5 text-neutral-400 transition-transform" x-bind:class="open === 1 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
    </button>
    <div x-show="open === 1" x-collapse class="px-6 pb-4 text-neutral-600">Chính sách bảo hành trọn đời có nghĩa là sản phẩm được bảo hành trong suốt vòng đời sử dụng hợp lý, thường là 10-15 năm.</div>
      </div>
      <div class="border border-neutral-200 rounded-xl overflow-hidden">
    <button x-on:click="open = open === 2 ? 0 : 2" class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-neutral-50">
          <span class="font-medium">Tôi có phải trả phí bảo hành không?</span>
          <svg class="w-5 h-5 text-neutral-400 transition-transform" x-bind:class="open === 2 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
    </button>
    <div x-show="open === 2" x-collapse class="px-6 pb-4 text-neutral-600">Các lỗi thuộc phạm vi bảo hành sẽ được sửa chữa miễn phí hoàn toàn.</div>
      </div>
      <div class="border border-neutral-200 rounded-xl overflow-hidden">
    <button x-on:click="open = open === 3 ? 0 : 3" class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-neutral-50">
          <span class="font-medium">Làm sao để bảo quản vest đúng cách?</span>
          <svg class="w-5 h-5 text-neutral-400 transition-transform" x-bind:class="open === 3 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
    </button>
    <div x-show="open === 3" x-collapse class="px-6 pb-4 text-neutral-600">Treo vest trên mắc áo có vai rộng, tránh gấp. Bảo quản nơi khô thoáng, tránh ánh nắng trực tiếp.</div>
      </div>
  </div>
  </div>
</section>

<section class="py-16 bg-primary-600 text-white">
  <div class="container mx-auto px-4 text-center">
  <h2 class="text-3xl font-serif font-bold mb-4">Cần hỗ trợ bảo hành?</h2>
  <p class="text-xl text-primary-100 mb-8">Liên hệ ngay để được hỗ trợ nhanh nhất</p>
  <div class="flex flex-col sm:flex-row gap-4 justify-center">
      <a href="tel:0901234567" class="inline-flex items-center justify-center gap-2 bg-white text-primary-600 px-8 py-4 rounded-lg font-semibold hover:bg-neutral-100">0901 234 567</a>
      <a href="{{ route('contact') }}" class="inline-block border-2 border-white text-white px-8 py-4 rounded-lg font-semibold hover:bg-white/10">Gửi yêu cầu</a>
  </div>
  </div>
</section>
@endsection
