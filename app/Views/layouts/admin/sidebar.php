<!-- Mobile Backdrop Overlay -->
<div id="sidebar-overlay" onclick="toggleSidebar()" class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm z-40 hidden md:hidden transition-opacity"></div>

<!-- Sidebar -->
<aside id="admin-sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 text-slate-300 flex flex-col shrink-0 border-r border-slate-800 shadow-xl -translate-x-full md:translate-x-0 md:static md:flex transition-transform duration-300 ease-in-out">
    <div class="h-16 flex items-center justify-between px-6 border-b border-slate-800">
        <div class="flex items-center space-x-3">
            <img src="<?= base_url('assets/img/logo.png') ?>" alt="Logo Dilan" class="w-8 h-8 object-contain">
            <span class="text-lg font-bold text-white tracking-wide">Panel Dilan</span>
        </div>
        <button onclick="toggleSidebar()" type="button" class="p-1.5 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800 md:hidden focus:outline-none" aria-label="Close Sidebar Menu">
            <i class="fas fa-times text-lg"></i>
        </button>
    </div>
    <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto custom-scrollbar">
        <a href="<?= base_url('/') ?>" class="flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-slate-800 hover:text-white transition-all text-slate-400">
            <i class="fas fa-desktop w-5"></i><span class="font-medium text-sm">Dashboard Public</span>
        </a>
        <div class="pt-6 pb-2 px-4 text-xs font-bold uppercase tracking-wider text-slate-500">Menu Utama</div>
        <a href="<?= base_url('admin/informasi') ?>" class="flex items-center space-x-3 px-4 py-3 rounded-xl <?= (uri_string() == 'admin/informasi' || uri_string() == 'admin') ? 'bg-brand-600 text-white' : 'hover:bg-slate-800 hover:text-white text-slate-400' ?> transition-all">
            <i class="fas fa-circle-info w-5"></i><span class="font-medium text-sm">Informasi</span>
        </a>
        <a href="<?= base_url('admin/media') ?>" class="flex items-center space-x-3 px-4 py-3 rounded-xl <?= uri_string() == 'admin/media' ? 'bg-brand-600 text-white' : 'hover:bg-slate-800 hover:text-white text-slate-400' ?> transition-all">
            <i class="fas fa-images w-5"></i><span class="font-medium text-sm">Media Pustaka</span>
        </a>
        <a href="<?= base_url('admin/kategori') ?>" class="flex items-center space-x-3 px-4 py-3 rounded-xl <?= uri_string() == 'admin/kategori' ? 'bg-brand-600 text-white' : 'hover:bg-slate-800 hover:text-white text-slate-400' ?> transition-all">
            <i class="fas fa-folder-open w-5"></i><span class="font-medium text-sm">Kategori</span>
        </a>
        <a href="<?= base_url('admin/operator') ?>" class="flex items-center space-x-3 px-4 py-3 rounded-xl <?= uri_string() == 'admin/operator' ? 'bg-brand-600 text-white' : 'hover:bg-slate-800 hover:text-white text-slate-400' ?> transition-all">
            <i class="fas fa-user-gear w-5"></i><span class="font-medium text-sm">Data Operator</span>
        </a>
        <a href="<?= base_url('admin/user_opd') ?>" class="flex items-center space-x-3 px-4 py-3 rounded-xl <?= uri_string() == 'admin/user_opd' ? 'bg-brand-600 text-white' : 'hover:bg-slate-800 hover:text-white text-slate-400' ?> transition-all">
            <i class="fas fa-users-gear w-5"></i><span class="font-medium text-sm">User OPD</span>
        </a>
    </nav>
    <div class="p-4 border-t border-slate-800 bg-slate-950 flex items-center space-x-3">
        <div class="h-9 w-9 rounded-xl border border-slate-700 bg-brand-600 flex items-center justify-center text-white font-bold text-sm shrink-0">
            <?= strtoupper(substr(session()->get('nama') ?? 'U', 0, 1)) ?>
        </div>
        <div class="flex-grow min-w-0">
            <p class="text-xs font-semibold text-white truncate" title="<?= esc(session()->get('nama') ?? 'User') ?>"><?= esc(session()->get('nama') ?? 'User') ?></p>
            <p class="text-[10px] text-slate-400 truncate"><?= esc(session()->get('peran') == 'admin' ? 'Administrator' : 'User OPD') ?></p>
        </div>
        <a href="<?= base_url('auth/logout') ?>" class="text-slate-400 hover:text-red-400" title="Logout"><i class="fas fa-power-off"></i></a>
    </div>
</aside>

