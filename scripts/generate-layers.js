#!/usr/bin/env node

/**
 * Layer Generator Service
 * Convert 3D GLB models to 2D layer images
 *
 * Usage: node generate-layers.js --model=suit.glb --fabric=wool_01.jpg
 */

import * as THREE from "three";
import { GLTFLoader } from "three/examples/jsm/loaders/GLTFLoader.js";
import { createCanvas } from "canvas";
import fs from "fs";
import path from "path";

class LayerGenerator {
    constructor(config = {}) {
        this.width = config.width || 800;
        this.height = config.height || 1200;
        this.views = config.views || ["front", "back"];
        this.parts = config.parts || [
            "jacket_body",
            "lapel",
            "collar",
            "breast_pocket",
            "buttons",
            "sleeves",
            "pants_body",
            "pants_pocket",
        ];

        this.setupRenderer();
        this.setupScene();
        this.setupCamera();
    }

    setupRenderer() {
        // Use headless rendering with node-canvas
        const canvas = createCanvas(this.width, this.height);
        this.renderer = new THREE.WebGLRenderer({
            canvas,
            antialias: true,
            alpha: true,
            preserveDrawingBuffer: true,
        });
        this.renderer.setSize(this.width, this.height);
        this.renderer.setClearColor(0x000000, 0); // Transparent background
    }

    setupScene() {
        this.scene = new THREE.Scene();

        // Lighting setup
        const ambientLight = new THREE.AmbientLight(0xffffff, 0.6);
        this.scene.add(ambientLight);

        const directionalLight = new THREE.DirectionalLight(0xffffff, 0.8);
        directionalLight.position.set(0, 1, 1);
        this.scene.add(directionalLight);
    }

    setupCamera() {
        this.camera = new THREE.PerspectiveCamera(
            45,
            this.width / this.height,
            0.1,
            1000
        );
        this.camera.position.set(0, 0, 5);
        this.camera.lookAt(0, 0, 0);
    }

    async loadModel(modelPath) {
        return new Promise((resolve, reject) => {
            const loader = new GLTFLoader();
            loader.load(
                modelPath,
                (gltf) => resolve(gltf.scene),
                undefined,
                reject
            );
        });
    }

    applyFabricTexture(model, fabricPath, partName) {
        const texture = new THREE.TextureLoader().load(fabricPath);
        texture.wrapS = THREE.RepeatWrapping;
        texture.wrapT = THREE.RepeatWrapping;
        texture.repeat.set(2, 2);

        model.traverse((child) => {
            if (child.isMesh && child.name === partName) {
                child.material.map = texture;
                child.material.needsUpdate = true;
            }
        });
    }

    setView(model, view) {
        switch (view) {
            case "front":
                model.rotation.y = 0;
                break;
            case "back":
                model.rotation.y = Math.PI;
                break;
            case "side_left":
                model.rotation.y = Math.PI / 2;
                break;
            case "side_right":
                model.rotation.y = -Math.PI / 2;
                break;
        }
    }

    hideAllParts(model) {
        model.traverse((child) => {
            if (child.isMesh) {
                child.visible = false;
            }
        });
    }

    showOnlyPart(model, partName) {
        this.hideAllParts(model);
        model.traverse((child) => {
            if (child.isMesh && child.name === partName) {
                child.visible = true;
            }
        });
    }

    render() {
        this.renderer.render(this.scene, this.camera);
    }

    saveImage(outputPath) {
        const canvas = this.renderer.domElement;
        const buffer = canvas.toBuffer("image/png");
        fs.writeFileSync(outputPath, buffer);
        console.log(`✅ Saved: ${outputPath}`);
    }

    async generateLayers(modelPath, fabricPath, outputDir) {
        console.log("🚀 Starting layer generation...");
        console.log(`Model: ${modelPath}`);
        console.log(`Fabric: ${fabricPath}`);

        // Create output directory
        if (!fs.existsSync(outputDir)) {
            fs.mkdirSync(outputDir, { recursive: true });
        }

        // Load model
        const model = await this.loadModel(modelPath);
        this.scene.add(model);

        const layers = [];
        let layerCount = 0;

        // Generate for each view
        for (const view of this.views) {
            console.log(`\n📸 Processing view: ${view}`);
            this.setView(model, view);

            // Generate for each part
            for (const part of this.parts) {
                console.log(`  → Rendering part: ${part}`);

                // Apply fabric texture
                this.applyFabricTexture(model, fabricPath, part);

                // Show only this part
                this.showOnlyPart(model, part);

                // Render
                this.render();

                // Save
                const filename = `${view}_${part}.png`;
                const filepath = path.join(outputDir, filename);
                this.saveImage(filepath);

                layers.push({
                    view,
                    part,
                    filename,
                    filepath,
                    z_index: 3000 + layerCount * 10,
                });

                layerCount++;
            }
        }

        console.log(`\n✨ Generated ${layerCount} layers successfully!`);
        return layers;
    }
}

// CLI Usage
async function main() {
    const args = process.argv.slice(2);
    const config = {};

    args.forEach((arg) => {
        const [key, value] = arg.split("=");
        config[key.replace("--", "")] = value;
    });

    const modelPath = config.model || "./models/suit.glb";
    const fabricPath = config.fabric || "./fabrics/default.jpg";
    const outputDir = config.output || "./output/layers";

    const generator = new LayerGenerator();
    await generator.generateLayers(modelPath, fabricPath, outputDir);
}

if (import.meta.url === `file://${process.argv[1]}`) {
    main().catch(console.error);
}

export default LayerGenerator;
