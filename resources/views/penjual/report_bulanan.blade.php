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
                        <h1 class="card-title"><strong>Report Bulanan | {{$data['bulan']}} {{$data['tahun']}}</strong></h1>
                        </div>
                        <div class="col-5">
                            <select name="tanggal" class="form-control" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
                                <option selected disabled>Tanggal Order Barang</option>
                                @foreach ($data['tanggal'] as $tanggal)
                                    @if ($tanggal->bulanNama == $data['bulan'] && $tanggal->tahun == $data['tahun'])
                                        <option value="{{$tanggal->bulan}}" selected >{{$tanggal->bulanNama}} {{$tanggal->tahun}}</option>
                                    @else            
                                        <option value="{{url('/Penjual/ReportBulanan/'.$tanggal->bulan.'/'.$tanggal->tahun)}}" >{{$tanggal->bulanNama}} {{$tanggal->tahun}}</option>
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
                    {{-- @foreach ($data['hasil'] as $preorder)
                    {{$preorder}}
                    @endforeach --}}
{{-- {{$data['hasil'][0]['nama']}} --}}
                    <table class="data-table data-table-feature" >
                            <thead>
                                <tr>
                                    <th>Nama Barang</th>
                                    <th>Pemasukan</th>
                                    <th>Pengeluaran</th>
                                    <th>Laba/Rugi</th>
                                    <th hidden>Jumlah Klaim</th>
                                    <th hidden>Jumlah Bersih</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data['hasil'] as $key => $preorder)
                                    <tr>
                                        <td>{{$preorder['nama']}}</td>
                                        <td>Rp. {{ number_format($preorder['pemasukan'],0,',','.') }},-</td>
                                        <td>Rp. {{ number_format($preorder['pengeluaran'],0,',','.') }},-</td>
                                        <td>
                                            @if($preorder['laba']<0)
                                            Rugi Rp. {{ number_format(abs($preorder['laba']),0,',','.') }},-
                                            @else
                                            Laba Rp. {{ number_format(abs($preorder['laba']),0,',','.') }},-
                                            @endif
                                        </td>
                                        <td hidden>Gram</td>
                                        <td hidden>Gram</td>            
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <h3>Total</h3>
                        <table class="table table-bordered" >
                            <thead>
                                <tr>
                                    <th>Pemasukan</th>
                                    <th>Pengeluaran</th>
                                    <th>Laba/Rugi</th>
                                </tr>
                            </thead>
                            <tbody>                                
                                <tr>
                                    <td>Rp. {{ number_format($data['pemasukanTotal'],0,',','.') }},-</td>
                                    <td>Rp. {{ number_format($data['pengeluaranTotal'],0,',','.') }},-</td>
                                    <td>
                                        @if($data['labaTotal']<0)
                                        Rugi Rp. {{ number_format(abs($data['labaTotal']),0,',','.') }},-</td>
                                        @else
                                        Laba Rp. {{ number_format($data['labaTotal'],0,',','.') }},-</td>
                                        @endif
                                </tr>
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
