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
                            <h1 class="card-title"><strong>Kelola Paket</strong></h1>
                        </div>
                        <div class="col-6">
                            <a id="btnGroupDrop1" class="btn btn-success btn-sm float-right" href="{{url('/Penjual/Paket/Tambah')}}">
                                Tambah
                            </a>                          
                        </div>
                    </div>

                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <table class="data-table data-table-feature">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Kode | Nama</th>
                                    <th>Kategori</th>
                                    <th>Supplier</th>
                                    <th>Pilihan</th>
                                    <th hidden>Ketersediaan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($produks as $key => $produk)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>
                                            <a href="{{url('/Penjual/Paket/Ubah/'.$produk->id)}}">{{$produk->kode}} | {{$produk->nama}}</a> 
                                        </td>
                                        <td>{{$produk->kategori->kategori}}</td>
                                        <td>{{$produk->supplier->name}}</td>
                                        <td>
                                            {{-- @if($produk->flag==1)
                                            <form action="{{url('/Penjual/Kategori/Hapus')}}" method="POST">
                                                @csrf
                                                <input type="hidden" name="id" value="{{$produk->id}}">
                                                <button type="submit" class="btn btn-xs btn-danger m-1" disabled>Hapus</button>
                                            </form>   
                                            @else --}}
                                            <form action="{{url('/Penjual/Produk/Hapus')}}" method="POST">
                                                @csrf
                                                <input type="hidden" name="id" value="{{$produk->id}}">
                                                <button type="submit" class="btn btn-xs btn-danger m-1">Hapus</button>
                                            </form>   
                                            {{-- @endif --}}
                                        </td>
                                        <td hidden>{{$produk->stok ?? 0}} {{$produk->satuan}} | <strong>({{toKg($produk->stok ?? 0)}} Kg)</strong></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
