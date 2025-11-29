<x-filament-panels::page>
  <div x-data="threeDConfiguratorAdmin(@js($this->configData))" class="three-d-configurator-admin" x-cloak>
    {{-- Main Layout: Left Sidebar + 3D Viewer --}}
    <div class="flex gap-4 h-[calc(100vh-200px)] min-h-[600px]">

      {{-- Left Sidebar: Configuration Panel --}}
      <div class="w-[380px] flex-shrink-0 bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 flex flex-col overflow-hidden">

        {{-- Product Type Tabs --}}
        <div class="flex border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
          <template x-for="productType in productTypes" :key="productType.id">
            <button type="button" @click="selectProductType(productType)" class="flex-1 px-4 py-3 text-sm font-medium transition-colors" :class="selectedProductType?.id === productType.id
                                ? 'bg-white dark:bg-gray-900 text-primary-600 border-b-2 border-primary-500'
                                : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white'" x-text="productType.name"></button>
          </template>
        </div>

        {{-- Main Tabs: Fabric / Style / Contrast --}}
        <div class="flex border-b border-gray-200 dark:border-gray-700">
          <button type="button" @click="activeTab = 'fabric'" class="flex-1 flex items-center justify-center gap-2 px-4 py-3 text-sm font-medium transition-colors" :class="activeTab === 'fabric'
                            ? 'bg-primary-50 dark:bg-primary-900/20 text-primary-600 border-b-2 border-primary-500'
                            : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800'">
            <x-heroicon-o-squares-2x2 class="w-5 h-5" />
            Fabric
          </button>
          <button type="button" @click="activeTab = 'style'" class="flex-1 flex items-center justify-center gap-2 px-4 py-3 text-sm font-medium transition-colors" :class="activeTab === 'style'
                            ? 'bg-primary-50 dark:bg-primary-900/20 text-primary-600 border-b-2 border-primary-500'
                            : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800'">
            <x-heroicon-o-adjustments-horizontal class="w-5 h-5" />
            Style
          </button>
          <button type="button" @click="activeTab = 'contrast'" class="flex-1 flex items-center justify-center gap-2 px-4 py-3 text-sm font-medium transition-colors" :class="activeTab === 'contrast'
                            ? 'bg-primary-50 dark:bg-primary-900/20 text-primary-600 border-b-2 border-primary-500'
                            : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800'">
            <x-heroicon-o-swatch class="w-5 h-5" />
            Contrast
          </button>
        </div>

        {{-- Tab Content --}}
        <div class="flex-1 overflow-y-auto">
          {{-- Fabric Tab --}}
          <div x-show="activeTab === 'fabric'" x-cloak class="p-4">
            @include('filament.pages.three-d-configurator.partials.fabric-tab')
          </div>

          {{-- Style Tab --}}
          <div x-show="activeTab === 'style'" x-cloak class="p-4">
            @include('filament.pages.three-d-configurator.partials.style-tab')
          </div>

          {{-- Contrast Tab --}}
          <div x-show="activeTab === 'contrast'" x-cloak class="p-4">
            @include('filament.pages.three-d-configurator.partials.contrast-tab')
          </div>
        </div>
      </div>

      {{-- 3D Viewer Panel --}}
      <div class="flex-1 flex flex-col">
        @include('filament.pages.three-d-configurator.partials.viewer-panel')

        {{-- Price Summary --}}
        @include('filament.pages.three-d-configurator.partials.price-summary')
      </div>
    </div>

    {{-- Instructions --}}
    <div class="mt-4 text-sm text-gray-500 dark:text-gray-400 text-center">
      💡 Drag to rotate | Scroll to zoom | Right-click to pan | Select fabric and options to customize
    </div>
  </div>

  {{-- Styles --}}
  @push('styles')
  <style>
    [x-cloak] {
      display: none !important;
    }

    .three-d-configurator-admin {

      /* Custom scrollbar */
      & ::-webkit-scrollbar {
        width: 6px;
        height: 6px;
      }

      & ::-webkit-scrollbar-track {
        background: transparent;
      }

      & ::-webkit-scrollbar-thumb {
        background: rgba(156, 163, 175, 0.5);
        border-radius: 3px;
      }

      & ::-webkit-scrollbar-thumb:hover {
        background: rgba(156, 163, 175, 0.7);
      }
    }

  </style>
  @endpush

  {{-- Load the Alpine component --}}
  @push('scripts')
  <script type="module">
    import { createThreeDConfiguratorAdmin } from '{{ Vite::asset('resources/js/three-d-configurator-admin.js') }}';

    // Register Alpine component if not already registered via alpine:init
    if (window.Alpine && !window.Alpine.data('threeDConfiguratorAdmin')) {
      window.Alpine.data('threeDConfiguratorAdmin', createThreeDConfiguratorAdmin);
    }
  </script>
  @endpush
</x-filament-panels::page>
