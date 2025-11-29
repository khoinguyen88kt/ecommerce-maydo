/**
 * ThreeDConfigurator - Modular 3D Model Configurator using Three.js
 *
 * Handles dynamic loading/swapping of GLB model parts and applying fabric textures
 * Based on Dunnio Tailor's modular architecture
 */

import * as THREE from "three";
import { GLTFLoader } from "three/examples/jsm/loaders/GLTFLoader.js";
import { DRACOLoader } from "three/examples/jsm/loaders/DRACOLoader.js";
import { OrbitControls } from "three/examples/jsm/controls/OrbitControls.js";

export class ThreeDConfigurator {
    constructor(container, options = {}) {
        this.container =
            typeof container === "string"
                ? document.querySelector(container)
                : container;

        if (!this.container) {
            throw new Error("Container element not found");
        }

        // Options
        this.options = {
            productType: "jacket",
            apiBaseUrl: "/api/3d-configurator",
            backgroundColor: 0xf5f5f5,
            ambientLightIntensity: 0.8,
            directionalLightIntensity: 1.2,
            cameraPosition: { x: 0, y: 1.2, z: 3 },
            enableOrbitControls: true,
            enableShadows: true,
            textureRepeat: { x: 32, y: 32 },
            ...options,
        };

        // State
        this.currentConfig = {};
        this.loadedParts = new Map(); // category -> THREE.Group
        this.meshMaterialMap = new Map(); // meshName -> { originalMaterial, currentMaterial }
        this.fabricTexture = null;
        this.liningTexture = null;
        this.configData = null;
        this.isLoading = false;

        // Three.js components
        this.scene = null;
        this.camera = null;
        this.renderer = null;
        this.controls = null;
        this.modelGroup = null;

        // Loaders
        this.gltfLoader = null;
        this.textureLoader = null;
        this.dracoLoader = null;

        // Event callbacks
        this.onLoadStart = options.onLoadStart || (() => {});
        this.onLoadProgress = options.onLoadProgress || (() => {});
        this.onLoadComplete = options.onLoadComplete || (() => {});
        this.onLoadError = options.onLoadError || (() => {});
        this.onConfigChange = options.onConfigChange || (() => {});

        this.init();
    }

    /**
     * Initialize Three.js scene
     */
    init() {
        // Scene
        this.scene = new THREE.Scene();
        this.scene.background = new THREE.Color(this.options.backgroundColor);

        // Camera
        const aspect = this.container.clientWidth / this.container.clientHeight;
        this.camera = new THREE.PerspectiveCamera(45, aspect, 0.1, 1000);
        this.camera.position.set(
            this.options.cameraPosition.x,
            this.options.cameraPosition.y,
            this.options.cameraPosition.z
        );

        // Renderer
        this.renderer = new THREE.WebGLRenderer({
            antialias: true,
            alpha: true,
            preserveDrawingBuffer: true,
        });
        this.renderer.setSize(
            this.container.clientWidth,
            this.container.clientHeight
        );
        this.renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));

        if (this.options.enableShadows) {
            this.renderer.shadowMap.enabled = true;
            this.renderer.shadowMap.type = THREE.PCFSoftShadowMap;
        }

        this.renderer.outputColorSpace = THREE.SRGBColorSpace;
        this.renderer.toneMapping = THREE.ACESFilmicToneMapping;
        this.renderer.toneMappingExposure = 1.2;

        this.container.appendChild(this.renderer.domElement);

        // Controls
        if (this.options.enableOrbitControls) {
            this.controls = new OrbitControls(
                this.camera,
                this.renderer.domElement
            );
            this.controls.enableDamping = true;
            this.controls.dampingFactor = 0.05;
            this.controls.minDistance = 1.5;
            this.controls.maxDistance = 8;
            this.controls.minPolarAngle = Math.PI / 4;
            this.controls.maxPolarAngle = Math.PI / 1.5;
            this.controls.target.set(0, 0.8, 0);
        }

        // Lights
        this.setupLights();

        // Model container
        this.modelGroup = new THREE.Group();
        this.scene.add(this.modelGroup);

        // Loaders
        this.setupLoaders();

        // Event listeners
        window.addEventListener("resize", () => this.onWindowResize());

        // Start render loop
        this.animate();
    }

    /**
     * Setup scene lights
     */
    setupLights() {
        // Ambient light
        const ambientLight = new THREE.AmbientLight(
            0xffffff,
            this.options.ambientLightIntensity
        );
        this.scene.add(ambientLight);

        // Main directional light
        const mainLight = new THREE.DirectionalLight(
            0xffffff,
            this.options.directionalLightIntensity
        );
        mainLight.position.set(5, 10, 7.5);

        if (this.options.enableShadows) {
            mainLight.castShadow = true;
            mainLight.shadow.mapSize.width = 2048;
            mainLight.shadow.mapSize.height = 2048;
            mainLight.shadow.camera.near = 0.5;
            mainLight.shadow.camera.far = 50;
        }

        this.scene.add(mainLight);

        // Fill lights
        const fillLight1 = new THREE.DirectionalLight(0xffffff, 0.4);
        fillLight1.position.set(-5, 5, -5);
        this.scene.add(fillLight1);

        const fillLight2 = new THREE.DirectionalLight(0xffffff, 0.3);
        fillLight2.position.set(0, -2, 5);
        this.scene.add(fillLight2);

        // Hemisphere light for natural look
        const hemiLight = new THREE.HemisphereLight(0xffffff, 0x444444, 0.6);
        hemiLight.position.set(0, 20, 0);
        this.scene.add(hemiLight);
    }

    /**
     * Setup model loaders
     */
    setupLoaders() {
        // DRACO loader for compressed meshes
        this.dracoLoader = new DRACOLoader();
        this.dracoLoader.setDecoderPath("/js/draco/");

        // GLTF loader
        this.gltfLoader = new GLTFLoader();
        this.gltfLoader.setDRACOLoader(this.dracoLoader);

        // Texture loader
        this.textureLoader = new THREE.TextureLoader();
    }

    /**
     * Load configuration data from API
     */
    async loadConfiguration(productType = null, fabricId = null) {
        this.isLoading = true;
        this.onLoadStart();

        try {
            const url = new URL(
                `${this.options.apiBaseUrl}/${
                    productType || this.options.productType
                }`,
                window.location.origin
            );
            if (fabricId) {
                url.searchParams.append("fabric_id", fabricId);
            }

            const response = await fetch(url);
            const data = await response.json();

            if (!data.success) {
                throw new Error(data.error || "Failed to load configuration");
            }

            this.configData = data.data;
            this.currentConfig = { ...data.data.default_config };

            // Load fabric texture if provided
            if (data.data.fabric?.texture_url) {
                await this.loadFabricTexture(data.data.fabric.texture_url);
            }

            // Load initial model parts
            await this.loadModelParts();

            this.onLoadComplete(this.configData);

            return this.configData;
        } catch (error) {
            this.onLoadError(error);
            throw error;
        } finally {
            this.isLoading = false;
        }
    }

    /**
     * Load all model parts for current configuration
     */
    async loadModelParts() {
        const response = await fetch(
            `${this.options.apiBaseUrl}/${this.options.productType}/model-files`,
            {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN":
                        document.querySelector('meta[name="csrf-token"]')
                            ?.content || "",
                },
                body: JSON.stringify({ config: this.currentConfig }),
            }
        );

        const data = await response.json();
        if (!data.success) {
            throw new Error(data.error || "Failed to get model files");
        }

        // Clear existing parts
        this.clearAllParts();

        // Load all parts
        const loadPromises = data.data.map((file) => this.loadPart(file));
        await Promise.all(loadPromises);

        // Center and scale model
        this.centerModel();
    }

    /**
     * Load a single model part
     */
    async loadPart(fileInfo) {
        return new Promise((resolve, reject) => {
            this.gltfLoader.load(
                fileInfo.url,
                (gltf) => {
                    const model = gltf.scene;
                    model.name = `${fileInfo.category}_${fileInfo.option}`;
                    model.userData = fileInfo;

                    // Process meshes
                    model.traverse((child) => {
                        if (child.isMesh) {
                            // Enable shadows
                            child.castShadow = true;
                            child.receiveShadow = true;

                            // Store original material
                            this.meshMaterialMap.set(child.name, {
                                original: child.material.clone(),
                                current: child.material,
                            });

                            // Find mesh config from fileInfo
                            const meshConfig = fileInfo.meshes?.find(
                                (m) => m.name === child.name
                            );
                            if (meshConfig) {
                                this.applyMeshConfig(child, meshConfig);
                            }
                        }
                    });

                    // Store and add to scene
                    this.loadedParts.set(fileInfo.category, model);
                    this.modelGroup.add(model);

                    resolve(model);
                },
                (progress) => {
                    const percent = (progress.loaded / progress.total) * 100;
                    this.onLoadProgress(fileInfo.category, percent);
                },
                (error) => {
                    console.error(
                        `Failed to load part: ${fileInfo.url}`,
                        error
                    );
                    reject(error);
                }
            );
        });
    }

    /**
     * Apply mesh configuration (material type, textures)
     */
    applyMeshConfig(mesh, config) {
        if (!config.apply_fabric_texture) return;

        const materialType = config.material_type || "fabric";

        switch (materialType) {
            case "fabric":
                if (this.fabricTexture) {
                    this.applyFabricToMesh(
                        mesh,
                        this.fabricTexture,
                        config.texture_settings
                    );
                }
                break;
            case "lining":
                if (this.liningTexture) {
                    this.applyFabricToMesh(
                        mesh,
                        this.liningTexture,
                        config.texture_settings
                    );
                }
                break;
            case "button":
            case "metal":
                // Keep original material or apply metallic settings
                this.applyMetallicMaterial(mesh, config);
                break;
            case "thread":
            case "contrast":
                // These might use a solid color from config
                break;
        }
    }

    /**
     * Apply fabric texture to a mesh
     */
    applyFabricToMesh(mesh, texture, settings = {}) {
        const material = new THREE.MeshStandardMaterial({
            map: texture.clone(),
            roughness: settings.roughness ?? 0.7,
            metalness: settings.metallic ?? 0.0,
        });

        // Apply UV transform
        if (material.map) {
            material.map.needsUpdate = true;

            // Use settings or defaults
            const scaleU = settings.scale?.u ?? this.options.textureRepeat.x;
            const scaleV = settings.scale?.v ?? this.options.textureRepeat.y;

            material.map.repeat.set(scaleU, scaleV);
            material.map.wrapS = THREE.RepeatWrapping;
            material.map.wrapT = THREE.RepeatWrapping;

            if (settings.offset) {
                material.map.offset.set(
                    settings.offset.u || 0,
                    settings.offset.v || 0
                );
            }

            if (settings.rotation) {
                material.map.rotation = settings.rotation;
            }
        }

        mesh.material = material;

        // Update material map
        const stored = this.meshMaterialMap.get(mesh.name);
        if (stored) {
            stored.current = material;
        }
    }

    /**
     * Apply metallic material settings
     */
    applyMetallicMaterial(mesh, config) {
        const settings = config.texture_settings || {};

        mesh.material.metalness = settings.metallic ?? 0.9;
        mesh.material.roughness = settings.roughness ?? 0.3;

        if (settings.color) {
            mesh.material.color.set(settings.color);
        }
    }

    /**
     * Load fabric texture
     */
    async loadFabricTexture(url) {
        return new Promise((resolve, reject) => {
            this.textureLoader.load(
                url,
                (texture) => {
                    texture.colorSpace = THREE.SRGBColorSpace;
                    texture.wrapS = THREE.RepeatWrapping;
                    texture.wrapT = THREE.RepeatWrapping;
                    texture.repeat.set(
                        this.options.textureRepeat.x,
                        this.options.textureRepeat.y
                    );

                    this.fabricTexture = texture;
                    resolve(texture);
                },
                undefined,
                (error) => {
                    console.error("Failed to load fabric texture:", error);
                    reject(error);
                }
            );
        });
    }

    /**
     * Change fabric
     */
    async changeFabric(fabricId, textureUrl = null) {
        if (textureUrl) {
            await this.loadFabricTexture(textureUrl);
        }

        // Re-apply fabric to all fabric meshes
        this.loadedParts.forEach((model) => {
            model.traverse((child) => {
                if (child.isMesh) {
                    const meshConfig = model.userData.meshes?.find(
                        (m) => m.name === child.name
                    );
                    if (
                        meshConfig?.material_type === "fabric" &&
                        meshConfig.apply_fabric_texture
                    ) {
                        this.applyFabricToMesh(
                            child,
                            this.fabricTexture,
                            meshConfig.texture_settings
                        );
                    }
                }
            });
        });

        this.onConfigChange({ type: "fabric", fabricId });
    }

    /**
     * Update configuration option
     */
    async updateOption(categoryCode, optionCode) {
        if (this.isLoading) return;

        this.isLoading = true;
        this.onLoadStart();

        try {
            const response = await fetch(
                `${this.options.apiBaseUrl}/${this.options.productType}/update-option`,
                {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN":
                            document.querySelector('meta[name="csrf-token"]')
                                ?.content || "",
                    },
                    body: JSON.stringify({
                        config: this.currentConfig,
                        category: categoryCode,
                        option: optionCode,
                    }),
                }
            );

            const data = await response.json();
            if (!data.success) {
                throw new Error(data.error || "Failed to update option");
            }

            // Remove old parts
            for (const toRemove of data.data.remove_files) {
                this.removePart(toRemove.category);
            }

            // Update config
            this.currentConfig = data.data.config;

            // Load new parts
            for (const file of data.data.changed_files) {
                await this.loadPart(file);
            }

            this.centerModel();
            this.onConfigChange({
                type: "option",
                category: categoryCode,
                option: optionCode,
            });
            this.onLoadComplete(this.configData);
        } catch (error) {
            this.onLoadError(error);
            throw error;
        } finally {
            this.isLoading = false;
        }
    }

    /**
     * Remove a part from the scene
     */
    removePart(categoryCode) {
        const part = this.loadedParts.get(categoryCode);
        if (part) {
            // Dispose materials and geometries
            part.traverse((child) => {
                if (child.isMesh) {
                    child.geometry?.dispose();
                    if (Array.isArray(child.material)) {
                        child.material.forEach((m) => m.dispose());
                    } else {
                        child.material?.dispose();
                    }
                    this.meshMaterialMap.delete(child.name);
                }
            });

            this.modelGroup.remove(part);
            this.loadedParts.delete(categoryCode);
        }
    }

    /**
     * Clear all loaded parts
     */
    clearAllParts() {
        this.loadedParts.forEach((part, category) => {
            this.removePart(category);
        });
        this.loadedParts.clear();
    }

    /**
     * Center and scale the model
     */
    centerModel() {
        const box = new THREE.Box3().setFromObject(this.modelGroup);
        const center = box.getCenter(new THREE.Vector3());
        const size = box.getSize(new THREE.Vector3());

        // Reset position to center
        this.modelGroup.position.x = -center.x;
        this.modelGroup.position.z = -center.z;

        // Keep Y at ground level
        this.modelGroup.position.y = -box.min.y;

        // Scale if too big
        const maxDim = Math.max(size.x, size.y, size.z);
        if (maxDim > 3) {
            const scale = 3 / maxDim;
            this.modelGroup.scale.setScalar(scale);
        }
    }

    /**
     * Get current configuration
     */
    getConfiguration() {
        return { ...this.currentConfig };
    }

    /**
     * Save configuration
     */
    async saveConfiguration(name = null, fabricId = null) {
        const response = await fetch(
            `${this.options.apiBaseUrl}/${this.options.productType}/save`,
            {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN":
                        document.querySelector('meta[name="csrf-token"]')
                            ?.content || "",
                },
                body: JSON.stringify({
                    config: this.currentConfig,
                    fabric_id: fabricId,
                    name: name,
                }),
            }
        );

        const data = await response.json();
        if (!data.success) {
            throw new Error(data.error || "Failed to save configuration");
        }

        return data.data;
    }

    /**
     * Take screenshot
     */
    takeScreenshot(width = 1920, height = 1080) {
        const originalWidth = this.renderer.domElement.width;
        const originalHeight = this.renderer.domElement.height;

        // Resize for screenshot
        this.renderer.setSize(width, height);
        this.camera.aspect = width / height;
        this.camera.updateProjectionMatrix();

        // Render
        this.renderer.render(this.scene, this.camera);

        // Get image
        const dataUrl = this.renderer.domElement.toDataURL("image/png");

        // Restore size
        this.renderer.setSize(originalWidth, originalHeight);
        this.camera.aspect = originalWidth / originalHeight;
        this.camera.updateProjectionMatrix();

        return dataUrl;
    }

    /**
     * Set camera view
     */
    setView(view) {
        const views = {
            front: { x: 0, y: 1.2, z: 3 },
            back: { x: 0, y: 1.2, z: -3 },
            left: { x: -3, y: 1.2, z: 0 },
            right: { x: 3, y: 1.2, z: 0 },
            top: { x: 0, y: 4, z: 0.1 },
        };

        const pos = views[view] || views.front;
        this.camera.position.set(pos.x, pos.y, pos.z);

        if (this.controls) {
            this.controls.target.set(0, 0.8, 0);
            this.controls.update();
        }
    }

    /**
     * Handle window resize
     */
    onWindowResize() {
        const width = this.container.clientWidth;
        const height = this.container.clientHeight;

        this.camera.aspect = width / height;
        this.camera.updateProjectionMatrix();
        this.renderer.setSize(width, height);
    }

    /**
     * Animation loop
     */
    animate() {
        requestAnimationFrame(() => this.animate());

        if (this.controls) {
            this.controls.update();
        }

        this.renderer.render(this.scene, this.camera);
    }

    /**
     * Dispose of all resources
     */
    dispose() {
        // Stop animation
        this.renderer.setAnimationLoop(null);

        // Clear parts
        this.clearAllParts();

        // Dispose textures
        if (this.fabricTexture) {
            this.fabricTexture.dispose();
        }
        if (this.liningTexture) {
            this.liningTexture.dispose();
        }

        // Dispose loaders
        this.dracoLoader?.dispose();

        // Dispose controls
        this.controls?.dispose();

        // Dispose renderer
        this.renderer.dispose();

        // Remove canvas
        this.container.removeChild(this.renderer.domElement);

        // Remove event listeners
        window.removeEventListener("resize", () => this.onWindowResize());
    }
}

export default ThreeDConfigurator;
