-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 29 Sep 2020 pada 16.39
-- Versi server: 10.4.13-MariaDB
-- Versi PHP: 7.4.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `veggo`
--

DELIMITER $$
--
-- Prosedur
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_get_barang_by_id_reseller` (`idd` VARCHAR(191), `dates` DATE)  NO SQL
BEGIN
	SELECT id_user, id_barang, SUM(volume) as volume, SUM(harga) as harga, bobot_kemasan, SUM(harga_diskon) as harga_diskon 
	FROM keranjang_resellers JOIN parent_keranjang_resellers ON id_parent_keranjang=parent_keranjang_resellers.id
	WHERE id_user=idd and status=0 and tanggal_pre_order=dates
	GROUP BY id_barang, bobot_kemasan;
    END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_get_barang_by_kategori` (IN `nama_sub_kategori` VARCHAR(255), `tanggals` DATE)  NO SQL
BEGIN
SELECT k.sub_kategori,b.* FROM kategoris k, barangs_groups bg, barangs b,barang_tanggals 
WHERE b.id=barang_tanggals.`id_barang` AND tanggal=tanggals AND  bg.id_kategori = k.id AND b.id = bg.id_barang AND k.sub_kategori = nama_sub_kategori;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_get_barang_by_tanggal` (IN `date` DATE)  NO SQL
BEGIN
	select * from barangs join barang_tanggals on barangs.id=barang_tanggals.`id_barang` where tanggal=date;
    END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_get_barang_by_tanggal_and_jenis` (IN `date` DATE, `jeniss` VARCHAR(191))  NO SQL
BEGIN
	SELECT * FROM barangs JOIN barang_tanggals ON barangs.id=barang_tanggals.`id_barang` WHERE tanggal=DATE and jenis=jeniss;
    END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_get_barang_by_tanggal_and_kategori` (IN `date` DATE, `kateg` VARCHAR(191))  NO SQL
BEGIN
	SELECT * FROM barangs JOIN barang_tanggals ON barangs.id=barang_tanggals.`id_barang` WHERE tanggal=DATE AND id_kategori=kateg;
    END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_get_barang_by_tanggal_and_search` (IN `date` DATE, `search` VARCHAR(191))  NO SQL
BEGIN
	SELECT * FROM barangs JOIN barang_tanggals ON barangs.id=barang_tanggals.`id_barang` WHERE tanggal=date AND nama LIKE CONCAT('%', search, '%');
    END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_get_barang_by_transaksi` (`idUser` VARCHAR(191))  BEGIN
 select detail_transaksis.id, transaksis.id, transaksis.`id_user`, harga_diskon,harga_akhir_diskon, nama, volume, jenis, satuan, volume_kirim_kurir from transaksis 
 join detail_transaksis on transaksis.`id`=detail_transaksis.`id_transaksi` 
 join barangs on barangs.`id`=detail_transaksis.`id_barang` 
 where transaksis.id=idUser;
    END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_get_buyer_list` (IN `kode` VARCHAR(100), IN `date` DATE)  NO SQL
BEGIN

SELECT b.kode,u.name FROM transaksis t
LEFT JOIN users u ON u.id = t.id_user
LEFT JOIN detail_transaksis dt ON dt.id_transaksi = t.id
LEFT JOIN barangs b ON b.id = dt.id_barang
WHERE t.tanggal_pre_order = date AND t.status = 1 AND b.kode = kode;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_get_isi_paket` (IN `id_barang_parent` VARCHAR(255))  NO SQL
select barangs.nama, barangs.satuan, isi_pakets.volume 
from barangs
inner join isi_pakets
on barangs.id = isi_pakets.id_barang
where isi_pakets.id_barang_parent = id_barang_parent$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_get_kurir_detail_barang` (IN `id_transaksi` VARCHAR(255))  NO SQL
select barangs.nama, detail_transaksis.volume_kirim_kurir, detail_transaksis.harga_akhir_diskon, transaksis.`total_bayar_akhir`,transaksis.`total_bayar`, barangs.jenis, barangs.id as barang_id, detail_transaksis.id as detail_transaksi_id
from detail_transaksis
inner join barangs
	on detail_transaksis.id_barang = barangs.id
inner join transaksis
	on detail_transaksis.id_transaksi = transaksis.id
where detail_transaksis.id_transaksi = id_transaksi$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_get_paket_akan_dikirim` (IN `id_kurir` VARCHAR(256))  NO SQL
select transaksis.nomor_invoice, users.name, alamats.alamat, alamats.blok_nomor, alamats.kecamatan, alamats.kotkab, alamats.kodepos, users.nomor_hp, transaksis.tanggal_pengiriman, alamats.lat, alamats.long, transaksis.id as transaksi_id
from transaksis
inner join users
	on users.id = transaksis.id_user
inner join alamats
	on alamats.id = transaksis.id_alamat
where transaksis.status = '5' and transaksis.id_kurir = id_kurir$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_get_paket_akan_dikirim_by_id_transaksi` (IN `id_transaksi` VARCHAR(255))  NO SQL
select transaksis.nomor_invoice, users.name, alamats.alamat,alamats.`info_tambahan`, alamats.blok_nomor, alamats.kecamatan, alamats.kotkab, alamats.kodepos, users.nomor_hp, transaksis.tanggal_pengiriman, alamats.lat, alamats.long, transaksis.keterangan, transaksis.id as transaksi_id, transaksis.total_bayar, transaksis.isAlreadyPay
from transaksis
inner join users
	on users.id = transaksis.id_user
inner join alamats
	on alamats.id = transaksis.id_alamat
where transaksis.id = id_transaksi$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_get_paket_ke_reseller` (IN `idreseller` VARCHAR(256))  NO SQL
BEGIN
	SELECT transaksis.nomor_invoice, transaksis.is_diterima_reseller, transaksis.status, transaksis.`is_confirm_finish_byuser`, users.name, alamats.alamat, alamats.blok_nomor, alamats.kecamatan, alamats.kotkab, alamats.kodepos, users.nomor_hp, transaksis.tanggal_pengiriman, alamats.lat, alamats.long, transaksis.id AS transaksi_id
	FROM transaksis
	INNER JOIN users
		ON users.id = transaksis.id_user
	INNER JOIN alamats
		ON alamats.id = transaksis.id_alamat
	WHERE transaksis.id_reseller = idreseller
	order by transaksis.tanggal_pengiriman desc;
    END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_get_paket_sedang_dikirim` (IN `id_kurir` VARCHAR(255))  NO SQL
BEGIN 

select transaksis.nomor_invoice, users.name, alamats.alamat, alamats.blok_nomor, alamats.kecamatan, alamats.kotkab, alamats.kodepos, users.nomor_hp, transaksis.tanggal_pengiriman, alamats.lat, alamats.long, transaksis.id as transaksi_id
from transaksis
inner join users
	on users.id = transaksis.id_user
inner join alamats
	on alamats.id = transaksis.id_alamat
where transaksis.status = '6' and transaksis.id_kurir = id_kurir;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_get_paket_selesai_dikirim` (IN `id_kurir` VARCHAR(255))  NO SQL
BEGIN
	SELECT transaksis.`is_confirm_finish_byuser`, transaksis.`nama_penerima`, transaksis.nomor_invoice, users.name, alamats.alamat, alamats.blok_nomor, alamats.kecamatan, alamats.kotkab, alamats.kodepos, users.nomor_hp, transaksis.tanggal_pengiriman, alamats.lat, alamats.long, transaksis.id AS transaksi_id
	FROM transaksis
	INNER JOIN users
		ON users.id = transaksis.id_user
	INNER JOIN alamats
		ON alamats.id = transaksis.id_alamat
	WHERE transaksis.status = '7' AND transaksis.id_kurir = id_kurir;
 END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_get_total_bayar_veggo_by_petani` (IN `date` DATE)  NO SQL
BEGIN
	SELECT transaksis.id_user AS id_petani, users.`name`AS nama_petani,SUM((harga_beli/bobot)*((bobot_terima*volume_terima)-COALESCE(volume_klaim, 0))) AS harga FROM transaksis 
	JOIN detail_transaksis ON transaksis.id=detail_transaksis.`id_transaksi` 
	JOIN barangs ON detail_transaksis.`id_barang`=barangs.id
	JOIN users ON transaksis.`id_user`=users.id
	LEFT JOIN detail_klaims ON detail_klaims.`id_detail_transaksi`= detail_transaksis.`id`
	WHERE nomor_invoice LIKE '%VGPETANI%' AND transaksis.status=3 AND tanggal_pre_order=date
	GROUP BY transaksis.id_user;
    END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_get_total_bayar_veggo_by_tanggal` (IN `date` DATE)  NO SQL
BEGIN
	SELECT transaksis.id_user AS id_petani, users.`name`as nama_petani, isAlreadyPay, nama,sum(COALESCE(volume_klaim, 0)) AS volume_klaim,SUM((harga_beli/bobot)*((bobot_terima*volume_terima)-COALESCE(volume_klaim, 0))) AS harga, SUM((bobot_kemasan * volume)) AS jumlah,SUM((bobot_kirim_petani*volume_kirim_petani)) AS jumlah_kirim, SUM((bobot_terima*volume_terima)) AS jumlah_terima,  SUM(selisih_kirim), SUM(selisih_terima), NAME FROM transaksis 
	JOIN detail_transaksis ON transaksis.id=detail_transaksis.`id_transaksi` 
	JOIN barangs ON detail_transaksis.`id_barang`=barangs.id
	JOIN users ON transaksis.`id_user`=users.id
	LEFT JOIN detail_klaims ON detail_klaims.`id_detail_transaksi`= detail_transaksis.`id`
	WHERE nomor_invoice LIKE '%VGPETANI%' AND transaksis.status=3 AND tanggal_pre_order=date
	GROUP BY nama;
    END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_get_total_non_paket` (IN `date` DATE)  NO SQL
BEGIN

SELECT b.kode AS kode_barang,b.id_user AS supplier_barang,b.nama AS nama_barang, SUM(dt.volume) AS volume, dt.bobot_kemasan AS bobot FROM transaksis t
INNER JOIN detail_transaksis dt ON dt.id_transaksi = t.id
INNER JOIN barangs b ON b.id = dt.id_barang
WHERE t.tanggal_pre_order = DATE AND t.status = 1 AND b.is_paket = 0 AND t.tipe_transaksi = 'FROM_BUYER' AND  dt.is_canceled_by_veggo = 0 AND dt.is_exclude_rekap = 0 AND b.jenis != 'Timbang'
GROUP BY kode_barang,supplier_barang,nama_barang, dt.bobot_kemasan;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_get_total_paket` (IN `date` DATE)  NO SQL
BEGIN

SELECT (SELECT b2.kode FROM barangs b2 WHERE b2.id = ip.id_barang) AS kode_barang,(SELECT b2.id_user FROM barangs b2 WHERE b2.id = ip.id_barang) AS supplier_barang,(SELECT b2.nama FROM barangs b2 WHERE b2.id = ip.id_barang) AS nama_barang, SUM(dt.volume) AS volume,ip.volume AS bobot FROM barangs b
LEFT JOIN isi_pakets ip ON ip.id_barang_parent = b.id
LEFT JOIN detail_transaksis dt ON dt.id_barang = b.id
LEFT JOIN transaksis t ON t.id = dt.id_transaksi
WHERE t.tanggal_pre_order = DATE AND t.status = 1 AND b.is_paket = 1 AND t.tipe_transaksi = 'FROM_BUYER' AND dt.is_canceled_by_veggo = 0 AND dt.is_exclude_rekap = 0
GROUP BY kode_barang,supplier_barang,nama_barang, ip.volume;


END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_get_total_pre_order` (IN `date` DATE)  NO SQL
BEGIN

SELECT b.kode,b.nama,b.jenis,dt.bobot_kemasan,b.satuan, SUM(dt.volume) AS volume FROM transaksis t
INNER JOIN detail_transaksis dt ON dt.id_transaksi = t.id
INNER JOIN barangs b ON b.id = dt.id_barang
WHERE t.tanggal_pre_order = date AND t.status = 1 AND t.tipe_transaksi = 'FROM_BUYER' AND dt.is_canceled_by_veggo = 0 AND dt.is_exclude_rekap = 0
GROUP BY b.kode,b.nama,b.jenis,dt.bobot_kemasan,b.satuan;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_get_total_timbang` (IN `date` DATE)  NO SQL
BEGIN

SELECT b.kode AS kode_barang,b.id_user AS supplier_barang,b.nama AS nama_barang, SUM(COALESCE(dt.bobot_kemasan, 1))  AS volume, dt.volume AS bobot FROM transaksis t
INNER JOIN detail_transaksis dt ON dt.id_transaksi = t.id
INNER JOIN barangs b ON b.id = dt.id_barang
WHERE t.tanggal_pre_order = DATE AND t.status = 1 AND b.is_paket = 0 AND t.tipe_transaksi = 'FROM_BUYER' AND  dt.is_canceled_by_veggo = 0 AND dt.is_exclude_rekap = 0 AND b.jenis = 'Timbang'
GROUP BY kode_barang,supplier_barang,nama_barang,dt.volume;

END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `alamats`
--

CREATE TABLE `alamats` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_user` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kotkab` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `daerah` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kodepos` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `long` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lat` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `blok_nomor` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `info_tambahan` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `barangs`
--

CREATE TABLE `barangs` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_user` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_kategori` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `satuan` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bobot` int(11) NOT NULL,
  `harga_beli` int(11) NOT NULL,
  `harga_jual` int(11) NOT NULL,
  `deskripsi` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `diskon` int(11) DEFAULT 0,
  `jenis_diskon` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `show_etalase` int(11) DEFAULT NULL,
  `is_paket` int(11) DEFAULT 0,
  `ketersediaan` int(11) DEFAULT NULL,
  `stok` int(11) DEFAULT NULL,
  `bobot_minimum_timbang` int(11) DEFAULT NULL,
  `bobot_kemasan_kemas` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `barangs`
--

INSERT INTO `barangs` (`id`, `id_user`, `id_kategori`, `nama`, `kode`, `jenis`, `satuan`, `bobot`, `harga_beli`, `harga_jual`, `deskripsi`, `diskon`, `jenis_diskon`, `show_etalase`, `is_paket`, `ketersediaan`, `stok`, `bobot_minimum_timbang`, `bobot_kemasan_kemas`, `created_at`, `updated_at`) VALUES
('08e642e7-e5d0-4e8e-af6d-456dc3492654', '52539219-07c9-4383-800c-66a9dfa50157', '2', 'Apel', 'B1317', 'Timbang', 'Gram', 1000, 50000, 55000, 'Ini Apel', 10, 'Potongan Persen', NULL, 0, NULL, 2050, 500, NULL, '2020-09-27 15:00:52', '2020-09-28 15:08:33'),
('112161f4-d386-4b97-9df1-8deb3e03bdb6', '52539219-07c9-4383-800c-66a9dfa50157', '1', 'Wortel', 'B1278', 'Kemas', 'Gram', 1000, 5000, 5500, 'ini wortel', 0, '-', NULL, 0, NULL, NULL, NULL, NULL, '2020-09-27 14:59:05', '2020-09-27 14:59:05'),
('39582314-34f8-4787-9dab-4d4a6be65fa0', '3', '5', 'Tempe', 'B9681', 'Kemas', 'Gram', 1000, 5000, 10000, 'Ini Tempe', 0, '-', NULL, 0, NULL, NULL, NULL, NULL, '2020-09-29 14:23:07', '2020-09-29 14:23:07'),
('577a19a8-8b60-416b-b933-0db5f5e07c5b', '3', '5', 'Terasi', 'B6990', 'Kemas', 'Gram', 1000, 1000, 2500, 'Ini Terasi', 0, '-', NULL, 0, NULL, NULL, NULL, NULL, '2020-09-29 12:42:18', '2020-09-29 12:42:18'),
('63ef0dc2-a772-4063-bf3e-5a8d057a4df8', '52539219-07c9-4383-800c-66a9dfa50157', '1', 'Kangkung', 'B4250', 'Kemas', 'Gram', 1000, 4000, 5000, 'Ini Kangkung', 0, '-', NULL, 0, NULL, NULL, NULL, NULL, '2020-09-29 12:44:33', '2020-09-29 12:44:33'),
('baa6e9c9-aadc-45e5-9fde-1e0b33219960', '3', '1', 'Cabai Keriting', 'B1515', 'Timbang', 'Gram', 1000, 25000, 30000, 'Ini Cabai', 0, '-', NULL, 0, NULL, NULL, 500, NULL, '2020-09-29 12:43:12', '2020-09-29 12:43:12'),
('cd84709b-ceb3-4a1a-b27f-f663cdabd5d4', '3', '1', 'Daun Singkong', 'B2709', 'Kemas', 'Gram', 1000, 5000, 8000, 'ini daun singkong', 0, '-', NULL, 0, NULL, NULL, NULL, NULL, '2020-09-29 12:46:16', '2020-09-29 12:46:16'),
('dbc0a797-9e5f-4f5a-8324-e20f175964ef', '52539219-07c9-4383-800c-66a9dfa50157', '5', 'Paket WA', 'BP488', 'Paket', 'Pcs', 1, 5500, 5500, '-', 500, '0', 0, 1, 0, 0, NULL, NULL, '2020-09-27 15:02:05', '2020-09-27 15:02:05'),
('f7d48d89-8790-48eb-ba22-8da30e538455', '3', '1', 'Bayam', 'B1223', 'Timbang', 'Gram', 1000, 5000, 8000, 'ini bayam', 10, 'Potongan Persen', NULL, 0, NULL, NULL, 500, NULL, '2020-09-29 12:45:25', '2020-09-29 12:45:25');

-- --------------------------------------------------------

--
-- Struktur dari tabel `barangs_groups`
--

CREATE TABLE `barangs_groups` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_barang` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_kategori` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `barangs_groups`
--

INSERT INTO `barangs_groups` (`id`, `id_barang`, `id_kategori`, `created_at`, `updated_at`) VALUES
('01412be3-5b9f-4fd0-8405-751125f8db84', 'cd84709b-ceb3-4a1a-b27f-f663cdabd5d4', 'ed3adc92-e534-4fb3-9a50-727b36601ea9', '2020-09-29 12:46:16', '2020-09-29 12:46:16'),
('24b90ff9-141c-4801-8aef-b75131072f4c', 'baa6e9c9-aadc-45e5-9fde-1e0b33219960', 'f49d5386-455a-46a6-bacd-2368cfa4bed1', '2020-09-29 12:43:12', '2020-09-29 12:43:12'),
('33a48014-fe78-4251-b485-2f49507936e2', '577a19a8-8b60-416b-b933-0db5f5e07c5b', 'cfad237b-e716-4d18-82b8-0940f9d834c5', '2020-09-29 12:42:18', '2020-09-29 12:42:18'),
('3d00d091-81c0-49ae-89b0-f70a0c5a0927', '63ef0dc2-a772-4063-bf3e-5a8d057a4df8', 'f49d5386-455a-46a6-bacd-2368cfa4bed1', '2020-09-29 12:44:33', '2020-09-29 12:44:33'),
('48d2e3cf-de10-42d4-a92a-3a801f5ec759', '08e642e7-e5d0-4e8e-af6d-456dc3492654', '5925d487-508b-450f-9c60-fdf7c0ba0385', '2020-09-27 15:00:52', '2020-09-27 15:00:52'),
('53fa85b8-f2ca-4f8e-a6b7-c2897d00f9bc', '63ef0dc2-a772-4063-bf3e-5a8d057a4df8', 'ed3adc92-e534-4fb3-9a50-727b36601ea9', '2020-09-29 12:44:33', '2020-09-29 12:44:33'),
('5604b83f-75ec-4f09-b537-3d5e47afca35', 'dbc0a797-9e5f-4f5a-8324-e20f175964ef', '5925d487-508b-450f-9c60-fdf7c0ba0385', '2020-09-27 15:02:05', '2020-09-27 15:02:05'),
('56ad59e9-226d-4dfe-948e-9ee9195ca959', '112161f4-d386-4b97-9df1-8deb3e03bdb6', 'acbc6531-e6e7-4368-b26a-5104fea9581f', '2020-09-27 14:59:05', '2020-09-27 14:59:05'),
('61f55d08-71cf-4070-9c86-957aeb2e4b89', 'f7d48d89-8790-48eb-ba22-8da30e538455', 'f49d5386-455a-46a6-bacd-2368cfa4bed1', '2020-09-29 12:45:25', '2020-09-29 12:45:25'),
('ae4aca94-4146-4ed9-87ca-1fcd6b21f8aa', '39582314-34f8-4787-9dab-4d4a6be65fa0', '305aa35c-4c22-4cc2-bc3f-65b11c3d5a94', '2020-09-29 14:23:07', '2020-09-29 14:23:07'),
('ca967269-2ea3-4981-b6e4-78b63d8fed4d', 'dbc0a797-9e5f-4f5a-8324-e20f175964ef', 'ed3adc92-e534-4fb3-9a50-727b36601ea9', '2020-09-27 15:02:05', '2020-09-27 15:02:05'),
('d5ba18f6-93f8-453c-a014-246677a505fb', 'dbc0a797-9e5f-4f5a-8324-e20f175964ef', 'acbc6531-e6e7-4368-b26a-5104fea9581f', '2020-09-27 15:02:05', '2020-09-27 15:02:05'),
('e1c11e86-3f1d-4ac8-b454-1546ceab433f', 'cd84709b-ceb3-4a1a-b27f-f663cdabd5d4', 'f49d5386-455a-46a6-bacd-2368cfa4bed1', '2020-09-29 12:46:16', '2020-09-29 12:46:16');

-- --------------------------------------------------------

--
-- Struktur dari tabel `barangs_kemasans`
--

CREATE TABLE `barangs_kemasans` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_barang` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bobot_kemasan` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `barangs_kemasans`
--

INSERT INTO `barangs_kemasans` (`id`, `id_barang`, `bobot_kemasan`, `created_at`, `updated_at`) VALUES
('08c51ec0-36e9-4bcd-9165-1e825645034d', 'cd84709b-ceb3-4a1a-b27f-f663cdabd5d4', '400', '2020-09-29 12:46:16', '2020-09-29 12:46:16'),
('36e584e6-d5a9-4bbc-8285-5e923f08fed7', '39582314-34f8-4787-9dab-4d4a6be65fa0', '500', '2020-09-29 14:23:07', '2020-09-29 14:23:07'),
('417643a3-e006-4502-84ac-267eb76a8606', '577a19a8-8b60-416b-b933-0db5f5e07c5b', '200', '2020-09-29 12:42:18', '2020-09-29 12:42:18'),
('bb211f3b-0b82-42a6-b094-8675a50441b6', '63ef0dc2-a772-4063-bf3e-5a8d057a4df8', '500', '2020-09-29 12:44:33', '2020-09-29 12:44:33'),
('e280dfcf-2473-4f72-9163-400f8bbaccd1', '112161f4-d386-4b97-9df1-8deb3e03bdb6', '400', '2020-09-27 14:59:05', '2020-09-27 14:59:05');

-- --------------------------------------------------------

--
-- Struktur dari tabel `barang_tanggals`
--

CREATE TABLE `barang_tanggals` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_barang` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `barang_tanggals`
--

INSERT INTO `barang_tanggals` (`id`, `id_barang`, `tanggal`, `created_at`, `updated_at`) VALUES
('79188482-2dbc-45d4-857f-8b2897bc6ca7', '112161f4-d386-4b97-9df1-8deb3e03bdb6', '2020-10-03', '2020-09-29 13:27:16', '2020-09-29 13:27:16'),
('8cc6e73a-3fdf-466b-8b18-b1b34774ad22', '08e642e7-e5d0-4e8e-af6d-456dc3492654', '2020-10-03', '2020-09-29 13:27:16', '2020-09-29 13:27:16'),
('36bdd413-8c10-4643-87b4-7d551a0afb5f', 'dbc0a797-9e5f-4f5a-8324-e20f175964ef', '2020-10-03', '2020-09-29 13:27:16', '2020-09-29 13:27:16'),
('5809dbe5-695d-4e1d-9cb9-64e8e04d687a', 'f7d48d89-8790-48eb-ba22-8da30e538455', '2020-10-07', '2020-09-29 19:47:26', '2020-09-29 19:47:26'),
('3baeaf2d-09ea-4df5-9e61-3897d32bd406', '112161f4-d386-4b97-9df1-8deb3e03bdb6', '2020-10-07', '2020-09-29 19:47:26', '2020-09-29 19:47:26'),
('c8940576-62dd-4d1a-8122-e373e12a9342', '08e642e7-e5d0-4e8e-af6d-456dc3492654', '2020-10-07', '2020-09-29 19:47:26', '2020-09-29 19:47:26'),
('e2bc75f0-82c1-4ce7-8e81-e0b7e2a44611', 'baa6e9c9-aadc-45e5-9fde-1e0b33219960', '2020-10-07', '2020-09-29 19:47:26', '2020-09-29 19:47:26'),
('c143378a-9f97-495a-9498-8f6f244c8483', 'cd84709b-ceb3-4a1a-b27f-f663cdabd5d4', '2020-10-07', '2020-09-29 19:47:27', '2020-09-29 19:47:27'),
('1847a2ac-b481-4cb1-8fa1-cdce8cabb2d0', '63ef0dc2-a772-4063-bf3e-5a8d057a4df8', '2020-10-07', '2020-09-29 19:47:27', '2020-09-29 19:47:27'),
('dc5b8ee1-88d1-45db-823b-da74c97436c6', '577a19a8-8b60-416b-b933-0db5f5e07c5b', '2020-10-07', '2020-09-29 19:47:27', '2020-09-29 19:47:27'),
('68458d18-161b-4246-b890-dab88e4eb28f', 'dbc0a797-9e5f-4f5a-8324-e20f175964ef', '2020-10-07', '2020-09-29 19:47:27', '2020-09-29 19:47:27');

-- --------------------------------------------------------

--
-- Struktur dari tabel `base_kategoris`
--

CREATE TABLE `base_kategoris` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kategori` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `base_kategoris`
--

INSERT INTO `base_kategoris` (`id`, `kategori`, `created_at`, `updated_at`) VALUES
('1', 'Sayur', NULL, NULL),
('2', 'Buah', NULL, NULL),
('3', 'Beras', NULL, NULL),
('4', 'Daging dan Telor', NULL, NULL),
('5', 'Makanan Sehat', NULL, NULL),
('6', 'Minuman Sehat', NULL, NULL),
('7', 'Berkebun', NULL, NULL),
('8', 'Lain - Lain', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `bobot_kemasans`
--

CREATE TABLE `bobot_kemasans` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bobot_kemasan` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `bobot_kemasans`
--

INSERT INTO `bobot_kemasans` (`id`, `bobot_kemasan`, `created_at`, `updated_at`) VALUES
('1', 100, NULL, NULL),
('2', 200, NULL, NULL),
('3', 250, NULL, NULL),
('4', 300, NULL, NULL),
('5', 400, NULL, NULL),
('6', 500, NULL, NULL),
('7', 1000, NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_keranjangs`
--

CREATE TABLE `detail_keranjangs` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_keranjang` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_barang` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `volume` int(11) NOT NULL,
  `harga` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `bobot_kemasan` int(11) DEFAULT NULL,
  `harga_diskon` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_klaims`
--

CREATE TABLE `detail_klaims` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_klaim` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_barang` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `volume_klaim` int(11) NOT NULL,
  `keterangan` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `foto_bukti` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_detail_transaksi` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_transaksis`
--

CREATE TABLE `detail_transaksis` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_barang` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `harga` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `is_info_petani` tinyint(1) NOT NULL DEFAULT 0,
  `is_canceled_by_veggo` tinyint(1) NOT NULL DEFAULT 0,
  `bobot_kemasan` int(11) DEFAULT 1,
  `volume` int(11) NOT NULL,
  `volume_selisih` int(11) DEFAULT NULL,
  `volume_kirim_petani` int(11) DEFAULT NULL,
  `volume_terima` int(11) DEFAULT NULL,
  `keterangan` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `id_transaksi` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `volume_kirim_kurir` int(11) DEFAULT NULL,
  `harga_akhir` int(11) DEFAULT NULL,
  `is_exclude_rekap` tinyint(4) DEFAULT 0,
  `bobot_selisih` int(11) DEFAULT NULL,
  `bobot_kirim_petani` int(11) DEFAULT NULL,
  `bobot_terima` int(11) DEFAULT NULL,
  `selisih_kirim` int(11) DEFAULT NULL,
  `selisih_terima` int(11) DEFAULT NULL,
  `harga_diskon` int(11) DEFAULT NULL,
  `harga_akhir_diskon` int(11) DEFAULT NULL,
  `bobot_kirim_kurir` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `foto_barangs`
--

CREATE TABLE `foto_barangs` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_barang` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `path` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `foto_barangs`
--

INSERT INTO `foto_barangs` (`id`, `id_barang`, `path`, `created_at`, `updated_at`) VALUES
('23d1c973-0918-42db-ba7f-d8bd845c4ba8', '63ef0dc2-a772-4063-bf3e-5a8d057a4df8', '1601383473LesajaLogotype.png', '2020-09-29 12:44:33', '2020-09-29 12:44:33'),
('6dd285ac-7663-47fb-8a6f-14fec854c0bb', '08e642e7-e5d0-4e8e-af6d-456dc3492654', '1601218852Screenshot 2020-09-12 205331.png', '2020-09-27 15:00:52', '2020-09-27 15:00:52'),
('9b31d4e7-3727-4d59-8765-07ab7e148cea', '577a19a8-8b60-416b-b933-0db5f5e07c5b', '1601383338LesajaLogotype.png', '2020-09-29 12:42:18', '2020-09-29 12:42:18'),
('9c5beb14-2fc1-46c9-b4e2-7bfe484700c4', 'cd84709b-ceb3-4a1a-b27f-f663cdabd5d4', '1601383576LesajaLogotype.png', '2020-09-29 12:46:16', '2020-09-29 12:46:16'),
('a97c0424-9db2-4656-b371-30565254b6a1', 'f7d48d89-8790-48eb-ba22-8da30e538455', '1601383525LesajaLogotype.png', '2020-09-29 12:45:25', '2020-09-29 12:45:25'),
('ace36351-e80a-4a35-a1b2-00cbea02816c', '112161f4-d386-4b97-9df1-8deb3e03bdb6', '1601218745LesajaLogotype.png', '2020-09-27 14:59:05', '2020-09-27 14:59:05'),
('bd95472d-2bc9-4b4b-800c-db9003959a7e', '39582314-34f8-4787-9dab-4d4a6be65fa0', '1601389387Screenshot 2020-09-12 205331.png', '2020-09-29 14:23:07', '2020-09-29 14:23:07'),
('e8608a53-fdf0-4bb0-becf-ee603d053066', 'dbc0a797-9e5f-4f5a-8324-e20f175964ef', '1601218925LesajaLogotype.png', '2020-09-27 15:02:05', '2020-09-27 15:02:05'),
('e98bea2d-135a-4fdb-a77c-ea55182a111d', 'baa6e9c9-aadc-45e5-9fde-1e0b33219960', '1601383392LesajaLogotype.png', '2020-09-29 12:43:12', '2020-09-29 12:43:12');

-- --------------------------------------------------------

--
-- Struktur dari tabel `hari_pengiriman`
--

CREATE TABLE `hari_pengiriman` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_hari` varchar(199) DEFAULT NULL,
  `tersedia` tinyint(1) DEFAULT 1,
  `urutan` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `inventaris`
--

CREATE TABLE `inventaris` (
  `id` varchar(191) NOT NULL,
  `id_transaksi` varchar(191) NOT NULL,
  `id_barang` varchar(191) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `keterangan` varchar(250) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `inventaris`
--

INSERT INTO `inventaris` (`id`, `id_transaksi`, `id_barang`, `tanggal`, `status`, `keterangan`, `jumlah`, `created_at`, `updated_at`) VALUES
('79ec9410-019c-11eb-a160-4d72e9b3b27b', 'tambah manual', '08e642e7-e5d0-4e8e-af6d-456dc3492654', '2020-09-28', 'IN', 'Tambah Stok', 1000, '2020-09-28 15:08:33', '2020-09-28 15:08:33'),
('ac4d41d0-00d3-11eb-b4c9-9da29c4ec224', 'tambah manual', '08e642e7-e5d0-4e8e-af6d-456dc3492654', '2020-09-27', 'IN', 'Tambah Stok', 1000, '2020-09-27 15:11:09', '2020-09-27 15:11:09'),
('fa3e9000-00d4-11eb-bc9d-dda45f6da469', 'tambah manual', '08e642e7-e5d0-4e8e-af6d-456dc3492654', '2020-09-27', 'IN', 'Tambah Stok', 1050, '2020-09-27 15:20:29', '2020-09-27 15:20:29');

-- --------------------------------------------------------

--
-- Struktur dari tabel `isi_pakets`
--

CREATE TABLE `isi_pakets` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_barang_parent` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_barang` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `volume` int(11) NOT NULL,
  `harga` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `isi_pakets`
--

INSERT INTO `isi_pakets` (`id`, `id_barang_parent`, `id_barang`, `volume`, `harga`, `created_at`, `updated_at`) VALUES
('69663f41-f69c-42c7-88f3-04ca670a556e', 'dbc0a797-9e5f-4f5a-8324-e20f175964ef', '08e642e7-e5d0-4e8e-af6d-456dc3492654', 100, 5000, '2020-09-27 15:02:05', '2020-09-27 15:02:05'),
('f8fa9da7-0aea-4e11-9f98-1ed2d3646ea1', 'dbc0a797-9e5f-4f5a-8324-e20f175964ef', '112161f4-d386-4b97-9df1-8deb3e03bdb6', 100, 500, '2020-09-27 15:02:05', '2020-09-27 15:02:05');

-- --------------------------------------------------------

--
-- Struktur dari tabel `isi_reseps`
--

CREATE TABLE `isi_reseps` (
  `id` varchar(191) NOT NULL,
  `id_parent_resep` varchar(191) DEFAULT NULL,
  `id_barang` varchar(191) DEFAULT NULL,
  `volume` float DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `kategoris`
--

CREATE TABLE `kategoris` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kategori` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sub_kategori` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `kategoris`
--

INSERT INTO `kategoris` (`id`, `kategori`, `sub_kategori`, `created_at`, `updated_at`) VALUES
('305aa35c-4c22-4cc2-bc3f-65b11c3d5a94', '5', 'Tempe', '2020-09-25 21:03:57', '2020-09-25 21:03:57'),
('3a2f99b8-566f-4880-8b9d-d13f0f3e4d57', '5', 'Mie', '2020-09-25 21:04:07', '2020-09-25 21:04:07'),
('5925d487-508b-450f-9c60-fdf7c0ba0385', '2', 'Buah Kemas', '2020-09-25 21:03:28', '2020-09-25 21:03:28'),
('acbc6531-e6e7-4368-b26a-5104fea9581f', '1', 'Sayur Kemas', '2020-09-25 21:02:27', '2020-09-25 21:02:27'),
('cfad237b-e716-4d18-82b8-0940f9d834c5', '5', 'Kue Kering', '2020-09-25 21:04:27', '2020-09-25 21:04:27'),
('ed3adc92-e534-4fb3-9a50-727b36601ea9', '1', 'Sayur Paket', '2020-09-25 20:55:49', '2020-09-25 20:55:49'),
('f49d5386-455a-46a6-bacd-2368cfa4bed1', '1', 'Sayur Timbang', '2020-09-25 21:02:38', '2020-09-25 21:02:38');

-- --------------------------------------------------------

--
-- Struktur dari tabel `keranjangs`
--

CREATE TABLE `keranjangs` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_user` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_pre_order` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `keranjang_resellers`
--

CREATE TABLE `keranjang_resellers` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_parent_keranjang` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_barang` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `volume` int(11) DEFAULT NULL,
  `harga` int(11) DEFAULT NULL,
  `bobot_kemasan` int(11) DEFAULT NULL,
  `harga_diskon` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `klaims`
--

CREATE TABLE `klaims` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode_klaim` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_transaksi` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `klaim_to` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `klaim_from` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_kirim` datetime NOT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `parent_keranjang_resellers`
--

CREATE TABLE `parent_keranjang_resellers` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_user` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_pre_order` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int(11) DEFAULT 0,
  `id_transaksi` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nohp` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `reseps`
--

CREATE TABLE `reseps` (
  `id` varchar(191) NOT NULL,
  `judul` varchar(191) DEFAULT NULL,
  `foto` varchar(191) DEFAULT NULL,
  `artikel` longtext DEFAULT NULL,
  `is_show` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `sample_database`
--

CREATE TABLE `sample_database` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sample` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tanggals`
--

CREATE TABLE `tanggals` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal` date DEFAULT NULL,
  `flag` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `tanggals`
--

INSERT INTO `tanggals` (`id`, `tanggal`, `flag`, `created_at`, `updated_at`) VALUES
('08470547-af0f-45a1-aeea-be1676638ba4', '2020-10-07', 1, '2020-09-29 19:47:05', '2020-09-29 19:47:27'),
('7c9d9399-a861-4569-9479-24c5df68c244', '2020-10-03', 1, '2020-09-29 13:26:54', '2020-09-29 13:27:16');

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksis`
--

CREATE TABLE `transaksis` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_user` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_alamat` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_kurir` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nomor_invoice` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_bayar` int(11) NOT NULL,
  `status` int(11) DEFAULT NULL,
  `is_info_petani` tinyint(1) NOT NULL DEFAULT 0,
  `is_canceled_by_veggo` tinyint(1) NOT NULL DEFAULT 0,
  `bukti_transfer` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_pre_order` date NOT NULL,
  `keterangan` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipe_transaksi` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT 'FROM_BUYER',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `isCheckout` tinyint(1) NOT NULL DEFAULT 0,
  `isAlreadyPay` tinyint(1) NOT NULL DEFAULT 0,
  `tanggal_pengiriman` timestamp NULL DEFAULT NULL,
  `tanggal_terima` timestamp NULL DEFAULT NULL,
  `nama_penerima` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `keterangan_penerima` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foto_penerima` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_bayar_akhir` int(11) DEFAULT NULL,
  `is_exclude_rekap` tinyint(4) DEFAULT 0,
  `is_confirm_finish_byuser` tinyint(4) NOT NULL DEFAULT 0,
  `id_reseller` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_diterima_reseller` int(11) DEFAULT NULL,
  `ongkir` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` int(11) NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `nomor_hp` varchar(199) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nomor_rek` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `atas_nama` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `remember_token`, `created_at`, `updated_at`, `nomor_hp`, `nomor_rek`, `bank`, `atas_nama`) VALUES
('3', 'petani 2', 'petani2@gmail.com', NULL, '$2y$10$Wvy0U5s0NcRztBBCrbt08Oq0XFKDe0RJ8m691XfW8cfQZXnZPA.kW', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('3dceb0cc-9983-470e-9e2b-67facc51175d', 'penjual', 'penjual@gmail.com', NULL, '$2y$10$Wvy0U5s0NcRztBBCrbt08Oq0XFKDe0RJ8m691XfW8cfQZXnZPA.kW', 1, NULL, '2019-11-19 09:19:36', '2020-09-16 06:54:11', NULL, '0943002527', 'BCA', 'VEGGO'),
('52539219-07c9-4383-800c-66a9dfa50157', 'petani 1', 'petani1@gmail.com', NULL, '$2y$10$Wvy0U5s0NcRztBBCrbt08Oq0XFKDe0RJ8m691XfW8cfQZXnZPA.kW', 3, NULL, '2019-11-19 09:20:52', '2019-11-19 09:20:52', NULL, NULL, NULL, NULL),
('75e884dc-9848-423f-8056-7dec9229d15b', 'Pembeli 2', 'pembeli2@gmail.com', NULL, '$2y$10$Wvy0U5s0NcRztBBCrbt08Oq0XFKDe0RJ8m691XfW8cfQZXnZPA.kW', 2, NULL, '2020-08-04 00:26:46', '2020-08-04 00:26:46', '086425163527', NULL, NULL, NULL),
('b0e0e59c-d033-4834-a80c-5399784de2b4', 'Pembeli 1', 'pembeli1@gmail.com', NULL, '$2y$10$Wvy0U5s0NcRztBBCrbt08Oq0XFKDe0RJ8m691XfW8cfQZXnZPA.kW', 2, NULL, '2020-09-16 06:03:15', '2020-09-16 06:03:15', '083526172364', NULL, NULL, NULL),
('ca1f9059-7889-44de-9c26-960283f5b887', 'Reseller_keputih', 'resellerkeputih@gmail.com', NULL, '$2y$10$Wvy0U5s0NcRztBBCrbt08Oq0XFKDe0RJ8m691XfW8cfQZXnZPA.kW', 4, NULL, NULL, NULL, '083212454345', NULL, NULL, NULL),
('edfc818a-88df-457a-9f43-529f9ffadasd', 'Kurir', 'kurir@gmail.com', '2019-12-30 08:10:46', '$2y$10$Wvy0U5s0NcRztBBCrbt08Oq0XFKDe0RJ8m691XfW8cfQZXnZPA.kW', 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `alamats`
--
ALTER TABLE `alamats`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `barangs`
--
ALTER TABLE `barangs`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `barangs_groups`
--
ALTER TABLE `barangs_groups`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `barangs_kemasans`
--
ALTER TABLE `barangs_kemasans`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `base_kategoris`
--
ALTER TABLE `base_kategoris`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `bobot_kemasans`
--
ALTER TABLE `bobot_kemasans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bobot_kemasans_bobot_kemasan_unique` (`bobot_kemasan`);

--
-- Indeks untuk tabel `detail_keranjangs`
--
ALTER TABLE `detail_keranjangs`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `detail_klaims`
--
ALTER TABLE `detail_klaims`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `detail_transaksis`
--
ALTER TABLE `detail_transaksis`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `foto_barangs`
--
ALTER TABLE `foto_barangs`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `hari_pengiriman`
--
ALTER TABLE `hari_pengiriman`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indeks untuk tabel `inventaris`
--
ALTER TABLE `inventaris`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `isi_pakets`
--
ALTER TABLE `isi_pakets`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `isi_reseps`
--
ALTER TABLE `isi_reseps`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `kategoris`
--
ALTER TABLE `kategoris`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `keranjangs`
--
ALTER TABLE `keranjangs`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `keranjang_resellers`
--
ALTER TABLE `keranjang_resellers`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `klaims`
--
ALTER TABLE `klaims`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `parent_keranjang_resellers`
--
ALTER TABLE `parent_keranjang_resellers`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indeks untuk tabel `reseps`
--
ALTER TABLE `reseps`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `sample_database`
--
ALTER TABLE `sample_database`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tanggals`
--
ALTER TABLE `tanggals`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `transaksis`
--
ALTER TABLE `transaksis`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `hari_pengiriman`
--
ALTER TABLE `hari_pengiriman`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `sample_database`
--
ALTER TABLE `sample_database`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
