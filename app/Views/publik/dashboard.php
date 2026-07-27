<?= $this->extend('layouts/main_publik') ?>

<?= $this->section('content') ?>
<!-- Hero Search Section -->
<div class="bg-gradient-to-tr from-brand-900 via-brand-800 to-indigo-900 text-white rounded-3xl p-5 sm:p-12 shadow-xl mb-8 sm:mb-12 relative overflow-hidden border border-brand-700 max-w-full">
    <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:20px_20px]"></div>
    <div class="relative z-10 max-w-3xl">
        <span class="bg-yellow-400/20 text-yellow-300 border border-yellow-400/30 px-3 py-1 rounded-full text-[11px] sm:text-xs font-semibold uppercase tracking-wider inline-block">Pusat Pengetahuan OPD Sinjai</span>
        <h1 class="text-2xl sm:text-4xl font-extrabold mt-3 sm:mt-4 leading-tight break-words">Cari Solusi, FAQ & Dokumentasi Instansi dengan Cepat</h1>
        <p class="text-slate-300 mt-2 text-xs sm:text-base font-light">Temukan informasi, panduan teknis, dan database pengetahuan terpadu di lingkungan instansi pemerintahan.</p>
        
        <!-- Search Bar -->
        <form action="<?= base_url('pencarian') ?>" method="GET" class="mt-6 sm:mt-8 flex flex-col sm:flex-row gap-3 w-full max-w-full">
            <div class="relative flex-grow min-w-0">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 text-base">
                    <i class="fas fa-search"></i>
                </div>
                <input type="text" name="cari" placeholder="Masukkan kata kunci informasi..." 
                    class="block w-full pl-11 sm:pl-12 pr-4 py-3.5 sm:py-4 bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:bg-white focus:text-slate-900 transition-all text-xs sm:text-sm font-medium">
            </div>
            <button type="submit" class="px-6 sm:px-8 py-3.5 sm:py-4 bg-yellow-400 hover:bg-yellow-300 text-slate-900 rounded-2xl font-bold transition-all shadow-lg shadow-yellow-500/20 transform hover:-translate-y-0.5 flex items-center justify-center space-x-2 text-xs sm:text-sm shrink-0">
                <span>Cari Informasi</span>
            </button>
        </form>
    </div>
</div>

<!-- Main Dashboard Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8 max-w-full">
    <!-- Popular Information Section (Left 2 Columns) -->
    <div class="lg:col-span-2 space-y-6 sm:space-y-8 min-w-0">
        <div class="bg-white rounded-3xl p-5 sm:p-8 border border-slate-200/80 shadow-sm max-w-full">
            <div class="flex items-center justify-between pb-4 sm:pb-6 border-b border-slate-100 min-w-0">
                <div class="flex items-center space-x-2.5 sm:space-x-3 min-w-0">
                    <div class="bg-yellow-50 p-2 sm:p-2.5 rounded-xl text-yellow-600 shrink-0">
                        <i class="fas fa-fire text-base sm:text-lg"></i>
                    </div>
                    <div class="min-w-0">
                        <h2 class="text-lg sm:text-xl font-bold text-slate-900 truncate">Informasi Populer</h2>
                        <p class="text-[11px] sm:text-xs text-slate-500 mt-0.5 truncate">Informasi paling sering diakses pengguna.</p>
                    </div>
                </div>
                <span class="text-[10px] sm:text-xs text-brand-600 bg-brand-50 border border-brand-100 px-2.5 py-0.5 sm:px-3 sm:py-1 rounded-full font-semibold uppercase shrink-0 ml-2">Diperbarui</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-6">
                <?php if (!empty($popular_info)): ?>
                    <?php foreach ($popular_info as $item): ?>
                    <div class="p-4 bg-slate-50 hover:bg-slate-100/80 rounded-2xl border border-slate-200/50 card-hover flex items-start space-x-3.5 min-w-0 max-w-full">
                        <div class="bg-brand-500/10 p-3 rounded-xl text-brand-600 shrink-0 mt-0.5">
                            <i class="fas fa-file-lines text-lg"></i>
                        </div>
                        <div class="min-w-0 flex-grow">
                            <a href="<?= base_url('detail/' . $item['info_id']) ?>" class="block font-bold text-slate-950 hover:text-brand-600 transition-colors text-sm truncate" title="<?= esc($item['judul']) ?>"><?= esc($item['judul']) ?></a>
                            <p class="text-xs text-slate-500 mt-1 truncate">Kategori: <?= esc($item['nama_kategori'] ?? 'Umum') ?></p>
                            <span class="inline-block mt-3 text-[10px] bg-slate-200/70 text-slate-600 px-2 py-0.5 rounded-md font-medium"><i class="fas fa-eye mr-1"></i><?= esc($item['jumlah_tayang']) ?> Dilihat</span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-span-2 p-8 text-center text-slate-500 text-sm">Belum ada data informasi populer.</div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Categories Section -->
        <div class="space-y-6 min-w-0">
            <h3 class="text-lg sm:text-xl font-bold text-slate-900 px-2">Daftar Kategori Pengetahuan</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-full">
                <?php if (!empty($categories)): ?>
                    <?php foreach ($categories as $cat): ?>
                    <div class="bg-white rounded-3xl p-5 sm:p-6 border border-slate-200/80 shadow-sm flex flex-col justify-between min-w-0 max-w-full">
                        <div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-semibold text-brand-600 bg-brand-50 px-3 py-1 rounded-full border border-brand-100 uppercase">Kategori</span>
                                <i class="fas fa-folder text-brand-400 text-xl"></i>
                            </div>
                            <h4 class="text-base sm:text-lg font-bold text-slate-950 mt-4 truncate"><?= esc($cat['nama_kategori']) ?></h4>
                            <ul class="mt-4 space-y-2.5 min-w-0">
                                <?php if (!empty($cat['artikel'])): ?>
                                    <?php foreach ($cat['artikel'] as $art): ?>
                                    <li class="flex items-center space-x-2 text-sm text-slate-600 hover:text-brand-600 transition-colors min-w-0">
                                        <i class="fas fa-chevron-right text-[10px] text-slate-400 shrink-0"></i>
                                        <a href="<?= base_url('detail/' . $art['info_id']) ?>" class="truncate flex-grow min-w-0"><?= esc($art['judul']) ?></a>
                                    </li>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <li class="text-xs text-slate-400 italic">Belum ada artikel panduan.</li>
                                <?php endif; ?>
                            </ul>
                        </div>
                        <div class="mt-6 border-t border-slate-100 pt-4 flex justify-between items-center min-w-0">
                            <span class="text-xs text-slate-400 truncate mr-2"><?= esc($cat['jumlah_artikel']) ?> Artikel Panduan</span>
                            <a href="<?= base_url('kategori/' . $cat['kategori_id']) ?>" class="text-xs font-bold text-brand-600 hover:text-brand-700 transition-colors flex items-center space-x-1 shrink-0">
                                <span>Lihat Semua</span>
                                <i class="fas fa-arrow-right text-[10px]"></i>
                            </a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-span-2 p-8 text-center text-slate-500 text-sm">Belum ada kategori terdaftar.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Right Widget Sidebar (1 Column) -->
    <div class="space-y-6 sm:space-y-8 min-w-0">
        <!-- Info OPD FAQ List Widget -->
        <div class="bg-white rounded-3xl p-5 sm:p-6 border border-slate-200/80 shadow-sm min-w-0 max-w-full">
            <h3 class="font-bold text-slate-900 text-base sm:text-lg border-b border-slate-100 pb-4 mb-4 flex items-center space-x-2">
                <i class="fas fa-building-columns text-slate-400"></i>
                <span>FAQ Instansi / OPD</span>
            </h3>
            <div class="space-y-3 min-w-0">
                <?php if (!empty($opd_list)): ?>
                    <?php foreach ($opd_list as $opd): ?>
                    <a href="<?= base_url('faq_opd/' . $opd['kategori_id']) ?>" class="block p-3.5 rounded-2xl hover:bg-slate-50 border border-slate-100 hover:border-slate-200 transition-all card-hover flex items-center justify-between min-w-0 max-w-full">
                        <div class="min-w-0 flex-grow">
                            <p class="text-sm font-bold text-slate-950 truncate"><?= esc($opd['nama_kategori']) ?></p>
                            <p class="text-[10px] text-slate-500 mt-0.5 truncate"><?= esc($opd['url_apk']) ?></p>
                        </div>
                        <i class="fas fa-chevron-right text-slate-400 text-xs shrink-0 pl-2"></i>
                    </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-xs text-slate-500 italic">Belum ada instansi/OPD terdaftar.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- App Stats Widget -->
        <div class="bg-gradient-to-tr from-slate-900 to-slate-800 text-white rounded-3xl p-5 sm:p-6 shadow-md border border-slate-700 min-w-0 max-w-full">
            <h3 class="font-bold text-xs sm:text-sm uppercase tracking-wider text-slate-400 mb-4">Statistik Konten</h3>
            <div class="grid grid-cols-2 gap-3 sm:gap-4">
                <div class="bg-slate-800/80 p-3.5 sm:p-4 rounded-2xl border border-slate-700/50 min-w-0">
                    <div class="text-xl sm:text-2xl font-black text-brand-400"><?= esc($total_artikel) ?></div>
                    <div class="text-[10px] sm:text-xs text-slate-300 mt-1 truncate">Artikel Pengetahuan</div>
                </div>
                <div class="bg-slate-800/80 p-3.5 sm:p-4 rounded-2xl border border-slate-700/50 min-w-0">
                    <div class="text-xl sm:text-2xl font-black text-yellow-300"><?= esc($total_faq) ?></div>
                    <div class="text-[10px] sm:text-xs text-slate-300 mt-1 truncate">FAQ Terdaftar</div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

