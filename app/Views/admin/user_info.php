<?= $this->extend('layouts/main_admin_blank') ?>

<?= $this->section('content') ?>
<body class="bg-slate-50 text-slate-700 flex h-screen overflow-hidden">
    <!-- Main Content Area -->
    <div class="flex-grow flex flex-col min-w-0 overflow-hidden">
        <!-- Top Navbar -->
        <header class="h-16 bg-white/90 backdrop-blur-md border-b border-slate-200/70 px-6 flex items-center justify-between shrink-0 shadow-xs sticky top-0 z-10 transition-all duration-300 ease-in-out">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-tr from-indigo-600 to-blue-600 rounded-xl text-white flex items-center justify-center shadow-md shadow-indigo-500/20 transition-all duration-300 ease-in-out hover:scale-105">
                    <i class="fas fa-building text-base"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-base font-bold text-slate-800 tracking-tight block leading-tight"><?= esc($kategori_name ?? 'User OPD') ?></span>
                        <?php if (!empty($user_categories) && count($user_categories) > 1): ?>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                <?= count($user_categories) ?> Kategori Terhubung
                            </span>
                        <?php endif; ?>
                    </div>
                    <span class="text-[11px] text-slate-400 font-medium">Panel Manajemen Informasi</span>
                </div>
            </div>
            
            <div class="flex items-center space-x-3">
                <!-- Dropdown Switch Kategori (Jika Memegang Lebih dari 1 Kategori) -->
                <?php if (!empty($user_categories) && count($user_categories) > 1): ?>
                <div class="relative">
                    <button type="button" onclick="toggleCategoryDropdown()" id="btnCategorySwitch" class="flex items-center gap-2 px-3 py-1.5 bg-slate-50 hover:bg-indigo-50 border border-slate-200 hover:border-indigo-300 rounded-xl text-xs font-semibold text-slate-700 hover:text-indigo-700 transition-all duration-300">
                        <i class="fas fa-arrows-rotate text-indigo-500 text-xs"></i>
                        <span class="hidden sm:inline">Ganti Kategori:</span>
                        <span class="font-bold text-indigo-600 truncate max-w-[140px]"><?= esc($kategori_name) ?></span>
                        <i class="fas fa-chevron-down text-[10px] text-slate-400"></i>
                    </button>

                    <!-- Menu Dropdown Switcher -->
                    <div id="dropdownCategoryMenu" class="absolute right-0 mt-2 w-64 bg-white rounded-2xl shadow-xl border border-slate-200/80 py-2 z-50 hidden transform transition-all duration-200">
                        <div class="px-3 py-1.5 border-b border-slate-100 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                            Pilih Kategori Aktif
                        </div>
                        <div class="max-h-60 overflow-y-auto py-1">
                            <?php foreach ($user_categories as $ucat): 
                                $isActive = ($ucat['kategori_id'] == $kategori_id);
                                $kodeSlug = !empty($ucat['kode_kategori']) ? $ucat['kode_kategori'] : strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $ucat['nama_kategori'] ?? ''), '-'));
                            ?>
                            <a href="<?= base_url('admin/user_info/switch_kategori/' . $ucat['kategori_id']) ?>" class="flex items-center justify-between px-3.5 py-2.5 text-xs transition-colors <?= $isActive ? 'bg-indigo-50/80 text-indigo-700 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900 font-medium' ?>">
                                <div class="truncate mr-2">
                                    <div class="truncate"><?= esc($ucat['nama_kategori']) ?></div>
                                    <div class="text-[10px] text-slate-400 font-mono font-normal"><?= esc($kodeSlug) ?></div>
                                </div>
                                <?php if ($isActive): ?>
                                    <i class="fas fa-check-circle text-indigo-600 text-sm shrink-0"></i>
                                <?php endif; ?>
                            </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-semibold bg-indigo-50/80 text-indigo-700 border border-indigo-100 shadow-xs transition-all duration-300 ease-in-out hover:bg-indigo-100/80">
                    <i class="fas fa-user-circle mr-2 text-indigo-500"></i>
                    <span class="truncate max-w-[120px]"><?= esc(session()->get('nama') ?? 'Operator Daerah') ?></span>
                </span>
                <a href="<?= base_url('auth/logout') ?>" class="w-9 h-9 flex items-center justify-center rounded-xl text-slate-400 hover:text-red-500 hover:bg-red-50 transition-all duration-300 ease-in-out" title="Logout">
                    <i class="fas fa-sign-out-alt"></i>
                </a>
            </div>
        </header>

        <!-- Main Content scrollable -->
        <main class="flex-grow overflow-y-auto p-6 bg-slate-50/50 custom-scrollbar">
            
            <!-- Banner Integrasi API Pengiriman Data -->
            <?php if (!empty($api_key)): ?>
            <div class="mb-6 p-5 rounded-3xl bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white shadow-xl shadow-indigo-950/10 flex flex-col md:flex-row md:items-center justify-between gap-4 border border-indigo-700/30 transition-all duration-300">
                <div class="flex items-center space-x-3.5">
                    <div class="w-11 h-11 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 flex items-center justify-center shrink-0 shadow-inner">
                        <i class="fas fa-plug text-indigo-300 text-base"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-bold uppercase tracking-wider text-indigo-300">Integrasi API Pengiriman Data</span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">Aktif</span>
                        </div>
                        <p class="text-xs text-slate-300 mt-0.5">Kirim dan sinkronkan data FAQ langsung dari aplikasi eksternal OPD Anda via REST API.</p>
                    </div>
                </div>
                
                <div class="flex flex-wrap items-center gap-2.5">
                    <div class="flex items-center bg-slate-950/80 border border-white/10 rounded-xl px-3.5 py-2 text-xs font-mono text-indigo-200">
                        <span class="text-slate-400 mr-2 text-[10px] uppercase font-sans font-bold">API Key:</span>
                        <span><?= substr(esc($api_key), 0, 16) ?>...</span>
                        <button type="button" onclick="copyApiKey('<?= esc($api_key, 'js') ?>')" class="ml-2.5 text-indigo-400 hover:text-white transition-colors" title="Salin Kunci API Lengkap">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                    <button type="button" onclick="toggleModal('modalUserInfoApiGuide')" class="px-4 py-2 bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-500 hover:to-blue-500 text-white text-xs font-semibold rounded-xl shadow-md transition-all flex items-center gap-2">
                        <i class="fas fa-book-open text-xs"></i>
                        <span>Panduan Integrasi</span>
                    </button>
                </div>
            </div>
            <?php endif; ?>

            <!-- Table Card -->
            <div class="bg-white rounded-3xl border border-slate-200/60 shadow-[0_10px_30px_rgba(15,23,42,0.05)] overflow-hidden transition-all duration-300 ease-in-out">
                
                <!-- Flash Notification -->
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="mx-6 mt-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200/80 text-emerald-700 text-xs font-semibold flex items-center justify-between shadow-xs transition-all duration-300 ease-in-out">
                        <div class="flex items-center gap-2.5">
                            <i class="fas fa-check-circle text-emerald-500 text-sm"></i>
                            <span><?= session()->getFlashdata('success') ?></span>
                        </div>
                        <button onclick="this.parentElement.remove()" class="text-emerald-400 hover:text-emerald-700 transition-all duration-300 ease-in-out"><i class="fas fa-times"></i></button>
                    </div>
                <?php endif; ?>

                <div class="p-6 border-b border-slate-100/80 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white">
                    <div>
                        <h2 class="text-lg font-bold text-slate-800 tracking-tight flex items-center gap-2">
                            <i class="fas fa-question-circle text-indigo-600 text-base"></i>
                            FAQ <?= esc($kategori_name ?? 'Layanan') ?>
                        </h2>
                        <p class="text-xs text-slate-400 mt-1 font-medium">Daftar FAQ dan panduan penulisan oleh <?= esc($kategori_name ?? 'OPD') ?>.</p>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-center w-full sm:w-auto">
                        <!-- Search Form -->
                        <form action="<?= base_url('admin/user_info') ?>" method="GET" class="relative max-w-xs w-full sm:w-64 group">
                            <input type="text" name="keyword" value="<?= esc($keyword ?? '') ?>" placeholder="Cari FAQ..." class="block w-full pl-10 pr-4 py-2.5 bg-slate-100/70 hover:bg-slate-100 border border-slate-200/80 rounded-xl focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 focus:bg-white text-xs font-medium text-slate-800 placeholder-slate-400 shadow-xs transition-all duration-300 ease-in-out">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-600 text-xs transition-all duration-300 ease-in-out">
                                <i class="fas fa-search"></i>
                            </div>
                        </form>

                        <!-- Tombol Tambah FAQ (+) -->
                        <a href="<?= base_url('admin/form_info_user') ?>" title="Tambah FAQ" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-gradient-to-r from-indigo-600 via-indigo-600 to-blue-600 hover:from-indigo-500 hover:to-blue-500 text-white text-xs font-semibold rounded-xl shadow-md shadow-indigo-500/25 hover:shadow-lg hover:shadow-indigo-500/35 hover:-translate-y-0.5 active:translate-y-0 transition-all duration-300 ease-in-out shrink-0">
                            <i class="fas fa-plus text-xs"></i>
                            <span>Tambah FAQ</span>
                        </a>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600 min-w-[800px]">
                        <thead class="bg-slate-50/80 text-slate-500 uppercase text-[10px] font-bold tracking-wider border-b border-slate-100/80">
                            <tr>
                                <th class="py-4 px-6">No.</th>
                                <th class="py-4 px-6 text-slate-700">Judul</th>
                                <th class="py-4 px-6 text-slate-700">Kata Kunci</th>
                                <th class="py-4 px-6 text-slate-700">Tgl Buat</th>
                                <th class="py-4 px-6 text-slate-700">Dibuat Oleh</th>
                                <th class="py-4 px-6 text-slate-700">Tgl Update</th>
                                <th class="py-4 px-6 text-slate-700">Diperbarui Oleh</th>
                                <th class="py-4 px-6 text-center text-slate-700">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100/70">
                            <?php 
                            if (!empty($informasi)):
                                $currentPage = $pager->getCurrentPage('user_info');
                                $perPage = $pager->getPerPage('user_info');
                                $no = 1 + ($currentPage - 1) * $perPage;
                                foreach($informasi as $info): 
                            ?>
                            <tr class="hover:bg-slate-50/90 transition-all duration-300 ease-in-out group">
                                <td class="py-4 px-6 text-xs text-slate-400 font-medium"><?= $no++ ?>.</td>
                                <td class="py-4 px-6 font-semibold text-slate-800 text-xs group-hover:text-indigo-600 transition-all duration-300 ease-in-out"><?= esc($info['judul']) ?></td>
                                <td class="py-4 px-6">
                                    <span class="inline-flex items-center px-2.5 py-1 bg-slate-100 text-slate-600 rounded-md text-[11px] font-medium transition-all duration-300 ease-in-out group-hover:bg-indigo-50 group-hover:text-indigo-700">
                                        <?= esc($info['kata_kunci']) ?>
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-xs text-slate-500"><?= date('d-m-Y', strtotime($info['tgl_buat'])) ?></td>
                                <td class="py-4 px-6 text-xs text-slate-500"><?= esc($info['dibuat_oleh']) ?></td>
                                <td class="py-4 px-6 text-xs text-slate-500"><?= date('d-m-Y', strtotime($info['tgl_update'])) ?></td>
                                <td class="py-4 px-6 text-xs text-slate-500"><?= esc($info['diperbarui_oleh']) ?></td>
                                <td class="py-4 px-6 text-center">
                                    <div class="flex items-center justify-center space-x-1.5">
                                        <a href="<?= base_url('admin/form_info_user/' . $info['info_id']) ?>" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition-all duration-300 ease-in-out" title="Edit FAQ">
                                            <i class="fas fa-edit text-xs"></i>
                                        </a>
                                        <form action="<?= base_url('admin/user_info/delete/' . $info['info_id']) ?>" method="post" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus FAQ ini?');">
                                            <button type="submit" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-red-600 hover:bg-red-50 transition-all duration-300 ease-in-out" title="Hapus FAQ">
                                                <i class="fas fa-trash text-xs"></i>
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
                                <td colspan="8" class="py-16 px-6 text-center">
                                    <div class="flex flex-col items-center justify-center max-w-sm mx-auto">
                                        <div class="w-20 h-20 bg-slate-100/80 rounded-full flex items-center justify-center text-slate-300 text-3xl mb-4 shadow-inner transition-all duration-300 ease-in-out hover:scale-105">
                                            <i class="fas fa-question-circle"></i>
                                        </div>
                                        <h3 class="text-base font-bold text-slate-800 mb-1">Belum Ada FAQ</h3>
                                        <p class="text-xs text-slate-400 leading-relaxed font-medium mb-5">Mulai buat FAQ pertama Anda dengan menekan tombol tambah.</p>
                                        <a href="<?= base_url('admin/form_info_user') ?>" class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-500 hover:to-blue-500 text-white text-xs font-semibold rounded-xl shadow-md shadow-indigo-500/20 hover:shadow-lg transition-all duration-300 ease-in-out">
                                            <i class="fas fa-plus text-xs"></i>
                                            <span>Tambah FAQ</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <?php
                $total = $pager->getTotal('user_info');
                $currentPage = $pager->getCurrentPage('user_info');
                $perPage = $pager->getPerPage('user_info');
                $start = $total > 0 ? 1 + ($currentPage - 1) * $perPage : 0;
                $end = min($currentPage * $perPage, $total);
                ?>
                <!-- Footer Pagination -->
                <div class="p-6 border-t border-slate-100/80 bg-slate-50/50 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs font-semibold text-slate-500">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-indigo-500 inline-block"></span>
                        <span>Menampilkan <?= $start ?>-<?= $end ?> dari <?= $total ?> data</span>
                    </div>
                    <div>
                        <?= $pager->links('user_info', 'tailwind') ?>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal Panduan Pengiriman Data API untuk User OPD -->
    <div id="modalUserInfoApiGuide" class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
        <div class="bg-white w-full max-w-2xl rounded-3xl shadow-2xl border border-slate-200/80 overflow-hidden transform scale-95 transition-all max-h-[92vh] flex flex-col">
            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/80">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-indigo-600 text-white flex items-center justify-center shadow-md shadow-indigo-500/20">
                        <i class="fas fa-plug text-sm"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-900 text-base">Panduan Pengiriman Data API</h3>
                        <p class="text-xs text-slate-400">Integrasikan aplikasi OPD Anda untuk mengirim data FAQ secara otomatis.</p>
                    </div>
                </div>
                <button type="button" onclick="toggleModal('modalUserInfoApiGuide')" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
            
            <div class="p-6 space-y-4 overflow-y-auto text-xs text-slate-600 custom-scrollbar">
                <!-- Info Kunci API -->
                <div class="bg-indigo-50/60 border border-indigo-100 p-4 rounded-2xl">
                    <span class="font-bold text-indigo-950 block text-xs mb-1">Kredensial API OPD Anda</span>
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mt-2">
                        <div class="font-mono text-[11px] text-indigo-900 bg-white px-3 py-2 rounded-xl border border-indigo-200/70 select-all break-all">
                            <?= esc($api_key ?? '-') ?>
                        </div>
                        <button type="button" onclick="copyApiKey('<?= esc($api_key ?? '', 'js') ?>')" class="px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-semibold shrink-0 transition-colors flex items-center justify-center gap-1.5 shadow-sm">
                            <i class="fas fa-copy"></i>
                            <span>Salin API Key</span>
                        </button>
                    </div>
                    <span class="text-[11px] text-indigo-600 mt-2 block">Kategori Anda: <b>#<?= esc($kategori_id ?? 0) ?> (<?= esc($kategori_name ?? '') ?>)</b>. Seluruh data yang Anda kirim otomatis terisolasi dan tersimpan di kategori ini.</span>
                </div>

                <!-- Endpoint & Header -->
                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200">
                    <span class="font-bold text-slate-800 block mb-1">1. Endpoint & Header HTTP</span>
                    <div class="space-y-1.5 font-mono text-[11px] mt-2">
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-0.5 rounded bg-emerald-600 text-white font-bold text-[10px]">POST</span>
                            <span class="text-slate-800 font-semibold"><?= base_url('api/faqs') ?></span>
                        </div>
                        <div class="text-slate-500 pt-1">
                            Header wajib:
                            <div class="bg-slate-900 text-emerald-400 p-2.5 rounded-xl mt-1 space-y-0.5">
                                <div>Content-Type: application/json</div>
                                <div>X-API-KEY: <?= esc($api_key ?? 'your_api_key_here') ?></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contoh cURL -->
                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200">
                    <span class="font-bold text-slate-800 block mb-1">2. Contoh Pengiriman via cURL (Terminal)</span>
                    <pre class="bg-slate-900 text-slate-200 p-3 rounded-xl font-mono text-[11px] overflow-x-auto leading-relaxed mt-2">curl -X POST "<?= base_url('api/faqs') ?>" \
  -H "Content-Type: application/json" \
  -H "X-API-KEY: <?= esc($api_key ?? 'your_api_key_here') ?>" \
  -d '{
    "judul": "Bagaimana cara mendaftar antrean online?",
    "isi": "&lt;p&gt;Silakan kunjungi menu antrean di aplikasi kami.&lt;/p&gt;",
    "kata_kunci": "antrean, pendaftaran"
  }'</pre>
                </div>

                <!-- Contoh PHP -->
                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200">
                    <span class="font-bold text-slate-800 block mb-1">3. Contoh Integrasi PHP (cURL)</span>
                    <pre class="bg-slate-900 text-slate-200 p-3 rounded-xl font-mono text-[11px] overflow-x-auto leading-relaxed mt-2">&lt;?php
$payload = [
    'judul'      => 'Pertanyaan Baru dari Aplikasi',
    'isi'        => '&lt;p&gt;Jawaban lengkap informasi...&lt;/p&gt;',
    'kata_kunci' => 'layanan, panduan'
];

$ch = curl_init('<?= base_url('api/faqs') ?>');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'X-API-KEY: <?= esc($api_key ?? 'your_api_key_here') ?>'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$result = json_decode($response, true);
if ($httpCode === 201 &amp;&amp; $result['success']) {
    echo "Berhasil dikirim! ID: " . $result['data']['id'];
}
?&gt;</pre>
                </div>
            </div>
            
            <div class="p-4 border-t border-slate-100 bg-slate-50/80 flex justify-end">
                <button type="button" onclick="toggleModal('modalUserInfoApiGuide')" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-700 text-white rounded-xl text-xs font-semibold transition-colors">
                    Tutup Panduan
                </button>
            </div>
        </div>
    </div>

    <script>
        function toggleModal(id) {
            const modal = document.getElementById(id);
            if (modal) {
                modal.classList.toggle('hidden');
            }
        }

        function toggleCategoryDropdown() {
            const menu = document.getElementById('dropdownCategoryMenu');
            if (menu) {
                menu.classList.toggle('hidden');
            }
        }

        // Tutup dropdown jika klik di luar
        window.addEventListener('click', function(e) {
            const btn = document.getElementById('btnCategorySwitch');
            const menu = document.getElementById('dropdownCategoryMenu');
            if (btn && menu && !btn.contains(e.target) && !menu.contains(e.target)) {
                menu.classList.add('hidden');
            }
        });

        function copyApiKey(key) {
            if (!key) return;
            navigator.clipboard.writeText(key).then(function() {
                alert('API Key berhasil disalin ke clipboard!');
            }, function(err) {
                console.error('Gagal menyalin:', err);
            });
        }
    </script>
</body>
<?= $this->endSection() ?>
