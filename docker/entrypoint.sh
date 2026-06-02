#!/bin/sh
set -e

echo "🚀 Starting Prahari deployment..."

# Map Render's DATABASE_URL to Laravel's DB_URL
if [ -n "$DATABASE_URL" ]; then
    echo "🗄️  Mapping DATABASE_URL → DB_URL for Laravel..."
    export DB_URL="$DATABASE_URL"
fi

# Copy .env.example if .env doesn't exist
if [ ! -f .env ]; then
    echo "📋 Creating .env from .env.example..."
    cp .env.example .env
fi

# Generate app key if not set
if [ -z "$APP_KEY" ]; then
    echo "🔑 Generating application key..."
    php artisan key:generate --force
else
    echo "🔑 Using APP_KEY from environment..."
fi

# Cache configuration for production performance
echo "⚡ Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run database migrations
echo "🗄️  Running database migrations..."
php artisan migrate --force

# Create storage symlink
echo "🔗 Creating storage symlink..."
php artisan storage:link --force 2>/dev/null || true

# Ensure correct permissions
echo "🔒 Setting permissions..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Create supervisor log directory
mkdir -p /var/log/supervisor

echo "✅ Deployment ready! Starting services..."

exec "$@"
