<?php

namespace App\Controllers;

use App\Models\InfoModel;
use App\Models\KategoriModel;
use CodeIgniter\Controller;

class EmbedController extends Controller
{
    protected $infoModel;
    protected $kategoriModel;

    public function __construct()
    {
        $this->infoModel     = new InfoModel();
        $this->kategoriModel = new KategoriModel();
    }

    public function faq($categoryId = null)
    {
        // 1. Set Security Headers agar bisa di-embed di Iframe Pihak Ketiga
        response()->removeHeader('X-Frame-Options');
        response()->setHeader('Content-Security-Policy', "frame-ancestors *");

        // 2. Ambil parameter pencarian awal jika ada
        $search = $this->request->getGet('search') ?? $this->request->getGet('keyword');

        $category = null;
        $articles = [];

        if (!empty($categoryId) && is_numeric($categoryId)) {
            $category = $this->kategoriModel->find($categoryId);
            
            if ($category) {
                $builder = $this->infoModel->where('kategori_id', $categoryId);
                
                if (!empty($search)) {
                    $search = trim($search);
                    $builder->groupStart()
                        ->like('judul', $search)
                        ->orLike('isi', $search)
                    ->groupEnd();
                }
                
                $articles = $builder->findAll();
            }
        } else {
            // Jika tanpa categoryId, ambil seluruh FAQ
            $builder = $this->infoModel;
            
            if (!empty($search)) {
                $search = trim($search);
                $builder->groupStart()
                    ->like('judul', $search)
                    ->orLike('isi', $search)
                ->groupEnd();
            }
            
            $articles = $builder->findAll();
        }

        $data = [
            'category'   => $category,
            'articles'   => $articles,
            'search'     => $search,
            'categoryId' => $categoryId
        ];

        return view('embed/faq', $data);
    }
}
