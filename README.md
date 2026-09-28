# 💡 DILAN - Digital Information & Knowledge Base System

**DILAN** adalah platform terintegrasi basis pengetahuan (*Knowledge Base*), pusat edukasi digital, dan Frequently Asked Questions (FAQ) Pemerintah Kabupaten Sinjai. Platform ini memfasilitasi integrasi satu pintu (*Single Source of Truth*) untuk informasi layanan publik dari seluruh OPD/Puskesmas/Unit Kerja ke aplikasi dan portal web daerah.

---

## 🚀 Fitur Utama

- 🏢 **Multi-Tenant & Multi-Category OPD Management**: Pengelolaan artikel dan informasi berbasis kategori OPD/Puskesmas dengan dukungan akun OPD yang dapat menangani multi-kategori (*Multi-Category Switcher*).
- 🔌 **RESTful API Ingestion & Public Knowledge API**:
  - Ingestion data FAQ otomatis dari aplikasi luar/eksternal OPD menggunakan autentikasi Header `X-API-KEY`.
  - Endpoint publik untuk pencarian instan dan penyajian data knowledge base ke portal web.
- 🖼️ **Widget Embed Responsif**: Modul iframe siap pasang pada website OPD atau aplikasi pihak ketiga tanpa perlu coding ulang antarmuka FAQ.
- 🎨 **Modern & Clean UI/UX**: Antarmuka responsif berbasis Tailwind CSS dengan desain kartu modern, soft shadow, micro-interaction yang smooth, dan standar aksi tabel *icon-only*.
- 🔑 **Otentikasi Terintegrasi (API Pegawai Sinjai)**: Terhubung langsung dengan Single Sign-On / API Pegawai BKPPD & Diskominfo Sinjai.

---

## 🛠️ Tech Stack & Lingkungan Server

| Komponen | Spesifikasi / Keterangan |
| :--- | :--- |
| **Framework** | CodeIgniter 4 (PHP 8.2+) |
| **Database** | MariaDB 10.x / 12.x (`MySQLi` Driver) |
| **Styling** | Tailwind CSS + FontAwesome 6 |
| **Server Stack** | Apache 2.4 + mod_php (Termux / Linux Server) |
| **Format Commit** | Standar Git: `YYMMDD - [Tipe]: Deskripsi` |

---

## ⚙️ Panduan Instalasi & Konfigurasi

### 1. Clone Repositori
```bash
git clone git@github.com:rahimkominfo/dilan.git
cd dilan
```

### 2. Konfigurasi Environment (`.env`)
Salin atau buat file `.env` di root project:
```ini
CI_ENVIRONMENT = development

# APP
app.baseURL = 'http://cepad/dilan/'

# DATABASE
database.default.hostname = 127.0.0.1
database.default.database = dilan_db
database.default.username = root
database.default.password = 
database.default.DBDriver = MySQLi
database.default.port = 3306
```

### 3. Migrasi & Skema Database
Impor file skema database ke MariaDB:
```bash
mariadb -u root -e "CREATE DATABASE IF NOT EXISTS dilan_db;"
mariadb -u root dilan_db < schema.sql
```

---

## 🌐 Dokumentasi Endpoint API

Base URL API: `http://<domain_atau_ip>/dilan/api/`

### 1. Endpoint Publik (Read-Only)
- `GET /api/faqs/category/{kode_kategori}` : Mengambil daftar FAQ per kategori/OPD.
- `GET /api/faqs/category/{kode_kategori}/search?q={keyword}` : Pencarian artikel FAQ.
- `GET /api/faqs/detail/{id_info}` : Mengambil detail 1 artikel FAQ lengkap.

### 2. Endpoint Pengiriman Data (Wajib Header `X-API-KEY`)
- `POST /api/faqs` : Pengiriman 1 data FAQ baru.
- `POST /api/faqs/category/{kode_kategori}` : Pengiriman data FAQ langsung terikat ke kategori OPD.
- `POST /api/faqs/batch` : Pengiriman data FAQ secara massal (*bulk ingestion*).

Contoh request cURL:
```bash
curl -X POST "http://cepad/dilan/api/faqs/category/nama-kategori" \
  -H "Content-Type: application/json" \
  -H "X-API-KEY: your_api_key_here" \
  -d '{
    "judul": "Bagaimana cara mendaftar antrean online?",
    "isi": "<p>Silakan gunakan menu pendaftaran pada aplikasi.</p>",
    "kata_kunci": "antrean, pendaftaran"
  }'
```

---

## 👥 Pengembang & Hak Cipta

- **Pengembang**: Muhammad Rusyaid, S.Kom., M.Si. (Pranata Komputer Ahli Muda Diskominfo Sinjai / Software House Developer)
- **Instansi**: Dinas Komunikasi, Informatika, dan Persandian Kabupaten Sinjai
- **Lisensi**: MIT License
