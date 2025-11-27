@extends('layouts.app')

@section('title', 'Hướng Dẫn Đo Size Vest | Suit Configurator - Tự Đo Tại Nhà')
@section('description', 'Hướng dẫn chi tiết cách tự đo số đo cơ thể để may vest vừa vặn hoàn hảo tại nhà. Bao gồm: vai, ngực, eo, hông, tay áo, dài áo.')
@section('keywords', 'đo size vest, cách đo số đo vest, hướng dẫn đo vest, size chart vest, tự đo vest tại nhà')
@section('og_image', asset('images/og-size-guide.jpg'))

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
      "name": "Hướng dẫn đo size",
      "item": "{{ route('size-guide') }}"
  }
  ]
}
</script>
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "HowTo",
  "name": "Cách tự đo size vest tại nhà",
  "description": "Hướng dẫn chi tiết cách đo các số đo cơ thể để may vest vừa vặn",
  "image": "{{ asset('images/size-guide.jpg') }}",
  "totalTime": "PT10M",
  "estimatedCost": {
  "@@type": "MonetaryAmount",
  "currency": "VND",
  "value": "0"
  },
  "supply": [
  {
      "@@type": "HowToSupply",
      "name": "Thước dây"
  },
  {
      "@@type": "HowToSupply",
      "name": "Gương"
  }
  ],
  "step": [
  {
      "@@type": "HowToStep",
      "name": "Đo vòng ngực",
      "text": "Đo vòng quanh phần đầy đặn nhất của ngực",
      "position": 1
  },
  {
      "@@type": "HowToStep",
      "name": "Đo vòng eo",
      "text": "Đo vòng quanh eo tại vị trí nhỏ nhất",
      "position": 2
  },
  {
      "@@type": "HowToStep",
      "name": "Đo rộng vai",
      "text": "Đo từ đầu vai trái đến đầu vai phải",
      "position": 3
  },
  {
      "@@type": "HowToStep",
      "name": "Đo dài tay",
      "text": "Đo từ đầu vai đến cổ tay",
      "position": 4
  }
  ]
}
</script>
@endsection

@section('content')
{{-- Hero Section --}}
<section class="relative py-20 bg-neutral-900 text-white">
  <div class="container mx-auto px-4 text-center">
  <h1 class="text-5xl font-serif font-bold mb-4">Hướng dẫn đo size</h1>
  <p class="text-xl text-neutral-300 max-w-2xl mx-auto">
      Đo chính xác để có bộ vest vừa vặn hoàn hảo. Chỉ cần một thước dây và gương.
  </p>
  </div>
</section>

{{-- Video Guide --}}
<section class="py-16 bg-white">
  <div class="container mx-auto px-4">
  <div class="max-w-4xl mx-auto">
      <div class="aspect-video bg-neutral-200 rounded-2xl mb-8 flex items-center justify-center">
    <div class="text-center">
          <svg class="w-20 h-20 text-neutral-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
          <p class="text-neutral-500">Video hướng dẫn đo size</p>
    </div>
      </div>
      <div class="bg-primary-50 rounded-xl p-6 flex items-start gap-4">
    <svg class="w-6 h-6 text-primary-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    <div>
          <h3 class="font-semibold text-primary-900 mb-1">Mẹo đo chính xác</h3>
          <p class="text-primary-700 text-sm">
      Mặc áo mỏng sát người khi đo. Đứng thẳng, thư giãn tự nhiên. Nhờ người khác đo sẽ chính xác hơn.
          </p>
    </div>
      </div>
  </div>
  </div>
</section>

{{-- Measurements Guide --}}
<section class="py-16 bg-neutral-50">
  <div class="container mx-auto px-4">
  <div class="text-center mb-12">
      <h2 class="text-3xl font-serif font-bold">Các số đo cần thiết</h2>
      <p class="text-neutral-600 mt-2">Đo theo thứ tự từ trên xuống dưới</p>
  </div>

  <div class="max-w-5xl mx-auto space-y-8">
      @php
      $measurements = [
    [
          'number' => '01',
          'name' => 'Vòng cổ (Neck)',
          'description' => 'Đo vòng quanh cổ, ngay dưới cổ họng, để thước dây sát da nhưng không quá chặt.',
          'tip' => 'Nên chừa 1-2cm để thoải mái khi cài nút'
    ],
    [
          'number' => '02',
          'name' => 'Vai (Shoulder)',
          'description' => 'Đo từ điểm xương vai bên này sang điểm xương vai bên kia, đi ngang qua phía sau cổ.',
          'tip' => 'Đây là số đo quan trọng nhất cho vest'
    ],
    [
          'number' => '03',
          'name' => 'Vòng ngực (Chest)',
          'description' => 'Đo vòng quanh ngực tại điểm rộng nhất, đi ngang qua nách. Giữ thước dây song song với sàn.',
          'tip' => 'Thở ra bình thường khi đo'
    ],
    [
          'number' => '04',
          'name' => 'Vòng eo (Waist)',
          'description' => 'Đo vòng quanh phần eo tự nhiên, thường là phần nhỏ nhất của thân trên, ngang rốn.',
          'tip' => 'Đứng thư giãn, không hóp bụng'
    ],
    [
          'number' => '05',
          'name' => 'Vòng hông (Hip)',
          'description' => 'Đo vòng quanh phần rộng nhất của hông, khoảng 20cm dưới eo.',
          'tip' => 'Đảm bảo thước dây đi ngang qua phần lồi nhất của mông'
    ],
    [
          'number' => '06',
          'name' => 'Dài tay (Sleeve)',
          'description' => 'Đo từ điểm xương vai, dọc theo cánh tay hơi cong, xuống đến xương cổ tay.',
          'tip' => 'Cánh tay hơi gập tự nhiên khi đo'
    ],
    [
          'number' => '07',
          'name' => 'Dài áo (Jacket Length)',
          'description' => 'Đo từ đốt sống cổ số 7 (xương nhô lên khi cúi đầu) xuống đến điểm mong muốn.',
          'tip' => 'Chiều dài tiêu chuẩn thường che nửa mông'
    ],
    [
          'number' => '08',
          'name' => 'Bắp tay (Bicep)',
          'description' => 'Đo vòng quanh phần lớn nhất của bắp tay, thường ở giữa vai và khuỷu tay.',
          'tip' => 'Để tay buông thả tự nhiên khi đo'
    ],
    [
          'number' => '09',
          'name' => 'Cổ tay (Wrist)',
          'description' => 'Đo vòng quanh cổ tay, ngay trên xương cổ tay.',
          'tip' => 'Chừa không gian cho đồng hồ hoặc phụ kiện'
    ],
      ];
      @endphp

      @foreach($measurements as $m)
      <div class="bg-white rounded-2xl shadow-sm p-6 flex flex-col md:flex-row gap-6">
    <div class="flex-shrink-0">
          <div class="w-16 h-16 bg-primary-100 rounded-2xl flex items-center justify-center">
      <span class="text-2xl font-bold text-primary-600">{{ $m['number'] }}</span>
          </div>
    </div>
    <div class="flex-1">
          <h3 class="text-xl font-semibold mb-2">{{ $m['name'] }}</h3>
          <p class="text-neutral-600 mb-3">{{ $m['description'] }}</p>
          <div class="flex items-center gap-2 text-sm text-primary-600">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
      </svg>
      <span>{{ $m['tip'] }}</span>
          </div>
    </div>
    <div class="flex-shrink-0 w-48 h-32 bg-neutral-100 rounded-xl hidden lg:flex items-center justify-center">
          <span class="text-neutral-400 text-sm">Hình minh họa</span>
    </div>
      </div>
      @endforeach
  </div>
  </div>
</section>

{{-- Size Chart --}}
<section class="py-16 bg-white">
  <div class="container mx-auto px-4">
  <div class="text-center mb-12">
      <h2 class="text-3xl font-serif font-bold">Bảng size tham khảo</h2>
      <p class="text-neutral-600 mt-2">Đây là bảng size chuẩn, sản phẩm may đo sẽ được điều chỉnh theo số đo thực tế</p>
  </div>

  <div class="max-w-4xl mx-auto overflow-x-auto">
      <table class="w-full text-sm">
    <thead>
          <tr class="bg-neutral-900 text-white">
      <th class="px-6 py-4 text-left font-semibold">Size</th>
      <th class="px-6 py-4 text-center font-semibold">Ngực (cm)</th>
      <th class="px-6 py-4 text-center font-semibold">Eo (cm)</th>
      <th class="px-6 py-4 text-center font-semibold">Vai (cm)</th>
      <th class="px-6 py-4 text-center font-semibold">Chiều cao</th>
          </tr>
    </thead>
    <tbody class="divide-y divide-neutral-100">
          @php
          $sizes = [
      ['size' => 'S', 'chest' => '86-90', 'waist' => '74-78', 'shoulder' => '42-44', 'height' => '160-165'],
      ['size' => 'M', 'chest' => '90-94', 'waist' => '78-82', 'shoulder' => '44-46', 'height' => '165-170'],
      ['size' => 'L', 'chest' => '94-98', 'waist' => '82-86', 'shoulder' => '46-48', 'height' => '170-175'],
      ['size' => 'XL', 'chest' => '98-102', 'waist' => '86-90', 'shoulder' => '48-50', 'height' => '175-180'],
      ['size' => 'XXL', 'chest' => '102-106', 'waist' => '90-94', 'shoulder' => '50-52', 'height' => '180-185'],
      ['size' => '3XL', 'chest' => '106-110', 'waist' => '94-98', 'shoulder' => '52-54', 'height' => '185+'],
          ];
          @endphp
          @foreach($sizes as $s)
          <tr class="hover:bg-neutral-50">
      <td class="px-6 py-4 font-semibold">{{ $s['size'] }}</td>
      <td class="px-6 py-4 text-center text-neutral-600">{{ $s['chest'] }}</td>
      <td class="px-6 py-4 text-center text-neutral-600">{{ $s['waist'] }}</td>
      <td class="px-6 py-4 text-center text-neutral-600">{{ $s['shoulder'] }}</td>
      <td class="px-6 py-4 text-center text-neutral-600">{{ $s['height'] }}</td>
          </tr>
          @endforeach
    </tbody>
      </table>
  </div>
  </div>
</section>

{{-- CTA Section --}}
<section class="py-16 bg-primary-600 text-white">
  <div class="container mx-auto px-4 text-center">
  <h2 class="text-3xl font-serif font-bold mb-4">Cần hỗ trợ đo size?</h2>
  <p class="text-xl text-primary-100 mb-8 max-w-2xl mx-auto">
      Đặt lịch hẹn tại showroom để được chuyên gia đo miễn phí, hoặc liên hệ hotline để được tư vấn.
  </p>
  <div class="flex flex-col sm:flex-row gap-4 justify-center">
      <a href="{{ route('contact') }}" class="inline-block bg-white text-primary-600 px-8 py-4 rounded-lg font-semibold hover:bg-neutral-100 transition-colors">
    Đặt lịch hẹn
      </a>
      <a href="tel:0901234567" class="inline-block border-2 border-white text-white px-8 py-4 rounded-lg font-semibold hover:bg-white/10 transition-colors">
    Hotline: 0901 234 567
      </a>
  </div>
  </div>
</section>
@endsection
