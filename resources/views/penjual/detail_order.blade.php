@extends('penjual.layouts.app')
@section('title','Home')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body" style="overflow-x:auto;">
                    
                    <div class="row p-3">
                        <div class="col-6">
                            <h1 class="card-title"><strong>Detail Transaksi | {{$data['transaksi']->nomor_invoice}}</strong></h1>
                        </div>
                    </div>
                    <table class="table table-bordered p-4">
                        <tr>
                            <th>Nama</th>
                            <th>Jenis</th>
                            <th>Volume</th>
                            <th>Bobot</th>
                            <th>Harga</th>
                        </tr>
                        @foreach ($data['detail'] as $data)
                            <tr>
                                <td>{{$data->barang->nama}}</td>
                                <td>{{$data->barang->jenis}}</td>
                                @if ($data->barang->jenis=="Timbang")
                                    <td>1 </td>
                                    <td>{{$data->volume}} Gram</td>    
                                @else
                                    <td>{{$data->volume}}</td>    
                                    <td>{{$data->bobot_kemasan}} Gram</td>
                                @endif
                                
                                <td>{{number_format($data->harga_diskon,2,',','.')}}</td>
                            </tr>                                        
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
