@extends('penjual.layouts.app')

@section('title')
@section('content')
<form method="POST" action="{{ url('/Penjual/Pengaturan/TambahUser') }}">
    @csrf
    <div class="col-12">
        <br>
        <br>
        <div class="card">
            <div class="card-body">
                <div class="mb-4">
                    <div class="row">
                        <div class="col-12" align="center">
                            <h2>Tambah User</h2>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="mb-4">
                    <input type="text" class="form-control" placeholder="Masukan Nama" name="name" required>
                </div>
                <div class="mb-4">
                    <input type="text" class="form-control" placeholder="Nomor Telepon" name="nomor_hp" required>
                </div>
                <div class="mb-4">
                    <input type="text" class="form-control" placeholder="Email" name="email" required>
                </div>
                <div class="mb-4">
                    <input type="text" class="form-control" placeholder="Password" name="password" required>
                </div>             
                <div class="mb-4">
                    <label for="">Role</label>
                    <select class="form-control select2-single" name="role" required>
                        <option value="" disabled selected>Pilih Role</option>
                        <option value="3">Petani</option>
                        <option value="4">Reseller</option>
                        <option value="5">Kurir</option>
                    </select>
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