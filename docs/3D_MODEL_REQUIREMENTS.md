# 3D Model Requirements for Suit Configurator

## Current Database Structure

Hệ thống hiện tại có **58 parts** được organize thành các nhóm:

### 1. Jacket (14 parts)
- `jacket_body` - Thân áo chính (front)
- `jacket_back_panel` / `jacket_back_body` - Mặt sau áo
- `jacket_back_collar` - Cổ áo phía sau
- `breast_pocket` - Túi ngực
- `lapel` / `lapel_opt` - Ve áo
- `collar` / `collar_opt` - Cổ áo
- `pocket_right` / `pocket_right_opt` - Túi phải
- `pocket_left` / `pocket_left_opt` - Túi trái
- `sleeve_right` / `sleeve_right_inner` - Tay áo phải
- `sleeve_left` / `sleeve_left_inner` - Tay áo trái
- `sleeve_back_right` / `sleeve_back_left` - Tay áo phía sau
- `sleeve_left_buttons` - Nút tay áo
- `buttons_body` / `buttons_body_opt` - Nút áo thân
- `buttons_sleeve` / `buttons_sleeve_opt` - Nút tay áo

### 2. Pants (11 parts)
- `pants_body` / `pants_body_opt` - Thân quần (front)
- `pants_back_body` - Thân quần (back)
- `pants_belt` / `pants_back_belt` - Thắt lưng
- `pants_pocket` / `pants_pocket_opt` - Túi quần (front)
- `pants_back_pocket` / `pants_back_pocket_opt` / `pants_back_pocket_left_opt` - Túi sau
- `pants_buttons` / `pants_back_buttons` - Nút quần
- `pants_cuff_opt` - Gấu quần

### 3. Waistcoat/Vest (9 parts)
- `waistcoat_body` / `waistcoat_body_top` - Thân áo vest
- `waistcoat_back` - Mặt sau vest
- `waistcoat_edge` - Viền vest
- `waistcoat_pocket` - Túi vest
- `waistcoat_buttons` - Nút vest
- `waistcoat_lining_back` - Lớp lót phía sau
- `waistcoat_shirt_base` / `waistcoat_shirt_shadow` / `waistcoat_shirt_top` - Áo sơ mi bên trong
- `waistcoat_back_shirt` / `waistcoat_back_shadow` - Áo sơ mi phía sau

### 4. Accessories (6 parts)
- `shirt_base` / `shirt_back` - Áo sơ mi
- `shirt_shadow` / `shirt_back_shadow` - Bóng áo sơ mi
- `shirt_cuff` - Măng séc áo
- `tie` - Cà vạt
- `shoes` / `shoes_back` - Giày

## 3D Model Requirements

### Minimum Requirements
1. **Format**: GLB (GLTF Binary)
2. **Separated Meshes**: Mỗi part phải là 1 mesh riêng với tên rõ ràng
3. **Naming Convention**: Tên mesh nên match với parts trong database
4. **Scale**: Human-sized (height ~1.7-1.8 units)
5. **Origin**: Centered at origin (0,0,0)
6. **Facing**: Front view = -Z direction

### Optimal Structure Example
```
Scene
├── Jacket_Body (jacket_body)
├── Jacket_Lapel_Left (lapel)
├── Jacket_Lapel_Right (lapel)
├── Jacket_Collar (collar)
├── Jacket_Sleeve_Left (sleeve_left)
├── Jacket_Sleeve_Right (sleeve_right)
├── Jacket_Pocket_Left (pocket_left)
├── Jacket_Pocket_Right (pocket_right)
├── Pants_Body (pants_body)
├── Pants_Pocket_Left (pants_pocket)
├── Pants_Pocket_Right (pants_pocket)
└── ...
```

## Where to Find/Create Models

### Option 1: Purchase from Marketplaces
- **CGTrader**: Search "business suit rigged separated parts"
- **TurboSquid**: "men suit 3d model modular"
- **Sketchfab**: Filter by "Downloadable" + "GLB/GLTF"
- Cost: $20-$100 USD

### Option 2: Create in Blender (Recommended)
1. Use base mesh from MakeHuman or similar
2. Model suit parts separately
3. Name each mesh according to parts list
4. Export as GLB with these settings:
   - Format: GLTF Binary (.glb)
   - Include: Selected Objects
   - Transform: +Y Up
   - Geometry: Apply Modifiers

### Option 3: Modify Existing Models
1. Import FBX/OBJ suit model to Blender
2. Separate by loose parts: Edit Mode > P > By Loose Parts
3. Rename meshes to match parts list
4. Re-export as GLB

## Testing Workflow

1. **Place model in**: `storage/app/3d-models/`
2. **Create parts mapping** manually or use inspect script
3. **Upload via Filament admin** at `/admin/three-d-models`
4. **Fill parts mapping** JSON:
```json
{
  "Jacket_Body_Mesh": "jacket_body",
  "Lapel_Left": "lapel",
  "Sleeve_R": "sleeve_right",
  ...
}
```
5. **Click "Generate Layers"** button
6. **Check output** in `public/storage/generated-layers/`

## Current Test Models

- `test-suit.glb` (479KB) - CesiumMan: Animated character, có texture
- `box-test.glb` (1.6KB) - Simple box: Test basic loading
- `helmet-test.glb` (3.6MB) - Damaged Helmet: Complex with textures

**Note**: Các model test này không có structure phù hợp. Cần tìm/tạo model chuyên dụng cho suit configurator.

## Recommended Next Steps

1. **Short term**: Tạo simple box models để test workflow
2. **Medium term**: Thuê freelancer trên Fiverr tạo 1 suit model ($50-100)
3. **Long term**: Học Blender để tự tạo và customize các variants

## Example: Creating Simple Test in Blender

```python
# Blender Python script to create simple suit parts
import bpy

# Clear scene
bpy.ops.object.select_all(action='SELECT')
bpy.ops.object.delete()

# Create jacket body
bpy.ops.mesh.primitive_cube_add(location=(0, 0, 1))
jacket = bpy.context.active_object
jacket.name = "jacket_body"
jacket.scale = (0.4, 0.2, 0.6)

# Create sleeve left
bpy.ops.mesh.primitive_cube_add(location=(-0.5, 0, 0.8))
sleeve_l = bpy.context.active_object
sleeve_l.name = "sleeve_left"
sleeve_l.scale = (0.15, 0.15, 0.5)

# Create sleeve right
bpy.ops.mesh.primitive_cube_add(location=(0.5, 0, 0.8))
sleeve_r = bpy.context.active_object
sleeve_r.name = "sleeve_right"
sleeve_r.scale = (0.15, 0.15, 0.5)

# Create pants
bpy.ops.mesh.primitive_cube_add(location=(0, 0, 0.2))
pants = bpy.context.active_object
pants.name = "pants_body"
pants.scale = (0.35, 0.2, 0.5)

# Export
bpy.ops.export_scene.gltf(filepath="/tmp/simple-suit.glb")
```

Paste vào Blender Python Console và chạy để tạo test model đơn giản.
