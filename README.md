# Menu dan Role Management API Template

![Lumen](https://img.shields.io/badge/Lumen-10.x-blue.svg) ![PHP](https://img.shields.io/badge/PHP-8.2-blue.svg) ![License](https://img.shields.io/badge/License-MIT-green.svg)

Menu dan Role Management API Template adalah layanan API berbasis Lumen

## 🚀 Fitur Utama

-   Autentikasi menggunakan **JWT dan LDAP**
-   Manajemen stok barang (**users**, **menus**, **roles**, dll.)
-   Pengolahan data dengan **Guzzle** dan **Excel Export**
-   Pengelolaan peran dan izin menggunakan **Spatie Permission**

---

## 📌 Persyaratan Minimum

-   **PHP 8.1.0**
-   **Lumen 10.0.4**
-   **Composer**
-   **MySQL / PostgreSQL / Oracle (PDO OCI enabled)**
-   **Redis (Opsional untuk caching)**

---

## 🔧 Instalasi

1. Clone repository:
    ```sh
    git clone https://github.com/4fri/admin-dashboard-BE.git
    cd admin-template
    ```
2. Install dependensi:
    ```sh
    composer install
    ```
3. Copy file `.env.example` ke `.env` dan atur konfigurasi:
    ```sh
    cp .env.example .env
    ```
4. Generate application key:
    ```sh
    php artisan key:generate
    ```
5. Jalankan migrasi database:
    ```sh
    php artisan migrate --seed
    ```
6. Jalankan aplikasi:
    ```sh
    php artisan serve
    ```

---

## 🛠 Konfigurasi Lingkungan

Pastikan `.env` diatur dengan benar, contoh:

```env
APP_NAME=SISI Inventory
APP_ENV=local
APP_DEBUG=true
APP_KEY=
APP_URL=http://localhost

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=db_name
DB_USERNAME=db_username
DB_PASSWORD=db_password

CACHE_DRIVER=file
QUEUE_CONNECTION=sync
```

---

## 🔒 Keamanan

-   Semua endpoint memerlukan **JWT Authentication**.
-   Gunakan **role-based access control (RBAC)** dengan Spatie Permission.
-   **Rate Limiting** dapat diterapkan dengan middleware.

---

## 📝 Lisensi

SISI Inventory Backend menggunakan lisensi [MIT](https://opensource.org/licenses/MIT).
