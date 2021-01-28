@extends('pembeli.layouts.layout_checkout')

@section('title')
    Ubah alamat
@endsection

@section('css')

@endsection

@section('content')
<form method="POST" action="{{ url('/Pembeli/UbahAlamatSubmit/'.$alamat[0]->id) }}">
    @csrf
    <div class="col-12">
        <br>
        <br>
        <div class="card">
            <div class="card-body">
                <div class="mb-4">
                    <div class="row">
                        <div class="col-12" align="center">
                            <h2>Ubah Alamat</h2>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="mb-4">
                <input type="text" class="form-control" placeholder="Masukan Alamat" name="alamat" value="{{$alamat[0]->alamat}}">
                </div>
                <div class="mb-4">
                    <input type="text" class="form-control" placeholder="Nomor Alamat" name="blok_nomor" value="{{$alamat[0]->blok_nomor}}">
                </div>
                <div class="mb-4">
                    <input type="text" class="form-control" placeholder="daerah" name="daerah" value="{{$alamat[0]->daerah}}">
                </div>
                <div class="mb-4">
                    <input type="text" class="form-control" placeholder="Informasi Tambahan" name="kotkab" value="{{$alamat[0]->info_tambahan}}">
                </div>             
                <div class="mb-4">
                    <input type="text" class="form-control" placeholder="Masukan Kode Pos" name="kodepos" value="{{$alamat[0]->kodepos}}">
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