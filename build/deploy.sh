#!/bin/bash
set -e

echo "===== START DEPLOY ====="
cd /var/www/html/PcDetail

echo "Code ล่าสุดจาก GitHub"
git fetch origin
git reset --hard origin/main
git clean -fd -e composer.phar -e .env

echo "===== Composer Install ====="
if [ ! -f composer.phar ]; then
  echo "Download Composer 2.8.6"
  php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
  php composer-setup.php --version=2.8.6
  rm composer-setup.php
fi

php composer.phar install --no-dev --optimize-autoloader --no-interaction

echo "Reload PHP-FPM and NGINX"
sudo systemctl restart php7.4-fpm
sudo systemctl reload nginx

echo "===== DEPLOY SUCCESS ====="