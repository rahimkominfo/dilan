<?php

namespace App\Controllers;

class Admin extends BaseController
{
    protected $infoModel;
    protected $kategoriModel;

    public function __construct()
    {
        $this->infoModel = new \App\Models\InfoModel();
        $this->kategoriModel = new \App\Models\KategoriModel();
        $this->mediaModel = new \App\Models\MediaModel();
    }

    public function informasi()
    {
        $keyword = $this->request->getVar('keyword') ?? '';

        $infoQuery = $this->infoModel;

        if ($keyword !== '') {
            $infoQuery = $infoQuery->groupStart()
                ->like('judul', $keyword)
                ->orLike('isi', $keyword)
                ->orLike('kata_kunci', $keyword)
                ->groupEnd();
        }

        $data = [
            'title'     => 'Kelola Informasi - Panel Admin',
            'informasi' => $infoQuery->paginate(10, 'informasi'),
            'pager'     => $this->infoModel->pager,
            'keyword'   => $keyword
        ];
        return view('admin/informasi', $data);
    }

    public function form_info($id = null)
    {
        $info = null;
        if ($id) {
            $info = $this->infoModel->find($id);
        }

        $data = [
            'title' => $id ? 'Edit Informasi - Panel Admin' : 'Tambah Informasi - Panel Admin',
            'info' => $info,
            'kategori' => $this->kategoriModel->findAll()
        ];
        return view('admin/form_info', $data);
    }

    public function info_store()
    {
        $data = [
            'judul' => $this->request->getPost('judul'),
            'kategori_id' => $this->request->getPost('kategori_id'),
            'isi' => $this->request->getPost('isi'),
            'kata_kunci' => $this->request->getPost('kata_kunci'),
            'tgl_buat' => date('Y-m-d H:i:s'),
            'dibuat_oleh' => session()->get('nip') ?? 'Admin',
            'diperbarui_oleh' => session()->get('nip') ?? 'Admin',
            'jumlah_tayang' => 0
        ];
        $this->infoModel->insert($data);
        return redirect()->to(base_url('admin/informasi'))->with('success', 'Informasi berhasil ditambahkan.');
    }

    public function info_update($id)
    {
        $data = [
            'judul' => $this->request->getPost('judul'),
            'kategori_id' => $this->request->getPost('kategori_id'),
            'isi' => $this->request->getPost('isi'),
            'kata_kunci' => $this->request->getPost('kata_kunci'),
            'tgl_update' => date('Y-m-d H:i:s'),
            'diperbarui_oleh' => session()->get('nip') ?? 'Admin'
        ];
        $this->infoModel->update($id, $data);
        return redirect()->to(base_url('admin/informasi'))->with('success', 'Informasi berhasil diperbarui.');
    }

    public function info_delete($id)
    {
        $this->infoModel->delete($id);
        return redirect()->to(base_url('admin/informasi'))->with('success', 'Informasi berhasil dihapus.');
    }

    public function kategori()
    {
        $keyword = $this->request->getVar('keyword') ?? '';

        $kategoriQuery = $this->kategoriModel;

        if ($keyword !== '') {
            $kategoriQuery = $kategoriQuery->groupStart()
                ->like('nama_kategori', $keyword)
                ->groupEnd();
        }

        $data = [
            'title'    => 'Kelola Kategori - Panel Admin',
            'kategori' => $kategoriQuery->paginate(10, 'kategori'),
            'pager'    => $this->kategoriModel->pager,
            'keyword'  => $keyword
        ];
        return view('admin/kategori', $data);
    }

    public function kategori_store()
    {
        $this->kategoriModel->insert([
            'nama_kategori' => $this->request->getPost('nama_kategori')
        ]);
        return redirect()->to(base_url('admin/kategori'))->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function kategori_update($id)
    {
        $this->kategoriModel->update($id, [
            'nama_kategori' => $this->request->getPost('nama_kategori')
        ]);
        return redirect()->to(base_url('admin/kategori'))->with('success', 'Kategori berhasil diperbarui.');
    }

    public function kategori_delete($id)
    {
        $this->kategoriModel->delete($id);
        return redirect()->to(base_url('admin/kategori'))->with('success', 'Kategori berhasil dihapus.');
    }

    public function media()
    {
        $keyword = $this->request->getVar('keyword') ?? '';

        $mediaQuery = $this->mediaModel;

        if ($keyword !== '') {
            $mediaQuery = $mediaQuery->groupStart()
                ->like('nama', $keyword)
                ->orLike('file', $keyword)
                ->groupEnd();
        }

        $data = [
            'title'   => 'Media Pustaka - Panel Admin',
            'media'   => $mediaQuery->paginate(10, 'media'),
            'pager'   => $this->mediaModel->pager,
            'keyword' => $keyword
        ];
        return view('admin/media', $data);
    }

    public function media_upload()
    {
        $file = $this->request->getFile('media_file');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads', $newName);
            
            // Get display name from input or fallback to client file name
            $nama = $this->request->getPost('nama');
            if (empty($nama)) {
                $nama = $file->getClientName();
            }

            $this->mediaModel->insert([
                'nama'       => $nama,
                'file'       => $newName,
                'tipe_media' => $file->getClientMimeType(),
                'ukuran_media' => $file->getSizeByUnit('kb') . ' kb'
            ]);
        }
        return redirect()->to(base_url('admin/media'))->with('success', 'Media berhasil diupload.');
    }

    public function media_delete($id)
    {
        $media = $this->mediaModel->find($id);
        if ($media) {
            $filePath = FCPATH . 'uploads/' . $media['file'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            $this->mediaModel->delete($id);
        }
        return redirect()->to(base_url('admin/media'))->with('success', 'Media berhasil dihapus.');
    }

    public function operator()
    {
        $keyword = $this->request->getVar('keyword') ?? '';
        $operatorModel = new \App\Models\OperatorModel();

        $operatorQuery = $operatorModel->select('operator.*, info.judul, jenis.nama_jenis')
            ->join('info', 'info.info_id = operator.info_id', 'left')
            ->join('jenis', 'jenis.jenis_id = operator.jenis_id', 'left')
            ->orderBy('operator.tgl_tulis', 'DESC');

        if ($keyword !== '') {
            $operatorQuery = $operatorQuery->groupStart()
                ->like('operator.nip', $keyword)
                ->orLike('info.judul', $keyword)
                ->orLike('jenis.nama_jenis', $keyword)
                ->groupEnd();
        }

        $operatorData = $operatorQuery->paginate(10, 'operator');

        // Resolve NIP to Name using API
        foreach ($operatorData as &$op) {
            $op['nama'] = $this->getNamaByNip($op['nip']);
        }

        $data = [
            'title'    => 'Data Operator - Panel Admin',
            'operator' => $operatorData,
            'pager'    => $operatorModel->pager,
            'keyword'  => $keyword
        ];
        return view('admin/operator', $data);
    }

    public function user_opd()
    {
        $userModel = new \App\Models\UserModel();
        $kategoriModel = new \App\Models\KategoriModel();

        $keyword = $this->request->getVar('keyword') ?? '';

        $userQuery = $userModel->select('pengguna.*, kategori.nama_kategori')
            ->join('kategori', 'kategori.kategori_id = pengguna.kategori_id', 'left');

        if ($keyword !== '') {
            $userQuery = $userQuery->groupStart()
                ->like('pengguna.nip', $keyword)
                ->orLike('pengguna.url_apk', $keyword)
                ->orLike('kategori.nama_kategori', $keyword)
                ->groupEnd();
        }

        $userData = $userQuery->paginate(10, 'user_opd');

        // Resolve NIP to Name using API
        foreach ($userData as &$user) {
            $user['nama'] = $this->getNamaByNip($user['nip']);
        }

        $data = [
            'title'    => 'Kelola User OPD - Panel Admin',
            'user_opd' => $userData,
            'pager'    => $userModel->pager,
            'kategori' => $kategoriModel->findAll(),
            'keyword'  => $keyword
        ];
        return view('admin/user_opd', $data);
    }

    public function user_opd_store()
    {
        $userModel = new \App\Models\UserModel();
        $data = [
            'nip'         => $this->request->getPost('nip'),
            'kategori_id' => $this->request->getPost('kategori_id'),
            'url_apk'     => $this->request->getPost('url_apk'),
            'peran'        => 'user',
            'password'    => ''
        ];
        $userModel->insert($data);
        return redirect()->to(base_url('admin/user_opd'))->with('success', 'User OPD berhasil ditambahkan.');
    }

    public function user_opd_update($id)
    {
        $userModel = new \App\Models\UserModel();
        $data = [
            'nip'         => $this->request->getPost('nip'),
            'kategori_id' => $this->request->getPost('kategori_id'),
            'url_apk'     => $this->request->getPost('url_apk')
        ];
        $userModel->update($id, $data);
        return redirect()->to(base_url('admin/user_opd'))->with('success', 'User OPD berhasil diperbarui.');
    }

    public function user_opd_delete($id)
    {
        $userModel = new \App\Models\UserModel();
        $userModel->delete($id);
        return redirect()->to(base_url('admin/user_opd'))->with('success', 'User OPD berhasil dihapus.');
    }

    public function user_info()
    {
        $kategori_id = session()->get('kategori_id'); // From login session
        $informasi = [];
        $kategoriName = 'Semua Kategori';

        if ($kategori_id) {
            $informasi = $this->infoModel->where('kategori_id', $kategori_id)->findAll();
            $kat = $this->kategoriModel->find($kategori_id);
            if ($kat) {
                $kategoriName = $kat['nama_kategori'];
            }
        } else {
            $informasi = $this->infoModel->findAll();
        }

        $data = [
            'title'         => 'Dashboard User OPD - Dilan',
            'informasi'     => $informasi,
            'kategori_name' => $kategoriName
        ];
        return view('admin/user_info', $data);
    }

    public function form_info_user($id = null)
    {
        $info = null;
        if ($id) {
            $info = $this->infoModel->find($id);
        }

        $data = [
            'title' => $id ? 'Edit Info User - Panel Admin' : 'Tambah Info User - Panel Admin',
            'info' => $info,
            'kategori' => $this->kategoriModel->findAll()
        ];
        return view('admin/form_info_user', $data);
    }

    public function user_info_store()
    {
        $data = [
            'judul' => $this->request->getPost('judul'),
            'kategori_id' => session()->get('kategori_id'),
            'isi' => $this->request->getPost('isi'),
            'kata_kunci' => $this->request->getPost('kata_kunci'),
            'tgl_buat' => date('Y-m-d H:i:s'),
            'dibuat_oleh' => session()->get('nip') ?? 'User OPD',
            'diperbarui_oleh' => session()->get('nip') ?? 'User OPD',
            'jumlah_tayang' => 0
        ];
        $this->infoModel->insert($data);
        return redirect()->to(base_url('admin/user_info'))->with('success', 'Informasi berhasil ditambahkan.');
    }

    public function user_info_update($id)
    {
        $data = [
            'judul' => $this->request->getPost('judul'),
            'isi' => $this->request->getPost('isi'),
            'kata_kunci' => $this->request->getPost('kata_kunci'),
            'tgl_update' => date('Y-m-d H:i:s'),
            'diperbarui_oleh' => session()->get('nip') ?? 'User OPD'
        ];
        $this->infoModel->update($id, $data);
        return redirect()->to(base_url('admin/user_info'))->with('success', 'Informasi berhasil diperbarui.');
    }

    public function user_info_delete($id)
    {
        $this->infoModel->delete($id);
        return redirect()->to(base_url('admin/user_info'))->with('success', 'Informasi berhasil dihapus.');
    }

    private function getNamaByNip($nip)
    {
        static $nameCache = [];

        if (empty($nip) || !is_numeric($nip)) {
            return $nip ?: 'Admin';
        }

        if (isset($nameCache[$nip])) {
            return $nameCache[$nip];
        }

        $apiUrl = 'http://apps.sinjaikab.go.id/api/pegawai/data_pegawai/?nip=' . (int)$nip;
        try {
            $opts = [
                "http" => [
                    "method"  => "GET",
                    "header"  => "Accept: application/json\r\n",
                    "timeout" => 2
                ]
            ];
            $context = stream_context_create($opts);
            $response = @file_get_contents($apiUrl, false, $context);
            if ($response !== false) {
                $data = json_decode($response);
                if (isset($data->nama) && !empty($data->nama)) {
                    $nameCache[$nip] = (string)$data->nama;
                    return $nameCache[$nip];
                }
            }
        } catch (\Exception $e) {
            log_message('error', 'API getNamaByNip error: ' . $e->getMessage());
        }

        $nameCache[$nip] = $nip;
        return $nip;
    }
}
