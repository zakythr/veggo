@extends('reseller.layouts.layout')

@section('title')
    Tambah Alamat

@section('css')

@endsection

@section('content')
<form method="POST" action="{{ url('/Reseller/TambahAlamatSubmit') }}">
    @csrf
    <div class="col-12">
        <br>
        <br>
        <div class="card">
            <div class="card-body">
                <div class="mb-4">
                    <div class="row">
                        <div class="col-12" align="center">
                            <h2>Tambah Alamat</h2>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="mb-4">
                    <input type="text" class="form-control" placeholder="Masukan Alamat" name="alamat">
                </div>
                <div class="mb-4">
                    <input type="text" class="form-control" placeholder="Nomor Alamat" name="blok_nomor">
                </div>
                <div class="mb-4">
                    <input type="text" class="form-control" placeholder="daerah" name="daerah">
                </div>
                <div class="mb-4">
                    <input type="text" class="form-control" placeholder="Informasi Tambahan" name="kotkab">
                </div>             
                <div class="mb-4">
                    <input type="text" class="form-control" placeholder="Masukan Kode Pos" name="kodepos">
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