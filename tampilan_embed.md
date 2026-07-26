# Rancangan Tampilan Embed FAQ (Iframe Widget)

Dokumen ini berisi spesifikasi teknis, arsitektur, dan racangan antarmuka (UI/UX) untuk fitur **Halaman Embed FAQ DILAN AR**. Halaman ini dirancang khusus agar dapat dipasang (*embedded*) pada aplikasi pihak ketiga (seperti Portal OPD, Aplikasi PKM, Website Instansi, dll.) menggunakan `<iframe>` secara mulus dan responsif.

---

## 1. 🎯 Latar Belakang & Tujuan

### Masalah Integrasi API Saat Ini
1. **CORS / Domain Policy**: Aplikasi pihak ketiga membutuhkan penanganan *Cross-Origin Resource Sharing* ketika memanggil REST API `FaqApiController`.
2. **Kompleksitas Frontend Pihak Ketiga**: Pihak ketiga harus membuat sendiri tampilan UI (search input, accordion, responsive layout, dsb) dan menangani *parsing* JSON dari API.
3. **Konsistensi UI/UX**: Tampilan FAQ di berbagai aplikasi menjadi berbeda-beda dan membutuhkan penyesuaian CSS berulang.

### Solusi: Halaman Dedicated Embed (`/embed/faq/{category_id}`)
Menyediakan halaman khusus di server **DILAN AR** yang ringan, *clean*, tanpa *header/footer* navigasi utama website, dan dapat disematkan langsung via `<iframe>`. Halaman ini sudah menyatu dengan fitur **Pencarian Real-Time** dan **Accordion FAQ**.

---

## 2. 🏗️ Arsitektur & Alur Kerja Embed

```
+-----------------------------------------------------------------------+
|                       APLIKASI PIHAK KETIGA                           |
|                                                                       |
|  [ Elemen Aplikasi Pihak Ketiga ]                                     |
|  +-----------------------------------------------------------------+  |
|  | <iframe src="https://dilan.domain/embed/faq/5">                  |  |
|  |  +-----------------------------------------------------------+  |  |
|  |  |  🔍 [ Cari pertanyaan... ]                               |  |  |
|  |  |                                                           |  |  |
|  |  |  ▼ Bagaimana cara mendaftar akun?                         |  |  |
|  |  |    Jawaban lengkap dari DILAN AR...                        |  |  |
|  |  |  ▼ Berapa lama proses verifikasi?                         |  |  |
|  |  +-----------------------------------------------------------+  |  |
|  +-----------------------------------------------------------------+  |
+-----------------------------------------------------------------------+
```

---

## 3. 🛠️ Spesifikasi Endpoint & Route

### A. Routing CodeIgniter 4 (`app/Config/Routes.php`)
```php
// Route Embed FAQ untuk Aplikasi Pihak Ketiga
$routes->group('embed', function ($routes) {
    $routes->get('faq/(:num)', 'EmbedController::faq/$1');
    $routes->get('faq', 'EmbedController::faq');
});
```

### B. Parameter URL (Query Strings)
| Parameter | Tipe | Contoh | Deskripsi |
| :--- | :--- | :--- | :--- |
| `category_id` | Path / Num | `/embed/faq/3` | ID Kategori FAQ yang ditampilkan. |
| `search` | Query | `?search=layanan` | Kata kunci pencarian awal (opsional). |
| `theme` | Query | `?theme=light` | Skema warna (`light` atau `clean`). |
| `primary_color` | Query | `?primary_color=1d4ed8` | Custom warna aksen (hex tanpa `#`). |

---

## 4. 🔒 Konfigurasi Keamanan Header (CORS & CSP)

Agar `<iframe>` dapat dimuat oleh domain pihak ketiga tanpa terblokir oleh kebijakan browser (*X-Frame-Options*), Controller Embed akan mengatur header HTTP khusus:

```php
// Menghapus X-Frame-Options SAMEORIGIN untuk halaman embed
$this->response->removeHeader('X-Frame-Options');

// Mengizinkan iframe dipasang di domain manapun (atau domain terdaftar)
$this->response->setHeader('Content-Security-Policy', "frame-ancestors *");
```

---

## 5. 🎨 Rancangan UI/UX Halaman Embed

### Komponen Utama Tampilan:
1. **Search Bar Sticky / Floating**:
   - Live Search (pencarian langsung tanpa reload halaman).
   - Tombol reset/clear search.
2. **Kategori Badge / Header Minimalis**:
   - Menampilkan nama kategori FAQ yang sedang aktif.
3. **FAQ Accordion Items**:
   - Pertanyaan (Title Bar) dengan icon toggle expandable.
   - Jawaban (Expandable Content) dengan format text/HTML rapi.
4. **Empty State**:
   - Tampilan jika data FAQ kosong atau hasil pencarian tidak ditemukan.
5. **Auto-Resize Height Script (`postMessage`)**:
   - Mengirim tinggi konten ke window induk agar iframe menyesuaikan tinggi secara otomatis tanpa scrollbar ganda (*double scrollbar*).

---

## 6. 📝 Draft Kode Implementasi

### A. Controller: `app/Controllers/EmbedController.php`

```php
<?php

namespace App\Controllers;

use App\Models\InfoModel;
use App\Models\KategoriModel;
use CodeIgniter\Controller;

class EmbedController extends Controller
{
    protected $infoModel;
    protected $kategoriModel;

    public function __construct()
    {
        $this->infoModel     = new InfoModel();
        $this->kategoriModel = new KategoriModel();
    }

    public function faq($categoryId = null)
    {
        // 1. Set Security Headers agar bisa di-embed di Iframe Pihak Ketiga
        response()->removeHeader('X-Frame-Options');
        response()->setHeader('Content-Security-Policy', "frame-ancestors *");

        // 2. Ambil parameter pencarian awal jika ada
        $search = $this->request->getGet('search') ?? $this->request->getGet('keyword');

        $category = null;
        $articles = [];

        if (!empty($categoryId) && is_numeric($categoryId)) {
            $category = $this->kategoriModel->find($categoryId);
            
            if ($category) {
                $builder = $this->infoModel->where('kategori_id', $categoryId);
                
                if (!empty($search)) {
                    $search = trim($search);
                    $builder->groupStart()
                        ->like('judul', $search)
                        ->orLike('isi', $search)
                    ->groupEnd();
                }
                
                $articles = $builder->findAll();
            }
        }

        $data = [
            'category' => $category,
            'articles' => $articles,
            'search'   => $search,
            'categoryId' => $categoryId
        ];

        return view('embed/faq', $data);
    }
}
```

---

### B. View: `app/Views/embed/faq.php`

```html
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ Embed - DILAN AR</title>
    <!-- Tailwind CSS & FontAwesome -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: transparent; }
        .faq-answer p { margin-bottom: 0.5rem; }
    </style>
</head>
<body class="font-sans text-slate-800 p-2 sm:p-4">

    <div id="embed-container" class="max-w-4xl mx-auto space-y-4">
        
        <!-- Header & Search Box -->
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-200">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-3">
                <div class="flex items-center space-x-2">
                    <span class="p-2 bg-blue-50 text-blue-600 rounded-lg text-sm font-bold">
                        <i class="fas fa-circle-question"></i>
                    </span>
                    <div>
                        <h2 class="font-bold text-slate-900 text-base">
                            <?= $category ? esc($category['nama_kategori']) : 'Pusat Bantuan & FAQ' ?>
                        </h2>
                        <p class="text-xs text-slate-500">Temukan jawaban atas pertanyaan umum Anda.</p>
                    </div>
                </div>
            </div>

            <!-- Input Pencarian -->
            <div class="relative">
                <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                <input 
                    type="text" 
                    id="faq-search-input" 
                    onkeyup="filterFaq()" 
                    value="<?= esc($search) ?>"
                    placeholder="Ketik kata kunci pencarian FAQ..." 
                    class="w-full pl-10 pr-10 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all"
                >
                <button id="clear-search" onclick="clearSearch()" class="hidden absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                    <i class="fas fa-times-circle"></i>
                </button>
            </div>
        </div>

        <!-- Daftar FAQ Accordion -->
        <div class="space-y-3" id="faq-list">
            <?php if (!empty($articles)): ?>
                <?php foreach ($articles as $index => $item): ?>
                    <div class="faq-card bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm hover:border-blue-300 transition-all">
                        <button onclick="toggleFaq('faq-item-<?= $item['info_id'] ?>')" class="w-full text-left px-5 py-3.5 font-semibold text-slate-800 flex items-center justify-between gap-3 hover:bg-slate-50 transition-colors">
                            <span class="faq-question text-sm leading-snug"><?= esc($item['judul']) ?></span>
                            <i id="icon-faq-item-<?= $item['info_id'] ?>" class="fas fa-chevron-down text-slate-400 text-xs shrink-0 transition-transform duration-200"></i>
                        </button>
                        <div id="content-faq-item-<?= $item['info_id'] ?>" class="hidden px-5 pb-4 pt-2 border-t border-slate-100 text-xs sm:text-sm text-slate-600 leading-relaxed bg-slate-50/50 faq-answer">
                            <?= $item['isi'] ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div id="empty-state" class="bg-white rounded-2xl p-8 text-center border border-slate-200">
                    <i class="fas fa-search-minus text-3xl text-slate-300 mb-2"></i>
                    <p class="text-sm font-semibold text-slate-600">FAQ Tidak Ditemukan</p>
                    <p class="text-xs text-slate-400 mt-1">Tidak ada data FAQ yang cocok dengan pencarian Anda.</p>
                </div>
            <?php endif; ?>

            <!-- State Kosong jika Client Filter 0 Hasil -->
            <div id="no-result-state" class="hidden bg-white rounded-2xl p-8 text-center border border-slate-200">
                <i class="fas fa-search-minus text-3xl text-slate-300 mb-2"></i>
                <p class="text-sm font-semibold text-slate-600">Hasil tidak ditemukan</p>
                <p class="text-xs text-slate-400 mt-1">Coba gunakan kata kunci pencarian yang lain.</p>
            </div>
        </div>

    </div>

    <!-- Script Accordion, Live Search & Auto Height PostMessage -->
    <script>
        function toggleFaq(id) {
            const content = document.getElementById('content-' + id);
            const icon = document.getElementById('icon-' + id);

            if (content.classList.contains('hidden')) {
                content.classList.remove('hidden');
                icon.classList.add('rotate-180');
            } else {
                content.classList.add('hidden');
                icon.classList.remove('rotate-180');
            }
            sendHeightToParent();
        }

        function filterFaq() {
            const input = document.getElementById('faq-search-input');
            const clearBtn = document.getElementById('clear-search');
            const filter = input.value.toLowerCase().trim();
            const cards = document.querySelectorAll('.faq-card');
            const noResultState = document.getElementById('no-result-state');
            let visibleCount = 0;

            if (filter.length > 0) {
                clearBtn.classList.remove('hidden');
            } else {
                clearBtn.classList.add('hidden');
            }

            cards.forEach(card => {
                const question = card.querySelector('.faq-question').textContent.toLowerCase();
                const answer = card.querySelector('.faq-answer').textContent.toLowerCase();

                if (question.includes(filter) || answer.includes(filter)) {
                    card.style.display = "";
                    visibleCount++;
                } else {
                    card.style.display = "none";
                }
            });

            if (noResultState) {
                if (visibleCount === 0 && cards.length > 0) {
                    noResultState.classList.remove('hidden');
                } else {
                    noResultState.classList.add('hidden');
                }
            }

            sendHeightToParent();
        }

        function clearSearch() {
            const input = document.getElementById('faq-search-input');
            input.value = '';
            filterFaq();
        }

        // Auto Resize Height Iframe ke Host Window
        function sendHeightToParent() {
            setTimeout(() => {
                const height = document.getElementById('embed-container').offsetHeight + 40;
                window.parent.postMessage({
                    type: 'dilan_faq_resize',
                    height: height
                }, '*');
            }, 50);
        }

        window.addEventListener('load', sendHeightToParent);
        window.addEventListener('resize', sendHeightToParent);
    </script>
</body>
</html>
```

---

## 7. 💻 Panduan Integrasi Bagi Aplikasi Pihak Ketiga

Aplikasi lain dapat memasang widget FAQ ini dengan memilih salah satu cara berikut:

### Opsi A: Iframe Sederhana (Tinggi Statis)
```html
<!-- Gantilah angka 5 dengan ID Kategori FAQ Anda -->
<iframe 
    src="https://dilan.domain/embed/faq/5" 
    width="100%" 
    height="600px" 
    style="border:none; overflow:hidden;"
    title="FAQ DILAN AR">
</iframe>
```

### Opsi B: Iframe Responsif Auto-Height (Rekomendasi ⭐)
Agar iframe secara otomatis menyesuaikan tingginya tanpa ada scrollbar ganda:

```html
<!-- Iframe Element -->
<iframe 
    id="dilan-faq-frame" 
    src="https://dilan.domain/embed/faq/5" 
    width="100%" 
    height="400px" 
    style="border:none; transition: height 0.2s ease;"
    title="FAQ DILAN AR">
</iframe>

<!-- Script Resizer Parent Window -->
<script>
    window.addEventListener('message', function(event) {
        if (event.data && event.data.type === 'dilan_faq_resize') {
            document.getElementById('dilan-faq-frame').style.height = event.data.height + 'px';
        }
    });
</script>
```

---

## 8. 📋 Checklist Rencana Pelaksanaan

- [ ] Membaca dan menyetujui rancangan di file `tampilan_embed.md`.
- [ ] Membuat file Controller `app/Controllers/EmbedController.php`.
- [ ] Membuat file View `app/Views/embed/faq.php`.
- [ ] Menambahkan rute pada `app/Config/Routes.php`.
- [ ] Uji coba tampilan pada browser dan simulasi `<iframe>`.
