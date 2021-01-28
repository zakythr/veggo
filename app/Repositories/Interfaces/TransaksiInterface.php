<?php

namespace App\Repositories\Interfaces;

interface TransaksiInterface
{
    public function updateTransaksi($id_transaksi, $status);
    public function konfirmasiDiterima($id_transaksi, $nama_penerima, $foto_penerima, $keterangan_penerima, $tanggal_terima);
    public function halamanCheckout($data);
    public function checkTransaksi($date);
    public function updateHalamanCheckout($data);
    public function update($id,$data);
    
    public function all();
    
    public function findByTanggalPreOrder($date);
    public function findByTanggalPreOrderAndTipeTransaksi($date,$tipe);
    public function findByIdUser($id);
    public function find($id);
    public function findByTipeTransaksi($tipe);

    public function getTransactionDate();

    public function orderKePetani($data);
    public function updateStatusOrderKePetani($id,$flag);
    public function getTransaksiPengiriman($date);
    public function updateKurirPengiriman($id,$kurir,$reseller,$flag);
    public function findByTipeTransaksiAndTanggalPreOrderAndStatusOrIsAlreadyPay($tipe,$tanggal,$status,$ispaid);
    public function findByTanggalPreOrderAndStatusAndTipeTransakis($date,$status,$tipe);
    public function updateTanggalTerima($id,$date);
    public function updateTanggalPengiriman($id,$date);
}



?>