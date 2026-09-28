<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Api extends BaseConfig
{
    /**
     * Master API Key untuk akses Superadmin / Sistem Integrasi Terpusat.
     * Dapat mengelola FAQ untuk seluruh kategori tanpa batasan OPD.
     */
    public string $masterKey = 'dilan_master_key_secret_2026';

    /**
     * Nama HTTP Header untuk API Key
     */
    public string $headerName = 'X-API-KEY';

    /**
     * Apakah mengizinkan autentikasi via query string (?api_key=...)
     * Berguna untuk testing atau lingkungan tertentu
     */
    public bool $allowQueryKey = true;

    /**
     * Apakah pengiriman data via endpoint kategori mewajibkan API Key.
     * Jika false, endpoint /api/faqs/category/{category_code} dapat menerima data
     * langsung berdasarkan kode kategori (dengan atau tanpa API Key).
     * Jika true, pengiriman tetap wajib menyertakan X-API-KEY.
     */
    public bool $requireAuth = false;

    public function __construct()
    {
        parent::__construct();

        // Ambil dari environment (.env) jika diset
        if ($envKey = env('API_MASTER_KEY')) {
            $this->masterKey = $envKey;
        }

        if (env('API_REQUIRE_AUTH') !== null) {
            $this->requireAuth = filter_var(env('API_REQUIRE_AUTH'), FILTER_VALIDATE_BOOLEAN);
        }
    }
}
