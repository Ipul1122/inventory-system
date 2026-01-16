# Simple Inventory API (Laravel 11 + JWT)

Aplikasi Backend dan Frontend Sederhana untuk sistem inventaris dan manajemen pengguna. Aplikasi ini dibuat sebagai bagian dari *Technical Test* Programmer.

Menggunakan **Laravel 11** dengan arsitektur **RESTful API**, autentikasi berbasis **JWT (JSON Web Token)**, dan **Role-Based Access Control (RBAC)**.

---
### BACKEND
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

Install dependency PHP
```
composer install

```
### 2. Setup Environment (.env)
```
cp .env.example .env
```
**Buka file .env dan sesuaikan konfigurasi database Anda**
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=YOUR_DATABASE_NAME
DB_USERNAME=root
DB_PASSWORD=

```
### 3. Generate Keys
```
php artisan key:generate
php artisan jwt:secret
```
### 4. Database Migration & Seeding
```
php artisan migrate --seed
```
### 5 Running server
```
php artisan serve
```
## API DOCUMENTATION
- Go to Headers and set key and value
**Key: Accept**

**Value: application/json**

## EXAMPLE BODY JSON
### api/auth/login
- POST this into Postman, go to body, choice raw and input at body section
```
{
    "email": "admin@toko.com",
    "password": "password"
}
```
- You can GET bearer Token 

### api/auth/products
- if u want created new data, take and copy bearer token from auth login.
- Go to Authorization, choice auth type is bearer Token, then paste into Token from you right
- Then go back to body and paste this JSON to make data
```
{
    "name": "Laptop Gaming",
    "stock": 10,
    "price": 15000000
}
```
- it will add data in table products

---
### FRONT END
---

# 🖥️ Frontend Documentation (Simple Inventory Client)

Frontend aplikasi ini dibangun menggunakan pendekatan **Single Page Application (SPA)** sederhana yang terintegrasi langsung ke dalam Laravel Blade.

Antarmuka ini dirancang untuk mengkonsumsi **REST API** yang telah dibuat, menangui Autentikasi JWT, dan manajemen state sederhana menggunakan **Vanilla JavaScript** dan **Local Storage**.

---

## 🛠 Tech Stack (Frontend)
* **Structure:** HTML5 (Laravel Blade View)
* **Styling:** Bootstrap 5 (via CDN) - Responsive & Modern UI.
* **Logic:** Vanilla JavaScript (ES6+).
* **HTTP Client:** Native Fetch API.
* **State Management:** LocalStorage (untuk menyimpan Token JWT & Role).

---

## 📂 Lokasi File
Seluruh logika Frontend (HTML, CSS, JS) terdapat dalam **satu file** untuk kemudahan deployment dan testing:
* Path: `resources/views/app.blade.php`
* Route: `http://127.0.0.1:8000/`

---

## 🚀 Cara Menjalankan UI
Karena frontend ini menyatu dengan monolith Laravel, Anda tidak perlu menjalankan `npm run dev` atau server terpisah.

1.  Pastikan server backend berjalan:
    ```bash
    php artisan serve
    ```
2.  Buka browser dan akses:
    ```
    [http://127.0.0.1:8000](http://127.0.0.1:8000)
    ```
3.  Anda akan diarahkan ke halaman Login secara otomatis.

---

## ✨ Fitur Antarmuka
Frontend memiliki logika dinamis yang menyesuaikan tampilan berdasarkan **Role User** yang login.

### 1. Authentication Flow
* **Login:** Mengirim email/password ke API -> Menerima JWT -> Simpan di Browser.
* **Auto Redirect:** Jika user belum login, otomatis dilempar ke halaman login.
* **Logout:** Menghapus Token dari LocalStorage dan refresh halaman.

### 2. Dashboard Inventaris
* **View Products:** Menampilkan tabel barang (Nama, Harga, Sisa Stok).
* **Badge Stok:** Indikator warna (Merah jika kosong, Biru jika tersedia).
* **Tambah Barang:** Tombol hanya muncul jika login sebagai **Admin**.
* **Jual Barang:**
    * Tombol muncul untuk **Admin** & **Seller**.
    * **Validasi UI:** Sistem akan mengecek stok saat tombol diklik. Jika input melebihi sisa stok, muncul **Alert Error** tanpa reload halaman.

### 3. Manajemen User (Role Settings)
* **Proteksi Menu:** Menu ini samasekali tidak terlihat (hidden) jika login sebagai Seller/Pelanggan.
* **Change Role:** Admin dapat mengubah role user lain melalui dropdown dan tombol "Simpan".

---

## 🧪 Skenario Testing UI
Gunakan akun berikut untuk melihat perbedaan tampilan (UI Logic):

| Akun | Email | Tampilan yang Diharapkan |
| :--- | :--- | :--- |
| **Admin** | `admin@toko.com` | Menu lengkap (Inventaris + User), Bisa Tambah Barang, Bisa Jual. |
| **Seller** | `seller@toko.com` | Hanya Menu Inventaris, **Bisa Jual**, TAPI Tombol "Tambah Barang" Hilang. |
| **Pelanggan** | `pelanggan@toko.com` | Hanya Menu Inventaris (View Only), Tidak ada tombol aksi apapun. |

---

## ⚠️ Troubleshooting Frontend
Jika data tidak muncul atau terjadi error:
1.  **Cek Console:** Tekan `F12` -> Tab **Console** untuk melihat pesan error JavaScript.
2.  **Cek Token:** Tekan `F12` -> Tab **Application** -> **Local Storage**. Pastikan ada key bernama `token`.
3.  **Clear Cache:** Jika tampilan berantakan, coba Hard Refresh (`Ctrl + F5`).

---
*Frontend Module Implementation*
