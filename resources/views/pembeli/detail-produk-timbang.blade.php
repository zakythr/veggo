@extends('pembeli.layouts.layout_detail')

@section('title')
    {{ $data['barang']->nama }}
@endsection

@section('content')
    <div class="col-12 list">

        {{-- <div class="mb-2">
            <h1>{{ $data['barang']->nama }}</h1>
            <div class="text-zero top-right-button-container">
                @if($data['barang']->ketersediaan != 0)
                    <button type="button" class="btn btn-success mb-1">Tambah Keranjang</button>
                @elseif($data['barang']->ketersediaan == 0)
                    <button type="button" class="btn btn-success mb-1" disabled>Tambah Keranjang</button>
                @endif
            </div>
        </div> --}}
        <hr>
        <div class="row justify-content-md-center ">
            <div class="col-lg-4 col-12 mb-4" align="center">
                <div class="card mb-4">
                    <img src="{{ asset('img/foto_barang/'.$data['barang']->foto_barang[0]->path) }}">

                    <div class="card-body">
                        <p class="text-muted text-small mb-2">Deskripsi</p>
                        <p class="mb-3">
                            {{ $data['barang']->deskripsi }}
                        </p>

                        <p class="text-muted text-small mb-2">Harga</p>
                        <p class="mb-3">
                            @if($data['barang']->diskon>0)
                                @if($data['barang']->diskon>100)
                                
                                    <span style="font-size: 0.7rem;background-color:red;color: white;padding: 0px 2px;font-weight: bold;">{{ number_format(( $data['barang']->diskon/ $data['barang']->harga_jual)*100) }}%</span>
                                    <span style="font-size: 0.7rem;" class="mr-2 price-dc"><strike>Rp. {{ number_format( $data['barang']->harga_jual/10,0,',','.') }},-</strike></span><br>
                                    <b>Rp. {{ number_format(( $data['barang']->harga_jual/10- $data['barang']->diskon/10),0,',','.') }},-</b> / 100 Gram
                                
                                @else
                                
                                    <span style="font-size: 0.7rem;background-color:red;color: white;padding: 0px 2px;font-weight: bold;">{{$data['barang']->diskon}}%</span>
                                    <span style="font-size: 0.7rem;" class="mr-2 price-dc"><strike>Rp. {{ number_format($data['barang']->harga_jual/10,0,',','.') }},-</strike></span><br>
                                    <b>Rp. {{ number_format(($data['barang']->harga_jual-($data['barang']->harga_jual*($data['barang']->diskon/100)))/10,0,',','.') }},-</b> <br> 
                                    / 100 Gram
                                  
                                @endif
                            @else
                            <b>Rp. {{ number_format($data['barang']->harga_jual/10,0,',','.') }},-</b> / 100 Gram
                            @endif
                        </p>

                        <p class="text-muted text-small mb-2">Kode</p>
                        <p class="mb-3">
                            <b>{{ $data['barang']->kode }}</b>
                        </p>

                        <p class="text-muted text-small mb-2">Bobot Mininum Pembelian</p>
                        <p class="mb-3">
                            <span class="badge badge-pill badge-success mb-1">
                                {{ $data['barang']->bobot_minimum_timbang }} {{ $data['barang']->satuan }}
                            </span>
                        </p>

                        <p class="text-muted text-small mb-2">Jenis</p>
                        <p class="mb-3">
                            <span class="badge badge-pill badge-success mb-1">
                                {{ $data['barang']->jenis }}
                            </span>
                        </p>

                        <p class="text-muted text-small mb-2">Ketersediaan</p>
                        <p class="mb-3">
                            @if($data['barang']->ketersediaan ==  0)
                                <span class="badge badge-pill badge-danger mb-1">
                                    Habis
                                </span>
                            @elseif($data['barang']->ketersediaan ==  1)
                                <span class="badge badge-pill badge-danger mb-1">
                                    Sedikit
                                </span>
                            @elseif($data['barang']->ketersediaan ==  2)
                                <span class="badge badge-pill badge-danger mb-1">
                                    Sedang
                                </span>
                            @elseif($data['barang']->ketersediaan ==  3)
                                <span class="badge badge-pill badge-success mb-1">
                                    Banyak
                                </span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection