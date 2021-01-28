@extends('penjual.layouts.app')
@section('title','Home')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <form action="{{url('/Penjual/Paket/Tambah')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6">
                                        <h3>Produk Paket</h3>
                                    </div>
                                    <div class="col-6" align="right" style="color: white;">
                                        <a class="btn btn-sm btn-success" onclick="tambah_item()">Tambah</a>
                                        <a class="btn btn-sm btn-danger" onclick="hapus_item()">Kurang</a>
                                    </div>
                                </div>
                                <hr>
                                <div class="alert alert-danger" id="kosong_item_paket_etalase">
                                    Isi Paket Tidak Boleh Kosong
                                </div>
                                <div id="item_paket_etalase">
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
                                    <input class="form-control" placeholder="Nama Paket" name="nama" type="text">
                                </div>
                                <div class="form-group">
                                    <select class="form-control select2-single" name="id_user" required>
                                        <option selected disabled>Supplier</option>
                                        @foreach ($data['supplier'] as $supplier)
                                            <option value="{{$supplier->id}}">{{$supplier->name}}</option>       
                                        @endforeach
                                    </select>  
                                    
                                </div>
                                <div class="form-group">
                                    <select class="form-control select2-single" name="id_kategori" id="" required>
                                        <option selected disabled>Kategori</option>
                                        @foreach ($data['kategori'] as $kategori)
                                        <option value="{{$kategori->id}}">{{$kategori->kategori}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">Group Etalase</label>
                                    <select class="form-control select2-multiple" multiple="multiple" name="group[]" id="" required>
                                        @foreach ($data['subKategori'] as $subkategori)
                                        <option value="{{$subkategori->id}}">{{$subkategori->sub_kategori}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                        <input type="file" placeholder="foto" name="foto_barang[]" class="form-control" multiple accept='image/*' required>
                                        <small class="text-danger">Maksimal 5 Foto!</small>
                                </div>
                                
                                <div class="form-group">
                                    <label for="">Pilihan Diskon</label>
                                    <select class="form-control select2-single" name="jenis_diskon" id="jenis">
                                        <option value="-">-</option>
                                        <option value="Potongan Nominal">Potongan Nominal</option>
                                        <option value="Potongan Persen">Potongan Persen</option>
                                    </select>
                                </div>                           
                                <div class="form-group">
                                    <label for="">Diskon</label>
                                    <input class="form-control" id="diskon" placeholder="Diskon" type="text" name="diskon" value="0" >
                                </div>  
                                <div class="form-group">
                                    <label for="">Pilihan Diskon Reseller</label>
                                    <select class="form-control select2-single" name="jenis_diskon_reseller" id="jenis">
                                        <option value="-">-</option>
                                        <option value="Potongan Nominal">Potongan Nominal</option>
                                        <option value="Potongan Persen">Potongan Persen</option>
                                    </select>
                                </div>                           
                                <div class="form-group">
                                    <label for="">Diskon Reseller</label>
                                    <input class="form-control" id="diskon" placeholder="Diskon" type="text" name="diskon_reseller" value="0" >
                                </div>  
                                <div class="form-group">
                                    <input class="form-control" id="harga" placeholder="Harga Jual" name="harga_jual" type="text" value="0" required>
                                </div>                                                              
                                <div class="form-group">
                                    <button id="submit_btn" type="submit" disabled class="btn btn-sm btn-success float-right">Simpan</button>
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
        $("#paket_item_"+jumlah).remove();
        jumlah--;
        if(jumlah > 0){
            $("#kosong_item_paket_etalase").hide();
        }
        else if(jumlah == 0){
            $("#submit_btn").attr("disabled", true);
            $("#kosong_item_paket_etalase").show();
        }
        console.log(jumlah);
    }
    // getDiskon(){
    //     harga='#harga';
    //     diskon='#diskon';
    //     jenis='#jenis';

    //     jenis=$(jenis).val();
    //     diskon=$(diskon).val();
    //     harga=$(harga).val();

    //     if(jenis==1){
    //         hargaa=
    //     }


    // }

    function getTotal(index, mode){
        // mode : 0 = pilih dropdown
        // mode : 1 = ganti volume

        select_ke = '#select_ke_'+index;
        volume_ke = '#volume_ke_'+index;
        harga_ke = '#harga_ke_'+index;
        harga_total = '#harga_total';
        volume_total = '#volume_total';
        harga='#harga';

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

        var sum = 0;
        $('.volume_akhir').each(function(){
            sum += parseInt(this.value);
            $(volume_total).val(sum)
        });

        var sum = 0;
        $('.harga_akhir').each(function(){
            sum += parseInt(this.value);
            $(harga_total).val(sum)
            $(harga).val(sum)
        });

    }
    
    
</script> 
@endsection