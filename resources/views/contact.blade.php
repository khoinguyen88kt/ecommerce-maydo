@extends('layouts.app')

@section('title', 'Liên Hệ Suit Configurator | Tư Vấn May Đo Vest Miễn Phí')
@section('description', 'Liên hệ Suit Configurator để được tư vấn miễn phí về may đo vest. Showroom tại Hà Nội và TP.HCM. Hotline: 0901 234 567. Mở cửa 8h-21h.')
@section('keywords', 'liên hệ suit configurator, showroom vest nam, tư vấn may vest, đặt lịch may vest, hotline suit configurator')
@section('og_image', asset('images/og-contact.jpg'))

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
      "name": "Liên hệ",
      "item": "{{ route('contact') }}"
  }
  ]
}
</script>
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "ContactPage",
  "name": "Liên hệ Suit Configurator",
  "description": "Liên hệ tư vấn may đo vest miễn phí",
  "url": "{{ route('contact') }}",
  "mainEntity": {
  "@@type": "LocalBusiness",
  "name": "Suit Configurator",
  "telephone": "+84901234567",
  "email": "contact@suitconfigurator.vn",
  "address": [
      {
    "@@type": "PostalAddress",
    "name": "Showroom Hà Nội",
    "streetAddress": "123 Phố Huế",
    "addressLocality": "Quận Hai Bà Trưng",
    "addressRegion": "Hà Nội",
    "postalCode": "100000",
    "addressCountry": "VN"
      },
      {
    "@@type": "PostalAddress",
    "name": "Showroom TP.HCM",
    "streetAddress": "456 Nguyễn Huệ",
    "addressLocality": "Quận 1",
    "addressRegion": "TP. Hồ Chí Minh",
    "postalCode": "700000",
    "addressCountry": "VN"
      }
  ],
  "openingHoursSpecification": {
      "@@type": "OpeningHoursSpecification",
      "dayOfWeek": ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"],
      "opens": "08:00",
      "closes": "21:00"
  }
  }
}
</script>
@endsection

@section('content')
{{-- Hero Section --}}
<section class="relative h-64 bg-neutral-900 overflow-hidden">
  <div class="absolute inset-0">
  <img src="https://images.unsplash.com/photo-1497366216548-37526070297c?w=1920" alt="Office" class="w-full h-full object-cover opacity-30">
  </div>
  <div class="relative container mx-auto px-4 h-full flex items-center">
  <div class="max-w-2xl text-white">
      <h1 class="text-5xl font-serif font-bold mb-4">Liên hệ</h1>
      <p class="text-xl text-neutral-300">Chúng tôi luôn sẵn sàng hỗ trợ bạn</p>
  </div>
  </div>
</section>

<section class="py-20 bg-white">
  <div class="container mx-auto px-4">
  <div class="grid lg:grid-cols-3 gap-12">
      {{-- Contact Info --}}
      <div class="space-y-8">
    <div>
          <h2 class="text-2xl font-serif font-bold mb-6">Thông tin liên hệ</h2>
          <div class="space-y-6">
      <div class="flex items-start gap-4">
              <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center flex-shrink-0">
        <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
        </svg>
              </div>
              <div>
        <h3 class="font-semibold mb-1">Showroom Hà Nội</h3>
        <p class="text-neutral-600">123 Phố Huế, Quận Hai Bà Trưng, Hà Nội</p>
              </div>
      </div>
      <div class="flex items-start gap-4">
              <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center flex-shrink-0">
        <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
        </svg>
              </div>
              <div>
        <h3 class="font-semibold mb-1">Showroom TP.HCM</h3>
        <p class="text-neutral-600">456 Nguyễn Huệ, Quận 1, TP.HCM</p>
              </div>
      </div>
      <div class="flex items-start gap-4">
              <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center flex-shrink-0">
        <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
        </svg>
              </div>
              <div>
        <h3 class="font-semibold mb-1">Hotline</h3>
        <p class="text-neutral-600">0901 234 567</p>
        <p class="text-sm text-neutral-500">8:00 - 21:00, thứ 2 - CN</p>
              </div>
      </div>
      <div class="flex items-start gap-4">
              <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center flex-shrink-0">
        <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
        </svg>
              </div>
              <div>
        <h3 class="font-semibold mb-1">Email</h3>
        <p class="text-neutral-600">contact@suitconfigurator.vn</p>
              </div>
      </div>
          </div>
    </div>

    {{-- Social --}}
    <div>
          <h3 class="font-semibold mb-4">Kết nối với chúng tôi</h3>
          <div class="flex gap-4">
      <a href="#" class="w-10 h-10 bg-neutral-100 rounded-full flex items-center justify-center hover:bg-primary-100 hover:text-primary-600 transition-colors">
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
              </svg>
      </a>
      <a href="#" class="w-10 h-10 bg-neutral-100 rounded-full flex items-center justify-center hover:bg-primary-100 hover:text-primary-600 transition-colors">
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
              </svg>
      </a>
      <a href="#" class="w-10 h-10 bg-neutral-100 rounded-full flex items-center justify-center hover:bg-primary-100 hover:text-primary-600 transition-colors">
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
              </svg>
      </a>
      <a href="#" class="w-10 h-10 bg-neutral-100 rounded-full flex items-center justify-center hover:bg-primary-100 hover:text-primary-600 transition-colors">
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
        <path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/>
              </svg>
      </a>
          </div>
    </div>
      </div>

      {{-- Contact Form --}}
      <div class="lg:col-span-2">
    <div class="bg-neutral-50 rounded-2xl p-8">
          <h2 class="text-2xl font-serif font-bold mb-6">Gửi tin nhắn cho chúng tôi</h2>
          <form action="#" method="POST" class="space-y-6">
      @csrf
      <div class="grid md:grid-cols-2 gap-6">
              <div>
        <label for="name" class="block text-sm font-medium text-neutral-700 mb-2">Họ và tên *</label>
        <input type="text" id="name" name="name" required
                  class="w-full px-4 py-3 border border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all bg-white"
                  placeholder="Nguyễn Văn A">
              </div>
              <div>
        <label for="phone" class="block text-sm font-medium text-neutral-700 mb-2">Số điện thoại *</label>
        <input type="tel" id="phone" name="phone" required
                  class="w-full px-4 py-3 border border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all bg-white"
                  placeholder="0901 234 567">
              </div>
      </div>
      <div>
              <label for="email" class="block text-sm font-medium text-neutral-700 mb-2">Email</label>
              <input type="email" id="email" name="email"
        class="w-full px-4 py-3 border border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all bg-white"
        placeholder="email@example.com">
      </div>
      <div>
              <label for="subject" class="block text-sm font-medium text-neutral-700 mb-2">Chủ đề</label>
              <select id="subject" name="subject"
        class="w-full px-4 py-3 border border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all bg-white">
        <option value="">Chọn chủ đề</option>
        <option value="consultation">Tư vấn đặt may</option>
        <option value="order">Hỏi về đơn hàng</option>
        <option value="appointment">Đặt lịch hẹn</option>
        <option value="feedback">Góp ý / Phản hồi</option>
        <option value="other">Khác</option>
              </select>
      </div>
      <div>
              <label for="message" class="block text-sm font-medium text-neutral-700 mb-2">Nội dung *</label>
              <textarea id="message" name="message" rows="5" required
        class="w-full px-4 py-3 border border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all resize-none bg-white"
        placeholder="Nhập nội dung tin nhắn..."></textarea>
      </div>
      <button type="submit" class="w-full btn-primary py-4">
              Gửi tin nhắn
      </button>
          </form>
    </div>
      </div>
  </div>
  </div>
</section>

{{-- Map Section --}}
<section class="h-96 bg-neutral-200">
  <iframe
  src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3724.096949055701!2d105.84972431533208!3d21.02883598600147!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135ab9bd9861ca1%3A0xe7887f7b72ca17a9!2sHoan%20Kiem%20Lake!5e0!3m2!1sen!2s!4v1607659462579!5m2!1sen!2s"
  width="100%"
  height="100%"
  style="border:0;"
  allowfullscreen=""
  loading="lazy">
  </iframe>
</section>
@endsection
