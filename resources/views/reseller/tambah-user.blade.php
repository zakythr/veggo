@extends('reseller.layouts.layout')

@section('title')
    Tambah Pembeli Offline

@section('css')

@endsection

@section('content')
<form method="POST" action="{{ url('/Reseller/TambahUserSubmit') }}">
    @csrf
    <div class="col-12">
        <br>
        <br>
        <div class="card">
            <div class="card-body">
                <div class="mb-4">
                    <div class="row">
                        <div class="col-12" align="center">
                            <h2>Tambah Pembeli Offline</h2>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="mb-4">
                    <input type="text" class="form-control" placeholder="Masukan Nama" name="name">
                </div>
                <div class="mb-4">
                    <input type="text" class="form-control" placeholder="Nomor Telepon" name="nomor_hp">
                </div>

            </div>
        </div>
    </div>
    <br>
    <div align="center">
        <div align="center">
            <button style="color:white;" class="btn btn-success" type="submit">Tambahkan</button>
        </div>
    </div>
</form>
@endsection

@section('script')

@endsection