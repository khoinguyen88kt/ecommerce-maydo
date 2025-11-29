#!/bin/bash

# Script to download 3D models from Dunnio Tailor
# These are GLTF models with separate BIN files

BASE_DIR="/Volumes/data_t7/workspace/laravel/ecommerce_maydo/suit-configurator/storage/app/3d-models/dunnio"
BASE_URL="https://t4t.vn/sites/default/files/fashion/meshes/3d/Render/Models"

USER_AGENT="Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36"
REFERER="https://dunniotailor.com/custom-suits"

download_file() {
  local url=$1
  local output=$2

  echo "Downloading: $url"
  curl -sL -o "$output" \
    -H "User-Agent: $USER_AGENT" \
    -H "Referer: $REFERER" \
    "$url"
}

# Create directories
mkdir -p "$BASE_DIR/jacket/front"
mkdir -p "$BASE_DIR/jacket/lapel"
mkdir -p "$BASE_DIR/jacket/sleeve"
mkdir -p "$BASE_DIR/jacket/pocket"
mkdir -p "$BASE_DIR/jacket/vent"
mkdir -p "$BASE_DIR/jacket/lining"
mkdir -p "$BASE_DIR/jacket/brand"
mkdir -p "$BASE_DIR/pant/style"
mkdir -p "$BASE_DIR/pant/waistband"
mkdir -p "$BASE_DIR/pant/pocket"
mkdir -p "$BASE_DIR/pant/lining"
mkdir -p "$BASE_DIR/textures"

echo "=== Downloading Jacket Models ==="

# Jacket Front
download_file "$BASE_URL/Jacket/Front/Bottom/2Button/Curved.gltf" "$BASE_DIR/jacket/front/Curved.gltf"
download_file "$BASE_URL/Jacket/Front/Bottom/2Button/Curved.bin" "$BASE_DIR/jacket/front/Curved.bin"
download_file "$BASE_URL/Jacket/Front/Button/2Button/S4.gltf" "$BASE_DIR/jacket/front/Button_S4.gltf"
download_file "$BASE_URL/Jacket/Front/Button/2Button/S4.bin" "$BASE_DIR/jacket/front/Button_S4.bin"
download_file "$BASE_URL/Jacket/Front/Button/2Button/S14.png" "$BASE_DIR/jacket/front/Button_S14.png"
download_file "$BASE_URL/Jacket/Front/Thread/2Button.gltf" "$BASE_DIR/jacket/front/Thread_2Button.gltf"
download_file "$BASE_URL/Jacket/Front/Thread/2Button.bin" "$BASE_DIR/jacket/front/Thread_2Button.bin"

# Jacket Lapel
download_file "$BASE_URL/Jacket/Lapel/Larger/Upper/2Button/CL1.gltf" "$BASE_DIR/jacket/lapel/Upper_CL1.gltf"
download_file "$BASE_URL/Jacket/Lapel/Larger/Upper/2Button/CL1.bin" "$BASE_DIR/jacket/lapel/Upper_CL1.bin"
download_file "$BASE_URL/Jacket/Lapel/Larger/Lower/2Button/CL1.gltf" "$BASE_DIR/jacket/lapel/Lower_CL1.gltf"
download_file "$BASE_URL/Jacket/Lapel/Larger/Lower/2Button/CL1.bin" "$BASE_DIR/jacket/lapel/Lower_CL1.bin"

# Jacket Sleeve
download_file "$BASE_URL/Jacket/Sleeve/Sleeve.gltf" "$BASE_DIR/jacket/sleeve/Sleeve.gltf"
download_file "$BASE_URL/Jacket/Sleeve/Sleeve.bin" "$BASE_DIR/jacket/sleeve/Sleeve.bin"
download_file "$BASE_URL/Jacket/Sleeve/Standard/4Button/S4.gltf" "$BASE_DIR/jacket/sleeve/4Button_S4.gltf"
download_file "$BASE_URL/Jacket/Sleeve/Standard/4Button/S4.bin" "$BASE_DIR/jacket/sleeve/4Button_S4.bin"
download_file "$BASE_URL/Jacket/Sleeve/Standard/4Button/S14.png" "$BASE_DIR/jacket/sleeve/4Button_S14.png"
download_file "$BASE_URL/Jacket/Sleeve/Standard/LastButton/S4.gltf" "$BASE_DIR/jacket/sleeve/LastButton_S4.gltf"
download_file "$BASE_URL/Jacket/Sleeve/Standard/LastButton/S4.bin" "$BASE_DIR/jacket/sleeve/LastButton_S4.bin"
download_file "$BASE_URL/Jacket/Sleeve/Standard/LastButton/S14.png" "$BASE_DIR/jacket/sleeve/LastButton_S14.png"
download_file "$BASE_URL/Jacket/Sleeve/Standard/Thread/4Button.gltf" "$BASE_DIR/jacket/sleeve/Thread_4Button.gltf"
download_file "$BASE_URL/Jacket/Sleeve/Standard/Thread/4Button.bin" "$BASE_DIR/jacket/sleeve/Thread_4Button.bin"
download_file "$BASE_URL/Jacket/Sleeve/Standard/Thread/LastThread.gltf" "$BASE_DIR/jacket/sleeve/Thread_Last.gltf"
download_file "$BASE_URL/Jacket/Sleeve/Standard/Thread/LastThread.bin" "$BASE_DIR/jacket/sleeve/Thread_Last.bin"

# Jacket Pocket
download_file "$BASE_URL/Jacket/Pocket/PK-1.gltf" "$BASE_DIR/jacket/pocket/PK-1.gltf"
download_file "$BASE_URL/Jacket/Pocket/PK-1.bin" "$BASE_DIR/jacket/pocket/PK-1.bin"
download_file "$BASE_URL/Jacket/Pocket/ChestPocket.gltf" "$BASE_DIR/jacket/pocket/ChestPocket.gltf"
download_file "$BASE_URL/Jacket/Pocket/ChestPocket.bin" "$BASE_DIR/jacket/pocket/ChestPocket.bin"

# Jacket Vent
download_file "$BASE_URL/Jacket/Vent/SideVent.gltf" "$BASE_DIR/jacket/vent/SideVent.gltf"
download_file "$BASE_URL/Jacket/Vent/SideVent.bin" "$BASE_DIR/jacket/vent/SideVent.bin"

# Jacket Lining
download_file "$BASE_URL/Jacket/Lining/Sleeve.gltf" "$BASE_DIR/jacket/lining/Sleeve.gltf"
download_file "$BASE_URL/Jacket/Lining/Sleeve.bin" "$BASE_DIR/jacket/lining/Sleeve.bin"
download_file "$BASE_URL/Jacket/Lining/FullyLined/Curved.gltf" "$BASE_DIR/jacket/lining/FullyLined_Curved.gltf"
download_file "$BASE_URL/Jacket/Lining/FullyLined/Curved.bin" "$BASE_DIR/jacket/lining/FullyLined_Curved.bin"

# Jacket Brand
download_file "$BASE_URL/Jacket/Brand/Label.gltf" "$BASE_DIR/jacket/brand/Label.gltf"
download_file "$BASE_URL/Jacket/Brand/Label.bin" "$BASE_DIR/jacket/brand/Label.bin"

echo "=== Downloading Pant Models ==="

# Pant Style
download_file "$BASE_URL/Pant/Style/1Pleats/Dave.gltf" "$BASE_DIR/pant/style/Dave.gltf"
download_file "$BASE_URL/Pant/Style/1Pleats/Dave.bin" "$BASE_DIR/pant/style/Dave.bin"

# Pant Waistband
download_file "$BASE_URL/Pant/Waistband/Square.gltf" "$BASE_DIR/pant/waistband/Square.gltf"
download_file "$BASE_URL/Pant/Waistband/Square.bin" "$BASE_DIR/pant/waistband/Square.bin"
download_file "$BASE_URL/Pant/Waistband/Button/S4.gltf" "$BASE_DIR/pant/waistband/Button_S4.gltf"
download_file "$BASE_URL/Pant/Waistband/Button/S4.bin" "$BASE_DIR/pant/waistband/Button_S4.bin"
download_file "$BASE_URL/Pant/Waistband/Button/S14.png" "$BASE_DIR/pant/waistband/Button_S14.png"
download_file "$BASE_URL/Pant/Waistband/Button/Thread.gltf" "$BASE_DIR/pant/waistband/Thread.gltf"
download_file "$BASE_URL/Pant/Waistband/Button/Thread.bin" "$BASE_DIR/pant/waistband/Thread.bin"

# Pant Beltloops
download_file "$BASE_URL/Pant/Beltloops/Single.gltf" "$BASE_DIR/pant/waistband/Beltloops_Single.gltf"
download_file "$BASE_URL/Pant/Beltloops/Single.bin" "$BASE_DIR/pant/waistband/Beltloops_Single.bin"

# Pant Pocket
download_file "$BASE_URL/Pant/Pocket/Slanted.gltf" "$BASE_DIR/pant/pocket/Slanted.gltf"
download_file "$BASE_URL/Pant/Pocket/Slanted.bin" "$BASE_DIR/pant/pocket/Slanted.bin"
download_file "$BASE_URL/Pant/BackPocket/Right/Single.gltf" "$BASE_DIR/pant/pocket/BackPocket_Single.gltf"
download_file "$BASE_URL/Pant/BackPocket/Right/Single.bin" "$BASE_DIR/pant/pocket/BackPocket_Single.bin"

# Pant Lining
download_file "$BASE_URL/Pant/Lining/Dave.gltf" "$BASE_DIR/pant/lining/Dave.gltf"
download_file "$BASE_URL/Pant/Lining/Dave.bin" "$BASE_DIR/pant/lining/Dave.bin"

echo "=== Downloading Textures ==="

# Textures from dunniotailor.com
download_file "https://dunniotailor.com/sites/all/themes/t4t_theme/img/3d/textures/default_lining.jpg" "$BASE_DIR/textures/default_lining.jpg"
download_file "https://dunniotailor.com//sites/all/themes/t4t_theme/img/3d/textures/Label/label-vest.png" "$BASE_DIR/textures/label-vest.png"
download_file "https://dunniotailor.com/sites/default/files/environments/studio.env" "$BASE_DIR/textures/studio.env"
download_file "https://dunniotailor.com/sites/all/themes/t4t_theme/img/3d/scene_bg.jpg" "$BASE_DIR/textures/scene_bg.jpg"

echo "=== Download Complete ==="
echo "Files saved to: $BASE_DIR"
ls -la "$BASE_DIR/jacket/"
ls -la "$BASE_DIR/pant/"
