-- ============================================================
-- FILE    : api_enikda.sql
-- PROJECT : DILAN AR - Diskominfo Sinjai
-- TANGGAL : 2026-09-28
-- DESKRIPSI: Query ALTER TABLE untuk perubahan struktur database
--            terbaru yang mendukung fitur REST API pengiriman data
--            dari aplikasi eksternal (e-NIKDA dan aplikasi OPD lainnya).
-- ============================================================
-- CARA PENGGUNAAN:
--   mysql -u root -p dilan_db < api_enikda.sql
-- ATAU jalankan langsung di phpMyAdmin / MySQL Workbench.
-- ============================================================

SET FOREIGN_KEY_CHECKS = 0;

-- ============================================================
-- 1. TABEL: kategori
--    PERUBAHAN: Tambah kolom `kode_kategori`
--    FUNGSI   : Kode unik berbasis slug untuk identifikasi
--               kategori via REST API tanpa perlu tahu ID numerik.
--               Contoh: 'pkm-sinjai', 'dispusip', 'peduli-pensiun'
-- ============================================================

DROP PROCEDURE IF EXISTS `sp_alter_kategori`;
DELIMITER $$
CREATE PROCEDURE `sp_alter_kategori`()
BEGIN
    -- Tambah kolom kode_kategori jika belum ada
    IF NOT EXISTS (
        SELECT 1 FROM information_schema.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME   = 'kategori'
          AND COLUMN_NAME  = 'kode_kategori'
    ) THEN
        ALTER TABLE `kategori`
            ADD COLUMN `kode_kategori` VARCHAR(64)
                CHARACTER SET utf8mb4
                COLLATE utf8mb4_general_ci
                NULL
                DEFAULT NULL
                COMMENT 'Kode unik slug kategori untuk endpoint API, contoh: pkm-sinjai'
            AFTER `nama_kategori`;
        SELECT 'kolom kode_kategori berhasil ditambahkan' AS status;
    ELSE
        SELECT 'kolom kode_kategori sudah ada, dilewati' AS status;
    END IF;

    -- Tambah UNIQUE INDEX jika belum ada
    IF NOT EXISTS (
        SELECT 1 FROM information_schema.STATISTICS
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME   = 'kategori'
          AND INDEX_NAME   = 'idx_kode_kategori'
    ) THEN
        ALTER TABLE `kategori`
            ADD UNIQUE KEY `idx_kode_kategori` (`kode_kategori`);
        SELECT 'index idx_kode_kategori berhasil dibuat' AS status;
    ELSE
        SELECT 'index idx_kode_kategori sudah ada, dilewati' AS status;
    END IF;
END$$
DELIMITER ;
CALL `sp_alter_kategori`();
DROP PROCEDURE IF EXISTS `sp_alter_kategori`;


-- ============================================================
-- 2. TABEL: pengguna
--    PERUBAHAN: Tambah kolom `api_key`
--    FUNGSI   : API Key unik per akun OPD untuk autentikasi
--               request ke endpoint REST API (header X-API-KEY
--               atau Bearer token).
-- ============================================================

DROP PROCEDURE IF EXISTS `sp_alter_pengguna`;
DELIMITER $$
CREATE PROCEDURE `sp_alter_pengguna`()
BEGIN
    -- Tambah kolom api_key jika belum ada
    IF NOT EXISTS (
        SELECT 1 FROM information_schema.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME   = 'pengguna'
          AND COLUMN_NAME  = 'api_key'
    ) THEN
        ALTER TABLE `pengguna`
            ADD COLUMN `api_key` VARCHAR(64)
                NULL
                DEFAULT NULL
                COMMENT 'API Key unik per akun OPD untuk autentikasi X-API-KEY'
            AFTER `url_apk`;
        SELECT 'kolom api_key berhasil ditambahkan' AS status;
    ELSE
        SELECT 'kolom api_key sudah ada, dilewati' AS status;
    END IF;

    -- Tambah UNIQUE INDEX jika belum ada
    IF NOT EXISTS (
        SELECT 1 FROM information_schema.STATISTICS
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME   = 'pengguna'
          AND INDEX_NAME   = 'idx_api_key'
    ) THEN
        ALTER TABLE `pengguna`
            ADD UNIQUE KEY `idx_api_key` (`api_key`);
        SELECT 'index idx_api_key berhasil dibuat' AS status;
    ELSE
        SELECT 'index idx_api_key sudah ada, dilewati' AS status;
    END IF;
END$$
DELIMITER ;
CALL `sp_alter_pengguna`();
DROP PROCEDURE IF EXISTS `sp_alter_pengguna`;


-- ============================================================
-- 3. GENERATE API KEY untuk pengguna yang belum punya
--    (MD5 dari kombinasi nip + kategori_id + UUID random)
--    Query ini aman dijalankan berulang kali —
--    hanya mengisi baris yang api_key-nya masih NULL.
-- ============================================================

UPDATE `pengguna`
SET `api_key` = MD5(CONCAT(`nip`, '-', `kategori_id`, '-', UUID()))
WHERE `api_key` IS NULL;

SELECT CONCAT('API Key di-generate untuk ', ROW_COUNT(), ' akun') AS status;


-- ============================================================
-- 4. VERIFIKASI HASIL PERUBAHAN
-- ============================================================

SELECT '=== STRUKTUR TABEL kategori ===' AS '';
DESCRIBE `kategori`;

SELECT '=== STRUKTUR TABEL pengguna ===' AS '';
DESCRIBE `pengguna`;

SELECT '=== DAFTAR kode_kategori ===' AS '';
SELECT
    `kategori_id`,
    `nama_kategori`,
    IFNULL(`kode_kategori`, '-- belum diisi --') AS `kode_kategori`
FROM `kategori`
ORDER BY `kategori_id`;

SELECT '=== DAFTAR API KEY per pengguna ===' AS '';
SELECT
    p.`pengguna_id`,
    p.`nip`,
    k.`nama_kategori`,
    IFNULL(p.`api_key`, '-- belum ada --') AS `api_key`
FROM `pengguna` p
LEFT JOIN `kategori` k ON k.`kategori_id` = p.`kategori_id`
ORDER BY p.`pengguna_id`;

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================
-- END OF FILE api_enikda.sql
-- ============================================================
