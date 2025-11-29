{{-- Price Summary Panel --}}
<div class="mt-4 bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
  <div class="flex items-center justify-between">
    <div>
      <div class="flex items-baseline gap-2">
        <span class="text-2xl font-bold text-gray-900 dark:text-white" x-text="'$' + totalPrice"></span>
        <span class="text-sm text-gray-500">total</span>
      </div>
      <div class="text-sm text-gray-500 mt-1">
        <span x-text="selectedProductType?.name || 'Product'"></span>:
        <span x-text="'$' + (selectedFabric?.price || 0)"></span>
      </div>
    </div>
    <div class="flex gap-3">
      <x-filament::button color="gray" @click="takeScreenshot()">
        <x-slot name="icon">
          <x-heroicon-o-camera class="w-5 h-5" />
        </x-slot>
        Screenshot
      </x-filament::button>
      <x-filament::button color="primary" @click="saveConfiguration()">
        Save Configuration
      </x-filament::button>
    </div>
  </div>
</div>
