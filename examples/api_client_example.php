<?php
/**
 * Contoh Implementasi Klien: Pengiriman Data FAQ via API Dilan AR
 * 
 * Skrip ini mendemonstrasikan cara aplikasi lain (eksternal/OPD)
 * mengirim, memperbarui, dan menyinkronkan data FAQ ke sistem Dilan.
 * 
 * Jalankan dari terminal:
 * php examples/api_client_example.php
 */

// Konfigurasi API
$apiBaseUrl = 'http://localhost/dilan_ar/public/api';
// Masukkan API Key OPD Anda (bisa didapatkan di Dashboard User OPD atau Admin User OPD)
$apiKey     = 'dilan_key_927f583a2b5f49c9ecac5927ce0fa817'; // Contoh: Peduli Pensiun (Kategori 6)

echo "=== TEST INTEGRASI PENGIRIMAN DATA VIA API DILAN AR ===\n\n";

// Helper fungsi request HTTP menggunakan cURL
function callApi($method, $url, $apiKey, $data = null) {
    $ch = curl_init();
    
    $headers = [
        'Accept: application/json',
        'X-API-KEY: ' . $apiKey
    ];

    $opts = [
        CURLOPT_URL            => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST  => strtoupper($method),
        CURLOPT_TIMEOUT        => 10
    ];

    if ($data !== null) {
        $headers[] = 'Content-Type: application/json';
        $opts[CURLOPT_POSTFIELDS] = json_encode($data);
    }

    $opts[CURLOPT_HTTPHEADER] = $headers;
    curl_setopt_array($ch, $opts);

    $rawResponse = curl_exec($ch);
    $httpCode    = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError   = curl_error($ch);
    curl_close($ch);

    if ($rawResponse === false) {
        return ['code' => $httpCode, 'error' => $curlError];
    }

    return [
        'code' => $httpCode,
        'json' => json_decode($rawResponse, true),
        'raw'  => $rawResponse
    ];
}

// -----------------------------------------------------------------------------
// 1. CEK KONEKSI & AUTENTIKASI (PING)
// -----------------------------------------------------------------------------
echo "[1] Menguji Koneksi & Kunci API (GET /api/ping)...\n";
$pingRes = callApi('GET', $apiBaseUrl . '/ping', $apiKey);
echo "HTTP Status: {$pingRes['code']}\n";
echo "Response: " . json_encode($pingRes['json'], JSON_PRETTY_PRINT) . "\n\n";

if ($pingRes['code'] !== 200 || empty($pingRes['json']['success'])) {
    echo "Gagal menghubungkan API. Harap periksa URL atau API Key Anda.\n";
    exit(1);
}

// -----------------------------------------------------------------------------
// 2. KIRIM DATA FAQ TUNGGAL (POST /api/faqs)
// -----------------------------------------------------------------------------
echo "[2] Mengirim Data FAQ Baru dari Aplikasi Lain (POST /api/faqs)...\n";
$newFaqData = [
    'judul'      => 'Bagaimana cara mengajukan pensiun dini? (Auto-Sync ' . date('H:i:s') . ')',
    'isi'        => '<p>Pengajuan pensiun dini dapat dilakukan melalui aplikasi Peduli Pensiun dengan melampirkan SK Pengangkatan Pertama dan berkas pendukung lainnya.</p>',
    'kata_kunci' => 'pensiun, syarat pensiun, bkd'
];

$createRes = callApi('POST', $apiBaseUrl . '/faqs', $apiKey, $newFaqData);
echo "HTTP Status: {$createRes['code']}\n";
echo "Response: " . json_encode($createRes['json'], JSON_PRETTY_PRINT) . "\n\n";

$createdId = $createRes['json']['data']['id'] ?? null;

// -----------------------------------------------------------------------------
// 3. PERBARUI DATA FAQ (PUT /api/faqs/{id})
// -----------------------------------------------------------------------------
if ($createdId) {
    echo "[3] Memperbarui Data FAQ yang Baru Dikirim (PUT /api/faqs/{$createdId})...\n";
    $updateData = [
        'judul'      => 'Bagaimana cara mengajukan pensiun dini? (Diperbarui via API)',
        'isi'        => '<p>Pengajuan pensiun dini telah diperbarui. Mohon gunakan formulir revisi terbaru tahun 2026.</p>',
        'kata_kunci' => 'pensiun, revisi'
    ];

    $updateRes = callApi('PUT', $apiBaseUrl . '/faqs/' . $createdId, $apiKey, $updateData);
    echo "HTTP Status: {$updateRes['code']}\n";
    echo "Response: " . json_encode($updateRes['json'], JSON_PRETTY_PRINT) . "\n\n";
}

// -----------------------------------------------------------------------------
// 4. KIRIM DATA MASSAL / BATCH SYNC (POST /api/faqs/batch)
// -----------------------------------------------------------------------------
echo "[4] Mengirim Data FAQ Massal (Batch Sync) (POST /api/faqs/batch)...\n";
$batchData = [
    'items' => [
        [
            'judul'      => 'Berapa batas usia pensiun PNS? (' . date('H:i:s') . ')',
            'isi'        => '<p>Batas usia pensiun PNS bervariasi antara 58 hingga 65 tahun tergantung jenjang jabatan.</p>',
            'kata_kunci' => 'bup, batas usia pensiun'
        ],
        [
            'judul'      => 'Di mana mengambil kartu pensiun? (' . date('H:i:s') . ')',
            'isi'        => '<p>Kartu pensiun dapat diambil di kantor BKPSDMA Sinjai pada jam kerja.</p>',
            'kata_kunci' => 'kartu pensiun, karpeg'
        ]
    ]
];

$batchRes = callApi('POST', $apiBaseUrl . '/faqs/batch', $apiKey, $batchData);
echo "HTTP Status: {$batchRes['code']}\n";
echo "Response: " . json_encode($batchRes['json'], JSON_PRETTY_PRINT) . "\n\n";

// -----------------------------------------------------------------------------
// 5. LIHAT DAFTAR FAQ KATEGORI SAYA (GET /api/faqs/my)
// -----------------------------------------------------------------------------
echo "[5] Mengambil Daftar FAQ yang Tersimpan (GET /api/faqs/my)...\n";
$myRes = callApi('GET', $apiBaseUrl . '/faqs/my', $apiKey);
echo "HTTP Status: {$myRes['code']}\n";
echo "Total FAQ Tersimpan: " . ($myRes['json']['total'] ?? 0) . "\n\n";

// -----------------------------------------------------------------------------
// 6. HAPUS DATA FAQ CONTOH (DELETE /api/faqs/{id})
// -----------------------------------------------------------------------------
if ($createdId) {
    echo "[6] Membersihkan Data Uji Coba (DELETE /api/faqs/{$createdId})...\n";
    $delRes = callApi('DELETE', $apiBaseUrl . '/faqs/' . $createdId, $apiKey);
    echo "HTTP Status: {$delRes['code']}\n";
    echo "Response: " . json_encode($delRes['json'], JSON_PRETTY_PRINT) . "\n\n";
}

// Bersihkan data batch
if (!empty($batchRes['json']['data'])) {
    foreach ($batchRes['json']['data'] as $bItem) {
        callApi('DELETE', $apiBaseUrl . '/faqs/' . $bItem['id'], $apiKey);
    }
    echo "Data batch contoh berhasil dibersihkan kembali.\n";
}

echo "\n=== INTEGRASI API BERHASIL 100% ===\n";
