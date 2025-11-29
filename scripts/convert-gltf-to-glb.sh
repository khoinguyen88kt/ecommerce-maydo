#!/bin/bash
# Convert all GLTF files to GLB format

BASE_DIR="/var/www/storage/app/3d-models/dunnio"
OUTPUT_DIR="$BASE_DIR/glb"

mkdir -p "$OUTPUT_DIR"

# Find all GLTF files and convert them
find "$BASE_DIR" -name "*.gltf" -not -path "*/glb/*" | while read gltf; do
  dir=$(dirname "$gltf")
  name=$(basename "$gltf" .gltf)
  relpath=${dir#$BASE_DIR/}

  mkdir -p "$OUTPUT_DIR/$relpath"
  output="$OUTPUT_DIR/$relpath/$name.glb"

  echo "Converting: $gltf"
  echo "       to: $output"
  gltf-transform copy "$gltf" "$output"
  echo ""
done

echo "Done! All GLB files saved to: $OUTPUT_DIR"
