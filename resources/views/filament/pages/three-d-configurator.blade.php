<x-filament-panels::page>
  <div x-data="threeDConfiguratorAdmin(@js($this->configData))" class="three-d-configurator-admin" x-cloak>
    {{-- Main Layout: Left Sidebar + 3D Viewer --}}
    <div class="flex gap-4" style="height: calc(100vh - 200px); min-height: 600px;">

      {{-- Left Sidebar: Configuration Panel --}}
      <div class="w-[320px] flex-shrink-0 bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 flex flex-col overflow-hidden" style="width: 320px;">

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

  {{-- Alpine Component Registration Script --}}
  <script>
    // Store Three.js objects OUTSIDE Alpine to avoid proxy conflicts
    window.threeContext = {
      THREE: null
      , GLTFLoader: null
      , scene: null
      , camera: null
      , renderer: null
      , controls: null
      , model: null
    };

    // Create component factory function
    function createThreeDConfiguratorAdmin(initialData) {
      return {
        // ==================== STATE ====================
        productTypes: initialData?.productTypes || []
        , selectedProductType: null
        , categories: initialData?.categories || []
        , fabrics: initialData?.fabrics || []
        , currentConfig: initialData?.currentConfig || {},
        // UI State
        activeTab: 'fabric'
        , activeStyleCategory: null
        , fabricSearch: ''
        , isLoading: true
        , loadingMessage: 'Initializing 3D viewer...'
        , error: null
        , wireframe: false,

        // Selections
        selectedFabric: null
        , selectedLining: null
        , selectedButtonColor: null
        , selectedThreadColor: null,

        // Price
        totalPrice: 0,

        // Pagination
        fabricPage: 1
        , fabricsPerPage: 20,

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
        , ],

        threadColors: [{
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

        // ==================== COMPUTED ====================
        get filteredFabrics() {
          if (!this.fabricSearch) return this.fabrics;
          const search = this.fabricSearch.toLowerCase();
          return this.fabrics.filter(f =>
            f.name?.toLowerCase().includes(search) ||
            f.material?.toLowerCase().includes(search) ||
            f.code?.toLowerCase().includes(search)
          );
        },

        get totalFabricPages() {
          return Math.ceil(this.filteredFabrics.length / this.fabricsPerPage);
        },

        get paginatedFabrics() {
          const start = (this.fabricPage - 1) * this.fabricsPerPage;
          const end = start + this.fabricsPerPage;
          return this.filteredFabrics.slice(start, end);
        },

        get styleCategories() {
          return this.categories.filter(c =>
            c.display_type !== 'fabric' && c.display_type !== 'contrast'
          );
        },

        get contrastFabrics() {
          return this.fabrics.slice(0, 8);
        },

        // ==================== LIFECYCLE ====================
        init() {
          // Watch for search changes to reset page
          this.$watch('fabricSearch', () => {
            this.fabricPage = 1;
          });

          if (initialData?.productType) {
            this.selectedProductType = initialData.productType;
          } else if (this.productTypes.length > 0) {
            this.selectedProductType = this.productTypes[0];
          }

          if (this.styleCategories.length > 0) {
            this.activeStyleCategory = this.styleCategories[0].code;
          }

          this.selectedButtonColor = this.buttonColors[0];
          this.selectedThreadColor = this.threadColors[0];
          this.calculatePrice();

          this.$nextTick(() => this.initThreeJS());
        },

        // ==================== METHODS ====================
        selectProductType(productType) {
          this.selectedProductType = productType;
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
        },

        selectOption(categoryCode, option) {
          this.currentConfig[categoryCode] = option.code;
          this.updateModelPart(categoryCode, option);
          this.calculatePrice();
        },

        scrollToCategory(categoryCode) {
          this.activeStyleCategory = categoryCode;
          const el = document.getElementById('category-' + categoryCode);
          if (el) el.scrollIntoView({
            behavior: 'smooth'
            , block: 'start'
          });
        },

        calculatePrice() {
          let price = this.selectedFabric?.price || 0;
          for (const category of this.categories) {
            const selectedCode = this.currentConfig[category.code];
            const option = category.options?.find(o => o.code === selectedCode);
            if (option?.price_modifier) {
              price += parseFloat(option.price_modifier);
            }
          }
          this.totalPrice = price.toFixed(0);
        },

        // ==================== THREE.JS ====================
        async initThreeJS() {
          const container = this.$refs.threeContainer;
          if (!container) return;

          try {
            this.loadingMessage = 'Loading 3D engine...';

            // Import Three.js modules and store in external context (NOT Alpine reactive)
            const THREE = await import('https://esm.sh/three@0.160.0');
            const {
              OrbitControls
            } = await import('https://esm.sh/three@0.160.0/examples/jsm/controls/OrbitControls.js');
            const {
              GLTFLoader
            } = await import('https://esm.sh/three@0.160.0/examples/jsm/loaders/GLTFLoader.js');

            // Store in external threeContext to avoid Alpine proxy conflicts
            window.threeContext.THREE = THREE;
            window.threeContext.GLTFLoader = GLTFLoader;

            // Scene
            window.threeContext.scene = new THREE.Scene();
            window.threeContext.scene.background = new THREE.Color(0xf5f5f5);

            // Camera
            const aspect = container.clientWidth / container.clientHeight;
            window.threeContext.camera = new THREE.PerspectiveCamera(45, aspect, 0.1, 1000);
            window.threeContext.camera.position.set(0, 1.2, 3);

            // Renderer
            window.threeContext.renderer = new THREE.WebGLRenderer({
              antialias: true
              , preserveDrawingBuffer: true
            });
            window.threeContext.renderer.setSize(container.clientWidth, container.clientHeight);
            window.threeContext.renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
            window.threeContext.renderer.shadowMap.enabled = true;
            window.threeContext.renderer.shadowMap.type = THREE.PCFSoftShadowMap;
            window.threeContext.renderer.outputColorSpace = THREE.SRGBColorSpace;
            container.appendChild(window.threeContext.renderer.domElement);

            // Lights
            window.threeContext.scene.add(new THREE.AmbientLight(0xffffff, 0.7));
            const mainLight = new THREE.DirectionalLight(0xffffff, 1.0);
            mainLight.position.set(5, 10, 7);
            mainLight.castShadow = true;
            window.threeContext.scene.add(mainLight);
            const fillLight = new THREE.DirectionalLight(0xffffff, 0.5);
            fillLight.position.set(-5, 5, -5);
            window.threeContext.scene.add(fillLight);

            // Controls
            window.threeContext.controls = new OrbitControls(window.threeContext.camera, window.threeContext.renderer.domElement);
            window.threeContext.controls.enableDamping = true;
            window.threeContext.controls.dampingFactor = 0.05;
            window.threeContext.controls.target.set(0, 0.8, 0);
            window.threeContext.controls.minDistance = 1.5;
            window.threeContext.controls.maxDistance = 8;
            window.threeContext.controls.update();

            // Animation loop - use direct references, no Alpine proxies
            const animate = () => {
              requestAnimationFrame(animate);
              if (window.threeContext.controls) window.threeContext.controls.update();
              if (window.threeContext.renderer && window.threeContext.scene && window.threeContext.camera) {
                window.threeContext.renderer.render(window.threeContext.scene, window.threeContext.camera);
              }
            };
            animate();

            // Resize handler
            new ResizeObserver(() => {
              if (!container.clientWidth || !container.clientHeight) return;
              window.threeContext.camera.aspect = container.clientWidth / container.clientHeight;
              window.threeContext.camera.updateProjectionMatrix();
              window.threeContext.renderer.setSize(container.clientWidth, container.clientHeight);
            }).observe(container);

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
          if (!window.threeContext.THREE) return;
          this.loadingMessage = 'Loading 3D model...';

          const productCode = this.selectedProductType?.code || 'jacket';
          const modelUrl = `/storage/3d-models/${productCode}/demo.glb`;

          try {
            const loader = new window.threeContext.GLTFLoader();
            loader.load(
              modelUrl
              , (gltf) => {
                if (window.threeContext.model) window.threeContext.scene.remove(window.threeContext.model);
                window.threeContext.model = gltf.scene;
                this.centerModel();
                window.threeContext.model.traverse((child) => {
                  if (child.isMesh) {
                    child.castShadow = true;
                    child.receiveShadow = true;
                    child.userData.originalMaterial = child.material.clone();
                  }
                });
                window.threeContext.scene.add(window.threeContext.model);
                this.isLoading = false;
              }
              , (progress) => {
                if (progress.total > 0) {
                  const percent = Math.round((progress.loaded / progress.total) * 100);
                  this.loadingMessage = `Loading model... ${percent}%`;
                }
              }
              , () => {
                console.warn('Model not found, creating placeholder');
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
          const THREE = window.threeContext.THREE;
          const group = new THREE.Group();
          const suitMaterial = new THREE.MeshStandardMaterial({
            color: 0x3b82f6
            , roughness: 0.7
          });
          const pantsMaterial = new THREE.MeshStandardMaterial({
            color: 0x1e3a5f
            , roughness: 0.8
          });

          // Torso
          const torso = new THREE.Mesh(new THREE.CylinderGeometry(0.3, 0.25, 0.8, 32), suitMaterial);
          torso.position.y = 1.2;
          group.add(torso);

          // Shoulders
          const shoulderGeom = new THREE.CapsuleGeometry(0.08, 0.5, 8, 16);
          const leftShoulder = new THREE.Mesh(shoulderGeom, suitMaterial);
          leftShoulder.rotation.z = Math.PI / 2;
          leftShoulder.position.set(-0.45, 1.5, 0);
          group.add(leftShoulder);
          const rightShoulder = new THREE.Mesh(shoulderGeom, suitMaterial);
          rightShoulder.rotation.z = Math.PI / 2;
          rightShoulder.position.set(0.45, 1.5, 0);
          group.add(rightShoulder);

          // Arms
          const armGeom = new THREE.CylinderGeometry(0.06, 0.05, 0.6, 16);
          const leftArm = new THREE.Mesh(armGeom, suitMaterial);
          leftArm.position.set(-0.45, 1.1, 0);
          group.add(leftArm);
          const rightArm = new THREE.Mesh(armGeom, suitMaterial);
          rightArm.position.set(0.45, 1.1, 0);
          group.add(rightArm);

          // Pants
          const pants = new THREE.Mesh(new THREE.CylinderGeometry(0.25, 0.15, 0.6, 32), pantsMaterial);
          pants.position.y = 0.5;
          group.add(pants);

          // Legs
          const legGeom = new THREE.CylinderGeometry(0.08, 0.06, 0.5, 16);
          const leftLeg = new THREE.Mesh(legGeom, pantsMaterial);
          leftLeg.position.set(-0.12, 0, 0);
          group.add(leftLeg);
          const rightLeg = new THREE.Mesh(legGeom, pantsMaterial);
          rightLeg.position.set(0.12, 0, 0);
          group.add(rightLeg);

          if (window.threeContext.model) window.threeContext.scene.remove(window.threeContext.model);
          window.threeContext.model = group;
          window.threeContext.scene.add(group);

          group.traverse((child) => {
            if (child.isMesh) {
              child.castShadow = true;
              child.receiveShadow = true;
              child.userData.originalMaterial = child.material.clone();
            }
          });
        },

        centerModel() {
          if (!window.threeContext.model || !window.threeContext.THREE) return;
          const THREE = window.threeContext.THREE;
          const box = new THREE.Box3().setFromObject(window.threeContext.model);
          const center = box.getCenter(new THREE.Vector3());
          const size = box.getSize(new THREE.Vector3());

          // Center model at origin
          window.threeContext.model.position.x = -center.x;
          window.threeContext.model.position.y = -box.min.y;
          window.threeContext.model.position.z = -center.z;

          // Scale model to fit nicely
          const maxDim = Math.max(size.x, size.y, size.z);
          if (maxDim > 2.5) window.threeContext.model.scale.setScalar(2.5 / maxDim);

          // Update camera target to model center
          const newBox = new THREE.Box3().setFromObject(window.threeContext.model);
          const newCenter = newBox.getCenter(new THREE.Vector3());
          window.threeContext.controls.target.set(newCenter.x, newCenter.y, newCenter.z);
          window.threeContext.camera.position.set(newCenter.x, newCenter.y + 0.5, newCenter.z + 3);
          window.threeContext.controls.update();
        },

        applyFabricTexture(fabric) {
          if (!window.threeContext.model || !window.threeContext.THREE) return;
          const THREE = window.threeContext.THREE;
          const textureUrl = fabric.texture_url || fabric.thumbnail_url;

          if (textureUrl) {
            new THREE.TextureLoader().load(
              textureUrl
              , (texture) => {
                texture.wrapS = texture.wrapT = THREE.RepeatWrapping;
                texture.repeat.set(8, 8);
                texture.colorSpace = THREE.SRGBColorSpace;
                window.threeContext.model.traverse((child) => {
                  if (child.isMesh) {
                    child.material.color = new THREE.Color(0xffffff);
                    child.material.map = texture;
                    child.material.needsUpdate = true;
                  }
                });
              }
              , undefined
              , () => this.applyFabricColor(fabric.color_hex || '#3b82f6')
            );
          } else if (fabric.color_hex) {
            this.applyFabricColor(fabric.color_hex);
          }
        },

        applyFabricColor(colorHex) {
          if (!window.threeContext.model || !window.threeContext.THREE) return;
          const color = new window.threeContext.THREE.Color(colorHex);
          window.threeContext.model.traverse((child) => {
            if (child.isMesh) {
              child.material.map = null;
              child.material.color = color;
              child.material.needsUpdate = true;
            }
          });
        },

        updateModelPart(categoryCode, option) {
          console.log('Update model part:', categoryCode, option.code);
        },

        // ==================== VIEW CONTROLS ====================
        setView(view) {
          if (!window.threeContext.camera || !window.threeContext.controls) return;
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
          window.threeContext.camera.position.set(pos.x, pos.y, pos.z);
          window.threeContext.controls.target.set(0, 0.8, 0);
          window.threeContext.controls.update();
        },

        zoomIn() {
          if (window.threeContext.camera) window.threeContext.camera.position.multiplyScalar(0.9);
        },

        zoomOut() {
          if (window.threeContext.camera) window.threeContext.camera.position.multiplyScalar(1.1);
        },

        toggleWireframe() {
          if (!window.threeContext.model) return;
          this.wireframe = !this.wireframe;
          window.threeContext.model.traverse((child) => {
            if (child.isMesh) child.material.wireframe = this.wireframe;
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
          if (!window.threeContext.renderer) return;
          window.threeContext.renderer.render(window.threeContext.scene, window.threeContext.camera);
          const link = document.createElement('a');
          link.download = `suit-config-${Date.now()}.png`;
          link.href = window.threeContext.renderer.domElement.toDataURL('image/png');
          link.click();
        },

        async saveConfiguration() {
          try {
            const config = {
              product_type: this.selectedProductType?.code
              , fabric_id: this.selectedFabric?.id
              , config: this.currentConfig
              , lining_id: this.selectedLining?.id
              , button_color: this.selectedButtonColor?.code
              , thread_color: this.selectedThreadColor?.code
            , };
            console.log('Save configuration:', config);

            if (window.$wire) {
              window.$wire.dispatch('notify', {
                type: 'success'
                , message: 'Configuration saved!'
              });
            } else {
              alert('Configuration saved!');
            }
          } catch (error) {
            console.error('Failed to save:', error);
            alert('Failed to save configuration');
          }
        }
      };
    }

    // Register Alpine component immediately (Alpine is already loaded by Filament)
    document.addEventListener('DOMContentLoaded', () => {
      if (window.Alpine) {
        Alpine.data('threeDConfiguratorAdmin', createThreeDConfiguratorAdmin);
      }
    });

    // Also try alpine:init in case it hasn't fired yet
    document.addEventListener('alpine:init', () => {
      Alpine.data('threeDConfiguratorAdmin', createThreeDConfiguratorAdmin);
    });

    // Fallback: register immediately if Alpine already exists
    if (window.Alpine) {
      Alpine.data('threeDConfiguratorAdmin', createThreeDConfiguratorAdmin);
    }

  </script>
</x-filament-panels::page>
