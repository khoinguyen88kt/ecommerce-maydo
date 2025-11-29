# Database Export - Suit Configurator

## Thông tin file export

- **File**: `suit_configurator_full_20251129_161728.sql`
- **Database**: suit_configurator
- **MySQL Version**: 8.0
- **Ngày export**: 29/11/2025

## Cấu trúc database bao gồm

### 1. Core Tables
- `users` - Người dùng hệ thống
- `cache`, `cache_locks` - Laravel cache
- `sessions` - Sessions
- `jobs`, `job_batches`, `failed_jobs` - Queue jobs

### 2. Suit Configuration Tables (2D Layer System)
- `fabric_categories` - Danh mục vải (5 categories)
- `fabrics` - Danh sách vải (30+ fabrics với texture images)
- `suit_models` - Các mẫu vest
- `option_types` - Loại tùy chọn (17 types)
- `option_values` - Giá trị tùy chọn (50+ values)
- `suit_model_option_types` - Liên kết model với options
- `suit_layers` - Layers ảnh cho configurator
- `suit_configurations` - Cấu hình đã lưu

### 3. E-commerce Tables
- `carts`, `cart_items` - Giỏ hàng
- `orders`, `order_items` - Đơn hàng
- `discount_codes` - Mã giảm giá

### 4. 3D Configurator Tables (New Modular System)
- `product_types_3d` - Loại sản phẩm 3D (Jacket, Pant, Vest)
- `part_categories_3d` - Danh mục parts (Style, Lapel, Pocket...)
- `part_options_3d` - Options cho mỗi category
- `part_sub_options_3d` - Sub-options (Width, Buttons...)
- `model_meshes_3d` - Mesh definitions
- `model_configurations_3d` - Saved configurations
- `model_cache_3d` - Performance cache
- `three_d_models` - 3D models linked to suit_models

### 5. Permission Tables (Spatie)
- `roles`, `permissions`
- `model_has_roles`, `model_has_permissions`
- `role_has_permissions`

## Hướng dẫn Import

### Option 1: Import qua Docker

```bash
# Copy file vào container và import
docker cp database/exports/suit_configurator_full_20251129_161728.sql suit-configurator-db:/tmp/

# Import
docker compose exec db mysql -u suit_user -psuit_password suit_configurator < /tmp/suit_configurator_full_20251129_161728.sql

# Hoặc từ host machine
docker compose exec -T db mysql -u suit_user -psuit_password suit_configurator < database/exports/suit_configurator_full_20251129_161728.sql
```

### Option 2: Import trực tiếp (không Docker)

```bash
# Tạo database nếu chưa có
mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS suit_configurator CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Import
mysql -u root -p suit_configurator < database/exports/suit_configurator_full_20251129_161728.sql
```

### Option 3: Dùng Laravel artisan (fresh migrate + seed)

```bash
# Nếu muốn migrate fresh và seed lại
php artisan migrate:fresh --seed

# Hoặc chạy seeders riêng lẻ
php artisan db:seed --class=DatabaseSeeder
php artisan db:seed --class=RolePermissionSeeder
php artisan db:seed --class=AdminUserSeeder
php artisan db:seed --class=ThreeDConfiguratorSeeder
```

## Tài khoản mặc định

| Email | Password | Role |
|-------|----------|------|
| admin@example.com | password | admin |
| admin@suitconfigurator.vn | password | user |
| test@example.com | password | user |

## Dữ liệu sample

### Fabric Categories (5)
1. Italian Wool - Vải Wool Ý
2. British Wool - Vải Wool Anh
3. Linen - Vải Linen
4. Cotton - Vải Cotton
5. Blended Fabrics - Vải Blend

### Option Types (17 types cho Jacket + Pants)
**Jacket Options:**
- Suit Type (Chọn Bộ)
- Jacket Style (Kiểu Vest)
- Jacket Fit (Dáng Vest)
- Lapel Type (Ve Áo)
- Jacket Buttons (Nút Áo)
- Breast Pocket (Túi Ngực)
- Hip Pockets (Túi Hông)
- Hip Pocket Style (Kiểu Túi Hông)
- Back Vent (Thân Sau)
- Sleeve Buttons (Cúc Tay)
- Sleeve Vent Style (Kiểu Sẻ Tay)

**Pants Options:**
- Pants Fit (Dáng Quần)
- Pants Pleats (Thân Trước Quần)
- Front Pockets (Túi Thân Trước)
- Back Pockets (Túi Thân Sau)
- Back Pocket Style (Kiểu Túi Sau)
- Pants Cuff (Gấu Quần)

### 3D Product Types (3)
1. Jacket - Áo Vest
2. Pant - Quần Vest
3. Vest - Áo Gile

### 3D Part Categories
**Jacket:** Style, Lapel, Pocket, Vent, Sleeve, Lining
**Pant:** Style, Pleats, Hem
**Vest:** Style, Back

## Cấu hình .env cần thiết

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=suit_configurator
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

## Notes

- File export sử dụng `--complete-insert --skip-extended-insert` để dễ đọc và debug
- Tất cả foreign keys được tắt khi import (`SET FOREIGN_KEY_CHECKS=0`)
- Encoding: UTF8MB4 Unicode CI
- Nếu gặp lỗi charset, đảm bảo MySQL server hỗ trợ utf8mb4
