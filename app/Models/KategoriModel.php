<?php

namespace App\Models;

use CodeIgniter\Model;

class KategoriModel extends Model
{
    protected $table            = 'kategori';
    protected $primaryKey       = 'kategori_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['nama_kategori', 'kode_kategori'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Cari kategori berdasarkan identifier fleksibel:
     * - ID Kategori (integer)
     * - Kolom kode_kategori (string/slug)
     * - Nama kategori
     * - NIP / username OPD di tabel pengguna
     *
     * @param string|int|null $identifier
     * @return array|null
     */
    public function findByIdentifier($identifier): ?array
    {
        if (empty($identifier)) {
            return null;
        }

        $identifier = trim((string) $identifier);

        // 1. Cek jika numeric ID
        if (is_numeric($identifier) && (int)$identifier > 0) {
            $found = $this->find((int)$identifier);
            if ($found) {
                return $found;
            }
        }

        // 2. Cek kode_kategori persis
        $found = $this->where('kode_kategori', $identifier)->first();
        if ($found) {
            return $found;
        }

        // 3. Cek format slug (lowercase, dash)
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $identifier), '-'));
        if (!empty($slug)) {
            $found = $this->where('kode_kategori', $slug)->first();
            if ($found) {
                return $found;
            }
        }

        // 4. Cek nama_kategori persis
        $found = $this->where('nama_kategori', $identifier)->first();
        if ($found) {
            return $found;
        }

        // 5. Cek dari NIP / username OPD di tabel pengguna
        $userModel = new \App\Models\UserModel();
        $user = $userModel->where('nip', $identifier)->first();
        if ($user && !empty($user['kategori_id'])) {
            return $this->find((int)$user['kategori_id']);
        }

        return null;
    }
}
