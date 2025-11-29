#!/usr/bin/env node

/**
 * Batch Layer Generator
 * Generate layers for multiple models and fabrics
 * Then save to database
 */

import LayerGenerator from "./generate-layers.js";
import { exec } from "child_process";
import { promisify } from "util";
import fs from "fs/promises";
import path from "path";

const execAsync = promisify(exec);

class BatchGenerator {
    constructor() {
        this.generator = new LayerGenerator();
        this.baseOutputDir = "./public/images/configurator/generated";
    }

    async getModels() {
        // Scan models directory
        const modelsDir = "./storage/app/3d-models";
        const files = await fs.readdir(modelsDir);
        return files
            .filter((f) => f.endsWith(".glb"))
            .map((f) => path.join(modelsDir, f));
    }

    async getFabrics() {
        // Scan fabrics directory
        const fabricsDir = "./storage/app/fabrics";
        const files = await fs.readdir(fabricsDir);
        return files
            .filter((f) => /\.(jpg|png)$/i.test(f))
            .map((f) => path.join(fabricsDir, f));
    }

    async generateForModelAndFabric(modelPath, fabricPath) {
        const modelName = path.basename(modelPath, ".glb");
        const fabricName = path.basename(fabricPath, path.extname(fabricPath));

        const outputDir = path.join(this.baseOutputDir, modelName, fabricName);

        console.log(`\n${"=".repeat(60)}`);
        console.log(`🎨 Generating layers:`);
        console.log(`   Model: ${modelName}`);
        console.log(`   Fabric: ${fabricName}`);
        console.log(`${"=".repeat(60)}`);

        const layers = await this.generator.generateLayers(
            modelPath,
            fabricPath,
            outputDir
        );

        return {
            modelName,
            fabricName,
            layers,
            outputDir,
        };
    }

    async saveToDatabasePHP(results) {
        console.log("\n💾 Saving to database...");

        // Create JSON data file
        const dataPath = "./storage/app/layer-data.json";
        await fs.writeFile(dataPath, JSON.stringify(results, null, 2));

        // Call Laravel artisan command to import
        try {
            const { stdout } = await execAsync(
                `php artisan layers:import ${dataPath}`
            );
            console.log(stdout);
        } catch (error) {
            console.error("Error importing to database:", error);
        }
    }

    async generateAll() {
        console.log("🚀 Starting batch generation...\n");

        const models = await this.getModels();
        const fabrics = await this.getFabrics();

        console.log(`Found ${models.length} models`);
        console.log(`Found ${fabrics.length} fabrics`);
        console.log(`Total combinations: ${models.length * fabrics.length}\n`);

        const allResults = [];
        let completed = 0;
        const total = models.length * fabrics.length;

        for (const model of models) {
            for (const fabric of fabrics) {
                try {
                    const result = await this.generateForModelAndFabric(
                        model,
                        fabric
                    );
                    allResults.push(result);
                    completed++;

                    console.log(
                        `\n✅ Progress: ${completed}/${total} (${Math.round(
                            (completed / total) * 100
                        )}%)`
                    );
                } catch (error) {
                    console.error(
                        `❌ Error processing ${model} with ${fabric}:`,
                        error
                    );
                }
            }
        }

        // Save all results to database
        await this.saveToDatabasePHP(allResults);

        console.log("\n" + "=".repeat(60));
        console.log("✨ Batch generation completed!");
        console.log(`   Generated: ${completed}/${total} combinations`);
        console.log(
            `   Total layers: ${allResults.reduce(
                (sum, r) => sum + r.layers.length,
                0
            )}`
        );
        console.log("=".repeat(60));

        return allResults;
    }
}

// Run
const batch = new BatchGenerator();
batch.generateAll().catch(console.error);
