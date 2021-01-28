@extends('petani.layouts.app')
@section('title','Order Veggo')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">

                    <div class="row p-3">
                        <div class="col-6">
                            <h1 class="card-title"><strong>Daftar Pesanan</strong></h1>
                        </div>
                        <div class="col-6">
                            {{-- <button id="btnGroupDrop1" type="button" class="btn btn-primary btn-sm float-right dropdown-toggle"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Tambah
                            </button>
                            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                <a class="dropdown-item" href="{{url('/Penjual/PreOrder/Akumulasi')}}">Akumulasi</a>
                                <a class="dropdown-item" href="{{url('/Penjual/PreOrder/Tambah')}}">Pre Order</a>
                            </div>                         --}}
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
                                    <th>Invoice</th>
                                    <th hidden>Nama</th>
                                    <th>Tanggal Order</th>
                                    <th>Tanggal Kirim</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data['order'] as $key => $preorder)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>
                                            <a href="{{url('/Petani/Order/Konfirmasi/'.$preorder->id)}}">{{$preorder->nomor_invoice}}</a>  
                                        </td>
                                        <td hidden>{{$preorder->user->name}}</td>
                                        <td>{{$preorder->created_at}}</td>
                                        <td>{{$preorder->tanggal_pre_order}}</td>
                                        <td>
                                            @if($preorder->status == 1)
                                                <a class="badge badge-primary mb-1 text-white" href="{{url('/Petani/Order/Konfirmasi/'.$preorder->id)}}">Belum Diproses</a>
                                            @elseif($preorder->status == 2)
                                                <a class="badge badge-info mb-1 text-white" href="{{url('/Petani/Order/Konfirmasi/'.$preorder->id)}}">Tunggu Konfirmasi</a>
                                            @elseif($preorder->status == 3)
                                                <a class="badge badge-success mb-1" href="{{url('/Petani/Order/Konfirmasi/'.$preorder->id)}}">Selesai Diproses</a>
                                            @elseif($preorder->status == 7)
                                                <a class="badge badge-danger mb-1" href="{{url('/Petani/Order/Konfirmasi/'.$preorder->id)}}">Batal</a>
                                            @endif
                                        
                                        </td>
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
