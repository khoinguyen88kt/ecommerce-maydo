<div class="rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-800 overflow-hidden">
  <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
    <h3 class="text-lg font-medium text-gray-900 dark:text-white flex items-center gap-2">
      <x-heroicon-o-cube class="w-5 h-5" />
      3D Model Viewer
    </h3>
    <div class="flex items-center gap-2">
      <button type="button" id="reset-camera" class="px-3 py-1 text-sm bg-gray-200 dark:bg-gray-700 rounded hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300">
        Reset View
      </button>
      <button type="button" id="toggle-wireframe" class="px-3 py-1 text-sm bg-gray-200 dark:bg-gray-700 rounded hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300">
        Wireframe
      </button>
    </div>
  </div>

  @if($this->getGlbUrl())
  <div id="three-container" class="w-full h-[500px] relative">
    <div id="loading-overlay" class="absolute inset-0 flex items-center justify-center bg-gray-900/50 z-10">
      <div class="text-center text-white">
        <svg class="animate-spin h-10 w-10 mx-auto mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <p>Loading 3D Model...</p>
      </div>
    </div>
  </div>

  <div id="model-info" class="p-4 border-t border-gray-200 dark:border-gray-700 text-sm text-gray-600 dark:text-gray-400">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
      <div>
        <span class="font-medium">Meshes:</span>
        <span id="mesh-count">-</span>
      </div>
      <div>
        <span class="font-medium">Vertices:</span>
        <span id="vertex-count">-</span>
      </div>
      <div>
        <span class="font-medium">Triangles:</span>
        <span id="triangle-count">-</span>
      </div>
      <div>
        <span class="font-medium">Parts:</span>
        <span id="parts-list">-</span>
      </div>
    </div>
  </div>

  @push('scripts')
  <script type="importmap">
    {
      "imports": {
        "three": "https://cdn.jsdelivr.net/npm/three@0.160.0/build/three.module.js",
        "three/addons/": "https://cdn.jsdelivr.net/npm/three@0.160.0/examples/jsm/"
      }
    }
    </script>
  <script type="module">
    import * as THREE from 'three';
      import { OrbitControls } from 'three/addons/controls/OrbitControls.js';
      import { GLTFLoader } from 'three/addons/loaders/GLTFLoader.js';

      const container = document.getElementById('three-container');
      const loadingOverlay = document.getElementById('loading-overlay');

      // Scene setup
      const scene = new THREE.Scene();
      scene.background = new THREE.Color(0x1a1a2e);

      // Camera
      const camera = new THREE.PerspectiveCamera(
        45,
        container.clientWidth / container.clientHeight,
        0.1,
        1000
      );
      camera.position.set(3, 2, 3);

      // Renderer
      const renderer = new THREE.WebGLRenderer({ antialias: true });
      renderer.setSize(container.clientWidth, container.clientHeight);
      renderer.setPixelRatio(window.devicePixelRatio);
      renderer.shadowMap.enabled = true;
      renderer.shadowMap.type = THREE.PCFSoftShadowMap;
      renderer.toneMapping = THREE.ACESFilmicToneMapping;
      renderer.toneMappingExposure = 1;
      container.appendChild(renderer.domElement);

      // Controls
      const controls = new OrbitControls(camera, renderer.domElement);
      controls.enableDamping = true;
      controls.dampingFactor = 0.05;
      controls.screenSpacePanning = false;
      controls.minDistance = 1;
      controls.maxDistance = 20;

      // Lights
      const ambientLight = new THREE.AmbientLight(0xffffff, 0.5);
      scene.add(ambientLight);

      const directionalLight = new THREE.DirectionalLight(0xffffff, 1);
      directionalLight.position.set(5, 10, 7.5);
      directionalLight.castShadow = true;
      scene.add(directionalLight);

      const fillLight = new THREE.DirectionalLight(0xffffff, 0.3);
      fillLight.position.set(-5, 0, -5);
      scene.add(fillLight);

      // Grid helper
      const gridHelper = new THREE.GridHelper(10, 10, 0x444444, 0x222222);
      scene.add(gridHelper);

      // Load GLB model
      const loader = new GLTFLoader();
      let model = null;
      let isWireframe = false;

      loader.load(
        '{{ $this->getGlbUrl() }}',
        function (gltf) {
          model = gltf.scene;

          // Center and scale model
          const box = new THREE.Box3().setFromObject(model);
          const center = box.getCenter(new THREE.Vector3());
          const size = box.getSize(new THREE.Vector3());
          const maxDim = Math.max(size.x, size.y, size.z);
          const scale = 2 / maxDim;

          model.scale.multiplyScalar(scale);
          model.position.sub(center.multiplyScalar(scale));
          model.position.y += size.y * scale / 2;

          // Enable shadows
          model.traverse((child) => {
            if (child.isMesh) {
              child.castShadow = true;
              child.receiveShadow = true;
            }
          });

          scene.add(model);

          // Update model info
          let meshCount = 0;
          let vertexCount = 0;
          let triangleCount = 0;
          const partNames = [];

          model.traverse((child) => {
            if (child.isMesh) {
              meshCount++;
              partNames.push(child.name || 'unnamed');
              if (child.geometry) {
                const geo = child.geometry;
                vertexCount += geo.attributes.position ? geo.attributes.position.count : 0;
                triangleCount += geo.index ? geo.index.count / 3 : (geo.attributes.position ? geo.attributes.position.count / 3 : 0);
              }
            }
          });

          document.getElementById('mesh-count').textContent = meshCount;
          document.getElementById('vertex-count').textContent = vertexCount.toLocaleString();
          document.getElementById('triangle-count').textContent = Math.floor(triangleCount).toLocaleString();
          document.getElementById('parts-list').textContent = partNames.slice(0, 5).join(', ') + (partNames.length > 5 ? '...' : '');

          // Hide loading
          loadingOverlay.style.display = 'none';
        },
        function (xhr) {
          const percent = (xhr.loaded / xhr.total * 100).toFixed(0);
          loadingOverlay.querySelector('p').textContent = `Loading... ${percent}%`;
        },
        function (error) {
          console.error('Error loading model:', error);
          loadingOverlay.innerHTML = `
            <div class="text-center text-red-400">
              <svg class="h-10 w-10 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
              </svg>
              <p>Failed to load model</p>
              <p class="text-sm mt-1">${error.message || 'Unknown error'}</p>
            </div>
          `;
        }
      );

      // Reset camera button
      document.getElementById('reset-camera').addEventListener('click', () => {
        camera.position.set(3, 2, 3);
        camera.lookAt(0, 0, 0);
        controls.reset();
      });

      // Toggle wireframe button
      document.getElementById('toggle-wireframe').addEventListener('click', () => {
        isWireframe = !isWireframe;
        if (model) {
          model.traverse((child) => {
            if (child.isMesh && child.material) {
              if (Array.isArray(child.material)) {
                child.material.forEach(m => m.wireframe = isWireframe);
              } else {
                child.material.wireframe = isWireframe;
              }
            }
          });
        }
      });

      // Animation loop
      function animate() {
        requestAnimationFrame(animate);
        controls.update();
        renderer.render(scene, camera);
      }
      animate();

      // Handle resize
      const resizeObserver = new ResizeObserver(() => {
        camera.aspect = container.clientWidth / container.clientHeight;
        camera.updateProjectionMatrix();
        renderer.setSize(container.clientWidth, container.clientHeight);
      });
      resizeObserver.observe(container);
    </script>
  @endpush
  @else
  <div class="h-[300px] flex items-center justify-center text-gray-500 dark:text-gray-400">
    <div class="text-center">
      <x-heroicon-o-cube class="w-16 h-16 mx-auto mb-2 opacity-50" />
      <p>No GLB file uploaded</p>
    </div>
  </div>
  @endif
</div>
