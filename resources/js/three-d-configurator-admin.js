/**
 * 3D Configurator Admin - Alpine.js Component
 *
 * This component handles the 3D suit/garment configurator in the Filament admin panel.
 * It uses Three.js for 3D rendering and Alpine.js for state management.
 */

// Store module imports
let THREE = null;
let OrbitControls = null;
let GLTFLoader = null;

/**
 * Initialize Three.js modules asynchronously
 */
async function loadThreeModules() {
    if (THREE) return { THREE, OrbitControls, GLTFLoader };

    THREE = await import("https://esm.sh/three@0.160.0");
    const controlsModule = await import(
        "https://esm.sh/three@0.160.0/examples/jsm/controls/OrbitControls.js"
    );
    const loaderModule = await import(
        "https://esm.sh/three@0.160.0/examples/jsm/loaders/GLTFLoader.js"
    );

    OrbitControls = controlsModule.OrbitControls;
    GLTFLoader = loaderModule.GLTFLoader;

    return { THREE, OrbitControls, GLTFLoader };
}

/**
 * Create the Alpine.js component for 3D Configurator
 */
function createThreeDConfiguratorAdmin(initialData) {
    return {
        // ==================== STATE ====================

        // Product & Categories
        productTypes: initialData?.productTypes || [],
        selectedProductType: null,
        categories: initialData?.categories || [],
        fabrics: initialData?.fabrics || [],
        currentConfig: initialData?.currentConfig || {},

        // UI State
        activeTab: "fabric",
        activeStyleCategory: null,
        fabricSearch: "",
        isLoading: true,
        loadingMessage: "Initializing 3D viewer...",
        error: null,
        wireframe: false,

        // Selections
        selectedFabric: null,
        selectedLining: null,
        selectedButtonColor: null,
        selectedThreadColor: null,

        // Price
        totalPrice: 0,

        // Contrast Options
        buttonColors: [
            { code: "black", name: "Black", hex: "#1a1a1a" },
            { code: "brown", name: "Brown", hex: "#8B4513" },
            { code: "navy", name: "Navy", hex: "#000080" },
            { code: "silver", name: "Silver", hex: "#C0C0C0" },
            { code: "gold", name: "Gold", hex: "#FFD700" },
        ],

        threadColors: [
            { code: "matching", name: "Matching", hex: "#808080" },
            { code: "white", name: "White", hex: "#FFFFFF" },
            { code: "black", name: "Black", hex: "#1a1a1a" },
            { code: "red", name: "Red", hex: "#DC143C" },
            { code: "blue", name: "Blue", hex: "#0000CD" },
        ],

        // Three.js Objects (will be initialized later)
        _three: null,
        _scene: null,
        _camera: null,
        _renderer: null,
        _controls: null,
        _model: null,
        _resizeObserver: null,

        // ==================== COMPUTED PROPERTIES ====================

        get filteredFabrics() {
            if (!this.fabricSearch) return this.fabrics;
            const search = this.fabricSearch.toLowerCase();
            return this.fabrics.filter(
                (f) =>
                    f.name?.toLowerCase().includes(search) ||
                    f.material?.toLowerCase().includes(search) ||
                    f.code?.toLowerCase().includes(search)
            );
        },

        get styleCategories() {
            return this.categories.filter(
                (c) =>
                    c.display_type !== "fabric" && c.display_type !== "contrast"
            );
        },

        get contrastFabrics() {
            return this.fabrics.slice(0, 8);
        },

        // ==================== LIFECYCLE ====================

        init() {
            // Set initial product type
            if (initialData?.productType) {
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

            // Initialize Three.js on next tick
            this.$nextTick(() => {
                this.initThreeJS();
            });
        },

        destroy() {
            // Cleanup Three.js resources
            if (this._resizeObserver) {
                this._resizeObserver.disconnect();
            }
            if (this._renderer) {
                this._renderer.dispose();
            }
            if (this._controls) {
                this._controls.dispose();
            }
        },

        // ==================== PRODUCT METHODS ====================

        selectProductType(productType) {
            this.selectedProductType = productType;
            this.loadProductConfiguration();
        },

        async loadProductConfiguration() {
            try {
                this.isLoading = true;
                this.loadingMessage = "Loading configuration...";

                const response = await fetch(
                    `/api/3d-configurator/${this.selectedProductType.code}`
                );
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
                console.error("Failed to load configuration:", error);
                this.error = "Failed to load configuration";
            } finally {
                this.isLoading = false;
            }
        },

        // ==================== SELECTION METHODS ====================

        selectFabric(fabric) {
            this.selectedFabric = fabric;
            this.applyFabricTexture(fabric);
            this.calculatePrice();
        },

        selectLining(fabric) {
            this.selectedLining = fabric;
            // TODO: Apply lining texture to lining meshes
        },

        selectOption(categoryCode, option) {
            this.currentConfig[categoryCode] = option.code;
            this.updateModelPart(categoryCode, option);
            this.calculatePrice();
        },

        scrollToCategory(categoryCode) {
            this.activeStyleCategory = categoryCode;
            const el = document.getElementById("category-" + categoryCode);
            if (el) {
                el.scrollIntoView({ behavior: "smooth", block: "start" });
            }
        },

        // ==================== PRICE CALCULATION ====================

        calculatePrice() {
            let price = this.selectedFabric?.price || 0;

            // Add price modifiers from selected options
            for (const category of this.categories) {
                const selectedCode = this.currentConfig[category.code];
                const option = category.options?.find(
                    (o) => o.code === selectedCode
                );
                if (option?.price_modifier) {
                    price += parseFloat(option.price_modifier);
                }
            }

            this.totalPrice = price.toFixed(0);
        },

        // ==================== THREE.JS INITIALIZATION ====================

        async initThreeJS() {
            const container = this.$refs.threeContainer;
            if (!container) return;

            try {
                this.loadingMessage = "Loading 3D engine...";

                // Load Three.js modules
                const modules = await loadThreeModules();
                this._three = modules.THREE;

                // Setup scene
                this._scene = new this._three.Scene();
                this._scene.background = new this._three.Color(0xf5f5f5);

                // Setup camera
                const aspect = container.clientWidth / container.clientHeight;
                this._camera = new this._three.PerspectiveCamera(
                    45,
                    aspect,
                    0.1,
                    1000
                );
                this._camera.position.set(0, 1.2, 3);

                // Setup renderer
                this._renderer = new this._three.WebGLRenderer({
                    antialias: true,
                    preserveDrawingBuffer: true,
                });
                this._renderer.setSize(
                    container.clientWidth,
                    container.clientHeight
                );
                this._renderer.setPixelRatio(
                    Math.min(window.devicePixelRatio, 2)
                );
                this._renderer.shadowMap.enabled = true;
                this._renderer.shadowMap.type = this._three.PCFSoftShadowMap;
                this._renderer.outputColorSpace = this._three.SRGBColorSpace;
                container.appendChild(this._renderer.domElement);

                // Setup lights
                this.setupLights();

                // Setup controls
                this._controls = new OrbitControls(
                    this._camera,
                    this._renderer.domElement
                );
                this._controls.enableDamping = true;
                this._controls.dampingFactor = 0.05;
                this._controls.target.set(0, 0.8, 0);
                this._controls.minDistance = 1.5;
                this._controls.maxDistance = 8;
                this._controls.update();

                // Start animation loop
                this.animate();

                // Handle resize
                this.setupResizeObserver(container);

                // Load initial model
                await this.loadModel();

                this.error = null;
                this.isLoading = false;
            } catch (err) {
                console.error("Three.js init error:", err);
                this.error = "Failed to initialize 3D viewer: " + err.message;
                this.isLoading = false;
            }
        },

        setupLights() {
            const THREE = this._three;

            const ambientLight = new THREE.AmbientLight(0xffffff, 0.7);
            this._scene.add(ambientLight);

            const mainLight = new THREE.DirectionalLight(0xffffff, 1.0);
            mainLight.position.set(5, 10, 7);
            mainLight.castShadow = true;
            this._scene.add(mainLight);

            const fillLight = new THREE.DirectionalLight(0xffffff, 0.5);
            fillLight.position.set(-5, 5, -5);
            this._scene.add(fillLight);

            const backLight = new THREE.DirectionalLight(0xffffff, 0.3);
            backLight.position.set(0, -5, -10);
            this._scene.add(backLight);
        },

        setupResizeObserver(container) {
            this._resizeObserver = new ResizeObserver(() => {
                if (!container.clientWidth || !container.clientHeight) return;
                this._camera.aspect =
                    container.clientWidth / container.clientHeight;
                this._camera.updateProjectionMatrix();
                this._renderer.setSize(
                    container.clientWidth,
                    container.clientHeight
                );
            });
            this._resizeObserver.observe(container);
        },

        animate() {
            const loop = () => {
                requestAnimationFrame(loop);
                this._controls?.update();
                this._renderer?.render(this._scene, this._camera);
            };
            loop();
        },

        // ==================== MODEL LOADING ====================

        async loadModel() {
            if (!this._three) return;

            this.loadingMessage = "Loading 3D model...";

            // Build model URL based on product type
            const productCode = this.selectedProductType?.code || "jacket";
            const modelUrl = `/storage/3d-models/${productCode}/demo.glb`;

            try {
                const loader = new GLTFLoader();

                loader.load(
                    modelUrl,
                    (gltf) => this.onModelLoaded(gltf),
                    (progress) => this.onModelProgress(progress),
                    (error) => this.onModelError(error)
                );
            } catch (err) {
                console.warn("Error loading model:", err);
                this.createPlaceholderModel();
                this.isLoading = false;
            }
        },

        onModelLoaded(gltf) {
            if (this._model) {
                this._scene.remove(this._model);
            }

            this._model = gltf.scene;
            this.centerModel();

            // Setup shadows
            this._model.traverse((child) => {
                if (child.isMesh) {
                    child.castShadow = true;
                    child.receiveShadow = true;
                    child.userData.originalMaterial = child.material.clone();
                }
            });

            this._scene.add(this._model);
            this.isLoading = false;
        },

        onModelProgress(progress) {
            if (progress.total > 0) {
                const percent = Math.round(
                    (progress.loaded / progress.total) * 100
                );
                this.loadingMessage = `Loading model... ${percent}%`;
            }
        },

        onModelError(error) {
            console.warn("Model not found, creating placeholder:", error);
            this.createPlaceholderModel();
            this.isLoading = false;
        },

        createPlaceholderModel() {
            const THREE = this._three;
            const group = new THREE.Group();

            // Material
            const suitMaterial = new THREE.MeshStandardMaterial({
                color: 0x3b82f6,
                roughness: 0.7,
                metalness: 0.0,
            });

            const pantsMaterial = new THREE.MeshStandardMaterial({
                color: 0x1e3a5f,
                roughness: 0.8,
            });

            // Torso
            const torsoGeometry = new THREE.CylinderGeometry(
                0.3,
                0.25,
                0.8,
                32
            );
            const torso = new THREE.Mesh(torsoGeometry, suitMaterial);
            torso.position.y = 1.2;
            group.add(torso);

            // Shoulders
            const shoulderGeometry = new THREE.CapsuleGeometry(
                0.08,
                0.5,
                8,
                16
            );

            const leftShoulder = new THREE.Mesh(shoulderGeometry, suitMaterial);
            leftShoulder.rotation.z = Math.PI / 2;
            leftShoulder.position.set(-0.45, 1.5, 0);
            group.add(leftShoulder);

            const rightShoulder = new THREE.Mesh(
                shoulderGeometry,
                suitMaterial
            );
            rightShoulder.rotation.z = Math.PI / 2;
            rightShoulder.position.set(0.45, 1.5, 0);
            group.add(rightShoulder);

            // Arms
            const armGeometry = new THREE.CylinderGeometry(0.06, 0.05, 0.6, 16);

            const leftArm = new THREE.Mesh(armGeometry, suitMaterial);
            leftArm.position.set(-0.45, 1.1, 0);
            group.add(leftArm);

            const rightArm = new THREE.Mesh(armGeometry, suitMaterial);
            rightArm.position.set(0.45, 1.1, 0);
            group.add(rightArm);

            // Pants
            const pantsGeometry = new THREE.CylinderGeometry(
                0.25,
                0.15,
                0.6,
                32
            );
            const pants = new THREE.Mesh(pantsGeometry, pantsMaterial);
            pants.position.y = 0.5;
            group.add(pants);

            // Legs
            const legGeometry = new THREE.CylinderGeometry(0.08, 0.06, 0.5, 16);

            const leftLeg = new THREE.Mesh(legGeometry, pantsMaterial);
            leftLeg.position.set(-0.12, 0, 0);
            group.add(leftLeg);

            const rightLeg = new THREE.Mesh(legGeometry, pantsMaterial);
            rightLeg.position.set(0.12, 0, 0);
            group.add(rightLeg);

            // Replace current model
            if (this._model) {
                this._scene.remove(this._model);
            }
            this._model = group;
            this._scene.add(group);

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
            if (!this._model || !this._three) return;

            const box = new this._three.Box3().setFromObject(this._model);
            const center = box.getCenter(new this._three.Vector3());
            const size = box.getSize(new this._three.Vector3());

            this._model.position.x = -center.x;
            this._model.position.y = -box.min.y;
            this._model.position.z = -center.z;

            // Adjust scale if too large
            const maxDim = Math.max(size.x, size.y, size.z);
            if (maxDim > 3) {
                const scale = 3 / maxDim;
                this._model.scale.setScalar(scale);
            }
        },

        // ==================== TEXTURE & MATERIAL ====================

        applyFabricTexture(fabric) {
            if (!this._model || !this._three) return;

            const THREE = this._three;
            const textureUrl = fabric.texture_url || fabric.thumbnail_url;

            if (textureUrl) {
                const textureLoader = new THREE.TextureLoader();
                textureLoader.load(
                    textureUrl,
                    (texture) => {
                        texture.wrapS = THREE.RepeatWrapping;
                        texture.wrapT = THREE.RepeatWrapping;
                        texture.repeat.set(8, 8);
                        texture.colorSpace = THREE.SRGBColorSpace;

                        this._model.traverse((child) => {
                            if (child.isMesh) {
                                child.material.color = new THREE.Color(
                                    0xffffff
                                );
                                child.material.map = texture;
                                child.material.metalness = 0.0;
                                child.material.roughness = 0.8;
                                child.material.needsUpdate = true;
                            }
                        });
                    },
                    undefined,
                    (error) => {
                        console.error("Error loading texture:", error);
                        this.applyFabricColor(fabric.color_hex || "#3b82f6");
                    }
                );
            } else if (fabric.color_hex) {
                this.applyFabricColor(fabric.color_hex);
            }
        },

        applyFabricColor(colorHex) {
            if (!this._model || !this._three) return;

            const color = new this._three.Color(colorHex);

            this._model.traverse((child) => {
                if (child.isMesh) {
                    child.material.map = null;
                    child.material.color = color;
                    child.material.needsUpdate = true;
                }
            });
        },

        updateModelPart(categoryCode, option) {
            // TODO: Load/swap specific model parts based on category and option
            console.log("Update model part:", categoryCode, option.code);
        },

        // ==================== VIEW CONTROLS ====================

        setView(view) {
            if (!this._camera || !this._controls) return;

            const views = {
                front: { x: 0, y: 1.2, z: 3 },
                back: { x: 0, y: 1.2, z: -3 },
                side: { x: 3, y: 1.2, z: 0 },
            };

            const pos = views[view] || views.front;
            this._camera.position.set(pos.x, pos.y, pos.z);
            this._controls.target.set(0, 0.8, 0);
            this._controls.update();
        },

        zoomIn() {
            if (!this._camera) return;
            this._camera.position.multiplyScalar(0.9);
        },

        zoomOut() {
            if (!this._camera) return;
            this._camera.position.multiplyScalar(1.1);
        },

        toggleWireframe() {
            if (!this._model) return;
            this.wireframe = !this.wireframe;

            this._model.traverse((child) => {
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

        // ==================== ACTIONS ====================

        takeScreenshot() {
            if (!this._renderer) return;

            // Render current frame
            this._renderer.render(this._scene, this._camera);

            // Get data URL
            const dataUrl = this._renderer.domElement.toDataURL("image/png");

            // Download
            const link = document.createElement("a");
            link.download = `suit-config-${Date.now()}.png`;
            link.href = dataUrl;
            link.click();
        },

        async saveConfiguration() {
            try {
                const config = {
                    product_type: this.selectedProductType?.code,
                    fabric_id: this.selectedFabric?.id,
                    config: this.currentConfig,
                    lining_id: this.selectedLining?.id,
                    button_color: this.selectedButtonColor?.code,
                    thread_color: this.selectedThreadColor?.code,
                };

                // TODO: Save to backend via API
                console.log("Save configuration:", config);

                // Show success notification
                if (window.$wire) {
                    window.$wire.dispatch("notify", {
                        type: "success",
                        message: "Configuration saved successfully!",
                    });
                } else {
                    alert("Configuration saved! (Check console for details)");
                }
            } catch (error) {
                console.error("Failed to save configuration:", error);
                alert("Failed to save configuration");
            }
        },
    };
}

// Register with Alpine when this script loads
document.addEventListener("alpine:init", () => {
    Alpine.data("threeDConfiguratorAdmin", createThreeDConfiguratorAdmin);
});

// Also expose globally for manual registration if needed
window.createThreeDConfiguratorAdmin = createThreeDConfiguratorAdmin;

export { createThreeDConfiguratorAdmin };
