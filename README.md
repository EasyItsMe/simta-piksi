# SIMTA-PIKSI (Sistem Informasi Manajemen Tugas Akhir)

SIMTA-PIKSI adalah sebuah platform berbasis web yang dikembangkan menggunakan **Laravel 11** untuk mempermudah, mendigitalkan, dan memonitor seluruh proses pengelolaan Tugas Akhir (TA) mahasiswa secara terpadu.

Sistem ini menghubungkan 3 peran utama (Aktor) dalam satu ekosistem:
1. **Admin / Koordinator TA**
2. **Dosen Pembimbing**
3. **Mahasiswa**

---

## ✨ Fitur Unggulan

### 🎓 Panel Mahasiswa
- **Pengajuan Judul Cerdas**: Mahasiswa dapat mengajukan 2 alternatif judul TA sekaligus.
- **Logbook Bimbingan**: Mencatat setiap aktivitas bimbingan lengkap dengan catatan revisi. Fitur ini telah dilengkapi sistem validasi berurutan (tidak bisa melompat tahapan jika bab sebelumnya belum di-ACC).
- **Indikator Progress Visual**: Menampilkan persentase dan jejak langkah (Step Indicator) yang sangat intuitif untuk melihat bab mana yang sudah selesai.
- **Pendaftaran Sidang**: Mengunggah berkas final dan mendaftar sidang secara mandiri.

### 👨‍🏫 Panel Dosen Pembimbing
- **Review Bimbingan Interaktif**: Menyetujui (ACC) atau menolak progress bimbingan mahasiswa.
- **Lampiran Revisi**: Dosen tidak hanya bisa memberi komentar teks, tetapi juga melampirkan *file* dokumen (PDF/Word) yang berisi coretan revisi untuk diunduh mahasiswa.
- **Daftar Mahasiswa Bimbingan**: Melacak seluruh anak bimbingan dalam satu dasbor rapi.

### 🛡️ Panel Admin
- **Manajemen Akun Massal**: Fitur *Import* data Mahasiswa dan Dosen via file Excel (`.xlsx`), lengkap dengan validasi duplikasi.
- **Plotting Pembimbing**: Menentukan dan menetapkan Dosen Pembimbing 1 dan 2 untuk tiap mahasiswa.
- **Jadwal Sidang & Penilaian**: Mengatur tanggal, ruangan, dan menginput nilai akhir sidang mahasiswa.
- **Laporan PDF (Cetak)**: Mengekspor rekapitulasi kelulusan, jadwal sidang, daftar dosen, dan data mahasiswa ke format PDF profesional.

---

## 🚀 Panduan Instalasi (Local Development)

Ikuti langkah-langkah berikut untuk menjalankan sistem ini di komputer Anda.

### Persyaratan Sistem:
- **PHP** >= 8.2
- **Composer**
- **Node.js** & NPM
- **MySQL** / MariaDB (via XAMPP/Laragon)

### Langkah Instalasi:

1. **Kloning Repositori**
   ```bash
   git clone https://github.com/EasyItsMe/simta-piksi.git
   cd simta-piksi
   ```

2. **Instalasi Dependencies (Backend & Frontend)**
   ```bash
   composer install
   npm install
   npm run build
   ```

3. **Konfigurasi Environment**
   Gandakan file contoh menjadi file rahasia `.env`:
   ```bash
   cp .env.example .env
   ```
   Buka file `.env` dan ubah konfigurasi database Anda menjadi:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=simta_piksi
   DB_USERNAME=root
   DB_PASSWORD=
   ```
   *Jangan lupa buat database kosong bernama `simta_piksi` di phpMyAdmin Anda!*

4. **Generate Key & Migrasi Database**
   ```bash
   php artisan key:generate
   php artisan migrate:fresh --seed
   ```
   *(Perintah ini sekaligus akan membuat akun dummy untuk Admin, Dosen, dan Mahasiswa).*

5. **Nyalakan Server!**
   ```bash
   php artisan serve
   ```
   Buka browser Anda dan akses `http://localhost:8000`.

---

## 🔒 Hak Cipta & Lisensi
Proyek ini dibangun sebagai solusi sistem akademik modern. Dirancang menggunakan prinsip UI/UX yang elegan dan standar arsitektur MVC (Model-View-Controller) yang solid.
