@extends('reseller.layouts.layout')

@section('title')
    Checkout Pemesanan    
@endsection

@section('css')

@endsection

@section('content')
<form method="POST" action="{{ url('/Reseller/Checkout/Purchase') }}">
    @csrf
    <div class="form-group">
        <input hidden name="tanggal" value="{{$data['keranjang']->tanggal_pre_order}}">
    </div>
    <div class="col-12">
        <div class="alert alert-success" role="alert">
            Pembayaran dapat dilakukan setelah pemesanan.
        </div>
    </div>
    <div class="col-12">
        <br>
        <br>
        <div>                
            <div class="row">
                <div class="col-6" align="left">
                    <h3>Tanggal Pengiriman : {{ $data['keranjang']->tanggal_pre_order }}</h3>
                </div>
            </div>            
        </div>
    </div>
    <div class="col-12">
        <br>
        <br>
        <div class="card">
            <div class="card-body">
                <div class="mb-4">
                    <div class="row">
                        <div class="col-6" align="left">
                            <h2>Detail Pemesan</h2>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="mb-4">
                    <a href="#">
                        <p class="list-item-heading mb-1 color-theme-1">{{ Auth::user()->name }}</p>                        
                        <br>
                        <a class="mb-1 text-muted text-small">{{ Auth::user()->nomor_hp }}</a>
                        <br>
                        <a class="mb-4 text-small">{{ Auth::user()->email }}</a>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <br>
        <br>
        <div class="card">
            <div class="card-body">
                <div class="mb-4">
                    <div class="row">
                        <div class="col-6" align="left">
                            <h2>Alamat Pemesan</h2>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="mb-4">
                    <select class="form-control" name="alamat">
                        @foreach($data['alamat'] as $alamat)
                        <option value="{{$alamat->id}}">
                        <div>
                            <a href="#">
                                <div class="row">                                
                                        <p class="list-item-heading mb-1 color-theme-1">{{ $alamat->nama_alamat }}</p>
                                        <br>
                                        <a class="mb-4 text-small">Jalan {{ $alamat->alamat}}, {{$alamat->blok_nomor }}, {{ $alamat->kodepos }}</a>
                                        <br>
                                        <a class="mb-1 text-muted text-small">{{$alamat->daerah}}</a>
                                </div>
                            </a>
                        </div>
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <br>
        <br>
        <div class="card">
            <div class="card-body">
                <div class="mb-4">
                    <div class="row">
                        <div class="col-6" align="left">
                            <h2>Pesanan</h2>
                        </div>
                    </div>
                </div>
                <hr>
                @foreach($data['detail_keranjang'] as $keranjang)
                    @if($keranjang->jenis == 'Kemas')
                        <div class="mb-4">
                            <div class="row">
                                <div class="col-6">
                                    <a href="#">
                                        @if($keranjang->diskon>0)
                                        @if($keranjang->diskon>100)
                                        <p class="list-item-heading mb-1 color-theme-1">{{ $keranjang->nama }} ( diskon {{ "Rp " . number_format($keranjang->diskon,0,',','.') }} )</p>
                                        @else
                                        <p class="list-item-heading mb-1 color-theme-1">{{ $keranjang->nama }} ( diskon {{$keranjang->diskon}}% )</p>
                                        @endif
                                        @else
                                        <p class="list-item-heading mb-1 color-theme-1">{{ $keranjang->nama }}</p>
                                        @endif
                                        <span><b>Kemas</b></span>
                                        <br>
                                        <p class="mb-4 text-small">{{ "Rp " . number_format(($keranjang->harga_satuan/10)*($keranjang->bobot_kemasan/100),0,',','.') }} x {{ $keranjang->volume/100 }} Pcs</p>
                                    </a>
                                </div>
                                <div class="col-6" align="right">
                                    @if($keranjang->diskon>0)
                                    <h5><strike>{{ "Rp " . number_format($keranjang->harga,0,',','.') }}</strike></h5>
                                    <h5>{{ "Rp " . number_format($keranjang->harga_diskon,0,',','.') }}</h5>
                                    @else
                                    <h5>{{ "Rp " . number_format($keranjang->harga,0,',','.') }}</h5>
                                    @endif
                                </div>
                            </div>
                            <div class="separator"></div>
                        </div>
                    @elseif($keranjang->jenis == 'Paket')
                    <div class="mb-4">
                        <div class="row">
                            <div class="col-6">
                                <a href="#">
                                    @if($keranjang->diskon>0)
                                        @if($keranjang->diskon>100)
                                        <p class="list-item-heading mb-1 color-theme-1">{{ $keranjang->nama }} ( diskon {{ "Rp " . number_format(($keranjang->diskon*$keranjang->volume),0,',','.') }} )</p>
                                        @else
                                        <p class="list-item-heading mb-1 color-theme-1">{{ $keranjang->nama }} ( diskon {{$keranjang->diskon}}% )</p>
                                        @endif
                                    @else
                                    <p class="list-item-heading mb-1 color-theme-1">{{ $keranjang->nama }}</p>
                                    @endif
                                    <span><b>Paket</b></span>
                                    <br>                                      
                                    <p class="mb-4 text-small">{{ "Rp " . number_format($keranjang->harga_satuan,0,',','.') }} x {{ $keranjang->volume }} {{ $keranjang->satuan }}</p>
                                </a>
                            </div>
                            <div class="col-6" align="right">
                                @if($keranjang->diskon>0)
                                <h5><strike>{{ "Rp " . number_format($keranjang->harga,0,',','.') }}</strike></h5>
                                <h5>{{ "Rp " . number_format($keranjang->harga_diskon,0,',','.') }}</h5>
                                @else
                                <h5>{{ "Rp " . number_format($keranjang->harga,0,',','.') }}</h5>
                                @endif
                            </div>
                        </div>
                        <div class="separator"></div>
                    </div>
                    @elseif($keranjang->jenis == 'Timbang')
                    <div class="mb-4">
                        <div class="row">
                            <div class="col-6">
                                <a href="#">
                                    @if($keranjang->diskon>0)
                                    @if($keranjang->diskon>100)
                                        <p class="list-item-heading mb-1 color-theme-1">{{ $keranjang->nama }} ( diskon {{ "Rp " . number_format($keranjang->diskon,0,',','.') }} )</p>
                                        @else
                                        <p class="list-item-heading mb-1 color-theme-1">{{ $keranjang->nama }} ( diskon {{$keranjang->diskon}}% )</p>
                                        @endif
                                    @else
                                    <p class="list-item-heading mb-1 color-theme-1">{{ $keranjang->nama }}</p>
                                    @endif
                                    <span><b>Timbang</b></span>
                                    <br>
                                    <p class="mb-4 text-small">{{ "Rp " . number_format($keranjang->harga_satuan/1000,0,',','.') }} x {{ $keranjang->volume }} {{ $keranjang->satuan }}</p>
                                </a>
                            </div>
                            <div class="col-6" align="right">
                                @if($keranjang->diskon>0)
                                <h5><strike>{{ "Rp " . number_format($keranjang->harga,0,',','.') }}</strike></h5>
                                <h5>{{ "Rp " . number_format($keranjang->harga_diskon,0,',','.') }}</h5>
                                @else
                                <h5>{{ "Rp " . number_format($keranjang->harga,0,',','.') }}</h5>
                                @endif
                            </div>
                        </div>
                        <div class="separator"></div>
                    </div>
                    @endif
                @endforeach
                <div class="mb-4">
                    <div class="row">
                        <div class="col-6" align="left">
                            <h2>Estimasi Harga</h2>
                        </div>
                        <div class="col-6">
                            <div align="right">
                                <h5>Total : <b>{{ "Rp " . number_format($data['total'],0,',','.') }}</b></h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <br>
        <br>
        <div class="card">
            <div class="card-body">
                <div class="mb-4">
                    <div class="row">
                        <div class="col-6" align="left">
                            <h2>Keterangan</h2>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="mb-4">
                    <input type="text" class="form-control" placeholder="Masukan keterangan" name="keterangan">
                </div>
            </div>
        </div>
    </div>
    <br>
    <div align="center">
        <div align="center">
            @if($data['alamat']->count()>0)
            <button style="color:white;" class="btn btn-success" type="submit">Pesan</button>
            @else
            <button style="color:white;" class="btn btn-success" type="submit" disabled>Pesan</button>
            @endif
        </div>
    </div>
</form>
@endsection

@section('script')

@endsection