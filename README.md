# PcDetail

เว็บสำหรับดูแลข้อมูลเครื่องคอมพิวเตอร์ในองค์กร พัฒนาด้วย CodeIgniter 4 และใช้ Microsoft SQL Server เป็นฐานข้อมูลหลัก

## ความสามารถหลัก

- เข้าสู่ระบบและกำหนดสิทธิ์ผู้ดูแลระบบ
- ดูรายการ PC, ค้นหา, กรอง และแก้ไขข้อมูลเครื่อง
- ดูประวัติการเปลี่ยนแปลงและภาพรวมบน Dashboard
- สแกนช่วง IP ในเครือข่ายและตรวจสถานะด้วย ping
- ใช้ session สำหรับหน้าเว็บ และ JWT สำหรับ API ข้อมูล PC

## สิ่งที่ต้องมี

- PHP 8.4.x และ Composer 2 (`composer.json` กำหนด PHP 8.4)
- PHP extensions ที่ Composer ตรวจสอบว่าจำเป็น รวมถึง `intl`, `mbstring`, `gd`, `zip` และส่วนขยาย XML
- Microsoft SQL Server, Microsoft ODBC Driver และ PHP extension `sqlsrv` ที่ตรงกับ PHP/ระบบปฏิบัติการ
- ฐานข้อมูลและบัญชีผู้ใช้ SQL Server ที่ได้รับสิทธิ์สำหรับโปรเจกต์นี้

โปรเจกต์ไม่มี migration สำหรับสร้างฐานข้อมูลใหม่ใน repository นี้ จึงต้องมี schema และข้อมูลที่แอปใช้ก่อนเริ่มใช้งานจริง

## เริ่มใช้งานในเครื่อง

1. ติดตั้ง dependency จาก `composer.lock`:

   ```powershell
   composer install
   composer check-platform-reqs
   ```

2. สร้างหรือแก้ไขไฟล์ `.env` ที่ root ของโปรเจกต์ โดยใช้ค่าของสภาพแวดล้อมตนเอง ตัวอย่างค่าที่จำเป็น:

   ```dotenv
   CI_ENVIRONMENT = development
   app.baseURL = 'http://localhost:8080/'

   database.default.hostname = YOUR_SQL_SERVER
   database.default.database = YOUR_DATABASE
   database.default.username = YOUR_USERNAME
   database.default.password = YOUR_PASSWORD
   database.default.DBDriver = SQLSRV
   database.default.port = 1433

   JWT_SECRET_KEY = REPLACE_WITH_RANDOM_SECRET
   JWT_REFRESH_SECRET_KEY = REPLACE_WITH_ANOTHER_RANDOM_SECRET
   JWT_ACCESS_EXPIRE = 180
   JWT_REFRESH_EXPIRE = 604800
   SESSION_IDLE_TIMEOUT = 600
   PCDETAIL_ADMIN_USERS = admin_username
   ```

   ใช้ secret คนละค่าที่สุ่มขึ้นใหม่ และเปลี่ยน `admin_username` เป็นชื่อบัญชีที่มีอยู่จริง หากต้องใช้การส่งอีเมล ให้ตั้งค่า `email.SMTPPass` ใน `.env` ของเครื่องนั้นด้วย อย่า commit `.env` หรือรหัสผ่านลง Git

3. เริ่มเว็บเซิร์ฟเวอร์:

   ```powershell
   php spark serve --host localhost --port 8080
   ```

4. เปิด `http://localhost:8080/` และเข้าสู่ระบบด้วยบัญชีที่มีอยู่ในฐานข้อมูล

หากใช้ Apache/Nginx ให้ตั้ง document root ไปที่ `public/` และตั้ง `app.baseURL` ให้ตรงกับ URL ที่เปิดจริง XAMPP ที่ยังใช้ PHP รุ่นเก่าไม่สามารถรัน dependency ชุด PHP 8.4 นี้ได้; ดูรายละเอียดใน [คู่มือ PHP 8.4](docs/php84-upgrade.md)

## ทดสอบ

```powershell
php vendor/bin/phpunit --no-coverage --no-logging --do-not-cache-result
php spark routes
```

Unit tests ใช้ฐานข้อมูลทดสอบแยกจาก SQL Server จริง จึงควรทดสอบการเข้าสู่ระบบ, รายการ PC, สิทธิ์ผู้ดูแล และการสแกน IP ในสภาพแวดล้อมทดสอบก่อนนำไปใช้จริง

## โครงสร้างที่เกี่ยวข้อง

| ตำแหน่ง | หน้าที่ |
| --- | --- |
| `app/Config/Routes.php` | เส้นทางหน้าเว็บและ API |
| `app/Controllers/Pc/` | การทำงานของหน้าและ API |
| `app/Models/Pc/` | การอ่าน/เขียนข้อมูล PC |
| `app/Views/pages/Pc/` | หน้าเว็บ |
| `public/` | ไฟล์ที่เว็บเซิร์ฟเวอร์เปิดให้เข้าถึง |
| `tests/` | ชุดทดสอบ |

อ่านเพิ่ม: [การตั้งค่าและ deploy PHP 8.4](docs/php84-upgrade.md), [แนวทางความปลอดภัย](docs/security-hardening.md)
