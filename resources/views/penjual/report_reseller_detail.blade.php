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
                            <h1 class="card-title"><strong>Pembeli Offline</strong></h1>
                        </div>
                    </div>
                    {{-- {{$collection}} --}}
                    <table class="data-table data-table-feature" >
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Berat</th>
                                <th>Jumlah</th>
                                <th>Total</th>
                                <th>Rabat</th>
                                <th hidden>Jumlah Bersih</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data['kemas'] as $key => $preorder)
                                <tr>
                                    <td>{{$preorder->nama}}</td>
                                    <td>{{$preorder->berat}}</td>
                                    <td>{{$preorder->jumlah}}</td>
                                    <td>Rp. {{ number_format(abs($preorder->total),0,',','.') }},-</td>
                                    <td>Rp. {{ number_format(abs($preorder->rabat),0,',','.') }},-</td>
                                    <td hidden>Gram</td>            
                                </tr>
                            @endforeach
                            @foreach ($data['timbang'] as $key => $preorder)
                                <tr>
                                    <td>{{$preorder->nama}}</td>
                                    <td>{{$preorder->berat}}</td>
                                    <td>{{$preorder->jumlah}}</td>
                                    <td>Rp. {{ number_format(abs($preorder->total),0,',','.') }},-</td>
                                    <td>Rp. {{ number_format(abs($preorder->rabat),0,',','.') }},-</td>
                                    <td hidden>Gram</td>  
                                </tr>
                            @endforeach
                            @foreach ($data['paket'] as $key => $preorder)
                                <tr>
                                    <td>{{$preorder->nama}}</td>
                                    <td>-</td>
                                    <td>{{$preorder->jumlah}}</td>
                                    <td>Rp. {{ number_format(abs($preorder->total),0,',','.') }},-</td>
                                    <td>Rp. {{ number_format(abs($preorder->rabat),0,',','.') }},-</td>
                                    <td hidden>Gram</td>            
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <h3>Total</h3>
                    <table class="table table-bordered" >
                        <thead>
                            <tr>
                                <th>Total Sebelum Rabat</th>
                                <th>Rabat</th>
                                <th>Total Akhir</th>
                            </tr>
                        </thead>
                        <tbody>                                
                            <tr>
                                <td>Rp. {{ number_format($data['total'][0]->total,0,',','.') }},-</td>
                                <td>Rp. {{ number_format($data['total'][0]->rabat,0,',','.') }},-</td>
                                <td>Rp. {{ number_format($data['total'][0]->total_akhir,0,',','.') }},-</td>
                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
