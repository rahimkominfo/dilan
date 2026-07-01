SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- Drop old tables if they exist
DROP TABLE IF EXISTS `tb_info`;
DROP TABLE IF EXISTS `tb_jenis`;
DROP TABLE IF EXISTS `tb_kategori`;
DROP TABLE IF EXISTS `tb_media`;
DROP TABLE IF EXISTS `tb_operator`;
DROP TABLE IF EXISTS `tb_user`;

-- Drop new tables if they exist to recreate
DROP TABLE IF EXISTS `info`;
DROP TABLE IF EXISTS `jenis`;
DROP TABLE IF EXISTS `kategori`;
DROP TABLE IF EXISTS `media`;
DROP TABLE IF EXISTS `operator`;
DROP TABLE IF EXISTS `pengguna`;

-- --------------------------------------------------------
-- Table structure for table `info`
-- --------------------------------------------------------

CREATE TABLE `info` (
  `info_id` int NOT NULL AUTO_INCREMENT,
  `judul` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `isi` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tgl_buat` datetime NOT NULL,
  `tgl_update` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dibuat_oleh` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `diperbarui_oleh` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `jumlah_tayang` int NOT NULL DEFAULT 0,
  `kategori_id` int NOT NULL,
  `kata_kunci` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`info_id`),
  KEY `kategori_id` (`kategori_id`),
  CONSTRAINT `fk_info_kategori` FOREIGN KEY (`kategori_id`) REFERENCES `kategori` (`kategori_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Table structure for table `jenis`
-- --------------------------------------------------------

CREATE TABLE `jenis` (
  `jenis_id` int NOT NULL AUTO_INCREMENT,
  `nama_jenis` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`jenis_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Table structure for table `kategori`
-- --------------------------------------------------------

CREATE TABLE `kategori` (
  `kategori_id` int NOT NULL AUTO_INCREMENT,
  `nama_kategori` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`kategori_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Table structure for table `media`
-- --------------------------------------------------------

CREATE TABLE `media` (
  `media_id` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) NOT NULL,
  `file` text NOT NULL,
  `tipe_media` varchar(32) NOT NULL,
  `ukuran_media` varchar(32) NOT NULL,
  PRIMARY KEY (`media_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------
-- Table structure for table `operator`
-- --------------------------------------------------------

CREATE TABLE `operator` (
  `operator_id` int NOT NULL AUTO_INCREMENT,
  `nip` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `info_id` int NOT NULL,
  `tgl_tulis` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `jenis_id` int NOT NULL,
  PRIMARY KEY (`operator_id`),
  KEY `nip` (`nip`),
  KEY `info_id` (`info_id`),
  KEY `jenis_id` (`jenis_id`),
  CONSTRAINT `fk_operator_pengguna` FOREIGN KEY (`nip`) REFERENCES `pengguna` (`nip`) ON DELETE CASCADE,
  CONSTRAINT `fk_operator_info` FOREIGN KEY (`info_id`) REFERENCES `info` (`info_id`) ON DELETE CASCADE,
  CONSTRAINT `fk_operator_jenis` FOREIGN KEY (`jenis_id`) REFERENCES `jenis` (`jenis_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Table structure for table `pengguna`
-- --------------------------------------------------------

CREATE TABLE `pengguna` (
  `pengguna_id` int NOT NULL AUTO_INCREMENT,
  `nip` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `peran` enum('admin','user') DEFAULT 'user',
  `kategori_id` int NOT NULL,
  `url_apk` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`pengguna_id`),
  UNIQUE KEY `nip_kategori` (`nip`,`kategori_id`),
  KEY `kategori_id` (`kategori_id`),
  CONSTRAINT `fk_pengguna_kategori` FOREIGN KEY (`kategori_id`) REFERENCES `kategori` (`kategori_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Insert mock data so the app has something to show
INSERT INTO `kategori` (`kategori_id`, `nama_kategori`) VALUES
(1, 'Administrasi Kepegawaian'),
(2, 'Pelayanan Publik');

INSERT INTO `jenis` (`jenis_id`, `nama_jenis`) VALUES
(1, 'CREATE'),
(2, 'UPDATE'),
(3, 'DELETE');

INSERT INTO `info` (`info_id`, `judul`, `isi`, `tgl_buat`, `dibuat_oleh`, `diperbarui_oleh`, `jumlah_tayang`, `kategori_id`, `kata_kunci`) VALUES
(1, 'Panduan Cuti', '<p>Ini adalah panduan cuti.</p>', NOW(), 'admin', 'admin', 10, 1, 'cuti, libur'),
(2, 'Layanan KTP', '<p>Syarat membuat KTP baru.</p>', NOW(), 'admin', 'admin', 5, 2, 'ktp, layanan');

INSERT INTO `pengguna` (`pengguna_id`, `nip`, `password`, `peran`, `kategori_id`, `url_apk`) VALUES
(1, '123456789', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 1, 'http://example.com'); -- password is 'password'

SET FOREIGN_KEY_CHECKS = 1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
