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
                    <span class="text-base font-bold text-slate-800 tracking-tight block leading-tight"><?= esc($kategori_name ?? 'User OPD') ?></span>
                    <span class="text-[11px] text-slate-400 font-medium">Panel Manajemen Informasi</span>
                </div>
            </div>
            
            <div class="flex items-center space-x-4">
                <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-semibold bg-indigo-50/80 text-indigo-700 border border-indigo-100 shadow-xs transition-all duration-300 ease-in-out hover:bg-indigo-100/80">
                    <i class="fas fa-user-circle mr-2 text-indigo-500"></i>
                    <?= esc(session()->get('nama') ?? 'Operator Daerah') ?>
                </span>
                <a href="<?= base_url('auth/logout') ?>" class="w-9 h-9 flex items-center justify-center rounded-xl text-slate-400 hover:text-red-500 hover:bg-red-50 transition-all duration-300 ease-in-out" title="Logout">
                    <i class="fas fa-sign-out-alt"></i>
                </a>
            </div>
        </header>

        <!-- Main Content scrollable -->
        <main class="flex-grow overflow-y-auto p-6 bg-slate-50/50 custom-scrollbar">
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
</body>
<?= $this->endSection() ?>
