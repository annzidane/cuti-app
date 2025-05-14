## 🛠️ Laravel Project Setup

Proyek ini adalah aplikasi Laravel. Ikuti panduan di bawah untuk mengatur lingkungan pengembangan lokal Anda.

---

### 📋 Persyaratan

Pastikan Anda sudah menginstal:

* PHP >= 8.1
* Composer
* Laravel CLI
* MySQL / MariaDB
* Node.js & NPM
* (Opsional) Redis, jika digunakan

---

### 🚀 Langkah-langkah Setup

1. **Clone repository**

   ```bash
   git clone https://github.com/nama-user/nama-repo.git
   cd nama-repo
   ```

2. **Install dependencies**

   ```bash
   composer install
   npm install && npm run dev
   ```

3. **Copy `.env` dan konfigurasi**

   ```bash
   cp .env.example .env
   ```

   Lalu edit file `.env` dan sesuaikan konfigurasi berikut:

   ```env
   APP_NAME=Laravel
   APP_ENV=local
   APP_KEY=base64:
   APP_DEBUG=true
   APP_URL=http://localhost

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=your_database
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

4. **Generate application key**

   ```bash
   php artisan key:generate
   ```

5. **Jalankan migrasi dan seeder**

   ```bash
   php artisan migrate --seed
   ```

   Seeder akan membuat 1 akun admin secara otomatis.

---

### 🔐 Akun Admin Default

Seeder `AdminUserSeeder` akan membuat akun admin berikut:

| Email                                         | Password    |
| --------------------------------------------- | ----------- |
| [admin@example.com](mailto:admin@example.com) | password123 |

> ⚠️ Gantilah password ini di lingkungan production demi keamanan.

---

### 💡 Menjalankan Server

```bash
php artisan serve
```

Lalu buka [http://localhost:8000](http://localhost:8000)

---

### 🧪 Testing

```bash
php artisan test
```

---

### 📦 Build Assets (untuk produksi)

```bash
npm run build
```

---

### 🧹 Tips Tambahan

* Untuk memperbarui struktur database:

  ```bash
  php artisan migrate:fresh --seed
  ```

* Jika menggunakan Laravel Sanctum atau Passport, jalankan juga setup tambahan sesuai dokumentasi resmi.

---

Silakan sesuaikan bagian-bagian tertentu jika Anda menggunakan konfigurasi atau stack tambahan (seperti Docker, Redis, Horizon, dll). Jika butuh versi README dalam bahasa Inggris juga, saya bisa bantu.
