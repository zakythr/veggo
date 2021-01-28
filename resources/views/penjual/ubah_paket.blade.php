@extends('penjual.layouts.app')
@section('title','Ubah Paket')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <form action="{{url('/Penjual/Paket/Ubah')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="harga_jual_reseller" value="{{$data['paket']->harga_jual_reseller}}">
                <input type="hidden" name="id" value="{{$data['paket']->id}}">
                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6">
                                        <h3>Ubah Paket</h3>
                                    </div>
                                    <div class="col-6" align="right" style="color: white;">
                                        <a class="btn btn-sm btn-info" onclick="tambah_item()">Tambah</a>
                                        <a class="btn btn-sm btn-danger" onclick="hapus_item()">Kurang</a>
                                    </div>
                                </div>
                                <hr>
                                <div class="alert alert-danger" style="display:none" id="kosong_item_paket_etalase">
                                    Isi Paket Tidak Boleh Kosong
                                </div>
                                <div id="item_paket_etalase">
                                    @foreach ($data['isiPaket'] as $key => $paket)
                                        <div class="form-row pakets" id="paket_item_{{$key}}">
                                            <div class="form-group col-md-4">
                                            <select id="select_ke_{{$key}}" name="isi_paket[]" class="form-control" onchange="getTotal({{$key}},0)">
                                                    <option selected disabled>Pilih Produk</option>
                                                    @foreach($data['produk'] as $produk)
                                                        @if($paket->id_barang == $produk->id)
                                                            <option value="{{$produk->id}}" selected>{{$produk->nama}}</option>
                                                        @else
                                                            <option value="{{$produk->id}}">{{$produk->nama}}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <input id="volume_ke_{{$key}}" type="number" placeholder="Volume Gram" name="volume_isi_paket[]" value="{{$paket->harga}}" onkeyup="getTotal({{$key}},1)" class="form-control volume_akhir">
                                            </div>
                                            <div class="form-group col-md-4">
                                                <input id="harga_ke_{{$key}}" type="number" readonly placeholder="Harga" name="harga_isi_paket[]" value="{{$paket->volume}}" class="form-control harga_akhir">
                                            </div>
                                        </div>                                        
                                    @endforeach
                                </div>
                                <hr>
                                <div class="form-row">
                                    <div class="form-group col-md-4 hidden">

                                    </div>
                                    <div class="form-group col-md-4">
                                        <input id="volume_total" type="number" placeholder="Volume Total" class="form-control" readonly>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <input id="harga_total" type="number" placeholder="Harga Total" name="harga_beli" class="form-control" readonly>
                                    </div>                                                                        
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group">
                                    <input class="form-control" placeholder="Nama Paket" name="nama" type="text" value="{{$data['paket']->nama}}">
                                </div>
                                <div class="form-group">
                                    <select class="form-control select2-single" name="id_user" required>
                                        <option selected disabled>Supplier</option>
                                        @foreach ($data['supplier'] as $supplier)
                                            @if($supplier->id == $data['paket']->id_user)
                                                <option value="{{$supplier->id}}" selected>{{$supplier->name}}</option> 
                                            @else
                                                <option value="{{$supplier->id}}">{{$supplier->name}}</option>       
                                            @endif
                                        @endforeach
                                        @if($data['paket']->id_user==Auth::user()->id)
                                            <option value="{{Auth::user()->id}}" selected>penjual</option>       
                                        @else
                                            <option value="{{Auth::user()->id}}">penjual</option>       
                                        @endif
                                    </select>                     
                                </div>
                                <div class="form-group">
                                    <select class="form-control select2-single" name="id_kategori" id="" required>
                                        <option selected disabled>Kategori</option>
                                        @foreach ($data['kategori'] as $kategori)
                                            @if($data['paket']->id_kategori == $kategori->id)
                                                <option value="{{$kategori->id}}" selected>{{$kategori->kategori}}</option>
                                            @else
                                                <option value="{{$kategori->id}}">{{$kategori->kategori}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <select class="form-control select2-multiple" multiple="multiple" name="group[]" id="" required>
                                        @foreach ($data['subKategori'] as $subkategori)
                                            @if($subkategori->selected == 1)
                                                <option value="{{$subkategori->id}}" selected>{{$subkategori->sub_kategori}}</option>
                                            @else
                                                <option value="{{$subkategori->id}}">{{$subkategori->sub_kategori}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Harga Jual" name="harga_jual" type="text" value="{{$data['paket']->harga_jual}}" required>
                                </div>                                
                                <div class="form-group">
                                    <label for="">Pilihan Diskon</label>
                                    <select class="form-control select2-single" name="jenis_diskon" id="">
                                        <option>-</option>
                                        @if($data['paket']->jenis_diskon=="Potongan Nominal")
                                        <option selected>Potongan Nominal</option>
                                        @else
                                        <option>Potongan Nominal</option>
                                        @endif
                                        @if($data['paket']->jenis_diskon=="Potongan Persen")
                                        <option selected>Potongan Persen</option>
                                        @else
                                        <option>Potongan Persen</option>
                                        @endif
                                    </select>
                                </div>                                
                                <div class="form-group">
                                    <label for="">Diskon</label>
                                    <input class="form-control" placeholder="Diskon" type="text" name="diskon" value="{{$data['paket']->diskon}}">
                                </div>    
                                <div class="form-group">
                                    <label for="">Pilihan Diskon Reseller</label>
                                    <select class="form-control select2-single" name="jenis_diskon_reseller" id="jenis">
                                        <option>-</option>
                                        @if($data['paket']->jenis_diskon_reseller=="Potongan Nominal")
                                        <option selected>Potongan Nominal</option>
                                        @else
                                        <option>Potongan Nominal</option>
                                        @endif
                                        @if($data['paket']->jenis_diskon_reseller=="Potongan Persen")
                                        <option selected>Potongan Persen</option>
                                        @else
                                        <option>Potongan Persen</option>
                                        @endif
                                    </select>
                                </div>                           
                                <div class="form-group">
                                    <label for="">Diskon Reseller</label>
                                    <input class="form-control" id="diskon" placeholder="Diskon" type="text" name="diskon_reseller"  value="{{$data['paket']->diskon_reseller}}" >
                                </div>                              
                                <div class="form-group">
                                    <button id="submit_btn" type="submit" class="btn btn-sm btn-primary float-right">Simpan</button>
                                </div>      
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="invisible">
    @foreach ($data['produk'] as $harga_produk)
        <input type="hidden" id="harga_beli_{{$harga_produk->id}}" value="{{$harga_produk->harga_beli}}">
        <input type="hidden" id="volume_beli_{{$harga_produk->id}}" value="{{$harga_produk->bobot}}">
    @endforeach
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script type="text/javascript">
    
    var jumlah = 0;
    function setBarangEtalase()
    {
        $data = $( "#dropdown_barang option:selected" ).val();
        alert($data);
    }

    var jumlah = {!! $data['index'] !!}
    function tambah_item()
    {
        jumlah++;
        $.ajax({
            type:'GET',
            url:'/Penjual/Paket/Tambah/Item/'+jumlah,
                success:function(data,status,xhr){
                    // $("#item_paket_etalase")
                    //     .find("select")
                    //     .select2("destroy")
                    //     .end()
                    //     .append(data);
                                         
                    // $("#item_paket_etalase").find("select").select2();                    
                    $("#item_paket_etalase").append(data)
                    $("#submit_btn").attr("disabled", false);
                }
        });
        if(jumlah > 0){
            $("#kosong_item_paket_etalase").hide();
        }
        else if(jumlah == 0){
            $("#kosong_item_paket_etalase").show();
        }
        console.log(jumlah);
    }

    function hapus_item()
    {   
        if(jumlah <= 0){
            $("#submit_btn").attr("disabled", true);
            $("#kosong_item_paket_etalase").show();
        }

        if(jumlah >= 0){
            $("#paket_item_"+jumlah).remove();
            jumlah--;
        }
        hitung_total()
        console.log(jumlah);
    }

    function getTotal(index, mode){
        // mode : 0 = pilih dropdown
        // mode : 1 = ganti volume

        select_ke = '#select_ke_'+index;
        volume_ke = '#volume_ke_'+index;
        harga_ke = '#harga_ke_'+index;
        harga_total = '#harga_total';
        volume_total = '#volume_total';

        id_barang = $(select_ke).val();

        harga_beli = $('#harga_beli_'+id_barang).val();
        volume_beli = $('#volume_beli_'+id_barang).val();    

        if(mode == 0){
            volume_barang = $(volume_ke).val(100);        
            harga_barang = $(harga_ke).val((harga_beli/volume_beli)*100);    
        }else if(mode == 1){

            harga_per_gram = harga_beli/volume_beli;
            volume_barang = $(volume_ke).val();        
            harga_barang = $(harga_ke).val(harga_per_gram * volume_barang);

        }

        hitung_total()

    }

    function hitung_total(){
        var sum = 0;
        $('.volume_akhir').each(function(){
            sum += parseInt(this.value);
            $(volume_total).val(sum)
        });

        var sum = 0;
        $('.harga_akhir').each(function(){
            sum += parseInt(this.value);
            $(harga_total).val(sum)
        });
    }

    $( document ).ready(function() {
        hitung_total()
    });
    
    
    
</script> 
@endsection