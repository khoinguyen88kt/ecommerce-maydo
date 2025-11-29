import * as THREE from "three";
import { GLTFLoader } from "three/examples/jsm/loaders/GLTFLoader.js";
import fs from "fs";
import path from "path";
import { fileURLToPath } from "url";

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const modelPath =
    process.argv[2] ||
    path.join(__dirname, "../storage/app/3d-models/test-suit.glb");
const absolutePath = path.resolve(modelPath);

console.log(`\n🔍 Inspecting 3D Model: ${absolutePath}\n`);

// Read file as ArrayBuffer
const fileBuffer = fs.readFileSync(absolutePath);
const arrayBuffer = fileBuffer.buffer.slice(
    fileBuffer.byteOffset,
    fileBuffer.byteOffset + fileBuffer.byteLength
);

const loader = new GLTFLoader();

loader.parse(
    arrayBuffer,
    "",
    (gltf) => {
        console.log("✅ Model loaded successfully!\n");

        console.log("📦 Scene Structure:");
        console.log("==================\n");

        const meshes = [];

        gltf.scene.traverse((object) => {
            if (object.isMesh) {
                meshes.push({
                    name: object.name || "Unnamed",
                    geometry: object.geometry.type,
                    vertices: object.geometry.attributes.position.count,
                    material: object.material.name || "Unnamed Material",
                    materialType: object.material.type,
                    hasTexture: !!object.material.map,
                    hasNormalMap: !!object.material.normalMap,
                    position: object.position,
                    scale: object.scale,
                });

                console.log(`Mesh: "${object.name || "Unnamed"}"`);
                console.log(`  - Type: ${object.geometry.type}`);
                console.log(
                    `  - Vertices: ${object.geometry.attributes.position.count}`
                );
                console.log(
                    `  - Material: ${object.material.name || "Unnamed"} (${
                        object.material.type
                    })`
                );
                console.log(`  - Has Texture: ${!!object.material.map}`);
                console.log(
                    `  - Position: (${object.position.x.toFixed(
                        2
                    )}, ${object.position.y.toFixed(
                        2
                    )}, ${object.position.z.toFixed(2)})`
                );
                console.log("");
            }
        });

        console.log(`\n📊 Summary:`);
        console.log(`  Total Meshes: ${meshes.length}`);
        console.log(
            `  Total Vertices: ${meshes.reduce(
                (sum, m) => sum + m.vertices,
                0
            )}`
        );

        // Generate parts mapping suggestion
        console.log(`\n💡 Suggested Parts Mapping:`);
        console.log("==========================\n");

        const partsMapping = {};
        meshes.forEach((mesh, index) => {
            const meshName = mesh.name.toLowerCase();

            // Try to map to suit parts
            let suggestedPart = "unknown";

            if (
                meshName.includes("jacket") ||
                meshName.includes("coat") ||
                meshName.includes("blazer")
            ) {
                suggestedPart = "jacket_body";
            } else if (
                meshName.includes("pants") ||
                meshName.includes("trouser")
            ) {
                suggestedPart = "pants_body";
            } else if (
                meshName.includes("vest") ||
                meshName.includes("waistcoat")
            ) {
                suggestedPart = "waistcoat_body";
            } else if (meshName.includes("collar")) {
                suggestedPart = "collar";
            } else if (meshName.includes("lapel")) {
                suggestedPart = "lapel";
            } else if (meshName.includes("sleeve")) {
                if (meshName.includes("left")) suggestedPart = "sleeve_left";
                else if (meshName.includes("right"))
                    suggestedPart = "sleeve_right";
                else suggestedPart = "sleeve_right";
            } else if (meshName.includes("pocket")) {
                suggestedPart = "breast_pocket";
            } else if (meshName.includes("button")) {
                suggestedPart = "buttons_body";
            } else if (meshName.includes("shirt")) {
                suggestedPart = "shirt_base";
            } else if (meshName.includes("tie")) {
                suggestedPart = "tie";
            }

            partsMapping[mesh.name] = suggestedPart;
            console.log(`  "${mesh.name}" → ${suggestedPart}`);
        });

        // Save mapping to JSON
        const mappingFile = absolutePath.replace(".glb", "-parts-mapping.json");
        fs.writeFileSync(mappingFile, JSON.stringify(partsMapping, null, 2));
        console.log(`\n💾 Parts mapping saved to: ${mappingFile}`);

        console.log(`\n✨ Inspection complete!`);
    },
    undefined,
    (error) => {
        console.error("❌ Error loading model:", error);
        process.exit(1);
    }
);
