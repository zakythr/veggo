@extends('pembeli.layouts.layout_checkout')

@section('title')
    Ubah Rekening
@endsection

@section('css')

@endsection

@section('content')
<form method="POST" action="{{ url('/Penjual/Pengaturan/Rekening') }}">
    @csrf
    <div class="col-12">
        <br>
        <br>
        <div class="card">
            <div class="card-body">
                <div class="mb-4">
                    <div class="row">
                        <div class="col-12" align="center">
                            <h2>Ubah Rekening</h2>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="mb-4">
                <input type="text" class="form-control" placeholder="Nomor Rekening" name="nomor_rek" value="{{$data['nomor_rek']}}">
                </div>
                <div class="mb-4">
                    <input type="text" class="form-control" placeholder="Nama Bank" name="bank" value="{{$data['bank']}}">
                </div>
                <div class="mb-4">
                    <input type="text" class="form-control" placeholder="Atas Nama" name="atas_nama" value="{{$data['atas_nama']}}">
                </div>
            </div>
        </div>
    </div>
    <br>
    <div align="center">
        <div align="center">
            <button style="color:white;" class="btn btn-success" type="submit">Ubah</button>
        </div>
    </div>
</form>
@endsection

@section('script')

@endsection