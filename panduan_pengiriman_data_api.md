# Panduan Mekanisme Pengiriman Data via API dari Aplikasi Eksternal (API Data Ingestion)

Dokumen ini adalah spesifikasi teknis dan panduan resmi bagi pengembang aplikasi luar (seperti aplikasi OPD, PKM, portal layanan publik, atau sistem pihak ketiga) untuk **mengirimkan, memperbarui, menyinkronkan (batch/bulk sync), dan menghapus data FAQ/Informasi** ke sistem **DILAN AR** melalui REST API.

---

## 1. 🏗️ Arsitektur & Alur Pengiriman Data

```
+--------------------------------------------------------------------------+
|                       APLIKASI LAIN (KLIEN / OPD)                        |
|   (Misal: Aplikasi Peduli Pensiun, Website PKM, Portal OPD, dll.)        |
+--------------------------------------------------------------------------+
                                     |
               HTTP Request (JSON Payload + Header X-API-KEY)
                                     |
                                     v
+--------------------------------------------------------------------------+
|                          SERVER DILAN AR (API)                           |
|                                                                          |
|  [1. ApiKeyFilter] -------------------------------------------------+    |
|      - Memvalidasi HTTP Header `X-API-KEY` atau Bearer Token        |    |
|      - Mengidentifikasi OPD dan mengunci kategori (Data Scoping)    |    |
|                                                                     |    |
|  [2. FaqApiController] <--------------------------------------------+    |
|      - Validasi Input (judul, isi, kata_kunci)                           |
|      - Pencegahan Tampering (OPD hanya bisa kelola kategori miliknya)   |
|      - Insert / Update / Batch / Delete                                  |
|                                                                          |
|  [3. Database Storage & Audit]                                           |
|      - Tabel `info`: Data FAQ/Informasi tersimpan                        |
|      - Tabel `operator`: Log riwayat perubahan (CREATE, UPDATE, DELETE)  |
+--------------------------------------------------------------------------+
```

---

## 2. 🔑 Kredensial & Autentikasi API

Setiap aplikasi OPD atau pengembang pihak ketiga harus menyertakan **Kunci API (API Key)** pada setiap request HTTP.

### Cara Menemukan API Key Anda:
1. **Bagi Pengguna OPD**: Buka menu **Dashboard User OPD** (`/admin/user_info`), API Key Anda tertera di banner bagian atas.
2. **Bagi Administrator Sistem**: Buka menu **Kelola User OPD** (`/admin/user_opd`), kolom **Kunci API** menampilkan key untuk tiap OPD dan tombol untuk generate ulang (*regenerate*).

### Format Header HTTP (Wajib):
```http
X-API-KEY: dilan_key_xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
```
*Atau menggunakan format Bearer Authorization:*
```http
Authorization: Bearer dilan_key_xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
```

> **Catatan Keamanan (Data Scoping):**
> Setiap API Key milik OPD terikat secara otomatis ke ID Kategori masing-masing. Sistem akan secara otomatis mengarahkan data yang Anda kirim ke kategori Anda dan mencegah perubahan pada data milik OPD lain.

---

## 3. 📡 Ringkasan Endpoint API

| Method | Endpoint | Fungsi | Autentikasi |
| :--- | :--- | :--- | :--- |
| `GET` | `/api/ping` | Uji konektivitas & cek identitas API Key | Wajib |
| `GET` | `/api/faqs/my` | Mengambil seluruh FAQ milik OPD Anda | Wajib |
| `POST` | `/api/faqs` | Mengirim dan menyimpan 1 data FAQ baru | Wajib |
| `POST` | `/api/faqs/send` | Alias alternatif untuk mengirim data FAQ baru | Wajib |
| `POST` | `/api/faqs/batch` | Mengirim data FAQ massal sekaligus (*bulk sync*) | Wajib |
| `PUT` | `/api/faqs/{id}` | Memperbarui data FAQ yang sudah ada | Wajib |
| `POST` | `/api/faqs/update/{id}` | Alternatif pembaruan data (jika PUT diblokir) | Wajib |
| `DELETE` | `/api/faqs/{id}` | Menghapus data FAQ | Wajib |
| `POST` | `/api/faqs/delete/{id}` | Alternatif penghapusan data (jika DELETE diblokir) | Wajib |
| `GET` | `/api/faqs/detail/{id}` | Melihat detail 1 data FAQ | Publik |
| `GET` | `/api/faqs/category/{id}` | Melihat daftar FAQ dalam kategori tertentu | Publik |

---

## 4. 📝 Spesifikasi Lengkap Endpoint

### 4.1. Uji Koneksi & Identitas (`GET /api/ping`)
Gunakan endpoint ini untuk memastikan API Key Anda aktif dan mengenali kategori serta hak akses Anda.

* **URL**: `/api/ping`
* **Method**: `GET`
* **Headers**: `X-API-KEY: [YOUR_API_KEY]`
* **Contoh Respon (200 OK)**:
```json
{
    "success": true,
    "message": "Koneksi API Dilan berhasil terhubung.",
    "authenticated_as": {
        "nip": "198611052009042003",
        "role": "user",
        "kategori_id": 6,
        "nama_kategori": "Peduli Pensiun",
        "url_apk": "http://apps.sinjaikab.go.id/peduli-pensiun/",
        "is_master": false
    },
    "server_time": "2026-09-28 00:58:03"
}
```

---

### 4.2. Mengirim Data FAQ Tunggal (`POST /api/faqs`)
Digunakan oleh aplikasi lain untuk mengirim data artikel/FAQ baru.

* **URL**: `/api/faqs` (atau `/api/faqs/send`)
* **Method**: `POST`
* **Headers**:
  * `Content-Type: application/json`
  * `X-API-KEY: [YOUR_API_KEY]`
* **Payload Request (JSON)**:
```json
{
  "judul": "Bagaimana cara mendaftar antrean online?",
  "isi": "<p>Silakan kunjungi menu antrean di aplikasi kami dan isi formulir pendaftaran.</p>",
  "kata_kunci": "antrean, pendaftaran, online"
}
```
*Parameter Opsional untuk Admin Master:*
* `kategori_id`: *(Integer)* Wajib jika menggunakan Master API Key, opsional untuk User OPD karena sudah terkunci ke kategorinya.

* **Respon Sukses (201 Created)**:
```json
{
    "success": true,
    "message": "Data FAQ/Informasi berhasil dikirim dan disimpan ke sistem.",
    "data": {
        "id": 64,
        "judul": "Bagaimana cara mendaftar antrean online?",
        "isi": "<p>Silakan kunjungi menu antrean di aplikasi kami dan isi formulir pendaftaran.</p>",
        "kata_kunci": "antrean, pendaftaran, online",
        "kategori_id": 6,
        "nama_kategori": "Peduli Pensiun",
        "dibuat_oleh": "API (198611052009042003)",
        "tgl_buat": "2026-09-28 08:30:00"
    }
}
```

---

### 4.3. Mengirim Data FAQ Massal / Sinkronisasi (`POST /api/faqs/batch`)
Sangat cocok untuk sinkronisasi database aplikasi lain ke Dilan sekaligus (maksimal 100 data per request).

* **URL**: `/api/faqs/batch` (atau `/api/faqs/bulk`)
* **Method**: `POST`
* **Headers**:
  * `Content-Type: application/json`
  * `X-API-KEY: [YOUR_API_KEY]`
* **Payload Request (JSON)**:
```json
{
  "items": [
    {
      "judul": "Berapa batas usia pensiun PNS?",
      "isi": "<p>Batas usia pensiun berkisar antara 58 sampai 65 tahun.</p>",
      "kata_kunci": "usia pensiun, bup"
    },
    {
      "judul": "Di mana lokasi pengambilan SK?",
      "isi": "<p>SK dapat diambil di loket pelayanan BKPSDMA Sinjai.</p>",
      "kata_kunci": "sk pensiun, loket"
    }
  ]
}
```
* **Respon Sukses (201 Created)**:
```json
{
    "success": true,
    "message": "Proses pengiriman data FAQ massal selesai.",
    "total_success": 2,
    "total_failed": 0,
    "data": [
        { "id": 65, "judul": "Berapa batas usia pensiun PNS?", "kategori_id": 6 },
        { "id": 66, "judul": "Di mana lokasi pengambilan SK?", "kategori_id": 6 }
    ],
    "failed": []
}
```

---

### 4.4. Memperbarui Data FAQ (`PUT /api/faqs/{id}`)
Digunakan untuk mengupdate pertanyaan, isi jawaban, atau kata kunci dari FAQ yang sudah pernah dikirim.

* **URL**: `/api/faqs/{id}` (atau `POST /api/faqs/update/{id}`)
* **Method**: `PUT` atau `POST`
* **Headers**:
  * `Content-Type: application/json`
  * `X-API-KEY: [YOUR_API_KEY]`
* **Payload Request (JSON)**:
```json
{
  "judul": "Bagaimana cara mendaftar antrean online? (Revisi)",
  "isi": "<p>Informasi alur pendaftaran terbaru tahun 2026...</p>",
  "kata_kunci": "antrean, pendaftaran, revisi"
}
```
* **Respon Sukses (200 OK)**:
```json
{
    "success": true,
    "message": "Data FAQ/Informasi berhasil diperbarui.",
    "data": {
        "id": 64,
        "judul": "Bagaimana cara mendaftar antrean online? (Revisi)",
        "isi": "<p>Informasi alur pendaftaran terbaru tahun 2026...</p>",
        "kata_kunci": "antrean, pendaftaran, revisi",
        "kategori_id": 6,
        "diperbarui_oleh": "API (198611052009042003)",
        "tgl_update": "2026-09-28 08:35:00"
    }
}
```

---

### 4.5. Menghapus Data FAQ (`DELETE /api/faqs/{id}`)
Digunakan untuk menghapus artikel FAQ. Hanya pemilik kategori yang dapat menghapus data miliknya.

* **URL**: `/api/faqs/{id}` (atau `POST /api/faqs/delete/{id}`)
* **Method**: `DELETE` atau `POST`
* **Headers**: `X-API-KEY: [YOUR_API_KEY]`
* **Respon Sukses (200 OK)**:
```json
{
    "success": true,
    "message": "Data FAQ dengan ID 64 berhasil dihapus.",
    "deleted_id": 64
}
```

---

### 4.6. Mengambil Daftar FAQ Tersimpan Milik OPD (`GET /api/faqs/my`)
* **URL**: `/api/faqs/my` (Mendukung parameter `?search=keyword`)
* **Method**: `GET`
* **Headers**: `X-API-KEY: [YOUR_API_KEY]`
* **Respon Sukses (200 OK)**:
```json
{
    "success": true,
    "category": {
        "id": 6,
        "name": "Peduli Pensiun"
    },
    "total": 5,
    "data": [
        {
            "id": 64,
            "judul": "Bagaimana cara mendaftar antrean online?",
            "isi": "<p>Jawaban...</p>",
            "kata_kunci": "antrean",
            "kategori_id": 6,
            "tgl_buat": "2026-09-28 08:30:00",
            "tgl_update": "2026-09-28 08:35:00",
            "dibuat_oleh": "API (198611052009042003)",
            "diperbarui_oleh": "API (198611052009042003)"
        }
    ]
}
```

---

## 5. 💻 Contoh Kode Implementasi di Aplikasi Klien

### 5.1. PHP (cURL Murni)
```php
<?php
$apiKey = 'dilan_key_927f583a2b5f49c9ecac5927ce0fa817';
$url    = 'http://example.com/api/faqs';

$payload = [
    'judul'      => 'Bagaimana cara verifikasi dokumen pensiun?',
    'isi'        => '<p>Silakan upload dokumen melalui aplikasi Peduli Pensiun.</p>',
    'kata_kunci' => 'verifikasi, dokumen, pensiun'
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'X-API-KEY: ' . $apiKey
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$result = json_decode($response, true);
if ($httpCode === 201 && $result['success']) {
    echo "Sukses disimpan dengan ID: " . $result['data']['id'];
} else {
    echo "Gagal: " . ($result['message'] ?? 'Error');
}
```

### 5.2. PHP (Laravel / Guzzle HTTP Client)
```php
use Illuminate\Support\Facades\Http;

$response = Http::withHeaders([
    'X-API-KEY' => 'dilan_key_927f583a2b5f49c9ecac5927ce0fa817'
])->post('http://example.com/api/faqs', [
    'judul'      => 'Pertanyaan dari Sistem PKM',
    'isi'        => '<p>Jawaban lengkap...</p>',
    'kata_kunci' => 'pkm, faskes'
]);

if ($response->successful()) {
    $data = $response->json();
    echo "ID Data: " . $data['data']['id'];
}
```

### 5.3. JavaScript (Browser Fetch / Node.js)
```javascript
async function kirimDataFaq() {
  const url = 'http://example.com/api/faqs';
  const apiKey = 'dilan_key_927f583a2b5f49c9ecac5927ce0fa817';

  const body = {
    judul: 'Alur Pelayanan Pasien Rawat Jalan',
    isi: '<p>Pasien mengambil nomor antrean di loket utama...</p>',
    kata_kunci: 'rawat jalan, antrean'
  };

  try {
    const res = await fetch(url, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-API-KEY': apiKey
      },
      body: JSON.stringify(body)
    });

    const data = await res.json();
    if (res.ok && data.success) {
      console.log('Berhasil disimpan:', data.data.id);
    } else {
      console.error('Gagal:', data.message);
    }
  } catch (err) {
    console.error('Network Error:', err);
  }
}

kirimDataFaq();
```

### 5.4. Python (Requests Library)
```python
import requests

url = "http://example.com/api/faqs"
headers = {
    "Content-Type": "application/json",
    "X-API-KEY": "dilan_key_927f583a2b5f49c9ecac5927ce0fa817"
}
payload = {
    "judul": "Syarat Pendaftaran Poli Gigi",
    "isi": "<p>Membawa KTP dan kartu BPJS yang masih aktif.</p>",
    "kata_kunci": "poli gigi, bpjs"
}

response = requests.post(url, json=payload, headers=headers)
result = response.json()

if response.status_code == 201 and result.get("success"):
    print(f"Sukses disimpan! ID: {result['data']['id']}")
else:
    print(f"Gagal: {result.get('message')}")
```

### 5.5. cURL (Terminal / Command Line)
```bash
curl -X POST "http://example.com/api/faqs" \
  -H "Content-Type: application/json" \
  -H "X-API-KEY: dilan_key_927f583a2b5f49c9ecac5927ce0fa817" \
  -d '{
    "judul": "Tanya jawab dari terminal",
    "isi": "<p>Isi jawaban dari terminal.</p>",
    "kata_kunci": "curl, terminal"
  }'
```

---

## 6. ⚠️ Respon Kode Kesalahan (Error Handling)

| Kode HTTP | Error Code | Arti & Solusi |
| :--- | :--- | :--- |
| `400 Bad Request` | - | Validasi data gagal (misal judul/isi kosong atau format JSON tidak valid). Periksa objek `errors`. |
| `401 Unauthorized` | `API_KEY_MISSING` / `INVALID_API_KEY` | Header `X-API-KEY` tidak disertakan atau salah. Periksa kembali API Key Anda. |
| `403 Forbidden` | `FORBIDDEN_CATEGORY` | Anda mencoba mengirim/mengubah/menghapus data milik kategori OPD lain yang bukan wewenang Anda. |
| `404 Not Found` | - | Data FAQ dengan ID tersebut atau Kategori tidak ditemukan. |
| `500 Internal Error` | - | Terjadi kesalahan pada server/database. |

---

## 7. ✅ Verifikasi Pengujian Otomatis (PHPUnit)

Fitur ini dilengkapi unit & feature test lengkap yang dapat dijalankan kapan saja:
```bash
./vendor/bin/phpunit tests/unit/FaqApiSendTest.php
```
Hasil uji:
```text
OK (12 tests, 43 assertions)
```
Semua kasus uji (Missing Key, Invalid Key, Master Key, OPD Key, Validation, Isolation Scoping 403, Single Create, Batch Create, Update, Delete, List) teruji **100% Lulus**.
