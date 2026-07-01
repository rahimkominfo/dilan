<?= $this->extend('layouts/main_admin') ?>

<?= $this->section('content') ?>
<!-- Table Card -->
<div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
    <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-lg font-bold text-slate-900">Kelola Kategori</h2>
            <p class="text-xs text-slate-400 mt-1">Mengelompokkan artikel informasi untuk kemudahan pencarian.</p>
        </div>
        
        <div class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-center w-full sm:w-auto">
            <!-- Search Form -->
            <form action="<?= base_url('admin/kategori') ?>" method="GET" class="relative max-w-xs w-full sm:w-64">
                <input type="text" name="keyword" value="<?= esc($keyword ?? '') ?>" placeholder="Cari kategori..." class="block w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500 text-xs font-medium text-slate-900">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-xs">
                    <i class="fas fa-search"></i>
                </div>
            </form>

            <button onclick="toggleModal('modalKategori')" title="Tambah Kategori" class="w-10 h-10 bg-brand-600 hover:bg-brand-500 text-white rounded-xl shadow-md transition-all flex items-center justify-center shrink-0">
                <i class="fas fa-plus text-sm"></i>
            </button>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-slate-600 min-w-[500px]">
            <thead class="bg-slate-50 text-slate-700 uppercase text-[10px] font-bold tracking-wider border-b border-slate-100">
                <tr>
                    <th class="py-4 px-6 w-20">No.</th>
                    <th class="py-4 px-6">Nama Kategori</th>
                    <th class="py-4 px-6 text-center w-32">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php 
                $currentPage = $pager->getCurrentPage('kategori');
                $perPage = $pager->getPerPage('kategori');
                $no = 1 + ($currentPage - 1) * $perPage;
                foreach($kategori as $kat): 
                ?>
                <tr class="hover:bg-slate-50/80 transition-colors">
                    <td class="py-4 px-6 font-semibold"><?= $no++ ?>.</td>
                    <td class="py-4 px-6 font-semibold text-slate-900"><?= esc($kat['nama_kategori']) ?></td>
                    <td class="py-4 px-6 text-center">
                        <div class="flex items-center justify-center space-x-3">
                            <button onclick="openEditModal(<?= $kat['kategori_id'] ?>, '<?= esc($kat['nama_kategori'], 'js') ?>')" class="text-slate-400 hover:text-brand-600 transition-colors text-base" title="Edit"><i class="fas fa-edit"></i></button>
                            <form action="<?= base_url('admin/kategori/delete/' . $kat['kategori_id']) ?>" method="post" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?');">
                                <button type="submit" class="text-slate-400 hover:text-red-600 transition-colors text-base" title="Hapus"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <?php
    $total = $pager->getTotal('kategori');
    $currentPage = $pager->getCurrentPage('kategori');
    $perPage = $pager->getPerPage('kategori');
    $start = $total > 0 ? 1 + ($currentPage - 1) * $perPage : 0;
    $end = min($currentPage * $perPage, $total);
    ?>
    <!-- Footer Pagination -->
    <div class="p-6 border-t border-slate-100 bg-slate-50 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs font-semibold text-slate-500">
        <span>Menampilkan <?= $start ?>-<?= $end ?> dari <?= $total ?> data</span>
        <?= $pager->links('kategori', 'tailwind') ?>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('modals') ?>
<!-- Tambah Kategori Modal -->
<div id="modalKategori" class="fixed inset-0 bg-slate-950/40 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl border border-slate-200/60 overflow-hidden transform scale-95 transition-all">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <h3 class="font-bold text-slate-900 text-base">Tambah Kategori Baru</h3>
            <button onclick="toggleModal('modalKategori')" class="text-slate-400 hover:text-slate-600"><i class="fas fa-times"></i></button>
        </div>
        
        <form action="<?= base_url('admin/kategori/store') ?>" method="post" class="p-6 space-y-4">
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Kategori</label>
                <input type="text" name="nama_kategori" placeholder="Masukkan nama kategori..." required class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500 text-sm">
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                <button type="button" onclick="toggleModal('modalKategori')" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-sm font-bold">Batal</button>
                <button type="submit" class="px-6 py-2.5 bg-brand-600 hover:bg-brand-500 text-white rounded-xl text-sm font-bold shadow-md">Tambah</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Kategori Modal -->
<div id="modalEditKategori" class="fixed inset-0 bg-slate-950/40 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl border border-slate-200/60 overflow-hidden transform scale-95 transition-all">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <h3 class="font-bold text-slate-900 text-base">Edit Kategori</h3>
            <button onclick="toggleModal('modalEditKategori')" class="text-slate-400 hover:text-slate-600"><i class="fas fa-times"></i></button>
        </div>
        
        <form id="formEditKategori" action="" method="post" class="p-6 space-y-4">
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Kategori</label>
                <input type="text" id="edit_nm_kategori" name="nama_kategori" placeholder="Masukkan nama kategori..." required class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500 text-sm font-semibold text-slate-900">
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                <button type="button" onclick="toggleModal('modalEditKategori')" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-sm font-bold">Batal</button>
                <button type="submit" class="px-6 py-2.5 bg-brand-600 hover:bg-brand-500 text-white rounded-xl text-sm font-bold shadow-md">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleModal(id) {
        document.getElementById(id).classList.toggle('hidden');
    }
    function openEditModal(id, nama) {
        const modal = document.getElementById('modalEditKategori');
        const form = document.getElementById('formEditKategori');
        const input = document.getElementById('edit_nm_kategori');
        
        form.action = `<?= base_url('admin/kategori/update') ?>/${id}`;
        input.value = nama;
        
        modal.classList.remove('hidden');
    }
</script>
<?= $this->endSection() ?>
