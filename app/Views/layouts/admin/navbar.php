<header class="h-16 bg-white border-b border-slate-200 px-6 flex items-center justify-between shrink-0 shadow-sm relative z-20">
    <button class="p-2 rounded-xl text-slate-500 hover:bg-slate-100 hover:text-slate-700 md:hidden"><i class="fas fa-bars text-lg"></i></button>
    <div class="flex items-center space-x-4 ml-auto">
        <span class="text-xs font-semibold text-slate-600"><?= esc(session()->get('nama') ?? 'User') ?></span>
    </div>
</header>
