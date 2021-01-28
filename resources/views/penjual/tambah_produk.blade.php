@extends('penjual.layouts.app')
@section('title','Tambah Produk')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                       <div class="m-3">
                            @if($success = Session::get('success'))
                            <div>
                                <div class="alert alert-success">
                                    {{$success}}
                                </div>
                            </div>                        
                            @elseif($error = Session::get('error'))
                            <div>
                                <div class="alert alert-warning">
                                    {{$error}}
                                </div>
                            </div>
                            @endif
                        </div>                    
                        <form method="POST" action="{{url('/Penjual/Produk/Tambah')}}" enctype="multipart/form-data">
                    <div class="row p-3">
                        <div class="col-md-8">
                            <h1 class="card-title"><strong>Kelola Produk</strong></h5>
                        </div>
                        <div class="col-md-4">
                            <label for="">Supplier</label>
                            <select class="form-control select2-single" name="id_user" required>
                                <option label="&nbsp;" disabled selected>&nbsp;</option>
                                @foreach ($data['supplier'] as $supplier)
                                    <option value="{{$supplier->id}}">{{$supplier->name}}</option>       
                                @endforeach      
                            </select>                     
                        </div>
                    </div>

                    <hr>
                    <br>
                    
                    
                        @csrf
                        <input type="hidden" name="harga_jual_reseller" value="0">
                        <input type="hidden" name="id" value="{{Uuid::generate(4)}}">
                        <div class="form-row">
                            <div class="col-md-4 mb-3">
                                <label for="">Kode Produk</label>
                                <input type="text" class="form-control" name="kode" value="{{$data['kodebarang']}}" required readonly>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="">Nama Produk</label>
                                <input type="text" placeholder="Nama Produk" name="nama" class="form-control" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="">Kategori</label>
                                <select class="form-control select2-single" name="id_kategori" required>
                                    <option label="&nbsp;" disabled selected>&nbsp;</option>
                                    @foreach ($data['kategori_value'] as $kategori_value)
                                        <option value="{{$kategori_value->id}}">{{$kategori_value->kategori}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-2 mb-3">
                                <label for="">Jenis</label>
                                <select id="jenis" class="form-control select2-single" name="jenis" required>
                                    <option label="&nbsp;" disabled selected>&nbsp;</option>
                                    @foreach ($data['jenis_value'] as $jenis_value)
                                        <option value="{{$jenis_value}}">{{$jenis_value}}</option>
                                    @endforeach
                                </select>                                
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="">Satuan</label>
                                <select id="satuan" class="form-control select2-single" name="satuan" required>
                                    @foreach ($data['satuan_value'] as $satuan_value)
                                        <option value="{{$satuan_value}}">{{$satuan_value}}</option>
                                    @endforeach
                                </select>                     
                            </div>
                            <div class="col-md-4 mb-3" id="bobot_form">
                                <label for="" id="label_bobot">Bobot (dalam Gram)</label>
                                <input type="number" placeholder="Bobot" value="1000" name="bobot" class="form-control" required>
                            </div>
                            <div class="col-md-2 mb-3" id="pcs_form" style="display: none">
                                <label for="">Jumlah Pcs</label>
                                <input type="number" placeholder="Jumlah Pcs" value="0" name="jumlah_pcs" class="form-control" required>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="">Pilihan Diskon</label>
                                <select class="form-control select2-single" name="jenis_diskon" id="jenis_diskon">
                                    <option>-</option>
                                    <option>Potongan Nominal</option>
                                    <option>Potongan Persen</option>
                                </select>
                            </div>   
                            <div class="col-md-2 mb-3">
                                <label for="">Diskon</label>
                                <input id="diskon" type="number" placeholder="Diskon" name="diskon" class="form-control" value="0" required disabled>                          
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-4 mb-3">
                                <label for="">Harga Beli</label>
                                <input type="number" placeholder="Harga Beli" name="harga_beli" class="form-control" required>                          
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="">Harga Jual</label>
                                <input type="number" placeholder="Harga Jual" name="harga_jual" class="form-control" required>                          
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="">Pilihan Diskon Reseller</label>
                                <select class="form-control select2-single" name="jenis_diskon_reseller" id="jenis_diskon_reseller">
                                    <option>-</option>
                                    <option>Potongan Nominal</option>
                                    <option>Potongan Persen</option>
                                </select>
                            </div>   
                            <div class="col-md-2 mb-3">
                                <label for="">Diskon Reseller</label>
                                <input id="diskon_reseller" type="number" placeholder="Diskon" name="diskon_reseller" class="form-control" value="0" required disabled>                          
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-4 mb-3">
                                <div id="kemasan_form">
                                    <label for="">Kemasan (Gram)</label>
                                    <select class="form-control select2-multiple" name="bobot_kemasan[]" multiple="multiple">
                                        @foreach ($data['kemasan_value'] as $kemasan_value)
                                        <option value="{{$kemasan_value->bobot_kemasan}}">{{$kemasan_value->bobot_kemasan}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div id="timbang_form" style="display:none">
                                    <label for="">Bobot Minimal</label>
                                    <input class="form-control" type="number" name="bobot_minimum_timbang" id="">
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="">Group Etalase</label>
                                <select class="form-control select2-multiple" multiple="multiple" name="group_etalase[]" required>
                                    @foreach ($data['group'] as $group)
                                        <option value="{{$group->id}}">{{$group->sub_kategori}}</option>
                                    @endforeach
                                </select>                     
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="">Deskripsi</label>
                                <input type="text" placeholder="Deskripsi" name="deskripsi" class="form-control" required>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="">Foto Barang</label>
                                <input type="file" placeholder="foto" name="foto_barang[]" class="form-control" multiple accept='image/*' required>
                                <small class="text-danger">Maksimal 5 Foto!</small>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-sm btn-success">Simpan</button>

                    </form>

                 </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
    $('#satuan').change(function(){
        satuan = $('#satuan option:selected').val()
        if(satuan == "Pcs"){
            $('#pcs_form').show()
            $('#bobot_form').removeClass("col-md-4")
            $('#bobot_form').addClass("col-md-2")
            $("#label_bobot").text("Bobot per Pcs (dalam Gram)");
        }else if(satuan == "Gram"){
            $('#pcs_form').hide()
            $('#bobot_form').removeClass("col-md-2")
            $('#bobot_form').addClass("col-md-4")
            $("#label_bobot").text("Bobot (dalam Gram)");
        }
    })
    $('#jenis').change(function(){
        jenis = $('#jenis option:selected').val()
        if(jenis == "Timbang"){
            $('#timbang_form').show()
            $('#kemasan_form').hide()
        }else if(jenis == "Kemas"){
            $('#timbang_form').hide()
            $('#kemasan_form').show()
        }
    })
    $('#jenis_diskon').change(function(){
        jenis = $('#jenis_diskon option:selected').val()
        
        if(jenis == "-"){
            
            $("#diskon").prop('disabled', true);
        }else{
            
            $("#diskon").prop('disabled', false);
        }
    })
    $('#jenis_diskon_reseller').change(function(){
        jenis = $('#jenis_diskon_reseller option:selected').val()
        if(jenis == "-"){
            $("#diskon_reseller").prop('disabled', true);
        }else{
            $("#diskon_reseller").prop('disabled', false);
        }
    })
</script>
    
@endsection