<?php

namespace App\Filters;

use App\Models\KategoriModel;
use App\Models\UserModel;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Api as ApiConfig;

class ApiKeyFilter implements FilterInterface
{
    /**
     * Lakukan pengecekan autentikasi API Key sebelum request diteruskan ke Controller.
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $apiConfig = config(ApiConfig::class);
        $headerName = $apiConfig->headerName ?? 'X-API-KEY';

        $apiKey = null;

        // 1. Cek Header X-API-KEY
        $header = $request->header($headerName);
        if ($header) {
            $apiKey = trim($header->getValue());
        }

        // 2. Cek Header Authorization: Bearer <API_KEY>
        if (empty($apiKey)) {
            $authHeader = $request->header('Authorization');
            if ($authHeader) {
                $authVal = trim($authHeader->getValue());
                if (stripos($authVal, 'Bearer ') === 0) {
                    $apiKey = trim(substr($authVal, 7));
                }
            }
        }

        // 3. Fallback: Cek query parameter (?api_key=...) atau body param jika diizinkan
        if (empty($apiKey) && ($apiConfig->allowQueryKey ?? true)) {
            $apiKey = $request->getGet('api_key') ?? $request->getPost('api_key');
            if (empty($apiKey) && $request->hasHeader('Content-Type') && str_contains($request->getHeaderLine('Content-Type'), 'application/json')) {
                $json = $request->getJSON(true);
                if (is_array($json) && !empty($json['api_key'])) {
                    $apiKey = trim($json['api_key']);
                }
            }
        }

        // 4. Jika API Key tidak disertakan
        if (empty($apiKey)) {
            // Jika requireAuth false dan request diarahkan ke endpoint berbasis kategori, izinkan lewat ke Controller
            $uriPath = (string) $request->getUri()->getPath();
            if (!($apiConfig->requireAuth ?? false) && str_contains($uriPath, 'faqs/category')) {
                return;
            }

            return service('response')
                ->setStatusCode(401)
                ->setJSON([
                    'success'    => false,
                    'message'    => 'Akses ditolak: API Key tidak disertakan. Sertakan header X-API-KEY atau Authorization Bearer.',
                    'error_code' => 'API_KEY_MISSING'
                ]);
        }

        // 5. Cek apakah cocok dengan Master API Key (Superadmin / Sistem Terpusat)
        if (!empty($apiConfig->masterKey) && hash_equals($apiConfig->masterKey, $apiKey)) {
            $request->apiUser = [
                'role'          => 'admin',
                'nip'           => 'admin_master',
                'nama'          => 'Master Administrator API',
                'kategori_id'   => null,
                'nama_kategori' => 'Semua Kategori (Master Access)',
                'is_master'     => true
            ];
            return;
        }

        // 6. Cek API Key di tabel pengguna (Akun OPD / Klien)
        $userModel = new UserModel();
        $user = $userModel->findByApiKey($apiKey);

        if (!$user) {
            return service('response')
                ->setStatusCode(401)
                ->setJSON([
                    'success'    => false,
                    'message'    => 'Akses ditolak: API Key tidak valid.',
                    'error_code' => 'INVALID_API_KEY'
                ]);
        }

        // Ambil nama kategori terkait
        $kategoriModel = new KategoriModel();
        $category = $kategoriModel->find($user['kategori_id']);

        $request->apiUser = [
            'role'          => $user['peran'] ?? 'user',
            'pengguna_id'   => (int) $user['pengguna_id'],
            'nip'           => $user['nip'],
            'kategori_id'   => (int) $user['kategori_id'],
            'nama_kategori' => $category['nama_kategori'] ?? 'Tidak Diketahui',
            'url_apk'       => $user['url_apk'] ?? '',
            'is_master'     => false
        ];
    }

    /**
     * Dijalankan setelah request selesai (tidak diperlukan modifikasi).
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No action needed
    }
}
