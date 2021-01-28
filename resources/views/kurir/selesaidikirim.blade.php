@extends('kurir.layouts.layout')

@section('title')
    Kurir
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        @if($data['transaksi'] != null)
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h1 class="card-title"><strong>Paket Yang Selesai Dikirim</strong></h1>

                    <table class="table table-responsive data-table data-table-feature">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>No Invoice</th>
                                <th>Nama Penerima</th>
                                <th>Nomor Telefon</th>
                                <th>Tanggal Pengiriman</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $a=1; ?>
                            @foreach($data['transaksi'] as $transaksi)
                            <tr>
                                <td>{{ $a }}</td>
                                <td>{{ $transaksi->nomor_invoice }}</td>
                                <td>{{ $transaksi->nama_penerima }}</td>
                                <td>{{ $transaksi->nomor_hp }}</td>
                                <td>{{ $transaksi->tanggal_pengiriman }}</td>
                                <td>
                                    @if($transaksi->is_confirm_finish_byuser==0)
                                        <a class="badge badge-info mb-1 text-white">Tunggu Konfirmasi Pembeli</a>
                                    @else
                                        <a class="badge badge-primary mb-1 text-white">Selesai</a>
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
                Belum ada Paket yang selesai dikirim :(
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
