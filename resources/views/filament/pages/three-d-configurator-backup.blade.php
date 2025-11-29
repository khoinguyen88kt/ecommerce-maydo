<x-filament-panels::page>
  <div x-data="threeDConfiguratorAdmin(@js($this->configData))" class="three-d-configurator-admin">
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
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
            </svg>
            Fabric
          </button>
          <button type="button" @click="activeTab = 'style'" class="flex-1 flex items-center justify-center gap-2 px-4 py-3 text-sm font-medium transition-colors" :class="activeTab === 'style'
                            ? 'bg-primary-50 dark:bg-primary-900/20 text-primary-600 border-b-2 border-primary-500'
                            : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800'">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
            </svg>
            Style
          </button>
          <button type="button" @click="activeTab = 'contrast'" class="flex-1 flex items-center justify-center gap-2 px-4 py-3 text-sm font-medium transition-colors" :class="activeTab === 'contrast'
                            ? 'bg-primary-50 dark:bg-primary-900/20 text-primary-600 border-b-2 border-primary-500'
                            : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800'">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
            </svg>
            Contrast
          </button>
        </div>

        {{-- Tab Content --}}
        <div class="flex-1 overflow-y-auto">

          {{-- Fabric Tab --}}
          <div x-show="activeTab === 'fabric'" x-cloak class="p-4">
            {{-- Search & Filter --}}
            <div class="mb-4">
              <div class="relative">
                <input type="text" x-model="fabricSearch" placeholder="Search fabrics..." class="w-full pl-10 pr-4 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                <svg class="absolute left-3 top-2.5 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
              </div>
            </div>

            {{-- Fabric Grid --}}
            <div class="grid grid-cols-2 gap-3">
              <template x-for="fabric in filteredFabrics" :key="fabric.id">
                <button type="button" @click="selectFabric(fabric)" class="group relative bg-white dark:bg-gray-800 rounded-lg border-2 overflow-hidden transition-all hover:shadow-lg" :class="selectedFabric?.id === fabric.id
                                        ? 'border-primary-500 ring-2 ring-primary-500/20'
                                        : 'border-gray-200 dark:border-gray-700 hover:border-primary-300'">
                  {{-- Fabric Image --}}
                  <div class="aspect-square relative">
                    <template x-if="fabric.thumbnail_url">
                      <img :src="fabric.thumbnail_url" :alt="fabric.name" class="w-full h-full object-cover">
                    </template>
                    <template x-if="!fabric.thumbnail_url && fabric.color_hex">
                      <div class="w-full h-full" :style="'background-color: ' + fabric.color_hex"></div>
                    </template>
                    <template x-if="!fabric.thumbnail_url && !fabric.color_hex">
                      <div class="w-full h-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                      </div>
                    </template>

                    {{-- Selected Checkmark --}}
                    <div x-show="selectedFabric?.id === fabric.id" class="absolute top-2 right-2 w-6 h-6 bg-primary-500 rounded-full flex items-center justify-center">
                      <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                      </svg>
                    </div>
                  </div>

                  {{-- Fabric Info --}}
                  <div class="p-2 text-left">
                    <p class="text-xs font-medium text-gray-900 dark:text-white truncate" x-text="fabric.name"></p>
                    <p class="text-xs text-gray-500 dark:text-gray-400" x-text="'$' + (fabric.price || 0)"></p>
                    <p x-show="fabric.material" class="text-[10px] text-gray-400 truncate" x-text="fabric.material"></p>
                  </div>
                </button>
              </template>
            </div>

            <template x-if="filteredFabrics.length === 0">
              <div class="text-center py-8 text-gray-500">
                <svg class="w-12 h-12 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-sm">No fabrics found</p>
              </div>
            </template>
          </div>

          {{-- Style Tab --}}
          <div x-show="activeTab === 'style'" x-cloak class="p-4">
            {{-- Category Navigation --}}
            <div class="flex flex-wrap gap-2 mb-4 pb-3 border-b border-gray-200 dark:border-gray-700">
              <template x-for="category in styleCategories" :key="category.id">
                <a :href="'#category-' + category.code" class="px-3 py-1.5 text-xs font-medium rounded-full transition-colors" :class="activeStyleCategory === category.code
                                        ? 'bg-primary-500 text-white'
                                        : 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700'" @click.prevent="scrollToCategory(category.code)" x-text="category.name"></a>
              </template>
            </div>

            {{-- Style Options by Category --}}
            <div class="space-y-6">
              <template x-for="category in styleCategories" :key="category.id">
                <div :id="'category-' + category.code" class="scroll-mt-4">
                  <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-3" x-text="category.name"></h4>

                  {{-- Options Grid --}}
                  <div class="grid grid-cols-3 gap-2">
                    <template x-for="option in category.options" :key="option.id">
                      <button type="button" @click="selectOption(category.code, option)" class="group relative bg-white dark:bg-gray-800 rounded-lg border-2 overflow-hidden transition-all" :class="currentConfig[category.code] === option.code
                                                    ? 'border-primary-500 ring-2 ring-primary-500/20'
                                                    : 'border-gray-200 dark:border-gray-700 hover:border-primary-300'">
                        {{-- Option Icon/Image --}}
                        <div class="aspect-square p-2 flex items-center justify-center bg-gray-50 dark:bg-gray-900">
                          <template x-if="option.icon_path || option.thumbnail_url">
                            <img :src="option.icon_path || option.thumbnail_url" :alt="option.name" class="w-full h-full object-contain">
                          </template>
                          <template x-if="!option.icon_path && !option.thumbnail_url">
                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6z" />
                              </svg>
                            </div>
                          </template>

                          {{-- Selected Indicator --}}
                          <div x-show="currentConfig[category.code] === option.code" class="absolute top-1 right-1 w-4 h-4 bg-primary-500 rounded-full flex items-center justify-center">
                            <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                            </svg>
                          </div>
                        </div>

                        {{-- Option Name --}}
                        <div class="px-1.5 py-1 text-center">
                          <p class="text-[10px] text-gray-700 dark:text-gray-300 truncate" x-text="option.name"></p>
                        </div>
                      </button>
                    </template>
                  </div>
                </div>
              </template>
            </div>

            <template x-if="styleCategories.length === 0">
              <div class="text-center py-8 text-gray-500">
                <p class="text-sm">No style options available</p>
                <p class="text-xs mt-1">Add categories and options in the admin panel</p>
              </div>
            </template>
          </div>

          {{-- Contrast Tab --}}
          <div x-show="activeTab === 'contrast'" x-cloak class="p-4">
            {{-- Lining Selection --}}
            <div class="mb-6">
              <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Lining</h4>
              <div class="grid grid-cols-4 gap-2">
                <template x-for="fabric in contrastFabrics" :key="'lining-' + fabric.id">
                  <button type="button" @click="selectLining(fabric)" class="aspect-square rounded-lg border-2 overflow-hidden transition-all" :class="selectedLining?.id === fabric.id
                                            ? 'border-primary-500 ring-2 ring-primary-500/20'
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
            <div class="mb-6">
              <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Button Color</h4>
              <div class="flex gap-2">
                <template x-for="color in buttonColors" :key="color.code">
                  <button type="button" @click="selectedButtonColor = color" class="w-10 h-10 rounded-full border-2 transition-all" :class="selectedButtonColor?.code === color.code
                                            ? 'border-primary-500 ring-2 ring-primary-500/20'
                                            : 'border-gray-300 hover:border-primary-300'" :style="'background-color: ' + color.hex" :title="color.name"></button>
                </template>
              </div>
            </div>

            {{-- Thread Color Selection --}}
            <div>
              <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Thread Color</h4>
              <div class="flex gap-2">
                <template x-for="color in threadColors" :key="color.code">
                  <button type="button" @click="selectedThreadColor = color" class="w-10 h-10 rounded-full border-2 transition-all" :class="selectedThreadColor?.code === color.code
                                            ? 'border-primary-500 ring-2 ring-primary-500/20'
                                            : 'border-gray-300 hover:border-primary-300'" :style="'background-color: ' + color.hex" :title="color.name"></button>
                </template>
              </div>
            </div>
          </div>
        </div>
      </div>

      {{-- 3D Viewer Panel --}}
      <div class="flex-1 flex flex-col">
        {{-- Viewer Container --}}
        <div class="flex-1 bg-gray-100 dark:bg-gray-800 rounded-xl overflow-hidden relative">
          {{-- 3D Canvas --}}
          <div x-ref="threeContainer" class="w-full h-full"></div>

          {{-- Loading Overlay --}}
          <div x-show="isLoading" x-transition class="absolute inset-0 bg-white/80 dark:bg-gray-900/80 flex items-center justify-center">
            <div class="text-center">
              <svg class="animate-spin h-12 w-12 text-primary-500 mx-auto mb-4" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              <p class="text-gray-600 dark:text-gray-400" x-text="loadingMessage">Loading...</p>
            </div>
          </div>

          {{-- Error Overlay --}}
          <div x-show="error" x-transition class="absolute inset-0 bg-white/90 dark:bg-gray-900/90 flex items-center justify-center">
            <div class="text-center text-red-500">
              <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
              </svg>
              <p x-text="error" class="mb-4"></p>
              <button @click="initThreeJS()" class="px-4 py-2 bg-primary-500 text-white rounded-lg hover:bg-primary-600">
                Retry
              </button>
            </div>
          </div>

          {{-- View Controls --}}
          <div class="absolute bottom-4 left-4 flex gap-2">
            <button type="button" @click="setView('front')" class="px-3 py-2 bg-white dark:bg-gray-800 rounded-lg shadow text-sm font-medium hover:bg-gray-50 dark:hover:bg-gray-700">Front</button>
            <button type="button" @click="setView('back')" class="px-3 py-2 bg-white dark:bg-gray-800 rounded-lg shadow text-sm font-medium hover:bg-gray-50 dark:hover:bg-gray-700">Back</button>
            <button type="button" @click="setView('side')" class="px-3 py-2 bg-white dark:bg-gray-800 rounded-lg shadow text-sm font-medium hover:bg-gray-50 dark:hover:bg-gray-700">Side</button>
          </div>

          {{-- Zoom Controls --}}
          <div class="absolute bottom-4 right-4 flex flex-col gap-2">
            <button type="button" @click="zoomIn()" class="w-10 h-10 bg-white dark:bg-gray-800 rounded-lg shadow flex items-center justify-center hover:bg-gray-50 dark:hover:bg-gray-700">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
              </svg>
            </button>
            <button type="button" @click="zoomOut()" class="w-10 h-10 bg-white dark:bg-gray-800 rounded-lg shadow flex items-center justify-center hover:bg-gray-50 dark:hover:bg-gray-700">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
              </svg>
            </button>
            <button type="button" @click="toggleWireframe()" class="w-10 h-10 rounded-lg shadow flex items-center justify-center" :class="wireframe ? 'bg-primary-500 text-white' : 'bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700'">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6z" />
              </svg>
            </button>
            <button type="button" @click="toggleFullscreen()" class="w-10 h-10 bg-white dark:bg-gray-800 rounded-lg shadow flex items-center justify-center hover:bg-gray-50 dark:hover:bg-gray-700">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
              </svg>
            </button>
          </div>
        </div>

        {{-- Price Summary --}}
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
              <button type="button" @click="takeScreenshot()" class="px-4 py-2 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 font-medium">
                <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Screenshot
              </button>
              <button type="button" @click="saveConfiguration()" class="px-6 py-2 bg-primary-500 text-white rounded-lg hover:bg-primary-600 font-medium">
                Save Configuration
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Instructions --}}
    <div class="mt-4 text-sm text-gray-500 dark:text-gray-400 text-center">
      💡 Drag to rotate | Scroll to zoom | Right-click to pan | Select fabric and options to customize
    </div>
  </div>

  {{-- Alpine.js Component Script --}}
  @script
  <script>
    // Register Alpine component immediately since Alpine is already loaded by Filament
    Alpine.data('threeDConfiguratorAdmin', (initialData) => ({
      // State
      productTypes: initialData?.productTypes || [],
      selectedProductType: null,
      categories: initialData?.categories || [],
      fabrics: initialData?.fabrics || [],
      currentConfig: initialData?.currentConfig || {},

      // UI State
      activeTab: 'fabric',
      activeStyleCategory: null,
      fabricSearch: '',
      isLoading: true,
      loadingMessage: 'Initializing 3D viewer...',
      error: null,
      wireframe: false,

      // Selected Items
      selectedFabric: null,
      selectedLining: null,
      selectedButtonColor: null,
      selectedThreadColor: null,

        // Price
        totalPrice: 0,

        // Contrast Options
        buttonColors: [{
            code: 'black'
            , name: 'Black'
            , hex: '#1a1a1a'
          }
          , {
            code: 'brown'
            , name: 'Brown'
            , hex: '#8B4513'
          }
          , {
            code: 'navy'
            , name: 'Navy'
            , hex: '#000080'
          }
          , {
            code: 'silver'
            , name: 'Silver'
            , hex: '#C0C0C0'
          }
          , {
            code: 'gold'
            , name: 'Gold'
            , hex: '#FFD700'
          }
        , ]
        , threadColors: [{
            code: 'matching'
            , name: 'Matching'
            , hex: '#808080'
          }
          , {
            code: 'white'
            , name: 'White'
            , hex: '#FFFFFF'
          }
          , {
            code: 'black'
            , name: 'Black'
            , hex: '#1a1a1a'
          }
          , {
            code: 'red'
            , name: 'Red'
            , hex: '#DC143C'
          }
          , {
            code: 'blue'
            , name: 'Blue'
            , hex: '#0000CD'
          }
        , ],

        // Three.js
        THREE: null
        , scene: null
        , camera: null
        , renderer: null
        , controls: null
        , model: null,

        // Computed
        get filteredFabrics() {
          if (!this.fabricSearch) return this.fabrics;
          const search = this.fabricSearch.toLowerCase();
          return this.fabrics.filter(f =>
            f.name ? .toLowerCase().includes(search) ||
            f.material ? .toLowerCase().includes(search) ||
            f.code ? .toLowerCase().includes(search)
          );
        },

        get styleCategories() {
          return this.categories.filter(c =>
            c.display_type !== 'fabric' && c.display_type !== 'contrast'
          );
        },

        get contrastFabrics() {
          return this.fabrics.slice(0, 8); // Show first 8 as lining options
        },

        // Lifecycle
        init() {
          // Set initial product type
          if (initialData ? .productType) {
            this.selectedProductType = initialData.productType;
          } else if (this.productTypes.length > 0) {
            this.selectedProductType = this.productTypes[0];
          }

          // Set first style category as active
          if (this.styleCategories.length > 0) {
            this.activeStyleCategory = this.styleCategories[0].code;
          }

          // Set default selections
          this.selectedButtonColor = this.buttonColors[0];
          this.selectedThreadColor = this.threadColors[0];

          // Calculate initial price
          this.calculatePrice();

          // Initialize Three.js
          this.$nextTick(() => {
            this.initThreeJS();
          });
        },

        // Methods
        selectProductType(productType) {
          this.selectedProductType = productType;
          // Reload configuration for this product type
          this.loadProductConfiguration();
        },

        async loadProductConfiguration() {
          try {
            this.isLoading = true;
            this.loadingMessage = 'Loading configuration...';

            const response = await fetch(`/api/3d-configurator/${this.selectedProductType.code}`);
            const data = await response.json();

            if (data.success) {
              this.categories = data.data.categories || [];
              this.currentConfig = data.data.default_config || {};

              if (this.styleCategories.length > 0) {
                this.activeStyleCategory = this.styleCategories[0].code;
              }

              // Reload model
              await this.loadModel();
            }
          } catch (error) {
            console.error('Failed to load configuration:', error);
            this.error = 'Failed to load configuration';
          } finally {
            this.isLoading = false;
          }
        },

        selectFabric(fabric) {
          this.selectedFabric = fabric;
          this.applyFabricTexture(fabric);
          this.calculatePrice();
        },

        selectLining(fabric) {
          this.selectedLining = fabric;
          // Apply lining texture to lining meshes
        },

        selectOption(categoryCode, option) {
          this.currentConfig[categoryCode] = option.code;
          // Update 3D model
          this.updateModelPart(categoryCode, option);
          this.calculatePrice();
        },

        scrollToCategory(categoryCode) {
          this.activeStyleCategory = categoryCode;
          const el = document.getElementById('category-' + categoryCode);
          if (el) {
            el.scrollIntoView({
              behavior: 'smooth'
              , block: 'start'
            });
          }
        },

        calculatePrice() {
          let price = this.selectedFabric ? .price || 0;

          // Add price modifiers from selected options
          for (const category of this.categories) {
            const selectedCode = this.currentConfig[category.code];
            const option = category.options ? .find(o => o.code === selectedCode);
            if (option ? .price_modifier) {
              price += parseFloat(option.price_modifier);
            }
          }

          this.totalPrice = price.toFixed(0);
        },

        // Three.js Methods
        async initThreeJS() {
          const container = this.$refs.threeContainer;
          if (!container) return;

          try {
            this.loadingMessage = 'Loading 3D engine...';

            // Import Three.js
            const THREE = await import('https://esm.sh/three@0.160.0');
            const {
              OrbitControls
            } = await import('https://esm.sh/three@0.160.0/examples/jsm/controls/OrbitControls.js');
            const {
              GLTFLoader
            } = await import('https://esm.sh/three@0.160.0/examples/jsm/loaders/GLTFLoader.js');

            this.THREE = THREE;
            this.GLTFLoader = GLTFLoader;

            // Setup scene
            this.scene = new THREE.Scene();
            this.scene.background = new THREE.Color(0xf5f5f5);

            // Setup camera
            const aspect = container.clientWidth / container.clientHeight;
            this.camera = new THREE.PerspectiveCamera(45, aspect, 0.1, 1000);
            this.camera.position.set(0, 1.2, 3);

            // Setup renderer
            this.renderer = new THREE.WebGLRenderer({
              antialias: true
              , preserveDrawingBuffer: true
            });
            this.renderer.setSize(container.clientWidth, container.clientHeight);
            this.renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
            this.renderer.shadowMap.enabled = true;
            this.renderer.shadowMap.type = THREE.PCFSoftShadowMap;
            this.renderer.outputColorSpace = THREE.SRGBColorSpace;
            container.appendChild(this.renderer.domElement);

            // Setup lights
            const ambientLight = new THREE.AmbientLight(0xffffff, 0.7);
            this.scene.add(ambientLight);

            const mainLight = new THREE.DirectionalLight(0xffffff, 1.0);
            mainLight.position.set(5, 10, 7);
            mainLight.castShadow = true;
            this.scene.add(mainLight);

            const fillLight = new THREE.DirectionalLight(0xffffff, 0.5);
            fillLight.position.set(-5, 5, -5);
            this.scene.add(fillLight);

            const backLight = new THREE.DirectionalLight(0xffffff, 0.3);
            backLight.position.set(0, -5, -10);
            this.scene.add(backLight);

            // Setup controls
            this.controls = new OrbitControls(this.camera, this.renderer.domElement);
            this.controls.enableDamping = true;
            this.controls.dampingFactor = 0.05;
            this.controls.target.set(0, 0.8, 0);
            this.controls.minDistance = 1.5;
            this.controls.maxDistance = 8;
            this.controls.update();

            // Animation loop
            const animate = () => {
              requestAnimationFrame(animate);
              this.controls ? .update();
              this.renderer ? .render(this.scene, this.camera);
            };
            animate();

            // Handle resize
            const resizeObserver = new ResizeObserver(() => {
              if (!container.clientWidth || !container.clientHeight) return;
              this.camera.aspect = container.clientWidth / container.clientHeight;
              this.camera.updateProjectionMatrix();
              this.renderer.setSize(container.clientWidth, container.clientHeight);
            });
            resizeObserver.observe(container);

            // Load initial model
            await this.loadModel();

            this.error = null;
            this.isLoading = false;

          } catch (err) {
            console.error('Three.js init error:', err);
            this.error = 'Failed to initialize 3D viewer: ' + err.message;
            this.isLoading = false;
          }
        },

        async loadModel() {
          if (!this.THREE || !this.GLTFLoader) return;

          this.loadingMessage = 'Loading 3D model...';

          // For now, load a placeholder/demo model
          // In production, this would load based on selectedProductType
          const modelUrl = '/storage/3d-models/demo-suit.glb';

          try {
            const loader = new this.GLTFLoader();

            // Try to load model, if fails show placeholder
            loader.load(
              modelUrl
              , (gltf) => {
                if (this.model) {
                  this.scene.remove(this.model);
                }

                this.model = gltf.scene;
                this.centerModel();

                // Setup shadows
                this.model.traverse((child) => {
                  if (child.isMesh) {
                    child.castShadow = true;
                    child.receiveShadow = true;
                    child.userData.originalMaterial = child.material.clone();
                  }
                });

                this.scene.add(this.model);
                this.isLoading = false;
              }
              , (progress) => {
                const percent = Math.round((progress.loaded / progress.total) * 100);
                this.loadingMessage = `Loading model... ${percent}%`;
              }
              , (error) => {
                console.warn('Model not found, creating placeholder:', error);
                this.createPlaceholderModel();
                this.isLoading = false;
              }
            );
          } catch (err) {
            console.warn('Error loading model:', err);
            this.createPlaceholderModel();
            this.isLoading = false;
          }
        },

        createPlaceholderModel() {
          const THREE = this.THREE;

          // Create a simple placeholder (mannequin-like shape)
          const group = new THREE.Group();

          // Torso
          const torsoGeometry = new THREE.CylinderGeometry(0.3, 0.25, 0.8, 32);
          const torsoMaterial = new THREE.MeshStandardMaterial({
            color: 0x3b82f6
            , roughness: 0.7
            , metalness: 0.0
          });
          const torso = new THREE.Mesh(torsoGeometry, torsoMaterial);
          torso.position.y = 1.2;
          group.add(torso);

          // Shoulders
          const shoulderGeometry = new THREE.CapsuleGeometry(0.08, 0.5, 8, 16);
          const shoulderMaterial = new THREE.MeshStandardMaterial({
            color: 0x3b82f6
            , roughness: 0.7
          });

          const leftShoulder = new THREE.Mesh(shoulderGeometry, shoulderMaterial);
          leftShoulder.rotation.z = Math.PI / 2;
          leftShoulder.position.set(-0.45, 1.5, 0);
          group.add(leftShoulder);

          const rightShoulder = new THREE.Mesh(shoulderGeometry, shoulderMaterial);
          rightShoulder.rotation.z = Math.PI / 2;
          rightShoulder.position.set(0.45, 1.5, 0);
          group.add(rightShoulder);

          // Arms
          const armGeometry = new THREE.CylinderGeometry(0.06, 0.05, 0.6, 16);
          const armMaterial = new THREE.MeshStandardMaterial({
            color: 0x3b82f6
            , roughness: 0.7
          });

          const leftArm = new THREE.Mesh(armGeometry, armMaterial);
          leftArm.position.set(-0.45, 1.1, 0);
          group.add(leftArm);

          const rightArm = new THREE.Mesh(armGeometry, armMaterial);
          rightArm.position.set(0.45, 1.1, 0);
          group.add(rightArm);

          // Pants (lower body)
          const pantsGeometry = new THREE.CylinderGeometry(0.25, 0.15, 0.6, 32);
          const pantsMaterial = new THREE.MeshStandardMaterial({
            color: 0x1e3a5f
            , roughness: 0.8
          });
          const pants = new THREE.Mesh(pantsGeometry, pantsMaterial);
          pants.position.y = 0.5;
          group.add(pants);

          // Legs
          const legGeometry = new THREE.CylinderGeometry(0.08, 0.06, 0.5, 16);
          const legMaterial = new THREE.MeshStandardMaterial({
            color: 0x1e3a5f
            , roughness: 0.8
          });

          const leftLeg = new THREE.Mesh(legGeometry, legMaterial);
          leftLeg.position.set(-0.12, 0, 0);
          group.add(leftLeg);

          const rightLeg = new THREE.Mesh(legGeometry, legMaterial);
          rightLeg.position.set(0.12, 0, 0);
          group.add(rightLeg);

          // Add to scene
          if (this.model) {
            this.scene.remove(this.model);
          }
          this.model = group;
          this.scene.add(group);

          // Store materials for fabric application
          group.traverse((child) => {
            if (child.isMesh) {
              child.castShadow = true;
              child.receiveShadow = true;
              child.userData.originalMaterial = child.material.clone();
            }
          });
        },

        centerModel() {
          if (!this.model || !this.THREE) return;

          const box = new this.THREE.Box3().setFromObject(this.model);
          const center = box.getCenter(new this.THREE.Vector3());
          const size = box.getSize(new this.THREE.Vector3());

          this.model.position.x = -center.x;
          this.model.position.y = -box.min.y;
          this.model.position.z = -center.z;

          // Adjust camera
          const maxDim = Math.max(size.x, size.y, size.z);
          if (maxDim > 3) {
            const scale = 3 / maxDim;
            this.model.scale.setScalar(scale);
          }
        },

        applyFabricTexture(fabric) {
          if (!this.model || !this.THREE) return;

          const THREE = this.THREE;
          const textureUrl = fabric.texture_url || fabric.thumbnail_url;

          if (textureUrl) {
            const textureLoader = new THREE.TextureLoader();
            textureLoader.load(
              textureUrl
              , (texture) => {
                texture.wrapS = THREE.RepeatWrapping;
                texture.wrapT = THREE.RepeatWrapping;
                texture.repeat.set(8, 8);
                texture.colorSpace = THREE.SRGBColorSpace;

                this.model.traverse((child) => {
                  if (child.isMesh) {
                    child.material.color = new THREE.Color(0xffffff);
                    child.material.map = texture;
                    child.material.metalness = 0.0;
                    child.material.roughness = 0.8;
                    child.material.needsUpdate = true;
                  }
                });
              }
              , undefined
              , (error) => {
                console.error('Error loading texture:', error);
                this.applyFabricColor(fabric.color_hex || '#3b82f6');
              }
            );
          } else if (fabric.color_hex) {
            this.applyFabricColor(fabric.color_hex);
          }
        },

        applyFabricColor(colorHex) {
          if (!this.model || !this.THREE) return;

          const color = new this.THREE.Color(colorHex);

          this.model.traverse((child) => {
            if (child.isMesh) {
              child.material.map = null;
              child.material.color = color;
              child.material.needsUpdate = true;
            }
          });
        },

        updateModelPart(categoryCode, option) {
          // This would load/swap specific model parts
          // For now, just log the change
          console.log('Update model part:', categoryCode, option.code);
        },

        // View Controls
        setView(view) {
          if (!this.camera || !this.controls) return;

          const views = {
            front: {
              x: 0
              , y: 1.2
              , z: 3
            }
            , back: {
              x: 0
              , y: 1.2
              , z: -3
            }
            , side: {
              x: 3
              , y: 1.2
              , z: 0
            }
          , };

          const pos = views[view] || views.front;
          this.camera.position.set(pos.x, pos.y, pos.z);
          this.controls.target.set(0, 0.8, 0);
          this.controls.update();
        },

        zoomIn() {
          if (!this.camera) return;
          this.camera.position.multiplyScalar(0.9);
        },

        zoomOut() {
          if (!this.camera) return;
          this.camera.position.multiplyScalar(1.1);
        },

        toggleWireframe() {
          if (!this.model) return;
          this.wireframe = !this.wireframe;

          this.model.traverse((child) => {
            if (child.isMesh) {
              child.material.wireframe = this.wireframe;
            }
          });
        },

        toggleFullscreen() {
          const container = this.$refs.threeContainer;
          if (!document.fullscreenElement) {
            container.requestFullscreen();
          } else {
            document.exitFullscreen();
          }
        },

        takeScreenshot() {
          if (!this.renderer) return;

          // Render current frame
          this.renderer.render(this.scene, this.camera);

          // Get data URL
          const dataUrl = this.renderer.domElement.toDataURL('image/png');

          // Download
          const link = document.createElement('a');
          link.download = `suit-config-${Date.now()}.png`;
          link.href = dataUrl;
          link.click();
        },

        async saveConfiguration() {
          try {
            const config = {
              product_type: this.selectedProductType ? .code
              , fabric_id: this.selectedFabric ? .id
              , config: this.currentConfig
              , lining_id: this.selectedLining ? .id
              , button_color: this.selectedButtonColor ? .code
              , thread_color: this.selectedThreadColor ? .code
            , };

            // Would save to backend
            console.log('Save configuration:', config);

            // Show success notification
            if (window.$wire) {
              window.$wire.dispatch('notify', {
                type: 'success'
                , message: 'Configuration saved successfully!'
              });
            } else {
              alert('Configuration saved! (Check console for details)');
            }
          } catch (error) {
            console.error('Failed to save configuration:', error);
            alert('Failed to save configuration');
          }
        },
      }));
  </script>
  @endscript

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
</x-filament-panels::page>
