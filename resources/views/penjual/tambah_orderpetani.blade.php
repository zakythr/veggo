@extends('penjual.layouts.app')
@section('title','Tambah Order Petani')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form action="{{url('/Penjual/OrderPetani/Tambah')}}" method="POST">
                        @csrf
                        <div class="row p-6">
                            <div class="col-md-4">
                                <h1 class="card-title"><strong>Tambah Order Ke Petani</strong></h1>
                            </div>
                            <div class="col-md-3">
                                <label for="">Tanggal Kirim</label>
                                <select class="form-control select2-single" name="tanggal_kirim">
                                    <option value="" selected disabled></option>
                                    @foreach ($data['tanggal_kirim'] as $item)
                                        <option value="{{$item->tanggal_value}}">{{Carbon\Carbon::parse($item->tanggal_value)->format('d M Y')}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="">Produk</label>
                                <select class="form-control select2-single" name="" id="barang_select">
                                    <option value="" selected disabled></option>
                                    @foreach ($data['barang'] as $item)
                                        <option value="{{$item->id}}">{{$item->nama}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-outline-primary m-4 btn-sm" onclick="addRow()">Tambah</button>
                            </div>
                        </div>                        
                        <div class="">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Nama Produk</th>
                                        <th>Jumlah</th>
                                        <th>Bobot (Gram)</th>
                                        <th>-</th>
                                    </tr>
                                </thead>
                                <tbody id="table_body">


                                </tbody>
                            </table>
                        </div>
                    <button class="btn btn-sm btn-primary float-right">Simpan</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
    function addRow(){
        
        rand = Math.round((new Date()).getTime() / 1000);

        html = '<tr id="barang_row_'+rand+'">'+'<td>'+
                    '<input class="form-control" type="hidden" id="id_barang_'+rand+'" name="barang[]" value="" readonly required>'+
                    '<input class="form-control" type="text" id="nama_barang_'+rand+'" value="" readonly>'+
                    '</td>'+
                    '<td><input class="form-control" name="volume[]" type="text" value=""></td>'+
                    '<td><input class="form-control" name="bobot[]" type="text" value=""></td>'+
                    '<td><button  class="btn btn-sm btn-danger default" onclick=deleteRow("'+rand+'")><i class="simple-icon-ban"></i></button></td>'+
                '</tr>'

        id_barang = $('#barang_select option:selected').val()
        nama_barang = $('#barang_select option:selected').text()
        
        $('#table_body').append(html)

        $('#id_barang_'+rand).val(id_barang)
        $('#nama_barang_'+rand).val(nama_barang)
    }

    function deleteRow(id){
        $('#barang_row_'+id).remove()
    }

</script>
    
@endsection
