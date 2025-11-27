# Docker Setup for Suit Configurator

## Yêu cầu
- Docker
- Docker Compose

## Cài đặt và chạy

### 1. Copy file env
```bash
cp .env.docker .env
```

### 2. Build và chạy containers
```bash
docker-compose up -d --build
```

### 3. Cài đặt dependencies
```bash
# Cài đặt Composer dependencies
docker-compose exec app composer install

# Cài đặt NPM dependencies
docker-compose exec app npm install
```

### 4. Generate app key
```bash
docker-compose exec app php artisan key:generate
```

### 5. Chạy migrations
```bash
docker-compose exec app php artisan migrate --seed
```

### 6. Build assets
```bash
docker-compose exec app npm run build
```

### 7. Cấp quyền cho storage và cache
```bash
docker-compose exec app chmod -R 775 storage bootstrap/cache
docker-compose exec app chown -R suit:www-data storage bootstrap/cache
```

## Truy cập ứng dụng

- **Web**: http://localhost:8000
- **MySQL**: localhost:3307
  - Database: suit_configurator
  - Username: suit_user
  - Password: suit_password
  - Root password: root

## Các lệnh hữu ích

### Xem logs
```bash
docker-compose logs -f
docker-compose logs -f app
docker-compose logs -f nginx
docker-compose logs -f db
```

### Chạy artisan commands
```bash
docker-compose exec app php artisan [command]
```

### Truy cập container
```bash
docker-compose exec app bash
```

### Dừng containers
```bash
docker-compose down
```

### Dừng và xóa volumes
```bash
docker-compose down -v
```

### Rebuild containers
```bash
docker-compose up -d --build --force-recreate
```

## Cấu trúc thư mục

```
.
├── docker/
│   ├── nginx/
│   │   └── conf.d/
│   │       └── default.conf
│   ├── php/
│   │   └── local.ini
│   └── mysql/
│       └── my.cnf
├── docker-compose.yml
├── Dockerfile
└── .env.docker
```

## Lưu ý

- Code được mount qua volume nên mọi thay đổi sẽ được reflect ngay lập tức
- Database data được lưu trong named volume `db_data`
- PHP-FPM chạy với user `suit` (uid: 1000)
- Nginx lắng nghe trên port 8000
- MySQL lắng nghe trên port 3307 (để tránh conflict với MySQL local)
