@extends('kurir.layouts.layout')

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

                    <table class="table table-responsive data-table data-table-feature">
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
                                    <a href="{{ url('Kurir/Paket/AkanDikirim/DetailPaket/'.$transaksi->transaksi_id) }}" class="btn btn-primary btn-xs mb-1">Detail</a>
                                    <button type="button" class="btn btn-success btn-xs mb-1" data-toggle="modal" data-target="#modal{{$transaksi->transaksi_id}}">
                                        Konfirmasi
                                    </button>    
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

@section('modal')
    @foreach($data['transaksi'] as $transaksi)
        <div class="modal fade" id="modal{{$transaksi->transaksi_id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Konfirmasi Pengiriman Paket {{ $transaksi->nomor_invoice }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    Apakah anda Yakin Untuk Menerima Pengiriman Paket {{ $transaksi->nomor_invoice }} kepada {{ $transaksi->name }}? 
                </div>
                <div class="modal-footer">
                    <a style="color:white;" class="btn btn-danger" data-dismiss="modal">Tutup</a>
                    <a href="{{ url('Kurir/Paket/KonfirmasiSedangDikirim/'.$transaksi->transaksi_id) }}" style="color:white;" class="btn btn-success">Konfirmasi</a>
                </div>
            </div>
            </div>
        </div>  
    @endforeach
@endsection