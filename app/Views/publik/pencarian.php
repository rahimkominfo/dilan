<?= $this->extend('layouts/main_publik_blank') ?>

<?= $this->section('content') ?>
<!-- Navbar -->
<nav class="bg-gradient-to-r from-brand-700 via-brand-600 to-indigo-700 shadow-md text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
        <a href="<?= base_url('/') ?>" class="font-extrabold text-xl flex items-center space-x-2 text-white">
            <i class="fas fa-magnifying-glass text-yellow-300"></i>
            <span>PENCARIAN DILAN</span>
        </a>
        <a href="<?= base_url('/') ?>" class="text-sm font-semibold hover:text-yellow-300 flex items-center space-x-1 text-white">
            <i class="fas fa-home"></i>
            <span>Beranda</span>
        </a>
    </div>
</nav>

<!-- Main Content -->
<main class="flex-grow max-w-6xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <!-- Search Input Bar Header -->
    <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <nav class="flex text-xs font-semibold text-slate-500" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-2">
                <li><a href="<?= base_url('/') ?>" class="hover:text-brand-600"><i class="fas fa-home mr-1"></i>Beranda</a></li>
                <li class="flex items-center text-slate-300"><i class="fas fa-chevron-right text-[8px] mx-1"></i></li>
                <li class="text-slate-800">Hasil Pencarian</li>
            </ol>
        </nav>

        <form action="<?= base_url('pencarian') ?>" method="GET" class="flex gap-2 flex-grow md:max-w-md">
            <input type="text" name="cari" placeholder="Masukkan kata kunci baru..." value="<?= esc($keyword) ?>" class="px-4 py-2.5 rounded-2xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-500 text-sm w-full bg-white">
            <button type="submit" class="px-5 py-2.5 bg-brand-600 hover:bg-brand-500 text-white rounded-2xl text-sm font-bold transition-all flex items-center justify-center space-x-1">
                <i class="fas fa-search"></i>
                <span>Cari</span>
            </button>
        </form>
    </div>

    <!-- Search Results Header -->
    <div class="mb-6 px-2">
        <h2 class="text-lg font-bold text-slate-900">Hasil Pencarian untuk: <span class="text-brand-600">"<?= esc($keyword) ?>"</span></h2>
        <p class="text-xs text-slate-500 mt-1">Ditemukan <?= count($articles) ?> artikel panduan yang sesuai.</p>
    </div>

    <!-- Result Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <?php if (!empty($articles)): ?>
            <?php foreach ($articles as $article): ?>
            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm flex flex-col justify-between card-hover">
                <div>
                    <span class="text-[10px] font-bold text-brand-600 bg-brand-50 px-2.5 py-1 rounded-md uppercase"><?= esc($article['nama_kategori'] ?? 'Umum') ?></span>
                    <h3 class="text-base font-bold text-slate-900 mt-3"><?= esc($article['judul']) ?></h3>
                    <?php 
                        $snippet = strip_tags($article['isi']);
                        $snippet = str_ireplace('&nbsp;', ' ', $snippet);
                        $snippet = html_entity_decode($snippet, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                        $snippet = strlen($snippet) > 120 ? substr($snippet, 0, 120) . '...' : $snippet;
                    ?>
                    <p class="text-xs text-slate-500 mt-2 leading-relaxed"><?= esc($snippet) ?></p>
                </div>
                <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
                    <a href="<?= base_url('detail/' . $article['info_id']) ?>" class="text-xs font-bold text-brand-600 hover:text-brand-700 flex items-center space-x-1">
                        <span>Selengkapnya</span>
                        <i class="fas fa-arrow-right text-[10px]"></i>
                    </a>
                    <span class="text-[10px] text-slate-400"><i class="fas fa-calendar-alt mr-1"></i><?= date('d M Y', strtotime($article['tgl_update'] ?? $article['tgl_buat'])) ?></span>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-span-2 p-12 text-center bg-white border border-slate-200 rounded-3xl text-slate-500 w-full">
                <i class="fas fa-magnifying-glass text-4xl mb-3 text-slate-300"></i>
                <p>Tidak ada hasil pencarian yang cocok dengan kata kunci tersebut.</p>
            </div>
        <?php endif; ?>
    </div>
</main>

<footer class="bg-slate-900 text-slate-400 py-6 border-t border-slate-800 text-center text-xs">
    &copy; <?= date('Y') ?> Pemerintah Kabupaten. Semua hak cipta dilindungi.
</footer>
<?= $this->endSection() ?>
