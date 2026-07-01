-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 30, 2026 at 12:12 AM
-- Server version: 8.0.46-0ubuntu0.24.04.3
-- PHP Version: 8.3.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dilan_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `info`
--

CREATE TABLE `info` (
  `info_id` int NOT NULL,
  `judul` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `isi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tgl_buat` datetime NOT NULL,
  `tgl_update` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dibuat_oleh` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `diperbarui_oleh` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `jumlah_tayang` int NOT NULL DEFAULT '0',
  `kategori_id` int NOT NULL,
  `kata_kunci` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jenis`
--

CREATE TABLE `jenis` (
  `jenis_id` int NOT NULL,
  `nama_jenis` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `kategori_id` int NOT NULL,
  `nama_kategori` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

CREATE TABLE `media` (
  `media_id` int NOT NULL,
  `nama` varchar(255) NOT NULL,
  `file` text NOT NULL,
  `tipe_media` varchar(32) NOT NULL,
  `ukuran_media` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `operator`
--

CREATE TABLE `operator` (
  `operator_id` int NOT NULL,
  `nip` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `info_id` int NOT NULL,
  `tgl_tulis` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `jenis_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pengguna`
--

CREATE TABLE `pengguna` (
  `pengguna_id` int NOT NULL,
  `nip` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `peran` enum('admin','user') DEFAULT 'user',
  `kategori_id` int NOT NULL,
  `url_apk` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`kategori_id`, `nama_kategori`) VALUES
(1, 'Domain'),
(2, 'Umum'),
(4, 'Website Desa'),
(5, 'Website Kelurahan'),
(6, 'Peduli Pensiun'),
(7, 'Internet'),
(8, 'SPBE (Sistem Pemerintahan Berbasis Elektronik)'),
(9, 'PPID');

-- --------------------------------------------------------

--
-- Dumping data for table `jenis`
--

INSERT INTO `jenis` (`jenis_id`, `nama_jenis`) VALUES
(1, 'Create Document'),
(2, 'Update Document'),
(3, 'Delete Document');

-- --------------------------------------------------------

--
-- Dumping data for table `media`
--

INSERT INTO `media` (`media_id`, `nama`, `file`, `tipe_media`, `ukuran_media`) VALUES
(1, 'sinjai-01.jpg', 'sinjai-01.jpg', '.jpg', '47.54'),
(4, 'Permohonan_Email_1.jpg', 'Permohonan_Email_1.jpg', '.jpg', '150.68'),
(5, 'Permohonan_Email_2.jpg', 'Permohonan_Email_2.jpg', '.jpg', '190.68'),
(6, 'Permohonan_Email_3.jpg', 'Permohonan_Email_3.jpg', '.jpg', '198.04'),
(7, 'Permohonan_Email_4.jpg', 'Permohonan_Email_4.jpg', '.jpg', '203.54'),
(8, 'Permohonan_Email_5.jpg', 'Permohonan_Email_5.jpg', '.jpg', '162.98'),
(9, 'Permohonan_Email_6.jpg', 'Permohonan_Email_6.jpg', '.jpg', '160.76'),
(10, 'Permohonan_Email_7.jpg', 'Permohonan_Email_7.jpg', '.jpg', '200.8'),
(11, 'Permohonan_Email_8.jpg', 'Permohonan_Email_8.jpg', '.jpg', '187.59'),
(12, 'Permohonan_Domain_1.jpg', 'Permohonan_Domain_1.jpg', '.jpg', '124.58'),
(13, 'Permohonan_Domain_2.jpg', 'Permohonan_Domain_2.jpg', '.jpg', '199.67'),
(14, 'Permohonan_Domain_3.jpg', 'Permohonan_Domain_3.jpg', '.jpg', '174.28'),
(15, 'Permohonan_Domain_4.jpg', 'Permohonan_Domain_4.jpg', '.jpg', '143.73'),
(16, 'Permohonan_Domain_5.jpg', 'Permohonan_Domain_5.jpg', '.jpg', '213.25'),
(17, 'Permohonan_Domain_6.jpg', 'Permohonan_Domain_6.jpg', '.jpg', '238.52'),
(18, 'Permohonan_Domain_7.jpg', 'Permohonan_Domain_7.jpg', '.jpg', '200.8'),
(19, 'Permohonan_Domain_8.jpg', 'Permohonan_Domain_8.jpg', '.jpg', '207.72'),
(20, 'Permohonan_Domain_9.jpg', 'Permohonan_Domain_9.jpg', '.jpg', '153.4'),
(21, 'Permohonan_Domain_10.jpg', 'Permohonan_Domain_10.jpg', '.jpg', '184.78'),
(22, 'Permohonan_Domain_11.jpg', 'Permohonan_Domain_11.jpg', '.jpg', '163.19'),
(23, 'Permohonan_Domain_12.jpg', 'Permohonan_Domain_12.jpg', '.jpg', '160.55'),
(24, 'Permohonan_Domain_13.jpg', 'Permohonan_Domain_13.jpg', '.jpg', '243.51'),
(25, 'Permohonan_Domain_14.jpg', 'Permohonan_Domain_14.jpg', '.jpg', '147.07'),
(26, 'Permohonan_Domain_15.jpg', 'Permohonan_Domain_15.jpg', '.jpg', '184.42'),
(27, 'Permohonan_Domain_16.jpg', 'Permohonan_Domain_16.jpg', '.jpg', '175.21'),
(28, 'Permohonan_Domain_17.jpg', 'Permohonan_Domain_17.jpg', '.jpg', '147.09'),
(29, 'Permohonan_Domain_18.jpg', 'Permohonan_Domain_18.jpg', '.jpg', '235.79'),
(30, 'Permohonan_Domain_19.jpg', 'Permohonan_Domain_19.jpg', '.jpg', '122.08'),
(31, 'Permohonan_Domain_20.jpg', 'Permohonan_Domain_20.jpg', '.jpg', '247.7'),
(32, 'Permohonan_Domain_21.jpg', 'Permohonan_Domain_21.jpg', '.jpg', '233.12'),
(33, 'Permohonan_Domain_22.jpg', 'Permohonan_Domain_22.jpg', '.jpg', '253.77'),
(34, 'Permohonan_Domain_23.jpg', 'Permohonan_Domain_23.jpg', '.jpg', '201.1'),
(35, 'Permohonan_Domain_24.jpg', 'Permohonan_Domain_24.jpg', '.jpg', '178.62'),
(36, 'permohonan_website_desa_1.jpg', 'permohonan_website_desa_1.jpg', '.jpg', '186.04'),
(37, 'permohonan_website_desa_2.jpg', 'permohonan_website_desa_2.jpg', '.jpg', '160.65'),
(38, 'permohonan_website_desa_3.jpg', 'permohonan_website_desa_3.jpg', '.jpg', '204.16'),
(39, 'permohonan_website_desa_4.jpg', 'permohonan_website_desa_4.jpg', '.jpg', '211.77'),
(40, 'permohonan_website_desa_5.jpg', 'permohonan_website_desa_5.jpg', '.jpg', '174.41'),
(41, 'WhatsApp_Image_2022-10-05_at_14_08_09.jpeg', 'WhatsApp_Image_2022-10-05_at_14_08_09.jpeg', '.jpeg', '458.54'),
(42, 'surat_pemohonan-domain_desaid.jpg', 'surat_pemohonan-domain_desaid.jpg', '.jpg', '155.93'),
(43, 'surat_penunjukan_admin.jpg', 'surat_penunjukan_admin.jpg', '.jpg', '127.68'),
(44, 'logo_smart_kampung_bb.png', 'logo_smart_kampung_bb.png', '.png', '152.07'),
(45, 'skdesa-1.pdf', 'skdesa-1.pdf', '.pdf', 165),
(46, 'Format_Permohonan_Domain_Desa_ID.pdf', 'Format_Permohonan_Domain_Desa_ID.pdf', '.pdf', '5.72');

-- --------------------------------------------------------

--
-- Dumping data for table `pengguna`
--

INSERT INTO `pengguna` (`pengguna_id`, `nip`, `password`, `peran`, `kategori_id`, `url_apk`) VALUES
(1, '198611052009042003', NULL, 'user', 6, 'http://apps.sinjaikab.go.id/peduli-pensiun/'),
(2, '199910022022031005', NULL, 'user', 9, 'https://kb.sinjaikab.go.id/dilan'),
(3, '199910022022031005', NULL, 'user', 2, 'https://kb.sinjaikab.go.id/dilan'),
(4, '197708262010011003', NULL, 'user', 1, ''),
(5, '197706302009011005', NULL, 'user', 2, ''),
(6, '199210122022031009', NULL, 'user', 4, '');

-- --------------------------------------------------------

--
-- Dumping data for table `info`
--

INSERT INTO `info` (`info_id`, `judul`, `isi`, `tgl_buat`, `tgl_update`, `dibuat_oleh`, `diperbarui_oleh`, `jumlah_tayang`, `kategori_id`, `kata_kunci`) VALUES
(1, 'Apa Itu Nama Domain ?', '<p>Secara lebih teknis, domain adalah nama yang dipilih sebagai identitas web server atau komputer agar kita bisa lebih mudah mengaksesnya.&nbsp;Tanpa nama ini, kita harus mengetikkan serangkaian angka yang disebut IP address di kolom alamat browser setiap kali akan mengunjungi sebuah website.</p>\\r\\n\\r\\n<p>Mau tau lebih lanjut? Dalam artikel ini, kami akan menjelaskan semuanya, termasuk pengertian domain, fungsi, jenis-jenisnya, serta cara daftar dan transfernya. Check it out!</p>\\r\\n\\r\\n<p><strong>Pengertian Domain</strong><br />\\r\\nSingkatnya, nama domain adalah alamat yang perlu Anda gunakan untuk membuka dan mengakses website. Perumpamaannya seperti ini: website yang Anda miliki adalah sebuah rumah. Nah, layanan web hosting adalah tanah tempat Anda mendirikan rumah, sedangkan domain adalah alamat yang bisa digunakan orang-orang untuk menuju ke rumah Anda.</p>\\r\\n\\r\\n<p>Jadi, bisa dibilang bahwa domain adalah salah satu komponen utama website. Nama domain terdiri dari dua elemen utama, yaitu nama situs dan ekstensi. Contohnya, Facebook.com memuat nama situs (Facebook) dan ekstensi (.com).</p>\\r\\n\\r\\n<p>Registrasi nama domain dikelola oleh organisasi bernama ICANN (Internet Corporation for Assigned Names and Numbers). ICANN menentukan ekstensi yang tersedia dan memiliki database terpusat yang berisi informasi pengarahan nama domain.</p>\\r\\n\\r\\n<p><strong>Apa Fungsi Domain?</strong><br />\\r\\nSetiap website sebenarnya diwakili oleh serangkaian angka (alamat IP) yang nantinya digunakan komputer untuk mengambil datanya dari server, karena sistem komputasi bekerja dengan memahami angka-angka.</p>\\r\\n\\r\\n<p>Tentu saja akan sangat merepotkan bagi kita untuk mengingat setiap angka tersebut ketika akan mengakses sebuah situs, apalagi rangkaian angka ini selalu berbeda untuk setiap situs web. Oleh karena itu, nama ini pun tercipta.</p>\\r\\n\\r\\n<p>Sebagai contoh, kami akan menggunakan nama domain dilan.sinjaikab.go.id. Anggap saja alamat IP kami adalah 192.168.1.1. Alamat IP ini mengarah ke sebuah server yang menyimpan data website.</p>\\r\\n\\r\\n<p>Kemudian, komputer menggunakan angka tersebut untuk menuju server dan meminta data website, lalu menyajikannya kepada Anda di browser. Coba bayangkan, sungguh pusing kalau Anda harus mengingat setiap alamat IP website yang ingin dikunjungi.</p>\\r\\n\\r\\n<p>Kesimpulannya, kegunaan domain adalah untuk mempermudah pengunjung mengakses website yang akan mereka buka melalui web browser, cukup dengan mengetikkan alamatnya tanpa harus menghafal IP address website.</p>\\r\\n\\r\\n<p>Cari nama domain juga susah-susah gampang, karena satu rangkaian nama dan ekstensi hanya bisa dimiliki oleh sebuah website.</p>\\r\\n\\r\\n<p>Nah, kalau menggunakan provider hosting misalnya, seperti Hostinger, niagahoster, cloudkilat dan lainya, Anda bisa lebih mudah mencari nama domain bahkan mendapatkan domain gratis misalnya dengan berlangganan hosting minimal setahun. [hmm ... kayak iklan].</p>\\r\\n\\r\\n<p>Domain juga bisa memanfaatkan&nbsp;redirect&nbsp;atau pengalihan yang membantu Anda menentukan apakah pengunjung yang membuka situs Anda akan otomatis diarahkan ke situs web lain.</p>\\r\\n\\r\\n<p>Cara ini sangat berguna untuk campaign dan&nbsp;microsite, atau untuk mengarahkan pengunjung ke halaman landing khusus di situs utama Anda.</p>\\r\\n\\r\\n<p>Opsi pengalihan juga akan membantu menghindari kesalahan penulisan. Misalnya, ketika Anda salah mengetikkan URL Facebook dengan menulis www.fb.com,&nbsp;Anda akan tetap diarahkan ke www.facebook.com berkat opsi ini.</p>\\r\\n\\r\\n<p><strong>Perbedaan Domain dan URL</strong><br />\\r\\nMeskipun mirip, ada beberapa perbedaan domain dan URL (Universal Resource Locator). URL merupakan alamat web lengkap yang bisa mengarahkan pengunjung ke halaman tertentu di situs.&nbsp;Nah, nama domain adalah bagian dari URL.</p>\\r\\n\\r\\n<p>URL terdiri dari protokol, domain, dan path (jalur). Protokol menunjukkan apakah website memiliki sertifikat SSL. URL hanya akan memiliki path kalau mengarahkan pengunjung ke halaman tertentu sebuah website.</p>\\r\\n', '2022-10-04 03:15:19', '2022-10-04 03:29:21', '197708262010011003', '197706302009011005', 95, 1, 'domain'),
(2, 'Apakah itu Email ?', '<p><img alt=\\"Email\\" src=\\"https://3.bp.blogspot.com/-4-_ocRSOpP4/XLnBWVdrMSI/AAAAAAAAB4c/TzXFkBLoj6gA5UFb0NKHjODEJf4fWHzywCLcBGAs/s1600/pengertian-email.jpg\\" style=\\"width:100%\\" /></p>\\r\\n\\r\\n<p>Surat elektronik dalam internet sering juga disebut dengan istilah email (electronic mail). Mulai dari penulisan, pengiriman, penerimaan, hingga membaca email, semuanya dilakukan secara elektronis dan memerlukan jaringan internet.</p>\\r\\n\\r\\n<p>Fungsi email Menurut Eko H. Setianto dan SmitDev Community dalam buku Asyiknya Bertukar Email (2008), fungsi email pada dasarnya sama seperti surat, yakni sebagai media penyampaian pesan. Namun, karena dikirim menggunakan perangkat yang terhubung internet, email memiliki banyak kelebihan dibanding surat biasa. Salah satunya, email hanya membutuhkan waktu singkat untuk pengiriman dan penerimaannya.&nbsp;</p>\\r\\n\\r\\n<p>Berbeda dengan surat yang bisa membutuhkan waktu satu atau beberapa hari. Berikut beberapa keunggulan email:&nbsp;</p>\\r\\n\\r\\n<ol>\\r\\n	<li>Bisa digunakan di mana saja, asal terhubung dengan internet&nbsp;</li>\\r\\n	<li>Bisa dikirim ke beberapa orang sekaligus secara bersamaan&nbsp;</li>\\r\\n	<li>Hanya perlu mengeluarkan uang untuk membeli kuota internet&nbsp;</li>\\r\\n	<li>Dapat dibaca kapan dan di mana saja&nbsp;</li>\\r\\n	<li>Dapat disimpan sebagai arsip atau dikirim kembali ke orang lain.&nbsp;</li>\\r\\n</ol>\\r\\n\\r\\n<p>Dilansir dari buku Business Communication: Konsep dan Praktik Berkomunikasi (2020) karangan Alvian Hardianto dkk, fungsi utama email adalah mengirim dan menerima pesan melalui internet. Berikut beberapa fungsi email lainnya:&nbsp;</p>\\r\\n\\r\\n<ol>\\r\\n	<li>Mendaftar akun media sosial dan beberapa situs lainnya&nbsp;</li>\\r\\n	<li>Sebagai sarana promosi barang atau jasa&nbsp;</li>\\r\\n	<li>Mengirim dan menerima file berupa dokumen, foto, dan video&nbsp;</li>\\r\\n	<li>Pada beberapa merek gadget, email sering digunakan untuk mengaktifkan smartphone&nbsp;</li>\\r\\n	<li>Sebagai sarana berkomunikasi yang cukup efektif dan efisien.</li>\\r\\n</ol>\\r\\n', '2022-10-04 03:19:15', '2022-10-06 07:59:35', '197706302009011005', '199210122022031009', 44, 2, 'email'),
(3, 'Apa itu IP Address atau Alamat IP ?', '<p>IP address adalah salah satu komponen penting dari kegiatan online yang membantu Anda mendapatkan informasi dengan mudah dari internet.</p>\\r\\n\\r\\n<p>Karena dianggap cukup teknis, bahasan tentang IP address memang jarang muncul. Padahal, setiap perangkat yang Anda gunakan memiliki alamat IP tersendiri, baik itu laptop, ponsel, bahkan website.</p>\\r\\n\\r\\n<p><strong>Pengertian IP Address</strong><br />\\r\\nIP address adalah deretan angka yang dimiliki setiap perangkat seperti komputer, ponsel, server website, atau lainnya sebagai sebuah identitas yang unik.</p>\\r\\n\\r\\n<p>Angka-angka pada alamat IP akan berbeda di setiap perangkat dan memungkinkan komunikasi antar perangkat dapat dilakukan dengan baik.</p>\\r\\n\\r\\n<p>Ibarat komunikasi dengan teman lewat telepon, IP address adalah ibarat nomor telepon Anda yang digunakan teman menghubungi Anda, tanpa khawatir salah sambung.</p>\\r\\n\\r\\n<p>Lalu, dalam kegiatan online, bagaimana seseorang bisa mengakses sebuah situs di internet? Website adalah hal yang dijalankan pada server hosting yang juga merupakan perangkat komputer dengan IP address.</p>\\r\\n\\r\\n<p>Lalu, bagaimana seseorang bisa mengakses sebuah situs di internet dengan nomor-nomor tersebut? Perlu Anda ketahui juga bahwa semua situs merupakan kumpulan file dan data yang dijalankan pada server hosting tempat mereka disimpan &mdash; yang juga merupakan perangkat komputer.</p>\\r\\n\\r\\n<p>Oleh karena itu, masing-masing website memiliki IP addressnya sendiri. Contohnya, 74.125.224.72 atau 8.8.8.8 merupakan IP address yang digunakan oleh Google.</p>\\r\\n\\r\\n<p>Nah, itu tadi penjelasan tentang apa itu IP Address. Oh ya, sedikit info tambahan, IP Address adalah salah satu bagian dari website development. Makanya, Anda perlu mengenal seluk beluk IP Address untuk memahami website lebih jauh.</p>\\r\\n\\r\\n<p><strong>Fungsi IP Address</strong></p>\\r\\n\\r\\n<p>Seperti penjelasan di atas, Fungsi IP address adalah memudahkan setiap perangkat yang yang terkoneksi internet bisa berkomunikasi satu sama lain.</p>\\r\\n\\r\\n<p>Selain dianalogikan sebagai nomor telepon, IP address juga bisa diumpamakan sebagai nama orang dan alamat rumah. Mengapa?</p>\\r\\n\\r\\n<p>IP address adalah identitas sebuah komputer dalam jaringan internet. Dengan demikian, pemilik sebuah website dapat mengetahui semua IP address yang mengakses situsnya. Hal tersebut juga berlaku pada jaringan Wi-Fi publik.</p>\\r\\n\\r\\n<p>Selain itu, Fungsi IP address adalah sebagai alamat pengiriman data ke perangkat Anda. Ketika Anda mengakses sebuah situs, sebenarnya ada proses pengunduhan data yang dikirim dari situs tersebut. Proses tersebut dimungkinkan berkat IP address.</p>\\r\\n', '2022-10-04 03:33:43', '2022-10-04 11:33:43', '197706302009011005', '', 32, 2, 'ip address'),
(4, 'Apa itu Website ?', '<p>Website adalah bagian tidak terpisahkan dari perkembangan internet, dan saat ini jumlahnya mencapai 1,9 miliar di seluruh dunia. Bahkan, jumlah tersebut akan terus bertambah karena jenis website juga terus berkembang.</p>\\r\\n\\r\\n<p>Awalnya, website hanyalah untuk tujuan penggunaan pribadi. Namun, saat ini hampir semua perusahaan memilikinya. Sebut saja, Facebook, Apple, BBC News dan lainnya.</p>\\r\\n\\r\\n<p><strong>Apa Itu Website?</strong><br />\\r\\nWebsite adalah kumpulan halaman yang berisi informasi tertentu dan dapat diakses dengan mudah oleh siapapun, kapanpun, dan di manapun melalui internet.</p>\\r\\n\\r\\n<p>Anda bisa mengakses website dengan menuliskan URL di alamat website di browser. Misalnya, ketika Anda mengetikkan URL https://www.sinjaikab.go.id/, maka Anda akan masuk ke website resmi Pemkab Sinjai.</p>\\r\\n\\r\\n<p><img alt=\\"\\" src=\\"http://kb.sinjaikab.go.id/sinjaikab.JPG\\" style=\\"width:100%\\" /></p>\\r\\n\\r\\n<p>&nbsp;</p>\\r\\n\\r\\n<p>Seperti terlihat, di website di atas terdapat kumpulan halaman yang berisi informasi tertentu. Misalnya informasi tentang profil Kabupaten Sinjai, Berita tentang kegiatan pemerintah kabupaten, layanan-layanan publik yang bisa diakses, laporan-laporan transaparansi keuangan dan lain-lain.</p>\\r\\n\\r\\n<p>Nah, agar pengunjung lebih mudah untuk mengakses informasi yang mereka cari, halaman-halaman tersebut dikelompokkan dalam menu yang bisa diakses dari halaman utama.</p>\\r\\n\\r\\n<p>Sebagian besar website sudah menggunakan cara ini untuk lebih meningkatkan pengalaman pengunjung saat mengaksesnya. Lalu, bagaimanakah website pada saat pertama kali diciptakan?</p>\\r\\n\\r\\n<p><strong>Sejarah Website</strong></p>\\r\\n\\r\\n<p>Website pertama di dunia dibuat oleh Tim Berners-Lee pada akhir 1980-an dalam project World Wide Web (W3). Website tersebut resmi diluncurkan secara online pada 6 Agustus 1991 dengan URL&nbsp;<a href=\\"http://info.cern.ch/\\" rel=\\"noreferrer noopener nofollow\\" target=\\"_blank\\">http://info.cern.ch</a>.</p>\\r\\n\\r\\n<p>Nah, berikut tampilan website pertama di dunia. Masih sangat sederhana, bukan?</p>\\r\\n\\r\\n<p>&nbsp;</p>\\r\\n\\r\\n<p><img alt=\\"\\" src=\\"https://niagaspace.sgp1.digitaloceanspaces.com/blog/wp-content/uploads/2021/12/30141717/website-pertama-di-dunia-1024x387.jpg\\" style=\\"width:100%\\" /></p>\\r\\n\\r\\n<p>&nbsp;</p>\\r\\n\\r\\n<p>Coba bandingkan dengan website Sinjaikab di atas. Banyak perbedaan, bukan?</p>\\r\\n\\r\\n<p>Tentu saja. Alasannya, saat itu tujuan dibuatnya website milik Tim adalah memudahkan para peneliti di tempatnya bekerja untuk bertukar informasi. Sehingga, penggunaan website itu sendiri masih terbatas di lingkungan kerjanya saja di CERN.&nbsp;</p>\\r\\n\\r\\n<p>Dan pada 30 April 1993, website mulai dikenalkan kepada masyarakat dan dapat digunakan secara gratis oleh siapapun, baik individu, organisasi, maupun perusahaan. Dari sanalah website berkembang secara pesat hingga saat ini.</p>\\r\\n\\r\\n<p><strong>Unsur-Unsur Website</strong><br />\\r\\nTerdapat lima unsur yang sangat vital pada website. Tanpa adanya unsur ini, website tidak bisa ditemukan maupun diakses oleh pengguna internet. Apa saja unsur yang ada di website?&nbsp;</p>\\r\\n\\r\\n<p><strong>1. Domain</strong><br />\\r\\nAnda mungkin sudah sering mendengarnya, tapi sebetulnya pengertian domain dan perannya di dalam website itu apa sih? Domain adalah alamat sebuah website. Tercatat dalam sejarah domain, mulanya untuk mengunjungi suatu website Anda perlu mengetahui alamat IP atau IP Address yang ditandai deretan angka. Karena alamat IP sangat sulit diingat, maka terciptalah sistem &ldquo;penamaan&rdquo; alamat website. Misalnya, sinjaikab.go.id, youtube.com, google.com, dan lain sebagainya.</p>\\r\\n\\r\\n<p>Nah, Anda bisa menggunakan nama domain yang sesuai dengan maksud atau tujuan website. Misalnya, memakai nama brand bisnis, nama pribadi, atau nama topik dari website tersebut. Nama apapun bisa Anda pilih sebagai domain, asalkan singkat, mudah diingat, dieja, dan ditulis. Tujuannya, agar memudahkan ketika visitor ingin berkunjung ke website Anda.</p>\\r\\n\\r\\n<p><strong>2. Hosting</strong><br />\\r\\nHosting adalah server tempat di mana semua file website Anda disimpan serta dapat diakses dan dikelola melalui internet. Web hosting bisa diibaratkan sebuah rumah dan website adalah seluruh isi rumah tersebut, mulai dari gambar, video, teks, dan lainnya. Hosting merupakan unsur website untuk menyimpan semua data website Nah, agar website Anda dapat berjalan cepat dan aman, maka Anda perlu memilih &ldquo;rumah&rdquo; yang baik alias penyedia hosting yang terpercaya.&nbsp;</p>\\r\\n\\r\\n<p><strong>3. Konten</strong><br />\\r\\nAnda tentu mengunjungi website dengan tujuan tertentu, kan? Bisa untuk mencari sebuah informasi, berbelanja, atau lainnya. Nah, semua informasi itu disediakan dalam bentuk konten website. Dengan adanya konten, pengunjung dapat mengetahui informasi yang ada di sebuah website baik dalam bentuk gambar, video, bahkan teks.</p>\\r\\n\\r\\n<p>Sebagai contoh, website Sinjaikab ditujukan sebagai pusat informasi pemerintah Kabupaten Sinjai dan semua aplikasi-aplikasi yang digunakan untuk pelayanan publik.&nbsp;</p>\\r\\n\\r\\n<p>Maka, konten yang ditampilkan adalah tentang informasi pemerintahan, transparansi dan akuntabilitas pelayanan, hingga semua permalink menuju aplikasi yang digunakan.</p>\\r\\n\\r\\n<p><strong>4. Bahasa Pemrograman (Kode)</strong><br />\\r\\nUnsur website berikutnya adalah bahasa pemrograman. Nah, Tim Berners-Lee awalnya mengembangkan website dengan bahasa pemrograman HTML. Seiring berkembangnya dunia coding, terciptalah beberapa bahasa pemrograman lain. Coding adalah proses menulis kode untuk membangun website.</p>\\r\\n\\r\\n<p>Beberapa bahasa yang bisa di-coding antara lain CSS untuk mengatur tampilan elemen website, JavaScript agar website lebih dinamis serta interaktif, dan lainnya. Ketiga bahasa pemrograman tersebut, mulai membuat banyak website menarik bermunculan dan melahirkan banyak programmer hebat yang mampu membuat website dengan baik.</p>\\r\\n\\r\\n<p>Namun, teknologi website terus berkembang sehingga orang yang tidak memiliki keahlian bahasa pemrograman bisa membuat website dengan CMS atau Content Management System, yaitu software untuk mengatur konten website.&nbsp;</p>\\r\\n\\r\\n<p>Saat ini, pilihan CMS yang bisa Anda gunakan pun kian banyak. WordPress adalah CMS terpopuler saat ini, di mana telah digunakan oleh lebih dari 30 juta website atau setara dengan 60% website di dunia.</p>\\r\\n\\r\\n<p><strong>5. Tampilan&nbsp;</strong><br />\\r\\nInformasi di dalam sebuah website itu penting, tapi tampilan yang baik juga diinginkan oleh pengunjung. Bahkan, 48% pengguna internet menyebutkan bahwa desain website adalah faktor dalam menentukan kredibilitasnya, apalagi untuk website bisnis.&nbsp;</p>\\r\\n\\r\\n<p>Tampilan website bukan hanya sekedar menarik, tetapi juga harus user friendly. Artinya, menarik berkat kombinasi warna yang pas dengan struktur yang rapi sehingga memudahkan akses pengguna di semua perangkat.</p>\\r\\n\\r\\n<p>Untuk membuat tampilan menarik dengan coding, bahasa pemrograman CSS-lah yang digunakan. Namun, kalau menggunakan CMS seperti WordPress, ada ribuan pilihan template siap pakai.&nbsp;</p>\\r\\n\\r\\n<p>Saat ini bahkan telah banyak template yang memudahkan Anda untuk melakukan kustomisasi seperti mengedit font, memilih warna font, men-setting layout, dan lainnya.&nbsp;</p>\\r\\n', '2022-10-04 03:42:11', '2022-10-06 08:00:05', '197706302009011005', '199210122022031009', 51, 1, 'website'),
(5, 'Apa itu CMS ?', '<p>Anda sudah menyimak artikel pengertian <a href=\\"http://kb.sinjaikab.go.id/dilan/dashboard/detail/4\\">website</a> lalu tertarik membuat website tanpa coding. Jika begitu, maka menggunakan CMS adalah solusi yang tepat. Dalam beberapa langkah mudah, website Anda sudah jadi dan bisa diakses online.</p>\\r\\n\\r\\n<p>Eits, fungsi CMS bukan hanya untuk membantu Anda membangun website lho, tapi juga mengelola dan memposting konten dengan praktis.</p>\\r\\n\\r\\n<p><strong>Apa Itu CMS?</strong><br />\\r\\nCMS adalah singkatan dari content management system, yaitu software yang memungkinkan Anda untuk membuat dan mengelola website dengan mudah.</p>\\r\\n\\r\\n<p>Umumnya, sebuah CMS akan memberikan Anda sebuah antarmuka (user interface) di mana Anda bisa mengatur tampilan, fitur dan isi website dengan praktis.&nbsp;</p>\\r\\n\\r\\n<p>Antarmuka ini berisi berbagai menu yang diperlukan untuk mengutak-atik website sesuai dengan yang Anda inginkan. Singkatnya, Anda tidak perlu memiliki keahlian coding untuk mengelola website dengan CMS.&nbsp;</p>\\r\\n\\r\\n<p>Nah, supaya Anda punya gambaran, inilah tampilan halaman Dashboard (menu) WordPress, sebuah CMS yang cukup populer:</p>\\r\\n\\r\\n<p><img alt=\\"\\" src=\\"https://niagaspace.sgp1.digitaloceanspaces.com/blog/wp-content/uploads/2018/04/07083658/dashboard-wordpress-1024x506.png\\" style=\\"height:247px; width:500px\\" /></p>\\r\\n\\r\\n<p>Seperti yang terlihat, menu-menu yang disediakan akan membantu Anda untuk: &nbsp;</p>\\r\\n\\r\\n<ul>\\r\\n	<li>Membuat dan mengelola halaman dan postingan</li>\\r\\n	<li>Mengupload gambar</li>\\r\\n	<li>Mengatur tampilan web</li>\\r\\n	<li>Menambahkan fitur ke website</li>\\r\\n	<li>Mengubah pengaturan CMS</li>\\r\\n	<li>Menambahkan dan mengelola user role (pengguna CMS)</li>\\r\\n	<li>Secara umum, CMS dibagi menjadi dua jenis, yaitu hosted CMS dan self-hosted CMS.</li>\\r\\n</ul>\\r\\n\\r\\n<p>Hosted CMS adalah content management system yang tidak memerlukan instalasi. Sementara itu, self hosted CMS mengharuskan Anda menginstal sendiri CMS ke hosting. Namun, tak perlu khawatir karena penyedia layanan hosting biasanya sudah menyediakan cara install CMS dengan sekali klik. &nbsp;&nbsp;</p>\\r\\n\\r\\n<p>Praktis, ya? Bayangkan kalau semua hal di atas harus Anda lakukan dengan coding sendiri, selain ribet tentu akan memakan banyak waktu, bukan?</p>\\r\\n\\r\\n<p>Nah, daya tarik CMS bukan hanya kemudahan saja, tetapi juga kemampuannya untuk membuat beragam jenis website, seperti:</p>\\r\\n\\r\\n<ul>\\r\\n	<li>Landing page bisnis</li>\\r\\n	<li>Toko online</li>\\r\\n	<li>Website company profile</li>\\r\\n	<li>Blog</li>\\r\\n	<li>Forum</li>\\r\\n	<li>E-learning</li>\\r\\n	<li>Portal berita</li>\\r\\n</ul>\\r\\n\\r\\n<p>Manfaat CMS bisa dilihat dari tautan berikut.</p>\\r\\n', '2022-10-04 04:00:46', '2022-10-04 04:04:13', '197706302009011005', '197706302009011005', 38, 2, 'cms'),
(6, 'Apa Manfaat CMS ?', '<p>Secara umum, manfaat CMS adalah memudahkan Anda untuk membuat dan mengelola berbagai jenis website. Namun, masih ada beberapa manfaat website CMS lainnya. Apa saja itu?</p>\\r\\n\\r\\n<p><strong>1. Membangun Website Tanpa Perlu Coding</strong><br />\\r\\nManfaat CMS yang pertama adalah proses web development bisa dilakukan tanpa coding. Coding adalah proses menulis kode untuk membangun website. Namun dengan CMS, Anda tidak perlu belajar berbagai bahasa pemrograman untuk coding website.</p>\\r\\n\\r\\n<p>Tak hanya itu, Anda juga jadi bisa berhemat karena tidak perlu membayar web developer atau web agency untuk membantu dalam pengelolaan website tersebut.</p>\\r\\n\\r\\n<p>Meski demikian, CMS juga cocok untuk Anda yang punya keahlian koding tapi tidak ingin membuat website dari nol, kok. Sebab, kebanyakan content management system menawarkan kemudahan kustomisasi pengaturan, seperti mengedit tema dan membuat plugin custom.</p>\\r\\n\\r\\n<p><strong>2. Memudahkan Menata Tampilan Website dengan Tema</strong><br />\\r\\nMerancang desain website agar tampak menarik tentu bukan hal yang mudah. Meskipun Anda sudah mempraktikkan berbagai tips desain website sekalipun.</p>\\r\\n\\r\\n<p>Namun, tidak demikian jika Anda menggunakan content management system. CMS menyediakan banyak tema yang bisa Anda pilih untuk mengganti tampilan website secara instan. Beberapa CMS hanya menyediakan sedikit tema bawaan yang bisa digunakan. Akan tetapi, ada juga yang pilihan temanya sampai ribuan.</p>\\r\\n\\r\\n<p>Menariknya, sebagian besar tema CMS mudah dikustomisasi. Misalnya, Anda bisa mengubah background, warna teks, dan urutan menunya dengan beberapa klik saja.</p>\\r\\n\\r\\n<p>Yang tak kalah penting, banyak tema CMS yang bisa digunakan gratis. Namun, Anda juga masih bisa membeli tema berbayar dari marketplace pihak ketiga.&nbsp;</p>\\r\\n\\r\\n<p><strong>3. Menambah Berbagai Fungsi dengan Plugin</strong><br />\\r\\nUmumnya, content management system itu siap pakai. Maksudnya, sudah memiliki fitur yang lengkap sebagai cara membuat website gratis secara sederhana. Jadi, Anda langsung dapat membuat halaman dan menentukan tampilannya.</p>\\r\\n\\r\\n<p>Akan tetapi, ketika ingin menambahkan fungsi khusus untuk website yang lebih kompleks, seperti website bisnis dan kursus online, Anda memerlukan plugin. Nah, plugin adalah software tambahan untuk menambahkan fungsi atau fitur tertentu di website Anda.</p>\\r\\n\\r\\n<p>Untungnya, langkah untuk menginstal plugin CMS itu mudah kok. &nbsp;Umumnya, cukup dengan dua langkah, yaitu instalasi dan aktivasi.</p>\\r\\n\\r\\n<p>Ragam plugin dari sebuah CMS juga banyak, mulai dari plugin untuk keamanan hingga kecepatan. Beberapa plugin juga memudahkan Anda menambahkan elemen tertentu ke website, misalnya formulir kontak, pop-up, dan social share.</p>\\r\\n\\r\\n<p><strong>4. Membuat Website Lebih Aman</strong><br />\\r\\nKalau Anda membuat website dengan cara coding, maka bisa saja terjadi error kalau codingnya tidak benar. Nah, dengan CMS, kendala tersebut tidak terjadi karena setiap kode sudah diuji agar dapat berjalan baik.&nbsp;</p>\\r\\n\\r\\n<p>Bahkan, kalau terjadi error yang membuat website jadi tidak bisa diakses atau mudah diretas, pihak pengembang CMS akan cepat memberikan update. Anda cukup melakukan update melalui menu yang disediakan.&nbsp;</p>\\r\\n\\r\\n<p>Bahkan penyedia layanan hosting murah Indonesia, seperti Niagahoster, menyediakan fitur update otomatis sehingga Anda tidak perlu repot melakukan update sendiri berulang kali.&nbsp;</p>\\r\\n\\r\\n<p>Apabila masih ingin meningkatkan keamanan, Anda tinggal menginstal plugin yang diperlukan. Mudah, ya?</p>\\r\\n\\r\\n<p><strong>5. Memudahkan Pengelolaan Bersama</strong><br />\\r\\nLayaknya sebuah bisnis, pengelolaan website bisa saja dilakukan oleh banyak orang. Di website toko online, misalnya, bisa saja ada yang bertugas mengurus halaman produk, menulis konten blog, dan lain-lain.</p>\\r\\n\\r\\n<p>Kalau hak akses ke website tidak diatur dengan baik, bisa saja terjadi kendala yang mengganggu operasional website. Misalnya, ada yang tanpa sengaja mengutak-atik pengaturan website hingga terjadi error.</p>\\r\\n\\r\\n<p>Mengelola akses website tentu bukan hal yang mudah. Untungnya, content management system menyediakan pengaturan hak akses yang efektif. Saat membuatkan akun untuk anggota tim, Anda tinggal menentukan role atau perannya. Misalnya editor, author, atua contributor.</p>\\r\\n\\r\\n<p>Dengan begitu, masing-masing orang hanya bisa mengakses website sesuai kewenangannya sehingga lebih teratur.&nbsp;</p>\\r\\n\\r\\n<p><strong>6. Membuat Website Ramah Mesin Pencarian</strong><br />\\r\\nTanpa muncul di mesin pencarian, website Anda tidak akan dikenal publik. Hal ini tentunya sebuah kerugian besar, apalagi jika website Anda digunakan untuk berbisnis.</p>\\r\\n\\r\\n<p>Nah, agar bisa muncul di Google dan mesin pencarian lainnya, website Anda harus SEO-friendly, baik secara teknis maupun dalam kaitannya dengan pengalaman pengunjung saat mengaksesnya.&nbsp;</p>\\r\\n\\r\\n<p>Kabar baiknya, hampir semua CMS dirancang agar ramah mesin pencarian. Hal ini bisa dilihat dari kode yang rapi, kemudahan untuk menerapkan search engine optimization (SEO), hingga tersedianya berbagai &nbsp;plugin untuk mendukung SEO.</p>\\r\\n\\r\\n<p><strong>7. Memudahkan Pengelolaan Konten</strong><br />\\r\\nSesuai namanya, salah satu daya tarik CMS adalah manajemen konten yang baik. Hal tersebut diwujudkan dengan adanya kategori dan tag untuk memilah-milah halaman dan postingan di website.</p>\\r\\n\\r\\n<p>Jika membuat website dengan coding, kemudahan manajemen seperti itu tidak Anda dapatkan. Anda jadi harus membuat database halaman dan posting sendiri.</p>\\r\\n\\r\\n<p>Pengelolaan media di CMS juga tidak kalah baik. Ada fitur media library untuk menyimpan dan mengelola gambar-gambar yang Anda upload.</p>\\r\\n\\r\\n<p>Selain itu, ada juga fitur embed untuk menampilkan media dari media sosial atau platform video di website Anda. Dengan demikian, membuat konten multimedia di CMS sangatlah mudah.</p>\\r\\n', '2022-10-04 04:06:17', '2022-10-04 12:06:17', '197706302009011005', '', 29, 1, ''),
(7, 'Permohonan Email go.id', '<p>Berikut ini adalah langkah-langkah dalam mengajukan permohonan email go.id,</p>\\r\\n\\r\\n<p><img alt=\\"\\" src=\\"http://kb.sinjaikab.go.id/dilan/uploads/Permohonan_Email_1.jpg\\" style=\\"width:100%\\" /></p>\\r\\n\\r\\n<p>1. Buka laman https://servicedesk.layanan.go.id, silahkan masuk atau daftar jika belum punya akun&nbsp;<em>https://servicedesk.layanan.go.id/Register/form</em></p>\\r\\n\\r\\n<p><img alt=\\"\\" src=\\"http://kb.sinjaikab.go.id/dilan/uploads/Permohonan_Email_2.jpg\\" style=\\"width:100%\\" /></p>\\r\\n\\r\\n<p>2. Lengkapi form sesuai yang diminta, untuk jenis pengguna silahkan pilih <strong>Publik, </strong>jangan lupa untuk foto selfie dengan KTP</p>\\r\\n\\r\\n<p><img alt=\\"\\" src=\\"http://kb.sinjaikab.go.id/dilan/uploads/Permohonan_Email_3.jpg\\" style=\\"width:100%\\" /></p>\\r\\n\\r\\n<p>3. Setelah melakukan verifikasi email, silahkan login dengan akun yang telah didaftarkan</p>\\r\\n\\r\\n<p><img alt=\\"\\" src=\\"http://kb.sinjaikab.go.id/dilan/uploads/Permohonan_Email_4.jpg\\" style=\\"width:100%\\" /></p>\\r\\n\\r\\n<p>4. Setelah itu masuk ke dalam menu Permintaan Baru, Pilih Layanan Desa kemudian Pilih Akun dan Akses Aplikasi</p>\\r\\n\\r\\n<p><img alt=\\"\\" src=\\"http://kb.sinjaikab.go.id/dilan/uploads/Permohonan_Email_5.jpg\\" style=\\"width:100%\\" /></p>\\r\\n\\r\\n<p>5. Pada form permintaan pengguna, isi Judul dan keterangan sesuai dengan kepentingan yaitu permintaan akun email (go.id). Jangan lupa mengganti urgensi ke tingkat &quot;tinggi&quot; dan melampirkan SK Perangkat Desa</p>\\r\\n\\r\\n<p><img alt=\\"\\" src=\\"http://kb.sinjaikab.go.id/dilan/uploads/Permohonan_Email_6.jpg\\" style=\\"width:100%\\" /></p>\\r\\n\\r\\n<p>6. Pada menu &quot;Permintaan yang sedang berlangsung&quot; dapat dilihat status yang sedang berjalan.&nbsp;</p>\\r\\n\\r\\n<p><img alt=\\"\\" src=\\"http://kb.sinjaikab.go.id/dilan/uploads/Permohonan_Email_7.jpg\\" style=\\"width:100%\\" /></p>\\r\\n\\r\\n<p>7. Jika telah terselesaikan (Berwarna Hijau), Maka akan muncul halaman yang berisi Username dan Password yang dapat digunakan login pada <em>https://mail.go.id/</em></p>\\r\\n\\r\\n<p><img alt=\\"\\" src=\\"http://kb.sinjaikab.go.id/dilan/uploads/Permohonan_Email_8.jpg\\" style=\\"width:100%\\" /></p>\\r\\n\\r\\n<p>&nbsp;</p>\\r\\n\\r\\n<p><em>Proses Waktu penyelesaian : <strong>Maksimal 2 Hari Kerja</strong></em></p>\\r\\n', '2022-10-06 01:55:13', '2022-10-06 07:47:32', '197706302009011005', '199210122022031009', 18, 4, 'email'),
(8, 'Permohonan Domain desa.id', '<p>Berikut ini adalah proses permohonan domain desa.id, gambar presentasi dibuat oleh Dzul, pranata komputer di Dinas Komunikasi Informatika dan Persandian Kabupaten Sinjai.</p>\\r\\n\\r\\n<p><img alt=\\"\\" src=\\"http://kb.sinjaikab.go.id/dilan/uploads/Permohonan_Domain_1.jpg\\" style=\\"width:100%\\" /></p>\\r\\n\\r\\n<p>1. Login pada halaman&nbsp;<em>https://mail.go.id/&nbsp;</em>menggunakan Username dan Passward yang didapatkan dari&nbsp;<em>https://servicedesk.layanan.go.id/</em></p>\\r\\n\\r\\n<p><img alt=\\"\\" src=\\"http://kb.sinjaikab.go.id/dilan/uploads/Permohonan_Domain_2.jpg\\" style=\\"width:100%\\" /></p>\\r\\n\\r\\n<p>2. Buka tab baru, lalu buka laman https://domain.go.id/ dan klik Login</p>\\r\\n\\r\\n<p><img alt=\\"\\" src=\\"http://kb.sinjaikab.go.id/dilan/uploads/Permohonan_Domain_3.jpg\\" style=\\"width:100%\\" /></p>\\r\\n\\r\\n<p>3. Klik<strong> </strong><strong><u>Daftar Disini</u></strong> untuk mulai proses pendaftaran</p>\\r\\n\\r\\n<p><img alt=\\"\\" src=\\"http://kb.sinjaikab.go.id/dilan/uploads/Permohonan_Domain_4.jpg\\" style=\\"width:100%\\" /></p>\\r\\n\\r\\n<p>4. Wajib mengisi semua kolom yang ditandai dengan *). masukkan nama email yang sebelumnya terdaftar pada <em>https://mail.go.id/.&nbsp; </em>Dokumen yang diunggah harus identitas yang valid, SK Perangkat Desa (untuk domain desa.id). Ukuran maksimal file adalah 1024 KB.</p>\\r\\n\\r\\n<p><img alt=\\"\\" src=\\"http://kb.sinjaikab.go.id/dilan/uploads/Permohonan_Domain_5.jpg\\" style=\\"width:100%\\" /></p>\\r\\n\\r\\n<p>5. Cek email aktivasi yang masuk pada laman&nbsp;<em>https://mail.go.id/</em></p>\\r\\n\\r\\n<p><img alt=\\"\\" src=\\"http://kb.sinjaikab.go.id/dilan/uploads/Permohonan_Domain_6.jpg\\" style=\\"width:100%\\" /></p>\\r\\n\\r\\n<p>6. Cek email konfirmasi bahwa akun telah diaktifkan</p>\\r\\n\\r\\n<p><img alt=\\"\\" src=\\"http://kb.sinjaikab.go.id/dilan/uploads/Permohonan_Domain_7.jpg\\" style=\\"width:100%\\" /></p>\\r\\n\\r\\n<p>7. Kembali ke laman <em>https://domain.go.id/&nbsp;</em>dan klik login. Masukkan nama akun dan password yang telah didaftarkan pada langkah sebelumnya</p>\\r\\n\\r\\n<p><img alt=\\"\\" src=\\"http://kb.sinjaikab.go.id/dilan/uploads/Permohonan_Domain_8.jpg\\" style=\\"width:100%\\" /></p>\\r\\n\\r\\n<p>8. Klik <u>Pendaftaran Domain</u></p>\\r\\n\\r\\n<p><img alt=\\"\\" src=\\"http://kb.sinjaikab.go.id/dilan/uploads/Permohonan_Domain_9.jpg\\" style=\\"width:100%\\" /></p>\\r\\n\\r\\n<p>9. Masukkan nama domain (nama Desa) untuk mengecek ketersediaan domain (desa.id)</p>\\r\\n\\r\\n<p><img alt=\\"\\" src=\\"http://kb.sinjaikab.go.id/dilan/uploads/Permohonan_Domain_10.jpg\\" style=\\"width:100%\\" /></p>\\r\\n\\r\\n<p>10. Pilih durasi domain sesuai dengan kebutuhan</p>\\r\\n\\r\\n<p><img alt=\\"\\" src=\\"http://kb.sinjaikab.go.id/dilan/uploads/Permohonan_Domain_11.jpg\\" style=\\"width:100%\\" /></p>\\r\\n\\r\\n<p>11. Cek rangkuman. Apabila telah sesuai maka klik <u>Submit</u></p>\\r\\n\\r\\n<p><img alt=\\"\\" src=\\"http://kb.sinjaikab.go.id/dilan/uploads/Permohonan_Domain_12.jpg\\" style=\\"width:100%\\" /></p>\\r\\n\\r\\n<p>12. Mengunggah dokumen baru untuk pendaftaran. Jenis dokumen yang dilampirkan harus sesuai dengan domain yang diajukan (<em>Identitas/Surat Kuasa)</em></p>\\r\\n\\r\\n<p><img alt=\\"\\" src=\\"http://kb.sinjaikab.go.id/dilan/uploads/Permohonan_Domain_13.jpg\\" style=\\"width:100%\\" /></p>\\r\\n\\r\\n<p>13. Konfirmasi dokumen lalu tekan <u>Submit</u></p>\\r\\n\\r\\n<p><img alt=\\"\\" src=\\"http://kb.sinjaikab.go.id/dilan/uploads/Permohonan_Domain_14.jpg\\" style=\\"width:100%\\" /></p>\\r\\n\\r\\n<p>14. Kembali ke menu <u>List Domain</u>&nbsp;</p>\\r\\n\\r\\n<p><img alt=\\"\\" src=\\"http://kb.sinjaikab.go.id/dilan/uploads/Permohonan_Domain_15.jpg\\" style=\\"width:100%\\" /></p>\\r\\n\\r\\n<p>15. Pilih dokumen yang akan dikirim. Terdapat 4 dokumen seperti pada langkah sebelumnya</p>\\r\\n\\r\\n<p><img alt=\\"\\" src=\\"http://kb.sinjaikab.go.id/dilan/uploads/Permohonan_Domain_16.jpg\\" style=\\"width:100%\\" /></p>\\r\\n\\r\\n<p>16. Konfirmasi Dokumen Domain. Apabilah telah sesuai, klik <u>Submit</u></p>\\r\\n\\r\\n<p><img alt=\\"\\" src=\\"http://kb.sinjaikab.go.id/dilan/uploads/Permohonan_Domain_17.jpg\\" style=\\"width:100%\\" /></p>\\r\\n\\r\\n<p>17. Buka kembali laman&nbsp;https://mail.go.id/. Buka file invoice/petunjuk pembayaran yang terdapat terlampir pada email masuk</p>\\r\\n\\r\\n<p><img alt=\\"\\" src=\\"http://kb.sinjaikab.go.id/dilan/uploads/Permohonan_Domain_18.jpg\\" style=\\"width:100%\\" /></p>\\r\\n\\r\\n<p>18. Lakukan pembayaran sesuai petunjuk yang terdapat pada lembar invoice</p>\\r\\n\\r\\n<p><img alt=\\"\\" src=\\"http://kb.sinjaikab.go.id/dilan/uploads/Permohonan_Domain_19.jpg\\" style=\\"width:100%\\" /></p>\\r\\n\\r\\n<p>19. Pada daftar domain, status berubah menjadi penundaan pembayaran dengan waktu jatuh tempo selama 7 hari. dan status berubah apabila telah terbayarkan</p>\\r\\n\\r\\n<p><img alt=\\"\\" src=\\"http://kb.sinjaikab.go.id/dilan/uploads/Permohonan_Domain_20.jpg\\" style=\\"width:100%\\" /></p>\\r\\n\\r\\n<p>20.&nbsp;Setelah melakukan pembayaran, konfirmasi pembayaran ke email <strong>helpdesk@pandi.id</strong> dengan subject &quot;<strong>Nomor Invoice - Nama Domain</strong>&quot;</p>\\r\\n\\r\\n<p><img alt=\\"\\" src=\\"http://kb.sinjaikab.go.id/dilan/uploads/Permohonan_Domain_21.jpg\\" style=\\"width:100%\\" /></p>\\r\\n\\r\\n<p>21. Menunggu balasan email konfirmasi pembayaran</p>\\r\\n\\r\\n<p><img alt=\\"\\" src=\\"http://kb.sinjaikab.go.id/dilan/uploads/Permohonan_Domain_22.jpg\\" style=\\"width:100%\\" /></p>\\r\\n\\r\\n<p>22. Jika berhasil, maka ada balasan email bahwa domain telah berhasil dan siap untuk digunakan</p>\\r\\n\\r\\n<p><img alt=\\"\\" src=\\"http://kb.sinjaikab.go.id/dilan/uploads/Permohonan_Domain_23.jpg\\" style=\\"width:100%\\" /></p>\\r\\n\\r\\n<p>23. Domain yang telah terdaftar akan menampilkan Tanggal berakhir dan sisa waktu aktif domain</p>\\r\\n\\r\\n<p><img alt=\\"\\" src=\\"http://kb.sinjaikab.go.id/dilan/uploads/Permohonan_Domain_24.jpg\\" style=\\"width:100%\\" /></p>\\r\\n\\r\\n<p>Permohonan biasanya akan menunggu hingga 2 hari sesuai prosedur, jika semua lancar dan persyaratan lengkap maka permohonan domain desa.id akan disetujui lebih cepat.&nbsp;</p>\\r\\n\\r\\n<p>&nbsp;</p>\\r\\n', '2022-10-06 02:21:15', '2022-10-06 07:52:27', '197706302009011005', '199210122022031009', 30, 4, 'domain'),
(9, 'Prosedur Pendaftaran Website Desa', '<p>Prosedur Pendaftaran Website Desa, dibuat oleh Dzul, pranata komputer di Dinas Komunikasi Informatika dan Persandian Kabupaten Sinjai</p>\\r\\n\\r\\n<p><img alt=\\"\\" src=\\"http://kb.sinjaikab.go.id/dilan/uploads/permohonan_website_desa_1.jpg\\" style=\\"width:100%\\" /></p>\\r\\n\\r\\n<p>&nbsp;</p>\\r\\n\\r\\n<p><img alt=\\"\\" src=\\"http://kb.sinjaikab.go.id/dilan/uploads/permohonan_website_desa_2.jpg\\" style=\\"width:100%\\" /></p>\\r\\n\\r\\n<p>&nbsp;</p>\\r\\n\\r\\n<p><img alt=\\"\\" src=\\"http://kb.sinjaikab.go.id/dilan/uploads/permohonan_website_desa_3.jpg\\" style=\\"width:100%\\" /></p>\\r\\n\\r\\n<p>Permohonan Email go.id bisa dilihat pada tautan berikut&nbsp;<a href=\\"http://kb.sinjaikab.go.id/dilan/dashboard/update_count/7/2\\">http://kb.sinjaikab.go.id/dilan/dashboard/update_count/7/2</a></p>\\r\\n\\r\\n<p>&nbsp;</p>\\r\\n\\r\\n<p><img alt=\\"\\" src=\\"http://kb.sinjaikab.go.id/dilan/uploads/permohonan_website_desa_4.jpg\\" style=\\"width:100%\\" /></p>\\r\\n\\r\\n<p>Prosedur pendaftaran domain desa.id bisa dilihat secara lengkap dari tautan berikut ini&nbsp;<a href=\\"http://kb.sinjaikab.go.id/dilan/dashboard/update_count/8/3\\">http://kb.sinjaikab.go.id/dilan/dashboard/update_count/8/3</a></p>\\r\\n\\r\\n<p><img alt=\\"\\" src=\\"http://kb.sinjaikab.go.id/dilan/uploads/permohonan_website_desa_5.jpg\\" style=\\"width:100%\\" /></p>\\r\\n\\r\\n<p>Aktivasi sideka-ng akan dilakukan oleh admin kabupaten. Silahkan menghubungi Dinas Komunikasi Informatika dan Persandian Kabupaten Sinjai.</p>\\r\\n', '2022-10-06 02:47:46', '2022-10-06 07:46:26', '197706302009011005', '199210122022031009', 56, 4, 'website'),
(10, 'Kebutuhan Berkas Persuratan untuk Pendaftaran Email go.id dan Domain desa.id', '<p>Kebutuhan Berkas Persuratan untuk Pendaftaran Email go.id dan Domain desa.id</p>\\r\\n\\r\\n<p>1. SK Perangkat Desa, silahkan scan atau foto dan jadikan PDF&nbsp;</p>\\r\\n\\r\\n<p>2. Surat Permohonan Domain, dengan contoh format berikut :</p>\\r\\n\\r\\n<p><img alt=\\"\\" src=\\"http://kb.sinjaikab.go.id/dilan/uploads/surat_pemohonan-domain_desaid.jpg\\" style=\\"width:100%\\" /></p>\\r\\n\\r\\n<p>3. Surat Penunjukan Admin, minimal adalah kepala seksi di desa dan namanya ada dalam SK Perangkat Desa.&nbsp;</p>\\r\\n\\r\\n<p><img alt=\\"\\" src=\\"http://kb.sinjaikab.go.id/dilan/uploads/surat_penunjukan_admin.jpg\\" style=\\"width:100%\\" /></p>\\r\\n\\r\\n<p>4. Foto selfie dengan KTP khusus untuk yang akan menjadi admin mendaftarkan email go.id</p>\\r\\n\\r\\n<p>Ukuran file masing-masing usahakan kurang dari 1 Mb.</p>\\r\\n\\r\\n<p>Unduh file PDF,<br />\\r\\n1. <a href=\\"https://kb.sinjaikab.go.id/dilan/uploads/Format_Permohonan_Domain_Desa_ID.pdf\\">Surat Permohonan Domain</a><br />\\r\\n2. <a href=\\"https://kb.sinjaikab.go.id/dilan/uploads/skdesa-1.pdf\\">Surat Kuasa</a></p>\\r\\n', '2022-10-06 03:03:37', '2022-11-25 00:46:56', '197706302009011005', '197706302009011005', 51, 4, 'berkas'),
(11, 'Bagaimana Membuat Website Kelurahan ?', '<p><strong>Website Kelurahan</strong></p>\\r\\n\\r\\n<ol>\\r\\n	<li>Untuk website kelurahan, silahkan datang ke Dinas Komunikasi Informatika dan Persandian Kabupaten Sinjai pada jam kerja.&nbsp;</li>\\r\\n	<li>Pihak kelurahan bisa mengirim 2 - 5 anggota untuk mengikuti bimbingan teknis pembuatan website kelurahan, dan membawa laptop/PC sendiri. (kapasitas tempat di Diskominfo bisa sampai 10 orang).</li>\\r\\n	<li>Sub Domain untuk kelurahan sudah disediakan.</li>\\r\\n	<li>CMS yang dipakai adalah OpenSID.</li>\\r\\n	<li>Ada baiknya jika pihak kelurahan menginformasikan minimal sehari sebelumnya agar trainer di Diskominfo Sinjai bisa bersiap sesuai waktu yang disepakati.</li>\\r\\n</ol>\\r\\n', '2022-10-20 06:03:28', '2022-10-20 14:03:28', '197706302009011005', '', 60, 5, 'webkelurahan'),
(12, 'Apa itu pensiun', '<h1><span style=\\"font-size:28px\\"><strong>PENSIUN</strong></span></h1>\\r\\n\\r\\n<div class=\\"content\\">\\r\\n<div class=\\"field field-label-hidden field-name-body field-type-text-with-summary\\">\\r\\n<div class=\\"field-items\\">\\r\\n<div class=\\"even field-item\\">\\r\\n<p><strong>Pengertian</strong><br />\\r\\nPensiun adalah penghasilan yang diterima setiap bulan oleh seorang bekas pegawai yang tidak dapat bekerja lagi, untuk membiayai kehidupan selanjutnya agar tidak terlantar apabila tidak berdaya lagi untuk mencari penghasilan yang lain<br />\\r\\nBerdasarkan UU No.11 Tahun 1969, Pensiun diberikan sebagai jaminan hari tua dan sebagai penghargaan atas jasa-jasa pegawai negeri selama bertahun-tahun bekerja dalam dinas pemerintah.<br />\\r\\nBerdasarkan Undang-undang No.43 Tahun 1999 Pasal 10, Pensiun adalah jaminan hari tua dan sebagai balas jasa terhadap Pegawai Negeri yang telah bertahun-tahun mengabdikan dirinya kepada Negara. Pada pokoknya adalah menjadi kewajiban setiap orang untuk berusaha menjamin hari tuanya, dan untuk ini setiap Pegawai Negeri Sipil wajib menjadi peserta dari suatu badan asuransi sosial yang dibentuk oleh pemerintah. Karena pensiun bukan saja sebagai jaminan hari tua, tetapi juga adalah sebagai balas jasa, maka Pemerintah memberikan sumbangannya kepada Pegawai Negeri.<br />\\r\\n<strong>Latar Belakang Adanya Pensiun</strong></p>\\r\\n\\r\\n<ul>\\r\\n	<li>Karena batas usia pensiun ;</li>\\r\\n	<li>Kemauan Sendiri;</li>\\r\\n	<li>Takdir Misalnya : Sakit, Meninggal dunia;</li>\\r\\n	<li>Rekturisasi/Dinas;</li>\\r\\n	<li>Diberhentikan dengan tidak hormat karena adanya kasus .</li>\\r\\n</ul>\\r\\n\\r\\n<p><strong>Unsur Sifat Pensiun</strong></p>\\r\\n\\r\\n<ol style=\\"list-style-type:decimal\\">\\r\\n	<li>Penghargaan, diberhentikan dengan hormat;</li>\\r\\n	<li>Jaminan hari tua;</li>\\r\\n	<li>Jasa terhadap Negara atau pemerintah.</li>\\r\\n</ol>\\r\\n\\r\\n<p><strong>Hak atas pensiun Pegawai</strong>&nbsp;(Undang &ndash; undang Nomor : 11 Thn.1969 pasal 9)<br />\\r\\nPegawai yang diberhentikan dengan hormat sebagai Pegawai Negeri Sipil berhak menerima pensiun pegawai, jikalau ia pada saat pemberhentiannya sebagai pegawai :</p>\\r\\n\\r\\n<ul>\\r\\n	<li>Telah mencapai usia sekurang-kurangnya 50 Tahun dan mempunyai masa kerja untuk pensiun sekurang-kurangnya 20 Tahun.</li>\\r\\n	<li>Mempunyai masa kerja sekurang-kurangnya 4 Tahun dan oleh badan / pejabat yang ditunjuk oleh departemen kesehatan berdasarkan peraturan tentang pengujian kesehatan pegawai negeri, dinyatakan tidak dapat bekerja lagi dalam jabatan apapun juga karena keadaan jasmani atau rohani yang tidak disebabkan oleh dan karena ia menjalankan kewajiban jabatannya.</li>\\r\\n	<li>Pegawai negeri yang setelah menjalankan suatu tugas Negara tidak dipekerjakan kembali sebagai pegawai negeri, berhak menerima pensiun pegawai apabila ia diberhentikan dengan hormat sebagai pegawai negeri dan pada saat pemberhentiannya sebagai pegawai negeri ia telah mencapai usia sekurang-kurangnya 50 TH dan memiliki masa kerja untuk pensiun sekurang &ndash; kurangnya 10 Tahun.</li>\\r\\n</ul>\\r\\n\\r\\n<p><strong>Jenis Pensiun</strong></p>\\r\\n\\r\\n<ul>\\r\\n	<li>Non Batas Usia Pensiun (Non BUP);</li>\\r\\n	<li>Batas Usia Pensiun (BUP), PNS yang telah mencapai BUP harus diberhentikan dengan hormat sebagai PNS;</li>\\r\\n	<li>Pensiun Janda/Duda;</li>\\r\\n	<li>Pensiun Anak.</li>\\r\\n</ul>\\r\\n\\r\\n<p>Macam-macam BUP ditentukan sebagai berikut</p>\\r\\n\\r\\n<ul>\\r\\n	<li>Usia 56 tahun</li>\\r\\n	<li>Usia 58 tahun</li>\\r\\n	<li>Usia 60 tahun</li>\\r\\n	<li>Usia 63 tahun</li>\\r\\n	<li>Usia 65 tahun</li>\\r\\n	<li>Usia 70 tahun</li>\\r\\n</ul>\\r\\n\\r\\n<p>PNS diberhentikan dengan hormat sebagai PNS karena mencapai BUP, berhak atas pensiun apabila ia telah memiliki masa kerja pensiun sekurang-kurangnya 10 tahun<br />\\r\\nPNS yang akan mencapai BUP dapat dibebaskan dari jabatannya untuk paling lama 1 tahun dengan mendapat penghasilan berdasarkan peraturan perundangan yang berlaku kecuali tunjangan jabatan<br />\\r\\nPNS yang memangku jabatan sebagaimana dimaksud dalam pasal 44 PP No. 32/1979 apabila tidak memangku lagi jabatan tersebut maka sebelum yang bersangkutan diberhentikan sebagai PNS kepada yang bersangkutan diberikan bebas tugas 1 tahun.<br />\\r\\n<strong>Dasar Hukum Pemberian Pensiun PNS dan Janda/Duda</strong></p>\\r\\n\\r\\n<ul>\\r\\n	<li>UU No. 11 tahun 1969, Tentang pensiun pegawai dan pensiun janda/dudanya PNS ;</li>\\r\\n	<li>UU No. 8 Tahun 1974 Jo. UU No. 43 Tahun 1999,Tentang Pokok-pokok kepegawaian ;</li>\\r\\n	<li>PP No. 7 tahun 1977 , PP No.15 tahun 1985, PP No. 15 tahun 1992, PP No. 15 tahun 1993 , PP No. 6 tahun 1997 dan PP No. 10 tahun 2008;</li>\\r\\n	<li>PP No. 32 tahun 1979, Tentang pemberhentian Pegawai Negeri Sipil ;</li>\\r\\n	<li>PP No. 12 tahun 1981, Tentang perawatan tunjangan cacat dan uang duka ;</li>\\r\\n	<li>PP No, 1 tahun 1983, Tentang perlakuan terhadap calaon PNS yang tewas atau cacat akibat kecelakaan karena dinas ;</li>\\r\\n	<li>PP No. 49 tahun 1980,Tentang pemberhentian tunjangan tambahan penghasilan bagi PNS , janda/duda PNS;</li>\\r\\n	<li>PP No. 5 tahun 1987, Tentang perlakuan terhadap penerimaan pensiun/tunjangan yang hilang ;</li>\\r\\n	<li>PP No. 8 tahun 1989, Tentang pemberhentian dan pemberian pensiun otomatis PNS serta pemberian pensiun janda/duda ;</li>\\r\\n	<li>SE Ka. BAKN, No 16/SE/1982, Tentang pemberhentian PNS daerah yang berpangkat Pembina Tk I Golongan ruang IV/b keatas ;</li>\\r\\n	<li>Keputusan Ka. BAKN No. 74/Kep/1989 tentang pemberhentian dan pemberian pensiun PNS daerah serta pemberian pensiun janda/dudanya ;</li>\\r\\n	<li>Kep Ka. BAKN No. 18 tahun 1992 tentang tata cara pemberhentian dan pemberian pensiun PNS yang berpangkat Pembina Tk I golongan ruang IV/b serta pembayarannya;</li>\\r\\n	<li>Kep. Ka BAKN No.19 tahun 1993 tentang penetapan pensiun janda/duda pensiun PNS yang belum ditetapkan berdasarkan PP No. 8 tahun 1989 ;</li>\\r\\n	<li>Kep. Ka. BAKN No. 32 Tahun 1994 tentang pertimbangan teknis pensiun janda/duda pensiun PNS yang berpangkat Pembina Tk I golongan ruang IV/b keatas;</li>\\r\\n	<li>PP nomor 9 tahun 2003 Tentang Wewenang Pengangkatan, Pemindahan dan Pemberhentian Pegawai Negeri Sipil;</li>\\r\\n	<li>Keputusan Kepala BKN Nomor 14 tahun 2003 Tentang Petunjuk Teknis Pemberhentian dan Pemberian Pensiun Pegawai Negeri Sipil serta Pensiun Janda/Duda sebagai Pelaksanaan Peraturan Pemerintah Nomor 9 tahun 2003 Tentang Wewenang Pengangkatan, Pemindahan dan Pemberhentian Pegawai Negeri Sipil;</li>\\r\\n	<li>Peraturan Bupati Kuningan Nomor 7 tahun 2005 Tentang Ketentuan Tata Naskah Dinas Di Lingkungan Pemerintah Kabupaten Kuningan;</li>\\r\\n	<li>Peraturan Pemerintah Nomor 13 Tahun 2007 Tentang Penetapan Pensiun Pokok Pensiunan Pegawai Negeri Sipil dan Janda/Duda;</li>\\r\\n	<li>Peraturan Kepala BKN Nomor 3 tahun 2008 Tentang Petunjuk Teknis Pelaksanaan Peraturan Pemerintahan Nomor 14 tahun 2008 Tentang Penetapan Pensiun Pokok Pensiunan Pegawai Negeri Sipil dan Janda/ Dudanya.</li>\\r\\n</ul>\\r\\n\\r\\n<p><strong>Berakhirnya hak pensiun pegawai&nbsp;</strong>( pasal 14 UU No.11/1969 )<br />\\r\\nHak pensiun pegawai berakhir pada penghabisan bulan penerima pensiun pegawai yang bersangkutan meninggal dunia.<br />\\r\\n<strong>Pembatalan pemberian pensiun pegawai</strong>&nbsp;( pasal 15 UU No. 11/1969 )<br />\\r\\nPembayaran pensiun pegawai dihentikan dan surat keputusan tentang pemberhentian pensiun pegawai dibatalkan, apabila penerima pensiun pegawai diangkat kembali menjadi pegawai negeri atau diangkat kembali dalam suatu jabatan negeri dengan hak untuk kemudian setelah diberhentikan lagi, memperoleh pensiun menurut Undang-undang atau peraturan yang sesuai dengan UU. No.11/1969<br />\\r\\n<strong>Pendaftaran isteri/suami/ anak sebagai yang berhak menerima pensiun janda/duda.</strong></p>\\r\\n\\r\\n<ul>\\r\\n	<li>Pendaftaran isteri( isteri &ndash; isteri) /suami/anak(anak-anak) sebagai yang berhak menerima pensiun janda / duda harus dilakukan oleh pegawai negeri atau penerima pensiun pegawai yang bersangkutan menurut petunjuk kepala Kantor Urusan Pegawai.<br />\\r\\n	Pendaftaran lebih dari seorang isteri sebagai yang berhak menerima pensiun harus dilakukan dengan pengetahuan tiap-tiap isteri didaftarkan.</li>\\r\\n	<li>Pendaftaran isteri ( isteri &ndash; isteri ) / anak ( anak-anak) sebagai yang berhak menerima pensiun janda harus dilakukan dalam waktu 1 ( satu ) tahun sesudah perkawinan/kelahiran atau sesudah saat terjadinya kemungkinan lain untuk melakukan pendaftaran itu.</li>\\r\\n</ul>\\r\\n\\r\\n<p>Persyaratan Pensiun BUP :</p>\\r\\n\\r\\n<ul>\\r\\n	<li>Foto copy Karpeg yang dilegalisir;</li>\\r\\n	<li>Foto copy Karis/Karsu yang dilegalisir;</li>\\r\\n	<li>Surat Pernyataan tidak menyimpan barang miliki Negara;</li>\\r\\n	<li>Salinan Foto copy Surat Nikah yang telah dilegalisir oleh Kepala Kantor Urusan Agama kecamatan setempat;</li>\\r\\n	<li>Daftar susunan keluarga yang disahkan oleh camat setempat;</li>\\r\\n	<li>Foto copy Akte / Surat Kenal Lahir anak dilegalisir BKKBCS setempat;</li>\\r\\n	<li>Daftar perincian gaji terakhir;</li>\\r\\n	<li>Surat Keterangan masa kerja sebelum menjadi PNS;</li>\\r\\n	<li>Foto copy SK CPNS (80%);</li>\\r\\n	<li>Foto copy SK PNS (100%);</li>\\r\\n	<li>Foto copy SK Pangkat terakhir;</li>\\r\\n	<li>Foto copy Surat Keterangan Berkala terakhir;</li>\\r\\n	<li>Foto copy SK Jabatan terakhir;</li>\\r\\n	<li>Daftar Riwayat Pekerjaan;</li>\\r\\n	<li>Surat Pernyataan Tidak Pernah Dijatuhi Hukuman Disiplin Tingkat Sedang/Berat;</li>\\r\\n	<li>DP 3 dua tahun terakhir;</li>\\r\\n	<li>Data Perorangan Calon Penerima Pensiun (DPCP);</li>\\r\\n	<li>Surat Keterangan Kuliah (bagi anak yang masih kuliah);</li>\\r\\n	<li>Foto copy Kartu Tanda Penduduk (KTP);</li>\\r\\n	<li>7 (tujuh) lembar photo terbaru ukuran 4 x 6 cm (tanpa tutup kepala dan kacamata);</li>\\r\\n	<li>Surat Pengantar dari Dinas.</li>\\r\\n</ul>\\r\\n\\r\\n<p><strong>Persyaratan Pensiun Janda / Duda :</strong></p>\\r\\n\\r\\n<ul>\\r\\n	<li>Foto copy Karpeg yang dilegalisir;</li>\\r\\n	<li>Foto copy Karis/Karsu yang dilegalisir;</li>\\r\\n	<li>Surat Pernyataan tidak menyimpan barang miliki Negara;</li>\\r\\n	<li>Salinan Foto copy Surat Nikah yang telah dilegalisir oleh Kepala Kantor Urusan Agama kecamatan setempat;</li>\\r\\n	<li>Daftar susunan keluarga yang disahkan oleh camat setempat;</li>\\r\\n	<li>Foto copy Akte / Surat Kenal Lahir anak dilegalisir BKKBCS setempat;</li>\\r\\n	<li>Daftar perincian gaji terakhir;</li>\\r\\n	<li>Surat Keterangan masa kerja sebelum menjadi PNS;</li>\\r\\n	<li>Foto copy SK CPNS (80%);</li>\\r\\n	<li>Foto copy SK PNS (100%);</li>\\r\\n	<li>Foto copy SK Pangkat terakhir;</li>\\r\\n	<li>Foto copy Surat Keterangan Berkala terakhir;</li>\\r\\n	<li>Foto copy SK Jabatan terakhir;</li>\\r\\n	<li>Daftar Riwayat Pekerjaan;</li>\\r\\n	<li>Surat Keterangan Kuliah (bagi anak yang masih kuliah);</li>\\r\\n	<li>Foto copy Kartu Tanda Penduduk (KTP);</li>\\r\\n	<li>7 (tujuh) lembar photo terbaru ukuran 4 x 6 cm (tanpa tutup kepala dan kacamata);</li>\\r\\n	<li>Surat Keterangan Kematian dari Desa / Kelurahan;</li>\\r\\n	<li>Surat Keterangan Janda / Duda dari Desa / Kelurahan;</li>\\r\\n	<li>Surat Keterangan Ahli Waris dari Desa / Kelurahan;</li>\\r\\n	<li>Surat Pernyataan Tidak Pernah Dijatuhi Hukuman Disiplin Tingkat Sedang/Berat;</li>\\r\\n	<li>DP 3 dua tahun terakhir;</li>\\r\\n	<li>Surat Pengantar dari Dinas.</li>\\r\\n</ul>\\r\\n\\r\\n<p><strong>Pegawai Negeri Sipil yang memangku jabatan usia 58 Tahun :</strong></p>\\r\\n\\r\\n<ol style=\\"list-style-type:decimal\\">\\r\\n	<li>Hakim Mahkamah Pelayaran ( PP No.32 tahun 1979)</li>\\r\\n	<li>Hakim Agama pada pengadilan agama tingkat banding</li>\\r\\n	<li>Hakim Agama pada pengadilan agama</li>\\r\\n	<li>Jaksa yang tidak memangku Jabatan Eselon I, II ( UU No. 5 tahun 1991)</li>\\r\\n	<li>Sekretaris jenderal, inspektur jenderal, direktur jenderal dan kepala Bandan di departemen</li>\\r\\n	<li>Eselon I dalam jabatan structural</li>\\r\\n	<li>Eselon II dalam jabatan structural</li>\\r\\n	<li>Ketua, wakil ketua dan hakim pengadilan negeri</li>\\r\\n	<li>Dokter yang ditugaskan secara penuh pada lembaga kedokteran negeri sesuai dengan profesinya</li>\\r\\n	<li>Pengawas sekolah lanjutan tingkat atas dan pengawas sekolah lanjutan tingkat pertama</li>\\r\\n	<li>Guru yang ditugaskan secara penuh pada sekolah lanjutan tingkat atas dan sekolah lanjutan tingkat pertama</li>\\r\\n	<li>Penilik taman kanak-kanak, penilik sekolah dasar, penilik pendidikan agama</li>\\r\\n	<li>Jaksa yang tidak memangku jabatan Eselon I dan II</li>\\r\\n	<li>Jabatan lain yang ditentukan oleh Presiden</li>\\r\\n</ol>\\r\\n</div>\\r\\n</div>\\r\\n</div>\\r\\n</div>\\r\\n', '2022-11-02 00:56:02', '2022-11-07 03:31:41', '198611052009042003', '198611052009042003', 67, 6, 'Pensiun'),
(14, 'Kapan ASN Mengusulkan Pensiun ?', '<p><span style=\\"font-size:28px\\"><strong>PENGUSULAN PENSIUN ASN</strong></span></p>\\r\\n\\r\\n<p>1. Untuk ASN dengan Pangkat <strong>IV/c Ke Atas</strong>, Maka Pengusulan Pensiun di lakukan <strong>9 Bulan</strong> sebelum TMT Pensiun.</p>\\r\\n\\r\\n<p>2. Untuk ASN dengan Pangkat <strong>IV/b Ke Bawah</strong>, Maka Pengusulan Pensiun dilakukan <strong>6 Bulan</strong> Sebelum TMT Pensiun.</p>\\r\\n', '2022-11-07 03:26:27', '2022-11-07 03:32:59', '198611052009042003', '198611052009042003', 80, 6, 'Pengusulan_Pensiun'),
(15, 'Logo Smart Kampung', '<p><img alt=\\"\\" src=\\"https://kb.sinjaikab.go.id/dilan/uploads/logo_smart_kampung_bb.png\\" style=\\"height:396px; width:400px\\" /></p>\\r\\n\\r\\n<p>Logo Smart Kampung,</p>\\r\\n\\r\\n<ol>\\r\\n	<li>Kuning, bulat, menggambarkan matahari sebagai sumber energi terbesar di alam semesta yang bisa dikonversi menjadi energi listrik untuk mengaktifkan semua peralatan elektronik dengan baik dan ramah lingkungan;</li>\\r\\n	<li>Kuda, yang memberikan informasi bahwa logo ini dari Pemerintah Kabupaten Sinjai</li>\\r\\n	<li>Rumah, menunjukkan bahwa smart kampung ini akan memberikan dampak positif pada setiap penduduk yang tinggal di Kabupaten Sinjai, terutama dalam pelayanan publik;</li>\\r\\n	<li>Tower, semua distribusi informasi memanfaatkan jaringan internet terutama yang disiapkan oleh provider telekomunikasi melalui menara-menara yang mereka bangun hingga pelosok;</li>\\r\\n	<li>Lengkung sinyal, di bawah, memberikan gambaran bahwa smart kampung ini memanfaatkan Teknologi Informasi dan Komunikasi terutama internet. Lengkung itu juga bisa menggambarkan antena VSAT yang bisa menjangkau daerah blankspot dengan koneksi ke satelit;</li>\\r\\n	<li>Pohon, smart kampung ini akan mengoptimalkan potensi-potensi alami dan unggulan di desa;</li>\\r\\n	<li>Warna hijau (kesegaran), kuning (menyenangkan), merah (komunikasi), biru (keamanan, tenang), hitam (ketegasan).</li>\\r\\n	<li>Tulisan smart kampung dan sinjai memperjelas logo tentang logo ini.</li>\\r\\n</ol>\\r\\n', '2022-11-08 12:47:07', '2022-11-08 12:50:26', '197706302009011005', '197706302009011005', 35, 2, 'smartkampung'),
(16, 'Pengecekan Progres pengusulan Pensiun?', '<p>Progres <span style=\\"font-size:28px\\">Pensiun</span></p>\\r\\n\\r\\n<p>Dapat dilihat pada aplikasi peduli Pensiun</p>\\r\\n', '2022-11-10 01:10:51', '2022-11-10 01:16:36', '198611052009042003', '198611052009042003', 52, 6, 'Progres'),
(17, 'Tentang Taspen?', '<p><span style=\\"font-size:28px\\">Link</span> <span style=\\"font-size:28px\\">Taspen</span></p>\\r\\n\\r\\n<p>Dapat dilihat pada link website resmi PT.TASPEN</p>\\r\\n\\r\\n<p>https://www.taspen.co.id/</p>\\r\\n', '2022-11-10 01:12:50', '2022-11-10 01:17:06', '198611052009042003', '198611052009042003', 28, 6, 'Taspen'),
(19, 'Persyaratan Administrasi Penerbitan KTP', '<h3><strong>Persyaratan Penerbitan KTP Elektronik (e-KTP) untuk WNI</strong></h3>\\r\\n\\r\\n<ol>\\r\\n	<li>\\r\\n	<p><strong>Sudah berusia 17 tahun</strong> atau sudah/pernah menikah.</p>\\r\\n	</li>\\r\\n	<li>\\r\\n	<p><strong>Fotokopi Kartu Keluarga (KK)</strong> terbaru.</p>\\r\\n	</li>\\r\\n	<li>\\r\\n	<p><strong>Surat pengantar dari RT/RW dan Kelurahan/Desa</strong> (jika diminta oleh daerah setempat).</p>\\r\\n	</li>\\r\\n	<li>\\r\\n	<p><strong>Hadir langsung</strong> ke Dinas Dukcapil atau tempat pelayanan KTP untuk:</p>\\r\\n\\r\\n	<ul>\\r\\n		<li>\\r\\n		<p>Perekaman biometrik (foto, sidik jari, iris mata, tanda tangan digital).</p>\\r\\n		</li>\\r\\n	</ul>\\r\\n	</li>\\r\\n	<li>\\r\\n	<p><strong>Surat pindah</strong> (jika pemohon pindah domisili dari daerah lain).</p>\\r\\n	</li>\\r\\n	<li>\\r\\n	<p><strong>Akta kelahiran atau ijazah</strong> (sebagai data pendukung jika diperlukan verifikasi).</p>\\r\\n	</li>\\r\\n</ol>\\r\\n', '2025-05-19 02:31:53', '2025-05-19 02:36:43', '199910022022031005', '199910022022031005', 15, 2, 'KTP Adminsitrasi capil'),
(20, 'Website PPID ?', '<p>Klik Untuk Buka Website :<br />\\r\\n<a class=\\"btn btn-outline-primary rounded-pill px-4\\" href=\\"https://ppidkab.sinjaikab.go.id/\\">PPID </a></p>\\r\\n', '2025-05-19 02:47:59', '2025-05-19 02:48:59', '199910022022031005', '199910022022031005', 14, 9, 'PPID'),
(21, 'Bagaimana cara mengajukan permintaan informasi?', '<p>Permintaan dapat diajukan secara:</p>\\r\\n\\r\\n<ul>\\r\\n	<li>\\r\\n	<p>Langsung datang ke kantor PPID.</p>\\r\\n	</li>\\r\\n	<li>\\r\\n	<p>Melalui surat.</p>\\r\\n	</li>\\r\\n	<li>\\r\\n	<p>Melalui email resmi PPID.</p>\\r\\n	</li>\\r\\n	<li>\\r\\n	<p>Melalui website atau aplikasi layanan informasi publik&nbsp;</p>\\r\\n	</li>\\r\\n</ul>\\r\\n', '2025-05-19 07:42:02', '2025-05-19 15:42:02', '199910022022031005', '', 12, 9, 'PPID Permintaan Informasi'),
(22, 'Apa saja alasan informasi bisa ditolak atau dikecualikan?', '<p>Informasi dapat dikecualikan jika menyangkut:</p>\\r\\n\\r\\n<ul>\\r\\n	<li>\\r\\n	<p>Rahasia negara.</p>\\r\\n	</li>\\r\\n	<li>\\r\\n	<p>Keamanan dan pertahanan negara.</p>\\r\\n	</li>\\r\\n	<li>\\r\\n	<p>Kepentingan perlindungan pribadi.</p>\\r\\n	</li>\\r\\n	<li>\\r\\n	<p>Rahasia dagang atau hak kekayaan intelektual.</p>\\r\\n	</li>\\r\\n</ul>\\r\\n', '2025-05-19 07:44:34', '2025-05-19 15:44:34', '199910022022031005', '', 12, 9, 'PPID Informasi Dikecualikan'),
(23, 'Jenis informasi apa saja yang dapat diminta melalui PPID?', '<ul>\\r\\n\\r\\n	<li>Informasi yang wajib disediakan dan diumumkan secara berkala :\\r\\n		<a href=\\"https://ppidkab.sinjaikab.go.id/ppid/upload/index/1\\">Informasi Berkala</a>\\r\\n	</li>\\r\\n	<li>Informasi yang diumumkan secara serta-merta :\\r\\n		<a href=\\"https://ppidkab.sinjaikab.go.id/ppid/upload/index/2\\">Informasi Serta Merta</a>\\r\\n	</li>\\r\\n	<li>Informasi yang tersedia setiap saat :\\r\\n		<a href=\\"https://ppidkab.sinjaikab.go.id/ppid/upload/index/4\\">Informasi Setiap saat.</a>\\r\\n	</li>\\r\\n	<li>Informasi yang dikecualikan (dengan pengecualian tertentu sesuai ketentuan hukum). :\\r\\n		<a href=\\"https://ppidkab.sinjaikab.go.id/ppid/upload/index/3\\">Informasi Dikecualikan.</a>\\r\\n	</li>\\r\\n</ul>', '2025-05-19 07:53:10', '2025-05-19 15:53:10', '199910022022031005', '', 15, 9, 'PPID Jenis Informasi'),
(24, 'Apa yang bisa dilakukan jika permintaan informasi ditolak atau tidak ditanggapi?', '<ul>\\r\\n	<li>\\r\\n	<p>Mengajukan keberatan ke atasan PPID.</p>\\r\\n	</li>\\r\\n	<li>\\r\\n	<p>Jika belum puas, mengajukan sengketa informasi ke Komisi Informasi.</p>\\r\\n	</li>\\r\\n</ul>\\r\\n', '2025-05-19 07:58:02', '2025-05-19 15:58:02', '199910022022031005', '', 12, 9, 'PPID Informasi Dikecualikan');

-- --------------------------------------------------------

--
-- Dumping data for table `operator`
--

INSERT INTO `operator` (`operator_id`, `nip`, `info_id`, `tgl_tulis`, `jenis_id`) VALUES
(1, '197708262010011003', 1, '2022-10-04 03:15:19', 1),
(2, '197706302009011005', 2, '2022-10-04 03:19:15', 1),
(3, '197706302009011005', 2, '2022-10-04 03:21:53', 2),
(4, '197706302009011005', 2, '2022-10-04 03:22:45', 2),
(5, '197706302009011005', 1, '2022-10-04 03:26:48', 2),
(6, '197706302009011005', 1, '2022-10-04 03:29:21', 2),
(7, '197706302009011005', 3, '2022-10-04 03:33:43', 1),
(8, '197706302009011005', 4, '2022-10-04 03:42:11', 1),
(9, '197706302009011005', 4, '2022-10-04 03:55:05', 2),
(10, '197706302009011005', 4, '2022-10-04 03:57:38', 2),
(11, '197706302009011005', 4, '2022-10-04 03:57:57', 2),
(12, '197706302009011005', 5, '2022-10-04 04:00:46', 1),
(13, '197706302009011005', 5, '2022-10-04 04:01:10', 2),
(14, '197706302009011005', 5, '2022-10-04 04:04:13', 2),
(15, '197706302009011005', 6, '2022-10-04 04:06:17', 1),
(16, '197706302009011005', 4, '2022-10-06 00:34:03', 2),
(17, '197706302009011005', 7, '2022-10-06 01:55:13', 1),
(18, '197706302009011005', 7, '2022-10-06 01:57:49', 2),
(19, '197706302009011005', 7, '2022-10-06 02:03:15', 2),
(20, '197706302009011005', 8, '2022-10-06 02:21:15', 1),
(21, '197706302009011005', 8, '2022-10-06 02:26:53', 2),
(22, '197706302009011005', 9, '2022-10-06 02:47:46', 1),
(23, '197706302009011005', 9, '2022-10-06 02:51:07', 2),
(24, '197706302009011005', 9, '2022-10-06 02:53:31', 2),
(25, '197706302009011005', 10, '2022-10-06 03:03:37', 1),
(26, '197706302009011005', 10, '2022-10-06 03:06:44', 2),
(27, '197706302009011005', 7, '2022-10-06 03:35:21', 2),
(28, '197706302009011005', 7, '2022-10-06 03:36:02', 2),
(29, '197706302009011005', 7, '2022-10-06 03:36:29', 2),
(30, '197706302009011005', 8, '2022-10-06 03:58:08', 2),
(31, '197706302009011005', 8, '2022-10-06 06:18:39', 2),
(32, '199210122022031009', 8, '2022-10-06 06:54:33', 2),
(33, '199210122022031009', 9, '2022-10-06 07:44:31', 2),
(34, '199210122022031009', 10, '2022-10-06 07:45:39', 2),
(35, '199210122022031009', 9, '2022-10-06 07:46:26', 2),
(36, '199210122022031009', 7, '2022-10-06 07:47:32', 2),
(37, '199210122022031009', 8, '2022-10-06 07:52:27', 2),
(38, '199210122022031009', 2, '2022-10-06 07:59:35', 2),
(39, '199210122022031009', 4, '2022-10-06 08:00:05', 2),
(40, '197706302009011005', 11, '2022-10-20 06:03:28', 1),
(41, '198611052009042003', 12, '2022-11-02 00:56:02', 1),
(42, '198611052009042003', 12, '2022-11-02 00:57:08', 2),
(43, '198611052009042003', 12, '2022-11-02 02:00:06', 2),
(46, '198611052009042003', 14, '2022-11-07 03:26:27', 1),
(47, '198611052009042003', 14, '2022-11-07 03:28:29', 2),
(48, '198611052009042003', 14, '2022-11-07 03:31:01', 2),
(49, '198611052009042003', 12, '2022-11-07 03:31:41', 2),
(50, '198611052009042003', 14, '2022-11-07 03:32:26', 2),
(51, '198611052009042003', 14, '2022-11-07 03:32:59', 2),
(52, '197706302009011005', 15, '2022-11-08 12:47:07', 1),
(53, '197706302009011005', 15, '2022-11-08 12:50:26', 2),
(54, '198611052009042003', 16, '2022-11-10 01:10:51', 1),
(55, '198611052009042003', 17, '2022-11-10 01:12:50', 1),
(56, '198611052009042003', 16, '2022-11-10 01:16:36', 2),
(57, '198611052009042003', 17, '2022-11-10 01:17:06', 2),
(58, '197706302009011005', 10, '2022-11-25 00:46:17', 2),
(59, '197706302009011005', 10, '2022-11-25 00:46:56', 2),
(61, '199910022022031005', 19, '2025-05-19 02:31:53', 1),
(62, '199910022022031005', 19, '2025-05-19 02:36:43', 2),
(63, '199910022022031005', 19, '2025-05-19 02:36:43', 2),
(64, '199910022022031005', 20, '2025-05-19 02:47:59', 1),
(65, '199910022022031005', 20, '2025-05-19 02:48:59', 2),
(66, '199910022022031005', 21, '2025-05-19 07:42:02', 1),
(67, '199910022022031005', 22, '2025-05-19 07:44:35', 1),
(68, '199910022022031005', 23, '2025-05-19 07:53:11', 1),
(69, '199910022022031005', 24, '2025-05-19 07:58:02', 1);

-- --------------------------------------------------------

-- Indexes for dumped tables
--

--
-- Indexes for table `info`
--
ALTER TABLE `info`
  ADD PRIMARY KEY (`info_id`),
  ADD KEY `kategori_id` (`kategori_id`);

--
-- Indexes for table `jenis`
--
ALTER TABLE `jenis`
  ADD PRIMARY KEY (`jenis_id`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`kategori_id`);

--
-- Indexes for table `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`media_id`);

--
-- Indexes for table `operator`
--
ALTER TABLE `operator`
  ADD PRIMARY KEY (`operator_id`),
  ADD KEY `nip` (`nip`),
  ADD KEY `info_id` (`info_id`),
  ADD KEY `jenis_id` (`jenis_id`);

--
-- Indexes for table `pengguna`
--
ALTER TABLE `pengguna`
  ADD PRIMARY KEY (`pengguna_id`),
  ADD UNIQUE KEY `nip_kategori` (`nip`,`kategori_id`),
  ADD KEY `kategori_id` (`kategori_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `info`
--
ALTER TABLE `info`
  MODIFY `info_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jenis`
--
ALTER TABLE `jenis`
  MODIFY `jenis_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `kategori_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `media`
--
ALTER TABLE `media`
  MODIFY `media_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `operator`
--
ALTER TABLE `operator`
  MODIFY `operator_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pengguna`
--
ALTER TABLE `pengguna`
  MODIFY `pengguna_id` int NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `info`
--
ALTER TABLE `info`
  ADD CONSTRAINT `fk_info_kategori` FOREIGN KEY (`kategori_id`) REFERENCES `kategori` (`kategori_id`) ON DELETE CASCADE;

--
-- Constraints for table `operator`
--
ALTER TABLE `operator`
  ADD CONSTRAINT `fk_operator_info` FOREIGN KEY (`info_id`) REFERENCES `info` (`info_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_operator_jenis` FOREIGN KEY (`jenis_id`) REFERENCES `jenis` (`jenis_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_operator_pengguna` FOREIGN KEY (`nip`) REFERENCES `pengguna` (`nip`) ON DELETE CASCADE;

--
-- Constraints for table `pengguna`
--
ALTER TABLE `pengguna`
  ADD CONSTRAINT `fk_pengguna_kategori` FOREIGN KEY (`kategori_id`) REFERENCES `kategori` (`kategori_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
