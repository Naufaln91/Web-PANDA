# TODO: Perubahan Proses Pembuatan Akun Guru dan Penentuan Role

## 1. Update Migration Whitelists
- Tambah kolom role enum('guru','wali_murid') di migration whitelists

## 2. Update Model Whitelist
- Tambah fillable role

## 3. Update AdminController whitelistStore
- Validasi dan simpan role saat tambah whitelist

## 4. Update View Whitelist Index
- Tambah select role di form tambah
- Update tabel untuk tampilkan kolom role

## 5. Update Migration Users
- Rename nama_orangtua ke nama
- Buat nama_anak dan kelas_anak nullable

## 6. Update Model User
- Update fillable nama

## 7. Update LoginController requestOtp
- Ambil role dari whitelist dan kirim ke frontend

## 8. Update LoginController completeProfile
- Validasi beda berdasarkan role - guru hanya nama, wali_murid lengkap

## 9. Update View Login step-profile
- Tampilkan form sesuai role

## 10. Update AdminController akunIndex
- Pisah data guru dan wali_murid

## 11. Update View Akun Index
- Tabel terpisah guru (kolom nama) dan wali murid (nama_orangtua, nama_anak, kelas_anak)

## Followup Steps
- Run php artisan migrate
- Test flow login guru dan wali murid
- Test admin kelola whitelist dengan role
- Test admin kelola akun tabel terpisah

----

# TODO: Perbaikan UI/UX Halaman Kelola Whitelist Nomor HP

## Tugas Utama
- Perbaiki UI/UX pada halaman kelola whitelist nomor HP
- Fokus pada form tambah nomor HP dan tabel daftar

## Langkah-langkah Perbaikan

### 1. Form Tambah Nomor HP
- [x] Tambahkan validasi frontend untuk format nomor HP Indonesia (dimulai dengan 08, panjang 10-13 digit)
- [x] Perbaiki styling input dengan ikon dan placeholder yang lebih deskriptif
- [x] Tambahkan loading state pada tombol submit
- [x] Perbaiki layout grid untuk responsivitas yang lebih baik
- [x] Tambahkan animasi dan feedback visual

### 2. Tabel Daftar Whitelist
- [x] Tambahkan fitur pencarian/filter untuk nomor HP dan role
- [x] Perbaiki styling tabel dengan hover effects dan spacing yang lebih baik
- [x] Tingkatkan badge role dengan warna dan ikon yang lebih menarik
- [x] Pastikan tabel responsif pada perangkat mobile
- [x] Tambahkan animasi untuk baris tabel

### 3. Perbaikan Umum UI/UX
- [x] Tambahkan animasi fade-in untuk elemen utama
- [x] Perbaiki typography dan spacing keseluruhan
- [x] Tingkatkan feedback pengguna dengan toast notifications yang lebih baik
- [x] Pastikan aksesibilitas (ARIA labels, keyboard navigation)
- [x] Optimalkan untuk performa (lazy loading jika diperlukan)

### 4. Testing dan Validasi
- [ ] Test responsivitas pada berbagai ukuran layar
- [ ] Test validasi form
- [ ] Test interaksi pengguna (hover, click, dll.)
- [ ] Verifikasi kompatibilitas browser

## Status
- [x] Analisis file existing
- [x] Implementasi perbaikan form
- [x] Implementasi perbaikan tabel
- [x] Implementasi perbaikan umum
- [ ] Testing

## Tugas Tambahan: Navbar Responsive
- [x] Buat navbar responsive pada layar HP
- [x] Kembalikan kelas 'sm:hidden' pada menu responsive untuk menyembunyikan pada desktop dan tampilkan pada mobile
