## [ session ]

-ไม่มี Activity เกิน 30 นาที → TimeOut → logout/login
-Session หมดอายุ 12 ชั่วโมง
-ทุก Request → Filter ตรวจ session
-Frontend → ยิง /check-session ทุก 30 วินาที ถ้า active → ตอบ active
ถ้า timeout → logout/login ใหม่
เปิดหน้าเว็บ
│
▼
Session Timeout ?
│
YES ───────────────► 401
code=SESSION_TIMEOUT
▼
Login

## Authentication

Session → ใช้ป้องกันหน้าเว็บ (Views)
JWT → ใช้ป้องกัน API

## [การดึงข้อมูล]

JWT + API
Authorisation กระบวนการให้สิทธิ์ การอนุญาต
Login

Generated JWT Token = User + Password + JWT_SECRET_KEY + 'HS256'
Authorisation → Verifly ผ่าน → Respone
ผู้ใช้กดปุ่ม "ค้นหา"
│
▼
apiFetch()
│
▼
เรียก API และแนบ Token
│
▼
Access Token หมดอายุ (3 นาที) อนาคตให้ต่อล่วงหน้า 2 นาที ก่อนหมดอายุโดยไม่รอให้ API ตอบ 401
│
▼
API ตอบ 401 (TOKEN_EXPIRED)
│
▼
apiFetch() เรียก /jwt/refresh อัตโนมัติ
│
▼
ได้ Access Token ใหม่
│
▼
ยิง API เดิมซ้ำอัตโนมัติ
│
▼
ผู้ใช้เห็นข้อมูลตามปกติ

logout/session timeout → ลบ access_token + refresh_token
