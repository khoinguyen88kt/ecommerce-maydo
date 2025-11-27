# Suit Configurator - Thiết kế Vest Nam May Đo

Hệ thống website may đo vest nam cao cấp với giao diện thiết kế trực quan kiểu layer-based (tương tự Hockerty, VestonDuyNguyen). Được xây dựng với Laravel 12, FilamentPHP, Tailwind CSS v4, và Alpine.js.

![Suit Configurator](https://via.placeholder.com/1200x600?text=Suit+Configurator+Preview)

## ✨ Tính năng

### Người dùng
- 🎨 **Thiết kế vest trực quan** - Xem trước vest với hệ thống layer PNG xếp chồng
- 🔄 **Xoay góc nhìn** - Xem trước, bên, sau
- 👔 **Tùy chỉnh đa dạng** - Ve áo, túi, nút, xẻ lưng, kiểu dáng
- 🎭 **Bộ sưu tập vải** - Wool Ý, Anh, Linen, Cotton, Blend
- 🔗 **Chia sẻ thiết kế** - Link chia sẻ unique cho mỗi cấu hình
- 🛒 **Giỏ hàng & Thanh toán** - Đầy đủ quy trình e-commerce
- 💳 **Đa phương thức thanh toán** - MoMo, VNPay, chuyển khoản, COD

### Admin (FilamentPHP)
- 📊 **Dashboard quản lý** - Thống kê đơn hàng, doanh thu
- 👔 **Quản lý Suit Models** - Thêm/sửa/xóa kiểu vest
- 🧵 **Quản lý vải** - Danh mục, mã vải, giá, thành phần
- ⚙️ **Quản lý tùy chọn** - Ve áo, túi, nút, xẻ lưng
- 📦 **Quản lý đơn hàng** - Theo dõi, cập nhật trạng thái
- 📈 **Báo cáo** - Doanh thu, sản phẩm bán chạy

## 🚀 Cài đặt

### Yêu cầu hệ thống
- PHP >= 8.2
- Composer
- Node.js >= 18
- MySQL >= 8.0
- npm hoặc yarn

### Bước 1: Clone và cài đặt dependencies

```bash
# Clone repository
cd suit-configurator

# Cài đặt PHP packages
composer install

# Cài đặt Node packages
npm install
```

### Bước 2: Cấu hình môi trường

```bash
# Copy file môi trường
cp .env.example .env

# Generate app key
php artisan key:generate
```

Chỉnh sửa file `.env`:

```env
APP_NAME="Suit Configurator"
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=suit_configurator
DB_USERNAME=root
DB_PASSWORD=your_password
```

### Bước 3: Thiết lập database

```bash
# Tạo database
mysql -u root -p -e "CREATE DATABASE suit_configurator CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Chạy migrations
php artisan migrate

# Seed dữ liệu mẫu
php artisan db:seed
```

### Bước 4: Build assets

```bash
# Development
npm run dev

# Production
npm run build
```

### Bước 5: Chạy server

```bash
php artisan serve
```

Truy cập:
- **Website**: http://localhost:8000
- **Admin Panel**: http://localhost:8000/admin

### Tài khoản admin mặc định
- Email: `admin@suitconfigurator.vn`
- Password: `password`

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

## 🛠️ Công nghệ sử dụng

| Thành phần | Công nghệ |
|------------|-----------|
| Backend | Laravel 12 |
| Admin Panel | FilamentPHP 3.2 |
| Frontend | Alpine.js 3, Blade |
| Styling | Tailwind CSS v4 |
| Database | MySQL 8 |
| Build Tool | Vite |

## 📝 API Endpoints

### Configurator
```
POST /api/configurator/layers    # Lấy layer images
POST /api/configurator/save      # Lưu cấu hình
```

### Cart
```
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
