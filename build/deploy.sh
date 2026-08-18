#!/bin/bash
set -e

echo "===== START DEPLOY ====="
cd /var/www/html/PcDetail

echo "Code ล่าสุดจาก GitHub"
#git pull origin main;  #ดึงข้อมูลล่าสุดจาก remote repository และ merge เข้ากับ branch ปัจจุบัน
git fetch origin  #ดึงข้อมูลล่าสุดจาก remote repository
git reset --hard origin/main    #รีเซ็ต branch ปัจจุบันให้ตรงกับ origin/main
git clean -fd  #ใช้ลบไฟล์ที่ Git ไม่รู้จัก

echo "===== Composer Install ====="
composer install --no-dev --optimize-autoloader --no-interaction

echo "Reload NGINX"
sudo systemctl reload nginx


echo "===== DEPLOY SUCCESS ====="