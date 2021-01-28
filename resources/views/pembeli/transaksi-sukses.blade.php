@extends('pembeli.layouts.layout_checkout')

@section('title')
    Checkout Pemesanan Berhasil
@endsection

@section('css')

@endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body" align="center">
                    <div>
                        <h5>PEMESANAN BERHASIL</h5>
                        <h1>INVOICE #{{$nomor}}</h1>
                        <h5>SILAHKAN LAKUKAN PEMBAYARAN DI TAB TRANSAKSI</h5>
                    </div>
                </div>
                <div class="card-footer" align="center">
                    <a class="btn btn-success" href="/Pembeli/Home">KEMBALI BELANJA KE ETALASE</a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection