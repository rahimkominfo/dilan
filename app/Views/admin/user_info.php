<?= $this->extend('layouts/main_admin_blank') ?>

<?= $this->section('content') ?>
<body class="bg-slate-50 text-slate-700 flex h-screen overflow-hidden">
    <!-- Main Content Area -->
    <div class="flex-grow flex flex-col min-w-0 overflow-hidden">
        <!-- Top Navbar -->
        <header class="h-16 bg-white border-b border-slate-200 px-6 flex items-center justify-between shrink-0 shadow-sm">
            <div class="flex items-center space-x-3">
                <div class="bg-indigo-600 p-2 rounded-xl text-white"><i class="fas fa-building text-base"></i></div>
                <span class="text-lg font-bold text-slate-900"><?= esc($kategori_name ?? 'User OPD') ?></span>
            </div>
            
            <div class="flex items-center space-x-4">
                <span class="text-xs bg-indigo-50 text-indigo-700 border border-indigo-200 px-2.5 py-1 rounded-full font-semibold"><?= esc(session()->get('nama') ?? 'Operator Daerah') ?></span>
                <a href="<?= base_url('auth/logout') ?>" class="text-slate-400 hover:text-red-500 transition-colors" title="Logout"><i class="fas fa-sign-out-alt"></i></a>
            </div>
        </header>

        <!-- Main Content scrollable -->
        <main class="flex-grow overflow-y-auto p-6 bg-slate-50/50 custom-scrollbar">
            <!-- Table Card -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">Artikel <?= esc($kategori_name ?? 'Layanan') ?></h2>
                        <p class="text-xs text-slate-400 mt-1">Daftar artikel dan panduan penulisan oleh <?= esc($kategori_name ?? 'OPD') ?>.</p>
                    </div>
                    <a href="<?= base_url('admin/form_info_user') ?>" title="Tambah Informasi" class="w-10 h-10 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl shadow-md transition-all flex items-center justify-center shrink-0">
                        <i class="fas fa-plus text-sm"></i>
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600 min-w-[800px]">
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
                            <?php $no = 1; foreach($informasi as $info): ?>
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-4 px-6"><?= $no++ ?>.</td>
                                <td class="py-4 px-6 font-semibold text-slate-900"><?= esc($info['judul']) ?></td>
                                <td class="py-4 px-6"><span class="px-2 py-1 bg-slate-100 text-slate-600 rounded-md text-xs"><?= esc($info['kata_kunci']) ?></span></td>
                                <td class="py-4 px-6 text-xs"><?= date('d-m-Y', strtotime($info['tgl_buat'])) ?></td>
                                <td class="py-4 px-6 text-xs"><?= esc($info['dibuat_oleh']) ?></td>
                                <td class="py-4 px-6 text-xs"><?= date('d-m-Y', strtotime($info['tgl_update'])) ?></td>
                                <td class="py-4 px-6 text-xs"><?= esc($info['diperbarui_oleh']) ?></td>
                                <td class="py-4 px-6 text-center">
                                    <div class="flex items-center justify-center space-x-3">
                                        <a href="<?= base_url('admin/form_info_user/' . $info['info_id']) ?>" class="text-slate-400 hover:text-indigo-600 text-base"><i class="fas fa-edit"></i></a>
                                        <form action="<?= base_url('admin/user_info/delete/' . $info['info_id']) ?>" method="post" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus informasi ini?');">
                                            <button type="submit" class="text-slate-400 hover:text-red-500 text-base"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>


</body>
<?= $this->endSection() ?>
