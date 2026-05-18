# 🛠️ Dokumentasi & Justifikasi Optimasi Database (Zero Waste)
**Proyek:** Sistem Informasi Pelaporan Kejadian Bencana - BPBD NTB  
**Pendekatan:** Extreme Optimization / Zero Waste Data Types

## 📌 Konsep Dasar (Zero Waste)
Pendekatan "Zero Waste" dalam desain arsitektur database bertujuan untuk mengalokasikan tipe data sekecil mungkin yang tetap mampu menampung batas maksimal kebutuhan logika bisnis. 
Daripada menggunakan `BIGINT` (8 bytes) atau `INT` (4 bytes) secara *default* untuk seluruh Primary Key, kita memangkasnya ke `TINYINT` (1 byte), `SMALLINT` (2 bytes), atau `MEDIUMINT` (3 bytes) sesuai dengan domain data.

**Keuntungan Utama:**
1. **Penyimpanan (Storage):** Mengurangi ukuran tabel dan index secara drastis di *disk*.
2. **Performa Memori (RAM):** *B-Tree Index* di MySQL/MariaDB dapat dimuat lebih banyak ke dalam *RAM/Buffer Pool*, sehingga proses pencarian (*Lookups*) dan relasi (*JOINs*) menjadi jauh lebih cepat.
3. **Efisiensi I/O:** Mengurangi beban baca/tulis data ke penyimpanan fisik.

---

## 📊 Justifikasi Perubahan per Tabel

### 1. Tabel Master Referensi Data Skala Kecil
Tabel-tabel ini berisi data *lookup* statis atau master data wilayah yang jumlah record-nya sangat minim dan jarang bertambah signifikan.

* **`disaster_types` (Jenis Bencana)**
  * **PK:** `TINYINT UNSIGNED` (Maks 255 record). 
  * *Justifikasi:* Kategori jenis bencana standar BNPB/BPBD tidak akan pernah melebihi 255 jenis (biasanya hanya berkisar belasan hingga puluhan).
  * *Penyesuaian:* `is_active` menggunakan `TINYINT(1)` menggantikan boolean default agar seragam dengan tipe native MySQL.
* **`regions` (Wilayah / Kabupaten & Kota)**
  * **PK:** `TINYINT UNSIGNED` (Maks 255 record).
  * *Justifikasi:* Di Provinsi NTB, jumlah Kabupaten/Kota hanya ada 10. Kapasitas 255 sudah sangat berlebih bahkan jika ada pemekaran wilayah yang sangat masif. Kolom `name` juga disusutkan menjadi `VARCHAR(50)`.

### 2. Tabel Master Referensi Data Skala Menengah
Tabel wilayah turunan yang jumlah datanya mencakup ratusan hingga ribuan, namun tidak sampai jutaan.

* **`districts` (Kecamatan) & `villages` (Desa/Kelurahan)**
  * **PK:** `SMALLINT UNSIGNED` (Maks 65.535 record).
  * *Justifikasi:* Jumlah kecamatan di NTB adalah 117, dan jumlah desa/kelurahan sekitar 1.143. Kapasitas `SMALLINT` yang mencapai 65 ribu sangat aman dan tepat sasaran. Relasi FK ke tabel induk (`region_id`, `district_id`) diselaraskan mengikuti ukuran tipe data asal.

### 3. Tabel Master Pengguna
* **`users` (Data Pengguna/Admin/Petugas)**
  * **PK:** `MEDIUMINT UNSIGNED` (Maks 16.777.215 record).
  * *Justifikasi:* Karena sistem BPBD ini lebih difokuskan pada petugas/admin internal, penggunaan `MEDIUMINT` (kapasitas ~16,7 Juta) dinilai paling pas. Menghindari `BIGINT` (miliaran) yang mubazir, namun tetap memberi ruang jika ke depan dibuka untuk publik/warga NTB (penduduk NTB ~5 juta jiwa). Panjang string `name` dan `email` diturunkan ke `100` karakter.

### 4. Tabel Transaksi / Dinamis (Data Masif)
Tabel ini akan terus bertambah seiring berjalannya waktu dan pelaporan.

* **`incident_reports` (Laporan Kejadian Utama)**
  * **PK:** `INT UNSIGNED` (Maks 4.294.967.295 record).
  * *Justifikasi:* Dipertahankan sebagai `INT` biasa karena tabel transaksi harian dapat menumpuk hingga jutaan data seiring berjalannya tahun (History). Mengurangi menjadi `MEDIUMINT` berisiko menyebabkan overflow di puluhan tahun ke depan.
  * *Optimasi String:* `report_no` dipendekkan ke 30 karakter (contoh: "REP-2026-0001" hanya belasan karakter), `reporter_phone` dipendekkan ke 20 karakter.
  * *Geospasial:* Kolom koordinat (`latitude`, `longitude`) dibuat sangat spesifik `DECIMAL(10,8)` dan `DECIMAL(11,8)` (akurasi hingga mili-meter), sangat krusial untuk proyeksi peta WebGIS.
  * *Relasi FK:* Semua *Foreign Key* ke tabel master di-downsize tipe datanya agar sejajar dengan referensi aslinya (TINYINT untuk `region_id` & `disaster_type_id`, MEDIUMINT untuk `user_id`).

* **`report_attachments` (Lampiran / Bukti Foto)**
  * **PK & FK:** Tetap `INT UNSIGNED` mengikuti `incident_reports`.
  * *Perubahan Konsep Data:* Kolom `file_size` diubah dari `VARCHAR` ("2.4 MB") menjadi `INT UNSIGNED` (ukuran bytes murni).
  * *Justifikasi:* Menyimpan data file_size sebagai angka murni (*bytes*) memungkinkan database untuk melakukan operasi agregasi matematis seperti `SUM(file_size)` dengan sangat cepat. Format penampilan (seperti KB/MB) harusnya ditangani di layer *Frontend* atau *Accessor/Mutator* di Laravel, bukan di database.

---

## 🎯 Dampak yang Diharapkan
1. **Index Size Optimization:** Pengurangan ukuran tipe data pada kolom yang direlasikan (*Foreign Key*) akan mengecilkan ukuran index (*B-Tree leaves*). Pencarian data laporan per Kabupaten/Kecamatan/Desa menjadi berkali-kali lipat lebih cepat.
2. **Prevention of Human Error:** `file_size` yang murni *integer* akan menghentikan kemungkinan anomali data (misal input "2,4mb", "2.4 MB", "2400 kb").
3. **Data Integrity Standard:** Pemakaian *unsigned* constraint pada seluruh entitas ID memastikan nilai tidak pernah negatif, menggandakan kapasitas positif tipe data tersebut.
