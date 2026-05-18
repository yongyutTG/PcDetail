#!/bin/bash

echo "===== START DEPLOY ====="

cd /var/www/html/PcDetail

echo "Pull ล่าสุดจาก GitHub"
git pull origin main

echo "Install Composer"
composer install --no-dev --optimize-autoloader


echo "Permission"
chmod -R 775 writable

echo "Reload NGINX"
sudo systemctl reload nginx

echo "===== DEPLOY SUCCESS ====="