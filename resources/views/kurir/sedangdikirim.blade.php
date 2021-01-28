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
                    <h1 class="card-title"><strong>Paket Sedang Dikirim</strong></h5>

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
                                    <a href="{{ url('Kurir/Paket/SedangDikirim/DetailPaket/'.$transaksi->transaksi_id) }}" class="btn btn-primary btn-xs mb-1">Detail</a>
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
                Belum ada Paket dalam proses pengiriman :(
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
                <h5 class="modal-title" id="exampleModalLabel">Konfirmasi Paket Selesai Dikirim {{ $transaksi->nomor_invoice }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <form method="POST" action="{{ url('Kurir/Paket/SedangDikirim/KonfirmasiSelesaiDikirim/'.$transaksi->transaksi_id) }}" enctype='multipart/form-data'>
                    @csrf
                    <div class="modal-body">
                        <div>
                            <div class="form-group">
                                <label>Nama Penerima</label>
                                <input type="text" class="form-control" name="nama_penerima" required>
                            </div>
                            <div class="form-group">
                                <label>Keterangan</label>
                                <input type="text" class="form-control" name="keterangan_penerima" required>
                            </div>
                            <div class="form-group">
                                <label>Tanggal Terima</label>
                                <input type="datetime-local" class="form-control" name="tanggal_terima" value="{{$data['hari_ini']}}" required>
                            </div>
                            
                            <div class="form-group">
                                <label>Foto</label>
                                <input type="file" class="form-control" name="foto_penerima">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a style="color:white;" class="btn btn-danger" data-dismiss="modal">Tutup</a>
                        <button type="submit" class="btn btn-success">Konfirmasi</button>
                    </div>
                </form>
            </div>
            </div>
        </div>  
    @endforeach
@endsection