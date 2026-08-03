<?= $this->extend('layouts/main_publik') ?>

<?= $this->section('content') ?>
<!-- Breadcrumb & Search Row -->
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <nav class="flex text-sm font-medium text-slate-500 bg-white px-5 py-3 rounded-full border border-slate-200 shadow-sm" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-2">
            <li>
                <a href="<?= base_url('/') ?>" class="hover:text-brand-600 transition-colors flex items-center">
                    <i class="fas fa-home mr-1"></i>
                    <span>Beranda</span>
                </a>
            </li>
            <li class="flex items-center text-slate-300"><i class="fas fa-chevron-right text-[10px] mx-1"></i></li>
            <li>
                <a href="<?= base_url('kategori/' . ($category['kategori_id'] ?? '')) ?>" class="hover:text-brand-600 transition-colors"><?= esc($category['nama_kategori'] ?? 'Umum') ?></a>
            </li>
            <li class="flex items-center text-slate-300"><i class="fas fa-chevron-right text-[10px] mx-1"></i></li>
            <li class="text-slate-800 font-semibold truncate max-w-[200px] sm:max-w-xs"><?= esc($article['judul']) ?></li>
        </ol>
    </nav>

    <form action="<?= base_url('pencarian') ?>" method="GET" class="flex gap-2">
        <input type="text" name="cari" placeholder="Cari informasi lain..." class="px-4 py-2.5 rounded-full border border-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-500 text-sm w-48 sm:w-64 bg-white">
        <button type="submit" class="px-4 py-2.5 bg-brand-600 hover:bg-brand-500 text-white rounded-full text-sm font-bold transition-all"><i class="fas fa-search"></i></button>
    </form>
</div>

<!-- Detail Article Layout -->
<div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 sm:p-10">
    <!-- Article Header -->
    <div class="border-b border-slate-100 pb-6 mb-6">
        <span class="text-xs font-bold text-brand-600 bg-brand-50 px-3 py-1 rounded-full border border-brand-100 uppercase"><?= esc($category['nama_kategori'] ?? 'Umum') ?></span>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-4 leading-snug"><?= esc($article['judul']) ?></h1>
        
        <!-- Author & Time Details -->
        <div class="flex flex-wrap items-center gap-4 text-xs text-slate-500 mt-6">
            <div class="flex items-center space-x-2">
                <div class="h-6 w-6 rounded-full bg-brand-500 text-white flex items-center justify-center font-bold text-[10px] uppercase">
                    <?= substr(esc($author_name ?? 'A'), 0, 1) ?>
                </div>
                <span class="font-semibold text-slate-700"><?= esc($author_name ?? 'Admin') ?></span>
            </div>
            <div class="h-1 w-1 bg-slate-300 rounded-full"></div>
            <div><i class="fas fa-calendar-alt mr-1"></i>Diperbarui tanggal <?= date('d-m-Y', strtotime($article['tgl_update'] ?? $article['tgl_buat'])) ?></div>
            <div class="h-1 w-1 bg-slate-300 rounded-full"></div>
            <div><i class="fas fa-eye mr-1"></i><?= esc($article['jumlah_tayang']) ?> kali dibaca</div>
        </div>
    </div>

    <!-- Article Body -->
    <article class="prose ck-content max-w-none text-slate-600 leading-relaxed space-y-6">
        <?= format_article_images($article['isi']) ?>
    </article>

    <!-- Tags Footer -->
    <?php 
        $tags = array_filter(array_map('trim', explode(',', $article['kata_kunci'] ?? '')));
    ?>
    <?php if (!empty($tags)): ?>
    <div class="border-t border-slate-100 pt-6 mt-8 flex flex-wrap gap-2">
        <?php foreach ($tags as $tag): ?>
        <span class="text-xs bg-slate-100 text-slate-600 px-3 py-1.5 rounded-full font-medium"><i class="fas fa-tag mr-1 text-slate-400"></i><?= esc($tag) ?></span>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
