@extends('reseller.layouts.layout')

@section('title')
    Pembeli Offline
@endsection

@section('content')
<form method="POST" action="{{ url('Reseller/Checkout') }}">
@csrf
<div class="form-group">
    <input hidden name="tanggal" value="{{$data['tanggals']}}">
</div>
<div class="row">
    <div class="col-12"  align="center">
        <div class="card">
            <div class="card-body" style="overflow-x:auto;">
                <h1 class="card-title"><strong>Pembeli Offline</strong></h1>
                <div class="form-group">
                    <label for="inputState">Tanggal Pengiriman</label>
                    <select onChange="window.location.href=this.value" id="inputState" class="form-control" name="tanggal_pre_order">
                        <option value="kosong" disabled selected>Pilih Tanggal</option>
                        @foreach($data['tanggal'] as $pengiriman)
                        @if ($data['tanggals']==$pengiriman->tanggal_value)
                        <option value="{{url('Reseller/User/'.Auth::user()->id.'/'.$pengiriman->tanggal_value)}}" selected>{{$pengiriman->tanggal}}</option>    
                        @else
                        <option value="{{url('Reseller/User/'.Auth::user()->id.'/'.$pengiriman->tanggal_value)}}">{{$pengiriman->tanggal}}</option>
                        @endif
                        @endforeach
                    </select>
                </div>
                <table class="table">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Nama</th>
                            <th scope="col">Alamat</th>
                            <th scope="col">Nomor Telepon</th>
                            <th scope="col">Pilihan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data['keranjangReseller'] as $key => $userReseller)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td>{{$userReseller->name}}</td>
                                <td>{{$userReseller->alamat}}</td>
                                <td>{{$userReseller->nohp}}</td>
                                <td>
                                    <a class="badge badge-success mb-1 text-white" href="{{url('/Reseller/Users/Detail/'.$userReseller->id)}}">Detail</a>
                                    <a class="badge badge-danger mb-1 text-white" href="{{url('/Reseller/Users/Hapus/'.$userReseller->id)}}">Hapus</a>
                                </td>
                            </tr>                                   
                        @endforeach
                    </tbody>
                </table>
                <div align="center">
                    <button style="color:white;" class="btn btn-success">Checkout</button>
                </div>
            </div>
        </div>
    </div>
</div>
</form>
@endsection