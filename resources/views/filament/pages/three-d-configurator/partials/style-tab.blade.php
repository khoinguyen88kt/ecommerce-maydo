{{-- Category Navigation --}}
<div class="flex flex-wrap gap-1 mb-3 pb-2 border-b border-gray-200 dark:border-gray-700">
  <template x-for="category in styleCategories" :key="category.id">
    <a :href="'#category-' + category.code" class="px-2 py-1 text-[10px] font-medium rounded-full transition-colors" :class="activeStyleCategory === category.code
               ? 'bg-primary-500 text-white'
               : 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700'" @click.prevent="scrollToCategory(category.code)" x-text="category.name"></a>
  </template>
</div>

{{-- Style Options by Category --}}
<div class="space-y-3">
  <template x-for="category in styleCategories" :key="category.id">
    <div :id="'category-' + category.code" class="scroll-mt-2">
      <h4 class="text-xs font-semibold text-gray-900 dark:text-white mb-2" x-text="category.name"></h4>

      {{-- Options Grid - 4 columns --}}
      <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 4px;">
        <template x-for="option in category.options" :key="option.id">
          <button type="button" @click="selectOption(category.code, option)" class="group relative bg-white dark:bg-gray-800 rounded border overflow-hidden transition-all" :class="currentConfig[category.code] === option.code
                          ? 'border-primary-500 ring-1 ring-primary-500/20'
                          : 'border-gray-200 dark:border-gray-700 hover:border-primary-300'">
            {{-- Option Icon/Image --}}
            <div class="aspect-square p-1 flex items-center justify-center bg-gray-50 dark:bg-gray-900">
              <template x-if="option.icon_path || option.thumbnail_url">
                <img :src="option.icon_path || option.thumbnail_url" :alt="option.name" class="w-full h-full object-contain">
              </template>
              <template x-if="!option.icon_path && !option.thumbnail_url">
                <div class="w-full h-full flex items-center justify-center text-gray-400">
                  <x-heroicon-o-squares-2x2 class="w-6 h-6" />
                </div>
              </template>

              {{-- Selected Indicator --}}
              <div x-show="currentConfig[category.code] === option.code" class="absolute top-0.5 right-0.5 w-3 h-3 bg-primary-500 rounded-full flex items-center justify-center">
                <x-heroicon-o-check class="w-2 h-2 text-white" />
              </div>
            </div>

            {{-- Option Name --}}
            <div class="px-0.5 py-0.5 text-center">
              <p class="text-[8px] text-gray-700 dark:text-gray-300 truncate leading-tight" x-text="option.name"></p>
            </div>
          </button>
        </template>
      </div>
    </div>
  </template>
</div>

{{-- Empty State --}}
<template x-if="styleCategories.length === 0">
  <div class="text-center py-8 text-gray-500">
    <p class="text-sm">No style options available</p>
    <p class="text-xs mt-1">Add categories and options in the admin panel</p>
  </div>
</template>
