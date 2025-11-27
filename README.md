# 🐼 PANDA (Pembelajaran Anak dengan Asyik)

PANDA adalah platform edukasi interaktif yang dirancang khusus untuk anak-anak usia dini (Taman Kanak-Kanak). Platform ini menyediakan berbagai fitur pembelajaran yang menyenangkan dan interaktif untuk membantu perkembangan anak dalam aspek kognitif, motorik, dan sosial.

## Fitur Utama

### 🎓 Kuis
- Guru dapat membuat kuis dengan berbagai tipe soal.
- Siswa dapat mengerjakan kuis secara interaktif.
- Riwayat nilai dan detail jawaban tersimpan untuk evaluasi.

### 📚 Materi Pembelajaran
- 🅰️ **Alfabet**: Pengenalan huruf A-Z.
- 🎨 **Warna**: Mengenal berbagai macam warna.
- 🦁 **Hewan**: Mengenal nama-nama hewan.
- 1️⃣ **Angka**: Pengenalan angka dasar.
- 🍎 **Buah**: Mengenal nama-nama buah.
- 🚗 **Transportasi**: Mengenal jenis-jenis kendaraan.

### 🎮 Permainan Edukatif
- **🧩 Puzzle**: Melatih pemecahan masalah visual.
- **🧮 Berhitung**: Belajar matematika dasar dengan cara yang menyenangkan.
- **🃏 Cocokkan Pasangan**: Melatih daya ingat (memory game).
- **🔢 Urutkan Angka**: Memahami urutan bilangan.
- **📝 Menyusun Kata**: Belajar mengeja dan menyusun huruf menjadi kata.
- **🌀 maze Labirin**: Melatih logika dan perencanaan jalur.

### 👥 Sistem Pengguna
- **Admin**: Mengelola platform dan pengguna
- **Guru**: Membuat dan mengelola materi serta kuis
- **Wali Murid**: Mewakili anak dalam menggunakan platform, mengakses materi, permainan, dan kuis

### 🔐 Keamanan dan Autentikasi
- Sistem login dengan email dan OTP
- Role-based access control
- Whitelist untuk kontrol akses

## 🛠️ Teknologi yang Digunakan

- **Backend**: [Laravel 12.x](https://laravel.com)
- **Frontend**: [Blade Templates](https://laravel.com/docs/blade), [Tailwind CSS](https://tailwindcss.com)
- **Interactivity**: [Alpine.js](https://alpinejs.dev), [jQuery](https://jquery.com)
- **Libraries**:
  - `sweetalert2` (Notifikasi cantik)
  - `sortablejs` (Drag & drop)
  - `canvas-confetti` (Efek selebrasi)
- **Build Tool**: [Vite](https://vitejs.dev)

## 🚀 Instalasi & Menjalankan Project

Ikuti langkah-langkah berikut untuk menjalankan project di komputer lokal Anda:

### Prasyarat
Pastikan Anda sudah menginstall:
- PHP >= 8.2
- Composer
- Node.js & NPM
- Database (MySQL/MariaDB/SQLite)

### Langkah-langkah

1. **Clone Repository**
   ```bash
   git clone https://github.com/username/panda-tk.git
   cd panda-tk
   ```

2. **Install Dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Konfigurasi Environment**
   Salin file `.env.example` menjadi `.env`:
   ```bash
   cp .env.example .env
   ```
   Buka file `.env` dan sesuaikan konfigurasi database Anda.

   **Opsional**: Anda dapat mengatur kredensial admin default di `.env`:
   ```env
   ADMIN_USERNAME=admin
   ADMIN_PASSWORD=admin123
   ```

4. **Generate App Key**
   ```bash
   php artisan key:generate
   ```

5. **Migrasi & Seeding Database**
   Jalankan migrasi dan seeder untuk membuat tabel dan data awal (termasuk akun admin):
   ```bash
   php artisan migrate --seed
   ```

6. **Jalankan Aplikasi**
   Buka dua terminal terpisah untuk menjalankan server Laravel dan Vite:

   Terminal 1:
   ```bash
   php artisan serve
   ```

   Terminal 2:
   ```bash
   npm run dev
   ```

7. **Akses Aplikasi**
   Buka browser dan kunjungi `http://localhost:8000`.

## 🔑 Akun Default

Setelah menjalankan `php artisan migrate --seed`, Anda dapat menggunakan akun berikut:

### Admin
- **Username**: `admin` (atau sesuai `ADMIN_USERNAME` di .env)
- **Password**: `admin123` (atau sesuai `ADMIN_PASSWORD` di .env)

### Whitelist Email (Untuk Pendaftaran)
Seeder juga menambahkan beberapa email ke whitelist agar bisa mendaftar sebagai Guru atau Wali Murid:
- `guru@example.com` (Role: Guru)
- `wali@example.com` (Role: Wali Murid)

---

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
