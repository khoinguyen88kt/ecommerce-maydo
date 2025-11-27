#!/bin/bash

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

echo -e "${BLUE}════════════════════════════════════════════════════════════${NC}"
echo -e "${BLUE}     SUIT CONFIGURATOR - Setup Script                        ${NC}"
echo -e "${BLUE}════════════════════════════════════════════════════════════${NC}"
echo ""

# Check if we're in the right directory
if [ ! -f "artisan" ]; then
    echo -e "${RED}Error: artisan file not found. Please run this script from the project root.${NC}"
    exit 1
fi

echo -e "${YELLOW}Step 1: Checking PHP version...${NC}"
PHP_VERSION=$(php -v | head -n 1 | cut -d " " -f 2 | cut -d "." -f 1,2)
echo -e "  PHP version: ${GREEN}$PHP_VERSION${NC}"

echo ""
echo -e "${YELLOW}Step 2: Installing Composer dependencies...${NC}"
composer install --no-interaction

echo ""
echo -e "${YELLOW}Step 3: Setting up environment file...${NC}"
if [ ! -f ".env" ]; then
    cp .env.example .env
    echo -e "  ${GREEN}Created .env file from .env.example${NC}"
else
    echo -e "  ${YELLOW}.env file already exists, skipping...${NC}"
fi

echo ""
echo -e "${YELLOW}Step 4: Generating application key...${NC}"
php artisan key:generate --force

echo ""
echo -e "${YELLOW}Step 5: Installing NPM dependencies...${NC}"
npm install

echo ""
echo -e "${YELLOW}Step 6: Building frontend assets...${NC}"
npm run build

echo ""
echo -e "${YELLOW}Step 7: Creating storage link...${NC}"
php artisan storage:link 2>/dev/null || true

echo ""
echo -e "${YELLOW}Step 8: Running database migrations...${NC}"
echo -e "${BLUE}  Note: Make sure you have created the database and configured .env${NC}"
read -p "  Continue with migrations? (y/n) " -n 1 -r
echo ""
if [[ $REPLY =~ ^[Yy]$ ]]; then
    php artisan migrate --force

    echo ""
    echo -e "${YELLOW}Step 9: Seeding database...${NC}"
    read -p "  Run database seeder? (y/n) " -n 1 -r
    echo ""
    if [[ $REPLY =~ ^[Yy]$ ]]; then
        php artisan db:seed --force
    fi
fi

echo ""
echo -e "${YELLOW}Step 10: Clearing caches...${NC}"
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

echo ""
echo -e "${GREEN}════════════════════════════════════════════════════════════${NC}"
echo -e "${GREEN}     Setup Complete!                                         ${NC}"
echo -e "${GREEN}════════════════════════════════════════════════════════════${NC}"
echo ""
echo -e "To start the development server, run:"
echo -e "  ${BLUE}php artisan serve${NC}"
echo ""
echo -e "Then open: ${BLUE}http://localhost:8000${NC}"
echo ""
echo -e "Admin panel: ${BLUE}http://localhost:8000/admin${NC}"
echo -e "  Email: ${GREEN}admin@suitconfigurator.vn${NC}"
echo -e "  Password: ${GREEN}password${NC}"
echo ""
