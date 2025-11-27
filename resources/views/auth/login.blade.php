@extends('layouts.app')

@section('title', 'Đăng Nhập | Suit Configurator')
@section('description', 'Đăng nhập vào tài khoản Suit Configurator để quản lý đơn hàng và thiết kế vest của bạn.')

@section('content')
<div class="min-h-[calc(100vh-5rem)] flex items-center justify-center py-12 px-4">
  <div class="" style="width: 450px;">
  {{-- Header --}}
  <div class="mb-8">
      <h1 class="text-2xl font-semibold tracking-tight text-neutral-900">Đăng nhập</h1>
      <p class="mt-2 text-sm text-neutral-600">
    Nhập email và mật khẩu để tiếp tục
      </p>
  </div>

  {{-- Alert Messages --}}
  @if ($errors->any())
  <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4">
      <div class="flex">
    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd"/>
    </svg>
    <div class="ml-3">
          <h3 class="text-sm font-medium text-red-800">Có lỗi xảy ra</h3>
          <ul class="mt-2 text-sm text-red-700 list-disc list-inside space-y-1">
      @foreach ($errors->all() as $error)
      <li>{{ $error }}</li>
      @endforeach
          </ul>
    </div>
      </div>
  </div>
  @endif

  @if (session('status'))
  <div class="mb-6 rounded-lg border border-green-200 bg-green-50 p-4">
      <p class="text-sm text-green-800">{{ session('status') }}</p>
  </div>
  @endif

  {{-- Login Form --}}
  <form method="POST" action="{{ route('login') }}" class="space-y-6">
      @csrf

      {{-- Email Field --}}
      <div class="space-y-2">
    <label for="email" class="block text-sm font-medium text-neutral-900">Email</label>
    <input
          id="email"
          name="email"
          type="email"
          autocomplete="email"
          required
          value="{{ old('email') }}"
          class="w-full h-10 px-3 rounded-md border border-neutral-300 bg-white text-sm shadow-sm transition-colors placeholder:text-neutral-500 focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-neutral-950 disabled:cursor-not-allowed disabled:opacity-50"
          placeholder="name@example.com"
    >
      </div>

      {{-- Password Field --}}
      <div class="space-y-2">
    <div class="flex items-center justify-between">
          <label for="password" class="block text-sm font-medium text-neutral-900">Mật khẩu</label>
          <a href="#" class="text-sm font-medium text-neutral-900 underline underline-offset-4 hover:text-neutral-700">
      Quên mật khẩu?
          </a>
    </div>
    <div class="relative" x-data="{ showPassword: false }">
          <input
      id="password"
      name="password"
      :type="showPassword ? 'text' : 'password'"
      autocomplete="current-password"
      required
      class="w-full h-10 px-3 pr-10 rounded-md border border-neutral-300 bg-white text-sm shadow-sm transition-colors placeholder:text-neutral-500 focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-neutral-950 disabled:cursor-not-allowed disabled:opacity-50"
      placeholder="••••••••"
          >
          <button
      type="button"
      @click="showPassword = !showPassword"
      class="absolute text-neutral-400 hover:text-neutral-600 transition-colors"
      style="right: 0.75rem; top: 50%; transform: translateY(-50%);"
          >
      <svg x-show="!showPassword" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
      </svg>
      <svg x-show="showPassword" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
      </svg>
          </button>
    </div>
      </div>

      {{-- Submit Button --}}
      <button
    type="submit"
    class="w-full h-10 inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md bg-neutral-900 px-4 text-sm font-medium text-white shadow transition-colors hover:bg-neutral-900/90 focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-neutral-950 disabled:pointer-events-none disabled:opacity-50"
      >
    Đăng nhập
      </button>

      {{-- Social Login --}}
      <div class="relative">
    <div class="absolute inset-0 flex items-center">
          <span class="w-full border-t border-neutral-200"></span>
    </div>
    <div class="relative flex justify-center text-xs uppercase">
          <span class="bg-white px-2 text-neutral-500">Hoặc tiếp tục với</span>
    </div>
      </div>

      <div class="grid grid-cols-2 gap-3">
    <button
          type="button"
          class="inline-flex h-10 items-center justify-center gap-2 whitespace-nowrap rounded-md border border-neutral-300 bg-white px-4 text-sm font-medium shadow-sm transition-colors hover:bg-neutral-50 hover:text-neutral-900 focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-neutral-950 disabled:pointer-events-none disabled:opacity-50"
    >
          <svg class="h-4 w-4" viewBox="0 0 24 24">
      <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
      <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
      <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
      <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
          </svg>
          Google
    </button>
    <button
          type="button"
          class="inline-flex h-10 items-center justify-center gap-2 whitespace-nowrap rounded-md border border-neutral-300 bg-white px-4 text-sm font-medium shadow-sm transition-colors hover:bg-neutral-50 hover:text-neutral-900 focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-neutral-950 disabled:pointer-events-none disabled:opacity-50"
    >
          <svg class="h-4 w-4" fill="#1877F2" viewBox="0 0 24 24">
      <path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/>
          </svg>
          Facebook
    </button>
      </div>
  </form>

  {{-- Sign Up Link --}}
  <div class="mt-6 text-center text-sm">
      <span class="text-neutral-600">Chưa có tài khoản?</span>
      <a href="{{ route('register') }}" class="font-medium text-neutral-900 underline underline-offset-4 hover:text-neutral-700">
    Đăng ký
      </a>
  </div>
  </div>
</div>
@endsection
