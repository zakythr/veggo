@extends('penjual.layouts.app')
@section('title','Pengiriman')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row p-3">
                        <div class="col-7">
                            <h1 class="card-title">Selesai | <strong>{{Carbon\Carbon::parse($data['filter_tanggal'])->format('D, d M Y')}}</strong></h1>
                        </div>
                        <div class="col-5">
                            <select name="tanggal_kirim" class="form-control" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
                                <option selected disabled>Tanggal Pengiriman</option>
                                @foreach ($data['tanggal'] as $tanggal)
                                    @if ($tanggal->tanggal_pre_order == $data['filter_tanggal'])
                                        <option value="{{$tanggal->tanggal_pre_order}}" selected >{{Carbon\Carbon::parse($tanggal->tanggal_pre_order)->format('D, d M Y')}}</option>
                                    @else            
                                        <option value="{{url('/Penjual/PreOrder/Selesai/Tanggal/'.$tanggal->tanggal_pre_order)}}" >{{Carbon\Carbon::parse($tanggal->tanggal_pre_order)->format('D, d M Y')}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>


                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <hr>
                    <table class="data-table data-table-feature">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>Invoice</th>
                                <th>Nama</th>
                                <th>Tanggal Pengiriman</th>
                                <th>Kurir</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data['preorder'] as $key => $preorder)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{$preorder->nomor_invoice}}</td>
                                    <td>{{$preorder->user->name}}</td>
                                    <td>{{$preorder->tanggal_pengiriman ?? 'Belum Dikirim'}}</td>
                                    <td>{{$preorder->kurir->name ?? $preorder->id_kurir }}</td>
                                    <td>
                                        @if($preorder->status == 6)
                                            <a class="badge badge-secondary mb-1" href="#">Dalam Pengiriman</a>
                                        @elseif($preorder->status == 7)
                                            @if($preorder->is_confirm_finish_byuser==0)
                                            <a class="badge badge-success mb-1" href="#">Tunggu Konfirmasi</a>
                                            @else
                                            <a class="badge badge-success mb-1" href="#">Diterima</a>
                                            @endif
                                        @endif
                                        @if($preorder->isAlreadyPay == 1)
                                            <a class="badge badge-info mb-1 text-white" data-toggle="modal" data-target="#pembayaran_modal_{{$preorder->nomor_invoice}}">Tunggu Konfirmasi</a>
                                        @elseif($preorder->isAlreadyPay == 2)
                                            <a class="badge badge-danger mb-1 text-white" data-toggle="modal" data-target="#pembayaran_modal_{{$preorder->nomor_invoice}}">Gagal</a>
                                        @elseif($preorder->isAlreadyPay == 3)
                                            <a class="badge badge-success mb-1 text-white" data-toggle="modal" data-target="#pembayaran_modal_{{$preorder->nomor_invoice}}">Lunas</a>
                                        @else
                                            <a class="badge badge-warning mb-1 text-white" data-toggle="modal" data-target="#pembayaran_modal_{{$preorder->nomor_invoice}}">Belum Lunas</a>
                                        @endif
                                        @if (strpos($preorder->nomor_invoice, 'VGRES') === 0)
                                                <a class="badge badge-info mb-1 text-white" href="{{url('/Penjual/PembeliOffline/'.$preorder->id)}}">Detail</a>
                                            @else
                                                <a class="badge badge-info mb-1 text-white" href="{{url('/Penjual/OrderDetail/'.$preorder->id)}}">Detail</a>
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
@section('script')

@endsection