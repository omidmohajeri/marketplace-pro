#!/bin/sh
set -eu
# Accept direct DB_* settings (Render/TiDB) and legacy Railway MySQL variables.
: "${DB_HOST:=${MYSQLHOST:-}}"
: "${DB_PORT:=${MYSQLPORT:-4000}}"
: "${DB_DATABASE:=${MYSQLDATABASE:-}}"
: "${DB_USERNAME:=${MYSQLUSER:-}}"
: "${DB_PASSWORD:=${MYSQLPASSWORD:-}}"
export DB_CONNECTION="${DB_CONNECTION:-mysql}" DB_HOST DB_PORT DB_DATABASE DB_USERNAME DB_PASSWORD
export APP_ENV="${APP_ENV:-production}"
export APP_DEBUG="${APP_DEBUG:-false}"
export LOG_CHANNEL="${LOG_CHANNEL:-stderr}"
export CACHE_STORE="${CACHE_STORE:-file}"
export SESSION_DRIVER="${SESSION_DRIVER:-file}"
export QUEUE_CONNECTION="${QUEUE_CONNECTION:-sync}"
# TiDB Cloud Starter requires TLS; Debian's system CA bundle is sufficient.
export MYSQL_ATTR_SSL_CA="${MYSQL_ATTR_SSL_CA:-/etc/ssl/certs/ca-certificates.crt}"
mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache || true
if [ -z "${APP_KEY:-}" ]; then
  echo 'ERROR: APP_KEY is missing. Generate it with: php artisan key:generate --show, then set it in the hosting environment.' >&2
  exit 1
fi
if [ -n "${DB_HOST:-}" ] && [ -n "${DB_DATABASE:-}" ] && [ -n "${DB_USERNAME:-}" ]; then
  php artisan migrate --force
else
  echo 'ERROR: Database settings are missing. Set DB_HOST, DB_DATABASE, DB_USERNAME, and DB_PASSWORD in the hosting environment.' >&2
  exit 1
fi
php artisan storage:link || true
php artisan config:cache
exec apache2-foreground
