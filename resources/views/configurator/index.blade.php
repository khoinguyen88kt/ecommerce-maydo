@extends('layouts.app')

@section('title', 'Thiết Kế Vest Nam May Đo Online | Suit Configurator')
@section('description', 'Tự thiết kế bộ vest nam may đo theo phong cách riêng. Chọn kiểu vest, chọn vải, tùy chỉnh chi tiết. Xem trước trực tiếp. Đặt hàng online.')

@push('styles')
@vite('resources/css/configurator.scss')
@endpush

@section('content')
<div x-data="suitConfigurator()" x-init="init()" class="bg-white">
  {{-- Top Navigation Bar --}}
  <div class="bg-white border-b border-gray-200 sticky top-16 lg:top-20 z-40">
  <div class="flex items-center justify-between h-14 px-4 lg:px-8">
      {{-- Left: Main Tabs --}}
      <div class="flex items-center space-x-2">
    <button
          x-on:click="mainTab = 'style'"
          :class="mainTab === 'style' ? 'active' : ''"
          class="main-tab"
    >
          PHONG CÁCH
    </button>
    <span class="text-gray-300">›</span>
    <button
          x-on:click="mainTab = 'fabric'"
          :class="mainTab === 'fabric' ? 'active' : ''"
          class="main-tab"
    >
          VẢI
    </button>
    <span class="text-gray-300">›</span>
    <button
          x-on:click="mainTab = 'extras'"
          :class="mainTab === 'extras' ? 'active' : ''"
          class="main-tab"
    >
          MỞ RỘNG
    </button>
      </div>

  </div>
  </div>

  {{-- Main Configurator Layout --}}
  <div class="configurator-container flex">
  {{-- Panel 1: Category Icons --}}
  <aside class="category-panel" x-show="mainTab === 'style'">
      <template x-for="optionType in optionTypes" :key="optionType.id">
    <div
          x-on:click="scrollToOption(optionType.slug)"
          :class="activeCategory === optionType.slug ? 'active' : ''"
          class="category-item"
    >
          <span
      class="icon"
      :class="optionType.slug.includes('pants') ? 'man-pants' : 'man-jacket'"
      x-text="getIconForOptionType(optionType.slug)"
          ></span>
          <span class="label" x-text="optionType.name_vi"></span>
    </div>
      </template>
  </aside>

  {{-- Panel 2: Options Detail --}}
  <aside class="options-panel" x-show="mainTab === 'style'" x-ref="optionsPanel">
      <template x-for="optionType in optionTypes" :key="optionType.id">
    <div class="option-section" :id="'option-' + optionType.slug">
          <div class="option-title" x-text="optionType.name_vi"></div>
          <div class="option-grid">
      <template x-for="value in optionType.values" :key="value.id">
              <div
        x-on:click="selectOption(optionType.slug, value.id)"
        :class="selectedOptions[optionType.slug] === value.id ? 'selected' : ''"
        class="option-item relative"
              >
        <span
                  class="icon"
                  :class="optionType.slug.includes('pants') ? 'man-pants' : 'man-jacket'"
                  x-text="getIconForValue(optionType.slug, value.slug)"
        ></span>
        <span class="name" x-text="value.name_vi"></span>
        <span
                  x-show="value.price_modifier > 0"
                  class="price"
                  x-text="'+' + formatShortPrice(value.price_modifier)"
        ></span>
        <div class="check">
                  <svg class="w-2 h-2 text-white" fill="currentColor" viewBox="0 0 20 20">
          <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                  </svg>
        </div>
              </div>
      </template>
          </div>
    </div>
      </template>
  </aside>

  {{-- Fabric Panel --}}
  <aside class="options-panel" x-show="mainTab === 'fabric'" style="width: 380px;">
      <div class="p-4">
    <h3 class="text-sm font-semibold text-gray-700 uppercase mb-4">Chọn Vải</h3>

    {{-- Category Filter --}}
    <div class="flex flex-wrap gap-2 mb-4">
          <button
      x-on:click="fabricCategory = null"
      :class="fabricCategory === null ? 'bg-[#8B0000] text-white' : 'bg-gray-100 text-gray-600'"
      class="px-3 py-1.5 text-xs rounded transition-colors"
          >
      Tất cả
          </button>
          @foreach($fabricCategories as $category)
          <button
      x-on:click="fabricCategory = {{ $category->id }}"
      :class="fabricCategory === {{ $category->id }} ? 'bg-[#8B0000] text-white' : 'bg-gray-100 text-gray-600'"
      class="px-3 py-1.5 text-xs rounded transition-colors"
          >
      {{ $category->name_vi }}
          </button>
          @endforeach
    </div>

    {{-- Fabric Grid --}}
    <div class="grid grid-cols-4 gap-2">
          @foreach($fabrics as $fabric)
          <button
      x-on:click="selectFabric({{ $fabric->id }})"
      x-show="fabricCategory === null || fabricCategory === {{ $fabric->fabric_category_id }}"
      :class="selectedFabric === {{ $fabric->id }} ? 'ring-2 ring-[#8B0000]' : ''"
      class="aspect-square rounded overflow-hidden transition-all hover:scale-105"
          >
      @if($fabric->thumbnail)
      <img src="{{ $fabric->thumbnail }}" alt="{{ $fabric->name_vi }}" class="w-full h-full object-cover">
      @else
      <div class="w-full h-full bg-gray-300"></div>
      @endif
          </button>
          @endforeach
    </div>
      </div>
  </aside>

  {{-- Extras Panel --}}
  <aside class="options-panel" x-show="mainTab === 'extras'" style="width: 380px;">
      <div class="p-4">
    <h3 class="text-sm font-semibold text-gray-700 uppercase mb-4">Tùy chọn mở rộng</h3>
    <p class="text-sm text-gray-500">Coming soon...</p>
      </div>
  </aside>

  {{-- 3D Suit Viewer --}}
  <main class="flex-1 suit-viewer relative flex items-center justify-center">
      {{-- Price Panel --}}
      <div class="price-panel">
    <p class="text-3xl font-bold text-gray-800" x-text="formatPrice(totalPrice)"></p>
    <button
          x-on:click="addToCart()"
          class="continue-btn mt-4"
    >
          TIẾP TỤC »
    </button>
      </div>

      {{-- Suit Model Display --}}
      <div class="suit-image-container">
    {{-- Layer Images --}}
    <template x-for="layer in currentLayers" :key="layer.id">
          <img
      :src="layer.image_path"
      alt=""
      :style="'z-index: ' + layer.z_index"
      class="absolute inset-0 w-full h-full object-contain"
      x-on:error="$el.remove()"
          >
    </template>

    {{-- Loading State --}}
    <div x-show="loading" x-cloak class="absolute inset-0 bg-white/60 backdrop-blur-sm flex items-center justify-center z-50">
          <svg class="animate-spin h-10 w-10 text-[#8B0000]" fill="none" viewBox="0 0 24 24">
      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
    </div>
      </div>

      {{-- View Toggle Button - bên cạnh hình --}}
      <div class="flex flex-col items-center ml-4">
    <button
          x-on:click="currentView = currentView === 'front' ? 'back' : 'front'; loadLayers()"
          class="view-btn"
          :title="currentView === 'front' ? 'Xem mặt sau' : 'Xem mặt trước'"
    >
          <i class="fa-navigation">v</i>
    </button>
    <span class="text-xs text-gray-500 mt-2" x-text="currentView === 'front' ? 'Sau' : 'Trước'"></span>
      </div>
  </main>
  </div>
</div>

@push('scripts')
<script>
function suitConfigurator() {
  return {
  mainTab: 'style',
  activeCategory: 'suit_type',
  currentView: 'front',
  loading: false,
  selectedModel: null,
  selectedFabric: null,
  selectedOptions: {},
  fabricCategory: null,
  currentLayers: [],
  models: @json($suitModels),
  fabrics: @json($fabrics),
  optionTypes: @json($optionTypes),

  // Icon mapping for option types
  optionTypeIcons: {
      'suit_type': 'L',
      'jacket_style': 'a',
      'jacket_fit': 'b',
      'jacket_lapel_type': 'c',
      'jacket_buttons': 'C',
      'breast_pocket': 'l',
      'hip_pockets': 'l',
      'hip_pocket_style': 'l',
      'jacket_vent': 'p',
      'jacket_sleeve_buttons': 'j',
      'sleeve_vent_style': 'A',
      'pants_fit': 'b',
      'pants_peg': 's',
      'pants_front_pocket': 'e',
      'pants_back_pocket': 'o',
      'back_pocket_style': 'o',
      'pants_cuff': 'm',
  },

  // Icon mapping for option values
  iconMap: {
      'jacket_pants': 'L',
      'with_waistcoat': 'K',
      'single_breasted': 'a',
      'double_breasted': 'f',
      'mandarin': 'g',
      'classic_fit': 'a',
      'slim_fit': 'b',
      'notch_lapel': 'c',
      'peak_lapel': 'd',
      'shawl_lapel': 'e',
      '1_button': 'C',
      '2_buttons': 'C',
      '3_buttons': 'C',
      '4_buttons': 'C',
      '5_buttons': 'C',
      'with_breast_pocket': 'l',
      'no_breast_pocket': 'Z',
      'no_hip_pockets': 'Z',
      '2_welt_pockets': 'l',
      '3_welt_pockets': 'm',
      'welt_with_flap': 'l',
      'welt_no_flap': 'm',
      'patch_pockets': 'n',
      'no_vent': 'o',
      'center_vent': 'p',
      'side_vents': 'q',
      'no_sleeve_buttons': 'C',
      '2_sleeve_buttons': 'C',
      '3_sleeve_buttons': 'C',
      '4_sleeve_buttons': 'C',
      'functional_sleeve': 'A',
      'non_functional_sleeve': 'i',
      'classic_pants': 'a',
      'slim_pants': 'b',
      'no_pleats': 's',
      '1_pleat': 'c',
      '2_pleats': 'd',
      'slant_pockets': 'e',
      'vertical_pockets': 'g',
      'frogmouth_pockets': 'f',
      'no_back_pockets': 't',
      '1_back_pocket': 'n',
      '2_back_pockets': 'h',
      'standard_back_pocket': 'o',
      'patch_back_pocket': 'p',
      'flap_back_pocket': 'n',
      'standard_hem': 'm',
      'turnup_cuff': 'k',
  },

  getIconForOptionType(slug) {
      return this.optionTypeIcons[slug] || 'a';
  },

  getIconForValue(optionSlug, valueSlug) {
      return this.iconMap[valueSlug] || 'a';
  },

  scrollToOption(slug) {
      this.activeCategory = slug;
      const element = document.getElementById('option-' + slug);
      const panel = this.$refs.optionsPanel;
      if (element && panel) {
    const elementRect = element.getBoundingClientRect();
    const panelRect = panel.getBoundingClientRect();
    const offset = elementRect.top - panelRect.top + panel.scrollTop;
    panel.scrollTo({
          top: offset - 10,
          behavior: 'smooth'
    });
      }
  },

  async init() {
      this.optionTypes.forEach(type => {
    const defaultValue = type.values.find(v => v.is_default) || type.values[0];
    if (defaultValue) {
          this.selectedOptions[type.slug] = defaultValue.id;
    }
      });

      if (this.models.length > 0) {
    this.selectedModel = this.models[0].id;
      }
      if (this.fabrics.length > 0) {
    this.selectedFabric = this.fabrics[0].id;
      }

      await this.loadLayers();
  },

  async selectFabric(id) {
      this.selectedFabric = id;
      await this.loadLayers();
  },

  async selectOption(type, valueId) {
      this.selectedOptions[type] = valueId;
      this.activeCategory = type;
      await this.loadLayers();
  },

  async switchView(view) {
      this.currentView = view;
      await this.loadLayers();
  },

  async loadLayers() {
      if (!this.selectedModel) return;

      this.loading = true;
      try {
    const params = new URLSearchParams({
          model_id: this.selectedModel,
          view: this.currentView,
    });
    if (this.selectedFabric) {
          params.append('fabric_id', this.selectedFabric);
    }
    Object.entries(this.selectedOptions).forEach(([key, value]) => {
          params.append(`options[${key}]`, value);
    });

    const response = await fetch(`/api/configurator/layers?${params.toString()}`);
    const data = await response.json();

    if (data.success && data.layers) {
          this.currentLayers = data.layers;
    }
      } catch (error) {
    console.error('Error loading layers:', error);
      } finally {
    this.loading = false;
      }
  },

  get totalPrice() {
      let price = 0;
      const model = this.models.find(m => m.id === this.selectedModel);
      if (model) price += parseFloat(model.base_price);

      const fabric = this.fabrics.find(f => f.id === this.selectedFabric);
      if (fabric && fabric.price_modifier) price += parseFloat(fabric.price_modifier);

      Object.entries(this.selectedOptions).forEach(([typeSlug, valueId]) => {
    const optionType = this.optionTypes.find(t => t.slug === typeSlug);
    if (optionType) {
          const value = optionType.values.find(v => v.id === valueId);
          if (value && value.price_modifier) {
      price += parseFloat(value.price_modifier);
          }
    }
      });

      return price;
  },

  formatPrice(price) {
      return new Intl.NumberFormat('vi-VN').format(price) + ' VNĐ';
  },

  formatShortPrice(price) {
      if (price >= 1000000) {
    return (price / 1000000).toFixed(1) + 'tr';
      } else if (price >= 1000) {
    return (price / 1000).toFixed(0) + 'k';
      }
      return price;
  },

  async addToCart() {
      try {
    const response = await fetch('/api/cart/add', {
          method: 'POST',
          headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
          },
          body: JSON.stringify({
      model_id: this.selectedModel,
      fabric_id: this.selectedFabric,
      options: this.selectedOptions,
      price: this.totalPrice
          })
    });
    const data = await response.json();
    if (data.success) {
          window.location.href = '/cart';
    }
      } catch (error) {
    console.error('Error:', error);
      }
  }
  }
}
</script>
@endpush
@endsection
