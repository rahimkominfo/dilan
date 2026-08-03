<?= $this->extend('layouts/main_admin_blank') ?>

<?= $this->section('content') ?>
<body class="bg-slate-50 p-6 flex flex-col items-center justify-center min-h-screen">
    <!-- Form Card -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-xl p-6 sm:p-10 w-full max-w-3xl">
        <div class="border-b border-slate-100 pb-5 mb-6 flex justify-between items-center">
            <div>
                <h2 class="text-xl font-bold text-slate-900"><?= $info ? 'Edit Informasi (User OPD)' : 'Tambah Informasi (User OPD)' ?></h2>
                <p class="text-xs text-slate-400 mt-1">Perbarui judul dan konten panduan kepegawaian Anda.</p>
            </div>
            <button onclick="history.back()" class="px-3.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-lg transition-all flex items-center space-x-1">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali</span>
            </button>
        </div>

        <form action="<?= $info ? base_url('admin/user_info/update/'.$info['info_id']) : base_url('admin/user_info/store') ?>" method="post" class="space-y-6">
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Judul Informasi</label>
                <input type="text" name="judul" value="<?= $info ? esc($info['judul']) : '' ?>" required class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500 text-sm font-semibold text-slate-900">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Isi Panduan</label>
                <textarea id="isi_panduan" name="isi" rows="8" class="block w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500 text-sm text-slate-600 leading-relaxed"><?= $info ? esc($info['isi']) : '' ?></textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Kata Kunci</label>
                <input type="text" name="kata_kunci" value="<?= $info ? esc($info['kata_kunci']) : '' ?>" required class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500 text-sm">
            </div>

            <div class="flex justify-end gap-3 border-t border-slate-100 pt-5">
                <button type="button" onclick="history.back()" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-sm font-bold">Batal</button>
                <button type="submit" class="px-6 py-2.5 bg-brand-600 hover:bg-brand-500 text-white rounded-xl text-sm font-bold shadow-md transition-all"><?= $info ? 'Perbaharui' : 'Simpan' ?></button>
            </div>
        </form>
    </div>

    <!-- CKEditor 5 Local Assets -->
    <link rel="stylesheet" href="<?= base_url('assets/ckeditor5/ckeditor5.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/ckeditor5/ckeditor5-content.css') ?>">
    <style>
        /* Styling to set height for CKEditor 5 */
        .ck-editor__editable_inline {
            min-height: 300px;
        }

        /* Fix List styles in CKEditor 5 caused by Tailwind CSS preflight reset */
        .ck-content ol,
        .ck-editor__editable ol,
        .prose ol {
            list-style-type: decimal !important;
            padding-left: 2.5rem !important;
            margin-top: 0.5rem !important;
            margin-bottom: 0.5rem !important;
        }
        .ck-content ul,
        .ck-editor__editable ul,
        .prose ul {
            list-style-type: disc !important;
            padding-left: 2.5rem !important;
            margin-top: 0.5rem !important;
            margin-bottom: 0.5rem !important;
        }
        .ck-content ol ol,
        .ck-editor__editable ol ol,
        .prose ol ol {
            list-style-type: lower-alpha !important;
        }
        .ck-content ul ul,
        .ck-editor__editable ul ul,
        .prose ul ul {
            list-style-type: circle !important;
        }
        .ck-content ol ol ol,
        .ck-editor__editable ol ol ol,
        .prose ol ol ol {
            list-style-type: lower-roman !important;
        }
        .ck-content ul ul ul,
        .ck-editor__editable ul ul ul,
        .prose ul ul ul {
            list-style-type: square !important;
        }
        .ck-content li,
        .ck-editor__editable li,
        .prose li {
            display: list-item !important;
            margin-bottom: 0.25rem;
        }
    </style>
    <script src="<?= base_url('assets/ckeditor5/ckeditor5.umd.js') ?>"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const {
                ClassicEditor,
                Essentials,
                Bold,
                Italic,
                Underline,
                Paragraph,
                Heading,
                Link,
                List,
                Autoformat,
                BlockQuote,
                Image,
                ImageToolbar,
                ImageCaption,
                ImageStyle,
                ImageUpload,
                SimpleUploadAdapter,
                Alignment
            } = CKEDITOR;

            ClassicEditor
                .create(document.querySelector('#isi_panduan'), {
                    licenseKey: 'GPL',
                    plugins: [
                        Essentials,
                        Bold,
                        Italic,
                        Underline,
                        Paragraph,
                        Heading,
                        Link,
                        List,
                        Autoformat,
                        BlockQuote,
                        Image,
                        ImageToolbar,
                        ImageCaption,
                        ImageStyle,
                        ImageUpload,
                        SimpleUploadAdapter,
                        Alignment
                    ],
                    toolbar: [
                        'undo', 'redo', '|',
                        'heading', '|',
                        'bold', 'italic', 'underline', 'alignment', '|',
                        'link', 'bulletedList', 'numberedList', 'blockQuote', '|',
                        'insertImage'
                    ],
                    simpleUpload: {
                        uploadUrl: '<?= base_url('admin/upload_image') ?>'
                    }
                })
                .then(editor => {
                    console.log('CKEditor 5 initialized successfully.');
                    const form = document.querySelector('form');
                    if (form) {
                        form.addEventListener('submit', function() {
                            const rawData = editor.getData();
                            const encodedData = btoa(encodeURIComponent(rawData));
                            document.querySelector('#isi_panduan').value = encodedData;
                        });
                    }
                })
                .catch(error => {
                    console.error('Error during CKEditor 5 initialization:', error);
                });
        });
    </script>
</body>
<?= $this->endSection() ?>
