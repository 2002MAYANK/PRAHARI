#!/bin/sh
set -e

echo "🚀 Starting Prahari deployment..."

# Map Render's DATABASE_URL to Laravel's DB_URL
if [ -n "$DATABASE_URL" ]; then
    echo "🗄️  Mapping DATABASE_URL → DB_URL for Laravel..."
    export DB_URL="$DATABASE_URL"
fi

# Create .env file
if [ ! -f .env ]; then
    if [ -f .env.example ]; then
        echo "📋 Creating .env from .env.example..."
        cp .env.example .env
    else
        echo "📋 Creating minimal .env..."
        touch .env
    fi
fi

# Fix Windows CRLF line endings if present
sed -i 's/\r$//' .env

# Ensure APP_KEY= line exists in .env (key:generate needs it)
if ! grep -q "^APP_KEY=" .env; then
    echo "APP_KEY=" >> .env
fi

# Generate app key if not set or not in valid Laravel format (base64:...)
case "$APP_KEY" in
    base64:*)
        echo "🔑 Using valid APP_KEY from environment..."
        # Write it into .env so config:cache picks it up
        sed -i "s|^APP_KEY=.*|APP_KEY=${APP_KEY}|" .env
        ;;
    *)
        echo "🔑 Generating new Laravel application key..."
        php artisan key:generate --force
        # Export the generated key so config:cache uses it (env vars override .env)
        export APP_KEY=$(grep '^APP_KEY=' .env | cut -d'=' -f2-)
        echo "🔑 Generated key: ${APP_KEY}"
        ;;
esac

# Cache configuration for production performance
echo "⚡ Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run database migrations
echo "🗄️  Running database migrations..."
php artisan migrate --force

# Seed default admin user (idempotent — skips if admin already exists)
echo "👤 Seeding default admin user..."
php artisan db:seed --class=AdminSeeder --force

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
