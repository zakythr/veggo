@extends('penjual.layouts.app')
@section('title','Tambah Inventaris')
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
                            <h1 class="card-title"><strong>Tambah Inventaris</strong></h1>
                        </div>
                        <div class="col-md-4">
                            <label for="">Supplier</label>
                            <select class="form-control select2-single" name="id_user" required>
                                <option value="{{Auth::user()->id}}">Veggo</option>

                            </select>                     
                        </div>
                    </div>

                    <hr>
                    <br>
                    
                    
                        @csrf
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
                            <div class="col-md-4 mb-3">
                                <label for="">Jenis</label>
                                <select id="jenis" class="form-control select2-single" name="jenis" required>
                                    <option label="&nbsp;" disabled selected>&nbsp;</option>
                                    @foreach ($data['jenis_value'] as $jenis_value)
                                        <option value="{{$jenis_value}}">{{$jenis_value}}</option>
                                    @endforeach
                                </select>                                
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="">Satuan</label>
                                <select class="form-control select2-single" name="satuan" required>
                                    @foreach ($data['satuan_value'] as $satuan_value)
                                        <option value="{{$satuan_value}}">{{$satuan_value}}</option>
                                    @endforeach
                                </select>                     
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="">Bobot</label>
                                <input type="number" placeholder="Bobot" value="1000" name="bobot" class="form-control" required>
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
                            <div class="col-md-4 mb-3">
                                <label for="">Foto Barang</label>
                                <input type="file" placeholder="foto" name="foto_barang[]" class="form-control" multiple accept='image/*' required>
                                <small class="text-danger">Maksimal 5 Foto!</small>
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
                            <div class="col-md-4 mb-3">
                                <label for="">Deskripsi</label>
                                <input type="text" placeholder="Deskripsi" name="deskripsi" class="form-control" required>
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