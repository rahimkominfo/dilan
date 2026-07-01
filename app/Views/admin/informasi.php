<?= $this->extend('layouts/main_admin') ?>

<?= $this->section('content') ?>
<!-- Table Card -->
<div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
    <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-lg font-bold text-slate-900">Data Daftar Informasi</h2>
            <p class="text-xs text-slate-400 mt-1">Kelola data pengetahuan, panduan dan dokumentasi teknis.</p>
        </div>
        
        <div class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-center w-full sm:w-auto">
            <!-- Search Form -->
            <form action="<?= base_url('admin/informasi') ?>" method="GET" class="relative max-w-xs w-full sm:w-64">
                <input type="text" name="keyword" value="<?= esc($keyword ?? '') ?>" placeholder="Cari informasi..." class="block w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500 text-xs font-medium text-slate-900">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-xs">
                    <i class="fas fa-search"></i>
                </div>
            </form>

            <a href="<?= base_url('admin/form_info') ?>" title="Tambah Informasi" class="w-10 h-10 bg-brand-600 hover:bg-brand-500 text-white rounded-xl shadow-md transition-all flex items-center justify-center shrink-0">
                <i class="fas fa-plus text-sm"></i>
            </a>
        </div>
    </div>

    <!-- Table Container -->
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-slate-600 min-w-[900px]">
            <thead class="bg-slate-50 text-slate-700 uppercase text-[10px] font-bold tracking-wider border-b border-slate-100">
                <tr>
                    <th class="py-4 px-6">No.</th>
                    <th class="py-4 px-6">Judul</th>
                    <th class="py-4 px-6">Kata Kunci</th>
                    <th class="py-4 px-6">Tgl Buat</th>
                    <th class="py-4 px-6">Dibuat Oleh</th>
                    <th class="py-4 px-6">Tgl Update</th>
                    <th class="py-4 px-6">Diperbarui Oleh</th>
                    <th class="py-4 px-6 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php 
                $currentPage = $pager->getCurrentPage('informasi');
                $perPage = $pager->getPerPage('informasi');
                $no = 1 + ($currentPage - 1) * $perPage;
                foreach($informasi as $info): 
                ?>
                <tr class="hover:bg-slate-50/80 transition-colors">
                    <td class="py-4 px-6 font-semibold"><?= $no++ ?>.</td>
                    <td class="py-4 px-6 font-semibold text-slate-900"><?= esc($info['judul']) ?></td>
                    <td class="py-4 px-6"><span class="px-2 py-1 bg-slate-100 text-slate-600 rounded-md text-xs font-medium"><?= esc($info['kata_kunci']) ?></span></td>
                    <td class="py-4 px-6 text-xs"><?= date('d-m-Y', strtotime($info['tgl_buat'])) ?></td>
                    <td class="py-4 px-6 text-xs font-medium text-slate-900"><?= esc($info['dibuat_oleh']) ?></td>
                    <td class="py-4 px-6 text-xs"><?= date('d-m-Y', strtotime($info['tgl_update'])) ?></td>
                    <td class="py-4 px-6 text-xs font-medium text-slate-900"><?= esc($info['diperbarui_oleh']) ?></td>
                    <td class="py-4 px-6 text-center">
                        <div class="flex items-center justify-center space-x-3.5">
                            <a href="<?= base_url('admin/form_info/' . $info['info_id']) ?>" class="text-slate-400 hover:text-brand-600 transition-colors text-base"><i class="fas fa-edit"></i></a>
                            <form action="<?= base_url('admin/informasi/delete/' . $info['info_id']) ?>" method="post" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus informasi ini?');">
                                <button type="submit" class="text-slate-400 hover:text-red-600 transition-colors text-base"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php
    $total = $pager->getTotal('informasi');
    $currentPage = $pager->getCurrentPage('informasi');
    $perPage = $pager->getPerPage('informasi');
    $start = $total > 0 ? 1 + ($currentPage - 1) * $perPage : 0;
    $end = min($currentPage * $perPage, $total);
    ?>
    <!-- Footer Pagination -->
    <div class="p-6 border-t border-slate-100 bg-slate-50 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs font-semibold text-slate-500">
        <span>Menampilkan <?= $start ?>-<?= $end ?> dari <?= $total ?> data</span>
        <?= $pager->links('informasi', 'tailwind') ?>
    </div>
</div>

<script>
    function toggleModal(id) {
        const modal = document.getElementById(id);
        modal.classList.toggle('hidden');
    }
</script>
<?= $this->endSection() ?>
