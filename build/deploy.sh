#!/bin/bash
set -e

echo "===== START DEPLOY ====="
cd /var/www/html/PcDetail


echo "Code ล่าสุดจาก GitHub"
git fetch origin
git reset --hard origin/main

echo "Reload PHP-FPM and NGINX"
sudo systemctl restart php7.4-fpm
sudo systemctl reload nginx

echo "===== DEPLOY SUCCESS ====="