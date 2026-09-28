<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'pengguna';
    protected $primaryKey       = 'pengguna_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['nip', 'password', 'peran', 'kategori_id', 'url_apk', 'api_key'];

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
     * Cari pengguna berdasarkan API Key
     *
     * @param string $apiKey
     * @return array|null
     */
    public function findByApiKey(string $apiKey): ?array
    {
        if (empty($apiKey)) {
            return null;
        }

        return $this->where('api_key', trim($apiKey))->first();
    }

    /**
     * Buat dan simpan API Key baru untuk pengguna
     *
     * @param int $penggunaId
     * @return string
     */
    public function generateApiKey(int $penggunaId): string
    {
        $newKey = 'dilan_key_' . bin2hex(random_bytes(16));
        $this->update($penggunaId, ['api_key' => $newKey]);
        return $newKey;
    }
}
