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
