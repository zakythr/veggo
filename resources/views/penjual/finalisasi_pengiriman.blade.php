@extends('penjual.layouts.app')
@section('title','Finalisasi')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">

                    <div class="row p-3">
                        <div class="col-6">
                            <h1 class="card-title">Finalisasi Order | <strong>{{$data['transaksi']->nomor_invoice}}</strong></h1>
                        </div>
                        <div class="col-6">
                            @if($data['transaksi']->status == 4)
                            <button type="button" class="btn btn-primary btn-sm float-right dropdown-toggle" disabled>
                                Siap Kirim
                            </button> 
                            @else
                                <button id="btnGroupDrop1" type="button" class="btn btn-primary btn-sm float-right dropdown-toggle"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Simpan
                                </button> 
                                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                    <a class="dropdown-item" onclick="formSubmit()">Ya</a>
                                    <a class="dropdown-item" href="{{url('/Penjual/Pengiriman/Finalisasi')}}">Batal</a>
                                </div>                                                    
                                <button class="btn btn-outline-primary btn-sm float-right mr-1" id="tambah_btn">Tambah</button>
                                <button class="btn btn-outline-primary btn-sm float-right mr-1" id="tutup_btn" style="display:none">Tutup</button>
                            @endif
                        </div>
                    </div>

                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div id="tambah_finalisasi_div" style="display:none">
                        <form action="{{url('/Penjual/Pengiriman/Finalisasi/Tambah/Item')}}" method="POST">
                            @csrf
                            <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="display:none">id</th>
                                    <th width="30%">Produk</th>
                                    <th>Volume Beli</th>
                                    <th>Volume Kirim</th>
                                    <th>Harga</th>
                                    <th>-</th>
                                </tr>
                            </thead>
                            <tbody>
                                    <tr>
                                        <td style="display:none">
                                            <input type="number" id="temp_harga_satuan_aaa" value="">
                                            <input type="hidden" name="id_transaksi" value="{{$data['transaksi']->id}}">
                                        </td>
                                        <td>
                                            <select name="id_barang" id="produk_select_opt" class="form-control select2-single">
                                                <option value="" disabled selected>Pilih Produk</option>
                                                @foreach ($data['produk'] as $produk)
                                                    <option value="{{$produk->id}}">{{$produk->nama}} | @rupiah($produk->harga_jual/$produk->bobot)</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td><input type="number" class="form-control" id="volume_pesan_aaa" value="0" readonly></td>
                                        <td><input type="number" class="form-control" name="volume_kirim_kurir" placeholder="Volume Kirim" id="volume_kirim_kurir_aaa" value="0" onkeyup="getNewHargaAkhir('aaa')" required></td>
                                        <td><input type="text" class="form-control" name="harga_akhir" id="harga_akhir_aaa" value="0"></td>
                                        <td><button type="submit" class="btn btn-sm btn-success default"><i class="simple-icon-plus text-white"></i></button></td>
                                    </tr>
                                </tbody>
                            </table>                            
                        </form>
                    </div>
                    <div>
                        <form id="form_finalisasi" action="{{url('/Penjual/Pengiriman/Finalisasi')}}" method="POST">         
                        @csrf
                        <div class="row p-2">
                            <div class="col-md-6">
                                Nama : <strong>{{$data['transaksi']->user->name}}</strong><br>
                                Alamat : <strong>{{$data['transaksi']->alamat->alamat}}, {{$data['transaksi']->alamat->blok_nomor}}</strong><br>
                                Nomor Telepon : <strong>{{$data['transaksi']->user->nomor_hp}}</strong><br>
                                <div>Total : <strong id="totall_bayar">@rupiah($data['transaksi']->total_bayar)</strong><br></div>
                                Order Untuk : <strong>{{Carbon\Carbon::parse($data['transaksi']->tanggal_pre_order)->format('d M Y')}}</strong><br>
                                Ongkos Kirim : <strong>Rp <input name="ongkir" type="number" id="ongkir" value=0 ></strong>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Keterangan</label>
                                    @if($data['transaksi']->status == 4)
                                        <textarea name="keterangan" class="form-control" name="" id="" cols="10" rows="4" readonly>{{$data['transaksi']->keterangan}}</textarea>
                                    @else
                                        <textarea name="keterangan" class="form-control" name="" id="" cols="10" rows="4"></textarea>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <hr>
                        <input type="hidden" name="id_transaksi" value="{{$data['transaksi']->id}}">
                        <input type="hidden" id="total_bayar" name="total_harga" value="{{$data['transaksi']->total_bayar}}">
                        {{-- <input type="hidden" id="ongkirr" name="ongkir" value=0> --}}
                        <input type="hidden" id="ongkirrlast" value=0>
                        <div style="overflow-x:auto;">
                        <table  class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="display:none">id</th>
                                    <th>Produk</th>
                                    <th>Volume Beli x Bobot Beli</th>
                                    <th>Volume Kirim</th>
                                    <th style="display:none">Bobot Kirim</th>
                                    <th>Harga</th>
                                    <th>Diskon (%)</th>
                                    <th>Harga Akhir</th>
                                    <th>-</th>
                                </tr>
                            </thead>
                            <tbody>
                                    @foreach ($data['transaksi']->detailTransaksi as $key => $item)
                                        @if($item->status != 7)
                                            <tr id="row_{{$item->id}}">
                                                <td style="display:none"><input type="text" class="form-control" name="id_detail_transaksi[]" value="{{$item->id}}" readonly></td>
                                                @if($item->barang->jenis == "Kemas" && $item->barang->show_etalase == 1)
                                                    <td ><input style="width:150px;" type="text" class="form-control" value="{{$item->barang->nama}} | {{$item->volume}} x {{$item->bobot_kemasan}} {{$item->barang->satuan}}" readonly></td>
                                                @elseif($item->barang->jenis == "Timbang" && $item->barang->show_etalase == 1)
                                                    <td><input style="width:150px;" type="text" class="form-control" value="{{$item->barang->nama}} | {{$item->barang->jenis}}" readonly></td>
                                                @else
                                                    <td><input style="width:150px;" type="text" class="form-control" value="{{$item->barang->nama}}" readonly></td>
                                                @endif

                                                @if($item->barang->jenis == "Kemas")
                                                    <td><input style="width:150px;" type="text" class="form-control" id="volume_pesan_{{$key}}" value="{{$item->volume}} x {{$item->bobot_kemasan}} {{$item->barang->satuan}}" readonly></td>
                                                @else
                                                    <td><input style="width:150px;" type="text" class="form-control" id="volume_pesan_{{$key}}" value="{{$item->volume}} {{$item->barang->satuan}}" readonly></td>
                                                @endif

                                                @if($data['transaksi']->status == 4)
                                                    <td><input style="width:100px;" type="number" class="form-control" name="volume_kirim_kurir[]" placeholder="Volume Kirim" id="volume_kirim_kurir_{{$key}}" value="{{$item->volume_kirim_kurir}}" required readonly></td>
                                                @else
                                                    @if($item->barang->jenis == "Kemas")
                                                    <td><input style="width:100px;" type="number" class="form-control" name="volume_kirim_kurir[]" placeholder="Volume Kirim" id="volume_kirim_kurir_{{$key}}" value="{{$item->volume}}" onkeyup="getHargaAkhir({{$key}},{{$item->harga/($item->volume*$item->bobot_kemasan)}}, {{$item->barang->diskon}})" required></td>
                                                    @else
                                                    <td><input style="width:100px;" type="number" class="form-control" name="volume_kirim_kurir[]" placeholder="Volume Kirim" id="volume_kirim_kurir_{{$key}}" value="{{$item->volume}}" onkeyup="getHargaAkhir({{$key}},{{$item->harga/($item->volume)}}, {{$item->barang->diskon}})" required></td>
                                                    @endif
                                                @endif

                                                @if($data['transaksi']->status == 4)
                                                    <td style="display:none"><input type="number" class="form-control" name="bobot_kirim_kurir[]" placeholder="Bobot Kirim" id="bobot_kirim_kurir_{{$key}}" value="{{$item->bobot_kirim_kurir}}" readonly required></td>
                                                @else
                                                    @if($item->barang->jenis == "Kemas")
                                                        <td style="display:none"><input type="number" class="form-control" name="bobot_kirim_kurir[]" placeholder="Bobot Kirim" id="bobot_kirim_kurir_{{$key}}" value="{{$item->bobot_kemasan}}" onkeyup="getHargaAkhir({{$key}},{{$item->harga/($item->volume*$item->bobot_kemasan)}}, {{$item->barang->diskon}})" required></td>
                                                    @else
                                                        <td style="display:none"><input type="number" class="form-control" name="bobot_kirim_kurir[]" id="bobot_kirim_kurir_{{$key}}" value="1" readonly></td>
                                                    @endif
                                                @endif
                                                <td><input style="width:100px;" type="text" class="form-control" name="harga_akhir[]" id="harga_akhir_{{$key}}" value="{{$item->harga}}" readonly></td>
                                                @if($item->barang->diskon<101)
                                                    <td><input style="width:100px;" type="number" class="form-control" name="diskon[]" id="diskon_{{$key}}" value="{{$item->barang->diskon}}" readonly></td>
                                                    <td><input style="width:100px;" type="number" class="form-control" name="harga_akhir_diskon[]" id="harga_akhir_diskon_{{$key}}" value="{{$item->harga_diskon}}" readonly></td>
                                                @else
                                                    <td><input style="width:100px;" type="number" class="form-control" name="diskon[]" id="diskon_{{$key}}" value="{{number_format(($item->barang->diskon/$item->barang->harga_jual)*100)}}" readonly></td>
                                                    <td><input style="width:100px;" type="number" class="form-control" name="harga_akhir_diskon[]" id="harga_akhir_diskon_{{$key}}" value="{{$item->harga_diskon}}" readonly></td>
                                                @endif
                                                <td><button type="button" onclick="hapusItemFinalisasi('{{$item->id}}')" class="btn btn-sm btn-danger default"><i class="simple-icon-trash text-white"></i></button></td>
                                            </tr>                                    
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        </form>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>
<div style="display: none">
    @foreach ($data['produk'] as $item)
        <input id="produk_hidden_harga_{{$item->id}}" type="hidden" value="{{$item->harga_jual}}">
        <input id="produk_hidden_bobot_{{$item->id}}" type="hidden" value="{{$item->bobot}}">
    @endforeach

</div>
@endsection
@section('script')
<script>
    function formSubmit(){
        $('#form_finalisasi').submit()
    }

    function getNewHargaAkhir(selector){
        var volumeSelector = "#volume_kirim_kurir_"+selector
        var hargaSelector = "#harga_akhir_"+selector
        harga_satuan = $('#temp_harga_satuan_aaa').val()                    
        volume = $(volumeSelector).val()
        harga = $(hargaSelector).val()        
        harga_akhir = parseInt(harga_satuan) * parseInt(volume)
        $('#volume_pesan_aaa').val(volume)
        $(hargaSelector).val(harga_akhir)
    }

    function getHargaAkhir(selector,harga_satuan, diskon){
        // 48 57
        var volumeSelector = "#volume_kirim_kurir_"+selector
        var hargaSelector = "#harga_akhir_"+selector
        var bobotSelector = "#bobot_kirim_kurir_"+selector
        var hargaDiskonSelector = "#harga_akhir_diskon_"+selector
        var hargaTotalSelector="#total_bayar"
        var hargaTotalSelector2="#totall_bayar"

        volume = $(volumeSelector).val()
        bobot = $(bobotSelector).val()
        
        harga_sebelum=$(hargaDiskonSelector).val()
        total_bayar_sebelum=$(hargaTotalSelector).val()
        if(!$.isNumeric(volume) || !$.isNumeric(bobot)){
            volume = 0
            bobot = 0
        }

        
        // console.log(total_bayar_akhir)
                
        harga_akhir = parseInt(harga_satuan) * parseInt(volume) * parseInt(bobot)
        
        if(diskon >100){
            harga_diskon=harga_akhir-(volume*diskon)
        }
        else{
            harga_diskon=harga_akhir-(harga_akhir*(diskon/100))
        }

        console.log(harga_sebelum)
        console.log(harga_diskon)
        // console.log(totall)

        if(harga_sebelum == harga_diskon){
            total_bayar_akhir=total_bayar_sebelum
        }
        else{
            total_bayar_akhir=total_bayar_sebelum-harga_sebelum
            totall=total_bayar_akhir+harga_diskon
            total_bayar_akhirr="Rp "+totall.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")+".00"
            $(hargaTotalSelector).val(totall)
            $(hargaTotalSelector2).html(total_bayar_akhirr)
        }
        
        
        // console.log(total_bayar_akhir)
        // console.log(harga_diskon)
        // console.log(totall)
        
        // console.log(total_bayar_akhirr)

        $(hargaSelector).val(harga_akhir)
        $(hargaDiskonSelector).val(harga_diskon)
        
    }

    function getHargaAkhirOngkir(){
        var hargaTotalSelector="#total_bayar"
        var hargaTotalSelector2="#totall_bayar"
        var ongkirSelector="#ongkir"
        var ongkirSelector2="#ongkirr"
        var ongkirSelector3="#ongkirrlast"
        var x = event.which || event.keyCode;
        console.log(x)
        if((x>47 && x<58)|| x==46 || x==8){
            total_bayar=$(hargaTotalSelector).val()
            ongkirr=$(ongkirSelector).val()
            if(!$.isNumeric(ongkirr)){
                ongkirr = 0
            }
            ongkirr2=$(ongkirSelector2).val()
            $(ongkirSelector3).val(ongkirr2)
            ongkirrlast=$(ongkirSelector3).val()
                
            total_bayar= parseInt(total_bayar) - parseInt(ongkirrlast)
            total_bayar= parseInt(total_bayar) + parseInt(ongkirr)
            total_bayar_akhirr="Rp "+total_bayar.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")+".00"
            $(hargaTotalSelector).val(total_bayar)
            $(hargaTotalSelector2).html(total_bayar_akhirr)
            
            $(ongkirSelector2).val(ongkirr)
            
        }
    }

    function hapusItemFinalisasi(id_detail_transaksi){
        
        alert(id_detail_transaksi)
        row_selector = "#row_"+id_detail_transaksi;

        $.ajax({
            type:'POST',
            url:'/Penjual/Pengiriman/Finalisasi/Hapus/Item',
            data:{
                id_detail_transaksi : id_detail_transaksi,
                _token: '{{ csrf_token() }}',
            },
            success:function(data)
            {
                $(row_selector).remove()
            }
        });
        
    }

    $('#produk_select_opt').change(function(){
        idProduk = $('#produk_select_opt option:selected').val()
        harga_selector = "#produk_hidden_harga_"+idProduk
        bobot_selector = "#produk_hidden_bobot_"+idProduk

        harga_jual = $(harga_selector).val()
        bobot = $(bobot_selector).val()

        harga_per_gram = parseInt(harga_jual) / parseInt(bobot)
        console.log(harga_per_gram)
        $('#temp_harga_satuan_aaa').val(harga_per_gram)

    });

    $('#tambah_btn').click(function(){
        $('#tambah_finalisasi_div').show(400)
        $('#tambah_btn').hide()
        $('#tutup_btn').show()
    })

    $('#tutup_btn').click(function(){
        $('#tambah_finalisasi_div').hide(400)
        $('#tutup_btn').hide()
        $('#tambah_btn').show()
    })

</script>
@endsection
