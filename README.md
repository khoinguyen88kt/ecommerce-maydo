# Suit Configurator - Thiết kế Vest Nam May Đo

Hệ thống website may đo vest nam cao cấp với giao diện thiết kế trực quan kiểu layer-based (tương tự Hockerty, VestonDuyNguyen). Được xây dựng với Laravel 12, FilamentPHP, Tailwind CSS v4, và Alpine.js.

![Suit Configurator](https://via.placeholder.com/1200x600?text=Suit+Configurator+Preview)

## ✨ Tính năng

### Người dùng
- 🎨 **Thiết kế vest trực quan** - Xem trước vest với hệ thống layer PNG xếp chồng (10,000+ layer images)
- 🔄 **Xoay góc nhìn** - Xem mặt trước/sau
- 👔 **Tùy chỉnh đa dạng** - 17 loại tùy chọn với 47+ giá trị (ve áo, túi, nút, xẻ lưng, kiểu dáng)
- 🎭 **Bộ sưu tập vải** - 5 danh mục vải (Wool Ý, Anh, Linen, Cotton, Blend) với 20+ mẫu vải
- 🔗 **Chia sẻ thiết kế** - Link chia sẻ unique cho mỗi cấu hình
- 🛒 **Giỏ hàng & Thanh toán** - Đầy đủ quy trình e-commerce
- 💳 **Đa phương thức thanh toán** - MoMo, VNPay, chuyển khoản, COD

### Admin (FilamentPHP)
- 📊 **Dashboard quản lý** - Thống kê đơn hàng, doanh thu
- 👔 **Quản lý Suit Models** - 5 mẫu vest sẵn có, thêm/sửa/xóa
- 🧵 **Quản lý vải** - Danh mục, mã vải, giá, thành phần
- ⚙️ **Quản lý tùy chọn** - Ve áo, túi, nút, xẻ lưng
- 🖼️ **Quản lý Layer Images** - 9,995 layer images được seed sẵn
- 📦 **Quản lý đơn hàng** - Theo dõi, cập nhật trạng thái
- 📈 **Báo cáo** - Doanh thu, sản phẩm bán chạy

## 🚀 Cài đặt với Docker (Khuyến nghị)

### Yêu cầu hệ thống
- Docker Desktop
- Docker Compose
- 4GB RAM trở lên
- 10GB dung lượng đĩa trống

### Bước 1: Clone repository

```bash
git clone <repository-url>
cd suit-configurator
```

### Bước 2: Cấu hình môi trường

```bash
# Copy file môi trường Docker
cp .env.docker .env
```

File `.env` đã được cấu hình sẵn cho Docker:

```env
APP_NAME="Suit Configurator"
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=suit_configurator
DB_USERNAME=suit_user
DB_PASSWORD=suit_password
```

### Bước 3: Chạy setup script

```bash
chmod +x setup-docker.sh
./setup-docker.sh
```

Script sẽ tự động:
- ✅ Build Docker containers (PHP 8.3-FPM, Nginx, MySQL 8.0)
- ✅ Cài đặt Composer dependencies
- ✅ Generate application key
- ✅ Chạy database migrations
- ✅ Seed dữ liệu mẫu (option types, option values)
- ✅ Cài đặt NPM dependencies
- ✅ Build assets (Vite)
- ✅ Fix permissions

### Bước 4: Import dữ liệu đầy đủ (bao gồm layer images)

**Quan trọng**: Project đã có sẵn database SQLite (`database/database.sqlite`) chứa đầy đủ:
- 5 suit models
- 5 fabric categories
- 20 fabrics
- 17 option types với 47 option values
- **9,995 suit layer images** (quan trọng nhất!)
- 3 users mẫu

Chạy script migrate từ SQLite sang MySQL:

```bash
docker-compose exec app php migrate-sqlite-to-mysql.php
```

Output mong đợi:
```
Migrating table: suit_models
  Found 5 records
  ✅ Completed

Migrating table: fabric_categories
  Found 5 records
  ✅ Completed

Migrating table: fabrics
  Found 20 records
  ✅ Completed

Migrating table: option_types
  Found 17 records
  ✅ Completed

Migrating table: option_values
  Found 47 records
  ✅ Completed

Migrating table: suit_layers
  Found 9995 records
  Processed 9995/9995
  ✅ Completed

✅ Migration completed successfully!
```

### Bước 5: Truy cập ứng dụng

Sau khi setup xong:

- **Website**: http://localhost:8000
- **Admin Panel**: http://localhost:8000/admin
- **Thiết kế vest**: http://localhost:8000/thiet-ke-vest
- **MySQL**: localhost:3307 (từ host machine)

### Tài khoản admin mặc định
- Email: `admin@suitconfigurator.vn`
- Password: `password`

### Các lệnh Docker hữu ích

```bash
# Xem logs
docker-compose logs -f
docker-compose logs -f app
docker-compose logs -f nginx

# Chạy artisan commands
docker-compose exec app php artisan [command]

# Truy cập container
docker-compose exec app bash

# Dừng containers
docker-compose down

# Dừng và xóa volumes (reset database)
docker-compose down -v

# Rebuild containers
docker-compose up -d --build --force-recreate

# Restart services
docker-compose restart

# Xem trạng thái
docker-compose ps
```

### Cấu trúc Docker

```
suit-configurator/
├── docker-compose.yml           # Docker services configuration
├── Dockerfile                   # PHP 8.3 FPM image
├── setup-docker.sh             # Automated setup script
├── migrate-sqlite-to-mysql.php # Data migration script
├── .env.docker                 # Docker environment template
└── docker/
    ├── nginx/
    │   └── conf.d/
    │       └── default.conf    # Nginx configuration
    ├── php/
    │   └── local.ini          # PHP configuration
    └── mysql/
        └── my.cnf             # MySQL configuration
```

## 🛠️ Cài đặt thủ công (không dùng Docker)

### Yêu cầu hệ thống
- PHP >= 8.3
- Composer
- Node.js >= 18
- MySQL >= 8.0
- PHP Extensions: intl, pdo_mysql, mbstring, exif, pcntl, bcmath, gd, zip

### Các bước cài đặt

```bash
# 1. Cài đặt dependencies
composer install
npm install

# 2. Cấu hình môi trường
cp .env.example .env
php artisan key:generate

# 3. Cấu hình database trong .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=suit_configurator
DB_USERNAME=root
DB_PASSWORD=

# 4. Tạo database
mysql -u root -p -e "CREATE DATABASE suit_configurator CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# 5. Chạy migrations
php artisan migrate

# 6. Import dữ liệu từ SQLite
# Sửa file migrate-sqlite-to-mysql.php để dùng local MySQL
php migrate-sqlite-to-mysql.php

# 7. Build assets
npm run build

# 8. Chạy server
php artisan serve
```

## 📁 Cấu trúc thư mục

```
suit-configurator/
├── app/
│   ├── Filament/           # Admin panel resources
│   │   └── Resources/      # Filament CRUD resources
│   ├── Http/
│   │   └── Controllers/    # Web controllers
│   └── Models/             # Eloquent models
├── database/
│   ├── migrations/         # Database migrations
│   └── seeders/           # Seeders với dữ liệu mẫu
├── public/
│   └── images/            # Hình ảnh layer, vải, models
│       └── configurator/  # Layer images cho configurator
├── resources/
│   ├── css/               # Tailwind CSS
│   ├── js/                # Alpine.js
│   └── views/             # Blade templates
│       ├── configurator/  # Trang thiết kế vest
│       ├── cart/          # Giỏ hàng
│       ├── checkout/      # Thanh toán
│       └── layouts/       # Layout chung
└── routes/
    └── web.php            # Routes
```

## 🖼️ Hệ thống Layer Images

Vest được hiển thị bằng cách xếp chồng các hình PNG trong suốt:

```
public/images/configurator/
├── base/
│   ├── front.png          # Mannequin mặt trước
│   ├── side.png           # Mannequin mặt bên
│   └── back.png           # Mannequin mặt sau
├── shirt/
│   ├── front.png
│   ├── side.png
│   └── back.png
└── models/
    └── vest-2-nut-classic/
        ├── jacket/
        │   └── {fabric_code}/
        │       ├── front.png
        │       ├── side.png
        │       └── back.png
        ├── lapel/
        │   └── {lapel_style}/
        │       └── {fabric_code}/
        │           └── front.png
        ├── pocket/
        │   └── {pocket_style}/
        │       └── {fabric_code}/
        │           └── front.png
        └── vent/
            └── {vent_style}/
                └── {fabric_code}/
                    └── back.png
```

## 📊 Dữ liệu có sẵn sau khi setup

Sau khi chạy migration script, database sẽ có:

| Loại dữ liệu | Số lượng | Mô tả |
|--------------|----------|--------|
| Suit Models | 5 | Vest 2 nút Classic, 3 nút Business, Double Breasted, Slim Fit, Tuxedo |
| Fabric Categories | 5 | Wool Ý, Wool Anh, Linen, Cotton, Blend |
| Fabrics | 20 | Các mẫu vải trong từng danh mục |
| Option Types | 17 | Chọn bộ, Kiểu vest, Dáng vest, Ve áo, Nút, Túi, v.v. |
| Option Values | 47 | Các giá trị cụ thể cho mỗi option type |
| **Suit Layers** | **9,995** | **Layer images PNG cho 3D preview** |
| Users | 3 | Admin và 2 test users |

## 🛠️ Công nghệ sử dụng

| Thành phần | Công nghệ | Version |
|------------|-----------|---------|
| Backend | Laravel | 12.x |
| Admin Panel | FilamentPHP | 3.2 |
| Frontend | Alpine.js, Blade | 3.x |
| Styling | Tailwind CSS | v4 |
| Database | MySQL | 8.0 |
| Build Tool | Vite | Latest |
| Containerization | Docker, Docker Compose | Latest |
| PHP | PHP-FPM | 8.3 |
| Web Server | Nginx | Alpine |

## 📝 API Endpoints

### Configurator

```http
POST /api/configurator/layers    # Lấy layer images
POST /api/configurator/save      # Lưu cấu hình
```

### Cart

```http
GET  /api/cart/count             # Đếm sản phẩm trong giỏ
POST /api/cart/add               # Thêm vào giỏ
PATCH /api/cart/item/{id}        # Cập nhật số lượng
DELETE /api/cart/item/{id}       # Xóa khỏi giỏ
POST /api/cart/discount          # Áp dụng mã giảm giá
```

## 🌐 SEO Features

- ✅ Meta tags đầy đủ (title, description, keywords)
- ✅ Open Graph & Twitter Cards
- ✅ JSON-LD Structured Data
- ✅ Canonical URLs
- ✅ Vietnamese language URLs (`/thiet-ke-vest`, `/gio-hang`)
- ✅ Semantic HTML5

## 🔧 Tùy chỉnh

### Thêm kiểu vest mới

1. Vào Admin Panel > Kiểu Vest > Thêm mới
2. Upload layer images vào `public/images/configurator/models/{slug}/`
3. Cấu hình layers trong database hoặc theo naming convention

### Thêm vải mới

1. Vào Admin Panel > Quản lý Vải > Thêm mới
2. Upload hình swatch
3. Thiết lập mã vải, giá, thành phần

### Thêm tùy chọn mới

1. Vào Admin Panel > Tùy chọn > Thêm loại tùy chọn
2. Thêm các giá trị cho tùy chọn
3. Tạo layer images tương ứng

## 🔒 Bảo mật

- CSRF protection
- XSS prevention via Blade escaping
- SQL injection prevention via Eloquent ORM
- Input validation
- Rate limiting (có thể thêm)

## 📞 Hỗ trợ

Nếu bạn gặp vấn đề hoặc có câu hỏi:
- Tạo issue trên GitHub
- Email: support@suitconfigurator.vn

## 📄 License

MIT License - Xem file [LICENSE](LICENSE) để biết thêm chi tiết.

---

Made with ❤️ for Vietnamese tailoring industry
