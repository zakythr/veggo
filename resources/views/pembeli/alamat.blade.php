@extends('pembeli.layouts.layout_transaksi')

@section('title')
    Alamat Akun
@endsection

@section('content')
<div class="row">
    <div class="col-12"  align="center">
        <div class="card">
            <div class="card-body">
                <h1 class="card-title"><strong>Alamat Tujuan Pengiriman</strong></h1>
        
                <table class="table">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Alamat</th>
                            <th scope="col">Blok Nomor</th>
                            <th scope="col">Kode Pos</th>
                            <th scope="col">Pilihan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($alamat['alamat'] as $key => $alamat)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td>{{$alamat->alamat}}</td>
                                <td>{{$alamat->blok_nomor}}</td>
                                <td>{{$alamat->kodepos}}</td>
                                <td>
                                    <a class="badge badge-primary mb-1 text-white" href="{{url('/Pembeli/UbahAlamat/'.$alamat->id)}}">Ubah</a>
                                    <a class="badge badge-primary mb-1 text-white" href="{{url('/Pembeli/HapusAlamat/'.$alamat->id)}}">Hapus</a>
                                </td>
                            </tr>                                   
                        @endforeach
                    </tbody>
                </table>
                <div align="center">
                    <a style="color:white;" class="badge badge-primary" href="{{url('/Pembeli/TambahAlamat')}}">Tambah Alamat</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection