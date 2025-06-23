#!/bin/bash
set -e

# Create directories if they don't exist
mkdir -p /var/www/html/storage/logs
mkdir -p /var/www/html/storage/framework/cache
mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/framework/views
mkdir -p /var/www/html/bootstrap/cache
mkdir -p /var/log/supervisor

# Set proper permissions
chown -R www-data:www-data /var/www/html/storage
chown -R www-data:www-data /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage
chmod -R 775 /var/www/html/bootstrap/cache

# Wait for database to be ready
if [ "$DATABASE_CONNECTION" = "mysql" ]; then
    echo "Waiting for MySQL..."
    while ! mysqladmin ping -h"$DB_HOST" -u"$DB_USERNAME" -p"$DB_PASSWORD" --silent; do
        sleep 1
    done
    echo "MySQL is ready!"
fi

# Run migrations if needed (only in specific environments or controlled via env var)
if [ "${RUN_MIGRATIONS:-false}" = "true" ]; then
    echo "Running migrations..."
    php artisan migrate --force
    echo "Migrations completed!"
fi

# Cache configuration for better performance
echo "Optimizing Laravel..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
echo "Laravel optimized!"

# Start the application
echo "Starting application..."
exec "$@" 