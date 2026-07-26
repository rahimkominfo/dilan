# Dokumentasi Integrasi REST API FAQ Knowledge Base

Dokumen ini berisi spesifikasi teknis dan panduan integrasi REST API FAQ Knowledge Base berbasis **CodeIgniter 4**. API ini dirancang khusus untuk memungkinkan aplikasi pihak ketiga (seperti aplikasi OPD, PKM Sinjai, dll.) mengambil data FAQ dan melakukan pencarian FAQ yang terbatas **hanya pada kategori tertentu**.

---

## 📌 Ringkasan Fitur API

1. **Scoping Kategori yang Ketat**: Hasil pencarian dan pengambilan data FAQ diisolasi sepenuhnya berdasarkan `category_id`. Tidak akan menampilkan data FAQ dari kategori lain.
2. **Fleksibilitas Endpoint**: Mendukung endpoint standar `GET /api/faqs/category/{category_id}` dan endpoint pencarian dedicated `GET /api/faqs/category/{category_id}/search`.
3. **Pencarian Multi-Kolom**: Pencarian dilakukan pada kolom `judul` (pertanyaan) dan `isi` (jawaban).
4. **Respon JSON Terstandarisasi**: Mengembalikan status HTTP yang sesuai (200, 400, 404) dan struktur JSON yang konsisten.

---

## 🛠️ Struktur File Implementasi (CodeIgniter 4)

Semua komponen API telah diimplementasikan dalam struktur berikut:

1. **Route API**: `routes/api.php` (Dimuat di `app/Config/Routes.php`)
2. **Controller API**: `app/Controllers/FaqApiController.php`
3. **Model Data**: `app/Models/InfoModel.php` & `app/Models/KategoriModel.php`
4. **Unit Test**: `tests/unit/FaqApiControllerTest.php`

---

## 📄 Implementasi Kode

### 1. Route API (`routes/api.php`)

```php
<?php

use CodeIgniter\Router\RouteCollection;

/**
 * REST API Routes for Knowledge Base FAQ
 * 
 * @var RouteCollection $routes
 */
$routes->group('api', ['namespace' => 'App\Controllers'], function ($routes) {
    $routes->group('faqs', function ($routes) {
        // Ambil semua FAQ berdasarkan kategori (?search=... atau ?keyword=...)
        $routes->get('category/(:any)/search', 'FaqApiController::search/$1');
        $routes->get('category/(:any)', 'FaqApiController::index/$1');
    });
});
```

Dan didaftarkan pada `app/Config/Routes.php`:

```php
// Load API Routes
if (file_exists(ROOTPATH . 'routes/api.php')) {
    require ROOTPATH . 'routes/api.php';
}
```

---

### 2. Controller API (`app/Controllers/FaqApiController.php`)

```php
<?php

namespace App\Controllers;

use App\Models\InfoModel;
use App\Models\KategoriModel;
use CodeIgniter\RESTful\ResourceController;

class FaqApiController extends ResourceController
{
    protected $infoModel;
    protected $kategoriModel;

    public function __construct()
    {
        $this->infoModel     = new InfoModel();
        $this->kategoriModel = new KategoriModel();
    }

    /**
     * Ambil seluruh FAQ berdasarkan ID Kategori
     * GET /api/faqs/category/{category_id}
     * Juga mendukung query search: GET /api/faqs/category/{category_id}?search=keyword
     */
    public function index($categoryId = null)
    {
        // 1. Validasi Parameter ID Kategori
        if (!is_numeric($categoryId) || (int)$categoryId <= 0) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Parameter category ID tidak valid.'
            ]);
        }

        $categoryId = (int) $categoryId;

        // 2. Cek Keberadaan Kategori
        $category = $this->kategoriModel->find($categoryId);
        if (!$category) {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => 'FAQ tidak ditemukan.'
            ]);
        }

        // 3. Cek Parameter Pencarian (?search=... atau ?keyword=...)
        $search = $this->request->getGet('search') ?? $this->request->getGet('keyword');

        // 4. Query Data FAQ dengan Filter Kategori
        $builder = $this->infoModel->where('kategori_id', $categoryId);

        if (!empty($search)) {
            $search = trim($search);
            $builder->groupStart()
                ->like('judul', $search)
                ->orLike('isi', $search)
            ->groupEnd();
        }

        $faqs = $builder->findAll();

        // 5. Jika Data FAQ Kosong / Tidak Ditemukan
        if (empty($faqs)) {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => 'FAQ tidak ditemukan.'
            ]);
        }

        // 6. Format Resource JSON Response
        $formattedData = array_map(function ($faq) {
            return [
                'id'       => (int) $faq['info_id'],
                'question' => $faq['judul'],
                'answer'   => $faq['isi'],
            ];
        }, $faqs);

        return $this->response->setStatusCode(200)->setJSON([
            'success'  => true,
            'category' => [
                'id'   => (int) $category['kategori_id'],
                'name' => $category['nama_kategori'],
            ],
            'total'    => count($formattedData),
            'data'     => $formattedData,
        ]);
    }

    /**
     * Cari FAQ dalam Kategori Tertentu
     * GET /api/faqs/category/{category_id}/search?keyword=keyword
     */
    public function search($categoryId = null)
    {
        // 1. Validasi Parameter ID Kategori
        if (!is_numeric($categoryId) || (int)$categoryId <= 0) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Parameter category ID tidak valid.'
            ]);
        }

        $categoryId = (int) $categoryId;

        // 2. Cek Keberadaan Kategori
        $category = $this->kategoriModel->find($categoryId);
        if (!$category) {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => 'FAQ tidak ditemukan.'
            ]);
        }

        // 3. Ambil Kata Kunci Pencarian
        $keyword = $this->request->getGet('keyword') ?? $this->request->getGet('search');

        // 4. Query Data FAQ dengan Filter Kategori
        $builder = $this->infoModel->where('kategori_id', $categoryId);

        if (!empty($keyword)) {
            $keyword = trim($keyword);
            $builder->groupStart()
                ->like('judul', $keyword)
                ->orLike('isi', $keyword)
            ->groupEnd();
        }

        $faqs = $builder->findAll();

        // 5. Jika Data FAQ Kosong / Tidak Ditemukan
        if (empty($faqs)) {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => 'FAQ tidak ditemukan.'
            ]);
        }

        // 6. Format Resource JSON Response
        $formattedData = array_map(function ($faq) {
            return [
                'id'       => (int) $faq['info_id'],
                'question' => $faq['judul'],
                'answer'   => $faq['isi'],
            ];
        }, $faqs);

        return $this->response->setStatusCode(200)->setJSON([
            'success'  => true,
            'category' => [
                'id'   => (int) $category['kategori_id'],
                'name' => $category['nama_kategori'],
            ],
            'total'    => count($formattedData),
            'data'     => $formattedData,
        ]);
    }
}
```

---

## 🚀 Dokumentasi Endpoint & Cara Penggunaan

### 1. Ambil Semua FAQ Kategori

Digunakan untuk mengambil seluruh daftar FAQ berdasarkan ID Kategori tertentu.

* **Method**: `GET`
* **URL**: `/api/faqs/category/{category_id}`
* **Contoh Request**:
  ```http
  GET /api/faqs/category/10 HTTP/1.1
  Host: example.com
  Accept: application/json
  ```
* **cURL**:
  ```bash
  curl -X GET "http://example.com/api/faqs/category/10" -H "Accept: application/json"
  ```

---

### 2. Cari FAQ Dalam Kategori

Digunakan untuk melakukan pencarian kata kunci pada pertanyaan (`judul`) atau jawaban (`isi`) FAQ hanya di dalam kategori tertentu.

* **Method**: `GET`
* **URL Options**:
  * `GET /api/faqs/category/{category_id}?search={keyword}`
  * `GET /api/faqs/category/{category_id}/search?keyword={keyword}`
* **Contoh Request**:
  ```http
  GET /api/faqs/category/10?search=login HTTP/1.1
  Host: example.com
  Accept: application/json
  ```
  atau
  ```http
  GET /api/faqs/category/10/search?keyword=login HTTP/1.1
  Host: example.com
  Accept: application/json
  ```
* **cURL**:
  ```bash
  curl -X GET "http://example.com/api/faqs/category/10/search?keyword=login" -H "Accept: application/json"
  ```

---

## 📊 Format Respon JSON

### ✅ Respon Sukses (HTTP Status Code 200 OK)

Returned ketika kategori ditemukan dan minimal terdapat 1 FAQ yang cocok.

```json
{
    "success": true,
    "category": {
        "id": 10,
        "name": "PKM Sinjai"
    },
    "total": 5,
    "data": [
        {
            "id": 1,
            "question": "Bagaimana cara login?",
            "answer": "Silakan login menggunakan akun yang telah terdaftar."
        },
        {
            "id": 2,
            "question": "Bagaimana jika lupa kata sandi login?",
            "answer": "Silakan menghubungi administrator untuk reset kata sandi."
        }
    ]
}
```

---

### ❌ Respon Data Tidak Ditemukan / Kosong (HTTP Status Code 404 Not Found)

Returned jika ID kategori tidak ada di database, atau tidak ada FAQ yang cocok dengan kata kunci pencarian.

```json
{
    "success": false,
    "message": "FAQ tidak ditemukan."
}
```

---

### ⚠️ Respon Parameter Tidak Valid (HTTP Status Code 400 Bad Request)

Returned jika parameter ID kategori bukan merupakan angka atau bernilai `<=` 0.

```json
{
    "success": false,
    "message": "Parameter category ID tidak valid."
}
```

---

## 💡 Contoh Integrasi pada Aplikasi Klien (Client App)

### Contoh 1: Integrasi PHP (Guzzle / cURL)

```php
$categoryId = 10;
$keyword    = 'login';

$url = "http://example.com/api/faqs/category/{$categoryId}/search?keyword=" . urlencode($keyword);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$result = json_decode($response, true);

if ($httpCode === 200 && $result['success']) {
    echo "Kategori: " . $result['category']['name'] . "\n";
    foreach ($result['data'] as $faq) {
        echo "Q: " . $faq['question'] . "\n";
        echo "A: " . $faq['answer'] . "\n\n";
    }
} else {
    echo "Gagal: " . ($result['message'] ?? 'Terjadi kesalahan');
}
```

### Contoh 2: Integrasi JavaScript (Fetch API)

```javascript
async function fetchFaqs(categoryId, keyword = '') {
    const url = keyword 
        ? `/api/faqs/category/${categoryId}/search?keyword=${encodeURIComponent(keyword)}`
        : `/api/faqs/category/${categoryId}`;

    try {
        const response = await fetch(url);
        const data = await response.json();

        if (response.ok && data.success) {
            console.log(`Kategori: ${data.category.name}`);
            console.log(`Total FAQ: ${data.total}`);
            data.data.forEach(item => {
                console.log(`Q: ${item.question}`);
                console.log(`A: ${item.answer}`);
            });
        } else {
            console.error(`Error: ${data.message}`);
        }
    } catch (err) {
        console.error('Network Error:', err);
    }
}

// Panggil contoh:
fetchFaqs(10, 'login');
```

---

## ✅ Pengujian & Verifikasi Automated Test

Seluruh fungsi controller dan route telah diuji secara otomatis menggunakan **PHPUnit** dengan perintah:

```bash
./vendor/bin/phpunit tests/unit/FaqApiControllerTest.php
```

Hasil Pengujian:
```text
OK (6 tests, 25 assertions)
```
Semua skenario (Invalid ID, Category Not Found, Data Success, Search Success, Search Not Found) terverifikasi **100% Lulus**.
