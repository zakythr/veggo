<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Route::get('Etalase', 'Guest\EtalaseController@showBarang');

Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');
// Route::get('/nyoba', 'Pembeli\KeranjangController@getTanggalPengiriman');
Route::get('/nyoba', 'Pembeli\EtalaseController@percobaan');

Route::group(['prefix' => 'Kurir', 'middleware' => ['auth', 'kurir']], function () {
    Route::get('Home', 'Kurir\HomeController@index');

    Route::get('Paket/AkanDikirim', 'Kurir\PaketController@PaketYangAkanDikirim');
    Route::get('Paket/AkanDikirim/DetailPaket/{id_transaksi}', 'Kurir\PaketController@AkanDikirim_DetailPaket');
    Route::get('Paket/KonfirmasiSedangDikirim/{id_transaksi}', 'Kurir\PaketController@KonfirmasiDalamPengiriman');

    Route::get('Paket/SedangDikirim', 'Kurir\PaketController@PaketDalamPengiriman');
    Route::get('Paket/SedangDikirim/DetailPaket/{id_transaksi}', 'Kurir\PaketController@SedangDikirim_DetailPaket');
    Route::post('Paket/SedangDikirim/KonfirmasiSelesaiDikirim/{id_transaksi}', 'Kurir\PaketController@KonfirmasiSelesaiDikirim');

    Route::get('Paket/SelesaiDikirim', 'Kurir\PaketController@PaketSelesaiDikirim');
}); 

Route::group(['prefix' => 'Pembeli', 'middleware' => ['auth', 'pembeli']], function () {
    Route::get('Home', 'Pembeli\HomeController@index');

    Route::get('Profil', 'Pembeli\ProfilController@viewProfile');
    Route::get('Profil/Edit', 'Pembeli\ProfilController@editProfile');
    Route::post('Profil/Edit', 'Pembeli\ProfilController@_editProfile');
    
    Route::get('Etalase', 'Pembeli\EtalaseController@etalase');
    Route::get('Etalase/{date}', 'Pembeli\EtalaseController@etalaseDate');
    Route::get('Etalase/LihatProduks/{NamaProduk}', 'Pembeli\EtalaseController@etalaseByKategori');
    Route::get('Etalase/LihatProduks/{NamaProduk}/{date}', 'Pembeli\EtalaseController@etalaseByKategoriDate');
    Route::get('Etalase/LihatProduk/{NamaKategori}/{NamaSubKategori}', 'Pembeli\EtalaseController@etalaseBySubKategori');
    Route::get('Etalase/LihatProduk/{NamaKategori}/{NamaSubKategori}/{date}', 'Pembeli\EtalaseController@etalaseBySubKategoriDate');
    Route::get('Etalase/CariProduk/{nama}', 'Pembeli\EtalaseController@cariEtalase');
    Route::get('Etalase/CariProduk/{nama}/{date}', 'Pembeli\EtalaseController@cariEtalaseDate');
 
    Route::get('Etalase/Tambah/{id}', 'Pembeli\KeranjangController@showInputItemKeranjang');
    Route::get('Etalase/Ubah/{id}', 'Pembeli\KeranjangController@showUbahItemKeranjang');
    Route::post('Etalase/Tambah/{id}', 'Pembeli\KeranjangController@submitInputItemKeranjang');
    Route::post('Etalase/Ubah/{id}', 'Pembeli\KeranjangController@submitUbahItemKeranjang');
    Route::get('Etalase/Lihat/{date}', 'Pembeli\KeranjangController@lihatItemKeranjang');
    Route::get('Etalase/Hapus/{id}', 'Pembeli\KeranjangController@hapusItemKeranjang');
    Route::get('Etalase/Detail/{id_barang}', 'Pembeli\EtalaseController@detailProduk');

    Route::get('LihatAlamat/{id}', 'Pembeli\AlamatController@showAlamatbyUser');
    Route::get('TambahAlamat', 'Pembeli\AlamatController@tambahAlamat');
    Route::post('TambahAlamatSubmit', 'Pembeli\AlamatController@_tambahAlamat');
    Route::get('UbahAlamat/{id}', 'Pembeli\AlamatController@ubahAlamat');
    Route::post('UbahAlamatSubmit/{id}', 'Pembeli\AlamatController@_ubahAlamat');
    Route::get('HapusAlamat/{id}', 'Pembeli\AlamatController@hapusAlamat');

    Route::post('Checkout', 'Pembeli\TransaksiController@viewCheckout');
    Route::post('Checkout/Purchase', 'Pembeli\TransaksiController@submitCheckout');
    Route::get('Transaksi/Tipe/{tipe}', 'Pembeli\TransaksiController@showTransaksi');
    Route::post('Transaksi/Filter','Pembeli\TransaksiController@_filterTanggal');
    Route::post('Transaksi/Bayar/{id_transaksi}', 'Pembeli\TransaksiController@kirimBukti');
    Route::get('Transaksi/Detail/{id_transaksi}', 'Pembeli\TransaksiController@detailTransaksi');
    Route::get('Transaksi/Konfirmasi/{id_transaksi}', 'Pembeli\TransaksiController@konfirmasiTransaksi');
}); 

Route::group(['prefix' => 'Penjual', 'middleware' => ['auth', 'penjual']], function () {

    Route::get('Home', 'Penjual\ProdukController@index');

    Route::get('Produk','Penjual\ProdukController@listProduk');
    Route::get('Produk/Tambah','Penjual\ProdukController@tambahProduk');
    Route::post('Produk/Tambah','Penjual\ProdukController@_tambahProduk');
    Route::get('Produk/Ubah/{id}','Penjual\ProdukController@ubahProduk');
    Route::post('Produk/Ubah','Penjual\ProdukController@_ubahProduk');
    Route::post('Produk/Hapus','Penjual\ProdukController@_hapusProduk');

    Route::get('Kategori','Penjual\KategoriController@listKategori');
    Route::post('Kategori/Tambah','Penjual\KategoriController@_tambahKategori');
    Route::post('Kategori/Ubah','Penjual\KategoriController@_ubahKategori');
    Route::post('Kategori/Hapus','Penjual\KategoriController@_hapusKategori');

    Route::get('Etalase','Penjual\EtalaseController@etalase');
    Route::get('Etalase/Kelola','Penjual\EtalaseController@kelolaEtalase');
    Route::post('Etalase/Kelola','Penjual\EtalaseController@_kelolaEtalase');

    Route::get('Klaim','Penjual\KlaimController@klaim');
    Route::get('Klaim/Ubah/{id}','Penjual\KlaimController@detailKlaim');
    Route::post('Klaim/Ubah','Penjual\KlaimController@_ubahKlaim');
    Route::get('Klaim/Tambah','Penjual\KlaimController@tambahKlaim');
    Route::post('Klaim/Tambah/Filter','Penjual\KlaimController@_filterTambahKlaim');
    Route::post('Klaim/Tambah','Penjual\KlaimController@_tambahKlaim');

    Route::get('Paket','Penjual\PaketController@listPaket');
    Route::get('Paket/Tambah','Penjual\PaketController@tambahPaket');
    Route::post('Paket/Tambah','Penjual\PaketController@_tambahPaket');
    Route::get('Paket/Tambah/Item/{jumlah}','Penjual\PaketController@getPaketItem');
    Route::get('Paket/Ubah/{id}','Penjual\PaketController@ubahPaket');
    Route::post('Paket/Ubah','Penjual\PaketController@_ubahPaket');
    
    Route::get('PreOrder/Tanggal/{date}','Penjual\PreOrderController@preOrder');
    Route::get('PreOrder/Proses/Tanggal/{date}','Penjual\PreOrderController@prosesOrder');
    Route::get('PreOrder/SiapKirim/Tanggal/{date}','Penjual\PreOrderController@siapKirim');
    Route::get('PreOrder/Selesai/Tanggal/{date}','Penjual\PreOrderController@selesai');
    Route::get('PreOrder/Batal/Tanggal/{date}','Penjual\PreOrderController@batal');

    
    Route::get('PreOrder/Tambah','Penjual\PreOrderController@tambahPreOrder');
    Route::post('PreOrder/Tambah','Penjual\PreOrderController@_tambahPreOrder');
    Route::get('PreOrder/Akumulasi','Penjual\PreOrderController@akumulasi');
    Route::get('PreOrder/Akumulasi/Rekap/{date}','Penjual\PreOrderController@rekap_akumulasi');
    Route::get('PreOrder/Akumulasi/Barang/Detail/{kode}/{date}','Penjual\PreOrderController@rekap_detail');
    Route::post('PreOrder/Akumulasi/Filter','Penjual\PreOrderController@_filterTanggal');
    Route::post('PreOrder/Akumulasi/Rekap','Penjual\PreOrderController@_rekap_akumulasi');

    Route::post('PreOrder/Akumulasi/Batalkan','Penjual\PreOrderController@_batalkanPreorder');
    Route::post('PreOrder/Akumulasi/Batalkan/Detail','Penjual\PreOrderController@_batalkanDetailPreorder');

    Route::post('PreOrder/Akumulasi/Hapus','Penjual\PreOrderController@_excludePreorder');
    Route::post('PreOrder/Akumulasi/Hapus/Detail','Penjual\PreOrderController@_excludeDetailPreorder');

    Route::post('PreOrder/Pembayaran/Update','Penjual\PreOrderController@_updatePembayaran');

    Route::get('OrderPetani','Penjual\OrderPetaniController@orderPetani');
    Route::get('OrderPetani/Tambah','Penjual\OrderPetaniController@tambahOrderPetani');
    Route::post('OrderPetani/Tambah','Penjual\OrderPetaniController@_tambahOrderPetani');
    Route::get('OrderPetani/Konfirmasi/Terima/{id}','Penjual\OrderPetaniController@konfirmasiPenerimaan');
    Route::post('OrderPetani/Konfirmasi/Terima','Penjual\OrderPetaniController@_konfirmasiPenerimaan');
    Route::get('OrderPetani/Konfirmasi/Klaim/{id}','Penjual\KlaimController@klaimOrderPetani');
    Route::post('OrderPetani/Konfirmasi/Klaim','Penjual\KlaimController@_klaimOrderPetani');
    
    
    Route::get('Pengiriman/Tanggal/{date}','Penjual\PengirimanController@pengiriman');
    Route::get('Pengiriman/Finalisasi/{id}','Penjual\PengirimanController@finalisasiPengiriman');
    Route::post('Pengiriman/Finalisasi/Tambah/Item','Penjual\PengirimanController@_tambahItemFinalisasiPengiriman');
    Route::post('Pengiriman/Finalisasi/Hapus/Item','Penjual\PengirimanController@_hapusItemFinalisasiPengiriman');
    Route::post('Pengiriman/Finalisasi','Penjual\PengirimanController@_finalisasiPengiriman');
    Route::get('Pengiriman/Tambah','Penjual\PengirimanController@tambahPengiriman');
    Route::post('Pengiriman/Tambah/Filter','Penjual\PengirimanController@_filterTambahPengiriman');
    Route::post('Pengiriman/Tambah','Penjual\PengirimanController@_tambahPengiriman');
    
    Route::get('Inventaris/Produk','Penjual\ProdukController@inventarisProduk');
    Route::get('Inventaris/Paket','Penjual\ProdukController@inventarisPaket');
    Route::get('Inventaris/Tambah','Penjual\ProdukController@tambahinventaris');
    Route::get('Inventaris/Ubah/{id}','Penjual\ProdukController@ubahinventaris');
    Route::post('Inventaris/Ubah','Penjual\ProdukController@_ubahinventaris');
    Route::post('Inventaris/Tambah','Penjual\ProdukController@_tambahinventaris');
    
    Route::get('Pembayaran/{date}','Penjual\BayarPetaniController@showAll');

    Route::get('Stok', 'Penjual\ProdukController@stok');
    Route::post('Stok', 'Penjual\ProdukController@_stok');

    // Route::get('Pengaturan/Tanggal', 'Penjual\PengaturanController@tanggal');
    Route::get('Pengaturan/Tanggal/{date}', 'Penjual\PengaturanController@tanggal');
    Route::post('Pengaturan/SubmitTanggal', 'Penjual\PengaturanController@submitTanggal');
    Route::get('Pengaturan/BukaTanggal/{date}', 'Penjual\PengaturanController@bukaTanggal');
    Route::get('Pengaturan/BukaTanggall/{date}', 'Penjual\PengaturanController@bukaTanggall');
    Route::get('Pengaturan/CloseTanggal/{date}', 'Penjual\PengaturanController@closeTanggal');
    Route::get('Pengaturan/TambahUser', 'Penjual\PengaturanController@tambahUser');
    Route::post('Pengaturan/TambahUser', 'Penjual\PengaturanController@_tambahUser');
    Route::get('Pengaturan/Rekening', 'Penjual\PengaturanController@rekening');
    Route::post('Pengaturan/Rekening', 'Penjual\PengaturanController@_rekening');

    Route::get('PembeliOffline/{id}','Penjual\PreOrderController@pembeliOffline');
    Route::get('OrderDetail/{id}','Penjual\PreOrderController@orderDetail');

    Route::get('ReportHarian/{date}','Penjual\ReportController@reportHarian');
    Route::get('ReportBulanan/{bulan}/{tahun}','Penjual\ReportController@reportBulanan');
    Route::get('ReportReseller/{date}','Penjual\ReportController@reportReseller');
    Route::get('ReportResellerDetail/{date}/{id}','Penjual\ReportController@reportResellerDetail');
}); 

Route::group(['prefix' => 'Petani', 'middleware' => ['auth', 'petani']], function () {

    Route::get('Home', 'Petani\HomeController@index');
    

    Route::get('Produk/Demand/{date}', 'Petani\HomeController@demand');
    
    Route::get('Produk/Stok', 'Petani\HomeController@stok');
    Route::post('Produk/Stok', 'Petani\HomeController@_stok');
    
    Route::get('Order', 'Petani\HomeController@order');
    Route::get('Order/Detail/{id}', 'Petani\HomeController@orderDetail');
    Route::get('Order/Konfirmasi/{id}', 'Petani\HomeController@orderKonfirmasi');
    Route::post('Order/Konfirmasi', 'Petani\HomeController@_orderKonfirmasi');

    Route::get('Klaim','Petani\KlaimController@klaim');
    Route::get('Klaim/Detail/{id}','Petani\KlaimController@detailKlaim');

    Route::get('Pembayaran/{date}','Petani\PembayaranController@showAll');

}); 

Route::group(['prefix' => 'Reseller', 'middleware' => ['auth', 'reseller']], function () {
    Route::get('Home', 'Reseller\HomeController@index');

    Route::get('Profil', 'Reseller\ProfilController@viewProfile');
    Route::get('Profil/Edit', 'Reseller\ProfilController@editProfile');
    Route::post('Profil/Edit', 'Reseller\ProfilController@_editProfile');

    Route::get('Etalase', 'Reseller\EtalaseController@etalase');
    Route::get('Etalase/{date}', 'Reseller\EtalaseController@etalaseDate');
    Route::get('Etalase/CariProduk/{nama}', 'Reseller\EtalaseController@cariEtalase');
    Route::get('Etalase/CariProduk/{nama}/{date}', 'Reseller\EtalaseController@cariEtalaseDate');

    Route::get('Etalase/Tambah/{id}', 'Reseller\KeranjangController@showInputItemKeranjang');
    Route::get('Etalase/Ubah/{id}', 'Reseller\KeranjangController@showUbahItemKeranjang');
    Route::post('Etalase/Tambah/{id}', 'Reseller\KeranjangController@submitInputItemKeranjang');
    Route::post('Etalase/Ubah/{id}', 'Reseller\KeranjangController@submitUbahItemKeranjang');
    Route::get('Etalase/Lihat/{date}', 'Reseller\KeranjangController@lihatItemKeranjang');
    Route::get('Etalase/Hapus/{id}', 'Reseller\KeranjangController@hapusItemKeranjang');
    Route::get('Etalase/Detail/{id_barang}', 'Reseller\EtalaseController@detailProduk');

    Route::post('Checkout', 'Reseller\TransaksiController@viewCheckout');
    Route::post('Checkout/Purchase', 'Reseller\TransaksiController@submitCheckout');

    Route::get('Transaksi/Tipe/{tipe}', 'Reseller\TransaksiController@showTransaksi');
    Route::post('Transaksi/Filter','Reseller\TransaksiController@_filterTanggal');
    Route::post('Transaksi/Bayar/{id_transaksi}', 'Reseller\TransaksiController@kirimBukti');
    Route::get('Transaksi/Detail/{id_transaksi}', 'Reseller\TransaksiController@detailTransaksi');
    Route::get('Transaksi/Konfirmasi/{id_transaksi}', 'Reseller\TransaksiController@konfirmasiTransaksi');

    Route::get('Pengiriman', 'Reseller\PengirimanController@show');
    Route::get('Pengiriman/KonfirmasiSedangDikirim/{id}', 'Reseller\PengirimanController@konfirmasiSampai');
    Route::get('Pengiriman/KonfirmasiSelesaiDikirim/{id}', 'Reseller\PengirimanController@konfirmasiDiterima');
    Route::get('Pengiriman/Detail/{id_transaksi}', 'Reseller\PengirimanController@detail');

    Route::post('Save', 'Reseller\KeranjangController@simpan');

    Route::get('User/{id}/{date}', 'Reseller\UsersController@show');
    Route::get('Users/Detail/{id}', 'Reseller\UsersController@detail');
    Route::get('Users/Hapus/{id}', 'Reseller\UsersController@hapus');
    Route::get('Users/Delete/{id}/{idd}', 'Reseller\UsersController@hapusBarang');

    Route::get('LihatAlamat/{id}', 'Reseller\AlamatController@showAlamatbyUser');
    Route::get('TambahAlamat', 'Reseller\AlamatController@tambahAlamat');
    Route::post('TambahAlamatSubmit', 'Reseller\AlamatController@_tambahAlamat');
    Route::get('UbahAlamat/{id}', 'Reseller\AlamatController@ubahAlamat');
    Route::post('UbahAlamatSubmit/{id}', 'Reseller\AlamatController@_ubahAlamat');
    Route::get('HapusAlamat/{id}', 'Reseller\AlamatController@hapusAlamat');

}); 