<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  {{-- Primary Meta Tags --}}
  <title>@yield('title', 'Suit Configurator - May Đo Vest Nam Cao Cấp')</title>
  <meta name="title" content="@yield('title', 'Suit Configurator - May Đo Vest Nam Cao Cấp')">
  <meta name="description" content="@yield('description', 'Thiết kế vest nam may đo cao cấp theo phong cách riêng của bạn. Hơn 500 mẫu vải nhập khẩu từ Ý, Anh, Nhật. Bảo hành trọn đời.')">
  <meta name="keywords" content="@yield('keywords', 'vest nam, may đo vest, suit nam, vest cao cấp, vest cưới, vải vest, thiết kế vest, suit configurator')">
  <meta name="author" content="Suit Configurator">
  <meta name="robots" content="index, follow">
  <link rel="canonical" href="{{ url()->current() }}">

  {{-- Open Graph / Facebook --}}
  <meta property="og:type" content="@yield('og_type', 'website')">
  <meta property="og:url" content="{{ url()->current() }}">
  <meta property="og:title" content="@yield('title', 'Suit Configurator - May Đo Vest Nam Cao Cấp')">
  <meta property="og:description" content="@yield('description', 'Thiết kế vest nam may đo cao cấp theo phong cách riêng của bạn.')">
  <meta property="og:image" content="@yield('og_image', asset('images/og-default.jpg'))">
  <meta property="og:site_name" content="Suit Configurator">
  <meta property="og:locale" content="vi_VN">

  {{-- Twitter --}}
  <meta property="twitter:card" content="summary_large_image">
  <meta property="twitter:url" content="{{ url()->current() }}">
  <meta property="twitter:title" content="@yield('title', 'Suit Configurator - May Đo Vest Nam Cao Cấp')">
  <meta property="twitter:description" content="@yield('description', 'Thiết kế vest nam may đo cao cấp theo phong cách riêng của bạn.')">
  <meta property="twitter:image" content="@yield('og_image', asset('images/og-default.jpg'))">

  {{-- Mobile App Meta --}}
  <meta name="theme-color" content="#1e3a5f">
  <meta name="mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="default">
  <meta name="apple-mobile-web-app-title" content="Suit Configurator">

  {{-- Geo Tags --}}
  <meta name="geo.region" content="VN-SG">
  <meta name="geo.placename" content="Ho Chi Minh City">

  {{-- Favicon --}}
  <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
  <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">

  {{-- Fonts --}}
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  {{-- Assets --}}
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  {{-- Suit Icon Fonts --}}
  <link rel="stylesheet" href="{{ asset('css/suit-icons.css') }}">

  {{-- Structured Data / JSON-LD --}}
  @yield('structured_data')

  {{-- Default Organization Schema --}}
  <script type="application/ld+json">
  {
  "@@context": "https://schema.org",
  "@@type": "ClothingStore",
  "name": "Suit Configurator",
  "description": "Chuyên may đo vest nam cao cấp theo phong cách riêng",
  "url": "{{ config('app.url') }}",
  "logo": "{{ asset('images/logo.png') }}",
  "image": "{{ asset('images/og-default.jpg') }}",
  "telephone": "+84901234567",
  "email": "info@suitconfigurator.vn",
  "address": {
      "@@type": "PostalAddress",
      "streetAddress": "123 Đường ABC",
      "addressLocality": "Quận 1",
      "addressRegion": "TP. Hồ Chí Minh",
      "postalCode": "700000",
      "addressCountry": "VN"
  },
  "geo": {
      "@@type": "GeoCoordinates",
      "latitude": "10.7769",
      "longitude": "106.7009"
  },
  "openingHoursSpecification": [
      {
    "@@type": "OpeningHoursSpecification",
    "dayOfWeek": ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
    "opens": "09:00",
    "closes": "21:00"
      },
      {
    "@@type": "OpeningHoursSpecification",
    "dayOfWeek": "Sunday",
    "opens": "10:00",
    "closes": "18:00"
      }
  ],
  "priceRange": "₫₫₫",
  "sameAs": [
      "https://facebook.com/suitconfigurator",
      "https://instagram.com/suitconfigurator"
  ]
  }
  </script>

  @stack('styles')
</head>
<body class="bg-neutral-50 text-neutral-900 antialiased">
  <header class="fixed top-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-sm border-b border-neutral-200">
  <div class="container mx-auto px-4">
      <nav class="flex items-center justify-between h-16 lg:h-20">
    <a href="{{ route('home') }}" class="flex items-center space-x-2.5 group">
          {{-- Logo Icon - Modern Minimalist Suit --}}
          <div class="relative w-10 h-10 flex items-center justify-center">
      <svg class="w-10 h-10 transition-all duration-300 group-hover:scale-105" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
              {{-- Suit jacket silhouette - modern geometric --}}
              <path d="M24 6L15 11V17L11 21V42H18V28L24 22L30 28V42H37V21L33 17V11L24 6Z" fill="url(#suitGradient)" opacity="0.9"/>

              {{-- Lapel lines - sharp and modern --}}
              <path d="M24 10L20 14L17 18" stroke="#f97316" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              <path d="M24 10L28 14L31 18" stroke="#f97316" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>

              {{-- Button detail --}}
              <circle cx="24" cy="24" r="1.5" fill="#f97316"/>
              <circle cx="24" cy="29" r="1.5" fill="#f97316"/>

              {{-- Gradient definition --}}
              <defs>
        <linearGradient id="suitGradient" x1="24" y1="6" x2="24" y2="42" gradientUnits="userSpaceOnUse">
                  <stop offset="0%" stop-color="#1e3a8a"/>
                  <stop offset="100%" stop-color="#3b82f6"/>
        </linearGradient>
              </defs>
      </svg>
          </div>

          {{-- Brand Text --}}
          <div class="flex flex-col leading-none">
      <div class="flex items-baseline" style="letter-spacing: -0.03em;">
              <span class="font-black transition-all duration-300 group-hover:scale-105" style="font-family: 'Inter', sans-serif; font-size: 28px; background: linear-gradient(135deg, #f97316 0%, #fb923c 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
        24
              </span>
              <span class="font-black transition-all duration-300 group-hover:scale-105" style="font-family: 'Inter', sans-serif; font-size: 26px; background: linear-gradient(135deg, #f97316 0%, #fb923c 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; font-style: italic;">
        h
              </span>
      </div>
      <span class="font-bold text-neutral-900 group-hover:text-neutral-700 transition-colors uppercase" style="margin-top: -2px; font-size: 11px; letter-spacing: 0.25em;">
              MAY ĐO
      </span>
          </div>
    </a>
    <div class="hidden lg:flex items-center space-x-8">
          <a href="{{ route('home') }}" class="nav-link">Trang chủ</a>
          <a href="{{ route('configurator.index') }}" class="nav-link font-semibold text-primary-700">Thiết kế Vest</a>
          <a href="{{ route('fabrics.index') }}" class="nav-link">Bộ sưu tập vải</a>
          <a href="{{ route('about') }}" class="nav-link">Về chúng tôi</a>
          <a href="{{ route('contact') }}" class="nav-link">Liên hệ</a>
    </div>
    <div class="flex items-center space-x-4">
          <a href="{{ route('cart.index') }}" class="relative p-2 hover:bg-neutral-100 rounded-full transition-colors">
      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
      </svg>
          </a>
          @auth
          <div x-data="{ open: false }" class="relative">
      <button x-on:click="open = !open" class="flex items-center space-x-2 p-2 hover:bg-neutral-100 rounded-lg">
              <span class="text-sm font-medium">{{ Auth::user()->name }}</span>
      </button>
      <div x-show="open" x-on:click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 border">
              <a href="{{ route('orders.index') }}" class="block px-4 py-2 text-sm hover:bg-neutral-100">Đơn hàng</a>
              <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="w-full text-left px-4 py-2 text-sm hover:bg-neutral-100 text-red-600">Đăng xuất</button>
              </form>
      </div>
          </div>
          @endauth
          @guest
          <a href="{{ route('login') }}" class="text-sm font-medium hover:text-primary-600">Đăng nhập</a>
          @endguest
    </div>
      </nav>
  </div>
  </header>

  <main class="pt-16 lg:pt-20">
  @yield('content')
  </main>

  <footer class="bg-neutral-900 text-white mt-20">
  <div class="container mx-auto px-4 py-12">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
    <div>
          <div class="flex items-center space-x-2 mb-4">
      <svg class="w-8 h-8" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M24 6L15 11V17L11 21V42H18V28L24 22L30 28V42H37V21L33 17V11L24 6Z" fill="url(#suitGradientFooter)" opacity="0.9"/>
              <path d="M24 10L20 14L17 18" stroke="#f97316" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              <path d="M24 10L28 14L31 18" stroke="#f97316" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              <circle cx="24" cy="24" r="1.5" fill="#f97316"/>
              <circle cx="24" cy="29" r="1.5" fill="#f97316"/>
              <defs>
        <linearGradient id="suitGradientFooter" x1="24" y1="6" x2="24" y2="42" gradientUnits="userSpaceOnUse">
                  <stop offset="0%" stop-color="#3b82f6"/>
                  <stop offset="100%" stop-color="#60a5fa"/>
        </linearGradient>
              </defs>
      </svg>
      <h3 class="text-xl font-bold">24H MAY ĐO</h3>
          </div>
          <p class="text-neutral-400 text-sm mb-4">Chuyên may đo vest nam cao cấp theo phong cách riêng. Hơn 500 mẫu vải nhập khẩu.</p>
          <div class="flex space-x-4">
      <a href="https://facebook.com/suitconfigurator" target="_blank" rel="noopener" aria-label="Facebook" class="w-10 h-10 bg-neutral-800 rounded-full flex items-center justify-center hover:bg-primary-600 transition-colors">
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/></svg>
      </a>
      <a href="https://instagram.com/suitconfigurator" target="_blank" rel="noopener" aria-label="Instagram" class="w-10 h-10 bg-neutral-800 rounded-full flex items-center justify-center hover:bg-primary-600 transition-colors">
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
      </a>
      <a href="https://zalo.me/0901234567" target="_blank" rel="noopener" aria-label="Zalo" class="w-10 h-10 bg-neutral-800 rounded-full flex items-center justify-center hover:bg-primary-600 transition-colors">
              <span class="text-xs font-bold">Zalo</span>
      </a>
          </div>
    </div>
    <div>
          <h4 class="font-semibold mb-4">Dịch vụ</h4>
          <ul class="space-y-2 text-sm text-neutral-400">
      <li><a href="{{ route('configurator.index') }}" class="hover:text-white transition-colors">Thiết kế Vest</a></li>
      <li><a href="{{ route('fabrics.index') }}" class="hover:text-white transition-colors">Bộ sưu tập vải</a></li>
      <li><a href="{{ route('size-guide') }}" class="hover:text-white transition-colors">Hướng dẫn đo size</a></li>
          </ul>
    </div>
    <div>
          <h4 class="font-semibold mb-4">Hỗ trợ</h4>
          <ul class="space-y-2 text-sm text-neutral-400">
      <li><a href="{{ route('warranty') }}" class="hover:text-white transition-colors">Chính sách bảo hành</a></li>
      <li><a href="{{ route('terms') }}" class="hover:text-white transition-colors">Điều khoản dịch vụ</a></li>
      <li><a href="{{ route('privacy') }}" class="hover:text-white transition-colors">Chính sách bảo mật</a></li>
      <li><a href="{{ route('contact') }}" class="hover:text-white transition-colors">Liên hệ</a></li>
          </ul>
    </div>
    <div>
          <h4 class="font-semibold mb-4">Liên hệ</h4>
          <ul class="space-y-3 text-sm text-neutral-400">
      <li class="flex items-start gap-3">
              <svg class="w-5 h-5 text-primary-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
              <span>123 Đường ABC, Quận 1, TP.HCM</span>
      </li>
      <li class="flex items-center gap-3">
              <svg class="w-5 h-5 text-primary-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
              <a href="tel:0901234567" class="hover:text-white transition-colors">0901 234 567</a>
      </li>
      <li class="flex items-center gap-3">
              <svg class="w-5 h-5 text-primary-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
              <a href="mailto:info@suitconfigurator.vn" class="hover:text-white transition-colors">info&#64;suitconfigurator.vn</a>
      </li>
          </ul>
    </div>
      </div>
      <div class="border-t border-neutral-800 mt-12 pt-8">
    <div class="flex flex-col md:flex-row justify-between items-center gap-4">
          <p class="text-sm text-neutral-500">© {{ date('Y') }} Suit Configurator. Bảo lưu mọi quyền.</p>
          <div class="flex items-center gap-6 text-sm text-neutral-500">
      <a href="{{ route('terms') }}" class="hover:text-white transition-colors">Điều khoản</a>
      <a href="{{ route('privacy') }}" class="hover:text-white transition-colors">Bảo mật</a>
          </div>
    </div>
      </div>
  </div>
  </footer>
  @stack('scripts')
</body>
</html>
