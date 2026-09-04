#!/bin/bash
set -e
echo "===== START DEPLOY ====="
cd /var/www/html/PcDetail
echo "Code ล่าสุดจาก GitHub"
#git pull origin main;  
git fetch origin  #ดึงข้อมูลล่าสุดจาก remote repository
git reset --hard origin/main    #รีเซ็ต branch ปัจจุบันให้ตรงกับ origin/main
git clean -fd -e composer.phar -e .env

echo "===== Composer Install ====="

php composer.phar install --no-dev --optimize-autoloader --no-interaction

echo "Reload PHP-FPM and NGINX"
sudo systemctl restart php7.4-fpm
sudo systemctl reload nginx

echo "===== DEPLOY SUCCESS ====="