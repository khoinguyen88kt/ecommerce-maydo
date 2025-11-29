@props(['record' => null, 'modelUrl' => null, 'modelName' => 'Model 3D'])

@php
$fabrics = \App\Models\Fabric::all();

// Get model URL from record or direct prop
if ($record && $record->glb_file) {
// Keep full path, not just basename
$modelUrl = '/storage/glb/' . $record->glb_file;
$modelName = $record->suitModel?->name_vi ?? $record->suitModel?->name ?? 'Model 3D';
}
@endphp

<div x-data="{
    loading: true,
    error: null,
    wireframe: false,
    selectedFabric: null,
    scene: null,
    model: null,

    init() {
      this.$nextTick(() => {
        this.initThreeJS();
      });
    },

    async initThreeJS() {
      const container = this.$refs.container;
      if (!container) return;

      try {
        // Import Three.js using esm.sh (properly bundles dependencies)
        const THREE = await import('https://esm.sh/three@0.160.0');
        const { OrbitControls } = await import('https://esm.sh/three@0.160.0/examples/jsm/controls/OrbitControls.js');
        const { GLTFLoader } = await import('https://esm.sh/three@0.160.0/examples/jsm/loaders/GLTFLoader.js');

        // Setup scene
        const scene = new THREE.Scene();
        scene.background = new THREE.Color(0xf5f5f5);
        this.scene = scene;

        // Setup camera
        const camera = new THREE.PerspectiveCamera(45, container.clientWidth / container.clientHeight, 0.1, 1000);
        camera.position.set(0, 1.2, 3);

        // Setup renderer
        const renderer = new THREE.WebGLRenderer({ antialias: true });
        renderer.setSize(container.clientWidth, container.clientHeight);
        renderer.setPixelRatio(window.devicePixelRatio);
        renderer.shadowMap.enabled = true;
        renderer.shadowMap.type = THREE.PCFSoftShadowMap;
        container.appendChild(renderer.domElement);

        // Add lights
        const ambientLight = new THREE.AmbientLight(0xffffff, 0.6);
        scene.add(ambientLight);

        const directionalLight = new THREE.DirectionalLight(0xffffff, 1.0);
        directionalLight.position.set(5, 10, 7);
        directionalLight.castShadow = true;
        scene.add(directionalLight);

        const backLight = new THREE.DirectionalLight(0xffffff, 0.5);
        backLight.position.set(-5, 5, -5);
        scene.add(backLight);

        // Setup controls
        const controls = new OrbitControls(camera, renderer.domElement);
        controls.enableDamping = true;
        controls.dampingFactor = 0.05;
        controls.target.set(0, 0.8, 0);
        controls.update();

        // Load model
        const loader = new GLTFLoader();
        const modelUrl = '{{ $modelUrl }}';

        loader.load(
          modelUrl,
          (gltf) => {
            const model = gltf.scene;
            this.model = model;

            // Calculate bounding box
            const box = new THREE.Box3().setFromObject(model);
            const center = box.getCenter(new THREE.Vector3());
            const size = box.getSize(new THREE.Vector3());

            // Center model exactly in the middle of canvas
            model.position.x = -center.x;
            model.position.y = -center.y;
            model.position.z = -center.z;

            // Adjust camera to look at center
            const maxDim = Math.max(size.x, size.y, size.z);
            camera.position.set(0, 0, maxDim * 2);
            controls.target.set(0, 0, 0);
            controls.update();

            // Enable shadows
            model.traverse((child) => {
              if (child.isMesh) {
                child.castShadow = true;
                child.receiveShadow = true;
                // Store original material for reset
                child.userData.originalMaterial = child.material.clone();
              }
            });

            scene.add(model);
            this.loading = false;

            // Store meshes for fabric application
            this.meshes = [];
            model.traverse((child) => {
              if (child.isMesh) {
                this.meshes.push(child);
              }
            });
          },
          (progress) => {
            console.log('Loading:', (progress.loaded / progress.total * 100).toFixed(1) + '%');
          },
          (error) => {
            console.error('Error loading model:', error);
            this.error = 'Không thể tải model 3D. Vui lòng kiểm tra file.';
            this.loading = false;
          }
        );

        // Animation loop
        const animate = () => {
          requestAnimationFrame(animate);
          controls.update();
          renderer.render(scene, camera);
        };
        animate();

        // Handle resize
        const resizeObserver = new ResizeObserver(() => {
          camera.aspect = container.clientWidth / container.clientHeight;
          camera.updateProjectionMatrix();
          renderer.setSize(container.clientWidth, container.clientHeight);
        });
        resizeObserver.observe(container);

        // Store reference for wireframe toggle
        this.THREE = THREE;

      } catch (err) {
        console.error('Three.js init error:', err);
        this.error = 'Lỗi khởi tạo trình xem 3D';
        this.loading = false;
      }
    },

    toggleWireframe() {
      if (!this.model || !this.THREE) return;
      this.wireframe = !this.wireframe;

      this.model.traverse((child) => {
        if (child.isMesh) {
          child.material.wireframe = this.wireframe;
        }
      });
    },

    applyFabric(fabric) {
      if (!this.model || !this.THREE) return;
      this.selectedFabric = fabric;

      const THREE = this.THREE;

      // Use texture_url or thumbnail_url as texture source
      const textureUrl = fabric.texture_url || fabric.thumbnail_url;

      if (textureUrl) {
        const textureLoader = new THREE.TextureLoader();
        textureLoader.crossOrigin = 'anonymous'; // Enable CORS for external images
        textureLoader.load(
          textureUrl,
          (texture) => {
            // Configure texture for tiling - higher repeat for finer texture
            texture.wrapS = THREE.RepeatWrapping;
            texture.wrapT = THREE.RepeatWrapping;
            texture.repeat.set(6, 6); // Higher repeat for finer, more realistic appearance
            texture.colorSpace = THREE.SRGBColorSpace; // Proper color space

            // Apply texture to all meshes
            this.model.traverse((child) => {
              if (child.isMesh) {
                const applyMaterial = (mat) => {
                  // Set color to white so texture displays properly
                  mat.color = new THREE.Color(0xffffff);
                  mat.map = texture;
                  // Material properties for realistic fabric look
                  mat.metalness = 0.0;  // Fabric is not metallic
                  mat.roughness = 0.8;  // Fabric is quite rough/matte
                  mat.needsUpdate = true;
                };

                if (Array.isArray(child.material)) {
                  child.material.forEach(applyMaterial);
                } else {
                  applyMaterial(child.material);
                }
              }
            });
          },
          undefined,
          (error) => {
            console.error('Error loading texture:', error);
            // Fallback to color if texture fails
            this.applyFabricColor(fabric.color_hex);
          }
        );
      } else if (fabric.color_hex) {
        // Fallback to color if no texture
        this.applyFabricColor(fabric.color_hex);
      }
    },

    applyFabricColor(colorHex) {
      if (!this.model || !this.THREE) return;

      const THREE = this.THREE;
      const color = new THREE.Color(colorHex);

      this.model.traverse((child) => {
        if (child.isMesh) {
          if (Array.isArray(child.material)) {
            child.material.forEach(mat => {
              mat.map = null;
              mat.color = color;
              mat.needsUpdate = true;
            });
          } else {
            child.material.map = null;
            child.material.color = color;
            child.material.needsUpdate = true;
          }
        }
      });
    },

    resetMaterial() {
      if (!this.model) return;
      this.selectedFabric = null;

      this.model.traverse((child) => {
        if (child.isMesh && child.userData.originalMaterial) {
          // Dispose current texture if any
          if (child.material.map) {
            child.material.map.dispose();
          }
          child.material.copy(child.userData.originalMaterial);
          child.material.needsUpdate = true;
        }
      });
    },

    toggleFullscreen() {
      const container = this.$refs.container;
      if (!document.fullscreenElement) {
        container.requestFullscreen();
      } else {
        document.exitFullscreen();
      }
    }
  }" class="relative">
  {{-- Viewer Header --}}
  <div class="flex items-center justify-between mb-4">
    <h3 class="text-lg font-medium text-gray-900 dark:text-white">
      {{ $modelName }}
    </h3>
    <div class="flex items-center gap-2">
      <button type="button" @click="toggleWireframe()" class="px-3 py-2 text-sm font-medium rounded-lg" :class="wireframe ? 'bg-primary-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300'">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
        </svg>
      </button>
      <button type="button" @click="toggleFullscreen()" class="px-3 py-2 text-sm font-medium bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
        </svg>
      </button>
    </div>
  </div>

  <div class="flex gap-4">
    {{-- 3D Viewer Container --}}
    <div x-ref="container" class="flex-1 bg-gray-100 dark:bg-gray-800 rounded-xl overflow-hidden" style="height: 600px; min-height: 400px;">
      {{-- Loading Spinner --}}
      <div x-show="loading" class="absolute inset-0 flex items-center justify-center bg-gray-100 dark:bg-gray-800">
        <div class="text-center">
          <svg class="animate-spin h-12 w-12 text-primary-500 mx-auto mb-4" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          <p class="text-gray-600 dark:text-gray-400">Đang tải model 3D...</p>
        </div>
      </div>

      {{-- Error Message --}}
      <div x-show="error" class="absolute inset-0 flex items-center justify-center bg-gray-100 dark:bg-gray-800">
        <div class="text-center text-red-500">
          <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
          </svg>
          <p x-text="error"></p>
        </div>
      </div>
    </div>

    {{-- Fabric Selection Panel --}}
    <div class="bg-white dark:bg-gray-900 rounded-xl p-3 border border-gray-200 dark:border-gray-700 flex-shrink-0" style="width: 280px;">
      <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-3">
        Chọn vải
      </h4>

      {{-- Selected Fabric Info --}}
      <div x-show="selectedFabric" class="mb-3 p-2 bg-primary-50 dark:bg-primary-900/20 rounded-lg">
        <p class="text-xs text-gray-600 dark:text-gray-400">Đang áp dụng:</p>
        <p class="text-sm font-medium text-gray-900 dark:text-white" x-text="selectedFabric?.name"></p>
        <button type="button" @click="resetMaterial()" class="mt-1 text-xs text-primary-600 hover:text-primary-800">
          ← Reset
        </button>
      </div>

      {{-- Fabric Grid - 3 columns with inline styles --}}
      <div class="overflow-y-auto" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px; max-height: 450px;">
        @foreach($fabrics as $fabric)
        <button type="button" @click="applyFabric({{ json_encode(['id' => $fabric->id, 'name' => $fabric->name, 'color_hex' => $fabric->color_hex, 'texture_url' => $fabric->texture_url, 'thumbnail_url' => $fabric->thumbnail_url]) }})" class="group relative rounded-lg overflow-hidden border-2 transition-all" style="aspect-ratio: 1; width: 100%;" :class="selectedFabric?.id === {{ $fabric->id }} ? 'border-primary-500 ring-2 ring-primary-500' : 'border-gray-300 dark:border-gray-600 hover:border-primary-400'" title="{{ $fabric->name }}">
          @if($fabric->thumbnail_url)
          <img src="{{ $fabric->thumbnail_url }}" alt="{{ $fabric->name }}" class="w-full h-full object-cover" />
          @else
          <div class="w-full h-full" style="background-color: {{ $fabric->color_hex ?? '#cccccc' }}"></div>
          @endif
          {{-- Fabric name tooltip on hover --}}
          <div class="absolute inset-x-0 bottom-0 bg-black/60 text-white text-center opacity-0 group-hover:opacity-100 transition-opacity truncate" style="font-size: 9px; padding: 2px;">
            {{ $fabric->name }}
          </div>
        </button>
        @endforeach
      </div>

      @if($fabrics->isEmpty())
      <p class="text-sm text-gray-500 text-center py-8">
        Chưa có vải nào trong hệ thống
      </p>
      @endif
    </div>
  </div>

  {{-- Instructions --}}
  <div class="mt-4 text-sm text-gray-500 dark:text-gray-400">
    <p>💡 Kéo chuột để xoay | Cuộn để zoom | Click phải để di chuyển | Chọn vải để áp dụng lên model</p>
  </div>
</div>
