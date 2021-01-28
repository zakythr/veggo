-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: db
-- Generation Time: May 10, 2020 at 01:28 PM
-- Server version: 8.0.19
-- PHP Version: 7.4.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `veggo_dev_v2`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`%`@`%` PROCEDURE `sp_get_barang_by_kategori` (IN `nama_sub_kategori` VARCHAR(255))  NO SQL
BEGIN

SELECT k.sub_kategori,b.* FROM kategoris k, barangs_groups bg, barangs b
WHERE bg.id_kategori = k.id AND b.id = bg.id_barang AND k.sub_kategori = nama_sub_kategori;

END$$

CREATE DEFINER=`%`@`%` PROCEDURE `sp_get_buyer_list` (IN `kode` VARCHAR(100), IN `date` DATE)  NO SQL
BEGIN

SELECT b.kode,u.name FROM transaksis t
LEFT JOIN users u ON u.id = t.id_user
LEFT JOIN detail_transaksis dt ON dt.id_transaksi = t.id
LEFT JOIN barangs b ON b.id = dt.id_barang
WHERE t.tanggal_pre_order = date AND t.status = 1 AND b.kode = kode;

END$$

CREATE DEFINER=`%`@`%` PROCEDURE `sp_get_isi_paket` (IN `id_barang_parent` VARCHAR(255))  NO SQL
select barangs.nama, barangs.satuan, isi_pakets.volume 
from barangs
inner join isi_pakets
on barangs.id = isi_pakets.id_barang
where isi_pakets.id_barang_parent = id_barang_parent$$

CREATE DEFINER=`%`@`%` PROCEDURE `sp_get_kurir_detail_barang` (IN `id_transaksi` VARCHAR(255))  NO SQL
select barangs.nama, detail_transaksis.volume_kirim_kurir, detail_transaksis.harga_akhir, barangs.jenis, barangs.id as barang_id, detail_transaksis.id as detail_transaksi_id
from detail_transaksis
inner join barangs
	on detail_transaksis.id_barang = barangs.id
inner join transaksis
	on detail_transaksis.id_transaksi = transaksis.id
where detail_transaksis.id_transaksi = id_transaksi$$

CREATE DEFINER=`%`@`%` PROCEDURE `sp_get_paket_akan_dikirim` (IN `id_kurir` VARCHAR(256))  NO SQL
select transaksis.nomor_invoice, users.name, alamats.alamat, alamats.blok_nomor, alamats.daerah, alamats.kotkab, alamats.kodepos, users.nomor_hp, transaksis.tanggal_pengiriman, alamats.lat, alamats.long, transaksis.id as transaksi_id
from transaksis
inner join users
	on users.id = transaksis.id_user
inner join alamats
	on alamats.id = transaksis.id_alamat
where transaksis.status = '5' and transaksis.id_kurir = id_kurir$$

CREATE DEFINER=`%`@`%` PROCEDURE `sp_get_paket_akan_dikirim_by_id_transaksi` (IN `id_transaksi` VARCHAR(255))  NO SQL
select transaksis.nomor_invoice, users.name, alamats.alamat, alamats.blok_nomor, alamats.daerah, alamats.kotkab, alamats.kodepos, users.nomor_hp, transaksis.tanggal_pengiriman, alamats.lat, alamats.long, transaksis.keterangan, transaksis.id as transaksi_id, transaksis.total_bayar_akhir, transaksis.isAlreadyPay
from transaksis
inner join users
	on users.id = transaksis.id_user
inner join alamats
	on alamats.id = transaksis.id_alamat
where transaksis.id = id_transaksi$$

CREATE DEFINER=`%`@`%` PROCEDURE `sp_get_paket_sedang_dikirim` (IN `id_kurir` VARCHAR(255))  NO SQL
BEGIN 

select transaksis.nomor_invoice, users.name, alamats.alamat, alamats.blok_nomor, alamats.daerah, alamats.kotkab, alamats.kodepos, users.nomor_hp, transaksis.tanggal_pengiriman, alamats.lat, alamats.long, transaksis.id as transaksi_id
from transaksis
inner join users
	on users.id = transaksis.id_user
inner join alamats
	on alamats.id = transaksis.id_alamat
where transaksis.status = '6' and transaksis.id_kurir = id_kurir;

END$$

CREATE DEFINER=`mail`@`%` PROCEDURE `sp_get_total_non_paket` (IN `date` DATE)  NO SQL
BEGIN

SELECT b.kode AS kode_barang,b.id_user AS supplier_barang,b.nama AS nama_barang, SUM(IFNULL(dt.bobot_kemasan,1) * dt.volume) AS total FROM transaksis t
INNER JOIN detail_transaksis dt ON dt.id_transaksi = t.id
INNER JOIN barangs b ON b.id = dt.id_barang
WHERE t.tanggal_pre_order = date AND t.status = 1 AND b.is_paket = 0 AND t.tipe_transaksi = 'FROM_BUYER' AND dt.status = 1 AND dt.is_canceled_by_veggo = 0 AND dt.is_exclude_rekap = 0
GROUP BY kode_barang,supplier_barang,nama_barang;

END$$

CREATE DEFINER=`mail`@`%` PROCEDURE `sp_get_total_paket` (IN `date` DATE)  NO SQL
BEGIN

SELECT (SELECT b2.kode FROM barangs b2 WHERE b2.id = ip.id_barang) AS kode_barang,(SELECT b2.id_user FROM barangs b2 WHERE b2.id = ip.id_barang) AS supplier_barang,(SELECT b2.nama FROM barangs b2 WHERE b2.id = ip.id_barang) AS nama_barang, SUM(dt.volume*ip.volume) AS total FROM barangs b
LEFT JOIN isi_pakets ip ON ip.id_barang_parent = b.id
LEFT JOIN detail_transaksis dt ON dt.id_barang = b.id
LEFT JOIN transaksis t ON t.id = dt.id_transaksi
WHERE t.tanggal_pre_order = date AND t.status = 1 AND b.is_paket = 1 AND t.tipe_transaksi = 'FROM_BUYER' AND dt.status = 1 AND dt.is_canceled_by_veggo = 0 AND dt.is_exclude_rekap = 0
GROUP BY kode_barang,supplier_barang,nama_barang;


END$$

CREATE DEFINER=`mail`@`%` PROCEDURE `sp_get_total_pre_order` (IN `date` DATE)  NO SQL
BEGIN

SELECT b.kode,b.nama,b.jenis,dt.bobot_kemasan,b.satuan, SUM(dt.volume) AS volume FROM transaksis t
INNER JOIN detail_transaksis dt ON dt.id_transaksi = t.id
INNER JOIN barangs b ON b.id = dt.id_barang
WHERE t.tanggal_pre_order = date AND t.status = 1 AND dt.status = 1 AND t.tipe_transaksi = 'FROM_BUYER' AND dt.is_canceled_by_veggo = 0 AND dt.is_exclude_rekap = 0
GROUP BY b.kode,b.nama,b.jenis,dt.bobot_kemasan,b.satuan;

END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `alamats`
--

CREATE TABLE `alamats` (
  `id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_user` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `kotkab` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `daerah` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `kodepos` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `long` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `lat` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `blok_nomor` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `alamats`
--

INSERT INTO `alamats` (`id`, `id_user`, `kotkab`, `daerah`, `kodepos`, `long`, `lat`, `created_at`, `updated_at`, `blok_nomor`, `alamat`) VALUES
('319fc730-121b-11ea-96c5-b9c32ac498fe', 'edfc818a-88df-457a-9f43-529f9ff4a5e3', 'Surabaya', 'Mulyorejo', '60111', '-7.2631179', '112.799172344754', '2019-11-28 20:10:59', '2019-11-28 20:10:59', 'No. 66', 'Mulyosari Prima'),
('49a3ae70-1219-11ea-b39f-ef87334ef039', 'edfc818a-88df-457a-9f43-529f9ff4a5e3', 'Surabaya', 'Sukolilo', '60111', '-7.2911441', '112.8020775', '2019-11-28 19:57:20', '2019-11-28 19:57:20', 'III A/56', 'Bumi Marina Emas');

-- --------------------------------------------------------

--
-- Table structure for table `barangs`
--

CREATE TABLE `barangs` (
  `id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_user` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_kategori` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `satuan` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `bobot` int NOT NULL,
  `harga_beli` int NOT NULL,
  `harga_jual` int NOT NULL,
  `deskripsi` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `diskon` int DEFAULT NULL,
  `jenis_diskon` int DEFAULT NULL,
  `show_etalase` int DEFAULT NULL,
  `is_paket` int DEFAULT '0',
  `ketersediaan` int DEFAULT NULL,
  `stok` int DEFAULT NULL,
  `bobot_minimum_timbang` int DEFAULT NULL,
  `bobot_kemasan_kemas` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `barangs`
--

INSERT INTO `barangs` (`id`, `id_user`, `id_kategori`, `nama`, `kode`, `jenis`, `satuan`, `bobot`, `harga_beli`, `harga_jual`, `deskripsi`, `diskon`, `jenis_diskon`, `show_etalase`, `is_paket`, `ketersediaan`, `stok`, `bobot_minimum_timbang`, `bobot_kemasan_kemas`, `created_at`, `updated_at`) VALUES
('3f0b6322-a9b4-466b-9add-218434c37db1', '3dceb0cc-9983-470e-9e2b-67facc51175d', '8', 'Plastik Kemasan', 'B4248', 'Timbang', 'Pcs', 1000, 450000, 1000000, 'plastik bungkus', NULL, NULL, 0, 0, NULL, NULL, 1, NULL, '2020-01-12 07:52:49', '2020-01-12 07:52:49'),
('6a9b2890-f902-413a-9e5d-4e5d1e5974ce', '3', '2', 'Lemon Bandung Edited 2', 'B4580', 'Timbang', 'Gram', 1000, 5000, 8000, 'Lemon Bandung Enak', NULL, NULL, 1, 0, 3, 0, 100, NULL, '2019-11-26 11:56:18', '2019-12-30 21:41:26'),
('a012a046-5b4f-4bc1-a6a5-0dd3c343a20b', '3', '1', 'Bayam', 'B5805', 'Kemas', 'Gram', 1000, 25000, 30000, 'Bayam Enak', NULL, NULL, 0, 0, 1, 1000, NULL, NULL, '2019-12-30 21:33:41', '2020-02-11 09:01:29'),
('a63b0578-c0e5-4bc7-96c2-2b4a95a62d1f', '3dceb0cc-9983-470e-9e2b-67facc51175d', '8', 'Paket Apel Brokoli', 'BP198', 'Paket', 'Pcs', 1, 400, 1800, '-', 0, 0, 1, 1, 3, 0, NULL, NULL, '2019-11-27 13:42:25', '2019-11-28 04:43:46'),
('aaaaaaa-sdsadadasd', '52539219-07c9-4383-800c-66a9dfa50157', '1', 'Paket Salad', 'P3123', 'Paket', 'Pcs', 1, 2250, 24000, 'Paket Salad', NULL, NULL, 1, 1, 0, 0, NULL, NULL, '2019-11-26 04:35:52', '2020-02-04 09:37:22'),
('b30a85c8-b74d-4be5-a8cb-281cdb514a70', '3', '2', 'Apel Sunda Edited', 'B8233', 'Kemas', 'Gram', 1000, 10000, 12000, 'Apel Enak', NULL, NULL, 1, 0, 2, 0, NULL, NULL, '2019-11-26 12:20:58', '2019-11-28 04:43:47'),
('c50d52df-fd7d-442e-80f5-47b7ddd225f3', '3', '1', 'Kembang Kol', 'B5384', 'Kemas', 'Gram', 1000, 80000, 85000, 'Kembang Kol Enak', NULL, NULL, 0, 0, NULL, 0, NULL, NULL, '2020-01-01 06:46:31', '2020-01-01 06:46:31'),
('cc039ae0-5a1e-4328-8f9c-da8b91ef15ed', '52539219-07c9-4383-800c-66a9dfa50157', '2', 'Apel Malang', 'B1650', 'Kemas', 'Gram', 1000, 11000, 12000, 'Apel Dari Malang Enak', NULL, NULL, 0, 0, 1, 0, NULL, NULL, '2019-11-20 04:35:09', '2020-02-04 09:37:34'),
('d6f04460-80f9-4c73-9d9f-8ecd1cb3111a', '52539219-07c9-4383-800c-66a9dfa50157', '3', 'Brokoli Timbang Enak', 'B6741', 'Timbang', 'Gram', 1000, 4000, 8000, 'Brokoli', NULL, NULL, 1, 0, 2, 0, NULL, NULL, '2019-11-20 04:35:52', '2020-02-04 09:37:35');

-- --------------------------------------------------------

--
-- Table structure for table `barangs_groups`
--

CREATE TABLE `barangs_groups` (
  `id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_barang` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_kategori` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `barangs_groups`
--

INSERT INTO `barangs_groups` (`id`, `id_barang`, `id_kategori`, `created_at`, `updated_at`) VALUES
('3327a2a5-ab60-4d7c-b8ad-eaaeb1ad5ae4', 'a63b0578-c0e5-4bc7-96c2-2b4a95a62d1f', 'c983e870-0fb6-11ea-8455-d1e54b19ac1f', '2019-11-28 04:42:19', '2019-11-28 04:42:19'),
('390e0d2e-3217-44c1-9723-c5be18309c62', '3f0b6322-a9b4-466b-9add-218434c37db1', 'cb51c120-0fb6-11ea-a496-7b68c9d2baf3', '2020-01-12 07:52:49', '2020-01-12 07:52:49'),
('45f05275-3cb9-4c4a-8fa8-9db846944506', 'c50d52df-fd7d-442e-80f5-47b7ddd225f3', 'bf1ecbdd-4bd8-46ab-8438-ef762e0df46c', '2020-01-01 06:46:31', '2020-01-01 06:46:31'),
('5c9f3618-d9fd-40aa-b4fb-733ebaaf5c3e', 'a012a046-5b4f-4bc1-a6a5-0dd3c343a20b', 'a163070b-d262-48ee-bf6e-a87033fc5c06', '2019-12-30 21:33:43', '2019-12-30 21:33:43'),
('69566d56-ea9a-49b4-9cbb-d9492dce8495', 'a63b0578-c0e5-4bc7-96c2-2b4a95a62d1f', 'cafa6cf0-0fb6-11ea-a598-511b9a31292e', '2019-11-28 04:42:19', '2019-11-28 04:42:19'),
('9f29f1cb-cbe6-4224-ae82-e96db909f659', 'b30a85c8-b74d-4be5-a8cb-281cdb514a70', 'cafa6cf0-0fb6-11ea-a598-511b9a31292e', '2019-11-26 17:47:57', '2019-11-26 17:47:57'),
('a63b0578-c0e5-4bc7-96c2-2b4a95a62d1f', 'a63b0578-c0e5-4bc7-96c2-2b4a95a62d1f', 'a63b0578-c0e5-4bc7-96c2-2b4a95a62d1f', NULL, NULL),
('b3978ebd-8fed-4f4b-8456-c9d056f5cd7e', 'd6f04460-80f9-4c73-9d9f-8ecd1cb3111a', 'cac437b0-0fb6-11ea-826e-bdc71f9a2f70', '2019-12-22 08:24:23', '2019-12-22 08:24:23'),
('ced17dcd-f85e-46b7-8af7-1b88ec2fd441', 'cc039ae0-5a1e-4328-8f9c-da8b91ef15ed', 'db5fa7e8-6623-4f58-9fa0-b23a8f5b579f', '2019-11-20 05:09:00', '2019-11-20 05:09:00'),
('efcc338b-3900-43b2-a3e1-61a1c2c13203', 'aaaaaaa-sdsadadasd', 'cac437b0-0fb6-11ea-826e-bdc71f9a2f70', '2019-12-23 15:45:18', '2019-12-23 15:45:18'),
('fb600080-ae22-4620-a059-eec4a1d9d08d', '6a9b2890-f902-413a-9e5d-4e5d1e5974ce', 'cb0290b0-0fb6-11ea-a47b-7160f4acc51a', '2019-12-30 21:41:27', '2019-12-30 21:41:27');

-- --------------------------------------------------------

--
-- Table structure for table `barangs_kemasans`
--

CREATE TABLE `barangs_kemasans` (
  `id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_barang` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `bobot_kemasan` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `barangs_kemasans`
--

INSERT INTO `barangs_kemasans` (`id`, `id_barang`, `bobot_kemasan`, `created_at`, `updated_at`) VALUES
('42c2444c-2627-4d45-87c7-6ead12d9a7e0', 'c50d52df-fd7d-442e-80f5-47b7ddd225f3', '100', '2020-01-01 06:46:31', '2020-01-01 06:46:31'),
('457e368a-04ea-4ed1-a2ee-ed3f0d48c5b2', 'cc039ae0-5a1e-4328-8f9c-da8b91ef15ed', '400', '2019-11-20 05:09:00', '2019-11-20 05:09:00'),
('545b5485-0976-4119-96a5-773b018567db', 'c50d52df-fd7d-442e-80f5-47b7ddd225f3', '300', '2020-01-01 06:46:31', '2020-01-01 06:46:31'),
('60e646d6-c6e7-4594-bd00-79ba7896ded1', 'a012a046-5b4f-4bc1-a6a5-0dd3c343a20b', '400', '2019-12-30 21:33:42', '2019-12-30 21:33:42'),
('7b688c6b-6010-4a51-b154-108f7593ee81', 'd6f04460-80f9-4c73-9d9f-8ecd1cb3111a', '100', '2019-12-22 08:24:23', '2019-12-22 08:24:23'),
('7e7af68c-0c0b-4a11-a621-b37df4f06d30', 'b30a85c8-b74d-4be5-a8cb-281cdb514a70', '400', '2019-11-26 17:47:56', '2019-11-26 17:47:56'),
('8731906c-44b8-41ce-8dc5-f99bf1831c8f', 'c50d52df-fd7d-442e-80f5-47b7ddd225f3', '200', '2020-01-01 06:46:31', '2020-01-01 06:46:31'),
('8c740ea6-03d5-4e9c-938c-2bf55d7f8461', 'cc039ae0-5a1e-4328-8f9c-da8b91ef15ed', '100', '2019-11-20 05:09:00', '2019-11-20 05:09:00'),
('bbf44a91-068b-4ea0-949a-fdffac62c44a', 'a012a046-5b4f-4bc1-a6a5-0dd3c343a20b', '100', '2019-12-30 21:33:42', '2019-12-30 21:33:42'),
('dsfdsfdsfdsf', 'b30a85c8-b74d-4be5-a8cb-281cdb514a70', '300', NULL, NULL),
('e6dbad93-9851-4076-99e4-d162eaa02dfb', '6a9b2890-f902-413a-9e5d-4e5d1e5974ce', '100', '2019-12-30 21:41:27', '2019-12-30 21:41:27'),
('eeb8f025-1213-4e97-9467-f2ebc0f16907', '6a9b2890-f902-413a-9e5d-4e5d1e5974ce', '250', '2019-12-30 21:41:27', '2019-12-30 21:41:27');

-- --------------------------------------------------------

--
-- Table structure for table `base_kategoris`
--

CREATE TABLE `base_kategoris` (
  `id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `kategori` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `base_kategoris`
--

INSERT INTO `base_kategoris` (`id`, `kategori`, `created_at`, `updated_at`) VALUES
('1', 'Sayur', NULL, NULL),
('2', 'Buah', NULL, NULL),
('3', 'Makanan Sehat', NULL, NULL),
('4', 'Minuman Sehat', NULL, NULL),
('5', 'Beras', NULL, NULL),
('6', 'Bahan Olahan', NULL, NULL),
('7', 'Berkebun', NULL, NULL),
('8', 'Lain - Lain', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `bobot_kemasans`
--

CREATE TABLE `bobot_kemasans` (
  `id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `bobot_kemasan` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bobot_kemasans`
--

INSERT INTO `bobot_kemasans` (`id`, `bobot_kemasan`, `created_at`, `updated_at`) VALUES
('1', 100, NULL, NULL),
('2', 200, NULL, NULL),
('3', 250, NULL, NULL),
('4', 300, NULL, NULL),
('5', 400, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `detail_keranjangs`
--

CREATE TABLE `detail_keranjangs` (
  `id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_keranjang` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_barang` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `volume` int NOT NULL,
  `harga` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `bobot_kemasan` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `detail_klaims`
--

CREATE TABLE `detail_klaims` (
  `id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_klaim` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_barang` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `volume_klaim` int NOT NULL,
  `keterangan` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `detail_transaksis`
--

CREATE TABLE `detail_transaksis` (
  `id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_barang` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `harga` int NOT NULL,
  `status` int NOT NULL DEFAULT '0',
  `is_info_petani` tinyint(1) NOT NULL DEFAULT '0',
  `is_canceled_by_veggo` tinyint(1) NOT NULL DEFAULT '0',
  `bobot_kemasan` int DEFAULT '1',
  `volume` int NOT NULL,
  `volume_selisih` int DEFAULT NULL,
  `volume_kirim_petani` int DEFAULT NULL,
  `volume_terima` int DEFAULT NULL,
  `keterangan` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `id_transaksi` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `volume_kirim_kurir` int DEFAULT NULL,
  `harga_akhir` int DEFAULT NULL,
  `is_exclude_rekap` tinyint DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `detail_transaksis`
--

INSERT INTO `detail_transaksis` (`id`, `id_barang`, `harga`, `status`, `is_info_petani`, `is_canceled_by_veggo`, `bobot_kemasan`, `volume`, `volume_selisih`, `volume_kirim_petani`, `volume_terima`, `keterangan`, `created_at`, `updated_at`, `id_transaksi`, `volume_kirim_kurir`, `harga_akhir`, `is_exclude_rekap`) VALUES
('03c34560-321c-11ea-82a3-dfabe498207c', 'a012a046-5b4f-4bc1-a6a5-0dd3c343a20b', 12000, 4, 0, 0, 400, 1, NULL, NULL, NULL, NULL, '2020-01-08 20:37:29', '2020-01-09 13:56:21', 'f2338ba0-321b-11ea-8e50-39df0de80079', 1, 12000, NULL),
('0428f5f0-321c-11ea-be52-e1178f3d7a45', 'd6f04460-80f9-4c73-9d9f-8ecd1cb3111a', 4000, 4, 0, 0, 1, 500, NULL, NULL, NULL, NULL, '2020-01-08 20:37:29', '2020-01-09 13:56:21', 'f2338ba0-321b-11ea-8e50-39df0de80079', 500, 4000, NULL),
('457816c0-34d6-11ea-a8f7-a12a3f42bd77', 'a012a046-5b4f-4bc1-a6a5-0dd3c343a20b', 60000, 4, 0, 0, 400, 5, NULL, NULL, NULL, NULL, '2020-01-12 07:55:48', '2020-01-12 08:00:04', '3df78f40-34d6-11ea-94f2-3790dd78c4e3', 5, 60000, NULL),
('4aff3070-4758-11ea-96eb-fb43fa4dc791', 'a63b0578-c0e5-4bc7-96c2-2b4a95a62d1f', 1800, 0, 0, 0, NULL, 1, NULL, NULL, NULL, NULL, '2020-02-04 21:11:52', '2020-02-04 21:11:52', 'ae0c94c0-4405-11ea-b5fe-0ddb1a23072b', 3, 1800, NULL),
('4b0c5670-4758-11ea-bcf2-9ff53043d2de', '6a9b2890-f902-413a-9e5d-4e5d1e5974ce', 4000, 0, 0, 0, NULL, 500, NULL, NULL, NULL, NULL, '2020-02-04 21:11:53', '2020-02-04 21:11:53', 'ae0c94c0-4405-11ea-b5fe-0ddb1a23072b', 300, 1800, NULL),
('4b3d03d0-4758-11ea-9284-5fb672d537a5', 'b30a85c8-b74d-4be5-a8cb-281cdb514a70', 38400, 0, 0, 0, 400, 8, NULL, NULL, NULL, NULL, '2020-02-04 21:11:53', '2020-02-04 21:11:53', 'ae0c94c0-4405-11ea-b5fe-0ddb1a23072b', 400, 1800, NULL),
('5d562480-4ad3-11ea-9b6a-9d14b3d5c03f', 'cc039ae0-5a1e-4328-8f9c-da8b91ef15ed', 10800, 1, 0, 0, 100, 9, NULL, NULL, NULL, NULL, '2020-02-09 07:30:25', '2020-02-09 18:04:39', 'ea49dba0-47dc-11ea-a990-9924b0debc00', NULL, NULL, 0),
('90bdec50-34d6-11ea-9577-37a415b582f9', 'a012a046-5b4f-4bc1-a6a5-0dd3c343a20b', 50000, 3, 0, 0, 0, 2000, 0, 2000, 2000, 'ok', '2020-01-12 07:57:54', '2020-01-12 07:59:19', '90b3d740-34d6-11ea-9d9e-615cb68a1061', NULL, NULL, NULL),
('d7e5d1b0-4af5-11ea-839a-1de54bc8db45', 'a012a046-5b4f-4bc1-a6a5-0dd3c343a20b', 30000, 5, 0, 0, 250, 4, NULL, NULL, NULL, NULL, '2020-02-09 11:37:13', '2020-02-11 09:01:29', 'd31a0530-4af5-11ea-9287-7714e4c83551', 4, 30000, 0),
('f72d0860-4b2b-11ea-84dd-91eddc6088ca', 'a012a046-5b4f-4bc1-a6a5-0dd3c343a20b', 25000, 3, 0, 0, 0, 1000, 0, 1000, 1000, NULL, '2020-02-09 18:04:39', '2020-02-11 08:25:41', 'f71108a0-4b2b-11ea-b7da-b9c019f9f158', NULL, NULL, 0),
('fbee6bc0-32ab-11ea-9014-61616c1243e3', 'a012a046-5b4f-4bc1-a6a5-0dd3c343a20b', 10000, 3, 0, 0, 0, 400, 0, 400, 400, NULL, '2020-01-09 13:48:03', '2020-01-09 13:52:22', 'fbe25760-32ab-11ea-9ab6-51e2881456be', NULL, NULL, NULL),
('fc038a70-32ab-11ea-bb1b-49b0cadcf462', 'd6f04460-80f9-4c73-9d9f-8ecd1cb3111a', 2000, 3, 0, 0, 0, 500, 0, 500, 500, NULL, '2020-01-09 13:48:03', '2020-01-09 13:51:04', 'fbf902d0-32ab-11ea-a381-31bbfa0eb5d2', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `foto_barangs`
--

CREATE TABLE `foto_barangs` (
  `id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_barang` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `path` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `foto_barangs`
--

INSERT INTO `foto_barangs` (`id`, `id_barang`, `path`, `created_at`, `updated_at`) VALUES
('2347bc31-0199-422d-a4ad-c192e35ec055', 'b30a85c8-b74d-4be5-a8cb-281cdb514a70', '1574790477Spacetoon.png', '2019-11-26 17:47:57', '2019-11-26 17:47:57'),
('2538588d-9b5c-48bb-b159-71987bb8bf44', 'd6f04460-80f9-4c73-9d9f-8ecd1cb3111a', '1574224554grid.png', '2019-11-20 04:35:54', '2019-11-20 04:35:54'),
('3473b578-80bd-48cb-873c-0b157c1108f1', '3f0b6322-a9b4-466b-9add-218434c37db1', '1578790369spongebob.png', '2020-01-12 07:52:49', '2020-01-12 07:52:49'),
('3583fdd4-d28b-4dfd-b1eb-6b18597cf2f7', 'cc039ae0-5a1e-4328-8f9c-da8b91ef15ed', '1574224510TC16 LIGHT.png', '2019-11-20 04:35:11', '2019-11-20 04:35:11'),
('398fe662-92c6-4491-acba-9b6d75b180ef', 'a012a046-5b4f-4bc1-a6a5-0dd3c343a20b', '1577716423time-lapse-cars-on-fast-motion-134643.jpg', '2019-12-30 21:33:43', '2019-12-30 21:33:43'),
('4a39daa1-da35-46c2-bd8c-1f2b8935f278', '6a9b2890-f902-413a-9e5d-4e5d1e5974ce', '1574769380Untitled Diagram.png', '2019-11-26 11:56:20', '2019-11-26 11:56:20'),
('4b45694d-a1e2-4def-998a-40644416fc3c', 'b30a85c8-b74d-4be5-a8cb-281cdb514a70', '1574790478womanyellingcat.jpg', '2019-11-26 17:47:58', '2019-11-26 17:47:58'),
('b1cd2ae0-4926-4714-855e-53e9e22907d6', 'a63b0578-c0e5-4bc7-96c2-2b4a95a62d1f', '1574862146diana.jpg', '2019-11-27 13:42:26', '2019-11-27 13:42:26'),
('b422c4fb-ed7a-40f0-9162-660eb72ac7a4', '6a9b2890-f902-413a-9e5d-4e5d1e5974ce', '1574769380Untitled Diagram (1).png', '2019-11-26 11:56:20', '2019-11-26 11:56:20'),
('b748db90-35ac-41b4-929a-3289791c0180', 'c50d52df-fd7d-442e-80f5-47b7ddd225f3', '1577835991ekoherwantoro-mAxA2OmTmKA-unsplash.jpg', '2020-01-01 06:46:31', '2020-01-01 06:46:31');

-- --------------------------------------------------------

--
-- Table structure for table `hari_pengiriman`
--

CREATE TABLE `hari_pengiriman` (
  `id` bigint UNSIGNED NOT NULL,
  `nama_hari` varchar(199) DEFAULT NULL,
  `tersedia` tinyint(1) DEFAULT '1',
  `urutan` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `hari_pengiriman`
--

INSERT INTO `hari_pengiriman` (`id`, `nama_hari`, `tersedia`, `urutan`) VALUES
(1, 'Monday', 1, 1),
(2, 'Tuesday', 0, 2),
(3, 'Wednesday', 1, 3),
(4, 'Thursday', 0, 4),
(5, 'Friday', 1, 5),
(6, 'Saturday', 0, 6),
(7, 'Sunday', 1, 7);

-- --------------------------------------------------------

--
-- Table structure for table `inventaris`
--

CREATE TABLE `inventaris` (
  `id` varchar(191) NOT NULL,
  `id_transaksi` varchar(191) NOT NULL,
  `id_barang` varchar(191) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `keterangan` varchar(50) DEFAULT NULL,
  `jumlah` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `inventaris`
--

INSERT INTO `inventaris` (`id`, `id_transaksi`, `id_barang`, `tanggal`, `status`, `keterangan`, `jumlah`, `created_at`, `updated_at`) VALUES
('1fa985c0-4c72-11ea-a01e-b77f5c8ccd48', 'd31a0530-4af5-11ea-9287-7714e4c83551', 'a012a046-5b4f-4bc1-a6a5-0dd3c343a20b', '2020-02-11', 'OUT', 'PENGIRIMAN KE PEMBELI OLEH Kurir', 1000, '2020-02-11 08:59:22', '2020-02-11 08:59:22'),
('679bfa90-32ac-11ea-8bb0-2d401b73d6d6', 'fbf902d0-32ab-11ea-a381-31bbfa0eb5d2', 'd6f04460-80f9-4c73-9d9f-8ecd1cb3111a', '2020-01-09', 'IN', 'Order Ke Petani', 500, '2020-01-09 13:51:04', '2020-01-09 13:51:04'),
('6ae73160-4c72-11ea-b223-c5ba241a285a', 'd31a0530-4af5-11ea-9287-7714e4c83551', 'a012a046-5b4f-4bc1-a6a5-0dd3c343a20b', '2020-02-11', 'OUT', 'PENGIRIMAN KE PEMBELI OLEH Kurir', 1000, '2020-02-11 09:01:29', '2020-02-11 09:01:29'),
('6b0c2eb0-4c6d-11ea-ba88-0f271aa47fdb', 'f71108a0-4b2b-11ea-b7da-b9c019f9f158', 'a012a046-5b4f-4bc1-a6a5-0dd3c343a20b', '2020-02-11', 'IN', 'Order Ke Petani', 1000, '2020-02-11 08:25:41', '2020-02-11 08:25:41'),
('96369dc0-32ac-11ea-9b4f-25ce219d2cb9', 'fbe25760-32ab-11ea-9ab6-51e2881456be', 'a012a046-5b4f-4bc1-a6a5-0dd3c343a20b', '2020-01-09', 'IN', 'Order Ke Petani', 400, '2020-01-09 13:52:22', '2020-01-09 13:52:22'),
('c34209a0-34d6-11ea-832e-d53b04662eb8', '90b3d740-34d6-11ea-9d9e-615cb68a1061', 'a012a046-5b4f-4bc1-a6a5-0dd3c343a20b', '2020-01-12', 'IN', 'Order Ke Petani', 2000, '2020-01-12 07:59:19', '2020-01-12 07:59:19'),
('d7c62e90-32ad-11ea-8b5a-3f5cceffc2b1', 'f2338ba0-321b-11ea-8e50-39df0de80079', 'a012a046-5b4f-4bc1-a6a5-0dd3c343a20b', '2020-01-09', 'OUT', 'PENGIRIMAN KE PEMBELI OLEH Kurir', 400, '2020-01-09 14:01:21', '2020-01-09 14:01:21'),
('d7dc4080-32ad-11ea-9697-099ef7ae1be4', 'f2338ba0-321b-11ea-8e50-39df0de80079', 'd6f04460-80f9-4c73-9d9f-8ecd1cb3111a', '2020-01-09', 'OUT', 'PENGIRIMAN KE PEMBELI OLEH Kurir', 500, '2020-01-09 14:01:22', '2020-01-09 14:01:22');

-- --------------------------------------------------------

--
-- Table structure for table `isi_pakets`
--

CREATE TABLE `isi_pakets` (
  `id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_barang_parent` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_barang` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `volume` int NOT NULL,
  `harga` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `isi_pakets`
--

INSERT INTO `isi_pakets` (`id`, `id_barang_parent`, `id_barang`, `volume`, `harga`, `created_at`, `updated_at`) VALUES
('aa754381-ef22-4b5b-b4c2-76b9b088a78d', 'a63b0578-c0e5-4bc7-96c2-2b4a95a62d1f', 'd6f04460-80f9-4c73-9d9f-8ecd1cb3111a', 100, 400, '2019-11-28 04:42:18', '2019-11-28 04:42:18'),
('e4ba1129-53ed-409e-8cb5-b0ca6f3b9bda', 'aaaaaaa-sdsadadasd', 'd6f04460-80f9-4c73-9d9f-8ecd1cb3111a', 150, 600, '2019-12-23 15:45:17', '2019-12-23 15:45:17'),
('e589bdc1-df65-49bc-908c-e9eb71661247', 'aaaaaaa-sdsadadasd', 'cc039ae0-5a1e-4328-8f9c-da8b91ef15ed', 150, 1650, '2019-12-23 15:45:17', '2019-12-23 15:45:17');

-- --------------------------------------------------------

--
-- Table structure for table `isi_reseps`
--

CREATE TABLE `isi_reseps` (
  `id` varchar(191) NOT NULL,
  `id_parent_resep` varchar(191) DEFAULT NULL,
  `id_barang` varchar(191) DEFAULT NULL,
  `volume` float DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `isi_reseps`
--

INSERT INTO `isi_reseps` (`id`, `id_parent_resep`, `id_barang`, `volume`, `created_at`, `updated_at`) VALUES
('asd', 'idresep', 'd6f04460-80f9-4c73-9d9f-8ecd1cb3111a', 100, NULL, NULL),
('reso1', 'idresep', 'cc039ae0-5a1e-4328-8f9c-da8b91ef15ed', 100, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `kategoris`
--

CREATE TABLE `kategoris` (
  `id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `kategori` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sub_kategori` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kategoris`
--

INSERT INTO `kategoris` (`id`, `kategori`, `sub_kategori`, `created_at`, `updated_at`) VALUES
('1a8bf1a8-d927-4384-8d76-316f5fa5e308', '8', 'Resep', '2019-11-26 11:16:08', '2019-11-26 11:16:08'),
('a163070b-d262-48ee-bf6e-a87033fc5c06', '1', 'Bayam', '2019-12-30 21:23:29', '2019-12-30 21:23:29'),
('a63b0578-c0e5-4bc7-96c2-2b4a95a62d1f', '8', 'Paket', NULL, NULL),
('bf1ecbdd-4bd8-46ab-8438-ef762e0df46c', '1', 'Kembang Kol', '2020-01-01 06:44:00', '2020-01-01 06:44:00'),
('c983e870-0fb6-11ea-8455-d1e54b19ac1f', '1', 'Wortel', NULL, '2019-11-26 11:13:23'),
('cac437b0-0fb6-11ea-826e-bdc71f9a2f70', '5', 'Salad', NULL, NULL),
('cacc16e0-0fb6-11ea-be2d-e7ace5fb7e85', '6', 'Beras Merah', NULL, NULL),
('cad3ccf0-0fb6-11ea-aa9f-71cf9d346360', '6', 'Beras Putih', NULL, NULL),
('cadb6ed0-0fb6-11ea-a112-938e5ccc9c76', '7', 'Pupuk', NULL, NULL),
('cae32880-0fb6-11ea-9ec8-d1ec328a6251', '7', 'Vitamin', NULL, NULL),
('caf2a840-0fb6-11ea-9bc8-cf5c65eec66c', '1', 'Tomat', NULL, NULL),
('cafa6cf0-0fb6-11ea-a598-511b9a31292e', '2', 'Apel', NULL, NULL),
('cb0290b0-0fb6-11ea-a47b-7160f4acc51a', '2', 'Lemon', NULL, NULL),
('cb32e7e0-0fb6-11ea-92dc-b5a4bd319070', '3', 'Oat', NULL, NULL),
('cb3a8b10-0fb6-11ea-8912-d18da6979ff6', '3', 'Kacang', NULL, NULL),
('cb4243b0-0fb6-11ea-8df2-b59e39bdf216', '4', 'Yoghurt', NULL, NULL),
('cb4a28b0-0fb6-11ea-84a1-6fbb5bbed4da', '4', 'Jamu', NULL, NULL),
('cb51c120-0fb6-11ea-a496-7b68c9d2baf3', '5', 'Biskuit', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `keranjangs`
--

CREATE TABLE `keranjangs` (
  `id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_user` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_pre_order` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `klaims`
--

CREATE TABLE `klaims` (
  `id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode_klaim` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_transaksi` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `klaim_to` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `klaim_from` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_kirim` datetime NOT NULL,
  `status` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `klaims`
--

INSERT INTO `klaims` (`id`, `kode_klaim`, `id_transaksi`, `klaim_to`, `klaim_from`, `tanggal_kirim`, `status`, `created_at`, `updated_at`) VALUES
('9aec7c80-2dd7-11ea-897c-1d2a628a7713', 'KL75757', '0e274c40-2b1c-11ea-86be-2728b227f130', '3', '3dceb0cc-9983-470e-9e2b-67facc51175d', '2020-01-02 18:36:33', '1', '2020-01-03 10:17:42', '2020-01-03 10:17:42'),
('a6d77260-2e1b-11ea-bf7d-411de35f7ac9', 'KL76859', '0be15f70-2b1c-11ea-800f-f1be0e302542', '52539219-07c9-4383-800c-66a9dfa50157', '3dceb0cc-9983-470e-9e2b-67facc51175d', '2020-01-03 18:22:46', '1', '2020-01-03 18:24:48', '2020-01-03 18:24:48');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_11_14_043859_sample_database', 1),
(5, '2019_11_15_051951_create_barangs_table', 1),
(6, '2019_11_15_052004_create_foto_barangs_table', 1),
(7, '2019_11_15_052028_create_isi_pakets_table', 1),
(8, '2019_11_15_052539_create_barangs_groups_table', 1),
(9, '2019_11_15_052602_create_kategoris_table', 1),
(10, '2019_11_15_052615_create_alamats_table', 1),
(11, '2019_11_15_052625_create_keranjangs_table', 1),
(12, '2019_11_15_052637_create_detail_keranjangs_table', 1),
(13, '2019_11_15_052703_create_transaksis_table', 1),
(14, '2019_11_15_052713_create_detail_transaksis_table', 1),
(15, '2019_11_15_052725_create_klaims_table', 1),
(16, '2019_11_15_052735_create_detail_klaims_table', 1),
(17, '2019_11_15_120345_create_barangs_kemasans_table', 1),
(18, '2019_11_19_122823_create_bobot_kemasans_table', 1),
(19, '2019_11_19_160114_create_base_kategoris_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reseps`
--

CREATE TABLE `reseps` (
  `id` varchar(191) NOT NULL,
  `judul` varchar(191) DEFAULT NULL,
  `foto` varchar(191) DEFAULT NULL,
  `artikel` longtext,
  `is_show` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `reseps`
--

INSERT INTO `reseps` (`id`, `judul`, `foto`, `artikel`, `is_show`, `created_at`, `updated_at`) VALUES
('idresep', 'Resep Apel Brokoli Goreng', NULL, 'Ini isi artikelnya', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sample_database`
--

CREATE TABLE `sample_database` (
  `id` bigint UNSIGNED NOT NULL,
  `sample` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transaksis`
--

CREATE TABLE `transaksis` (
  `id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_user` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_alamat` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_kurir` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nomor_invoice` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_bayar` int NOT NULL,
  `status` int DEFAULT NULL,
  `is_info_petani` tinyint(1) NOT NULL DEFAULT '0',
  `is_canceled_by_veggo` tinyint(1) NOT NULL DEFAULT '0',
  `bukti_transfer` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_pre_order` date NOT NULL,
  `keterangan` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipe_transaksi` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'FROM_BUYER',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `isCheckout` tinyint(1) NOT NULL DEFAULT '0',
  `isAlreadyPay` tinyint(1) NOT NULL DEFAULT '0',
  `tanggal_pengiriman` timestamp NULL DEFAULT NULL,
  `tanggal_terima` timestamp NULL DEFAULT NULL,
  `nama_penerima` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `keterangan_penerima` text COLLATE utf8mb4_unicode_ci,
  `foto_penerima` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_bayar_akhir` int DEFAULT NULL,
  `is_exclude_rekap` tinyint DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transaksis`
--

INSERT INTO `transaksis` (`id`, `id_user`, `id_alamat`, `id_kurir`, `nomor_invoice`, `total_bayar`, `status`, `is_info_petani`, `is_canceled_by_veggo`, `bukti_transfer`, `tanggal_pre_order`, `keterangan`, `tipe_transaksi`, `created_at`, `updated_at`, `isCheckout`, `isAlreadyPay`, `tanggal_pengiriman`, `tanggal_terima`, `nama_penerima`, `keterangan_penerima`, `foto_penerima`, `total_bayar_akhir`, `is_exclude_rekap`) VALUES
('3df78f40-34d6-11ea-94f2-3790dd78c4e3', 'edfc818a-88df-457a-9f43-529f9ff4a5e3', NULL, NULL, 'VG08790', 60000, 4, 0, 0, NULL, '2020-01-08', NULL, 'FROM_BUYER', '2020-01-12 07:55:35', '2020-02-05 12:21:54', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('90b3d740-34d6-11ea-9d9e-615cb68a1061', '3', 'alamat-petani', 'veggo', 'VGPETANI26252', 0, 3, 0, 0, '0', '2020-01-08', 'order ke petani', 'FROM_VEGGO', '2020-01-12 07:57:54', '2020-01-12 07:59:19', 0, 0, '2020-01-12 07:59:06', '2020-01-12 07:59:19', NULL, NULL, NULL, NULL, NULL),
('ae0c94c0-4405-11ea-b5fe-0ddb1a23072b', 'edfc818a-88df-457a-9f43-529f9ff4a5e3', '49a3ae70-1219-11ea-b39f-ef87334ef039', 'edfc818a-88df-457a-9f43-529f9ffadasd', 'VG65212', 44200, 7, 0, 0, NULL, '2020-02-05', 'KOSAN A56 MANTAP', 'FROM_BUYER', '2020-01-31 15:42:57', '2020-02-05 03:07:17', 1, 0, '2020-01-05 07:59:06', NULL, NULL, NULL, NULL, NULL, NULL),
('d31a0530-4af5-11ea-9287-7714e4c83551', 'edfc818a-88df-457a-9f43-529f9ff4a5e3', NULL, 'edfc818a-88df-457a-9f43-529f9ffadasd', 'VG44724', 30000, 5, 0, 0, NULL, '2020-02-09', NULL, 'FROM_BUYER', '2020-02-09 11:37:05', '2020-02-11 09:01:28', 1, 0, '2020-02-11 09:01:28', NULL, NULL, NULL, NULL, NULL, 0),
('ea49dba0-47dc-11ea-a990-9924b0debc00', 'edfc818a-88df-457a-9f43-529f9ff4a5e3', NULL, NULL, 'VG24947', 10800, 1, 0, 0, NULL, '2020-02-09', 'COBA GANTI STATUS', 'FROM_BUYER', '2020-02-05 13:01:13', '2020-02-09 18:04:39', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
('f2338ba0-321b-11ea-8e50-39df0de80079', 'edfc818a-88df-457a-9f43-529f9ff4a5e3', '319fc730-121b-11ea-96c5-b9c32ac498fe', 'edfc818a-88df-457a-9f43-529f9ffadasd', 'VG55789', 16000, 6, 0, 0, NULL, '2020-01-10', 'YANG PUNYA KOSAN', 'FROM_BUYER', '2020-01-08 20:36:59', '2020-02-05 03:06:14', 1, 3, '2020-01-09 14:01:21', NULL, NULL, NULL, NULL, NULL, NULL),
('f71108a0-4b2b-11ea-b7da-b9c019f9f158', '3', 'alamat-petani', 'veggo', 'VGPETANI13625', 0, 3, 0, 0, '0', '2020-02-09', 'order ke petani', 'FROM_VEGGO', '2020-02-09 18:04:38', '2020-02-11 08:25:41', 0, 0, '2020-02-11 08:25:04', '2020-02-11 08:25:41', NULL, NULL, NULL, NULL, 0),
('fbe25760-32ab-11ea-9ab6-51e2881456be', '3', 'alamat-petani', 'veggo', 'VGPETANI51836', 0, 3, 0, 0, '0', '2020-01-10', 'order ke petani', 'FROM_VEGGO', '2020-01-09 13:48:03', '2020-01-09 13:52:22', 0, 0, '2020-01-09 13:50:58', '2020-01-09 13:52:22', NULL, NULL, NULL, NULL, NULL),
('fbf902d0-32ab-11ea-a381-31bbfa0eb5d2', '52539219-07c9-4383-800c-66a9dfa50157', 'alamat-petani', 'veggo', 'VGPETANI61750', 0, 3, 0, 0, '0', '2020-01-10', 'order ke petani', 'FROM_VEGGO', '2020-01-09 13:48:03', '2020-01-09 13:51:04', 0, 0, '2020-01-09 13:49:45', '2020-01-09 13:51:04', NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` int NOT NULL DEFAULT '2',
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `nomor_hp` varchar(199) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `remember_token`, `created_at`, `updated_at`, `nomor_hp`) VALUES
('3', 'petani_2', 'petani2@petani.com', NULL, '$2y$10$7AJS9DusjbREchuIBi0C4O/TiBdahfCyPZxcuB.9ptdxDb3db6NM6', 3, NULL, NULL, NULL, NULL),
('349f7c9f-87bf-48cf-a74d-ca745c2859e1', 'Pembeli 2', 'pembeli2@pembeli.com', NULL, '$2y$10$jeo.QbwRwLQNhNkVJXU1/uJquRuo.VF5YiFuQv5UA7BXp1nL71sn.', 2, NULL, '2019-12-20 23:46:49', '2019-12-20 23:46:49', NULL),
('3dceb0cc-9983-470e-9e2b-67facc51175d', 'penjual', 'penjual@penjual.com', NULL, '$2y$10$XGC0Tn5D0JFZ8TJv93N4TOYslhh03p2cFYTiIB8oPbYhQyt3HMCIS', 1, NULL, '2019-11-19 16:19:36', '2019-11-19 16:19:36', NULL),
('52539219-07c9-4383-800c-66a9dfa50157', 'petani_1', 'petani1@petani.com', NULL, '$2y$10$7AJS9DusjbREchuIBi0C4O/TiBdahfCyPZxcuB.9ptdxDb3db6NM6', 3, NULL, '2019-11-19 16:20:52', '2019-11-19 16:20:52', NULL),
('edfc818a-88df-457a-9f43-529f9ff4a5e3', 'Pembeli', 'pembeli@pembeli.com', NULL, '$2y$10$3DvwrSmZiF5.PmLAjHjt.u//CC8ewnGAbSIBnOIdKEmoa.9CozFBy', 2, NULL, '2019-11-25 11:40:59', '2019-11-25 11:40:59', '087855857881'),
('edfc818a-88df-457a-9f43-529f9ffadasd', 'Kurir', 'kurir@kurir.com', '2019-12-30 15:10:46', '$2y$10$3DvwrSmZiF5.PmLAjHjt.u//CC8ewnGAbSIBnOIdKEmoa.9CozFBy', 5, NULL, NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `alamats`
--
ALTER TABLE `alamats`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `barangs`
--
ALTER TABLE `barangs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `barangs_groups`
--
ALTER TABLE `barangs_groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `barangs_kemasans`
--
ALTER TABLE `barangs_kemasans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `base_kategoris`
--
ALTER TABLE `base_kategoris`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bobot_kemasans`
--
ALTER TABLE `bobot_kemasans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bobot_kemasans_bobot_kemasan_unique` (`bobot_kemasan`);

--
-- Indexes for table `detail_keranjangs`
--
ALTER TABLE `detail_keranjangs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `detail_klaims`
--
ALTER TABLE `detail_klaims`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `detail_transaksis`
--
ALTER TABLE `detail_transaksis`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `foto_barangs`
--
ALTER TABLE `foto_barangs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hari_pengiriman`
--
ALTER TABLE `hari_pengiriman`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `inventaris`
--
ALTER TABLE `inventaris`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `isi_pakets`
--
ALTER TABLE `isi_pakets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `isi_reseps`
--
ALTER TABLE `isi_reseps`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kategoris`
--
ALTER TABLE `kategoris`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `keranjangs`
--
ALTER TABLE `keranjangs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `klaims`
--
ALTER TABLE `klaims`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `reseps`
--
ALTER TABLE `reseps`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sample_database`
--
ALTER TABLE `sample_database`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaksis`
--
ALTER TABLE `transaksis`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hari_pengiriman`
--
ALTER TABLE `hari_pengiriman`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `sample_database`
--
ALTER TABLE `sample_database`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
