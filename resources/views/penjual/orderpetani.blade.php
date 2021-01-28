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
                            <h1 class="card-title"><strong>Daftar Order Ke Petani</strong></h1>
                        </div>
                        <div class="col-6">
                            <button id="btnGroupDrop1" type="button" class="btn btn-success btn-sm float-right dropdown-toggle"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Tambah
                            </button>
                            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                <a class="dropdown-item" href="{{url('/Penjual/OrderPetani/Tambah')}}">Order Ke Petani</a>
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
                                    <th width="5%">No</th>
                                    <th>Invoice</th>
                                    <th>Petani</th>
                                    <th>Tanggal Order</th>
                                    <th>Tanggal Kirim</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data['orderPetani'] as $key => $orderPetani)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{$orderPetani->nomor_invoice}}</td>
                                        <td>{{$orderPetani->user->name}}</td>
                                        <td>{{$orderPetani->created_at->format('d M Y H:i') ?? "-"}}</td>
                                        <td>{{Carbon\Carbon::parse($orderPetani->tanggal_pre_order)->format('d M Y') ?? "-"}}</td>
                                        <td>
                                            @if($orderPetani->status == 1)
                                                <a class="badge badge-primary mb-1" href="{{url('/Penjual/OrderPetani/Konfirmasi/Terima/'.$orderPetani->nomor_invoice)}}">Order Ke Petani</a>
                                            @elseif($orderPetani->status == 2)
                                                <a class="badge badge-info mb-1" href="{{url('/Penjual/OrderPetani/Konfirmasi/Terima/'.$orderPetani->nomor_invoice)}}">Tunggu Konfirmasi</a>
                                            @elseif($orderPetani->status == 3)
                                                <a class="badge badge-success mb-1" href="{{url('/Penjual/OrderPetani/Konfirmasi/Terima/'.$orderPetani->nomor_invoice)}}">Selesai</a>
                                            @elseif($orderPetani->status == 7)
                                                <a class="badge badge-danger mb-1" href="{{url('/Penjual/OrderPetani/Konfirmasi/Terima/'.$orderPetani->nomor_invoice)}}">Batal</a>
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
