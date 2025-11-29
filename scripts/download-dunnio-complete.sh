#!/bin/bash

# Download complete Dunnio 3D models
BASE_URL="https://t4t.vn/sites/default/files/fashion/meshes/3d/Render/Models"
BASE_DIR="/Volumes/data_t7/workspace/laravel/ecommerce_maydo/suit-configurator/storage/app/3d-models/dunnio-complete"

HEADERS='-H "User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36" -H "Referer: https://dunniotailor.com/"'

download_file() {
  local url="$1"
  local dest="$2"

  mkdir -p "$(dirname "$dest")"
  echo "Downloading: $url"
  curl -sL $HEADERS "$url" -o "$dest"
}

echo "=== Downloading Dunnio Complete 3D Models ==="

# JACKET PARTS
echo ""
echo "=== JACKET ==="

# Front/Bottom (main body)
mkdir -p "$BASE_DIR/jacket/front/bottom/2button"
download_file "$BASE_URL/Jacket/Front/Bottom/2Button/Curved.gltf" "$BASE_DIR/jacket/front/bottom/2button/Curved.gltf"
download_file "$BASE_URL/Jacket/Front/Bottom/2Button/Curved.bin" "$BASE_DIR/jacket/front/bottom/2button/Curved.bin"

# Front/Button
mkdir -p "$BASE_DIR/jacket/front/button/2button"
download_file "$BASE_URL/Jacket/Front/Button/2Button/S4.gltf" "$BASE_DIR/jacket/front/button/2button/S4.gltf"
download_file "$BASE_URL/Jacket/Front/Button/2Button/S4.bin" "$BASE_DIR/jacket/front/button/2button/S4.bin"
download_file "$BASE_URL/Jacket/Front/Button/2Button/S14.png" "$BASE_DIR/jacket/front/button/2button/S14.png"

# Front/Thread
mkdir -p "$BASE_DIR/jacket/front/thread"
download_file "$BASE_URL/Jacket/Front/Thread/2Button.gltf" "$BASE_DIR/jacket/front/thread/2Button.gltf"
download_file "$BASE_URL/Jacket/Front/Thread/2Button.bin" "$BASE_DIR/jacket/front/thread/2Button.bin"

# Lapel Upper
mkdir -p "$BASE_DIR/jacket/lapel/upper/2button"
download_file "$BASE_URL/Jacket/Lapel/Larger/Upper/2Button/CL1.gltf" "$BASE_DIR/jacket/lapel/upper/2button/CL1.gltf"
download_file "$BASE_URL/Jacket/Lapel/Larger/Upper/2Button/CL1.bin" "$BASE_DIR/jacket/lapel/upper/2button/CL1.bin"

# Lapel Lower
mkdir -p "$BASE_DIR/jacket/lapel/lower/2button"
download_file "$BASE_URL/Jacket/Lapel/Larger/Lower/2Button/CL1.gltf" "$BASE_DIR/jacket/lapel/lower/2button/CL1.gltf"
download_file "$BASE_URL/Jacket/Lapel/Larger/Lower/2Button/CL1.bin" "$BASE_DIR/jacket/lapel/lower/2button/CL1.bin"

# Sleeve
mkdir -p "$BASE_DIR/jacket/sleeve"
download_file "$BASE_URL/Jacket/Sleeve/Sleeve.gltf" "$BASE_DIR/jacket/sleeve/Sleeve.gltf"
download_file "$BASE_URL/Jacket/Sleeve/Sleeve.bin" "$BASE_DIR/jacket/sleeve/Sleeve.bin"

# Sleeve Buttons (4 Button)
mkdir -p "$BASE_DIR/jacket/sleeve/standard/4button"
download_file "$BASE_URL/Jacket/Sleeve/Standard/4Button/S4.gltf" "$BASE_DIR/jacket/sleeve/standard/4button/S4.gltf"
download_file "$BASE_URL/Jacket/Sleeve/Standard/4Button/S4.bin" "$BASE_DIR/jacket/sleeve/standard/4button/S4.bin"
download_file "$BASE_URL/Jacket/Sleeve/Standard/4Button/S14.png" "$BASE_DIR/jacket/sleeve/standard/4button/S14.png"

# Sleeve Last Button
mkdir -p "$BASE_DIR/jacket/sleeve/standard/lastbutton"
download_file "$BASE_URL/Jacket/Sleeve/Standard/LastButton/S4.gltf" "$BASE_DIR/jacket/sleeve/standard/lastbutton/S4.gltf"
download_file "$BASE_URL/Jacket/Sleeve/Standard/LastButton/S4.bin" "$BASE_DIR/jacket/sleeve/standard/lastbutton/S4.bin"
download_file "$BASE_URL/Jacket/Sleeve/Standard/LastButton/S14.png" "$BASE_DIR/jacket/sleeve/standard/lastbutton/S14.png"

# Sleeve Thread
mkdir -p "$BASE_DIR/jacket/sleeve/standard/thread"
download_file "$BASE_URL/Jacket/Sleeve/Standard/Thread/4Button.gltf" "$BASE_DIR/jacket/sleeve/standard/thread/4Button.gltf"
download_file "$BASE_URL/Jacket/Sleeve/Standard/Thread/4Button.bin" "$BASE_DIR/jacket/sleeve/standard/thread/4Button.bin"
download_file "$BASE_URL/Jacket/Sleeve/Standard/Thread/LastThread.gltf" "$BASE_DIR/jacket/sleeve/standard/thread/LastThread.gltf"
download_file "$BASE_URL/Jacket/Sleeve/Standard/Thread/LastThread.bin" "$BASE_DIR/jacket/sleeve/standard/thread/LastThread.bin"

# Pocket
mkdir -p "$BASE_DIR/jacket/pocket"
download_file "$BASE_URL/Jacket/Pocket/PK-1.gltf" "$BASE_DIR/jacket/pocket/PK-1.gltf"
download_file "$BASE_URL/Jacket/Pocket/PK-1.bin" "$BASE_DIR/jacket/pocket/PK-1.bin"
download_file "$BASE_URL/Jacket/Pocket/ChestPocket.gltf" "$BASE_DIR/jacket/pocket/ChestPocket.gltf"
download_file "$BASE_URL/Jacket/Pocket/ChestPocket.bin" "$BASE_DIR/jacket/pocket/ChestPocket.bin"

# Vent
mkdir -p "$BASE_DIR/jacket/vent"
download_file "$BASE_URL/Jacket/Vent/SideVent.gltf" "$BASE_DIR/jacket/vent/SideVent.gltf"
download_file "$BASE_URL/Jacket/Vent/SideVent.bin" "$BASE_DIR/jacket/vent/SideVent.bin"

# Lining
mkdir -p "$BASE_DIR/jacket/lining/fullylined"
download_file "$BASE_URL/Jacket/Lining/Sleeve.gltf" "$BASE_DIR/jacket/lining/Sleeve.gltf"
download_file "$BASE_URL/Jacket/Lining/Sleeve.bin" "$BASE_DIR/jacket/lining/Sleeve.bin"
download_file "$BASE_URL/Jacket/Lining/FullyLined/Curved.gltf" "$BASE_DIR/jacket/lining/fullylined/Curved.gltf"
download_file "$BASE_URL/Jacket/Lining/FullyLined/Curved.bin" "$BASE_DIR/jacket/lining/fullylined/Curved.bin"

# Brand/Label
mkdir -p "$BASE_DIR/jacket/brand"
download_file "$BASE_URL/Jacket/Brand/Label.gltf" "$BASE_DIR/jacket/brand/Label.gltf"
download_file "$BASE_URL/Jacket/Brand/Label.bin" "$BASE_DIR/jacket/brand/Label.bin"

# PANT PARTS
echo ""
echo "=== PANT ==="

# Style
mkdir -p "$BASE_DIR/pant/style/1pleats"
download_file "$BASE_URL/Pant/Style/1Pleats/Dave.gltf" "$BASE_DIR/pant/style/1pleats/Dave.gltf"
download_file "$BASE_URL/Pant/Style/1Pleats/Dave.bin" "$BASE_DIR/pant/style/1pleats/Dave.bin"

# Waistband
mkdir -p "$BASE_DIR/pant/waistband"
download_file "$BASE_URL/Pant/Waistband/Square.gltf" "$BASE_DIR/pant/waistband/Square.gltf"
download_file "$BASE_URL/Pant/Waistband/Square.bin" "$BASE_DIR/pant/waistband/Square.bin"

# Waistband Button
mkdir -p "$BASE_DIR/pant/waistband/button"
download_file "$BASE_URL/Pant/Waistband/Button/S4.gltf" "$BASE_DIR/pant/waistband/button/S4.gltf"
download_file "$BASE_URL/Pant/Waistband/Button/S4.bin" "$BASE_DIR/pant/waistband/button/S4.bin"
download_file "$BASE_URL/Pant/Waistband/Button/S14.png" "$BASE_DIR/pant/waistband/button/S14.png"
download_file "$BASE_URL/Pant/Waistband/Button/Thread.gltf" "$BASE_DIR/pant/waistband/button/Thread.gltf"
download_file "$BASE_URL/Pant/Waistband/Button/Thread.bin" "$BASE_DIR/pant/waistband/button/Thread.bin"

# Beltloops
mkdir -p "$BASE_DIR/pant/beltloops"
download_file "$BASE_URL/Pant/Beltloops/Single.gltf" "$BASE_DIR/pant/beltloops/Single.gltf"
download_file "$BASE_URL/Pant/Beltloops/Single.bin" "$BASE_DIR/pant/beltloops/Single.bin"

# Pocket
mkdir -p "$BASE_DIR/pant/pocket"
download_file "$BASE_URL/Pant/Pocket/Slanted.gltf" "$BASE_DIR/pant/pocket/Slanted.gltf"
download_file "$BASE_URL/Pant/Pocket/Slanted.bin" "$BASE_DIR/pant/pocket/Slanted.bin"

# Back Pocket
mkdir -p "$BASE_DIR/pant/backpocket/right"
download_file "$BASE_URL/Pant/BackPocket/Right/Single.gltf" "$BASE_DIR/pant/backpocket/right/Single.gltf"
download_file "$BASE_URL/Pant/BackPocket/Right/Single.bin" "$BASE_DIR/pant/backpocket/right/Single.bin"

# Lining
mkdir -p "$BASE_DIR/pant/lining"
download_file "$BASE_URL/Pant/Lining/Dave.gltf" "$BASE_DIR/pant/lining/Dave.gltf"
download_file "$BASE_URL/Pant/Lining/Dave.bin" "$BASE_DIR/pant/lining/Dave.bin"

# TEXTURES
echo ""
echo "=== TEXTURES ==="
mkdir -p "$BASE_DIR/textures"
curl -sL -H "User-Agent: Mozilla/5.0" -H "Referer: https://dunniotailor.com/" \
  "https://t4t.vn/sites/default/files/fashion/meshes/3d/Render/Textures/default_lining.jpg" \
  -o "$BASE_DIR/textures/default_lining.jpg"
curl -sL -H "User-Agent: Mozilla/5.0" -H "Referer: https://dunniotailor.com/" \
  "https://t4t.vn/sites/default/files/fashion/meshes/3d/Render/Textures/label-vest.png" \
  -o "$BASE_DIR/textures/label-vest.png"
curl -sL -H "User-Agent: Mozilla/5.0" -H "Referer: https://dunniotailor.com/" \
  "https://dunniotailor.com/sites/default/files/environments/studio.env" \
  -o "$BASE_DIR/textures/studio.env"

echo ""
echo "=== Download Complete ==="
echo "Files saved to: $BASE_DIR"

# Count files
echo ""
echo "File count:"
find "$BASE_DIR" -name "*.gltf" | wc -l | xargs echo "GLTF files:"
find "$BASE_DIR" -name "*.bin" | wc -l | xargs echo "BIN files:"
find "$BASE_DIR" -name "*.png" -o -name "*.jpg" | wc -l | xargs echo "Texture files:"
