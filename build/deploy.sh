#!/bin/bash

echo "===== START DEPLOY ====="

cd /var/www/html/PcDetail

echo "Pull ล่าสุดจาก GitHub"
git pull origin main

echo "Reload NGINX"
sudo systemctl reload nginx

echo "===== DEPLOY SUCCESS ====="