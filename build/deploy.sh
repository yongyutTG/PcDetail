#!/bin/bash

echo "===== START DEPLOY ====="
cd /var/www/html/PcDetail

echo "Pull ล่าสุดจาก GitHub"
git pull origin main

echo "===== Composer Install ====="
composer install --no-dev --optimize-autoloader

# echo "Reload NGINX"
# sudo systemctl reload nginx

echo "===== DEPLOY SUCCESS ====="