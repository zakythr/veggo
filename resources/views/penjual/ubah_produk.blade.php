@extends('penjual.layouts.app')
@section('title','Ubah Produk')
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


                        <form method="POST" action="{{url('/Penjual/Produk/Ubah')}}" enctype="multipart/form-data">
                    <div class="row p-3">
                        <div class="col-md-8">
                            <h1 class="card-title"><strong>Ubah Produk</strong></h1>
                        </div>
                        <div class="col-md-4">
                            <label for="">Supplier</label>
                            <select class="form-control select2-single" name="id_user" required>
                                <option label="&nbsp;" disabled selected>&nbsp;</option>
                                @foreach ($data['supplier'] as $supplier)
                                    @if($supplier->id == $data['barang']->id_user)
                                        <option value="{{$supplier->id}}" selected>{{$supplier->name}}</option> 
                                    @else
                                        <option value="{{$supplier->id}}">{{$supplier->name}}</option>       
                                    @endif
                                @endforeach
                                @if($data['barang']->id_user==Auth::user()->id)
                                    <option value="{{Auth::user()->id}}" selected>penjual</option>       
                                @else
                                    <option value="{{Auth::user()->id}}">penjual</option>       
                                @endif
                            </select>                     
                        </div>
                    </div>

                    <hr>
                    <br>
                    
                    
                        @csrf
                        <input type="hidden" name="harga_jual_reseller" value="{{$data['barang']->harga_jual_reseller}}">
                        <input type="hidden" name="id" value="{{$data['barang']->id}}">
                        <div class="form-row">
                            <div class="col-md-4 mb-3">
                                <label for="">Kode Produk</label>
                                <input type="text" class="form-control" name="kode" value="{{$data['barang']->kode}}" required readonly>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="">Nama Produk</label>
                                <input type="text" placeholder="Nama Produk" name="nama" value="{{$data['barang']->nama}}" class="form-control" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="">Kategori</label>
                                <select class="form-control select2-single" name="id_kategori" required>
                                    <option label="&nbsp;" disabled selected>&nbsp;</option>
                                    @foreach ($data['kategori_value'] as $kategori_value)
                                        @if($data['barang']->id_kategori == $kategori_value->id)
                                            <option selected value="{{$kategori_value->id}}">{{$kategori_value->kategori}}</option>
                                        @else
                                            <option value="{{$kategori_value->id}}">{{$kategori_value->kategori}}</option>
                                        @endif
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
                                        @if($data['barang']->jenis == $jenis_value)
                                            <option selected value="{{$jenis_value}}">{{$jenis_value}}</option>
                                        @else
                                            <option value="{{$jenis_value}}">{{$jenis_value}}</option>
                                        @endif
                                    @endforeach
                                </select>                                
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="">Satuan</label>
                                <select class="form-control select2-single" name="satuan" id="satuan" required>
                                    @foreach ($data['satuan_value'] as $satuan_value)
                                        @if($data['barang']->satuan == $satuan_value)
                                            <option selected value="{{$satuan_value}}">{{$satuan_value}}</option>
                                        @else
                                            <option value="{{$satuan_value}}">{{$satuan_value}}</option>
                                        @endif
                                    @endforeach
                                </select>                     
                            </div>
                            <div class="col-md-4 mb-3" id="bobot_form">
                                <label for="" id="label_bobot">Bobot (dalam Gram)</label>
                                <input type="number" placeholder="Bobot" value="{{$data['barang']->bobot}}" name="bobot" class="form-control" required>
                            </div>
                            <div class="col-md-2 mb-3" id="pcs_form" style="display: none">
                                <label for="">Jumlah Pcs</label>
                                <input type="number" placeholder="Jumlah Pcs" value="{{$data['barang']->jumlah_pcs}}" name="jumlah_pcs" class="form-control" required>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="">Pilihan Diskon</label>
                                <select class="form-control select2-single" name="jenis_diskon" id="">
                                    <option>-</option>
                                    @if($data['barang']->jenis_diskon=="Potongan Nominal")
                                    <option selected>Potongan Nominal</option>
                                    @else
                                    <option>Potongan Nominal</option>
                                    @endif
                                    @if($data['barang']->jenis_diskon=="Potongan Persen")
                                    <option selected>Potongan Persen</option>
                                    @else
                                    <option>Potongan Persen</option>
                                    @endif
                                </select>
                            </div>   
                            <div class="col-md-2 mb-3">
                                <label for="">Diskon</label>
                                <input type="number" placeholder="Diskon" name="diskon" class="form-control" required value="{{$data['barang']->diskon}}">                          
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-4 mb-3">
                                <label for="">Harga Beli</label>
                                <input type="number" placeholder="Harga Beli" value="{{$data['barang']->harga_beli}}" name="harga_beli" class="form-control" required>                          
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="">Harga Jual</label>
                                <input type="number" placeholder="Harga Jual" value="{{$data['barang']->harga_jual}}" name="harga_jual" class="form-control" required>                          
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="">Pilihan Diskon Reseller</label>
                                <select class="form-control select2-single" name="jenis_diskon_reseller" id="">
                                    <option>-</option>
                                    @if($data['barang']->jenis_diskon_reseller=="Potongan Nominal")
                                    <option selected>Potongan Nominal</option>
                                    @else
                                    <option>Potongan Nominal</option>
                                    @endif
                                    @if($data['barang']->jenis_diskon_reseller=="Potongan Persen")
                                    <option selected>Potongan Persen</option>
                                    @else
                                    <option>Potongan Persen</option>
                                    @endif
                                </select>
                            </div>   
                            <div class="col-md-2 mb-3">
                                <label for="">Diskon Reseller</label>
                                <input type="number" placeholder="Diskon" name="diskon_reseller" class="form-control" required value="{{$data['barang']->diskon_reseller}}" >                          
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-4 mb-4">
                                <div id="kemasan_form">
                                    <label for="">Kemasan (Gram)</label>
                                    <select class="form-control select2-multiple" name="bobot_kemasan[]" multiple="multiple">
                                        @foreach ($data['kemasan_value'] as $kemasan_value)
                                            @if($kemasan_value->selected == 1)
                                                <option selected value="{{$kemasan_value->bobot_kemasan}}">{{$kemasan_value->bobot_kemasan}}</option>
                                            @else
                                                <option value="{{$kemasan_value->bobot_kemasan}}">{{$kemasan_value->bobot_kemasan}}</option>
                                            @endif
                                        @endforeach
                                    </select>                                
                                </div>
                                <div id="timbang_form">
                                    <label for="">Bobot Minimal</label>
                                    <input class="form-control" type="number" name="bobot_minimum_timbang" value="{{$data['barang']->bobot_minimum_timbang}}" id="">
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="">Group Etalase</label>
                                <select class="form-control select2-multiple" multiple="multiple" name="group_etalase[]" required>
                                    @foreach ($data['group'] as $group)
                                        @if($group->selected == 1)
                                            <option selected value="{{$group->id}}">{{$group->sub_kategori}}</option>
                                        @else
                                            <option value="{{$group->id}}">{{$group->sub_kategori}}</option>
                                        @endif
                                    @endforeach
                                </select>                     
                            </div>
                            <div class="col-md-2 mb-4">
                                <label for="">Deskripsi</label>
                                <input type="text" placeholder="Deskripsi" value="{{$data['barang']->deskripsi}}" name="deskripsi" class="form-control" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-sm btn-primary">Simpan</button>

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
    $( document ).ready(function() {
        jenis = "{{$data['barang']->jenis}}"
        satuan="{{$data['barang']->satuan}}"
        if(jenis == "Timbang"){
            $('#timbang_form').show()
            $('#kemasan_form').hide()
        }else if(jenis == "Kemas"){
            $('#timbang_form').hide()
            $('#kemasan_form').show()
        }

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
    });    
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
</script>
    
@endsection