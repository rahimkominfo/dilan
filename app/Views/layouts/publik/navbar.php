<nav class="sticky top-0 z-40 bg-gradient-to-r from-brand-700 via-brand-600 to-indigo-700 shadow-lg text-white border-b border-brand-500/20 backdrop-blur-md w-full overflow-x-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 gap-2">
            <!-- Brand Title -->
            <div class="flex items-center space-x-2 sm:space-x-3 min-w-0 flex-1 sm:flex-initial">
                <a href="<?= base_url('/') ?>" class="flex items-center space-x-2 sm:space-x-3 min-w-0">
                    <div class="bg-white/20 p-1.5 sm:p-2 rounded-xl shrink-0">
                        <i class="fas fa-book-reader text-lg sm:text-xl text-yellow-300"></i>
                    </div>
                    <span class="text-xs sm:text-base md:text-xl font-extrabold tracking-wide sm:tracking-wider truncate">SISTEM <span class="text-yellow-300 font-bold">MANAJEMEN</span> PENGETAHUAN</span>
                </a>
            </div>

            <!-- Right Nav Links -->
            <div class="flex items-center space-x-2 sm:space-x-4 shrink-0">
                <?php 
                    $uri = uri_string();
                    $isRoot = url_is('/') || $uri === '' || $uri === '/';
                ?>
                <a href="<?= base_url('/') ?>" class="<?= $isRoot ? 'hidden sm:flex' : 'flex' ?> items-center space-x-1.5 sm:space-x-2 px-2.5 sm:px-3.5 py-2 rounded-xl text-xs sm:text-sm font-semibold hover:bg-white/10 transition-all text-white">
                    <i class="fas fa-home"></i>
                    <span>Beranda</span>
                </a>
                <a href="<?= base_url('auth/login') ?>" class="px-3 sm:px-4 py-2 bg-yellow-400 hover:bg-yellow-300 text-slate-900 rounded-xl text-xs sm:text-sm font-bold shadow-md transition-all flex items-center space-x-1.5 sm:space-x-2 shrink-0">
                    <i class="fas fa-sign-in-alt"></i>
                    <span>Masuk</span>
                </a>
            </div>
        </div>
    </div>
</nav>

