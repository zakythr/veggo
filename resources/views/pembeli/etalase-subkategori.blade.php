@extends('pembeli.layouts.layout')

@section('title')
    Jual Sayur Online Surabaya
@endsection

@section('css')
    <style>
        .loading {
            border: 6px solid #ccc;
            border-right-color: #888;
            border-radius: 22px;
            -webkit-animation: rotate 1s infinite linear;
        }

        @-webkit-keyframes rotate {
            100% {
                -webkit-transform: rotate(360deg);
            }
        }
    </style>
    <style type="text/css">
        body {
            font-family: 'Varela Round', sans-serif;
        }
        .modal-confirm {		
            color: #434e65;
            width: 525px;
        }
        .modal-confirm .modal-content {
            padding: 20px;
            font-size: 16px;
            border-radius: 5px;
            border: none;
        }
        .modal-confirm .modal-header {
            background: #47c9a2;
            border-bottom: none;   
            position: relative;
            text-align: center;
            margin: -20px -20px 0;
            border-radius: 5px 5px 0 0;
            padding: 35px;
        }
        .modal-confirm h4 {
            text-align: center;
            font-size: 36px;
            margin: 10px 0;
        }
        .modal-confirm .form-control, .modal-confirm .btn {
            min-height: 40px;
            border-radius: 3px; 
        }
        .modal-confirm .close {
            position: absolute;
            top: 15px;
            right: 15px;
            color: #fff;
            text-shadow: none;
            opacity: 0.5;
        }
        .modal-confirm .close:hover {
            opacity: 0.8;
        }
        .modal-confirm .icon-box {
            color: #fff;		
            width: 95px;
            height: 95px;
            display: inline-block;
            border-radius: 50%;
            z-index: 9;
            border: 5px solid #fff;
            padding: 15px;
            text-align: center;
        }
        .modal-confirm .icon-box i {
            font-size: 64px;
            margin: -4px 0 0 -4px;
        }
        .modal-confirm.modal-dialog {
            margin-top: 80px;
        }
        .modal-confirm .btn {
            color: #fff;
            border-radius: 4px;
            text-decoration: none;
            transition: all 0.4s;
            line-height: normal;
            border-radius: 30px;
            margin-top: 10px;
            padding: 6px 20px;
            border: none;
        }
        .modal-confirm .btn:hover, .modal-confirm .btn:focus {
            outline: none;
        }
        .modal-confirm .btn span {
            margin: 1px 3px 0;
            float: left;
        }
        .modal-confirm .btn i {
            margin-left: 1px;
            font-size: 20px;
            float: right;
        }
        .trigger-btn {
            display: inline-block;
            margin: 100px auto;
        }
    </style>
@endsection

@section('content')
<div class="container-fluid disable-text-selection">
    <div class="row">
        <div class="col-12">
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                    <select onChange="window.location.href=this.value" id="inputState" class="form-control" name="tanggal_pre_order">
                        <option value="kosong" disabled selected>Pilih Tanggal</option>
                        @foreach($data['tanggal_pengiriman'] as $pengiriman)
                        @if ($data['tanggal']==$pengiriman->tanggal_value)
                        <option value="{{url('Pembeli/Etalase/LihatProduks/'.$data['nama_produk'].'/'.$pengiriman->tanggal_value)}}" selected>{{$pengiriman->tanggal}}</option>    
                        @else
                        <option value="{{url('Pembeli/Etalase/LihatProduks/'.$data['nama_produk'].'/'.$pengiriman->tanggal_value)}}">{{$pengiriman->tanggal}}</option>
                        @endif
                        
                        @endforeach
                    </select>
                </div>
                </div>
            </div>
        </div>
        <input hidden id="tanggall" value="{{$data['tanggal']}}">
        <input hidden id="jumlah" value="{{count($data['barang'])}}">
        <br>
    </div>
    @foreach ($data['barang'] as $key => $item)
        @if(count($item['barang'])>0)
            <div class="col-12">
                <div class="mb-2">
                    <h1><strong>Produk {{ $data['nama_produk'] }}</strong></h1>
                </div>
            </div>
            @php
                $data['flag']=1;
            @endphp
            @break
        @endif
    @endforeach
    @if($data['flag']==1)
    @foreach ($data['barang'] as $key => $item)
    @if(count($item['barang'])>0)
    <div class="col-12">
        <div class="row">
            <div class="col-12">
                <div class="mb-2">
                    <h3>{{$item['subkategori']->sub_kategori}}</h3>
                </div>
                <div class="separator mb-5"></div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-12 list" data-check-all="checkAll">
                @foreach($item['barang'] as $keyy => $barang)
                
                <div class="contents_{{$key}}">
                    <div class="card d-flex flex-row mb-3">
                        <a class="d-flex" href="{{url('Pembeli/Etalase/Detail/'.$barang->id)}}">
                            <img src="{{ asset('img/foto_barang/'.$barang->path) }}" alt="{{$barang->nama}}" class="list-thumbnail responsive" />
                        </a>
                        <div class="pl-2 d-flex flex-grow-1 min-width-zero">
                            <div class="card-body align-self-center d-flex flex-column flex-lg-row justify-content-between min-width-zero align-items-lg-center">
                                <a href="{{url('Pembeli/Etalase/Detail/'.$barang->id_barang)}}" class="w-40 w-sm-100">
                                    <p class="list-item-heading mb-1 truncate">{{$barang->nama}}</p>
                                </a>
                                <p class="mb-1 text-small w-15 w-sm-100"><strong>{{$barang->jenis}}</strong></p>
                                <p class="mb-1 text-small w-15 w-sm-100">
                                    @if($barang->jenis=='Paket')
                                        @if($barang->diskon>0)
                                            @if($barang->diskon>100)
                                            
                                                <span style="font-size: 0.7rem;background-color:red;color: white;padding: 0px 2px;font-weight: bold;">{{ number_format(($barang->diskon/$barang->harga_jual)*100) }}% off</span>
                                                <span style="font-size: 0.7rem;" class="mr-2 price-dc"><strike>Rp. {{ number_format($barang->harga_jual,0,',','.') }},-</strike></span><br><br>
                                                <b>Rp. {{ number_format(($barang->harga_jual-$barang->diskon),0,',','.') }},-</b> 
                                                / {{ $barang->bobot }} {{ $barang->satuan }}
                                            
                                            @else
                                            
                                                <span style="font-size: 0.7rem;background-color:red;color: white;padding: 0px 2px;font-weight: bold;">{{$barang->diskon}}% off</span>
                                                <span style="font-size: 0.7rem;" class="mr-2 price-dc"><strike>Rp. {{ number_format($barang->harga_jual,0,',','.') }},-</strike></span><br><br>
                                                <b>Rp. {{ number_format(($barang->harga_jual-($barang->harga_jual*($barang->diskon/100))),0,',','.') }},-</b> 
                                                / {{ $barang->bobot }} {{ $barang->satuan }}
                                            
                                            @endif
                                        @else
                                        Rp. {{ number_format($barang->harga_jual,0,',','.') }},-</b> / {{ $barang->bobot }} {{ $barang->satuan }}
                                        @endif
                                    @else
                                        @if($barang->diskon>0)
                                        @if($barang->diskon>100)
                                                <span style="font-size: 0.7rem;background-color:red;color: white;padding: 0px 2px;font-weight: bold;">{{ number_format(($barang->diskon/$barang->harga_jual)*100) }}% off</span>
                                                <span style="font-size: 0.7rem;" class="mr-2 price-dc"><strike>Rp. {{ number_format($barang->harga_jual/10,0,',','.') }},-</strike></span><br><br>
                                                <b>Rp. {{ number_format(($barang->harga_jual/10-$barang->diskon/10),0,',','.') }},-</b>
                                                / {{ $barang->bobot/10 }} Gram
                                                @else
                                            <span style="font-size: 0.7rem;background-color:red;color: white;padding: 0px 2px;font-weight: bold;">{{$barang->diskon}}% off</span>
                                            <span style="font-size: 0.7rem;" class="mr-2 price-dc"><strike>Rp. {{ number_format($barang->harga_jual/10,0,',','.') }},-</strike></span><br><br>
                                            <b>Rp. {{ number_format(($barang->harga_jual-($barang->harga_jual*($barang->diskon/100)))/10,0,',','.') }},-</b> 
                                            / {{ $barang->bobot/10 }} Gram
                                        @endif
                                        @else
                                        Rp. {{ number_format($barang->harga_jual/10,0,',','.') }},-</b> / {{ $barang->bobot/10 }} {{ $barang->satuan }}
                                        @endif
                                    @endif
                                </p>
                            
                            <div class="w-15 w-sm-100">
                                @if($barang->ketersediaan==0)
                                    <span style="font-size: 0.7rem; font-weight: bold;padding: 0px 2px;">
                                        Ketersediaan : Habis
                                    </span>
                                @elseif($barang->ketersediaan==1)
                                    <span style="font-size: 0.7rem; font-weight: bold;padding: 0px 2px;  ">
                                        Ketersediaan : Sedikit
                                    </span>
                                @elseif($barang->ketersediaan==2)
                                    <span style="font-size: 0.7rem; font-weight: bold; padding: 0px 2px; ">
                                        Ketersediaan : Sedang
                                    </span>
                                @elseif($barang->ketersediaan==3)
                                    <span style="font-size: 0.7rem; font-weight: bold;padding: 0px 2px; ">
                                        Ketersediaan : Banyak
                                    </span>
                                @endif
                                
                            </div>
                            <br>
                            <div class="w-15 w-sm-100">
                            @if($barang->ketersediaan != 0)
                                <button class="btn btn-xs btn-success" style="color:white;" onclick="tambahItem('{{$barang->id_barang}}')" data-toggle="modal" data-target="#itemModal">Tambah</button>                                    
                            @else
                                <button class="btn btn-xs btn-danger" style="color:white;">Habis</button>                                    
                            @endif
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
                @endforeach

                @if (count($item['barang'])>3)
                    <div style="text-align: center;">
                        <a class="btn btn-sm btn-success" href="#" id="loadMore_{{$key}}">Lihat Selanjutnya</a>
                    </div>                            
                @endif

            </div>
        </div>
    </div>
    @endif
    @endforeach
    @else
        <div align="center" class="col-12">
            <h3>Silahkan pilih tanggal terlebih dahulu</h3><br>
        </div>
    @endif
</div>

    
</div>
@endsection

@section('modal')
    @if(Auth::user())
        <a style="display:none;" id="buttonBerhasilModal" href="#berhasilModal" class="trigger-btn" data-toggle="modal">Click to Open Success Modal</a>
        <div id="berhasilModal" class="modal fade">
            <div class="modal-dialog modal-confirm">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h4>Berhasil!</h4>	
                        <p>Berhasil menambah item ke Keranjang.</p>
                        <button class="btn btn-success" data-dismiss="modal"><span>Lanjutkan Belanja</span> <i class="material-icons">&#xE5C8;</i></button>
                    </div>
                </div>
            </div>
        </div>     
        <div class="modal fade" id="itemModal" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Tambah ke Keranjang</h5>
                        <button id="tutupItemModal" type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="loader" align="center">
                            <br><br><br><br>
                            <div class="loading"></div>
                            <a>Harap Tunggu</a>
                        </div>
                        <div id="tambah_ubah_item_body">

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="itemUbahModal" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Ubah Keranjang</h5>
                        <button id="tutupItemUbahModal" type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="loader" align="center">
                            <br><br><br><br>
                            <div class="loading"></div>
                            <a>Harap Tunggu</a>
                        </div>
                        <div id="ubah_item_body">

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade shopping-cart-modal-lg" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Keranjang Belanja</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="tutup_lihat_keranjang">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="loader" align="center">
                            <br><br><br><br>
                            <div class="loading"></div>
                            <a>Harap Tunggu</a>
                        </div>
                        <div id="lihat_keranjang">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@section('script')
    @if(Auth::user())
        <script>
            $(document).ready(function(){
                var jumlah=$('#jumlah').val();
                
                for(let i=0; i<=jumlah; i++){
                    $(".contents_"+i).slice(4).hide();
                    // let a=i
                    
                    $("#loadMore_"+i).on("click", function(e){ 
                        e.preventDefault();
                        
                        $(".contents_"+i+":hidden").slice(0, 4).slideDown();
                        if($(".contents_"+i+":hidden").length == 0) {
                            $("#loadMore_"+i).hide();
                        }
                    });
                    
                }
            })

            function tambahItem(id)
            {
                var tanggall=$('#tanggall').val();
                $('#tambah_ubah_item_body').empty();
                $('.loader').show();
                $.ajax({
                    type: 'GET',
                    url: '/Pembeli/Etalase/Tambah/'+id,
                    success: function (data) {
                        $('#tambah_ubah_item_body').append(data);
                        $('.loader').hide();
                    }
                });
            }
            function submitTambahItem(id)
            {
                var total_order = $('#total_order').val();
                var volume_order = $('#volume_order:checked').val();
                var tanggall=$('#tanggall').val();

                if($('#lainnya:checked').val()!= null ){
                    if($('#lainnya:checked').val()!= ""){
                    console.log("kk")
                    var total_order = $('#lainnya:checked').val();
                    }
                }
                if($('#total_orderr:checked').val() != null){
                    console.log("yy")
                    var total_order = $('#total_orderr:checked').val();
                }

                $('#tambah_ubah_item_body').empty();
                $('.loader').show();
                $.ajax({
                    type:'POST',
                    url:'/Pembeli/Etalase/Tambah/'+id,
                    data:{
                        total_order     : total_order,
                        volume_order    : volume_order,
                        tanggal         : tanggall,
                        _token: '{{ csrf_token() }}',
                    },
                    success:function(data)
                    {
                        if(data == 1)
                        {
                            $('#tutupItemModal').click()
                            $('.loader').hide();
                            $('#buttonBerhasilModal').click()
                        }
                    }
                });
            }
            function ubahItem(id)
            {
                $('#tutup_lihat_keranjang').click();
                $('#itemUbahModal').modal('show');
                $('#ubah_item_body').empty();
                $('.loader').show();
                $.ajax({
                    type: 'GET',
                    url: '/Pembeli/Etalase/Ubah/'+id,
                    success: function (data) {
                        $('#ubah_item_body').append(data);
                        $('.loader').hide();
                    }
                });
            }
            function submitUbahItem(id)
            {
                var total_order = $('#total_order').val();
                var volume_order = $('#volume_order:checked').val();
                var tanggall=$('#tanggall').val();

                if($('#lainnya:checked').val()!= null ){
                    if($('#lainnya:checked').val()!= ""){
                    console.log("kk")
                    var total_order = $('#lainnya:checked').val();
                    }
                }
                if($('#total_orderr:checked').val() != null){
                    console.log("yy")
                    var total_order = $('#total_orderr:checked').val();
                }

                $('#ubah_item_body').empty();
                $('.loader').show();
                $.ajax({
                    type:'POST',
                    url:'/Pembeli/Etalase/Ubah/'+id,
                    data:{
                        total_order     : total_order,
                        volume_order    : volume_order,
                        tanggal         : tanggall,
                        _token: '{{ csrf_token() }}',
                    },
                    success:function(data)
                    {
                        if(data == 1)
                        {
                            $('#tutupItemUbahModal').click()
                            $('.loader').hide();
                            $('#buttonBerhasilModal').click()
                        }
                    }
                });
            }
            function hapusItem(id)
            {
                $('#tambah_ubah_item_body').empty(function(){
                    $('.loader').show();
                });
                $.ajax({
                    type: 'GET',
                    url: '/Pembeli/Etalase/Hapus/'+id,
                    success: function (data) {
                        $('.loader').hide();
                        $('#tutupItemModal').click()
                        lihatItem()
                    }
                });
            }
            function lihatItem()
            {
                var tanggall=$('#tanggall').val();
                $('#lihat_keranjang').empty();
                $('.loader').show();
                $.ajax({
                    type: 'GET',
                    url: '/Pembeli/Etalase/Lihat/'+tanggall,
                    success: function (data) {
                        $('#lihat_keranjang').append(data);
                        $('.loader').hide();
                    }
                });
            }
            function tambahButton()
            {
                var curVal = Number($("#total_order").val());
                $("#total_order").val(curVal + 1);
                $("#show_total_order").html(curVal + 1);
            }
            function kurangButton()
            {
                var curVal = Number($("#total_order").val());
                if(curVal > 1){
                    $("#total_order").val(curVal - 1);
                    $("#show_total_order").html(curVal - 1);
                }
            }
        </script>
        <script>
        </script>
    @endif
@endsection
