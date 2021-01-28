@extends('petani.layouts.app')
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
                            <h1 class="card-title">Tagihan ke VEGGO | {{Carbon\Carbon::parse($data['filter_tanggal'])->format('D, d M Y')}}</h1>
                        </div>
                        <div class="col-5">
                            <select name="tanggal" class="form-control" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
                                <option selected disabled>Tanggal Order Barang</option>
                                @foreach ($data['tanggal'] as $tanggal)
                                    @if ($tanggal->tanggal_pre_order == $data['filter_tanggal'])
                                        <option value="{{$tanggal->tanggal_pre_order}}" selected >{{Carbon\Carbon::parse($tanggal->tanggal_pre_order)->format('D, d M Y')}} ({{$tanggal->total}})</option>
                                    @else            
                                        <option value="{{url('/Petani/Pembayaran/'.$tanggal->tanggal_pre_order)}}" >{{Carbon\Carbon::parse($tanggal->tanggal_pre_order)->format('D, d M Y')}}</option>
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
                                    <th>Nama Barang</th>
                                    <th>Harga</th>
                                    <th>Jumlah Kirim | Jumlah terima</th>
                                    <th>Jumlah Klaim</th>
                                    <th>Jumlah Bersih</th>
                                    <th hidden>Pilihan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data['transaksi'] as $key => $preorder)
                                    <tr>
                                        <td>{{$preorder->nama}}</td>
                                        <td>Rp. {{ number_format($preorder->harga,0,',','.') }},-</td>
                                        <td>{{$preorder->jumlah_kirim}} Gram | {{$preorder->jumlah_terima}} Gram</td>
                                        <td>{{$preorder->volume_klaim}} Gram</td>
                                        <td>{{$preorder->jumlah_terima - $preorder->volume_klaim}} Gram</td>
                                        <td hidden><a class="badge badge-success mb-1 text-white" >Konfirmasi Bayar</a></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <h3>Total Harga : Rp. {{ number_format($data['total'],0,',','.') }},-</h3>
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
