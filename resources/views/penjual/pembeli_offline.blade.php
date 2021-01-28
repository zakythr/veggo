@extends('penjual.layouts.app')
@section('title','Home')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    
                    <div class="row p-3">
                        <div class="col-6">
                            <h1 class="card-title"><strong>Pembeli Offline</strong></h1>
                        </div>
                    </div>
                    {{-- {{$collection}} --}}
                    @foreach ($collection as $key => $item)
                        <div class="border">
                            <button class="btn btn-link" data-toggle="collapse" data-target="#collapse_{{$key}}"
                                aria-expanded="true" aria-controls="collapse">
                                <h5>{{$item['name']}} - {{$item['alamat']}} - {{$item['nohp']}}</h5>
                            </button>

                            <div id="collapse_{{$key}}" class="collapse hide " data-parent="#accordion">
                                <table class="table table-bordered p-4">
                                    <tr>
                                        <th>Nama</th>
                                        <th>Jenis</th>
                                        <th>Volume</th>
                                        <th>Harga</th>
                                    </tr>
                                    @foreach ($item['data'] as $data)
                                        <tr>
                                            <td>{{$data->barang->nama}}</td>
                                            <td>{{$data->barang->jenis}}</td>
                                            <td>{{$data->volume}}</td>
                                            <td>{{number_format($data->harga_diskon,2,',','.')}}</td>
                                        </tr>                                        
                                    @endforeach
                                </table>
                            </div>
                        </div>                        
                    @endforeach

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
