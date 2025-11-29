#!/usr/bin/env node

/**
 * Create a simple test GLB file with multiple named parts
 * This uses pure JavaScript to generate GLTF structure
 */

import fs from "fs";
import path from "path";
import { fileURLToPath } from "url";

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

// Simple box mesh data
function createBox(name, position, scale) {
    const vertices = new Float32Array([
        // Front face
        -1, -1, 1, 1, -1, 1, 1, 1, 1, -1, 1, 1,
        // Back face
        -1, -1, -1, -1, 1, -1, 1, 1, -1, 1, -1, -1,
        // Top face
        -1, 1, -1, -1, 1, 1, 1, 1, 1, 1, 1, -1,
        // Bottom face
        -1, -1, -1, 1, -1, -1, 1, -1, 1, -1, -1, 1,
        // Right face
        1, -1, -1, 1, 1, -1, 1, 1, 1, 1, -1, 1,
        // Left face
        -1, -1, -1, -1, -1, 1, -1, 1, 1, -1, 1, -1,
    ]);

    // Apply scale and position
    for (let i = 0; i < vertices.length; i += 3) {
        vertices[i] = vertices[i] * scale[0] + position[0];
        vertices[i + 1] = vertices[i + 1] * scale[1] + position[1];
        vertices[i + 2] = vertices[i + 2] * scale[2] + position[2];
    }

    const indices = new Uint16Array([
        0,
        1,
        2,
        0,
        2,
        3, // Front
        4,
        5,
        6,
        4,
        6,
        7, // Back
        8,
        9,
        10,
        8,
        10,
        11, // Top
        12,
        13,
        14,
        12,
        14,
        15, // Bottom
        16,
        17,
        18,
        16,
        18,
        19, // Right
        20,
        21,
        22,
        20,
        22,
        23, // Left
    ]);

    return { name, vertices, indices };
}

// Create suit parts
const parts = [
    createBox("jacket_body", [0, 1.2, 0], [0.4, 0.5, 0.2]),
    createBox("lapel", [0.25, 1.5, 0.21], [0.1, 0.3, 0.02]),
    createBox("collar", [0, 1.7, 0.15], [0.35, 0.1, 0.05]),
    createBox("sleeve_right", [0.6, 1.2, 0], [0.12, 0.5, 0.12]),
    createBox("sleeve_left", [-0.6, 1.2, 0], [0.12, 0.5, 0.12]),
    createBox("pocket_right", [0.2, 1.1, 0.21], [0.08, 0.1, 0.02]),
    createBox("pocket_left", [-0.2, 1.1, 0.21], [0.08, 0.1, 0.02]),
    createBox("pants_body", [0, 0.5, 0], [0.35, 0.5, 0.18]),
    createBox("pants_pocket", [0.25, 0.7, 0.19], [0.06, 0.08, 0.02]),
];

console.log("🔨 Creating simple test suit model...\n");

// Build GLTF JSON structure
const gltf = {
    asset: {
        version: "2.0",
        generator: "Simple GLTF Generator",
    },
    scene: 0,
    scenes: [{ nodes: [] }],
    nodes: [],
    meshes: [],
    buffers: [],
    bufferViews: [],
    accessors: [],
};

let bufferData = Buffer.alloc(0);
let bufferOffset = 0;

parts.forEach((part, index) => {
    // Add vertex buffer
    const vertexBuffer = Buffer.from(part.vertices.buffer);
    const vertexBufferView = gltf.bufferViews.length;
    gltf.bufferViews.push({
        buffer: 0,
        byteOffset: bufferOffset,
        byteLength: vertexBuffer.length,
        target: 34962, // ARRAY_BUFFER
    });
    bufferData = Buffer.concat([bufferData, vertexBuffer]);
    bufferOffset += vertexBuffer.length;

    // Add index buffer
    const indexBuffer = Buffer.from(part.indices.buffer);
    const indexBufferView = gltf.bufferViews.length;
    gltf.bufferViews.push({
        buffer: 0,
        byteOffset: bufferOffset,
        byteLength: indexBuffer.length,
        target: 34963, // ELEMENT_ARRAY_BUFFER
    });
    bufferData = Buffer.concat([bufferData, indexBuffer]);
    bufferOffset += indexBuffer.length;

    // Add accessors
    const positionAccessor = gltf.accessors.length;
    gltf.accessors.push({
        bufferView: vertexBufferView,
        byteOffset: 0,
        componentType: 5126, // FLOAT
        count: part.vertices.length / 3,
        type: "VEC3",
        max: [
            Math.max(
                ...Array.from(part.vertices).filter((_, i) => i % 3 === 0)
            ),
            Math.max(
                ...Array.from(part.vertices).filter((_, i) => i % 3 === 1)
            ),
            Math.max(
                ...Array.from(part.vertices).filter((_, i) => i % 3 === 2)
            ),
        ],
        min: [
            Math.min(
                ...Array.from(part.vertices).filter((_, i) => i % 3 === 0)
            ),
            Math.min(
                ...Array.from(part.vertices).filter((_, i) => i % 3 === 1)
            ),
            Math.min(
                ...Array.from(part.vertices).filter((_, i) => i % 3 === 2)
            ),
        ],
    });

    const indicesAccessor = gltf.accessors.length;
    gltf.accessors.push({
        bufferView: indexBufferView,
        byteOffset: 0,
        componentType: 5123, // UNSIGNED_SHORT
        count: part.indices.length,
        type: "SCALAR",
    });

    // Add mesh
    gltf.meshes.push({
        name: part.name,
        primitives: [
            {
                attributes: {
                    POSITION: positionAccessor,
                },
                indices: indicesAccessor,
            },
        ],
    });

    // Add node
    gltf.nodes.push({
        name: part.name,
        mesh: index,
    });

    gltf.scenes[0].nodes.push(index);

    console.log(`✓ Created part: ${part.name}`);
});

// Add buffer
gltf.buffers.push({
    byteLength: bufferData.length,
});

// Convert to GLB
const jsonString = JSON.stringify(gltf);
const jsonBuffer = Buffer.from(jsonString);
const jsonPadding = (4 - (jsonBuffer.length % 4)) % 4;
const jsonChunk = Buffer.concat([jsonBuffer, Buffer.alloc(jsonPadding, 0x20)]);

const binPadding = (4 - (bufferData.length % 4)) % 4;
const binChunk = Buffer.concat([bufferData, Buffer.alloc(binPadding, 0)]);

// GLB header
const header = Buffer.alloc(12);
header.writeUInt32LE(0x46546c67, 0); // 'glTF' magic
header.writeUInt32LE(2, 4); // version
header.writeUInt32LE(12 + 8 + jsonChunk.length + 8 + binChunk.length, 8); // total length

// JSON chunk header
const jsonChunkHeader = Buffer.alloc(8);
jsonChunkHeader.writeUInt32LE(jsonChunk.length, 0);
jsonChunkHeader.writeUInt32LE(0x4e4f534a, 4); // 'JSON'

// BIN chunk header
const binChunkHeader = Buffer.alloc(8);
binChunkHeader.writeUInt32LE(binChunk.length, 0);
binChunkHeader.writeUInt32LE(0x004e4942, 4); // 'BIN\0'

const glb = Buffer.concat([
    header,
    jsonChunkHeader,
    jsonChunk,
    binChunkHeader,
    binChunk,
]);

// Save file
const outputPath = path.join(
    __dirname,
    "../storage/app/3d-models/simple-suit-test.glb"
);
fs.writeFileSync(outputPath, glb);

console.log(`\n✅ Model created successfully!`);
console.log(`📁 Output: ${outputPath}`);
console.log(`📊 Size: ${(glb.length / 1024).toFixed(2)} KB`);
console.log(`📦 Parts: ${parts.length}`);
console.log(`\n💡 Use this model to test the layer generation workflow.`);
