@extends('penjual.layouts.app')
@section('title','Inventaris')
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
                            <h1 class="card-title"><strong>Persediaan {{$data['nama']}}</strong></h1>
                        </div>
                        <div class="col-6">
                            <button id="btnGroupDrop1" type="button" class="btn btn-primary btn-sm float-right dropdown-toggle"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Informasi Detail
                            </button>
                            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                <a class="dropdown-item" href="{{url('/Penjual/Inventaris/Tambah')}}">Tambah Inventaris</a>
                                <button class="dropdown-item" data-toggle="modal" data-target="#exampleModal">
                                    Keluar Masuk Barang
                                </button>                                
                            </div>                        
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
                                <th width="4%">No</th>
                                <th>Kode</th>
                                <th>Produk</th>
                                <th>Ketersediaan</th>
                                <th>Pilihan</th>
                                <th hidden>aaa</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data['inventaris'] as $key => $item)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{$item->kode}}</td>
                                    <td><a href="{{url('/Penjual/Inventaris/Ubah/'.$item->id)}}">{{$item->nama}}</a></td>
                                    <td>
                                        {{-- @if ($item->satuan=="Pcs")
                                            {{$item->stok ?? 0}} {{$item->satuan}}</td>
                                        @else
                                            {{$item->stok ?? 0}} {{$item->satuan}} | <strong>({{toKg($item->stok ?? 0)}} Kg)</strong></td>    
                                        @endif --}}
                                        {{$item->stok ?? 0}} Gram | <strong>({{toKg($item->stok ?? 0)}} Kg)</strong></td>    
                                        
                                    <td><a href="{{url('/Penjual/Inventaris/Ubah/'.$item->id)}}" class="btn btn-success">Tambah Manual</a></td>
                                    <td hidden>aaaa</td>
                                </tr>                                    
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Keluar & Masuk Produk</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <table class="data-table data-table-feature" >
                <thead>
                    <tr>
                        <th width="4%">No</th>
                        <th>Produk</th>
                        <th>Invoice</th>
                        <th>Status</th>
                        <th>Jumlah</th>
                        <th>Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data['keluar_masuk'] as $key => $item)
                        <tr>
                            <td>{{$key+1}}</td>
                            <td>{{$item->barang->nama}}</td>
                            <td>{{$item->transaksi->nomor_invoice ?? "-"}}</td>
                            @if($item->status == "IN")
                                <td class="text-success p-4"><strong>{{$item->status}}</strong></td>
                            @else
                                <td class="text-danger p-4"><strong>{{$item->status}}</strong></td>
                            @endif
                            <td>{{$item->jumlah}} Gram/Pcs</td>
                            <td>{{$item->created_at->format('d M Y H:i')}}</td>
                        </tr>                                    
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
@endsection
