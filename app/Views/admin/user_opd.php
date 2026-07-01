<?= $this->extend('layouts/main_admin') ?>

<?= $this->section('content') ?>
<!-- Table Card -->
<div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
    <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-lg font-bold text-slate-900">Kelola User OPD</h2>
            <p class="text-xs text-slate-400 mt-1">Daftar akun pegawai OPD pengelola data penulisan sistem.</p>
        </div>
        
        <div class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-center w-full sm:w-auto">
            <!-- Search Form -->
            <form action="<?= base_url('admin/user_opd') ?>" method="GET" class="relative max-w-xs w-full sm:w-64">
                <input type="text" name="keyword" value="<?= esc($keyword ?? '') ?>" placeholder="Cari user..." class="block w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500 text-xs font-medium text-slate-900">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-xs">
                    <i class="fas fa-search"></i>
                </div>
            </form>

            <button onclick="toggleModal('modalUser')" title="Tambah User" class="w-10 h-10 bg-brand-600 hover:bg-brand-500 text-white rounded-xl shadow-md transition-all flex items-center justify-center shrink-0">
                <i class="fas fa-plus text-sm"></i>
            </button>
        </div>
    </div>

    <!-- Table Container -->
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-slate-600 min-w-[800px]">
            <thead class="bg-slate-50 text-slate-700 uppercase text-[10px] font-bold tracking-wider border-b border-slate-100">
                <tr>
                    <th class="py-4 px-6 w-20">No.</th>
                    <th class="py-4 px-6">NIP</th>
                    <th class="py-4 px-6">Nama</th>
                    <th class="py-4 px-6 w-32">Id Kategori</th>
                    <th class="py-4 px-6">Kategori OPD</th>
                    <th class="py-4 px-6">URL Aplikasi</th>
                    <th class="py-4 px-6 text-center w-32">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php 
                $currentPage = $pager->getCurrentPage('user_opd');
                $perPage = $pager->getPerPage('user_opd');
                $no = 1 + ($currentPage - 1) * $perPage;
                if (!empty($user_opd)): 
                    foreach($user_opd as $user): 
                ?>
                <tr class="hover:bg-slate-50/80 transition-colors">
                    <td class="py-4 px-6 font-semibold"><?= $no++ ?>.</td>
                    <td class="py-4 px-6 text-slate-900 font-semibold"><?= esc($user['nip']) ?></td>
                    <td class="py-4 px-6 font-medium text-slate-900"><?= esc($user['nama'] ?? '-') ?></td>
                    <td class="py-4 px-6"><?= esc($user['kategori_id']) ?></td>
                    <td class="py-4 px-6 font-medium text-slate-900"><?= esc($user['nama_kategori'] ?? 'Tidak Terikat') ?></td>
                    <td class="py-4 px-6 text-xs text-brand-600 hover:underline">
                        <?php if (!empty($user['url_apk'])): ?>
                            <a href="<?= esc($user['url_apk']) ?>" target="_blank"><?= esc($user['url_apk']) ?></a>
                        <?php else: ?>
                            <span class="text-slate-400 italic">Tidak ada URL</span>
                        <?php endif; ?>
                    </td>
                    <td class="py-4 px-6 text-center">
                        <div class="flex items-center justify-center space-x-3.5">
                            <button onclick="openEditModal(<?= $user['pengguna_id'] ?>, '<?= esc($user['nip'], 'js') ?>', <?= $user['kategori_id'] ?>, '<?= esc($user['url_apk'], 'js') ?>')" class="text-slate-400 hover:text-brand-600 text-base" title="Edit"><i class="fas fa-edit"></i></button>
                            <form action="<?= base_url('admin/user_opd/delete/' . $user['pengguna_id']) ?>" method="post" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun user ini?');">
                                <button type="submit" class="text-slate-400 hover:text-red-600 text-base" title="Hapus"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php 
                    endforeach; 
                else: 
                ?>
                <tr>
                    <td colspan="7" class="py-8 px-6 text-center text-slate-400 italic">Belum ada akun user OPD yang terdaftar.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php
    $total = $pager->getTotal('user_opd');
    $currentPage = $pager->getCurrentPage('user_opd');
    $perPage = $pager->getPerPage('user_opd');
    $start = $total > 0 ? 1 + ($currentPage - 1) * $perPage : 0;
    $end = min($currentPage * $perPage, $total);
    ?>
    <!-- Footer Pagination -->
    <div class="p-6 border-t border-slate-100 bg-slate-50 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs font-semibold text-slate-500">
        <span>Menampilkan <?= $start ?>-<?= $end ?> dari <?= $total ?> data</span>
        <?= $pager->links('user_opd', 'tailwind') ?>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('modals') ?>
<!-- Tambah User Modal -->
<div id="modalUser" class="fixed inset-0 bg-slate-950/40 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl border border-slate-200/60 overflow-hidden transform scale-95 transition-all">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <h3 class="font-bold text-slate-900 text-base">Tambah Akun User OPD</h3>
            <button onclick="toggleModal('modalUser')" class="text-slate-400 hover:text-slate-600"><i class="fas fa-times"></i></button>
        </div>
        <form action="<?= base_url('admin/user_opd/store') ?>" method="post" class="p-6 space-y-4">
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Kategori OPD</label>
                <select name="kategori_id" required class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500 text-sm">
                    <option value="">Pilih Kategori OPD...</option>
                    <?php foreach($kategori as $kat): ?>
                        <option value="<?= $kat['kategori_id'] ?>"><?= esc($kat['nama_kategori']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">NIP Pegawai</label>
                <input type="text" name="nip" placeholder="Masukkan NIP..." required class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500 text-sm font-semibold text-slate-900">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">URL Website Resmi Aplikasi</label>
                <input type="text" name="url_apk" placeholder="https://..." class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500 text-sm font-semibold text-slate-900">
            </div>
            <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                <button type="button" onclick="toggleModal('modalUser')" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-sm font-bold">Batal</button>
                <button type="submit" class="px-6 py-2.5 bg-brand-600 hover:bg-brand-500 text-white rounded-xl text-sm font-bold shadow-md">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit User Modal -->
<div id="modalEditUser" class="fixed inset-0 bg-slate-950/40 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl border border-slate-200/60 overflow-hidden transform scale-95 transition-all">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <h3 class="font-bold text-slate-900 text-base">Edit Akun User OPD</h3>
            <button onclick="toggleModal('modalEditUser')" class="text-slate-400 hover:text-slate-600"><i class="fas fa-times"></i></button>
        </div>
        <form id="formEditUser" action="" method="post" class="p-6 space-y-4">
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Kategori OPD</label>
                <select id="edit_id_kategori" name="kategori_id" required class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500 text-sm">
                    <?php foreach($kategori as $kat): ?>
                        <option value="<?= $kat['kategori_id'] ?>"><?= esc($kat['nama_kategori']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">NIP Pegawai</label>
                <input type="text" id="edit_nip" name="nip" required class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500 text-sm font-semibold text-slate-900">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">URL Website Resmi Aplikasi</label>
                <input type="text" id="edit_url_apk" name="url_apk" class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500 text-sm font-semibold text-slate-900">
            </div>
            <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                <button type="button" onclick="toggleModal('modalEditUser')" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-sm font-bold">Batal</button>
                <button type="submit" class="px-6 py-2.5 bg-brand-600 hover:bg-brand-500 text-white rounded-xl text-sm font-bold shadow-md">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleModal(id) {
        document.getElementById(id).classList.toggle('hidden');
    }
    function openEditModal(id, nip, idKategori, urlApk) {
        const modal = document.getElementById('modalEditUser');
        const form = document.getElementById('formEditUser');
        
        form.action = `<?= base_url('admin/user_opd/update') ?>/${id}`;
        document.getElementById('edit_nip').value = nip;
        document.getElementById('edit_id_kategori').value = idKategori;
        document.getElementById('edit_url_apk').value = urlApk;
        
        modal.classList.remove('hidden');
    }
</script>
<?= $this->endSection() ?>
