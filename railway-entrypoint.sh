#!/bin/sh
set -eu
# Map Railway MySQL service variables to Laravel's standard DB_* variables.
: "${DB_HOST:=${MYSQLHOST:-}}"
: "${DB_PORT:=${MYSQLPORT:-3306}}"
: "${DB_DATABASE:=${MYSQLDATABASE:-}}"
: "${DB_USERNAME:=${MYSQLUSER:-}}"
: "${DB_PASSWORD:=${MYSQLPASSWORD:-}}"
export DB_CONNECTION=mysql DB_HOST DB_PORT DB_DATABASE DB_USERNAME DB_PASSWORD
export APP_ENV="${APP_ENV:-production}"
export APP_DEBUG="${APP_DEBUG:-false}"
export LOG_CHANNEL="${LOG_CHANNEL:-stderr}"
export CACHE_STORE="${CACHE_STORE:-file}"
export SESSION_DRIVER="${SESSION_DRIVER:-file}"
export QUEUE_CONNECTION="${QUEUE_CONNECTION:-sync}"
mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache || true
if [ -z "${APP_KEY:-}" ]; then
  echo 'ERROR: APP_KEY is missing. Set APP_KEY in Railway variables (generate with: php artisan key:generate --show).' >&2
  exit 1
fi
if [ -n "${DB_HOST:-}" ] && [ -n "${DB_DATABASE:-}" ] && [ -n "${DB_USERNAME:-}" ]; then
  php artisan migrate --force
else
  echo 'ERROR: MySQL variables are missing. Attach a Railway MySQL service and map MYSQLHOST/MYSQLPORT/MYSQLDATABASE/MYSQLUSER/MYSQLPASSWORD.' >&2
  exit 1
fi
php artisan storage:link || true
php artisan config:cache
exec apache2-foreground
