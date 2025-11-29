{{-- Viewer Container --}}
<div class="flex-1 bg-gray-100 dark:bg-gray-800 rounded-xl overflow-hidden relative min-h-[500px] flex items-center justify-center">
  {{-- 3D Canvas - Centered --}}
  <div x-ref="threeContainer" class="absolute inset-0 flex items-center justify-center"></div>

  {{-- Loading Overlay --}}
  <div x-show="isLoading" x-transition class="absolute inset-0 bg-white/80 dark:bg-gray-900/80 flex items-center justify-center">
    <div class="text-center">
      <x-filament::loading-indicator class="h-12 w-12 text-primary-500 mx-auto mb-4" />
      <p class="text-gray-600 dark:text-gray-400" x-text="loadingMessage">Loading...</p>
    </div>
  </div>

  {{-- Error Overlay --}}
  <div x-show="error" x-transition class="absolute inset-0 bg-white/90 dark:bg-gray-900/90 flex items-center justify-center">
    <div class="text-center text-red-500">
      <x-heroicon-o-exclamation-triangle class="w-16 h-16 mx-auto mb-4" />
      <p x-text="error" class="mb-4"></p>
      <x-filament::button @click="initThreeJS()" color="primary">
        Retry
      </x-filament::button>
    </div>
  </div>

  {{-- View Controls --}}
  <div class="absolute bottom-4 left-4 flex gap-2">
    <x-filament::button size="sm" color="gray" @click="setView('front')">Front</x-filament::button>
    <x-filament::button size="sm" color="gray" @click="setView('back')">Back</x-filament::button>
    <x-filament::button size="sm" color="gray" @click="setView('side')">Side</x-filament::button>
  </div>

  {{-- Zoom Controls --}}
  <div class="absolute bottom-4 right-4 flex flex-col gap-2">
    <button type="button" @click="zoomIn()" class="w-10 h-10 bg-white dark:bg-gray-800 rounded-lg shadow flex items-center justify-center hover:bg-gray-50 dark:hover:bg-gray-700">
      <x-heroicon-o-plus class="w-5 h-5" />
    </button>
    <button type="button" @click="zoomOut()" class="w-10 h-10 bg-white dark:bg-gray-800 rounded-lg shadow flex items-center justify-center hover:bg-gray-50 dark:hover:bg-gray-700">
      <x-heroicon-o-minus class="w-5 h-5" />
    </button>
    <button type="button" @click="toggleWireframe()" class="w-10 h-10 rounded-lg shadow flex items-center justify-center" :class="wireframe ? 'bg-primary-500 text-white' : 'bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700'">
      <x-heroicon-o-cube-transparent class="w-5 h-5" />
    </button>
    <button type="button" @click="toggleFullscreen()" class="w-10 h-10 bg-white dark:bg-gray-800 rounded-lg shadow flex items-center justify-center hover:bg-gray-50 dark:hover:bg-gray-700">
      <x-heroicon-o-arrows-pointing-out class="w-5 h-5" />
    </button>
  </div>
</div>
