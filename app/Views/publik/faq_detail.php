<?= $this->extend('layouts/main_publik_blank') ?>

<?= $this->section('content') ?>
<!-- Navbar -->
<nav class="bg-gradient-to-r from-brand-700 via-brand-600 to-indigo-700 shadow-md text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
        <a href="<?= base_url('/') ?>" class="font-extrabold text-xl flex items-center space-x-2 text-white">
            <i class="fas fa-book-reader text-yellow-300"></i>
            <span>FAQ SISTEM</span>
        </a>
        <a href="<?= base_url('/') ?>" class="text-sm font-semibold hover:text-yellow-300 flex items-center space-x-1 text-white">
            <i class="fas fa-arrow-left text-xs"></i>
            <span>Kembali ke Beranda</span>
        </a>
    </div>
</nav>

<!-- Main Content -->
<main class="flex-grow max-w-4xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <!-- Back and Category Row -->
    <div class="flex items-center justify-between mb-6">
        <button onclick="history.back()" class="px-5 py-2 bg-white hover:bg-slate-100 border border-slate-200 text-slate-700 rounded-full text-sm font-semibold shadow-sm transition-all flex items-center space-x-2">
            <i class="fas fa-chevron-left text-xs"></i>
            <span>Kembali</span>
        </button>
        <span class="text-xs bg-indigo-50 border border-indigo-200 text-indigo-700 px-3.5 py-1.5 rounded-full font-bold uppercase">Kategori: <?= esc($category['nama_kategori'] ?? 'Umum') ?></span>
    </div>

    <!-- Detail Card -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 sm:p-10">
        <div class="border-b border-slate-100 pb-5 mb-6 flex justify-between items-start">
            <div>
                <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 leading-tight"><?= esc($article['judul']) ?></h1>
                <p class="text-xs text-slate-400 mt-2"><i class="fas fa-clock mr-1"></i>Diperbarui tanggal <?= date('d-m-Y', strtotime($article['tgl_update'] ?? $article['tgl_buat'])) ?></p>
            </div>
        </div>

        <!-- Content -->
        <div class="prose max-w-none text-slate-600 space-y-4">
            <?= format_article_images($article['isi']) ?>
        </div>
    </div>
</main>

<footer class="bg-slate-900 text-slate-400 py-6 border-t border-slate-800 text-center text-xs">
    &copy; <?= date('Y') ?> Pemerintah Kabupaten. Semua hak cipta dilindungi.
</footer>
<?= $this->endSection() ?>
