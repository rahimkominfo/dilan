<!DOCTYPE html>
<html lang="id" class="overflow-x-hidden">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= $title ?? 'Dilan - Sistem Manajemen Pengetahuan' ?></title>
    
    <!-- Tailwind CSS (Ganti dengan file lokal hasil build PostCSS nanti) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'Inter', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        html, body {
            max-width: 100%;
            overflow-x: hidden;
        }
        body {
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
            background-color: #f8fafc;
        }
        .glass {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.4);
        }
        .card-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px -10px rgba(2, 132, 199, 0.15);
        }
        /* Fix for Tailwind CSS Preflight reset on Rich Text Content (CKEditor Output) */
        .ck-content ol, .prose ol, .faq-answer ol, article ol {
            list-style-type: decimal !important;
            padding-left: 2rem !important;
            margin-top: 0.5rem !important;
            margin-bottom: 0.5rem !important;
        }
        .ck-content ul, .prose ul, .faq-answer ul, article ul {
            list-style-type: disc !important;
            padding-left: 2rem !important;
            margin-top: 0.5rem !important;
            margin-bottom: 0.5rem !important;
        }
        .ck-content ol ol, .prose ol ol, .faq-answer ol ol, article ol ol {
            list-style-type: lower-alpha !important;
        }
        .ck-content ul ul, .prose ul ul, .faq-answer ul ul, article ul ul {
            list-style-type: circle !important;
        }
        .ck-content ol ol ol, .prose ol ol ol, .faq-answer ol ol ol, article ol ol ol {
            list-style-type: lower-roman !important;
        }
        .ck-content ul ul ul, .prose ul ul ul, .faq-answer ul ul ul, article ul ul ul {
            list-style-type: square !important;
        }
        .ck-content li, .prose li, .faq-answer li, article li {
            display: list-item !important;
            margin-bottom: 0.25rem;
        }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex flex-col justify-between overflow-x-hidden max-w-full w-full">
    <?= $this->renderSection('content') ?>
</body>
</html>

