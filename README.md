# Simple Inventory API (Laravel 11 + JWT)

Aplikasi Backend Sederhana untuk sistem inventaris dan manajemen pengguna. Aplikasi ini dibuat sebagai bagian dari *Technical Test* Programmer.

Menggunakan **Laravel 11** dengan arsitektur **RESTful API**, autentikasi berbasis **JWT (JSON Web Token)**, dan **Role-Based Access Control (RBAC)**.

---

## 🛠 Tech Stack
* **Framework:** Laravel 11
* **Language:** PHP 8.2+
* **Database:** MySQL
* **Authentication:** `php-open-source-saver/jwt-auth`
* **Format Response:** JSON

---

## 🚀 Fitur Utama
1.  **Authentication:** Login menggunakan Email & Password dengan respon JWT.
2.  **Role Management:** Terdapat 3 role (Admin, Seller, Pelanggan).
3.  **Inventory System:** Admin dapat mengelola produk (CRUD).
4.  **Transaction Logic:** Seller dapat melakukan penjualan yang otomatis mengurangi stok barang (Atomic Transaction).
5.  **Security:** Middleware untuk membatasi akses endpoint berdasarkan Role.

---

## ⚙️ Cara Install & Menjalankan (Installation)

Ikuti langkah berikut untuk menjalankan aplikasi di local machine Anda.

### 1. Clone & Install Dependencies
Pastikan Composer sudah terinstall.
- Clone repository ini
```bash
git clone https://github.com/username-anda/inventory-api.git

```
cd inventory-api

**Install dependency PHP**
```
composer install

### 2. Setup Environment (.env)
```
cp .env.example .env
**Buka file .env dan sesuaikan konfigurasi database Anda**
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=YOUR_DATABASE_NAME
DB_USERNAME=root
DB_PASSWORD=

### 3. Generate Keys
```
php artisan key:generate
php artisan jwt:secret

### 4. Database Migration & Seeding
```
php artisan migrate --seed

### 5 Running server
```
php artisan serve

## API DOCUMENTATION
Key: Accept
Value: application/json

**example BODY JSON
```
{
    "product_id": 1,
    "quantity": 2
}



