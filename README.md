Rancang Bangun Aplikasi Audit Mutu Internal Standar Pendidikan Berbasis Web Sebagai Pendukung Sistem Penjaminan Mutu Internal Di Politeknik Negeri Banyuwangi

## Deskripsi Sistem

Sistem Informasi Sistem Penjaminan Mutu Internal (SPMI) merupakan aplikasi berbasis web yang dikembangkan untuk mendukung pelaksanaan Sistem Penjaminan Mutu Internal pada standar pendidikan di Politeknik Negeri Banyuwangi. Sistem ini digunakan untuk membantu proses pengelolaan standar mutu, pelaksanaan Audit Mutu Internal (AMI), pengisian penerapan standar, penilaian audit, pencatatan temuan, tindak lanjut temuan, hingga penyusunan kesimpulan dan laporan audit.

Sistem memiliki tiga jenis pengguna utama, yaitu PPMPP/Admin, Auditor, dan Auditee. Setiap pengguna memiliki hak akses dan fitur yang disesuaikan dengan perannya dalam pelaksanaan SPMI.

Sistem dikembangkan menggunakan framework Laravel dan menggunakan basis data untuk menyimpan seluruh data yang berkaitan dengan standar mutu dan pelaksanaan AMI. Dengan adanya sistem ini, proses pengelolaan data mutu dan pelaksanaan audit dapat dilakukan secara lebih terstruktur, terdokumentasi, dan terintegrasi.

---

## Tujuan Sistem

Sistem ini dikembangkan untuk:

1. Membantu pengelolaan data standar mutu pendidikan.
2. Mendukung pengelolaan periode Audit Mutu Internal (AMI).
3. Memfasilitasi pembentukan dan penugasan tim auditor.
4. Memudahkan auditee dalam mengisi data penerapan standar dan bukti pendukung.
5. Memudahkan auditor dalam melakukan penilaian terhadap penerapan standar.
6. Mendukung pencatatan dan pengelolaan temuan audit.
7. Memfasilitasi auditee dalam memberikan tanggapan terhadap temuan.
8. Mendukung pencatatan akar masalah dan rekomendasi perbaikan.
9. Membantu penyusunan kesimpulan dan laporan hasil AMI.
10. Menyediakan informasi hasil audit yang dapat digunakan untuk monitoring dan evaluasi mutu.

---

## Role dan Hak Akses

### 1. PPMPP / Admin

PPMPP atau Admin merupakan pengguna yang bertanggung jawab terhadap pengelolaan sistem dan pelaksanaan kegiatan AMI.

Fitur yang dapat digunakan antara lain:

- Dashboard admin.
- Pengelolaan standar mutu.
- Pengelolaan isi standar mutu.
- Pengelolaan indikator standar.
- Pengelolaan unit kerja.
- Pengelolaan pengguna.
- Pengelolaan periode AMI.
- Pengelolaan standar yang digunakan pada periode AMI.
- Pengelolaan tim AMI.
- Pengelolaan jadwal audit.
- Monitoring penerapan standar.
- Monitoring temuan audit.
- Pengelolaan laporan AMI.
- Melihat informasi dan hasil pelaksanaan audit.

Admin memiliki akses untuk mengelola data utama yang diperlukan dalam pelaksanaan SPMI.

### 2. Auditor

Auditor merupakan pengguna yang bertugas melakukan pemeriksaan dan penilaian terhadap penerapan standar pada unit kerja yang diaudit.

Fitur auditor meliputi:

- Dashboard auditor.
- Melihat periode AMI yang menjadi tugas auditor.
- Melihat data penerapan standar auditee.
- Melakukan pemeriksaan penerapan standar.
- Melakukan penilaian terhadap indikator yang diaudit.
- Menambahkan temuan audit.
- Mengelola temuan audit.
- Menambahkan akar masalah.
- Menambahkan rekomendasi perbaikan.
- Melakukan verifikasi dan menutup temuan.
- Menambahkan kesimpulan audit.
- Mengelola lampiran audit.
- Melihat hasil pelaksanaan audit.

### 3. Auditee

Auditee merupakan pengguna dari unit kerja yang menjadi objek pelaksanaan Audit Mutu Internal.

Fitur auditee meliputi:

- Dashboard auditee.
- Melihat periode AMI yang diikuti.
- Melihat indikator standar yang harus dipenuhi.
- Mengisi data penerapan standar.
- Menambahkan deskripsi hasil penerapan.
- Mengunggah atau menambahkan bukti pendukung.
- Mengubah data penerapan.
- Menghapus data penerapan apabila diperlukan.
- Melihat temuan audit.
- Memberikan tanggapan terhadap temuan.
- Melihat tindak lanjut dan rekomendasi perbaikan.

---

## Fitur Utama Sistem

### 1. Autentikasi Pengguna

Sistem menyediakan halaman login yang digunakan oleh pengguna untuk masuk ke dalam sistem. Pengguna dapat melakukan login menggunakan nama atau email dan password. Setelah berhasil melakukan autentikasi, pengguna diarahkan ke dashboard sesuai dengan role yang dimiliki.

### 2. Dashboard

Setiap role memiliki dashboard yang berbeda sesuai dengan hak aksesnya.

Dashboard menampilkan informasi penting seperti jumlah standar mutu, periode AMI, penerapan standar, dan temuan audit. Dashboard juga menyediakan informasi statistik hasil audit untuk membantu proses monitoring.

### 3. Pengelolaan Standar Mutu

Admin dapat mengelola data standar mutu yang digunakan dalam sistem. Standar mutu dapat memiliki isi standar dan indikator yang menjadi acuan dalam pelaksanaan penerapan serta pemeriksaan standar.

### 4. Pengelolaan Periode AMI

Admin dapat membuat dan mengelola periode Audit Mutu Internal. Data periode mencakup tahun AMI, standar mutu, unit kerja, tujuan audit, lingkup audit, waktu audit, tanggal pembukaan, tanggal penutupan, serta status periode.

### 5. Pengelolaan Tim AMI

Admin dapat menentukan pengguna yang ditugaskan sebagai auditor dalam suatu periode AMI. Penugasan ini digunakan untuk menentukan auditor yang memiliki kewenangan melakukan proses audit pada periode tertentu.

### 6. Pengelolaan Jadwal Audit

Sistem menyediakan pengelolaan jadwal pelaksanaan audit agar kegiatan AMI dapat dilakukan berdasarkan jadwal dan penugasan yang telah ditentukan.

### 7. Penerapan Standar

Auditee dapat mengisi penerapan standar berdasarkan indikator yang tersedia. Data penerapan meliputi deskripsi hasil penerapan dan bukti pendukung yang digunakan sebagai dasar pemeriksaan auditor.

### 8. Penilaian Audit

Auditor dapat melakukan penilaian terhadap penerapan standar berdasarkan indikator yang diaudit. Hasil penilaian menggunakan skala skor yang telah tersedia pada sistem.

### 9. Temuan Audit

Auditor dapat mencatat temuan apabila terdapat ketidaksesuaian atau kondisi yang perlu ditindaklanjuti berdasarkan hasil pemeriksaan penerapan standar.

### 10. Tanggapan Auditee

Auditee dapat memberikan tanggapan atau penjelasan terhadap temuan yang diberikan oleh auditor sebagai bagian dari proses tindak lanjut hasil audit.

### 11. Akar Masalah

Sistem menyediakan fitur untuk mencatat akar masalah dari suatu temuan sehingga penyebab terjadinya ketidaksesuaian dapat terdokumentasi.

### 12. Rekomendasi

Auditor dapat memberikan rekomendasi perbaikan sebagai tindak lanjut terhadap temuan audit yang telah dicatat.

### 13. Verifikasi dan Penutupan Temuan

Auditor dapat melakukan verifikasi terhadap tindak lanjut temuan. Temuan yang telah diverifikasi dapat dinyatakan selesai atau ditutup.

### 14. Kesimpulan Audit

Auditor dapat menambahkan kesimpulan akhir dari pelaksanaan Audit Mutu Internal pada suatu periode.

### 15. Lampiran Audit

Sistem menyediakan fitur untuk mengelola dokumen atau lampiran pendukung yang berkaitan dengan pelaksanaan Audit Mutu Internal.

### 16. Laporan AMI

Sistem menyediakan informasi hasil pelaksanaan AMI yang dapat digunakan untuk melihat dan mendokumentasikan hasil audit pada suatu periode.

---

# Struktur Project

Project ini menggunakan struktur standar framework Laravel dengan beberapa bagian utama sebagai berikut:

```text
spmi-app/
│
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   └── Middleware/
│   │
│   ├── Models/
│   └── Providers/
│
├── bootstrap/
│
├── config/
│
├── database/
│   ├── factories/
│   ├── migrations/
│   └── seeders/
│
├── public/
│   ├── css/
│   ├── js/
│   └── storage/
│
├── resources/
│   └── views/
│       ├── admin/
│       ├── auditor/
│       ├── auditee/
│       ├── auth/
│       └── layouts/
│
├── routes/
│   ├── web.php
│   └── console.php
│
├── storage/
│
├── tests/
│   ├── Feature/
│   └── Unit/
│
├── .env.example
├── artisan
├── composer.json
├── package.json
└── README.md

## Instalasi

**Kebutuhan:** PHP 8.2+, Composer, Node.js 20+, SQLite atau MySQL

```bash
git clone <url-repo>
cd tugas-akhir-2026-wildan2111

composer install
npm install

cp .env.example .env   # kalau ada. kalau belum, buat manual
php artisan key:generate
```

Untuk SQLite, buat file databasenya dulu:

```bash
# Windows
New-Item -ItemType File -Path database/database.sqlite -Force

# Linux/macOS
touch database/database.sqlite
```

Lalu migrasi + seed:

```bash
php artisan migrate --seed
php artisan storage:link
npm run build
php artisan serve
```

Atau pakai skrip bawaan:

```bash
composer setup
composer run dev
```

Buka http://127.0.0.1:8000

### Akun demo

| Username | Password | Role |
|----------|----------|------|
| yovita | password | admin |
| vita | password | auditee |
| auditor@gmail.com| password | auditor|


### MySQL

Di `.env`:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=safety_patrol_k3lh
DB_USERNAME=root
DB_PASSWORD=
```