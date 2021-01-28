@extends('penjual.layouts.app')
@section('title','Akumulasi')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card mb-1">
                <div class="card-body">
                    <div class="row p-1">
                        <div class="col-md-6">
                            <h1 class="card-title"><strong>Akumulasikan Pre Order</strong></h1>                            
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <form id="post_filter_tanggal" action="{{url('/Penjual/PreOrder/Akumulasi/Filter')}}" method="POST">
                                    @csrf
                                    <label for="">Filter Tanggal</label>
                                    <select id="filter_tanggal" name="tanggal" id="" class="form-control">
                                        @foreach ($data['date'] as $date)
                                            @if(session('tanggal_pre_order')!=null)
                                                @if($date->tanggal_pre_order == session('tanggal_pre_order'))
                                                    <option value="{{$date->tanggal_pre_order}}" selected>{{Carbon\Carbon::parse($date->tanggal_pre_order)->format('d M Y')}}</option>
                                                @else
                                                    <option value="{{$date->tanggal_pre_order}}">{{Carbon\Carbon::parse($date->tanggal_pre_order)->format('d M Y')}}</option>
                                                @endif
                                            @else
                                                @if($date->tanggal_pre_order == $data['current_date']))
                                                    <option value="{{$date->tanggal_pre_order}}" selected>{{Carbon\Carbon::parse($date->tanggal_pre_order)->format('d M Y')}}</option>
                                                @else
                                                    <option value="{{$date->tanggal_pre_order}}">{{Carbon\Carbon::parse($date->tanggal_pre_order)->format('d M Y')}}</option>
                                                @endif                                            
                                            @endif
                                        @endforeach
                                    </select>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @foreach ($data['preorder'] as $preorder)    
                <input type="hidden" name="tanggal_order" value="{{$preorder->tanggal_pre_order}}">           
                <div class="card mt-1" id="card_pre_order_{{$preorder->nomor_invoice}}">
                    <div class="card-body">
                        <div class="">
                            <a class="" href="#" data-toggle="collapse" data-target="#collapse_{{$preorder->nomor_invoice}}"
                                aria-expanded="true" aria-controls="collapse">
                                <div class="row pl-3 pr-3 pt-1">
                                    <div class="col-md-4">
                                        <p>{{$preorder->nomor_invoice}} | <strong>{{$preorder->user->name}}</strong></p>
                                        <p>{{$preorder->created_at->format('d M Y')}}</p>
                                        <p>@rupiah($preorder->total_bayar)</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p>{{$preorder->alamat->alamat}} {{$preorder->alamat->blok_nomor}}</p>
                                        <p>Pengiriman : GoSend</p>
                                        @if($preorder->status == 1)
                                            <p class="badge badge-success mb-1">Pre Order</p>
                                        @elseif($preorder->status == 4)
                                            <p class="badge badge-info mb-1">Siap Kirim</p>
                                        @elseif($preorder->status == 7)
                                            <p class="badge badge-danger mb-1">Batal</p>
                                        @endif
                                        @if($preorder->isAlreadyPay == 1)
                                            <p class="badge badge-info mb-1 text-white" data-toggle="modal" data-target="#pembayaran_modal_{{$preorder->nomor_invoice}}">Tunggu Konfirmasi</p>
                                        @elseif($preorder->isAlreadyPay == 2)
                                            <p class="badge badge-danger mb-1 text-white" data-toggle="modal" data-target="#pembayaran_modal_{{$preorder->nomor_invoice}}">Gagal</p>
                                        @elseif($preorder->isAlreadyPay == 3)
                                            <p class="badge badge-success mb-1 text-white" data-toggle="modal" data-target="#pembayaran_modal_{{$preorder->nomor_invoice}}">Lunas</p>
                                        @else
                                            <p class="badge badge-warning mb-1 text-white" data-toggle="modal" data-target="#pembayaran_modal_{{$preorder->nomor_invoice}}">Belum Lunas</p>
                                        @endif                                        
                                    </div>     
                                    <div class="col-md-2">
                                        <button class="btn btn-xs btn-outline-danger pull-right" onclick="hapusPreOrder('{{$preorder->nomor_invoice}}','{{$preorder->id}}')">Hapus</button>
                                        <button class="btn btn-xs btn-danger pull-right" onclick="batalkanPreOrder('{{$preorder->nomor_invoice}}','{{$preorder->id}}')">Batalkan</button>
                                    </div>
                                </div>
                            </a>

                            <div id="collapse_{{$preorder->nomor_invoice}}" class="collapse hide" data-parent="#accordion">
                                <table class="table table-bordered">
                                    <tr>
                                        <th>Kode</th>
                                        <th>Nama</th>
                                        <th>Volume</th>
                                        <th></th>
                                    </tr>
                                        @foreach ($preorder->detailTransaksi as $detail)
                                            @if($detail->is_canceled_by_veggo == 0 && $detail->is_exclude_rekap == 0)
                                                <tr id="row_detail_pre_order_{{$detail->barang->kode}}_{{$preorder->nomor_invoice}}">
                                                    <td>{{$detail->barang->kode}}</td>
                                                    <td>{{$detail->barang->nama}} | {{$detail->barang->jenis}}</td>
                                                    <td>
                                                        @if($detail->barang->jenis == "Kemas")
                                                            {{$detail->volume}} x {{$detail->bobot_kemasan}} {{$detail->barang->satuan}}
                                                        @else
                                                            {{$detail->volume}} {{$detail->barang->satuan}}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-xs btn-outline-danger m-1" onclick="batalkanDetailPreOrder('{{$detail->barang->kode}}','{{$detail->id}}','{{$preorder->nomor_invoice}}')">Batalkan</button>
                                                    </td>
                                                </tr>                                            
                                            @endif
                                        @endforeach
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            <a class="btn btn-sm btn-success float-right m-2" href="{{url('Penjual/PreOrder/Akumulasi/Rekap/'.session('tanggal_pre_order'))}}">Akumulasi</a>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<script>
    $( "#filter_tanggal" ).change(function() {
    $("#post_filter_tanggal").submit();
    });

    function hapusDetailPreOrder(selector,id,invoice){
        var r = confirm("Yakin Hapus Detail Pre Order?");
        if (r == true) {
            _hapusDetailPreOrder(selector,id,invoice)
        } else {

        }
    }

    function hapusPreOrder(selector,id){
        var r = confirm("Yakin Hapus Pre Order?");
        if (r == true) {
            _hapusPreOrder(selector,id)
        }
    }

    function batalkanDetailPreOrder(selector,id,invoice){
        var r = confirm("Yakin Batalkan Detail Pre Order?");
        if (r == true) {
            _batalkanDetailPreOrder(selector,id,invoice)
        } else {

        }
    }

    function batalkanPreOrder(selector,id){
        var r = confirm("Yakin Batalkan Pre Order?");
        if (r == true) {
            _batalkanPreOrder(selector,id)
        }
    }

    function _batalkanPreOrder(selector,id)
    {
        $.ajax({
            type:'POST',
            url:'Akumulasi/Batalkan',
            data:{
                id: id,
                _token: '{{ csrf_token() }}',
            },
            success:function(data)
            {
                console.log(data);
                if(data == 1)
                {
                    preOrderSelector = "#card_pre_order_"+selector
                    $(preOrderSelector).remove()
                }
            }
        });
    }    

    function _batalkanDetailPreOrder(selector,id,invoice)
    {
        $.ajax({
            type:'POST',
            url:'Akumulasi/Batalkan/Detail',
            data:{
                id: id,
                _token: '{{ csrf_token() }}',
            },
            success:function(data)
            {
                console.log(data);
                if(data == 1)
                {
                    detailPreOrderSelector = "#row_detail_pre_order_"+selector+"_"+invoice
                    $(detailPreOrderSelector).remove()
                }else{}
            }
        });
    }    

    function _hapusPreOrder(selector,id)
    {
        $.ajax({
            type:'POST',
            url:'Akumulasi/Hapus',
            data:{
                id: id,
                _token: '{{ csrf_token() }}',
            },
            success:function(data)
            {
                console.log(data);
                if(data == 1)
                {
                    preOrderSelector = "#card_pre_order_"+selector
                    $(preOrderSelector).remove()
                }
            }
        });
    }    

    function _hapusDetailPreOrder(selector,id,invoice)
    {
        $.ajax({
            type:'POST',
            url:'Akumulasi/Hapus/Detail',
            data:{
                id: id,
                _token: '{{ csrf_token() }}',
            },
            success:function(data)
            {
                console.log(data);
                if(data == 1)
                {
                    detailPreOrderSelector = "#row_detail_pre_order_"+selector+"_"+invoice
                    $(detailPreOrderSelector).remove()
                }else{}
            }
        });
    }    

</script>
@endsection
