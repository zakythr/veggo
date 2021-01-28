@extends('pembeli.layouts.layout')

@section('title')
    Detail Transaksi
@endsection

@section('css')

@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="mb-4">Detail</h5>
                    <hr>
                    <p class="text-muted text-small mb-2">Nomor Invoice</p>
                    <p class="mb-3">Rp {{number_format($detail['total'],2,',','.')}}</p>
                    <p class="text-muted text-small mb-2">Total Harga</p>
                    <p class="mb-3">Rp {{number_format($data['total'],2,',','.')}}</p>
                    <p class="text-muted text-small mb-2">Total Harga</p>
                    <p class="mb-3">Rp {{number_format($data['total'],2,',','.')}}</p>
                </div>
            </div>
            <br>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="mb-4">Isi Barang</h5>
                    <hr>
                    <div id="accordion">
                        @foreach($data['keranjangReseller'] as $transaksi)
                            <div class="border">
                                <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse{{$transaksi->id}}" aria-expanded="false" aria-controls="collapsecollapse{{$transaksi->id}}">
                                    {{$transaksi->barang->nama}}
                                </button>
                                <div id="collapse{{$transaksi->id}}" class="collapse" data-parent="#accordion" style="">
                                    <div class="p-4">
                                        <p class="text-muted text-small mb-2">Jenis</p>
                                        <p class="mb-3">
                                            <span class="badge badge-pill badge-success mb-1">{{$transaksi->barang->jenis}}</span>
                                        </p>
                                        <p class="text-muted text-small mb-2">Volume</p>
                                        <p class="mb-3">{{$transaksi->volume}}</p>
                                        <p class="text-muted text-small mb-2">Harga</p>
                                        <p class="mb-3">Rp {{number_format($transaksi->harga_diskon,2,',','.')}}</p>
                                        @if($data['dataPembeli']->id_transaksi==null)
                                        <a class="badge badge-danger mb-1 text-white" onclick="return confirm('Apakah Anda Yakin?')" href="{{url('/Reseller/Users/Delete/'.$transaksi->id_barang.'/'.$transaksi->id_parent_keranjang)}}">Hapus</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')

@endsection