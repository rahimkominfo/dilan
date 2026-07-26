<?php

namespace App\Controllers;

use App\Models\InfoModel;
use App\Models\KategoriModel;
use CodeIgniter\RESTful\ResourceController;

class FaqApiController extends ResourceController
{
    protected $infoModel;
    protected $kategoriModel;

    public function __construct()
    {
        $this->infoModel     = new InfoModel();
        $this->kategoriModel = new KategoriModel();
    }

    /**
     * Ambil seluruh FAQ berdasarkan ID Kategori
     * GET /api/faqs/category/{category_id}
     * Juga mendukung query search: GET /api/faqs/category/{category_id}?search=login
     *
     * @param int|string|null $categoryId
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function index($categoryId = null)
    {
        // 1. Validasi Parameter ID Kategori
        if (!is_numeric($categoryId) || (int)$categoryId <= 0) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Parameter category ID tidak valid.'
            ]);
        }

        $categoryId = (int) $categoryId;

        // 2. Cek Keberadaan Kategori
        $category = $this->kategoriModel->find($categoryId);
        if (!$category) {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => 'FAQ tidak ditemukan.'
            ]);
        }

        // 3. Cek apakah ada parameter pencarian (search / keyword)
        $search = $this->request->getGet('search') ?? $this->request->getGet('keyword');

        // 4. Query Data FAQ dengan Filter Kategori
        $builder = $this->infoModel->where('kategori_id', $categoryId);

        if (!empty($search)) {
            $search = trim($search);
            $builder->groupStart()
                ->like('judul', $search)
                ->orLike('isi', $search)
            ->groupEnd();
        }

        $faqs = $builder->findAll();

        // 5. Jika Data FAQ Kosong / Tidak Ditemukan
        if (empty($faqs)) {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => 'FAQ tidak ditemukan.'
            ]);
        }

        // 6. Format Resource JSON
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
     * GET /api/faqs/category/{category_id}/search?keyword=login
     *
     * @param int|string|null $categoryId
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function search($categoryId = null)
    {
        // 1. Validasi Parameter ID Kategori
        if (!is_numeric($categoryId) || (int)$categoryId <= 0) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Parameter category ID tidak valid.'
            ]);
        }

        $categoryId = (int) $categoryId;

        // 2. Cek Keberadaan Kategori
        $category = $this->kategoriModel->find($categoryId);
        if (!$category) {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => 'FAQ tidak ditemukan.'
            ]);
        }

        // 3. Ambil kata kunci pencarian
        $keyword = $this->request->getGet('keyword') ?? $this->request->getGet('search');

        // 4. Query Data FAQ dengan Filter Kategori
        $builder = $this->infoModel->where('kategori_id', $categoryId);

        if (!empty($keyword)) {
            $keyword = trim($keyword);
            $builder->groupStart()
                ->like('judul', $keyword)
                ->orLike('isi', $keyword)
            ->groupEnd();
        }

        $faqs = $builder->findAll();

        // 5. Jika Data FAQ Kosong / Tidak Ditemukan
        if (empty($faqs)) {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => 'FAQ tidak ditemukan.'
            ]);
        }

        // 6. Format Resource JSON
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
}
