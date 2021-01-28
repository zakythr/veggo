@extends('penjual.layouts.app')
@section('title','Ubah Inventaris')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">

                <div class="m-3">
                    @if($success = Session::get('success'))
                    <div>
                        <div class="alert alert-success">
                            {{$success}}
                        </div>
                    </div>                        
                    @elseif($error = Session::get('error'))
                    <div>
                        <div class="alert alert-warning">
                            {{$error}}
                        </div>
                    </div>
                    @endif
                </div>                    


                    <form method="POST" action="{{url('/Penjual/Inventaris/Ubah')}}" enctype="multipart/form-data">
                    <div class="row p-3">
                        <div class="col-md-8">
                            <h1 class="card-title"><strong>Ubah Inventaris</strong></h1>
                        </div>
                        <div class="col-md-4">
                        </div>
                    </div>

                    <hr>
                    <br>
                    
                        @csrf
                        
                        <input type="hidden" name="id" value="{{$data['barang']->id}}">
                        <input type="hidden" name="stok_awal" value="{{$data['barang']->stok}}">

                        <div class="form-row">
                            <div class="col-md-4 mb-3">
                                <label for="">Kode Produk</label>
                                <input type="text" class="form-control" name="kode" value="{{$data['barang']->kode}}" required readonly>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="">Nama Produk</label>
                                <input type="text" placeholder="Nama Produk" name="nama" value="{{$data['barang']->nama}}" class="form-control" required readonly>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="">Stok Inventaris</label>
                                <input type="number" placeholder="Stok Inventaris" name="stok" value="0" class="form-control" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-sm btn-primary float-right">Simpan</button>

                    </form>

                 </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>

</script>
    
@endsection