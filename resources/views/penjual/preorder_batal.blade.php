@extends('penjual.layouts.app')
@section('title','Home')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                <form id="form_filter" action="{{url('Penjual/PreOrder/Filter')}}" method="POST">
                @csrf

                    <div class="row p-3">
                        <div class="col-7">
                            <h1 class="card-title"><strong>Batal | {{Carbon\Carbon::parse($data['filter_tanggal'])->format('D, d M Y')}}</strong></h1>
                        </div>
                        <div class="col-5">
                            <select name="tanggal" class="form-control" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
                                <option selected disabled>Tanggal Pre Order</option>
                                @foreach ($data['tanggal'] as $tanggal)
                                    @if ($tanggal->tanggal_pre_order == $data['filter_tanggal'])
                                        <option value="{{$tanggal->tanggal_pre_order}}" selected >{{Carbon\Carbon::parse($tanggal->tanggal_pre_order)->format('D, d M Y')}} </option>
                                    @else            
                                        <option value="{{url('/Penjual/PreOrder/Batal/Tanggal/'.$tanggal->tanggal_pre_order)}}" >{{Carbon\Carbon::parse($tanggal->tanggal_pre_order)->format('D, d M Y')}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        
                </div>
                </form>

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
                                    <th>Nama</th>
                                    <th>Tanggal Order</th>
                                    <th>Tanggal Kirim</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data['preorder'] as $key => $preorder)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{$preorder->nomor_invoice}}</td>
                                        <td>{{$preorder->user->name}}</td>
                                        <td>{{$preorder->created_at->format('d M Y | H.i')}}</td>
                                        <td>{{Carbon\Carbon::parse($preorder->tanggal_pre_order)->format('d M Y')}}</td>
                                        <td>
                                            <a class="badge badge-danger mb-1 text-white" >Batal</a>
                                            @if($preorder->isAlreadyPay == 1)
                                                <a class="badge badge-info mb-1 text-white" >Tunggu Konfirmasi</a>
                                            @elseif($preorder->isAlreadyPay == 2)
                                                <a class="badge badge-danger mb-1 text-white" >Gagal</a>
                                            @elseif($preorder->isAlreadyPay == 3)
                                                <a class="badge badge-success mb-1 text-white" >Lunas</a>
                                            @else
                                                <a class="badge badge-warning mb-1 text-white" >Belum Lunas</a>
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
<script>
    function applyFilter(){
        $('#form_filter').submit()
    }
</script>
    
@endsection
