<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Sistem</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-700 min-h-screen flex items-center justify-center">

    <div class="w-full max-w-md px-6">

        <!-- Card Login -->
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">

            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 py-8 text-center">

                <!-- Icon -->
                <div class="w-20 h-20 bg-white rounded-full mx-auto flex items-center justify-center shadow-lg p-2 overflow-hidden">
                    <img src="<?= base_url('assets/img/logo.png') ?>" alt="Logo" class="w-full h-full object-contain">
                </div>

                <h1 class="text-white text-3xl font-bold mt-4">
                    Login
                </h1>

                <p class="text-blue-100 mt-2">
                    Silakan masuk ke sistem
                </p>

            </div>

            <!-- Body -->
            <div class="p-8">

                <!-- Flash Message -->
                <?php if (session()->getFlashdata('pesan')): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline"><?= session()->getFlashdata('pesan') ?></span>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('auth/login_process') ?>" method="post" class="space-y-5">

                    <!-- NIP / Username -->
                    <div>

                        <label class="block text-gray-700 font-semibold mb-2">
                            NIP/Username
                        </label>

                        <input
                            type="text"
                            name="nip"
                            id="nip"
                            value="<?= set_value('nip') ?>"
                            placeholder="Masukkan NIP / Username"
                            autofocus
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                        
                        <?php if (isset($validation) && $validation->hasError('nip')): ?>
                            <small class="text-red-500"><?= $validation->getError('nip') ?></small>
                        <?php endif; ?>
                    </div>

                    <!-- Password -->
                    <div>

                        <label class="block text-gray-700 font-semibold mb-2">
                            Password
                        </label>

                        <input
                            type="password"
                            name="password"
                            id="password"
                            placeholder="Masukkan Password"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">

                        <?php if (isset($validation) && $validation->hasError('password')): ?>
                            <small class="text-red-500"><?= $validation->getError('password') ?></small>
                        <?php endif; ?>
                    </div>

                    <!-- Button -->
                    <button
                        type="submit"
                        class="w-full bg-gradient-to-r from-blue-600 to-indigo-700 text-white py-3 rounded-xl font-semibold hover:shadow-xl hover:scale-105 transition duration-300">
                        Login
                    </button>

                </form>

            </div>

        </div>

        <!-- Footer -->
        <div class="text-center mt-6 text-white text-sm">
            © <?= date('Y') ?> Sistem Informasi
        </div>

    </div>

</body>
</html>
