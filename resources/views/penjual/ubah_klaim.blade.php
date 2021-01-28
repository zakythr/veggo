@extends('penjual.layouts.app')
@section('title','Home')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body" style="overflow-x:auto;">

                    <div class="row p-3">
                        <div class="col-6">
                            @foreach ($data['klaims'] as $key => $klaim)
                        <h1 class="card-title">Detail Klaim | <strong> {{$klaim->transaksi->nomor_invoice}} </strong> </h1>
                        @endforeach
                        </div>
                        <div class="col-6">
                            {{-- <a href="{{url('/Penjual/Produk/Tambah')}}" class="btn btn-sm btn-primary  float-right">Tambah</a> --}}
                        </div>
                    </div>

                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    
                    <table class="table table-bordered">
                        <tr>
                            <th>No</th>
                            <th>Nama Barang</th>
                            <th>Volume x Bobot Pesan </th>
                            <th>Volume x Bobot Kirim </th>
                            <th>Volume x Bobot Terima </th>
                            <th>Volume Klaim</th>
                            <th>Keterangan</th>
                            <th>Foto Bukti</th>
                        </tr>
                            @foreach ($data['klaim'] as $key=>$klaim)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{$klaim->barang->nama}}</td>
                                    <td>{{$klaim->detail_transaksi->volume}} x {{$klaim->detail_transaksi->bobot_kemasan}}</td>
                                    <td>{{$klaim->detail_transaksi->volume_kirim_petani}} x {{$klaim->detail_transaksi->bobot_kirim_petani}}</td>
                                    <td>{{$klaim->detail_transaksi->volume_terima}} x {{$klaim->detail_transaksi->bobot_terima}}</td>
                                    <td>{{$klaim->volume_klaim}} gram</td>
                                    <td>{{$klaim->keterangan}}</td>
                                    <td><img style="width:200px; height:auto;" src="{{ asset('img/bukti_klaim/'.$klaim->foto_bukti) }}"></td>
                                </tr>                                                                            
                            @endforeach
                    </table>
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
