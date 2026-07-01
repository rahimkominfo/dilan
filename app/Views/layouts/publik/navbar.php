<nav class="sticky top-0 z-40 bg-gradient-to-r from-brand-700 via-brand-600 to-indigo-700 shadow-lg text-white border-b border-brand-500/20 backdrop-blur-md">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <!-- Brand Title -->
            <div class="flex items-center space-x-3">
                <a href="<?= base_url('/') ?>" class="flex items-center space-x-3">
                    <div class="bg-white/20 p-2 rounded-xl">
                        <i class="fas fa-book-reader text-xl text-yellow-300"></i>
                    </div>
                    <span class="text-xl font-extrabold tracking-wider">SISTEM <span class="text-yellow-300 font-bold">MANAJEMEN</span> PENGETAHUAN</span>
                </a>
            </div>

            <!-- Right Nav Links -->
            <div class="flex items-center space-x-4">
                <a href="<?= base_url('/') ?>" class="px-3.5 py-2 rounded-xl text-sm font-semibold hover:bg-white/10 transition-all flex items-center space-x-2 text-white">
                    <i class="fas fa-home"></i>
                    <span>Beranda</span>
                </a>
                <a href="<?= base_url('auth/login') ?>" class="px-4 py-2 bg-yellow-400 hover:bg-yellow-300 text-slate-900 rounded-xl text-sm font-bold shadow-md transition-all flex items-center space-x-2">
                    <i class="fas fa-sign-in-alt"></i>
                    <span>Masuk</span>
                </a>
            </div>
        </div>
    </div>
</nav>
