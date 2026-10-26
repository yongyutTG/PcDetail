#!/usr/bin/env bash
set -euo pipefail

cd /var/www/html/PcDetail
PHP_BIN="${PHP_BIN:-php8.4}"
COMPOSER_BIN="${COMPOSER_BIN:-/usr/local/bin/composer}"

# Fail before touching the checkout if the server is not ready for PHP 8.4.
"$PHP_BIN" -r 'if (PHP_VERSION_ID < 80400 || PHP_VERSION_ID >= 80500) { fwrite(STDERR, "PHP 8.4.x is required\n"); exit(1); } foreach (["intl", "mbstring", "gd", "zip", "dom", "fileinfo", "sqlsrv"] as $ext) { if (!extension_loaded($ext)) { fwrite(STDERR, "Missing extension: $ext\n"); exit(1); } }'
test -f "$COMPOSER_BIN"
test -f .env
systemctl cat php8.4-fpm >/dev/null
sudo nginx -t

git fetch origin main
# CI supplies the tested revision. Manual runs deploy the fetched main revision.
DEPLOY_REVISION="${GITHUB_SHA:-origin/main}"
git merge-base --is-ancestor "$DEPLOY_REVISION" origin/main
git reset --hard "$DEPLOY_REVISION"
"$PHP_BIN" "$COMPOSER_BIN" install --no-dev --prefer-dist --no-interaction --optimize-autoloader
"$PHP_BIN" "$COMPOSER_BIN" check-platform-reqs --no-dev

sudo systemctl restart php8.4-fpm
sudo systemctl reload nginx
echo "Deployment completed."
