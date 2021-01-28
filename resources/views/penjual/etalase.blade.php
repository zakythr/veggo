@extends('penjual.layouts.app')
@section('title','Home')
@php
function toKg($volume){
    $volume = (int) $volume;
    return $volume/1000;
}
@endphp
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    
                    <div class="row p-3">
                        <div class="col-6">
                            <h1 class="card-title"><strong>Etalase</strong></h1>
                        </div>
                        <div class="col-6">
                            {{-- <a href="{{url('/Penjual/Etalase/Kelola')}}" class="btn btn-sm btn-primary  float-right">Ubah</a> --}}
                        </div>
                    </div>
                    {{-- {{$collection}} --}}
                    @foreach ($collection as $key => $item)
                        <div class="border">
                            <button class="btn btn-link" data-toggle="collapse" data-target="#collapse_{{$key}}"
                                aria-expanded="true" aria-controls="collapse">
                                <h5>{{$item['kategori']}}</h5>
                            </button>

                            <div id="collapse_{{$key}}" class="collapse hide " data-parent="#accordion">
                                <table class="table table-bordered p-4">
                                    <tr>
                                        <th>Kode</th>
                                        <th>Nama</th>
                                        <th>Harga Jual</th>
                                        <th>Ketersediaan</th>
                                    </tr>
                                    @foreach ($item['data'] as $data)
                                        <tr>
                                            <td>{{$data->kode}}</td>
                                            <td>{{$data->nama}}</td>
                                            <td>{{$data->harga_jual}}</td>
                                            <td>{{$data->stok ?? 0}} {{$data->satuan}} | <strong>({{toKg($data->stok ?? 0)}} Kg)</strong></td>
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
