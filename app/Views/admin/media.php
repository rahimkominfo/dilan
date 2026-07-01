<?= $this->extend('layouts/main_admin') ?>

<?= $this->section('content') ?>
<div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
    <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-lg font-bold text-slate-900">Data Pustaka Media</h2>
            <p class="text-xs text-slate-400 mt-1">Unggah berkas aset gambar, dokumen PDF atau berkas panduan lain.</p>
        </div>
        
        <div class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-center w-full sm:w-auto">
            <!-- Search Form -->
            <form action="<?= base_url('admin/media') ?>" method="GET" class="relative max-w-xs w-full sm:w-64">
                <input type="text" name="keyword" value="<?= esc($keyword ?? '') ?>" placeholder="Cari media..." class="block w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500 text-xs font-medium text-slate-900">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-xs">
                    <i class="fas fa-search"></i>
                </div>
            </form>

            <button onclick="toggleModal('modalMedia')" title="Upload Media" class="w-10 h-10 bg-brand-600 hover:bg-brand-500 text-white rounded-xl shadow-md transition-all flex items-center justify-center shrink-0">
                <i class="fas fa-plus text-sm"></i>
            </button>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-slate-600 min-w-[700px]">
            <thead class="bg-slate-50 text-slate-700 uppercase text-[10px] font-bold tracking-wider border-b border-slate-100">
                <tr>
                    <th class="py-4 px-6 w-20">No.</th>
                    <th class="py-4 px-6">Nama</th>
                    <th class="py-4 px-6">File</th>
                    <th class="py-4 px-6">Besar File</th>
                    <th class="py-4 px-6 text-center w-32">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php 
                $currentPage = $pager->getCurrentPage('media');
                $perPage = $pager->getPerPage('media');
                $no = 1 + ($currentPage - 1) * $perPage;
                foreach($media as $m): 
                ?>
                <tr class="hover:bg-slate-50/80 transition-colors">
                    <td class="py-4 px-6 font-semibold"><?= $no++ ?>.</td>
                    <td class="py-4 px-6 font-semibold text-slate-900"><?= esc($m['nama']) ?></td>
                    <td class="py-4 px-6">
                        <a href="<?= base_url('uploads/' . $m['file']) ?>" target="_blank" class="text-brand-600 hover:underline text-xs"><?= esc($m['file']) ?></a>
                    </td>
                    <td class="py-4 px-6 text-xs font-semibold text-slate-500"><?= esc($m['ukuran_media']) ?></td>
                    <td class="py-4 px-6 text-center">
                        <div class="flex items-center justify-center space-x-3">
                            <button onclick="copyLink('<?= base_url('uploads/' . $m['file']) ?>')" class="text-slate-400 hover:text-brand-600 text-base" title="Salin Link"><i class="fas fa-link"></i></button>
                            <form action="<?= base_url('admin/media/delete/' . $m['media_id']) ?>" method="post" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus media ini?');">
                                <button type="submit" class="text-slate-400 hover:text-red-600 text-base" title="Hapus"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <?php
    $total = $pager->getTotal('media');
    $currentPage = $pager->getCurrentPage('media');
    $perPage = $pager->getPerPage('media');
    $start = $total > 0 ? 1 + ($currentPage - 1) * $perPage : 0;
    $end = min($currentPage * $perPage, $total);
    ?>
    <!-- Footer Pagination -->
    <div class="p-6 border-t border-slate-100 bg-slate-50 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs font-semibold text-slate-500">
        <span>Menampilkan <?= $start ?>-<?= $end ?> dari <?= $total ?> data</span>
        <?= $pager->links('media', 'tailwind') ?>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('modals') ?>
<!-- Tambah Media Modal -->
<div id="modalMedia" class="fixed inset-0 bg-slate-950/40 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl border border-slate-200/60 overflow-hidden transform scale-95 transition-all">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <h3 class="font-bold text-slate-900 text-base">Tambah File Baru</h3>
            <button onclick="toggleModal('modalMedia')" class="text-slate-400 hover:text-slate-600"><i class="fas fa-times"></i></button>
        </div>
        
        <form action="<?= base_url('admin/media/upload') ?>" method="post" enctype="multipart/form-data" class="p-6 space-y-4">
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Media</label>
                <input type="text" name="nama" placeholder="Masukkan nama media..." class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500 text-xs font-semibold text-slate-900">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Pilih File</label>
                <div class="border-2 border-dashed border-slate-300 hover:border-brand-500 rounded-2xl p-8 text-center cursor-pointer transition-colors relative">
                    <input type="file" name="media_file" required class="absolute inset-0 opacity-0 cursor-pointer" onchange="updateFileName(this)">
                    <i class="fas fa-cloud-arrow-up text-4xl text-slate-400 mb-3 block"></i>
                    <span id="file-label" class="text-xs font-bold text-slate-700 block">Pilih Berkas Komputer</span>
                    <span class="text-[10px] text-slate-400 block mt-1">Maksimal besar berkas 10MB</span>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                <button type="button" onclick="toggleModal('modalMedia')" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-sm font-bold">Batal</button>
                <button type="submit" class="px-6 py-2.5 bg-brand-600 hover:bg-brand-500 text-white rounded-xl text-sm font-bold shadow-md">Mulai Upload</button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleModal(id) {
        document.getElementById(id).classList.toggle('hidden');
    }
    function updateFileName(input) {
        const label = document.getElementById('file-label');
        if (input.files && input.files[0]) {
            label.innerText = input.files[0].name;
        } else {
            label.innerText = "Pilih Berkas Komputer";
        }
    }
    function copyLink(link) {
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(link).then(function() {
                showToast("Tautan media berhasil disalin ke clipboard!");
            }).catch(function(err) {
                fallbackCopy(link);
            });
        } else {
            fallbackCopy(link);
        }
    }

    function fallbackCopy(text) {
        var textArea = document.createElement("textarea");
        textArea.value = text;
        textArea.style.position = "fixed";
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();
        try {
            var successful = document.execCommand('copy');
            if (successful) {
                showToast("Tautan media berhasil disalin!");
            } else {
                showToast("Gagal menyalin tautan.");
            }
        } catch (err) {
            alert("Tidak didukung oleh browser Anda.");
        }
        document.body.removeChild(textArea);
    }

    function showToast(message) {
        let container = document.getElementById('toast-container');
        if (!container) {
            container = document.createElement('div');
            container.id = 'toast-container';
            container.className = 'fixed bottom-5 right-5 z-50 flex flex-col gap-2';
            document.body.appendChild(container);
        }
        
        const toast = document.createElement('div');
        toast.className = 'bg-slate-900 text-white px-4 py-3 rounded-2xl shadow-xl flex items-center space-x-3 text-xs font-semibold border border-slate-800 transform translate-y-10 opacity-0 transition-all duration-300';
        toast.innerHTML = `
            <i class="fas fa-circle-check text-green-400 text-sm"></i>
            <span>${message}</span>
        `;
        
        container.appendChild(toast);
        
        setTimeout(() => {
            toast.classList.remove('translate-y-10', 'opacity-0');
        }, 10);
        
        setTimeout(() => {
            toast.classList.add('opacity-0', 'translate-y-2');
            setTimeout(() => {
                toast.remove();
            }, 300);
        }, 3000);
    }
</script>
<?= $this->endSection() ?>
