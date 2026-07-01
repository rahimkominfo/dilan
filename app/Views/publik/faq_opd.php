<?= $this->extend('layouts/main_publik_blank') ?>

<?= $this->section('content') ?>
<!-- Navbar -->
<nav class="bg-gradient-to-r from-brand-700 via-brand-600 to-indigo-700 shadow-md text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
        <a href="<?= base_url('/') ?>" class="font-extrabold text-xl flex items-center space-x-2 text-white">
            <i class="fas fa-building-columns text-yellow-300"></i>
            <span>FAQ INSTANSI</span>
        </a>
        <a href="<?= base_url('/') ?>" class="text-sm font-semibold hover:text-yellow-300 flex items-center space-x-1 text-white">
            <i class="fas fa-home"></i>
            <span>Beranda</span>
        </a>
    </div>
</nav>

<!-- Main Content -->
<main class="flex-grow max-w-5xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <!-- Header Row -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900">Informasi FAQ - <?= esc($category['nama_kategori']) ?></h1>
            <p class="text-xs text-slate-500 mt-1">Daftar tanya jawab umum layanan <?= esc($category['nama_kategori']) ?>.</p>
        </div>
        
        <div class="flex gap-2">
            <input type="text" id="faq-search" onkeyup="filterFaqs()" placeholder="Cari FAQ..." class="px-4 py-2.5 rounded-full border border-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-500 text-sm w-64 bg-white">
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- FAQ Accordion List (2 Columns) -->
        <div class="lg:col-span-2 space-y-4" id="faq-accordion-list">
            <?php if (!empty($articles)): ?>
                <?php foreach ($articles as $index => $article): ?>
                <div class="faq-item bg-white border border-slate-200/80 rounded-2xl overflow-hidden shadow-sm">
                    <button onclick="toggleAccordion('faq-<?= $article['info_id'] ?>')" class="w-full text-left px-6 py-4 font-bold text-slate-900 hover:bg-slate-50/50 flex items-center justify-between transition-colors">
                        <span class="faq-title text-sm"><?= esc($article['judul']) ?></span>
                        <i id="icon-faq-<?= $article['info_id'] ?>" class="fas fa-chevron-down text-slate-400 text-xs transition-transform duration-200"></i>
                    </button>
                    <div id="content-faq-<?= $article['info_id'] ?>" class="hidden px-6 pb-5 border-t border-slate-100 pt-3 text-sm text-slate-600 leading-relaxed bg-slate-50/30">
                        <?= format_article_images($article['isi']) ?>
                        <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-400">
                            <span><i class="fas fa-eye mr-1"></i><?= esc($article['jumlah_tayang']) ?> Dilihat</span>
                            <a href="<?= base_url('detail/' . $article['info_id']) ?>" class="text-brand-600 hover:underline font-semibold flex items-center">
                                <span>Detail Selengkapnya</span>
                                <i class="fas fa-arrow-right ml-1 text-[10px]"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="p-12 text-center bg-white border border-slate-200 rounded-3xl text-slate-500">
                    <i class="fas fa-folder-open text-3xl mb-3 text-slate-300"></i>
                    <p>Belum ada data FAQ untuk kategori ini.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Side Popular FAQs (1 Column) -->
        <div class="space-y-6">
            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm">
                <h3 class="font-bold text-slate-900 text-base border-b border-slate-100 pb-3 mb-4 flex items-center space-x-2">
                    <i class="fas fa-star text-yellow-500"></i>
                    <span>Paling Sering Dilihat</span>
                </h3>
                <?php if (!empty($popular_articles)): ?>
                    <ul class="space-y-3.5">
                        <?php foreach ($popular_articles as $p_art): ?>
                        <li class="flex items-start space-x-2">
                            <i class="fas fa-circle-question text-brand-500 text-xs mt-1 shrink-0"></i>
                            <a href="<?= base_url('detail/' . $p_art['info_id']) ?>" class="text-xs text-slate-700 hover:text-brand-600 font-semibold transition-colors"><?= esc($p_art['judul']) ?></a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p class="text-xs text-slate-500 italic">Tidak ada data.</p>
                <?php endif; ?>
            </div>

            <?php if (!empty($opd) && !empty($opd['url_apk'])): ?>
                <div class="bg-gradient-to-tr from-brand-900 to-indigo-900 text-white rounded-3xl p-6 shadow-md border border-brand-700">
                    <h3 class="font-bold text-xs uppercase tracking-wider text-yellow-300 mb-2">Aplikasi Pendukung</h3>
                    <p class="text-xs text-slate-200 mb-4">Akses aplikasi resmi atau portal pelayanan untuk kategori ini.</p>
                    <a href="<?= esc($opd['url_apk']) ?>" target="_blank" class="w-full py-2.5 bg-yellow-400 hover:bg-yellow-300 text-slate-900 font-bold rounded-xl text-xs text-center transition-all flex items-center justify-center space-x-2">
                        <i class="fas fa-external-link-alt"></i>
                        <span>Kunjungi Aplikasi</span>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<script>
    function toggleAccordion(id) {
        const content = document.getElementById('content-' + id);
        const icon = document.getElementById('icon-' + id);
        if(content.classList.contains('hidden')) {
            content.classList.remove('hidden');
            icon.classList.add('rotate-180');
        } else {
            content.classList.add('hidden');
            icon.classList.remove('rotate-180');
        }
    }

    function filterFaqs() {
        const query = document.getElementById('faq-search').value.toLowerCase();
        const items = document.querySelectorAll('.faq-item');
        items.forEach(item => {
            const text = item.querySelector('.faq-title').textContent.toLowerCase();
            if(text.includes(query)) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    }
</script>

<footer class="bg-slate-900 text-slate-400 py-6 border-t border-slate-800 text-center text-xs">
    &copy; <?= date('Y') ?> Pemerintah Kabupaten. Semua hak cipta dilindungi.
</footer>
<?= $this->endSection() ?>
