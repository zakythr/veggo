@extends('reseller.layouts.layout')

@section('title')
    Paket Akan Dikirim
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        @if($data['transaksi'] != null)
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h1 class="card-title"><strong>Paket Yang Akan Dikirim</strong></h1>
                    <hr>
                    <table class="data-table data-table-feature">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>No Invoice</th>
                                <th>Nama Penerima</th>
                                <th>Nomor Telefon</th>
                                <th>Tanggal Pengiriman</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $a=1; ?>
                            @foreach($data['transaksi'] as $transaksi)
                            <tr>
                                <td>{{ $a }}</td>
                                <td>{{ $transaksi->nomor_invoice }}</td>
                                <td>{{ $transaksi->name }}</td>
                                <td>{{ $transaksi->nomor_hp }}</td>
                                <td>{{ $transaksi->tanggal_pengiriman }}</td>
                                <td>
                                    <a href="{{ url('Reseller/Pengiriman/Detail/'.$transaksi->transaksi_id) }}" class="btn btn-primary btn-xs mb-1">Detail</a>
                                    @if($transaksi->is_diterima_reseller==1)
                                    <a href="{{ url('Reseller/Pengiriman/KonfirmasiSedangDikirim/'.$transaksi->transaksi_id) }}" class="btn btn-success btn-xs mb-1">Konfirmasi Sampai</a>  
                                    @else
                                        @if($transaksi->status==6)
                                        <a href="{{ url('Reseller/Pengiriman/KonfirmasiSelesaiDikirim/'.$transaksi->transaksi_id) }}" class="btn btn-success btn-xs mb-1">Konfirmasi Selesai</a>  
                                        @else
                                            @if($transaksi->is_confirm_finish_byuser==0)
                                            <a href="#" class="btn btn-warning btn-xs mb-1">Tunggu Konfirmasi Buyer</a>  
                                            @else
                                            <a href="#" class="btn btn-success btn-xs mb-1">Selesai</a>  
                                            @endif
                                        @endif
                                    @endif
                                </td>
                            </tr>
                            <?php $a++; ?>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
        @else
        <div class="col-12">
            <div class="alert alert-danger">
                Belum ada Paket yang akan dikirim :(
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

