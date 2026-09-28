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

            <!-- Tombol Panduan Integrasi API -->
            <button onclick="toggleModal('modalApiGuide')" title="Panduan Integrasi API" class="px-3.5 py-2.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-200 rounded-xl text-xs font-semibold flex items-center gap-2 transition-all shrink-0">
                <i class="fas fa-plug text-indigo-600"></i>
                <span>Panduan API</span>
            </button>

            <button onclick="toggleModal('modalUser')" title="Tambah User" class="w-10 h-10 bg-brand-600 hover:bg-brand-500 text-white rounded-xl shadow-md transition-all flex items-center justify-center shrink-0">
                <i class="fas fa-plus text-sm"></i>
            </button>
        </div>
    </div>

    <!-- Table Container -->
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-slate-600 min-w-[950px]">
            <thead class="bg-slate-50 text-slate-700 uppercase text-[10px] font-bold tracking-wider border-b border-slate-100">
                <tr>
                    <th class="py-4 px-6 w-16">No.</th>
                    <th class="py-4 px-6">NIP</th>
                    <th class="py-4 px-6">Nama Pegawai</th>
                    <th class="py-4 px-6">Kategori OPD</th>
                    <th class="py-4 px-6">Kunci API (API Key)</th>
                    <th class="py-4 px-6">URL Aplikasi</th>
                    <th class="py-4 px-6 text-center w-36">Aksi</th>
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
                    <td class="py-4 px-6">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-indigo-50 text-indigo-700">
                            #<?= esc($user['kategori_id']) ?> - <?= esc($user['nama_kategori'] ?? 'Tidak Terikat') ?>
                        </span>
                    </td>
                    <td class="py-4 px-6">
                        <?php if (!empty($user['api_key'])): ?>
                            <div class="flex items-center space-x-2">
                                <code class="text-xs bg-slate-100 px-2 py-1 rounded font-mono text-slate-800 tracking-wider">
                                    <?= substr(esc($user['api_key']), 0, 14) ?>...
                                </code>
                                <button type="button" onclick="copyToClipboard('<?= esc($user['api_key'], 'js') ?>')" class="text-slate-400 hover:text-indigo-600 text-xs p-1" title="Salin Kunci API Lengkap">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        <?php else: ?>
                            <span class="text-xs text-amber-600 bg-amber-50 px-2 py-0.5 rounded">Belum ada</span>
                        <?php endif; ?>
                    </td>
                    <td class="py-4 px-6 text-xs text-brand-600 hover:underline">
                        <?php if (!empty($user['url_apk'])): ?>
                            <a href="<?= esc($user['url_apk']) ?>" target="_blank" class="truncate max-w-[150px] inline-block"><?= esc($user['url_apk']) ?></a>
                        <?php else: ?>
                            <span class="text-slate-400 italic">Tidak ada URL</span>
                        <?php endif; ?>
                    </td>
                    <td class="py-4 px-6 text-center text-nowrap">
                        <div class="btn-group btn-group-sm text-nowrap inline-flex items-center space-x-2" role="group">
                            <?php 
                            $kodeSlug = !empty($user['kode_kategori']) ? $user['kode_kategori'] : strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $user['nama_kategori'] ?? 'kategori'), '-'));
                            ?>
                            <button type="button" onclick="openUserApiGuide('<?= esc($user['nama'] ?? $user['nip'], 'js') ?>', '<?= esc($user['nama_kategori'] ?? 'OPD', 'js') ?>', '<?= esc($kodeSlug, 'js') ?>', <?= (int)$user['kategori_id'] ?>, '<?= esc($user['api_key'] ?? '', 'js') ?>')" class="text-slate-400 hover:text-indigo-600 transition-colors text-base" title="Panduan API User OPD Ini">
                                <i class="fa-solid fa-circle-info"></i>
                            </button>
                            <button type="button" onclick="openEditModal(<?= $user['pengguna_id'] ?>, '<?= esc($user['nip'], 'js') ?>', <?= $user['kategori_id'] ?>, '<?= esc($user['url_apk'], 'js') ?>', '<?= esc($user['api_key'] ?? '', 'js') ?>')" class="text-slate-400 hover:text-brand-600 transition-colors text-base" title="Edit Data User">
                                <i class="fas fa-edit"></i>
                            </button>
                            <a href="<?= base_url('admin/user_opd/regenerate_api_key/' . $user['pengguna_id']) ?>" onclick="return confirm('Apakah Anda yakin ingin me-reset API Key ini? Aplikasi luar yang memakai key lama harus diubah.');" class="text-slate-400 hover:text-amber-600 transition-colors text-base" title="Generate Ulang API Key">
                                <i class="fas fa-key"></i>
                            </a>
                            <form action="<?= base_url('admin/user_opd/delete/' . $user['pengguna_id']) ?>" method="post" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun user ini?');">
                                <button type="submit" class="text-slate-400 hover:text-red-600 transition-colors text-base" title="Hapus User OPD">
                                    <i class="fas fa-trash"></i>
                                </button>
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
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">API Key (Opsional)</label>
                <input type="text" name="api_key" placeholder="Kosongkan untuk generate otomatis..." class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500 text-xs font-mono text-slate-700">
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
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Kunci API (API Key)</label>
                <input type="text" id="edit_api_key" name="api_key" class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500 text-xs font-mono text-slate-700">
            </div>
            <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                <button type="button" onclick="toggleModal('modalEditUser')" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-sm font-bold">Batal</button>
                <button type="submit" class="px-6 py-2.5 bg-brand-600 hover:bg-brand-500 text-white rounded-xl text-sm font-bold shadow-md">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Panduan API Spesifik User OPD -->
<div id="modalUserApiGuide" class="fixed inset-0 bg-slate-950/50 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white w-full max-w-2xl rounded-3xl shadow-2xl border border-slate-200/80 overflow-hidden transform scale-95 transition-all max-h-[90vh] flex flex-col">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-indigo-50/60">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-indigo-600 text-white flex items-center justify-center shadow-md shadow-indigo-200">
                    <i class="fa-solid fa-circle-info text-base"></i>
                </div>
                <div>
                    <h3 class="font-bold text-slate-900 text-base flex items-center gap-2">
                        <span>Panduan Integrasi API:</span>
                        <span id="userGuideOpdName" class="text-indigo-600 font-extrabold"></span>
                    </h3>
                    <p class="text-xs text-slate-500">Mekanisme koneksi & kirim FAQ otomatis dari aplikasi OPD ini ke Dilan.</p>
                </div>
            </div>
            <button onclick="toggleModal('modalUserApiGuide')" class="text-slate-400 hover:text-slate-600"><i class="fas fa-times text-lg"></i></button>
        </div>
        <div class="p-6 space-y-4 overflow-y-auto text-xs text-slate-600 custom-scrollbar">
            <!-- Autentikasi API Key OPD -->
            <div class="bg-slate-50 p-4 rounded-xl border border-slate-200">
                <div class="flex items-center justify-between mb-1">
                    <span class="font-bold text-slate-800">1. Kunci API OPD (X-API-KEY)</span>
                    <span id="userGuidePegawaiName" class="text-[10px] font-semibold px-2 py-0.5 rounded bg-slate-200 text-slate-700"></span>
                </div>
                <p class="text-slate-500 mb-2">Gunakan Header autentikasi resmi ini saat melakukan request ke API Dilan:</p>
                <div class="flex items-center gap-2">
                    <code id="userGuideApiKey" class="block flex-1 bg-slate-900 text-emerald-400 p-2.5 rounded-lg font-mono text-[11px] overflow-x-auto select-all"></code>
                    <button type="button" onclick="copyUserApiKey()" class="px-3 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg text-xs font-semibold flex items-center gap-1.5 transition-all shrink-0" title="Salin API Key">
                        <i class="far fa-copy"></i>
                        <span>Salin</span>
                    </button>
                </div>
            </div>

            <!-- Endpoint Khusus -->
            <div class="bg-slate-50 p-4 rounded-xl border border-slate-200">
                <span class="font-bold text-slate-800 block mb-1">2. Endpoint Pengiriman Otomatis ke Kategori OPD Ini</span>
                <div class="flex items-center gap-2 mb-2 font-mono text-[11px]">
                    <span class="px-2 py-0.5 rounded bg-emerald-600 text-white font-bold">POST</span>
                    <span id="userGuideEndpoint" class="text-slate-800 font-semibold select-all"></span>
                </div>
                
                <p class="text-slate-500 mb-1">Payload JSON (otomatis terikat dengan kategori OPD):</p>
                <pre class="bg-slate-900 text-slate-200 p-3 rounded-lg font-mono text-[11px] overflow-x-auto">{
  "judul": "Contoh Pertanyaan Layanan",
  "isi": "&lt;p&gt;Penjelasan jawaban informasi layanan...&lt;/p&gt;",
  "kata_kunci": "layanan, pendaftaran, opd"
}</pre>
            </div>

            <!-- cURL Contoh Siap Pakai -->
            <div class="bg-slate-50 p-4 rounded-xl border border-slate-200">
                <span class="font-bold text-slate-800 block mb-1">3. Contoh Perintah cURL (Siap Pakai)</span>
                <pre id="userGuideCurl" class="bg-slate-900 text-slate-200 p-3 rounded-lg font-mono text-[11px] overflow-x-auto select-all"></pre>
            </div>

            <!-- PHP cURL Sample -->
            <div class="bg-slate-50 p-4 rounded-xl border border-slate-200">
                <span class="font-bold text-slate-800 block mb-1">4. Contoh Integrasi Backend PHP / CodeIgniter</span>
                <pre id="userGuidePhp" class="bg-slate-900 text-slate-200 p-3 rounded-lg font-mono text-[11px] overflow-x-auto select-all"></pre>
            </div>
        </div>
        <div class="p-4 border-t border-slate-100 bg-slate-50 flex justify-end">
            <button onclick="toggleModal('modalUserApiGuide')" class="px-5 py-2 bg-slate-800 hover:bg-slate-700 text-white rounded-xl text-xs font-semibold">Tutup</button>
        </div>
    </div>
</div>

<script>
    let currentUserApiKey = '';

    function toggleModal(id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.toggle('hidden');
        }
    }

    function openEditModal(id, nip, idKategori, urlApk, apiKey) {
        const modal = document.getElementById('modalEditUser');
        const form = document.getElementById('formEditUser');
        
        form.action = `<?= base_url('admin/user_opd/update') ?>/${id}`;
        document.getElementById('edit_nip').value = nip;
        document.getElementById('edit_id_kategori').value = idKategori;
        document.getElementById('edit_url_apk').value = urlApk;
        document.getElementById('edit_api_key').value = apiKey || '';
        
        modal.classList.remove('hidden');
    }

    function openUserApiGuide(namaPegawai, namaKategori, kodeSlug, kategoriId, apiKey) {
        currentUserApiKey = apiKey || '';
        
        document.getElementById('userGuideOpdName').innerText = namaKategori;
        document.getElementById('userGuidePegawaiName').innerText = 'Pegawai: ' + namaPegawai;
        
        const apiKeyElement = document.getElementById('userGuideApiKey');
        const keyForCurl = currentUserApiKey ? currentUserApiKey : 'KUNCI_API_BELUM_DIBUAT';
        
        if (currentUserApiKey) {
            apiKeyElement.innerText = 'X-API-KEY: ' + currentUserApiKey;
        } else {
            apiKeyElement.innerText = 'X-API-KEY: (Belum dibuat - silakan klik tombol kunci untuk generate)';
        }
        
        const endpointUrl = `<?= base_url('api/faqs/category') ?>/${kodeSlug}`;
        document.getElementById('userGuideEndpoint').innerText = endpointUrl;

        const curlSample = `curl -X POST "${endpointUrl}" \\\n  -H "Content-Type: application/json" \\\n  -H "X-API-KEY: ${keyForCurl}" \\\n  -d '{\n    "judul": "Tanya Layanan ${namaKategori}",\n    "isi": "<p>Penjelasan informasi...</p>",\n    "kata_kunci": "info, layanan"\n  }'`;
        document.getElementById('userGuideCurl').innerText = curlSample;

        const phpSample = `// Kirim data FAQ dari aplikasi ${namaKategori}\n$ch = curl_init('${endpointUrl}');\ncurl_setopt($ch, CURLOPT_RETURNTRANSFER, true);\ncurl_setopt($ch, CURLOPT_POST, true);\ncurl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([\n    'judul' => 'Contoh Pertanyaan',\n    'isi' => '<p>Jawaban lengkap...</p>',\n    'kata_kunci' => 'layanan'\n]));\ncurl_setopt($ch, CURLOPT_HTTPHEADER, [\n    'Content-Type: application/json',\n    'X-API-KEY: ${keyForCurl}'\n]);\n$response = curl_exec($ch);\ncurl_close($ch);`;
        document.getElementById('userGuidePhp').innerText = phpSample;

        document.getElementById('modalUserApiGuide').classList.remove('hidden');
    }

    function copyUserApiKey() {
        if (!currentUserApiKey) {
            alert('User ini belum memiliki API Key. Silakan klik tombol generate kunci terlebih dahulu.');
            return;
        }
        navigator.clipboard.writeText(currentUserApiKey).then(() => {
            alert('Kunci API OPD berhasil disalin!');
        });
    }

    function copyToClipboard(text) {
        if (!text) return;
        navigator.clipboard.writeText(text).then(function() {
            alert('Kunci API berhasil disalin ke clipboard!');
        }, function(err) {
            console.error('Gagal menyalin:', err);
        });
    }
</script>
<?= $this->endSection() ?>
