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

  {{-- Mobile App Meta --}}
  <meta name="theme-color" content="#8B0000">
  <meta name="mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="default">
  <meta name="apple-mobile-web-app-title" content="Suit Configurator">

  {{-- Favicon --}}
  <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
  <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">

  {{-- Fonts --}}
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  {{-- Assets --}}
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  {{-- Suit Icon Fonts --}}
  <link rel="stylesheet" href="{{ asset('css/suit-icons.css') }}">

  {{-- Structured Data / JSON-LD --}}
  @yield('structured_data')

  @stack('styles')
</head>
<body class="bg-white text-gray-900 antialiased overflow-hidden">
  @yield('content')
  @stack('scripts')
</body>
</html>
