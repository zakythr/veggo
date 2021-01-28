@extends('reseller.layouts.layout')

@section('title')
    Detail Transaksi
@endsection

@section('content')
<div class="row">
    <div class="col-12"  align="center">
        <div class="card">
            <div class="card-body">
                <h1 class="card-title"><strong>Detail Transaksi</strong></h1>
                <table class="table">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Nama</th>
                            <th scope="col">Pilihan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($detail['transaksi'] as $key => $userReseller)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td>{{$userReseller->name}}</td>
                                <td>
                                    <a class="badge badge-primary mb-1 text-white" href="{{url('/Reseller/Users/Detail/'.$userReseller->id)}}">Detail</a>
                                </td>
                            </tr>                                   
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection