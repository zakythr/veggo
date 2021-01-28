@extends('reseller.layouts.layout')

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
        <div class="row">
            <div class="col-lg-4 col-12 mb-4">
                <div class="card mb-4">
                    <img src="{{ asset('img/foto_barang/'.$data['barang']->foto_barang[0]->path) }}">

                    <div class="card-body">
                        <p class="text-muted text-small mb-2">Deskripsi</p>
                        <p class="mb-3">
                            {{ $data['barang']->deskripsi }}
                        </p>

                        <p class="text-muted text-small mb-2">Harga</p>
                        <p class="mb-3">
                            <b>Rp. {{ number_format($data['barang']->harga_jual ,0,',','.') }},-</b> / Paket
                        </p>

                        <p class="text-muted text-small mb-2">Kode</p>
                        <p class="mb-3">
                            <b>{{ $data['barang']->kode }}</b>
                        </p>

                        <p class="text-muted text-small mb-2">Jenis</p>
                        <p class="mb-3">
                            @if($data['barang']->diskon>0)
                            @if($data['barang']->diskon>100)
                                
                                    <span style="font-size: 0.7rem;background-color:red;color: white;padding: 0px 2px;font-weight: bold;">{{ number_format(( $data['barang']->diskon/ $data['barang']->harga_jual)*100) }}%</span>
                                    <span style="font-size: 0.7rem;" class="mr-2 price-dc"><strike>Rp. {{ number_format( $data['barang']->harga_jual ,0,',','.') }},-</strike></span><br>
                                    <b>Rp. {{ number_format(( $data['barang']->harga_jual - $data['barang']->diskon ),0,',','.') }},-</b> / {{  $data['barang']->bobot  }} {{  $data['barang']->satuan }}
                                
                                @else
                                
                                    <span style="font-size: 0.7rem;background-color:red;color: white;padding: 0px 2px;font-weight: bold;">{{$data['barang']->diskon}}%</span>
                                    <span style="font-size: 0.7rem;" class="mr-2 price-dc"><strike>Rp. {{ number_format($data['barang']->harga_jual ,0,',','.') }},-</strike></span><br>
                                    <b>Rp. {{ number_format(($data['barang']->harga_jual-($data['barang']->harga_jual*($data['barang']->diskon ))) ,0,',','.') }},-</b> <br> 
                                    / {{ $data['barang']->bobot  }} {{ $data['barang']->satuan }}
                                  
                                @endif
                            
                            @else
                            <b>Rp. {{ number_format($data['barang']->harga_jual ,0,',','.') }},-</b> / 100 Gram
                            @endif
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

            <div class="col-12 col-lg-8">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="mb-4">Isi Paket</h5>
                        <hr>
                        <div class="row">
                            <div class="container-fluid">
                                <div class="col-12 list">

                                    @foreach($data['isi_paket'] as $isi_paket)
                                        <div class="card d-flex flex-row mb-3" style="background-color: #f8f9fa;">
                                            <div class="d-flex flex-grow-1 min-width-zero">
                                                <div class="card-body align-self-center d-flex flex-column flex-md-row justify-content-between min-width-zero align-items-md-center">
                                                    <a class="list-item-heading mb-1 truncate w-40 w-xs-100" href="#">
                                                        {{$isi_paket->nama}}
                                                    </a>
                                                    <div class="w-15 w-xs-100 text-right">
                                                        <span class="badge badge-pill badge-secondary">{{ $isi_paket->volume }} {{ $isi_paket->satuan }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <hr>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
