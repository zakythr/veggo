@extends('petani.layouts.app')
@section('title','Home')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">

                    <div class="row p-3">
                        <div class="col-6">
                            <h1 class="card-title"><strong>Klaim</strong></h1>
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
                                    <th>No</th>
                                    <th>Kode Klaim</th>
                                    <th>Dari</th>
                                    <th>Kepada</th>
                                    <th>Invoice</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data['klaim'] as $key => $klaim)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td><a href="{{url('/Petani/Klaim/Detail/'.$klaim->id)}}">{{$klaim->kode_klaim}}</a></td>
                                    <td>{{$klaim->dari->name}}</td>
                                    <td>{{$klaim->untuk->name}}</td>
                                    <td>{{$klaim->transaksi->nomor_invoice}}</td>
                                    <td>{{$klaim->created_at->format('d M Y | H:i')}}</td>
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
