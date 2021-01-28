@extends('penjual.layouts.app')
@section('title','Home')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">                    
                    <div class="row p-3">
                        <div class="col-12">
                            <h1 class="card-title"><strong>Kelola Etalase</strong></h1>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Filter Kategori</label>
                                
                                <select id="filter_kategori" class="form-control select2-single" name="id_kategori">
                                    <option selected value="">Pilih Kategori</option>                                
                                    @foreach ($kategoris as $kategori)
                                        @if(session('search_kategori') == $kategori->id)
                                            <option selected value="{{$kategori->id}}">{{$kategori->kategori}}</option>                                
                                        @else
                                            <option value="{{$kategori->id}}">{{$kategori->kategori}}</option>                                
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Nama</label>
                                <input id="filter_nama" type="text" value="{{session('search_nama_barang')}}"  class="form-control">
                            </div>
                        </div>
                    </div>                    
                    <hr>
                    <div id="table_placeholder">
                        <form method="POST" action="{{url('/Penjual/Etalase/Kelola')}}">
                            @csrf
                            <table class="table table-bordered">
                                <tr>
                                    <th>Kode</th>
                                    <th>Nama</th>
                                    <th>Harga</th>
                                    <th width="10%">Tampilkan</th>
                                </tr>
                                @foreach ($data['barang'] as $barang)
                                    <tr>
                                        <td>{{$barang->kode}}</td>
                                        <td>{{$barang->nama}}</td>
                                        <td>{{$barang->harga_jual}}</td>
                                        <td>
                                            <div class="custom-switch custom-switch-primary mb-2">
                                                @if($barang->show_etalase == 1)
                                        <input class="custom-switch-input" id="switch_{{$barang->kode}}" type="checkbox" value="{{$barang->id}}" name="etalase[]" checked>
                                                @else
                                                    <input class="custom-switch-input" id="switch_{{$barang->kode}}" type="checkbox" value="{{$barang->id}}" name="etalase[]">
                                                @endif
                                                <label class="custom-switch-btn" for="switch_{{$barang->kode}}"></label>
                                            </div>
                                        </td>
                                    </tr>                                    
                                @endforeach
                            </table>
                            <button type="submit" class="btn btn-primary btn-sm float-right">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
    <script>
        $(document).ready(function(){
            $("#filter_kategori").change(function(){
                var kategori = $(this).children("option:selected").val();
                // window.location.search+='&kategori='+kategori;
                // console.log(window.location+'?&kategori='+kategori)
                url = window.location+'?&kategori='+kategori
                window.location = url;
            });        
        });

        $(document).on('keypress',function(e) {
            if(e.which == 13) {
                var nama = $("#filter_nama").val();
                url = window.location+'?&nama='+nama;
                window.location = url;

                // window.location.search+='&nama='+nama;

            }
        });        

    </script>
    @endsection
