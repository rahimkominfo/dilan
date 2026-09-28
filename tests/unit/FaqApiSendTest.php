<?php

namespace CodeIgniter;

use App\Models\InfoModel;
use App\Models\KategoriModel;
use App\Models\UserModel;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;

/**
 * Unit & Feature Tests for API Data Sending / Ingestion Mechanism
 * 
 * @internal
 */
final class FaqApiSendTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    protected string $opdApiKey;
    protected int $opdCategoryId;
    protected string $opdNip;
    protected string $masterApiKey;

    protected function setUp(): void
    {
        parent::setUp();

        $this->masterApiKey = config(\Config\Api::class)->masterKey ?? 'dilan_master_key_secret_2026';

        // Ambil salah satu user OPD yang memiliki api_key
        $userModel = new UserModel();
        $user = $userModel->where('peran', 'user')->where('api_key IS NOT NULL')->first();

        if ($user) {
            $this->opdApiKey     = $user['api_key'];
            $this->opdCategoryId = (int) $user['kategori_id'];
            $this->opdNip        = $user['nip'];
        } else {
            // Fallback jika belum ada
            $this->opdApiKey     = 'dilan_key_test_sample_12345';
            $this->opdCategoryId = 6;
            $this->opdNip        = '198611052009042003';
        }
    }

    // =========================================================================
    // 1. PING & AUTENTIKASI TESTS
    // =========================================================================

    public function testPingWithoutApiKeyReturns401()
    {
        $result = $this->get('api/ping');
        $result->assertStatus(401);
        $json = json_decode($result->getJSON(), true);
        $this->assertFalse($json['success']);
        $this->assertEquals('API_KEY_MISSING', $json['error_code']);
    }

    public function testPingWithInvalidApiKeyReturns401()
    {
        $result = $this->withHeaders(['X-API-KEY' => 'invalid_random_key_999999'])
            ->get('api/ping');

        $result->assertStatus(401);
        $json = json_decode($result->getJSON(), true);
        $this->assertFalse($json['success']);
        $this->assertEquals('INVALID_API_KEY', $json['error_code']);
    }

    public function testPingWithMasterApiKeyReturns200()
    {
        $result = $this->withHeaders(['X-API-KEY' => $this->masterApiKey])
            ->get('api/ping');

        $result->assertStatus(200);
        $json = json_decode($result->getJSON(), true);
        $this->assertTrue($json['success']);
        $this->assertEquals('admin', $json['authenticated_as']['role']);
        $this->assertTrue($json['authenticated_as']['is_master']);
    }

    public function testPingWithOpdBearerTokenReturns200()
    {
        $result = $this->withHeaders(['Authorization' => 'Bearer ' . $this->opdApiKey])
            ->get('api/ping');

        $result->assertStatus(200);
        $json = json_decode($result->getJSON(), true);
        $this->assertTrue($json['success']);
        $this->assertEquals($this->opdNip, $json['authenticated_as']['nip']);
        $this->assertEquals($this->opdCategoryId, $json['authenticated_as']['kategori_id']);
    }

    // =========================================================================
    // 2. CREATE FAQ (PENGIRIMAN DATA TUNGGAL) TESTS
    // =========================================================================

    public function testCreateFaqWithoutAuthReturns401()
    {
        $result = $this->withBody(json_encode([
            'judul' => 'Tes Pertanyaan Tanpa Auth',
            'isi'   => 'Isi jawaban tes'
        ]))->withHeaders(['Content-Type' => 'application/json'])
           ->post('api/faqs');

        $result->assertStatus(401);
    }

    public function testCreateFaqValidationFailureReturns400()
    {
        $result = $this->withHeaders([
            'X-API-KEY'    => $this->opdApiKey,
            'Content-Type' => 'application/json'
        ])->withBody(json_encode([
            'judul' => '', // Kosong -> harus gagal
            'isi'   => ''
        ]))->post('api/faqs');

        $result->assertStatus(400);
        $json = json_decode($result->getJSON(), true);
        $this->assertFalse($json['success']);
        $this->assertArrayHasKey('errors', $json);
    }

    public function testCreateFaqForbiddenOtherCategoryReturns403()
    {
        // Mencoba mengirim ke kategori yang bukan miliknya (misal 9999 atau kategori lain)
        $foreignCategoryId = ($this->opdCategoryId === 1) ? 2 : 1;

        $result = $this->withHeaders([
            'X-API-KEY'    => $this->opdApiKey,
            'Content-Type' => 'application/json'
        ])->withBody(json_encode([
            'judul'       => 'Tes Pertanyaan Kategori Lain',
            'isi'         => 'Mencoba injeksi ke kategori orang lain',
            'kategori_id' => $foreignCategoryId
        ]))->post('api/faqs');

        $result->assertStatus(403);
        $json = json_decode($result->getJSON(), true);
        $this->assertFalse($json['success']);
        $this->assertEquals('FORBIDDEN_CATEGORY', $json['error_code']);
    }

    public function testCreateFaqSuccessReturns201()
    {
        $testTitle = 'Bagaimana cara mengirim data via API Dilan? ' . time();
        $testContent = '<p>Kirimkan request HTTP POST ke endpoint /api/faqs dengan menyertakan header X-API-KEY.</p>';
        $testTags = 'api, integrasi, kirim data';

        $result = $this->withHeaders([
            'X-API-KEY'    => $this->opdApiKey,
            'Content-Type' => 'application/json'
        ])->withBody(json_encode([
            'judul'      => $testTitle,
            'isi'        => $testContent,
            'kata_kunci' => $testTags
        ]))->post('api/faqs');

        $result->assertStatus(201);
        $json = json_decode($result->getJSON(), true);
        $this->assertTrue($json['success']);
        $this->assertArrayHasKey('data', $json);
        $this->assertEquals($testTitle, $json['data']['judul']);
        $this->assertEquals($this->opdCategoryId, $json['data']['kategori_id']);
        $createdId = $json['data']['id'];
        $this->assertGreaterThan(0, $createdId);

        // Bersihkan data tes
        (new InfoModel())->delete($createdId);
    }

    // =========================================================================
    // 3. UPDATE & DELETE TESTS
    // =========================================================================

    public function testUpdateFaqSuccessReturns200()
    {
        // Buat data sementara
        $infoModel = new InfoModel();
        $tempId = $infoModel->insert([
            'judul'           => 'FAQ Awal untuk Diupdate ' . time(),
            'isi'             => 'Isi awal',
            'kata_kunci'      => 'awal',
            'kategori_id'     => $this->opdCategoryId,
            'tgl_buat'        => date('Y-m-d H:i:s'),
            'dibuat_oleh'     => 'Test Unit',
            'diperbarui_oleh' => 'Test Unit'
        ]);

        $updatedTitle = 'FAQ Setelah Diupdate via API ' . time();

        $result = $this->withHeaders([
            'X-API-KEY'    => $this->opdApiKey,
            'Content-Type' => 'application/json'
        ])->withBody(json_encode([
            'judul' => $updatedTitle,
            'isi'   => 'Isi setelah diupdate via PUT'
        ]))->put('api/faqs/' . $tempId);

        $result->assertStatus(200);
        $json = json_decode($result->getJSON(), true);
        $this->assertTrue($json['success']);
        $this->assertEquals($updatedTitle, $json['data']['judul']);

        // Bersihkan data tes
        $infoModel->delete($tempId);
    }

    public function testDeleteFaqSuccessReturns200()
    {
        // Buat data sementara untuk dihapus
        $infoModel = new InfoModel();
        $tempId = $infoModel->insert([
            'judul'           => 'FAQ Sementara untuk Test Delete ' . time(),
            'isi'             => 'Akan dihapus',
            'kata_kunci'      => 'hapus, test',
            'kategori_id'     => $this->opdCategoryId,
            'tgl_buat'        => date('Y-m-d H:i:s'),
            'dibuat_oleh'     => 'Test Unit',
            'diperbarui_oleh' => 'Test Unit'
        ]);

        $result = $this->withHeaders(['X-API-KEY' => $this->opdApiKey])
            ->delete('api/faqs/' . $tempId);

        $result->assertStatus(200);
        $json = json_decode($result->getJSON(), true);
        $this->assertTrue($json['success']);
        $this->assertEquals($tempId, $json['deleted_id']);

        // Pastikan sudah terhapus dari database
        $this->assertNull($infoModel->find($tempId));
    }

    // =========================================================================
    // 4. BATCH & LIST TESTS
    // =========================================================================

    public function testBatchCreateFaqSuccessReturns201()
    {
        $batchPayload = [
            'items' => [
                [
                    'judul'      => 'Pertanyaan Batch 1 ' . time(),
                    'isi'        => 'Jawaban Batch 1',
                    'kata_kunci' => 'batch1'
                ],
                [
                    'judul'      => 'Pertanyaan Batch 2 ' . time(),
                    'isi'        => 'Jawaban Batch 2',
                    'kata_kunci' => 'batch2'
                ]
            ]
        ];

        $result = $this->withHeaders([
            'X-API-KEY'    => $this->opdApiKey,
            'Content-Type' => 'application/json'
        ])->withBody(json_encode($batchPayload))
          ->post('api/faqs/batch');

        $result->assertStatus(201);
        $json = json_decode($result->getJSON(), true);
        $this->assertTrue($json['success']);
        $this->assertEquals(2, $json['total_success']);
        $this->assertEquals(0, $json['total_failed']);

        // Bersihkan
        $infoModel = new InfoModel();
        foreach ($json['data'] as $item) {
            $infoModel->delete($item['id']);
        }
    }

    public function testMyFaqsReturnsList()
    {
        $result = $this->withHeaders(['X-API-KEY' => $this->opdApiKey])
            ->get('api/faqs/my');

        $result->assertStatus(200);
        $json = json_decode($result->getJSON(), true);
        $this->assertTrue($json['success']);
        $this->assertArrayHasKey('category', $json);
        $this->assertEquals($this->opdCategoryId, $json['category']['id']);
        $this->assertIsArray($json['data']);
    }
}
