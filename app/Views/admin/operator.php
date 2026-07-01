<?= $this->extend('layouts/main_admin') ?>

<?= $this->section('content') ?>
<div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
    <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-lg font-bold text-slate-900">Data Aktivitas Operator</h2>
            <p class="text-xs text-slate-400 mt-1">Daftar rekaman riwayat suntingan data oleh masing-masing NIP Operator.</p>
        </div>
        
        <div class="flex items-center w-full sm:w-auto">
            <!-- Search Form -->
            <form action="<?= base_url('admin/operator') ?>" method="GET" class="relative max-w-xs w-full sm:w-64">
                <input type="text" name="keyword" value="<?= esc($keyword ?? '') ?>" placeholder="Cari aktivitas..." class="block w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500 text-xs font-medium text-slate-900">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-xs">
                    <i class="fas fa-search"></i>
                </div>
            </form>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-slate-600 min-w-[700px]">
            <thead class="bg-slate-50 text-slate-700 uppercase text-[10px] font-bold tracking-wider border-b border-slate-100">
                <tr>
                    <th class="py-4 px-6 w-20">No.</th>
                    <th class="py-4 px-6">Nama</th>
                    <th class="py-4 px-6">Judul Artikel</th>
                    <th class="py-4 px-6">Tanggal Tulis</th>
                    <th class="py-4 px-6">Jenis Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php 
                $currentPage = $pager->getCurrentPage('operator');
                $perPage = $pager->getPerPage('operator');
                $no = 1 + ($currentPage - 1) * $perPage;
                if (!empty($operator)): 
                    foreach($operator as $op): 
                        // Set colors depending on type of action (nama_jenis)
                        $badgeClass = 'bg-slate-50 border border-slate-200 text-slate-700';
                        $actionName = strtoupper($op['nama_jenis'] ?? 'UNKNOWN');
                        
                        if (strpos($actionName, 'CREATE') !== false || strpos($actionName, 'TAMBAH') !== false) {
                            $badgeClass = 'bg-brand-50 border border-brand-200 text-brand-700';
                        } elseif (strpos($actionName, 'UPDATE') !== false || strpos($actionName, 'EDIT') !== false || strpos($actionName, 'SUNTING') !== false) {
                            $badgeClass = 'bg-yellow-50 border border-yellow-200 text-yellow-700';
                        } elseif (strpos($actionName, 'DELETE') !== false || strpos($actionName, 'HAPUS') !== false) {
                            $badgeClass = 'bg-red-50 border border-red-200 text-red-700';
                        }
                ?>
                <tr class="hover:bg-slate-50/80 transition-colors">
                    <td class="py-4 px-6 font-semibold"><?= $no++ ?>.</td>
                    <td class="py-4 px-6 text-slate-900 font-medium"><?= esc($op['nama']) ?></td>
                    <td class="py-4 px-6 font-semibold text-slate-900"><?= esc($op['judul'] ?? 'Artikel Terhapus / Tidak Ditemukan') ?></td>
                    <td class="py-4 px-6 text-xs"><?= date('d M Y H:i', strtotime($op['tgl_tulis'])) ?></td>
                    <td class="py-4 px-6">
                        <span class="inline-flex px-2.5 py-1 text-[10px] font-bold rounded-md uppercase <?= $badgeClass ?>"><?= $actionName ?></span>
                    </td>
                </tr>
                <?php 
                    endforeach; 
                else: 
                ?>
                <tr>
                    <td colspan="5" class="py-8 px-6 text-center text-slate-400 italic">Belum ada rekaman data aktivitas operator.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php
    $total = $pager->getTotal('operator');
    $currentPage = $pager->getCurrentPage('operator');
    $perPage = $pager->getPerPage('operator');
    $start = $total > 0 ? 1 + ($currentPage - 1) * $perPage : 0;
    $end = min($currentPage * $perPage, $total);
    ?>
    <!-- Footer Pagination -->
    <div class="p-6 border-t border-slate-100 bg-slate-50 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs font-semibold text-slate-500">
        <span>Menampilkan <?= $start ?>-<?= $end ?> dari <?= $total ?> data</span>
        <?= $pager->links('operator', 'tailwind') ?>
    </div>
</div>
<?= $this->endSection() ?>
