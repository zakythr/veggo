@extends('pembeli.layouts.layout_checkout')

@section('title')
    Ubah alamat
@endsection

@section('content')
<form method="POST" action="{{ url('/Pembeli/Profil/Edit/') }}">
    @csrf
    <div class="col-12">
        <br>
        <br>
        <div class="card">
            <div class="card-body">
                <div class="mb-4">
                    <div class="row">
                        <div class="col-12" align="center">
                            <h2>Ubah Profil</h2>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="mb-4">
                <input type="text" class="form-control" placeholder="Masukan Nama" name="name" value="{{$data['user']->name}}">
                </div>
                <div class="mb-4">
                    <input type="text" class="form-control" placeholder="Masukan Nomor Telepon" name="nomor_hp" value="{{$data['user']->nomor_hp}}">
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