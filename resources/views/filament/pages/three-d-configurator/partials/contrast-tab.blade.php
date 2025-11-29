{{-- Lining Selection --}}
<div class="mb-4">
  <h4 class="text-xs font-semibold text-gray-900 dark:text-white mb-2">Lining</h4>
  <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 4px;">
    <template x-for="fabric in contrastFabrics" :key="'lining-' + fabric.id">
      <button type="button" @click="selectLining(fabric)" class="aspect-square rounded border overflow-hidden transition-all" :class="selectedLining?.id === fabric.id
                      ? 'border-primary-500 ring-1 ring-primary-500/20'
                      : 'border-gray-200 dark:border-gray-700 hover:border-primary-300'">
        <template x-if="fabric.thumbnail_url">
          <img :src="fabric.thumbnail_url" :alt="fabric.name" class="w-full h-full object-cover">
        </template>
        <template x-if="!fabric.thumbnail_url">
          <div class="w-full h-full" :style="'background-color: ' + (fabric.color_hex || '#ccc')"></div>
        </template>
      </button>
    </template>
  </div>
</div>

{{-- Button Color Selection --}}
<div class="mb-4">
  <h4 class="text-xs font-semibold text-gray-900 dark:text-white mb-2">Button Color</h4>
  <div class="flex gap-1.5 flex-wrap">
    <template x-for="color in buttonColors" :key="color.code">
      <button type="button" @click="selectedButtonColor = color" class="w-7 h-7 rounded-full border transition-all" :class="selectedButtonColor?.code === color.code
                      ? 'border-primary-500 ring-1 ring-primary-500/20'
                      : 'border-gray-300 hover:border-primary-300'" :style="'background-color: ' + color.hex" :title="color.name"></button>
    </template>
  </div>
</div>

{{-- Thread Color Selection --}}
<div>
  <h4 class="text-xs font-semibold text-gray-900 dark:text-white mb-2">Thread Color</h4>
  <div class="flex gap-1.5 flex-wrap">
    <template x-for="color in threadColors" :key="color.code">
      <button type="button" @click="selectedThreadColor = color" class="w-7 h-7 rounded-full border transition-all" :class="selectedThreadColor?.code === color.code
                      ? 'border-primary-500 ring-1 ring-primary-500/20'
                      : 'border-gray-300 hover:border-primary-300'" :style="'background-color: ' + color.hex" :title="color.name"></button>
    </template>
  </div>
</div>
