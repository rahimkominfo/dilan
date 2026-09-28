<?php

namespace App\Controllers;

use App\Models\InfoModel;
use App\Models\KategoriModel;
use App\Models\OperatorModel;
use App\Models\UserModel;
use CodeIgniter\RESTful\ResourceController;

class FaqApiController extends ResourceController
{
    protected $infoModel;
    protected $kategoriModel;
    protected $operatorModel;

    public function __construct()
    {
        $this->infoModel     = new InfoModel();
        $this->kategoriModel = new KategoriModel();
        $this->operatorModel = new OperatorModel();
    }

    /**
     * Ambil data pengguna/klien yang telah terautentikasi oleh ApiKeyFilter
     *
     * @return array|null
     */
    protected function getApiUser(): ?array
    {
        return $this->request->apiUser ?? null;
    }

    /**
     * Ambil data input dari request (mendukung format JSON, POST Form, dan Raw Stream)
     *
     * @return array
     */
    protected function getRequestInput(): array
    {
        $json = $this->request->getJSON(true);
        if (!empty($json) && is_array($json)) {
            return $json;
        }

        $post = $this->request->getPost();
        if (!empty($post) && is_array($post)) {
            return $post;
        }

        $rawInput = $this->request->getRawInput();
        if (!empty($rawInput) && is_array($rawInput)) {
            return $rawInput;
        }

        return [];
    }

    /**
     * Decode konten jika dikirim dalam format base64
     *
     * @param string|null $isiInput
     * @return string
     */
    protected function decodeIsi($isiInput): string
    {
        if (empty($isiInput)) {
            return '';
        }

        $decoded = base64_decode($isiInput, true);
        if ($decoded !== false && base64_encode($decoded) === $isiInput) {
            return urldecode($decoded);
        }

        return (string) $isiInput;
    }

    /**
     * Catat aktivitas pengiriman data ke tabel log operator
     *
     * @param int $infoId
     * @param int $jenisId (1: CREATE, 2: UPDATE, 3: DELETE)
     * @param string|null $customNip
     * @return void
     */
    protected function logOperator(int $infoId, int $jenisId, ?string $customNip = null): void
    {
        $apiUser = $this->getApiUser();
        $nip = $customNip ?? $apiUser['nip'] ?? null;

        if (empty($nip)) {
            return;
        }

        // Verifikasi bahwa NIP terdaftar di tabel pengguna demi integritas relasi foreign key
        $userModel = new UserModel();
        $user = $userModel->where('nip', $nip)->first();

        if ($user) {
            try {
                $this->operatorModel->insert([
                    'nip'       => $nip,
                    'info_id'   => $infoId,
                    'jenis_id'  => $jenisId,
                    'tgl_tulis' => date('Y-m-d H:i:s')
                ]);
            } catch (\Throwable $e) {
                log_message('error', 'Gagal mencatat log operator API: ' . $e->getMessage());
            }
        }
    }

    // =========================================================================
    // ENDPOINT PUBLIK (GET KNOWLEDGE BASE)
    // =========================================================================

    /**
     * Ambil seluruh FAQ berdasarkan ID Kategori
     * GET /api/faqs/category/{category_id}
     * Mendukung filter: ?search=... atau ?keyword=...
     */
    public function index($categoryId = null)
    {
        if (!is_numeric($categoryId) || (int)$categoryId <= 0) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Parameter category ID tidak valid.'
            ]);
        }

        $categoryId = (int) $categoryId;

        $category = $this->kategoriModel->find($categoryId);
        if (!$category) {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => 'FAQ tidak ditemukan.'
            ]);
        }

        $search = $this->request->getGet('search') ?? $this->request->getGet('keyword');

        $builder = $this->infoModel->where('kategori_id', $categoryId);

        if (!empty($search)) {
            $search = trim($search);
            $builder->groupStart()
                ->like('judul', $search)
                ->orLike('isi', $search)
            ->groupEnd();
        }

        $faqs = $builder->findAll();

        if (empty($faqs)) {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => 'FAQ tidak ditemukan.'
            ]);
        }

        $formattedData = array_map(function ($faq) {
            return [
                'id'       => (int) $faq['info_id'],
                'question' => $faq['judul'],
                'answer'   => $faq['isi'],
            ];
        }, $faqs);

        return $this->response->setStatusCode(200)->setJSON([
            'success'  => true,
            'category' => [
                'id'   => (int) $category['kategori_id'],
                'name' => $category['nama_kategori'],
            ],
            'total'    => count($formattedData),
            'data'     => $formattedData,
        ]);
    }

    /**
     * Cari FAQ dalam Kategori Tertentu
     * GET /api/faqs/category/{category_id}/search?keyword=...
     */
    public function search($categoryId = null)
    {
        return $this->index($categoryId);
    }

    /**
     * Detail satu data FAQ
     * GET /api/faqs/detail/{id}
     */
    public function show($id = null)
    {
        if (!is_numeric($id) || (int)$id <= 0) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Parameter ID FAQ tidak valid.'
            ]);
        }

        $faq = $this->infoModel->find((int)$id);
        if (!$faq) {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => 'FAQ tidak ditemukan.'
            ]);
        }

        $category = $this->kategoriModel->find($faq['kategori_id']);

        return $this->response->setStatusCode(200)->setJSON([
            'success' => true,
            'data'    => [
                'id'            => (int) $faq['info_id'],
                'question'      => $faq['judul'],
                'answer'        => $faq['isi'],
                'kata_kunci'    => $faq['kata_kunci'],
                'kategori_id'   => (int) $faq['kategori_id'],
                'nama_kategori' => $category['nama_kategori'] ?? '',
                'tgl_buat'      => $faq['tgl_buat'],
                'tgl_update'    => $faq['tgl_update'],
                'jumlah_tayang' => (int) $faq['jumlah_tayang']
            ]
        ]);
    }

    // =========================================================================
    // ENDPOINT TERPROTEKSI: MEKANISME PENGIRIMAN DATA DARI APLIKASI LAIN
    // =========================================================================

    /**
     * Endpoint Cek Koneksi & Autentikasi API
     * GET /api/ping
     */
    public function ping()
    {
        $apiUser = $this->getApiUser();

        return $this->response->setStatusCode(200)->setJSON([
            'success'          => true,
            'message'          => 'Koneksi API Dilan berhasil terhubung.',
            'authenticated_as' => [
                'nip'           => $apiUser['nip'] ?? null,
                'role'          => $apiUser['role'] ?? null,
                'kategori_id'   => $apiUser['kategori_id'] ?? null,
                'nama_kategori' => $apiUser['nama_kategori'] ?? null,
                'url_apk'       => $apiUser['url_apk'] ?? null,
                'is_master'     => $apiUser['is_master'] ?? false
            ],
            'server_time'      => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Ambil data FAQ milik OPD yang sedang terautentikasi
     * GET /api/faqs/my
     */
    public function myFaqs()
    {
        $apiUser = $this->getApiUser();
        $builder = $this->infoModel;

        if ($apiUser['role'] === 'user') {
            $builder = $builder->where('kategori_id', $apiUser['kategori_id']);
        } elseif ($catId = $this->request->getGet('kategori_id')) {
            $builder = $builder->where('kategori_id', (int)$catId);
        }

        $search = $this->request->getGet('search') ?? $this->request->getGet('keyword');
        if (!empty($search)) {
            $search = trim($search);
            $builder = $builder->groupStart()
                ->like('judul', $search)
                ->orLike('isi', $search)
                ->orLike('kata_kunci', $search)
            ->groupEnd();
        }

        $faqs = $builder->orderBy('info_id', 'DESC')->findAll();

        $formattedData = array_map(function ($faq) {
            return [
                'id'              => (int) $faq['info_id'],
                'judul'           => $faq['judul'],
                'isi'             => $faq['isi'],
                'kata_kunci'      => $faq['kata_kunci'],
                'kategori_id'     => (int) $faq['kategori_id'],
                'tgl_buat'        => $faq['tgl_buat'],
                'tgl_update'      => $faq['tgl_update'],
                'dibuat_oleh'     => $faq['dibuat_oleh'],
                'diperbarui_oleh' => $faq['diperbarui_oleh']
            ];
        }, $faqs);

        return $this->response->setStatusCode(200)->setJSON([
            'success'  => true,
            'category' => [
                'id'   => $apiUser['kategori_id'],
                'name' => $apiUser['nama_kategori']
            ],
            'total'    => count($formattedData),
            'data'     => $formattedData
        ]);
    }

    /**
     * Kirim & Simpan Data FAQ Berdasarkan Kode Kategori
     * POST /api/faqs/category/{category_code}
     * POST /api/faqs/category/{category_code}/send
     *
     * @param string|int|null $categoryIdentifier
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function createByCategory($categoryIdentifier = null)
    {
        return $this->create($categoryIdentifier);
    }

    /**
     * Kirim & Simpan Data FAQ Baru dari Aplikasi Lain
     * POST /api/faqs
     * POST /api/faqs/send
     * POST /api/faqs/category/{category_code}
     *
     * @param string|int|null $categoryIdentifier
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function create($categoryIdentifier = null)
    {
        $input = $this->getRequestInput();

        // 1. Validasi Input (Judul dan Isi wajib diisi)
        $rules = [
            'judul' => [
                'label' => 'Judul Pertanyaan',
                'rules' => 'required|min_length[3]|max_length[256]'
            ],
            'isi' => [
                'label' => 'Isi Jawaban',
                'rules' => 'required'
            ],
            'kata_kunci' => [
                'label' => 'Kata Kunci',
                'rules' => 'permit_empty|max_length[128]'
            ]
        ];

        if (!$this->validateData($input, $rules)) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Validasi data gagal.',
                'errors'  => $this->validator->getErrors()
            ]);
        }

        // 2. Ambil Kode / ID Kategori dari berbagai kemungkinan input:
        // - Parameter URL: /api/faqs/category/{category_code}
        // - Parameter Body: 'kode_kategori', 'category_code', 'kategori_id', 'category_id'
        $catIdentifier = $categoryIdentifier 
            ?? $input['kode_kategori'] 
            ?? $input['category_code'] 
            ?? $input['kategori_id'] 
            ?? $input['category_id'] 
            ?? null;

        $apiUser = $this->getApiUser();

        // Jika tidak disertakan di URL maupun body, fallback ke kategori milik API Key pengguna
        if (empty($catIdentifier) && !empty($apiUser['kategori_id'])) {
            $catIdentifier = $apiUser['kategori_id'];
        }

        if (empty($catIdentifier)) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Kode atau ID kategori wajib disertakan (pada URL /api/faqs/category/{kode_kategori} atau pada body: kode_kategori).'
            ]);
        }

        // Cari Kategori di Database berdasarkan ID, kode_kategori, atau slug
        $category = $this->kategoriModel->findByIdentifier($catIdentifier);
        if (!$category) {
            return $this->response->setStatusCode(404)->setJSON([
                'success'    => false,
                'message'    => "Kategori dengan kode atau ID '{$catIdentifier}' tidak ditemukan di database.",
                'error_code' => 'CATEGORY_NOT_FOUND'
            ]);
        }

        $targetCategoryId = (int) $category['kategori_id'];
        $authorName       = 'API';
        $logNip           = null;

        // 3. Hak Akses & Data Scoping
        if ($apiUser) {
            // Jika request menggunakan API Key
            if ($apiUser['role'] === 'user') {
                if ((int)$apiUser['kategori_id'] !== $targetCategoryId) {
                    return $this->response->setStatusCode(403)->setJSON([
                        'success'    => false,
                        'message'    => "Akses ditolak: API Key Anda hanya terdaftar untuk kategori ID {$apiUser['kategori_id']} ({$apiUser['nama_kategori']}), tidak diizinkan mengirim ke kategori {$category['nama_kategori']}.",
                        'error_code' => 'FORBIDDEN_CATEGORY'
                    ]);
                }
            }
            $authorName = 'API (' . ($apiUser['nip'] ?? 'Sistem') . ')';
            $logNip     = $apiUser['nip'] ?? null;
        } else {
            // Jika request tanpa API Key
            $apiConfig = config(\Config\Api::class);
            if (!empty($apiConfig->requireAuth)) {
                return $this->response->setStatusCode(401)->setJSON([
                    'success'    => false,
                    'message'    => 'Akses ditolak: API Key wajib disertakan.',
                    'error_code' => 'API_KEY_MISSING'
                ]);
            }

            // Ambil NIP OPD pemilik kategori dari tabel pengguna untuk log audit
            $userModel = new UserModel();
            $opdUser = $userModel->where('kategori_id', $targetCategoryId)->first();
            if ($opdUser) {
                $authorName = 'API (' . $opdUser['nip'] . ')';
                $logNip     = $opdUser['nip'];
            } else {
                $authorName = 'API (' . ($category['kode_kategori'] ?? $category['nama_kategori']) . ')';
            }
        }

        // 4. Siapkan Data untuk Disimpan
        $judul      = trim($input['judul']);
        $isi        = $this->decodeIsi($input['isi']);
        $kataKunci  = trim($input['kata_kunci'] ?? '');

        $insertData = [
            'judul'           => $judul,
            'isi'             => $isi,
            'kata_kunci'      => $kataKunci,
            'kategori_id'     => $targetCategoryId,
            'tgl_buat'        => date('Y-m-d H:i:s'),
            'tgl_update'      => date('Y-m-d H:i:s'),
            'dibuat_oleh'     => $authorName,
            'diperbarui_oleh' => $authorName,
            'jumlah_tayang'   => 0
        ];

        $newId = $this->infoModel->insert($insertData);

        if (!$newId) {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Gagal menyimpan data ke database server.'
            ]);
        }

        // 5. Catat Log Audit ke Tabel Operator
        $this->logOperator((int)$newId, 1, $logNip); // 1 = CREATE

        return $this->response->setStatusCode(201)->setJSON([
            'success' => true,
            'message' => "Data FAQ/Informasi berhasil dikirim dan disimpan ke kategori '{$category['nama_kategori']}'.",
            'data'    => [
                'id'            => (int) $newId,
                'judul'         => $judul,
                'isi'           => $isi,
                'kata_kunci'    => $kataKunci,
                'kategori_id'   => $targetCategoryId,
                'kode_kategori' => $category['kode_kategori'] ?? null,
                'nama_kategori' => $category['nama_kategori'],
                'dibuat_oleh'   => $authorName,
                'tgl_buat'      => $insertData['tgl_buat']
            ]
        ]);
    }

    /**
     * Perbarui Data FAQ yang Sudah Ada
     * PUT /api/faqs/{id}
     * POST /api/faqs/update/{id}
     */
    public function update($id = null)
    {
        $apiUser = $this->getApiUser();
        if (!$apiUser) {
            return $this->response->setStatusCode(401)->setJSON([
                'success' => false,
                'message' => 'Autentikasi gagal.'
            ]);
        }

        if (!is_numeric($id) || (int)$id <= 0) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Parameter ID FAQ tidak valid.'
            ]);
        }

        $id = (int) $id;
        $existing = $this->infoModel->find($id);

        if (!$existing) {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => "Data FAQ dengan ID {$id} tidak ditemukan."
            ]);
        }

        // Cek Hak Akses Scoping Kategori
        if ($apiUser['role'] === 'user' && (int)$existing['kategori_id'] !== (int)$apiUser['kategori_id']) {
            return $this->response->setStatusCode(403)->setJSON([
                'success'    => false,
                'message'    => 'Akses ditolak: Anda tidak memiliki wewenang untuk mengubah data FAQ dari kategori lain.',
                'error_code' => 'FORBIDDEN_CATEGORY'
            ]);
        }

        $input = $this->getRequestInput();

        $updateData = [
            'tgl_update'      => date('Y-m-d H:i:s'),
            'diperbarui_oleh' => 'API (' . ($apiUser['nip'] ?? 'Sistem') . ')'
        ];

        if (isset($input['judul'])) {
            $judul = trim($input['judul']);
            if (mb_strlen($judul) < 3 || mb_strlen($judul) > 256) {
                return $this->response->setStatusCode(400)->setJSON([
                    'success' => false,
                    'message' => 'Panjang judul minimal 3 dan maksimal 256 karakter.'
                ]);
            }
            $updateData['judul'] = $judul;
        }

        if (isset($input['isi'])) {
            if (empty(trim($input['isi']))) {
                return $this->response->setStatusCode(400)->setJSON([
                    'success' => false,
                    'message' => 'Isi FAQ tidak boleh kosong.'
                ]);
            }
            $updateData['isi'] = $this->decodeIsi($input['isi']);
        }

        if (isset($input['kata_kunci'])) {
            $updateData['kata_kunci'] = trim($input['kata_kunci']);
        }

        // Jika Master Admin ingin memindahkan ke kategori lain
        if ($apiUser['role'] === 'admin' && !empty($input['kategori_id'])) {
            $newCat = $this->kategoriModel->find((int)$input['kategori_id']);
            if (!$newCat) {
                return $this->response->setStatusCode(404)->setJSON([
                    'success' => false,
                    'message' => 'Kategori target pemindahan tidak ditemukan.'
                ]);
            }
            $updateData['kategori_id'] = (int)$input['kategori_id'];
        }

        $this->infoModel->update($id, $updateData);

        // Catat Log Operator (2 = UPDATE)
        $this->logOperator($id, 2);

        $updatedRecord = $this->infoModel->find($id);

        return $this->response->setStatusCode(200)->setJSON([
            'success' => true,
            'message' => 'Data FAQ/Informasi berhasil diperbarui.',
            'data'    => [
                'id'              => (int) $updatedRecord['info_id'],
                'judul'           => $updatedRecord['judul'],
                'isi'             => $updatedRecord['isi'],
                'kata_kunci'      => $updatedRecord['kata_kunci'],
                'kategori_id'     => (int) $updatedRecord['kategori_id'],
                'diperbarui_oleh' => $updatedRecord['diperbarui_oleh'],
                'tgl_update'      => $updatedRecord['tgl_update']
            ]
        ]);
    }

    /**
     * Hapus Data FAQ
     * DELETE /api/faqs/{id}
     * POST /api/faqs/delete/{id}
     */
    public function delete($id = null)
    {
        $apiUser = $this->getApiUser();
        if (!$apiUser) {
            return $this->response->setStatusCode(401)->setJSON([
                'success' => false,
                'message' => 'Autentikasi gagal.'
            ]);
        }

        if (!is_numeric($id) || (int)$id <= 0) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Parameter ID FAQ tidak valid.'
            ]);
        }

        $id = (int) $id;
        $existing = $this->infoModel->find($id);

        if (!$existing) {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => "Data FAQ dengan ID {$id} tidak ditemukan."
            ]);
        }

        // Cek Hak Akses Scoping Kategori
        if ($apiUser['role'] === 'user' && (int)$existing['kategori_id'] !== (int)$apiUser['kategori_id']) {
            return $this->response->setStatusCode(403)->setJSON([
                'success'    => false,
                'message'    => 'Akses ditolak: Anda tidak memiliki wewenang untuk menghapus data FAQ dari kategori lain.',
                'error_code' => 'FORBIDDEN_CATEGORY'
            ]);
        }

        $this->infoModel->delete($id);

        return $this->response->setStatusCode(200)->setJSON([
            'success'    => true,
            'message'    => "Data FAQ dengan ID {$id} berhasil dihapus.",
            'deleted_id' => $id
        ]);
    }

    /**
     * Kirim & Simpan Data FAQ Secara Massal Berdasarkan Kode Kategori
     * POST /api/faqs/category/{category_code}/batch
     * POST /api/faqs/category/{category_code}/bulk
     *
     * @param string|int|null $categoryIdentifier
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function batchCreateByCategory($categoryIdentifier = null)
    {
        return $this->batchCreate($categoryIdentifier);
    }

    /**
     * Kirim & Simpan Data FAQ Secara Massal (Batch/Bulk Ingestion)
     * POST /api/faqs/batch
     * POST /api/faqs/bulk
     * POST /api/faqs/category/{category_code}/batch
     *
     * @param string|int|null $categoryIdentifier
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function batchCreate($categoryIdentifier = null)
    {
        $input = $this->getRequestInput();

        // 1. Ambil Kode / ID Kategori
        $catIdentifier = $categoryIdentifier 
            ?? $input['kode_kategori'] 
            ?? $input['category_code'] 
            ?? $input['kategori_id'] 
            ?? $input['category_id'] 
            ?? null;

        $apiUser = $this->getApiUser();

        if (empty($catIdentifier) && !empty($apiUser['kategori_id'])) {
            $catIdentifier = $apiUser['kategori_id'];
        }

        $defaultCategory = null;
        if (!empty($catIdentifier)) {
            $defaultCategory = $this->kategoriModel->findByIdentifier($catIdentifier);
            if (!$defaultCategory) {
                return $this->response->setStatusCode(404)->setJSON([
                    'success'    => false,
                    'message'    => "Kategori dengan kode atau ID '{$catIdentifier}' tidak ditemukan di database.",
                    'error_code' => 'CATEGORY_NOT_FOUND'
                ]);
            }
        }

        $defaultCatId = $defaultCategory ? (int)$defaultCategory['kategori_id'] : null;

        // Cek Hak Akses Scoping jika ada API Key
        $logNip     = null;
        $authorName = 'API';

        if ($apiUser) {
            if ($apiUser['role'] === 'user' && $defaultCatId !== null) {
                if ((int)$apiUser['kategori_id'] !== $defaultCatId) {
                    return $this->response->setStatusCode(403)->setJSON([
                        'success'    => false,
                        'message'    => "Akses ditolak: API Key Anda hanya terdaftar untuk kategori ID {$apiUser['kategori_id']}, tidak diizinkan mengirim ke kategori {$defaultCategory['nama_kategori']}.",
                        'error_code' => 'FORBIDDEN_CATEGORY'
                    ]);
                }
            }
            $authorName = 'API (' . ($apiUser['nip'] ?? 'Sistem') . ')';
            $logNip     = $apiUser['nip'] ?? null;
        } else {
            $apiConfig = config(\Config\Api::class);
            if (!empty($apiConfig->requireAuth)) {
                return $this->response->setStatusCode(401)->setJSON([
                    'success'    => false,
                    'message'    => 'Akses ditolak: API Key wajib disertakan.',
                    'error_code' => 'API_KEY_MISSING'
                ]);
            }

            if ($defaultCatId !== null) {
                $userModel = new UserModel();
                $opdUser = $userModel->where('kategori_id', $defaultCatId)->first();
                if ($opdUser) {
                    $authorName = 'API (' . $opdUser['nip'] . ')';
                    $logNip     = $opdUser['nip'];
                } else {
                    $authorName = 'API (' . ($defaultCategory['kode_kategori'] ?? $defaultCategory['nama_kategori']) . ')';
                }
            }
        }

        // Mendukung format {"items": [...]} atau array langsung [...]
        $items = isset($input['items']) && is_array($input['items']) ? $input['items'] : (array_is_list($input) ? $input : null);

        if (empty($items) || !is_array($items)) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Data items batch tidak valid. Harap sertakan daftar FAQ dalam format JSON array.'
            ]);
        }

        if (count($items) > 100) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Maksimal 100 data FAQ per request batch untuk menjaga performa server.'
            ]);
        }

        $successItems = [];
        $failedItems  = [];

        foreach ($items as $idx => $item) {
            $itemJudul = trim($item['judul'] ?? $item['question'] ?? '');
            $itemIsi   = $item['isi'] ?? $item['answer'] ?? '';
            $itemTags  = trim($item['kata_kunci'] ?? $item['tags'] ?? '');

            // Kategori ID per item atau default
            $itemCatId = $defaultCatId;
            if (!empty($item['kode_kategori']) || !empty($item['kategori_id'])) {
                $itemCatIdent = $item['kode_kategori'] ?? $item['kategori_id'];
                $itemCat = $this->kategoriModel->findByIdentifier($itemCatIdent);
                if ($itemCat) {
                    $itemCatId = (int)$itemCat['kategori_id'];
                }
            }

            if (empty($itemJudul) || empty($itemIsi) || empty($itemCatId)) {
                $failedItems[] = [
                    'index'  => $idx,
                    'judul'  => $itemJudul,
                    'reason' => 'Judul, isi, atau kategori tidak boleh kosong.'
                ];
                continue;
            }

            $insertData = [
                'judul'           => $itemJudul,
                'isi'             => $this->decodeIsi($itemIsi),
                'kata_kunci'      => $itemTags,
                'kategori_id'     => $itemCatId,
                'tgl_buat'        => date('Y-m-d H:i:s'),
                'tgl_update'      => date('Y-m-d H:i:s'),
                'dibuat_oleh'     => $authorName,
                'diperbarui_oleh' => $authorName,
                'jumlah_tayang'   => 0
            ];

            $newId = $this->infoModel->insert($insertData);
            if ($newId) {
                $this->logOperator((int)$newId, 1, $logNip);
                $successItems[] = [
                    'id'          => (int) $newId,
                    'judul'       => $itemJudul,
                    'kategori_id' => $itemCatId
                ];
            } else {
                $failedItems[] = [
                    'index'  => $idx,
                    'judul'  => $itemJudul,
                    'reason' => 'Gagal disimpan ke database.'
                ];
            }
        }

        return $this->response->setStatusCode(201)->setJSON([
            'success'       => true,
            'message'       => 'Proses pengiriman data FAQ massal selesai.',
            'category'      => $defaultCategory ? [
                'id'            => (int) $defaultCategory['kategori_id'],
                'kode_kategori' => $defaultCategory['kode_kategori'] ?? null,
                'nama_kategori' => $defaultCategory['nama_kategori']
            ] : null,
            'total_success' => count($successItems),
            'total_failed'  => count($failedItems),
            'data'          => $successItems,
            'failed'        => $failedItems
        ]);
    }
}
