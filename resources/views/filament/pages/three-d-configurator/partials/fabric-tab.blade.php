{{-- Search & Filter --}}
<div class="mb-4">
  <div class="relative">
    <input type="text" x-model="fabricSearch" placeholder="Search fabrics..." class="w-full pl-10 pr-4 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
    <x-heroicon-o-magnifying-glass class="absolute left-3 top-2.5 w-4 h-4 text-gray-400" />
  </div>
</div>

{{-- Fabric Grid with Pagination - 4 columns --}}
<div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 6px;">
  <template x-for="fabric in paginatedFabrics" :key="fabric.id">
    <button type="button" @click="selectFabric(fabric)" class="group relative bg-white dark:bg-gray-800 rounded border overflow-hidden transition-all hover:shadow-sm" :class="selectedFabric?.id === fabric.id
                    ? 'border-primary-500 ring-1 ring-primary-500/20'
                    : 'border-gray-200 dark:border-gray-700 hover:border-primary-300'">
      {{-- Fabric Image with Lazy Loading --}}
      <div class="aspect-square relative">
        <img x-show="fabric.thumbnail_url" :src="fabric.thumbnail_url" :alt="fabric.name" loading="lazy" class="w-full h-full object-cover">
        <div x-show="!fabric.thumbnail_url && fabric.color_hex" class="w-full h-full" :style="'background-color: ' + fabric.color_hex"></div>
        <div x-show="!fabric.thumbnail_url && !fabric.color_hex" class="w-full h-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
          <x-heroicon-o-photo class="w-8 h-8 text-gray-400" />
        </div>

        {{-- Selected Checkmark --}}
        <div x-show="selectedFabric?.id === fabric.id" class="absolute top-0.5 right-0.5 w-4 h-4 bg-primary-500 rounded-full flex items-center justify-center">
          <x-heroicon-o-check class="w-2.5 h-2.5 text-white" />
        </div>
      </div>

      {{-- Fabric Info - Ultra Compact --}}
      <div class="p-1 text-left">
        <p class="text-[9px] font-medium text-gray-900 dark:text-white truncate leading-tight" x-text="fabric.name"></p>
        <p class="text-[9px] text-gray-500 dark:text-gray-400" x-text="'$' + (fabric.price || 0)"></p>
      </div>
    </button>
  </template>
</div>

{{-- Pagination Controls --}}
<div x-show="totalFabricPages > 1" class="mt-4 flex items-center justify-between">
  <p class="text-xs text-gray-500">
    Showing <span x-text="((fabricPage - 1) * fabricsPerPage) + 1"></span>-<span x-text="Math.min(fabricPage * fabricsPerPage, filteredFabrics.length)"></span>
    of <span x-text="filteredFabrics.length"></span>
  </p>
  <div class="flex gap-1">
    <button type="button" @click="fabricPage = 1" :disabled="fabricPage === 1" class="px-2 py-1 text-xs rounded border border-gray-300 dark:border-gray-600 disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-100 dark:hover:bg-gray-700">
      <x-heroicon-o-chevron-double-left class="w-3 h-3" />
    </button>
    <button type="button" @click="fabricPage--" :disabled="fabricPage === 1" class="px-2 py-1 text-xs rounded border border-gray-300 dark:border-gray-600 disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-100 dark:hover:bg-gray-700">
      <x-heroicon-o-chevron-left class="w-3 h-3" />
    </button>
    <span class="px-3 py-1 text-xs bg-primary-500 text-white rounded" x-text="fabricPage"></span>
    <button type="button" @click="fabricPage++" :disabled="fabricPage >= totalFabricPages" class="px-2 py-1 text-xs rounded border border-gray-300 dark:border-gray-600 disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-100 dark:hover:bg-gray-700">
      <x-heroicon-o-chevron-right class="w-3 h-3" />
    </button>
    <button type="button" @click="fabricPage = totalFabricPages" :disabled="fabricPage >= totalFabricPages" class="px-2 py-1 text-xs rounded border border-gray-300 dark:border-gray-600 disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-100 dark:hover:bg-gray-700">
      <x-heroicon-o-chevron-double-right class="w-3 h-3" />
    </button>
  </div>
</div>

{{-- Empty State --}}
<div x-show="filteredFabrics.length === 0" class="text-center py-8 text-gray-500">
  <x-heroicon-o-face-frown class="w-12 h-12 mx-auto mb-2 text-gray-300" />
  <p class="text-sm">No fabrics found</p>
</div>
