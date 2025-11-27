#!/bin/bash

set -e

echo "🚀 Setting up Suit Configurator with Docker..."

# Check if Docker is running
if ! docker info > /dev/null 2>&1; then
  echo "❌ Docker is not running. Please start Docker first."
  exit 1
fi

# Stop and remove existing containers
echo "🧹 Cleaning up existing containers..."
docker-compose down -v 2>/dev/null || true

# Copy .env file
echo "📝 Setting up .env file..."
if [ ! -f .env ]; then
  cp .env.docker .env
  echo "✅ Created .env file"
else
  echo "⚠️  .env file already exists, skipping..."
fi

# Build and start containers
echo "🐳 Building and starting Docker containers..."
docker-compose up -d --build

# Wait for MySQL to be ready
echo "⏳ Waiting for MySQL to be ready..."
sleep 10
until docker-compose exec -T db mysqladmin ping -h localhost -u root -proot --silent; do
  echo "Waiting for database connection..."
  sleep 2
done
echo "✅ MySQL is ready!"

# Install Composer dependencies
echo "📦 Installing Composer dependencies..."
docker-compose exec -T app composer install --no-interaction --prefer-dist --optimize-autoloader

# Generate application key
echo "🔑 Generating application key..."
docker-compose exec -T app php artisan key:generate --force

# Run migrations
echo "🗄️  Running database migrations..."
docker-compose exec -T app php artisan migrate --force

# Run seeders (optional - comment out if not needed)
echo "🌱 Running database seeders..."
docker-compose exec -T app php artisan db:seed --force || echo "⚠️  No seeders to run"

# Install NPM dependencies
echo "📦 Installing NPM dependencies..."
docker-compose exec -T app npm install

# Build assets
echo "🎨 Building assets..."
docker-compose exec -T app npm run build

# Fix permissions
echo "🔧 Fixing permissions..."
docker-compose exec -T app chmod -R 775 storage bootstrap/cache
docker-compose exec -T app chown -R suit:www-data storage bootstrap/cache || true

# Clear caches
echo "🧹 Clearing caches..."
docker-compose exec -T app php artisan config:clear
docker-compose exec -T app php artisan cache:clear
docker-compose exec -T app php artisan view:clear

echo ""
echo "✅ Setup completed successfully!"
echo ""
echo "🌐 Application is running at: http://localhost:8000"
echo "🗄️  MySQL is available at: localhost:3307"
echo "   - Database: suit_configurator"
echo "   - Username: suit_user"
echo "   - Password: suit_password"
echo ""
echo "📋 Useful commands:"
echo "   - View logs: docker-compose logs -f"
echo "   - Stop: docker-compose down"
echo "   - Restart: docker-compose restart"
echo "   - Run artisan: docker-compose exec app php artisan [command]"
echo ""
