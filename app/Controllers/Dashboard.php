<?php

namespace App\Controllers;

class Dashboard extends BaseController
{
    protected $infoModel;
    protected $kategoriModel;
    protected $userModel;

    public function __construct()
    {
        $this->infoModel = new \App\Models\InfoModel();
        $this->kategoriModel = new \App\Models\KategoriModel();
        $this->userModel = new \App\Models\UserModel();
    }

    public function index()
    {
        // 1. Fetch Popular Information (join category name, ordered by views, limit 4)
        $popular_info = $this->infoModel->select('info.*, kategori.nama_kategori')
            ->join('kategori', 'kategori.kategori_id = info.kategori_id', 'left')
            ->orderBy('info.jumlah_tayang', 'DESC')
            ->limit(4)
            ->findAll();

        // 2. Fetch Categories with article count and top 2 articles
        $categories = $this->kategoriModel->findAll();
        foreach ($categories as &$category) {
            $category['jumlah_artikel'] = $this->infoModel->where('kategori_id', $category['kategori_id'])->countAllResults();
            $category['artikel'] = $this->infoModel->where('kategori_id', $category['kategori_id'])->limit(2)->findAll();
        }

        // 3. Fetch FAQ Instansi / OPD based on categories that have users with url_apk
        $opd_list = $this->kategoriModel->select('kategori.*, MAX(pengguna.url_apk) as url_apk')
            ->join('pengguna', 'pengguna.kategori_id = kategori.kategori_id')
            ->groupBy('kategori.kategori_id')
            ->findAll();

        // 4. Statistics
        $total_artikel = $this->infoModel->countAllResults();
        $total_faq = $this->infoModel->like('judul', '?')->countAllResults();

        $data = [
            'title'         => 'Dilan - Sistem Manajemen Pengetahuan',
            'popular_info'  => $popular_info,
            'categories'    => $categories,
            'opd_list'      => $opd_list,
            'total_artikel' => $total_artikel,
            'total_faq'     => $total_faq
        ];

        return view('publik/dashboard', $data);
    }

    public function detail($id = null)
    {
        if (!$id) {
            $article = $this->infoModel->first();
            if (!$article) {
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
            }
            $id = $article['info_id'];
        } else {
            $article = $this->infoModel->find($id);
            if (!$article) {
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
            }
        }

        // Increment count/view
        $this->infoModel->update($id, ['jumlah_tayang' => ($article['jumlah_tayang'] ?? 0) + 1]);
        $article['jumlah_tayang'] += 1;

        // Get category info
        $category = $this->kategoriModel->find($article['kategori_id']);

        // Fetch author name from API
        $author_name = $this->getNamaByNip($article['dibuat_oleh'] ?? '');

        $data = [
            'title'       => $article['judul'] . ' - Dilan',
            'article'     => $article,
            'category'    => $category,
            'author_name' => $author_name
        ];
        return view('publik/detail', $data);
    }

    private function getNamaByNip($nip)
    {
        if (empty($nip) || !is_numeric($nip)) {
            return $nip ?: 'Admin';
        }

        $apiUrl = 'http://apps.sinjaikab.go.id/api/pegawai/data_pegawai/?nip=' . (int)$nip;
        try {
            $opts = [
                "http" => [
                    "method"  => "GET",
                    "header"  => "Accept: application/json\r\n",
                    "timeout" => 3
                ]
            ];
            $context = stream_context_create($opts);
            $response = @file_get_contents($apiUrl, false, $context);
            if ($response !== false) {
                $data = json_decode($response);
                if (isset($data->nama) && !empty($data->nama)) {
                    return (string)$data->nama;
                }
            }
        } catch (\Exception $e) {
            log_message('error', 'API getNamaByNip error: ' . $e->getMessage());
        }

        return $nip;
    }

    public function faq_detail($id = null)
    {
        if (!$id) {
            $article = $this->infoModel->first();
            if (!$article) {
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
            }
            $id = $article['info_id'];
        } else {
            $article = $this->infoModel->find($id);
            if (!$article) {
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
            }
        }

        // Increment count/view
        $this->infoModel->update($id, ['jumlah_tayang' => ($article['jumlah_tayang'] ?? 0) + 1]);
        $article['jumlah_tayang'] += 1;

        // Get category info
        $category = $this->kategoriModel->find($article['kategori_id']);

        $data = [
            'title'    => $article['judul'] . ' - Dilan',
            'article'  => $article,
            'category' => $category
        ];
        return view('publik/faq_detail', $data);
    }

    public function faq_opd($kategori_id = null)
    {
        if (!$kategori_id) {
            // Fallback to first category with articles
            $firstCategory = $this->kategoriModel->first();
            if (!$firstCategory) {
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
            }
            $kategori_id = $firstCategory['kategori_id'];
        }

        $category = $this->kategoriModel->find($kategori_id);
        if (!$category) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $articles = $this->infoModel->where('kategori_id', $kategori_id)->findAll();
        $opd = $this->userModel->where('kategori_id', $kategori_id)->first();
        $popular_articles = $this->infoModel->where('kategori_id', $kategori_id)
                                            ->orderBy('jumlah_tayang', 'DESC')
                                            ->limit(5)
                                            ->findAll();

        $data = [
            'title'            => 'FAQ ' . $category['nama_kategori'] . ' - Dilan',
            'category'         => $category,
            'articles'         => $articles,
            'opd'              => $opd,
            'popular_articles' => $popular_articles
        ];

        return view('publik/faq_opd', $data);
    }

    public function kategori($id = null)
    {
        if (!$id) {
            $firstCategory = $this->kategoriModel->first();
            if (!$firstCategory) {
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
            }
            $id = $firstCategory['kategori_id'];
        }

        $category = $this->kategoriModel->find($id);
        if (!$category) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $articles = $this->infoModel->where('kategori_id', $id)->findAll();

        $data = [
            'title'    => 'Kategori ' . $category['nama_kategori'] . ' - Dilan',
            'category' => $category,
            'articles' => $articles
        ];
        return view('publik/kategori', $data);
    }

    public function pencarian()
    {
        $keyword = $this->request->getVar('cari') ?? '';

        if (!empty($keyword)) {
            $articles = $this->infoModel->select('info.*, kategori.nama_kategori')
                                        ->join('kategori', 'kategori.kategori_id = info.kategori_id', 'left')
                                        ->like('info.judul', $keyword)
                                        ->orLike('info.isi', $keyword)
                                        ->orLike('info.kata_kunci', $keyword)
                                        ->findAll();
        } else {
            $articles = [];
        }

        $data = [
            'title'    => 'Hasil Pencarian - Dilan',
            'keyword'  => $keyword,
            'articles' => $articles
        ];
        return view('publik/pencarian', $data);
    }
}
