@extends('penjual.layouts.app')
@section('title','Home')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body" style="overflow-x:auto;">                    
                    <div class="row p-3">
                        <div class="col-12">
                            <h1 class="card-title"> <strong>Kelola Etalase</strong></h1>
                        </div>
                    </div>                    
                    <hr>
                    <div id="table_placeholder">
                        <form method="POST" action="{{url('/Penjual/Pengaturan/SubmitTanggal')}}">
                            @csrf
                            <input hidden="true" type="text" name="tanggal" value="{{$data['tanggal']}}">
                            <table class="data-table data-table-scrollable">
                                <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Nama</th>
                                    <th>Harga</th>
                                    <th>Jenis</th>
                                    <th width="10%">Tampilkan</th>
                                    <th hidden="true"></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($data['barang'] as $barang)
                                    <tr>
                                        <td>{{$barang->kode}}</td>
                                        <td>{{$barang->nama}}</td>
                                        <td>{{$barang->harga_jual}}</td>
                                        <td>{{$barang->jenis}}</td>
                                        <td>
                                            <div class="custom-switch custom-switch-primary mb-2">
                                                <input class="custom-switch-input" id="switch_{{$barang->kode}}" type="checkbox" value="{{$barang->id}}" name="etalase[]" checked>
                                                <label class="custom-switch-btn" for="switch_{{$barang->kode}}"></label>
                                            </div>
                                        </td>
                                        
                                        <td hidden="true"></td>
                                    </tr>                                    
                                @endforeach
                            </tbody>
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
    {{-- <script>
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

    </script> --}}
    @endsection
