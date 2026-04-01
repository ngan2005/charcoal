# Hướng dẫn Deploy Laravel lên 000webhost

## Lưu Ý Quan Trọng

000webhost **KHÔNG có SSH** nên:
- Không dùng được Deployer
- Phải upload thủ công qua File Manager
- Chạy `composer install` trên máy local trước

---

## Các Bước Thực Hiện

### Bước 1: Đăng ký 000webhost

1. Vào [000webhost.com](https://www.000webhost.com/)
2. Click "Sign Up" → Đăng ký bằng email hoặc GitHub
3. Không cần thẻ tín dụng

### Bước 2: Tạo Website mới

1. Sau khi đăng nhập, click "Create Site"
2. Chọn "PHP" làm ngôn ngữ
3. Đặt tên website (ví dụ: `pinkcharcoal`)

### Bước 3: Chuẩn bị Code trên Máy Local

#### 3.1: Chạy composer install (đã có vendor rồi thì bỏ qua)

```bash
cd c:\laragon\www\charcoal
composer install --optimize-autoloader --no-dev
```

#### 3.2: Copy file `.env` và chỉnh sửa

```bash
copy .env .env.backup
```

Chỉnh sửa `.env` cho production:
- `APP_ENV=production`
- `APP_DEBUG=false`
- Cập nhật `DB_*` thành database của 000webhost

#### 3.3: Tạo file `.htaccess` trong thư mục gốc

Mình đã tạo file `.htaccess` cho bạn trong thư mục gốc project.

### Bước 4: Export Database

1. Vào phpMyAdmin trên 000webhost (trong dashboard)
2. Tạo database mới
3. Import file SQL từ Laragon:
   - Mở phpMyAdmin trên Laragon
   - Export database `charcoal`
   - Import vào 000webhost

### Bước 5: Upload Code lên 000webhost

#### Cách 1: File Manager (Dễ nhất)

1. Vào dashboard 000webhost → "Manage" → "File Manager"
2. Mở thư mục `public_html`
3. **Xóa hết file có sẵn**
4. Upload toàn bộ code Laravel (trừ thư mục `node_modules`, `.git`)

#### Cách 2: Upload Manager

1. Nén toàn bộ code thành file `.zip` (trừ `node_modules`, `.git`, `vendor` nếu muốn nhẹ)
2. Upload file `.zip` lên 000webhost
3. Giải nén trong File Manager

#### Cách 3: FTP (Nếu có thông tin FTP)

1. Dùng FileZilla kết nối FTP
2. Upload toàn bộ code vào `public_html`

### Bước 6: Cấu hình Database

1. Trong File Manager, mở `.env`
2. Cập nhật thông tin database từ 000webhost:

```
DB_CONNECTION=mysql
DB_HOST=localhost
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### Bước 7: Chạy Migration

Vì không có SSH, bạn cần tạo file `migrate.php` để chạy migration:

1. Tạo file `migrate.php` trong `public_html`:
```php
<?php
// Chạy file này 1 lần sau khi upload
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$artisan = $app->make(Illuminate\Contracts\Console\Kernel::class);
$artisan->call('migrate', ['--force' => true]);
echo "Migration completed!";
```

2. Mở trình duyệt: `http://yoursite.000webhost.com/migrate.php`

3. **XÓA file `migrate.php` ngay sau khi chạy xong!**

---

## Xử Lý Lỗi Thường Gặp

### Lỗi 500 Internal Server Error

Kiểm tra file `.htaccess` trong `public_html`:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /
    RewriteCond %{REQUEST_URI} !^/public/
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

### Lỗi "No input file specified"

Thêm vào `.htaccess` trong thư mục gốc:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /
    RewriteRule ^$ public/ [L]
    RewriteCond %{REQUEST_URI} !^/public/
    RewriteCond %{REQUEST_URI} !^/storage/
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

### Lỗi Permission Denied

Trong File Manager, click chuột phải vào thư mục `storage` → Set Permissions → `755`

### Lỗi Database Connection

1. Kiểm tra thông tin database trong `.env`
2. Đảm bảo database đã được tạo và import đúng cách
3. Username và password phải khớp với thông tin 000webhost cung cấp

---

## Cấu Trúc Thư Mục trên 000webhost

```
/home/a1b2c3d4/
├── public_html/          ← Đây là thư mục gốc website
│   ├── .htaccess
│   ├── index.php         ← Copy từ public/index.php
│   ├── css/
│   ├── js/
│   └── ...
├── .env
├── app/
├── bootstrap/
├── config/
├── database/
├── resources/
├── routes/
├── storage/
├── vendor/
├── artisan
└── composer.json
```

---

## Kiểm Tra Sau Khi Deploy

1. Truy cập `http://yoursite.000webhost.com`
2. Kiểm tra trang chủ có hiển thị không
3. Kiểm tra các trang khác (admin, đăng nhập...)
4. Kiểm tra upload hình ảnh có hoạt động không

---

## Lưu Ý Bảo Mật

Sau khi deploy thành công:

1. **XÓA các file không cần thiết:**
   - `migrate.php`
   - `.env.backup`
   - File README, CHANGELOG...

2. **Đặt APP_DEBUG=false trong .env:**
   ```
   APP_DEBUG=false
   ```

3. **Bảo vệ thư mục storage:**
   Thêm file `.htaccess` trong `storage/app/public/`:
   ```
   Options -Indexes
   ```
