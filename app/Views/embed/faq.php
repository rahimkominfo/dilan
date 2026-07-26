<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ Embed - DILAN AR</title>
    <!-- Tailwind CSS & FontAwesome -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: transparent; }
        .faq-answer p { margin-bottom: 0.5rem; }
    </style>
</head>
<body class="font-sans text-slate-800 p-2 sm:p-4">

    <div id="embed-container" class="max-w-4xl mx-auto space-y-4">
        
        <!-- Header & Search Box -->
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-200">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-3">
                <div class="flex items-center space-x-2">
                    <span class="p-2 bg-blue-50 text-blue-600 rounded-lg text-sm font-bold">
                        <i class="fas fa-circle-question"></i>
                    </span>
                    <div>
                        <h2 class="font-bold text-slate-900 text-base">
                            <?= $category ? esc($category['nama_kategori']) : 'Pusat Bantuan & FAQ' ?>
                        </h2>
                        <p class="text-xs text-slate-500">Temukan jawaban atas pertanyaan umum Anda.</p>
                    </div>
                </div>
            </div>

            <!-- Input Pencarian -->
            <div class="relative">
                <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                <input 
                    type="text" 
                    id="faq-search-input" 
                    onkeyup="filterFaq()" 
                    value="<?= esc($search) ?>"
                    placeholder="Ketik kata kunci pencarian FAQ..." 
                    class="w-full pl-10 pr-10 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all"
                >
                <button id="clear-search" onclick="clearSearch()" class="<?= empty($search) ? 'hidden' : '' ?> absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                    <i class="fas fa-times-circle"></i>
                </button>
            </div>
        </div>

        <!-- Daftar FAQ Accordion -->
        <div class="space-y-3" id="faq-list">
            <?php if (!empty($articles)): ?>
                <?php foreach ($articles as $index => $item): ?>
                    <div class="faq-card bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm hover:border-blue-300 transition-all">
                        <button onclick="toggleFaq('faq-item-<?= $item['info_id'] ?>')" class="w-full text-left px-5 py-3.5 font-semibold text-slate-800 flex items-center justify-between gap-3 hover:bg-slate-50 transition-colors">
                            <span class="faq-question text-sm leading-snug"><?= esc($item['judul']) ?></span>
                            <i id="icon-faq-item-<?= $item['info_id'] ?>" class="fas fa-chevron-down text-slate-400 text-xs shrink-0 transition-transform duration-200"></i>
                        </button>
                        <div id="content-faq-item-<?= $item['info_id'] ?>" class="hidden px-5 pb-4 pt-2 border-t border-slate-100 text-xs sm:text-sm text-slate-600 leading-relaxed bg-slate-50/50 faq-answer">
                            <?= $item['isi'] ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div id="empty-state" class="bg-white rounded-2xl p-8 text-center border border-slate-200">
                    <i class="fas fa-search-minus text-3xl text-slate-300 mb-2"></i>
                    <p class="text-sm font-semibold text-slate-600">FAQ Tidak Ditemukan</p>
                    <p class="text-xs text-slate-400 mt-1">Tidak ada data FAQ yang cocok dengan pencarian Anda.</p>
                </div>
            <?php endif; ?>

            <!-- State Kosong jika Client Filter 0 Hasil -->
            <div id="no-result-state" class="hidden bg-white rounded-2xl p-8 text-center border border-slate-200">
                <i class="fas fa-search-minus text-3xl text-slate-300 mb-2"></i>
                <p class="text-sm font-semibold text-slate-600">Hasil tidak ditemukan</p>
                <p class="text-xs text-slate-400 mt-1">Coba gunakan kata kunci pencarian yang lain.</p>
            </div>
        </div>

    </div>

    <!-- Script Accordion, Live Search & Auto Height PostMessage -->
    <script>
        function toggleFaq(id) {
            const content = document.getElementById('content-' + id);
            const icon = document.getElementById('icon-' + id);

            if (content.classList.contains('hidden')) {
                content.classList.remove('hidden');
                icon.classList.add('rotate-180');
            } else {
                content.classList.add('hidden');
                icon.classList.remove('rotate-180');
            }
            sendHeightToParent();
        }

        function filterFaq() {
            const input = document.getElementById('faq-search-input');
            const clearBtn = document.getElementById('clear-search');
            const filter = input.value.toLowerCase().trim();
            const cards = document.querySelectorAll('.faq-card');
            const noResultState = document.getElementById('no-result-state');
            let visibleCount = 0;

            if (filter.length > 0) {
                clearBtn.classList.remove('hidden');
            } else {
                clearBtn.classList.add('hidden');
            }

            cards.forEach(card => {
                const question = card.querySelector('.faq-question').textContent.toLowerCase();
                const answer = card.querySelector('.faq-answer').textContent.toLowerCase();

                if (question.includes(filter) || answer.includes(filter)) {
                    card.style.display = "";
                    visibleCount++;
                } else {
                    card.style.display = "none";
                }
            });

            if (noResultState) {
                if (visibleCount === 0 && cards.length > 0) {
                    noResultState.classList.remove('hidden');
                } else {
                    noResultState.classList.add('hidden');
                }
            }

            sendHeightToParent();
        }

        function clearSearch() {
            const input = document.getElementById('faq-search-input');
            input.value = '';
            filterFaq();
        }

        // Auto Resize Height Iframe ke Host Window
        function sendHeightToParent() {
            setTimeout(() => {
                const container = document.getElementById('embed-container');
                if (container) {
                    const height = container.offsetHeight + 40;
                    window.parent.postMessage({
                        type: 'dilan_faq_resize',
                        height: height
                    }, '*');
                }
            }, 50);
        }

        window.addEventListener('load', sendHeightToParent);
        window.addEventListener('resize', sendHeightToParent);
    </script>
</body>
</html>
